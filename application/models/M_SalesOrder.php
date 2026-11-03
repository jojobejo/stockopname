<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * M_SalesOrder.php
 *
 * GUDANG: v_available_stock.gudang = tb_karyawan.wilayah (integer)
 *         tbso_stock_reservation.gudang_id harus sama nilainya (integer).
 *
 * FORMAT exp_date di view: DD/MM/YYYY (text/varchar)
 *   Form mengirim YYYY-MM-DD → dikonversi DD/MM/YYYY saat query/simpan.
 *
 * STOK BERKURANG:
 *   v_available_stock JOIN tbso_stock_reservation ON:
 *     kd_barang = kd_barang
 *     exp_date  = exp_date   ← harus sama format (DD/MM/YYYY)
 *     gudang    = gudang_id  ← harus sama nilai (integer wilayah)
 *     status    = 'active'
 */
class M_SalesOrder extends CI_Model
{
    const BATAS_TONASE   = 6;
    const BATAS_KUBIKASI = 9;

    // ----------------------------------------------------------------
    // HELPER tanggal
    // ----------------------------------------------------------------
    private function _normalizeDate($raw)
    {
        $raw = trim((string)$raw);
        if (!$raw) return null;
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) return $raw;
        if (preg_match('/^(\d{2})[\/\-](\d{2})[\/\-](\d{4})$/', $raw, $m))
            return $m[3].'-'.$m[2].'-'.$m[1];
        return $raw;
    }

    // YYYY-MM-DD → DD/MM/YYYY (format kolom exp_date di view & reservasi)
    private function _toViewDate($ymd)
    {
        $ymd = $this->_normalizeDate($ymd);
        if (!$ymd) return '';
        $p = explode('-', $ymd);
        return count($p) === 3 ? $p[2].'/'.$p[1].'/'.$p[0] : $ymd;
    }

    // ----------------------------------------------------------------
    // GENERATE NOMOR SO
    // ----------------------------------------------------------------
    public function generate_no_faktur()
    {
        $prefix = 'INV' . date('dmY');

        $this->db->like('no_faktur', $prefix, 'after');
        $this->db->order_by('no_faktur', 'DESC');
        $this->db->limit(1);
        $row = $this->db->get('tbso_sales_order')->row();

        if ($row) {
            $last = (int) substr($row->no_faktur, -4);
            return $prefix . str_pad($last + 1, 4, '0', STR_PAD_LEFT);
        }
        return $prefix . '0001';
    }

    // ----------------------------------------------------------------
    // CUSTOMER
    // ----------------------------------------------------------------
    public function get_customers()
    {
        return $this->db->order_by('nama_customer', 'ASC')
                        ->get('tb_customer')
                        ->result_array();
    }

    public function get_customer($id)
    {
        return $this->db->get_where('tb_customer', ['id' => $id])->row_array();
    }

    // ----------------------------------------------------------------
    // STOK
    // ----------------------------------------------------------------
    public function get_available_stock($gudang_id = null, $kd_barang = null)
    {
        $this->db->where('available_stock >', 0);
        if (!empty($gudang_id)) $this->db->where('gudang', $gudang_id);
        if ($kd_barang)         $this->db->where('kode_barang', $kd_barang);
        return $this->db->get('v_available_stock')->result_array();
    }

    /**
     * Cek stok satu item dari tberp_stock_batch.
     * exp_date input (YYYY-MM-DD) akan dicocokkan dengan kolom expired_date.
     * Available = qty_on_hand - qty_reserved
     * gudang_id kosong → SUM semua gudang (fallback saat session belum set).
     */
    public function cek_stock($kd_barang, $exp_date, $gudang_id)
    {
        $ymd = $this->_normalizeDate($exp_date);

        if (!empty($gudang_id)) {
            $sql = "SELECT kd_barang, gudang_id, no_lot, expired_date,
                           qty_on_hand, qty_reserved,
                           (qty_on_hand - COALESCE(qty_reserved, 0)) AS available_stock
                    FROM tberp_stock_batch
                    WHERE kd_barang = ? AND expired_date = ? AND gudang_id = ?
                    LIMIT 1";
            return $this->db->query($sql, [$kd_barang, $ymd, $gudang_id])->row_array();
        } else {
            $sql = "SELECT kd_barang, gudang_id, no_lot, expired_date,
                           SUM(qty_on_hand) AS qty_on_hand,
                           SUM(COALESCE(qty_reserved, 0)) AS qty_reserved,
                           (SUM(qty_on_hand) - SUM(COALESCE(qty_reserved, 0))) AS available_stock
                    FROM tberp_stock_batch
                    WHERE kd_barang = ? AND expired_date = ?
                    GROUP BY kd_barang, expired_date
                    LIMIT 1";
            return $this->db->query($sql, [$kd_barang, $ymd])->row_array();
        }
    }

    // ----------------------------------------------------------------
    // MASTER BARANG
    // ----------------------------------------------------------------
    public function get_detail_barang($kd_barang)
    {
        $row = $this->db->get_where('tb_master_barang_all', ['kd_barang' => $kd_barang])->row_array();
        if (!$row) return null;
        return $this->_normalize_barang($row);
    }

    private function _get_master_bulk(array $kd_list)
    {
        if (empty($kd_list)) return [];
        $rows = $this->db->where_in('kd_barang', array_unique($kd_list))
                         ->get('tb_master_barang_all')
                         ->result_array();
        $map = [];
        foreach ($rows as $r) { $map[$r['kd_barang']] = $this->_normalize_barang($r); }
        return $map;
    }

    private function _normalize_barang(array $row)
    {
        $berat = 0;
        foreach (['berat','berat_gram','weight','berat_satuan','gr'] as $c) {
            if (array_key_exists($c,$row) && $row[$c]!==null && $row[$c]!=='') { $berat=(float)$row[$c]; break; }
        }
        $kubikasi = 0;
        foreach (['kubikasi','kubikasi_m3','volume','kubik','cbm'] as $c) {
            if (array_key_exists($c,$row) && $row[$c]!==null && $row[$c]!=='') { $kubikasi=(float)$row[$c]; break; }
        }
        $hpp = 0;
        foreach (['hpp','harga_pokok','cost','cogs','h_pokok'] as $c) {
            if (array_key_exists($c,$row) && $row[$c]!==null && $row[$c]!=='') { $hpp=(float)$row[$c]; break; }
        }

        $p = (int)($row['p'] ?? $row['panjang'] ?? $row['length'] ?? 0);
        $l = (int)($row['l'] ?? $row['lebar']   ?? $row['width']  ?? 0);
        $t = (int)($row['t'] ?? $row['tinggi']  ?? $row['height'] ?? 0);

        $isi = 0;
        foreach (['isi_box','qty_isi','isi','isi_per_box','qty_per_box','jumlah_isi'] as $c) {
            if (array_key_exists($c,$row) && (int)$row[$c]>0) { $isi=(int)$row[$c]; break; }
        }
        if ($isi < 1 && $p > 0 && $l > 0 && $t > 0) $isi = $p * $l * $t;
        if ($isi < 1) $isi = 1;

        $satuan = '';
        foreach (['satuan','unit','uom','satuan_kecil'] as $c) {
            if (!empty($row[$c])) { $satuan = (string)$row[$c]; break; }
        }

        $row['berat_gram']  = $berat;
        $row['kubikasi_m3'] = $kubikasi;
        $row['hpp']         = $hpp;
        $row['p']           = $p;
        $row['l']           = $l;
        $row['t']           = $t;
        $row['isi_per_box'] = $isi;
        $row['satuan']      = $satuan;

        return $row;
    }

    // ----------------------------------------------------------------
    // STOK + DIMENSI — AJAX /get_stock
    // PERUBAHAN: Menggunakan tberp_stock_ledger dan tberp_stock_batch
    //            Available = qty_on_hand - qty_reserved
    //            JOIN with master barang to get nama_barang
    // ----------------------------------------------------------------
    public function get_available_stock_with_dimensi($gudang_id = null, $kd_barang = null)
    {
        // First, try to get data from tberp_stock_batch
        $this->db->select('sb.*, mb.nama_barang,
                           (sb.qty_on_hand - COALESCE(sb.qty_reserved, 0)) AS available_stock');
        $this->db->from('tberp_stock_batch sb');
        $this->db->join('tb_master_barang_all mb', 'mb.kd_barang = sb.kd_barang', 'left');
        $this->db->where('(sb.qty_on_hand - COALESCE(sb.qty_reserved, 0)) >', 0);
        
        if (!empty($kd_barang)) {
            $this->db->where('sb.kd_barang', $kd_barang);
        }
        if (!empty($gudang_id)) {
            $this->db->where('sb.gudang_id', $gudang_id);
        }
        
        $stocks = $this->db->get()->result_array();

        // If tberp_stock_batch is empty, fallback to v_available_stock
        if (empty($stocks)) {
            $this->db->where('available_stock >', 0);
            if ($kd_barang)         $this->db->where('kode_barang', $kd_barang);
            if (!empty($gudang_id)) $this->db->where('gudang', $gudang_id);
            $stocks = $this->db->get('v_available_stock')->result_array();
        }

        if (empty($stocks)) return [];

        $kd_list = array_column($stocks, 'kd_barang');
        $master  = $this->_get_master_bulk($kd_list);

        foreach ($stocks as &$row) {
            $kd = $row['kd_barang'];
            $m  = isset($master[$kd]) ? $master[$kd] : [];

            // Use master data if nama_barang is empty
            if (empty($row['nama_barang']) && !empty($m['nama_barang'])) {
                $row['nama_barang'] = $m['nama_barang'];
            }

            $row['berat_gram']  = $m['berat_gram']  ?? 0;
            $row['kubikasi_m3'] = $m['kubikasi_m3'] ?? 0;
            $row['hpp']         = $m['hpp']         ?? 0;
            $row['p']           = $m['p']           ?? 0;
            $row['l']           = $m['l']           ?? 0;
            $row['t']           = $m['t']           ?? 0;
            $row['isi_per_box'] = $m['isi_per_box'] ?? 1;
            $row['satuan']      = $m['satuan'] ?? ($row['satuan'] ?? '');

            // Normalize expired_date to YYYY-MM-DD for form/JS
            // Handle both tberp_stock_batch (expired_date) and v_available_stock (exp_date)
            if (isset($row['expired_date'])) {
                $row['exp_date'] = $this->_normalizeDate($row['expired_date']);
                $row['gudang']   = $row['gudang_id'] ?? '';
                $row['qty_on_hand']  = (float)($row['qty_on_hand'] ?? $row['available_stock'] ?? 0);
                $row['qty_reserved'] = (float)($row['qty_reserved'] ?? 0);
            } else {
                // From v_available_stock
                $row['exp_date'] = $this->_normalizeDate($row['exp_date'] ?? '');
                $row['gudang']   = $row['gudang'] ?? '';
                $row['qty_on_hand']  = (float)($row['available_stock'] ?? 0);
                $row['qty_reserved'] = 0;
            }

            // Available = qty_on_hand - qty_reserved
            $av  = (float)($row['available_stock'] ?? 0);
            $isi = max(1, (int)$row['isi_per_box']);

            $row['available_box']  = (int)floor($av / $isi);
            $row['available_ecer'] = (int)fmod($av, $isi);
        }
        unset($row);

        return $stocks;
    }

    // ----------------------------------------------------------------
    // LIST SO
    // ----------------------------------------------------------------
    public function get_all_so($filter = [])
    {
        $this->db->select('so.*, c.nama_customer');
        $this->db->from('tbso_sales_order so');
        $this->db->join('tb_customer c', 'c.kd_customer = so.kd_customer', 'left');
        if (!empty($filter['status']))          $this->db->where('so.status', $filter['status']);
        if (!empty($filter['date1']))           $this->db->where('so.tanggal_transaksi >=', $filter['date1']);
        if (!empty($filter['date2']))           $this->db->where('so.tanggal_transaksi <=', $filter['date2']);
        if (!empty($filter['customer_id']))     $this->db->where('so.kd_customer', $filter['customer_id']);
        $this->db->order_by('so.create_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_so($id_so)
    {
        $this->db->select('so.*, c.nama_customer');
        $this->db->from('tbso_sales_order so');
        $this->db->join('tb_customer c', 'c.kd_customer = so.kd_customer', 'left');
        $this->db->where('so.id_so', $id_so);
        return $this->db->get()->row_array();
    }

    public function get_so_detail($no_faktur)
    {
        $rows = $this->db->get_where('tbso_sales_order_detail', ['no_faktur' => $no_faktur])->result_array();

        foreach ($rows as &$row) {
            $row['berat_gram']  = (float)($row['tonase_satuan']   ?? 0);
            $row['kubikasi_m3'] = (float)($row['kubikasi_satuan'] ?? 0);
            $row['hrg_pokok']   = (float)($row['hrg_pokok']       ?? 0);
            $row['gudang']      = $row['gudang_id'] ?? '';

            if (!isset($row['qty_box']) || $row['qty_box'] === null) {
                $isi = max(1, (int)($row['isi_per_box'] ?? 1));
                $row['qty_box']    = floor((float)$row['qty'] / $isi);
                $row['qty_satuan'] = fmod((float)$row['qty'], $isi);
            }
        }
        unset($row);
        return $rows;
    }

    // ----------------------------------------------------------------
    // SIMPAN SO
    //
    // PERUBAHAN: Menggunakan tberp_stock_ledger dengan tipe 'RESERVE'
    //            dan update tberp_stock_batch.qty_reserved
    // ----------------------------------------------------------------
    public function simpan_so($header, $details)
    {
        $this->db->trans_start();
        $this->db->insert('tbso_sales_order', $header);
        $id_so     = $this->db->insert_id();
        $no_faktur = $header['no_faktur'];
        $no_so = $header['no_so'];
        $gudang_id = $header['gudang_id'];

        foreach ($details as $d) {
            $d['no_faktur'] = $no_faktur;
            $d['no_so']     = $no_so;
            $this->db->insert('tbso_sales_order_detail', $d);
            $id_detail = $this->db->insert_id();

            // Simpan ke tbso_stock_reservation (untuk kompatibilitas)
            $this->db->insert('tbso_stock_reservation', [
                'no_faktur'    => $no_faktur,
                'no_so'        => $no_so,
                'id_so_detail' => $id_detail,
                'kd_barang'    => $d['kd_barang'],
                'exp_date'     => $this->_toViewDate($d['expired_date']),
                'no_lot'       => $d['no_lot'],
                'gudang_id'    => $gudang_id,
                'qty_reserved' => $d['qty'],
                'status'       => 'active',
            ]);

            // Simpan ke tberp_stock_ledger dengan tipe RESERVE (jika tabel ada)
            try {
                $this->db->insert('tberp_stock_ledger', [
                    'kd_barang'    => $d['kd_barang'],
                    'gudang_id'    => $gudang_id,
                    'no_lot'       => $d['no_lot'],
                    'expired_date' => $this->_normalizeDate($d['expired_date']),
                    'qty'          => $d['qty'],
                    'tipe'         => 'RESERVE',
                    'ref_no'       => $no_faktur,
                    'ref_type'     => 'SALES_ORDER',
                    'created_at'   => date('Y-m-d H:i:s'),
                ]);
            } catch (Exception $e) {
                // Ignore if table doesn't exist
            }

            // Update tberp_stock_batch.qty_reserved (jika tabel ada)
            try {
                $exp_date_normalized = $this->_normalizeDate($d['expired_date']);
                $this->db->where('kd_barang', $d['kd_barang']);
                $this->db->where('gudang_id', $gudang_id);
                if (!empty($d['no_lot'])) {
                    $this->db->where('no_lot', $d['no_lot']);
                }
                if (!empty($exp_date_normalized)) {
                    $this->db->where('expired_date', $exp_date_normalized);
                }
                $this->db->set('qty_reserved', 'qty_reserved + ' . (float)$d['qty'], FALSE);
                $this->db->set('update_at', date('Y-m-d H:i:s'));
                $this->db->update('tberp_stock_batch');
            } catch (Exception $e) {
                // Ignore if table doesn't exist
            }
        }

        $this->db->where('id_so', $id_so);
        $this->db->update('tbso_sales_order', ['jumlah_item' => count($details)]);
        $this->db->trans_complete();

        return $this->db->trans_status() ? $id_so : false;
    }

    // ----------------------------------------------------------------
    // UPDATE SO
    // PERUBAHAN: Handle stock ledger RELEASE untuk old data dan RESERVE untuk new data
    //            Also update tberp_stock_batch.qty_reserved
    // ----------------------------------------------------------------
    public function update_so($id_so, $header, $details)
    {
        $this->db->trans_start();
        $no_faktur = $header['no_faktur'];
        $no_so = $header['no_so'];
        $gudang_id = $header['gudang_id'];

        $this->db->where('id_so', $id_so);
        $this->db->update('tbso_sales_order', $header);

        // Get old details for releasing stock
        $old_details = $this->db->get_where('tbso_sales_order_detail', ['no_faktur' => $no_faktur])->result_array();

        // Release old stock_reservation
        $this->db->where('no_faktur', $no_faktur);
        $this->db->update('tbso_stock_reservation', ['status' => 'released']);

        // Release old stock_ledger (add RELEASE entry to reverse RESERVE)
        foreach ($old_details as $old) {
            $this->db->insert('tberp_stock_ledger', [
                'kd_barang'    => $old['kd_barang'],
                'gudang_id'    => $gudang_id,
                'no_lot'       => $old['no_lot'],
                'expired_date' => $old['expired_date'],
                'qty'          => $old['qty'],
                'tipe'         => 'RELEASE',
                'ref_no'       => $no_faktur,
                'ref_type'     => 'SALES_ORDER',
                'created_at'   => date('Y-m-d H:i:s'),
            ]);

            // Decrease qty_reserved in tberp_stock_batch for old items
            $this->db->where('kd_barang', $old['kd_barang']);
            $this->db->where('gudang_id', $gudang_id);
            $this->db->where('no_lot', $old['no_lot']);
            $this->db->where('expired_date', $old['expired_date']);
            $this->db->set('qty_reserved', 'qty_reserved - ' . (float)$old['qty'], FALSE);
            $this->db->set('update_at', date('Y-m-d H:i:s'));
            $this->db->update('tberp_stock_batch');
        }

        // Delete old details
        $this->db->delete('tbso_sales_order_detail', ['no_faktur' => $no_faktur]);

        // Insert new details and create new reservations
        foreach ($details as $d) {
            $d['no_faktur'] = $no_faktur;
            $d['no_so']     = $no_so;
            $this->db->insert('tbso_sales_order_detail', $d);
            $id_detail = $this->db->insert_id();

            // Simpan ke tbso_stock_reservation (untuk kompatibilitas)
            $this->db->insert('tbso_stock_reservation', [
                'no_faktur'    => $no_faktur,
                'no_so'        => $no_so,
                'id_so_detail' => $id_detail,
                'kd_barang'    => $d['kd_barang'],
                'exp_date'     => $this->_toViewDate($d['expired_date']),
                'no_lot'       => $d['no_lot'],
                'gudang_id'    => $gudang_id,
                'qty_reserved' => $d['qty'],
                'status'       => 'active',
            ]);

            // Simpan ke tberp_stock_ledger dengan tipe RESERVE
            $this->db->insert('tberp_stock_ledger', [
                'kd_barang'    => $d['kd_barang'],
                'gudang_id'    => $gudang_id,
                'no_lot'       => $d['no_lot'],
                'expired_date' => $this->_normalizeDate($d['expired_date']),
                'qty'          => $d['qty'],
                'tipe'         => 'RESERVE',
                'ref_no'       => $no_faktur,
                'ref_type'     => 'SALES_ORDER',
                'created_at'   => date('Y-m-d H:i:s'),
            ]);

            // Update tberp_stock_batch.qty_reserved
            $this->db->where('kd_barang', $d['kd_barang']);
            $this->db->where('gudang_id', $gudang_id);
            $this->db->where('no_lot', $d['no_lot']);
            $this->db->where('expired_date', $this->_normalizeDate($d['expired_date']));
            $this->db->set('qty_reserved', 'qty_reserved + ' . (float)$d['qty'], FALSE);
            $this->db->set('update_at', date('Y-m-d H:i:s'));
            $this->db->update('tberp_stock_batch');
        }

        $this->db->where('id_so', $id_so);
        $this->db->update('tbso_sales_order', ['jumlah_item' => count($details)]);
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // ----------------------------------------------------------------
    // VALIDASI STOK
    // ----------------------------------------------------------------
    public function validasi_stok($details, $gudang_id, $exclude_id_so = null)
    {
        $errors = [];
        
        // Ambil no_faktur dari id_so yang di-exclude
        $exclude_no_faktur = null;
        if ($exclude_id_so) {
            $so = $this->db->get_where('tbso_sales_order', ['id_so' => $exclude_id_so])->row_array();
            $exclude_no_faktur = $so['no_faktur'] ?? null;
        }

        foreach ($details as $d) {
            $stock     = $this->cek_stock($d['kd_barang'], $d['expired_date'], $gudang_id);
            $available = $stock ? (float)$stock['available_stock'] : 0;

            if ($exclude_no_faktur) {
                $this->db->select('SUM(qty_reserved) as qty');
                $this->db->where('no_faktur', $exclude_no_faktur); // ← no_faktur
                $this->db->where('kd_barang', $d['kd_barang']);
                $this->db->where('exp_date',  $this->_toViewDate($d['expired_date']));
                $this->db->where('status', 'active');
                $res       = $this->db->get('tbso_stock_reservation')->row_array();
                $available += $res ? (float)$res['qty'] : 0;
            }

            $available = round($available, 3);
            $diminta   = round((float)$d['qty'], 3);

            if ($diminta > $available) {
                $isi      = max(1, (int)($d['isi_per_box'] ?? 1));
                $av_box   = (int)floor($available / $isi);
                $av_ecer  = (int)fmod($available, $isi);
                $req_box  = (int)($d['qty_box']    ?? 0);
                $req_ecer = (int)($d['qty_satuan'] ?? 0);

                $errors[] = "Stok tidak cukup: <b>{$d['nama_barang']}</b> "
                        . "(Exp: {$d['expired_date']}) — "
                        . "Diminta: {$req_box} box + {$req_ecer} pcs = {$diminta} pcs, "
                        . "Tersedia: {$av_box} box + {$av_ecer} pcs = {$available} pcs";
            }
        }
        return $errors;
    }
    // ----------------------------------------------------------------
    // VALIDASI TONASE + KUBIKASI
    // ----------------------------------------------------------------
    public function validasi_tonase_kubikasi($details,
        $batas_tonase   = self::BATAS_TONASE,
        $batas_kubikasi = self::BATAS_KUBIKASI)
    {
        $batas_tonase   = ($batas_tonase   > 0) ? (float)$batas_tonase   : self::BATAS_TONASE;
        $batas_kubikasi = ($batas_kubikasi > 0) ? (float)$batas_kubikasi : self::BATAS_KUBIKASI;

        $total_tonase = 0; $total_kubikasi = 0;
        foreach ($details as $d) {
            $qty = (float)($d['qty'] ?? 0);
            $total_tonase   += $qty * ((float)($d['berat_gram']  ?? 0) / 1000000);
            $total_kubikasi += $qty *  (float)($d['kubikasi_m3'] ?? 0);
        }

        $total_tonase   = round($total_tonase,   6);
        $total_kubikasi = round($total_kubikasi, 6);
        $warnings = [];

        if ($total_tonase > $batas_tonase && $total_kubikasi <= $batas_kubikasi)
            $warnings[] = "Tonase melebihi batas (".round($total_tonase,3)." ton &gt; {$batas_tonase} ton).";
        elseif ($total_kubikasi > $batas_kubikasi && $total_tonase <= $batas_tonase)
            $warnings[] = "Kubikasi melebihi batas (".round($total_kubikasi,4)." m³ &gt; {$batas_kubikasi} m³).";
        elseif ($total_tonase > $batas_tonase && $total_kubikasi > $batas_kubikasi)
            $warnings[] = "Tonase (".round($total_tonase,3)." ton) DAN kubikasi (".round($total_kubikasi,4)." m³) melebihi batas!";

        return [
            'total_tonase'   => $total_tonase,
            'total_kubikasi' => $total_kubikasi,
            'batas_tonase'   => $batas_tonase,
            'batas_kubikasi' => $batas_kubikasi,
            'warnings'       => $warnings,
        ];
    }

    // ----------------------------------------------------------------
    // GET DAFTAR KARYAWAN — untuk dropdown pilih approver di form SO
    // ----------------------------------------------------------------
    public function get_approver_list()
    {
        return $this->db
            ->select('id, nm_karyawan, jobdesk, departemen')
            ->where('nm_karyawan !=', '')
            ->order_by('nm_karyawan', 'ASC')
            ->get('tb_karyawan')
            ->result_array();
    }

    public function simpan_request_approval($no_faktur, $no_so, $keterangan, $req_by, $approve_by)
    {
        // Cek apakah sudah ada pending untuk no_faktur ini
        $ada = $this->db->get_where('tbso_so_approval', [
            'no_faktur' => $no_faktur,
            'status'    => 'pending',
        ])->row_array();
 
        if (!$ada) {
            $this->db->insert('tbso_so_approval', [
                'no_faktur'  => $no_faktur,
                'no_so'      => $no_so,
                'tipe'       => 'harga',
                'keterangan' => $keterangan,
                'req_by'     => $req_by,
                'approve_by' => $approve_by,
                'status'     => 'pending',
                'req_at'     => date('Y-m-d H:i:s'),
            ]);
        }

        $this->db->where('no_faktur', $no_faktur);
        $this->db->update('tbso_sales_order', [
            'status'     => 'waiting_approval',
            'approve_by' => $approve_by,
        ]);
    }
 
    // ----------------------------------------------------------------
    // GET PENDING APPROVAL — untuk halaman daftar approval
    // Filter by approve_by agar hanya tampil yang relevan
    // ----------------------------------------------------------------
    public function get_pending_approval($approve_by = null)
    {
        $this->db->select('ap.*, so.customer_name, so.tanggal_transaksi, so.total_tonase, so.total_kubikasi, so.no_so');
        $this->db->from('tbso_so_approval ap');
        $this->db->join('tbso_sales_order so', 'so.no_faktur = ap.no_faktur', 'left');
        $this->db->where('ap.status', 'pending');
        if (!empty($approve_by)) {
            $this->db->where('ap.approve_by', $approve_by);
        }
        $this->db->order_by('ap.req_at', 'DESC');
        return $this->db->get()->result_array();
    }
 
    // ----------------------------------------------------------------
    // PROSES APPROVAL
    // ----------------------------------------------------------------
    public function proses_approval($id, $status, $note, $act_by)
    {
        $this->db->where('id', $id);
        $this->db->update('tbso_so_approval', [
            'status' => $status,
            'note'   => $note,
            'act_by' => $act_by,
            'act_at' => date('Y-m-d H:i:s'),
        ]);
 
        $row = $this->db->get_where('tbso_so_approval', ['id' => $id])->row_array();
        if ($row) {
            $new_status = ($status === 'approved') ? 'approved' : 'draft';
            $this->db->where('no_faktur', $row['no_faktur']);
            $this->db->update('tbso_sales_order', ['status' => $new_status]);
        }
    }

    // ----------------------------------------------------------------
    // UPDATE STATUS SO
    // PERUBAHAN: Handle stock ledger RELEASE when cancelling SO
    // ----------------------------------------------------------------
    public function update_status($id_so, $status, $update_by)
    {
        $so = $this->db->get_where('tbso_sales_order', ['id_so' => $id_so])->row_array();

        $this->db->where('id_so', $id_so);
        $this->db->update('tbso_sales_order', ['status' => $status, 'update_by' => $update_by]);

        if ($status === 'cancelled' && $so) {
            $no_faktur = $so['no_faktur'];
            $gudang_id = $so['gudang_id'];

            // Release stock_reservation
            $this->db->where('no_faktur', $no_faktur);
            $this->db->update('tbso_stock_reservation', ['status' => 'released']);

            // Get details for releasing stock_ledger and batch
            $details = $this->db->get_where('tbso_sales_order_detail', ['no_faktur' => $no_faktur])->result_array();

            foreach ($details as $d) {
                // Add RELEASE entry to stock_ledger to reverse the RESERVE
                $this->db->insert('tberp_stock_ledger', [
                    'kd_barang'    => $d['kd_barang'],
                    'gudang_id'    => $gudang_id,
                    'no_lot'       => $d['no_lot'],
                    'expired_date' => $d['expired_date'],
                    'qty'          => $d['qty'],
                    'tipe'         => 'RELEASE',
                    'ref_no'       => $no_faktur,
                    'ref_type'     => 'SALES_ORDER_CANCEL',
                    'created_at'   => date('Y-m-d H:i:s'),
                ]);

                // Decrease qty_reserved in tberp_stock_batch
                $this->db->where('kd_barang', $d['kd_barang']);
                $this->db->where('gudang_id', $gudang_id);
                $this->db->where('no_lot', $d['no_lot']);
                $this->db->where('expired_date', $d['expired_date']);
                $this->db->set('qty_reserved', 'qty_reserved - ' . (float)$d['qty'], FALSE);
                $this->db->set('update_at', date('Y-m-d H:i:s'));
                $this->db->update('tberp_stock_batch');
            }
        }
    }

    // ----------------------------------------------------------------
    // PARTIAL DELIVERY
    // ----------------------------------------------------------------
    public function update_qty_delivered($id_so_detail, $qty_delivered)
    {
        $this->db->where('id', $id_so_detail);
        $this->db->update('tbso_sales_order_detail', ['qty_delivered' => $qty_delivered]);
        $detail = $this->db->get_where('tbso_sales_order_detail', ['id'=>$id_so_detail])->row_array();
        if ($detail) {
            $all = $this->db->get_where('tbso_sales_order_detail', ['id_so'=>$detail['id_so']])->result_array();
            $all_done = true; $any_done = false;
            foreach ($all as $item) {
                if ($item['qty_delivered'] >= $item['qty']) $any_done = true;
                else $all_done = false;
            }
            if ($all_done) {
                $this->update_status($detail['id_so'], 'completed', 'system');
                $this->db->where('id_so', $detail['id_so']);
                $this->db->update('tbso_stock_reservation', ['status' => 'released']);
            } elseif ($any_done) {
                $this->update_status($detail['id_so'], 'partial_delivered', 'system');
            }
        }
    }
    
    // ----------------------------------------------------------------
    // GET KD PO DARI MASTER BARANG
    // ----------------------------------------------------------------
    public function get_ref_no($kd_barang, $exp_date, $no_lot = '')
    {
        $ymd = $this->_normalizeDate($exp_date);

        $this->db->select('ref_no');
        $this->db->from('tberp_stock_ledger');
        $this->db->where('kd_barang', $kd_barang);
        $this->db->where('expired_date', $ymd);

        if (!empty($no_lot)) {
            $this->db->where('no_lot', $no_lot);
        }

        $this->db->where_in('tipe', ['IN', 'SALDO_AWAL']);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(1);
        $row = $this->db->get()->row_array();
        return $row ? $row['ref_no'] : null;
    }

    // ----------------------------------------------------------------
    // DAFTAR PAJAK dari tb_set_tax
    // ----------------------------------------------------------------
    public function get_tax_list()
    {
        return $this->db->order_by('nm_tax', 'ASC')
                        ->get('tb_set_tax')
                        ->result_array();
    }

    public function get_do_for_sales()
    {
        return $this->db->query("
            SELECT
                a.kd_do                    AS kddo,
                a.tgl_create               AS createat,
                a.tgl_pengiriman           AS tglkirim,
                a.nolambung                AS nopol,
                a.regional                 AS rute,
                a.status,
                a.sales_confirm_status,
                a.sales_confirm_by,
                a.sales_confirm_at,
                a.sales_confirm_note,
                (
                    SELECT COUNT(DISTINCT kd_barang)
                    FROM tb_detail_do
                    WHERE kd_do = a.kd_do
                ) AS totalbarang,
                (
                    SELECT COUNT(DISTINCT kd_faktur)
                    FROM tb_detail_do
                    WHERE kd_do = a.kd_do
                ) AS totalfaktur
            FROM tb_do a
            WHERE a.status IN (2, 3)
              AND (
                SELECT COUNT(DISTINCT kd_faktur)
                FROM tb_detail_do
                WHERE kd_do = a.kd_do
              ) > 0
            ORDER BY a.tgl_create DESC
        ")->result();
    }

    public function update_sales_confirm($kd_do, $action, $confirm_by, $note = '')
    {
        $now = date('Y-m-d H:i:s');

        $this->db->where('kd_do', $kd_do);
        $this->db->update('tb_do', [
            'sales_confirm_status' => $action,
            'sales_confirm_by'     => $confirm_by,
            'sales_confirm_at'     => $now,
            'sales_confirm_note'   => $note,
            'status'               => ($action === 'siap') ? 3 : 2
        ]);

        $this->db->insert('tb_log_confirm_sales', [
            'kd_do'      => $kd_do,
            'action'     => $action,
            'note'       => $note,
            'confirm_by' => $confirm_by,
            'confirm_at' => $now
        ]);

        return $this->db->affected_rows();
    }

    public function get_log_confirm_sales($kd_do)
    {
        return $this->db->query("
            SELECT * FROM tb_log_confirm_sales
            WHERE kd_do = ?
            ORDER BY confirm_at DESC
        ", [$kd_do])->result();
    }

    public function insertlog_do($data)
    {
        return $this->db->insert('tb_log_do', $data);
    }
}