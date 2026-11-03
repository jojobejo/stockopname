<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class C_SalesOrder extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_SalesOrder');
        $this->load->model('M_Logistik');
        $this->load->model('M_ActivityLog');
        $this->load->library(['form_validation', 'session', 'pagination']);
        $this->load->helper(['url', 'form']);
    }

    // ================================================================
    // HELPER — Ambil data user yang sedang login dari session / DB.
    // ================================================================
    private function _getCurrentUser()
    {
        $id   = $this->session->userdata('id_karyawan')
             ?? $this->session->userdata('id')
             ?? $this->session->userdata('user_id')
             ?? $this->session->userdata('karyawan_id')
             ?? null;

        $usn  = $this->session->userdata('username')
             ?? $this->session->userdata('user_name')
             ?? $this->session->userdata('login')
             ?? null;

        $nama = $this->session->userdata('nm_karyawan')
             ?? $this->session->userdata('nama')
             ?? $this->session->userdata('name')
             ?? $this->session->userdata('nama_user')
             ?? null;

        $wil  = $this->session->userdata('wilayah')
             ?? $this->session->userdata('wilayah_id')
             ?? $this->session->userdata('gudang_id')
             ?? $this->session->userdata('id_wilayah')
             ?? null;

        if (!empty($nama) && !empty($wil)) {
            return [
                'nm_karyawan' => $nama,
                'wilayah'     => $wil,
                'username'    => $usn ?? $nama,
            ];
        }

        $row = null;
        if (!empty($id)) {
            $row = $this->db->get_where('tb_karyawan', ['id' => $id])->row_array();
        }
        if (!$row && !empty($usn)) {
            $row = $this->db->get_where('tb_karyawan', ['username' => $usn])->row_array();
        }

        if ($row) {
            return [
                'nm_karyawan' => $row['nm_karyawan'] ?? 'system',
                'wilayah'     => $row['wilayah']     ?? '',
                'username'    => $row['username']    ?? 'system',
            ];
        }

        return ['nm_karyawan' => 'system', 'wilayah' => '', 'username' => 'system'];
    }

    private function _getUsername()
    {
        return $this->_getCurrentUser()['nm_karyawan'];
    }

    private function _getGudangId($post = [])
    {
        if (!empty($post['gudang_id'])) return $post['gudang_id'];
        $wil = $this->_getCurrentUser()['wilayah'];
        if (!empty($wil)) return $wil;
        return '';
    }

    // ================================================================
    // HELPER — Decode id_so dari URI segment (:any)
    //
    // Dengan route $route['sales_order/detail/(:any)'] = '.../$1',
    // CodeIgniter meneruskan seluruh sisa URI sebagai satu string
    // dengan '/' di antaranya. Kita tinggal rawurldecode saja.
    //
    // Contoh URI : sales_order/detail/SO%2F202604%2F0003
    //   → $encoded : SO%2F202604%2F0003  → decode → SO/202604/0003  ✓
    //
    // Contoh URI : sales_order/detail/SO/202604/0003
    //   → $encoded : SO/202604/0003      → decode → SO/202604/0003  ✓
    // ================================================================
    private function _decodeId($encoded)
    {
        return rawurldecode((string)$encoded);
    }

    // ================================================================
    // HELPER — redirect ke detail, encode '/' → %2F agar 1 segment
    // ================================================================
    private function _redirectDetail($id_so)
    {
        // rawurlencode mengubah '/' → '%2F'
        // Hasilnya: sales_order/detail/SO%2F202604%2F0003
        // Route (:any) menangkap seluruh string itu sebagai $1
        redirect('sales_order/detail/' . rawurlencode($id_so));
    }

    // ================================================================
    // LIST
    // ================================================================
    public function index()
    {
        $filter = [
            'date1'       => $this->input->post('date1'),
            'date2'       => $this->input->post('date2'),
            'status'      => $this->input->post('status'),
            'customer_id' => $this->input->post('customer_id'),
        ];

        $data['page_title'] = 'KARISMA - Sales Order';
        $data['so_list']    = $this->M_SalesOrder->get_all_so($filter);
        $data['customers']  = $this->M_SalesOrder->get_customers();
        $data['filter']     = $filter;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/sales/so_list.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    // ================================================================
    // FORM CREATE
    // ================================================================
    public function create()
    {
        $data['page_title']     = 'KARISMA - Buat Sales Order';
        $data['no_so']          = '';   // kosong, diisi manual user
        $data['no_faktur']      = $this->M_SalesOrder->generate_no_faktur();
        $data['customers']      = $this->M_SalesOrder->get_customers();
        $data['tax_list']       = $this->M_SalesOrder->get_tax_list();
        $data['approver_list']  = $this->M_SalesOrder->get_approver_list();
        $data['gudang_id']      = $this->_getGudangId();
        $data['so']             = null;
        $data['details']        = [];
        $data['batas_tonase']   = M_SalesOrder::BATAS_TONASE;
        $data['batas_kubikasi'] = M_SalesOrder::BATAS_KUBIKASI;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/sales/so_form.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    // ================================================================
    // STORE (POST)
    // ================================================================
    public function store()
    {
        if ($this->input->method() !== 'post') show_404();

        $post      = $this->input->post(null, true);
        $details   = $this->_parse_detail_post($post);
        $gudang_id = $this->_getGudangId($post);

        if (empty($details)) {
            $this->session->set_flashdata('error', 'Minimal 1 item barang harus diisi.');
            redirect('sales_order/create');
            return;
        }

        $stock_errors = $this->M_SalesOrder->validasi_stok($details, $gudang_id);
        if (!empty($stock_errors)) {
            $this->session->set_flashdata('error', implode('<br>', $stock_errors));
            redirect('sales_order/create');
            return;
        }

        $tk      = $this->M_SalesOrder->validasi_tonase_kubikasi($details);
        $is_nego = 0;
        foreach ($details as $d) {
            if (!empty($d['is_nego'])) { $is_nego = 1; break; }
        }

        $no_so    = $post['no_so'];
        $no_faktur = $post['no_faktur'];

        $header = [
            'no_so'             => $post['no_so'],
            'no_faktur'         => $no_faktur,
            'tanggal_transaksi' => $post['tanggal'],
            'kd_customer'       => $post['customer_id'],
            'customer_name'     => $post['customer_name'],
            'gudang_id'         => $gudang_id,
            'batas_tonase'      => $tk['batas_tonase'],
            'batas_kubikasi'    => $tk['batas_kubikasi'],
            'total_tonase'      => $tk['total_tonase'],
            'total_kubikasi'    => $tk['total_kubikasi'],
            'status'            => $is_nego ? 'waiting_approval' : 'draft',
            'catatan'           => $post['catatan'] ?? null,
            'create_by'         => $this->_getUsername(),
        ];

        $id_so = $this->M_SalesOrder->simpan_so($header, $details); // return int

        if ($id_so) {
            $aksi = $is_nego ? 'CREATE_NEGO' : 'CREATE';
            $detail_str = [];
            foreach ($details as $d) {
                $detail_str[] = $d['nama_barang']
                    .' | Box: '.$d['qty_box']
                    .' | Ecer: '.$d['qty_satuan'].' pcs'
                    .' | Total: '.$d['qty'].' pcs';
            }
            $ket           = 'SO baru dibuat. Customer: '.$post['customer_name'].'. Total item: '.count($details);
            $detail_produk = implode("\n", $detail_str);
            $this->M_ActivityLog->log($no_so, $no_faktur, $aksi, $ket, $this->_getUsername(), $detail_produk);

            if ($is_nego) {
                // Ambil approver dari baris pertama yang nego
                $approve_by = '';
                foreach ($details as $d) {
                    if (!empty($d['is_nego']) && !empty($d['approve_by'])) {
                        $approve_by = $d['approve_by'];
                        break;
                    }
                }
                $ket_approval = 'Harga item berbeda dari HPP. Dibuat oleh: '.$this->_getUsername();
                $this->M_SalesOrder->simpan_request_approval(
                    $no_faktur, $no_so, $ket_approval, $this->_getUsername(), $approve_by
                );
                $this->session->set_flashdata('warning',
                    'SO berhasil disimpan. Menunggu approval dari <b>'.$approve_by.'</b>.');
            } elseif (!empty($tk['warnings'])) {
                $this->session->set_flashdata('warning', '<b>Peringatan:</b> ' . implode('<br>', $tk['warnings']));
            } else {
                $this->session->set_flashdata('success', 'Sales Order <b>' . $no_so . '</b> berhasil dibuat.');
            }
            redirect('sales_order/detail/' . $id_so); // id_so = integer, aman di URL
        } else {
            $this->session->set_flashdata('error', 'Gagal menyimpan SO.');
            redirect('sales_order/create');
        }
    }

    // ================================================================
    // DETAIL
    // Route: $route['sales_order/detail/(:any)'] = 'C_SalesOrder/detail/$1';
    // $encoded = seluruh sisa path setelah "detail/", mis: SO%2F202604%2F0003
    // ================================================================
    public function detail($id_so)
    {
        $so = $this->M_SalesOrder->get_so($id_so);
        if (!$so) show_404();

        $data['page_title'] = 'KARISMA - Detail SO ' . $id_so;
        $data['so']         = $so;
        $data['details'] = $this->M_SalesOrder->get_so_detail($so['no_faktur']);

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/sales/so_detail.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    // ================================================================
    // FORM EDIT
    // Route: $route['sales_order/edit/(:any)'] = 'C_SalesOrder/edit/$1';
    // ================================================================
    public function edit($id_so)
    {
        $so = $this->M_SalesOrder->get_so($id_so);
        if (!$so || $so['status'] !== 'draft') {
            $this->session->set_flashdata('error', 'SO tidak dapat diedit.');
            redirect('sales_order');
            return;
        }

        $data['page_title']     = 'KARISMA - Edit SO ' . $id_so;
        $data['no_so']          = $so['no_so'] ?? '';
        $data['no_faktur']      = $so['no_faktur'] ?? '';
        $data['so']             = $so;
        $data['details']        = $this->M_SalesOrder->get_so_detail($so['no_faktur']);
        $data['customers']      = $this->M_SalesOrder->get_customers();
        $data['tax_list']       = $this->M_SalesOrder->get_tax_list();
        $data['approver_list']  = $this->M_SalesOrder->get_approver_list();
        $data['gudang_id']      = $so['gudang_id'];
        $data['batas_tonase']   = M_SalesOrder::BATAS_TONASE;
        $data['batas_kubikasi'] = M_SalesOrder::BATAS_KUBIKASI;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/sales/so_form.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    // ================================================================
    // UPDATE (POST)
    // Route: $route['sales_order/update/(:any)'] = 'C_SalesOrder/update/$1';
    // ================================================================
    public function update($id_so)
    {
        $so = $this->M_SalesOrder->get_so($id_so);
        if (!$so) show_404();

        $post      = $this->input->post(null, true);
        $details   = $this->_parse_detail_post($post);
        $gudang_id = $this->_getGudangId($post);

        if (empty($details)) {
            $this->session->set_flashdata('error', 'Minimal 1 item barang harus diisi.');
            redirect('sales_order/edit/' . rawurlencode($id_so));
            return;
        }

        $stock_errors = $this->M_SalesOrder->validasi_stok($details, $gudang_id, $id_so);
        if (!empty($stock_errors)) {
            $this->session->set_flashdata('error', implode('<br>', $stock_errors));
            redirect('sales_order/edit/' . rawurlencode($id_so));
            return;
        }

        $tk      = $this->M_SalesOrder->validasi_tonase_kubikasi($details);
        $is_nego = 0;
        foreach ($details as $d) {
            if (!empty($d['is_nego'])) { $is_nego = 1; break; }
        }

        $header = [
            'no_so'             => $post['no_so']     ?? ($so['no_so']     ?? ''),
            'no_faktur'         => $post['no_faktur'] ?? ($so['no_faktur'] ?? ''),
            'tanggal_transaksi' => $post['tanggal'],
            'kd_customer'       => $post['customer_id'],
            'customer_name'     => $post['customer_name'],
            'gudang_id'         => $gudang_id,
            'batas_tonase'      => $tk['batas_tonase'],
            'batas_kubikasi'    => $tk['batas_kubikasi'],
            'total_tonase'      => $tk['total_tonase'],
            'total_kubikasi'    => $tk['total_kubikasi'],
            'status'            => $is_nego ? 'waiting_approval' : 'draft',
            'catatan'           => $post['catatan'] ?? null,
            'update_by'         => $this->_getUsername(),
        ];

        $result = $this->M_SalesOrder->update_so($id_so, $header, $details);

        if ($result) {
            // Activity log
            $so_data   = $this->M_SalesOrder->get_so($id_so);
            $no_so_log = $so_data['no_so']    ?? '';
            $no_fak    = $so_data['no_faktur'] ?? '';
            $detail_str = [];
            foreach ($details as $d) {
                $detail_str[] = $d['nama_barang']
                    .' | Box: '.$d['qty_box']
                    .' | Ecer: '.$d['qty_satuan'].' pcs'
                    .' | Total: '.$d['qty'].' pcs';
            }
            $ket           = 'SO diupdate. Customer: '.($post['customer_name']??'').'. Total item: '.count($details);
            $detail_produk = implode("\n", $detail_str);
            $this->M_ActivityLog->log($no_so_log, $no_fak, 'UPDATE', $ket, $this->_getUsername(), $detail_produk);

            if ($is_nego) {
                $approve_by   = $post['approve_by'] ?? '';
                $ket_approval = 'Harga item berbeda dari HPP. Diupdate oleh: '.$this->_getUsername();
                $this->M_SalesOrder->simpan_request_approval(
                    $post['no_faktur'] ?? '',
                    $post['no_so']     ?? '',
                    $ket_approval,
                    $this->_getUsername(),
                    $approve_by
                );
            }
            if (!empty($tk['warnings'])) {
                $this->session->set_flashdata('warning', implode('<br>', $tk['warnings']));
            } else {
                $this->session->set_flashdata('success', 'Sales Order <b>' . $id_so . '</b> berhasil diupdate.');
            }
            $this->_redirectDetail($id_so);
        } else {
            $this->session->set_flashdata('error', 'Gagal update SO.');
            redirect('sales_order/edit/' . rawurlencode($id_so));
        }
    }

    // ================================================================
    // CANCEL
    // Route: $route['sales_order/cancel/(:any)'] = 'C_SalesOrder/cancel/$1';
    // ================================================================
    public function cancel($id_so)
    {
        $so = $this->M_SalesOrder->get_so($id_so);
        if (!$so) show_404();

        if (!$so || in_array($so['status'], ['completed', 'cancelled'])) {
            $this->session->set_flashdata('error', 'SO tidak dapat dibatalkan.');
            $this->_redirectDetail($id_so);
            return;
        }
        $this->M_SalesOrder->update_status($id_so, 'cancelled', $this->_getUsername());

        // Activity log
        $so_data = $this->M_SalesOrder->get_so($id_so);
        $this->M_ActivityLog->log(
            $so_data['no_so']    ?? '',
            $so_data['no_faktur'] ?? '',
            'CANCEL',
            'SO dibatalkan.',
            $this->_getUsername()
        );

        $this->session->set_flashdata('success', 'Sales Order <b>' . $id_so . '</b> berhasil dibatalkan.');
        redirect('sales_order');
    }

    // ================================================================
    // APPROVAL
    // ================================================================
    public function approval()
    {
        $user        = $this->_getCurrentUser();
        $approve_by  = $user['nm_karyawan'];

        $data['page_title']    = 'KARISMA - Approval SO';
        $data['list']          = $this->M_SalesOrder->get_pending_approval($approve_by);
        $data['approver_name'] = $approve_by;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/sales/so_approval.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function approve()
    {
        if ($this->input->method() !== 'post') show_404();

        $status_approval = $this->input->post('status', true);
        $id_approval     = $this->input->post('id',     true);
        $note            = $this->input->post('note',   true);

        $this->M_SalesOrder->proses_approval($id_approval, $status_approval, $note, $this->_getUsername());

        // Activity log
        $approval  = $this->db->get_where('tbso_so_approval', ['id' => $id_approval])->row_array();
        $aksi_appr = ($status_approval === 'approved') ? 'APPROVE' : 'REJECT';
        $ket_appr  = 'SO '.($status_approval === 'approved' ? 'disetujui' : 'ditolak').'. Note: '.$note;
        $this->M_ActivityLog->log(
            $approval['no_so']    ?? '',
            $approval['no_faktur'] ?? '',
            $aksi_appr,
            $ket_appr,
            $this->_getUsername()
        );

        $msg = ($status_approval === 'approved') ? 'disetujui' : 'ditolak';
        $this->session->set_flashdata('success', "SO berhasil <b>{$msg}</b>.");
        redirect('sales_order/approval');
    }

    // ================================================================
    // ACTIVITY LOG
    // ================================================================
    public function activity_log()
    {
        $per_page = 20;
        $page     = (int)($this->input->get('page') ?? 1);
        $offset   = ($page - 1) * $per_page;

        $filter = [
            'no_so'    => $this->input->get('no_so',    true) ?? '',
            'aksi'     => $this->input->get('aksi',     true) ?? '',
            'tanggal'  => $this->input->get('tanggal',  true) ?? '',
            'keyword'  => $this->input->get('keyword',  true) ?? '',
        ];

        $data['page_title'] = 'KARISMA - Activity Log SO';
        $data['logs']       = $this->M_ActivityLog->get_filtered($filter, $per_page, $offset);
        $data['total']      = $this->M_ActivityLog->count_filtered($filter);
        $data['filter']     = $filter;
        $data['per_page']   = $per_page;
        $data['page']       = $page;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/sales/so_activity_log.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    // AJAX — activity log per SO (untuk tab di halaman detail)
    public function activity_log_so($id_so)
    {
        $so      = $this->M_SalesOrder->get_so($id_so);
        $no_so   = $so['no_so'] ?? '';
        $logs    = $this->M_ActivityLog->get_by_no_so($no_so);

        if (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'ok', 'data' => $logs], JSON_UNESCAPED_UNICODE);
    }

    // ================================================================
    // AJAX — get_stock
    // ================================================================
    public function get_stock()
    {
        if (ob_get_level()) ob_end_clean();

        try {
            $kd_barang = $this->input->get('kd_barang', true) ?: null;
            $stock = $this->M_SalesOrder->get_available_stock_with_dimensi(null, $kd_barang);

            foreach ($stock as &$row) {
                $row['available_stock'] = (float)($row['available_stock'] ?? 0);
                $row['available_box']   = (int)($row['available_box']    ?? 0);
                $row['available_ecer']  = (int)($row['available_ecer']   ?? 0);
                $row['berat_gram']      = (float)($row['berat_gram']     ?? 0);
                $row['kubikasi_m3']     = (float)($row['kubikasi_m3']    ?? 0);
                $row['hpp']             = (float)($row['hpp']            ?? 0);
                $row['isi_per_box']     = (int)($row['isi_per_box']      ?? 1);
                $row['p']               = (float)($row['p']              ?? 0);
                $row['l']               = (float)($row['l']              ?? 0);
                $row['t']               = (float)($row['t']              ?? 0);
                foreach (['kode_barang','nama_barang','satuan','exp_date','no_lot','gudang'] as $f) {
                    if (isset($row[$f])) {
                        $row[$f] = mb_convert_encoding((string)$row[$f], 'UTF-8', 'UTF-8');
                    }
                }
            }
            unset($row);

            $json = json_encode(['status' => 'ok', 'data' => $stock], JSON_UNESCAPED_UNICODE);
            if ($json === false) throw new Exception('json_encode failed: ' . json_last_error_msg());

            header('Content-Type: application/json; charset=utf-8');
            echo $json;

        } catch (Exception $e) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    // ================================================================
    // AJAX — get_barang
    // ================================================================
    public function get_barang()
    {
        if (ob_get_level()) ob_end_clean();
        $kd_barang = $this->input->get('kd_barang', true);
        $barang    = $this->M_SalesOrder->get_detail_barang($kd_barang);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['status' => 'ok', 'data' => $barang], JSON_UNESCAPED_UNICODE);
    }

    // ================================================================
    // PRIVATE — Parse POST detail item
    // ================================================================
    private function _parse_detail_post($post)
    {
        $details = [];
        if (empty($post['kd_barang']) || !is_array($post['kd_barang'])) return $details;

        foreach ($post['kd_barang'] as $i => $kd) {
            if (empty($kd)) continue;

            $hrg         = (float)($post['hrg_satuan'][$i]  ?? 0);
            $hrg_pk      = (float)($post['hrg_pokok'][$i]   ?? 0);
            $qty_box     = (float)($post['qty_box'][$i]      ?? 0);
            $qty_satuan  = (float)($post['qty_satuan'][$i]   ?? 0);
            $isi_per_box = max(1, (int)($post['isi_per_box'][$i] ?? 1));
            $pajak       = (float)($post['pajak'][$i]        ?? 0);

            $disc        = (float)($post['disc'][$i] ?? 0);
            $qty_kecil   = ($qty_box * $isi_per_box) + $qty_satuan;

            $subtotal_before_disc = $hrg * $qty_kecil;
            $subtotal_after_disc  = $subtotal_before_disc * (1 - $disc / 100);
            $total_tax            = $subtotal_after_disc  * (1 + $pajak / 100);
            $is_nego              = ($hrg > 0 && $hrg < $hrg_pk) ? 1 : 0;
            $approve_by_item      = trim($post['approve_by'][$i] ?? '');

            $ref_no = $this->M_SalesOrder->get_ref_no(
                $kd,
                $post['expired_date'][$i] ?? '',
                $post['no_lot'][$i]       ?? ''
            );

            $details[] = [
                'produk_id'    => $post['produk_id'][$i]    ?? '',
                'kd_barang'    => $kd,
                'nama_barang'  => $post['nama_barang'][$i]  ?? '',
                'qty'          => $qty_kecil,
                'qty_box'      => $qty_box,
                'qty_satuan'   => $qty_satuan,
                'isi_per_box'  => $isi_per_box,
                'satuan'       => $post['satuan'][$i]        ?? '',
                'expired_date' => $post['expired_date'][$i]  ?? '',
                'no_lot'       => $post['no_lot'][$i]        ?? null,
                'ref_no'       => $ref_no,
                'pajak'        => $pajak,
                'disc'         => $disc,
                'subtotal_before_disc' => $subtotal_before_disc,
                'subtotal_after_disc'  => $subtotal_after_disc,
                'hrg_satuan'   => $hrg,
                'hrg_pokok'    => $hrg_pk,
                'total_harga'  => $total_tax,
                'berat_gram'   => (float)($post['berat_gram'][$i]  ?? 0),
                'kubikasi_m3'  => (float)($post['kubikasi_m3'][$i] ?? 0),
                'kode_akun'    => $post['kode_akun'][$i]     ?? null,
                'approve_by'   => $approve_by_item,
                'create_by'    => $this->_getUsername(),
            ];
        }
        return $details;
    }

    public function list_do()
    {
        $data['page_title'] = 'KARISMA - SALES - LIST DO';
        $data['listdo']     = $this->M_Logistik->get_do_for_sales();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/sales/list_do.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    /**
     * Halaman Detail DO untuk Sales — view only + tombol konfirmasi
     */
    public function detail_do($kd_do)
    {
        // Ambil data sama seperti C_Logistik::detail_do tapi tanpa aksi edit
        $query = $this->db->query("
            SELECT
                x.norut, d.nama_customer AS nama_kios, d.telp1, d.telp2,
                x.kd_rute, d.regional, x.id, x.kd_faktur, x.tgl_transaksi,
                x.note_faktur, c.kd_barang AS kd_system, c.nama_barang AS nm_barang,
                x.no_lot, x.nominal_p, x.jtempo, x.tgl_exp, x.satuan,
                x.status, x.kd_do, x.qty,
                (c.p * c.l * c.t) AS dimensi,
                FLOOR(x.qty / (c.p * c.l * c.t)) AS qty_box,
                (x.qty % (c.p * c.l * c.t)) AS qty_pcs
            FROM (
                SELECT
                    a.id, a.norut, a.kd_do, a.kd_customer, a.kd_rute,
                    a.kd_faktur, a.tgl_transaksi, a.kd_barang, a.no_lot,
                    a.tgl_exp, a.nominal_p, a.jtempo, a.note_faktur,
                    a.satuan, a.status,
                    SUM(a.qty) AS qty
                FROM tb_detail_do a
                JOIN tb_do b ON b.kd_do = a.kd_do
                WHERE b.kd_do = ?
                GROUP BY
                    a.id, a.norut, a.kd_do, a.kd_customer, a.kd_rute,
                    a.kd_faktur, a.tgl_transaksi, a.kd_barang, a.no_lot,
                    a.tgl_exp, a.nominal_p, a.jtempo, a.satuan, a.status
            ) x
            JOIN tb_master_barang_all c ON c.kd_barang = x.kd_barang
            JOIN tb_customer d ON d.kd_customer = x.kd_customer
            ORDER BY d.nama_customer ASC, x.kd_faktur ASC, c.nama_barang ASC
        ", [$kd_do]);

        $query1 = $this->db->query("
            SELECT
                b.id, b.kd_do, b.regional, b.nolambung, b.driver,
                b.status, b.sales_confirm_status, b.sales_confirm_by,
                b.sales_confirm_at, b.sales_confirm_note,
                COUNT(DISTINCT a.kd_barang) AS total_barang,
                ROUND(SUM(a.qty * m.berat)/1000000, 2) AS total_tonase_faktur,
                ROUND(SUM(a.qty * m.kubikasi), 2) AS total_kubikasi,
                COUNT(DISTINCT a.kd_customer) AS totalfaktur
            FROM tb_detail_do a
            JOIN tb_do b ON b.kd_do = a.kd_do
            JOIN tb_master_barang_all m ON m.kd_barang = a.kd_barang
            WHERE b.kd_do = ?
            GROUP BY b.id, b.kd_do, b.regional, b.nolambung, b.driver,
                     b.status, b.sales_confirm_status, b.sales_confirm_by,
                     b.sales_confirm_at, b.sales_confirm_note
        ", [$kd_do]);

        $query2 = $this->db->where('kd_do', $kd_do)->get('tb_do');
        $log_confirm = $this->M_Logistik->get_log_confirm_sales($kd_do);

        $data['page_title']  = 'KARISMA - SALES - DETAIL DO';
        $data['kdo']         = $query1->result();
        $data['dostatus']    = $query2->result();
        $data['data_list']   = $query->result();
        $data['log_confirm'] = $log_confirm;
        $data['kd_do']       = $kd_do;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/sales/detail_do_sales.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    /**
     * Endpoint konfirmasi loading dari Sales
     * POST: kd_do, action (siap/belum_siap), note
     */
    public function confirm_loading()
    {
        while (ob_get_level()) ob_end_clean();
        header('Content-Type: application/json; charset=utf-8');

        $kd_do      = $this->input->post('kd_do');
        $action     = $this->input->post('action');
        $note       = $this->input->post('note') ?? '';
        $confirm_by = $this->session->userdata('nama');

        if (!$kd_do || !in_array($action, ['siap', 'belum_siap'])) {
            echo json_encode(['msg' => 'error', 'message' => 'Data tidak valid']);
            exit;
        }

        $do = $this->db->where('kd_do', $kd_do)->where('status', 2)->get('tb_do')->row();
        if (!$do) {
            echo json_encode(['msg' => 'error', 'message' => 'DO tidak ditemukan atau sudah dikonfirmasi']);
            exit;
        }

        $this->M_Logistik->update_sales_confirm($kd_do, $action, $confirm_by, $note);

        $msg = ($action === 'siap')
            ? 'Konfirmasi Siap Loading berhasil. DO sekarang On Delivery.'
            : 'DO ditandai Belum Siap Loading.';

        if ($action === 'siap') {
            $this->M_Logistik->insertlog_do([
                'kd_do'      => $kd_do,
                'tgl_input'  => date('d/m/Y'),
                'keterangan' => 'SALES CONFIRM - SIAP LOADING oleh ' . $confirm_by,
                'inputer'    => $confirm_by
            ]);
        }

        echo json_encode(['msg' => 'success', 'message' => $msg, 'action' => $action]);
        exit;
    }
}