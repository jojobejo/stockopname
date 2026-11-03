<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Checker extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        date_default_timezone_set('Asia/Jakarta'); // WIB UTC+7
    }

    // ================================================================
    // BONGKARAN
    // ================================================================
    public function get_list()
    {
        $this->db->select('
            b.*,
            bc.nik_checker,
            bc.nm_checker,
            bc.waktu_mulai,
            bc.waktu_selesai,
            bc.progres,
            bc.status_checker,
            bc.is_paused,
            bc.paused_at,
            bc.total_pause_secs,
            bc.pernah_pause
        ');
        $this->db->from('tb_bongkaran b');
        $this->db->join('tb_bongkaran_checker bc', 'bc.id_bongkaran = b.id', 'left');
        $this->db->where('b.is_archived', 0);
        $this->db->order_by('b.id', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_arsip_bongkaran()
    {
        $this->db->select('
            b.*,
            bc.nm_checker,
            bc.waktu_mulai,
            bc.waktu_selesai,
            bc.progres,
            bc.pernah_pause,
            bc.total_pause_secs
        ');
        $this->db->from('tb_bongkaran b');
        $this->db->join('tb_bongkaran_checker bc', 'bc.id_bongkaran = b.id', 'left');
        $this->db->where('b.is_archived', 1);
        $this->db->order_by('b.archived_at', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where('tb_bongkaran', ['id' => $id])->row_array();
    }

    // Daftar karyawan dengan jobdesk CHECKER (untuk dropdown MANAGERCK)
    public function get_list_checker()
    {
        return $this->db
            ->select('nik, nm_karyawan')
            ->where('jobdesk', 'CHECKER')
            ->order_by('nm_karyawan', 'ASC')
            ->get('tb_karyawan')->result_array();
    }

    // Ambil id_bongkaran yang sedang aktif (PROSES) milik checker ini
    public function get_active_id_by_checker($nik)
    {
        $row = $this->db
            ->select('b.id')
            ->from('tb_bongkaran b')
            ->join('tb_bongkaran_checker bc', 'bc.id_bongkaran = b.id')
            ->where('bc.nik_checker', $nik)
            ->where('bc.status_checker', 'PROSES')
            ->where('b.status !=', 'DONE')
            ->limit(1)
            ->get()->row();
        return $row ? (int)$row->id : null;
    }

    public function is_taken($id)
    {
        return $this->db->get_where('tb_bongkaran_checker', ['id_bongkaran' => $id])->num_rows() > 0;
    }

    public function get_checker($id)
    {
        return $this->db->get_where('tb_bongkaran_checker', ['id_bongkaran' => $id])->row_array();
    }

    public function generate_kode()
    {
        $prefix = 'BNG' . date('dmy');
        $last   = $this->db->like('kode_bongkar', $prefix, 'after')
                            ->order_by('id', 'DESC')->limit(1)
                            ->get('tb_bongkaran')->row();
        $urut = $last ? ((int) substr($last->kode_bongkar, -4)) + 1 : 1;
        return $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }

    public function create($data)           { return $this->db->insert('tb_bongkaran', $data); }

    public function start($id, $nik, $nama, $pintu = null)
    {
        $this->db->where('id', $id)->update('tb_bongkaran', [
            'status' => 'PROSES',
            'pintu'  => $pintu,
        ]);
        return $this->db->insert('tb_bongkaran_checker', [
            'id_bongkaran'   => $id,
            'nik_checker'    => $nik,
            'nm_checker'     => $nama,
            'waktu_mulai'    => date('Y-m-d H:i:s'),
            'progres'        => 0,
            'status_checker' => 'PROSES',
        ]);
    }

    public function update_progres($id, $progres)
    {
        return $this->db->where('id_bongkaran', $id)->update('tb_bongkaran_checker', ['progres' => $progres]);
    }

    public function checker_done($id)
    {
        $this->db->where('id_bongkaran', $id)->update('tb_bongkaran_checker', [
            'progres' => 100, 'waktu_selesai' => date('Y-m-d H:i:s'), 'status_checker' => 'DONE',
        ]);
        return $this->db->where('id', $id)->update('tb_bongkaran', ['status' => 'DONE']);
    }

    public function update_status($id, $status)
    {
        return $this->db->where('id', $id)->update('tb_bongkaran', ['status' => $status]);
    }

    public function archive($id, $by)
    {
        return $this->db->where('id', $id)->update('tb_bongkaran', [
            'is_archived' => 1, 'archived_at' => date('Y-m-d H:i:s'), 'archived_by' => $by,
        ]);
    }

    // ================================================================
    // LOADING KK
    // ================================================================
    public function generate_kode_kk()
    {
        $prefix = 'KK' . date('dmy');
        $last   = $this->db->like('kode', $prefix, 'after')
                            ->order_by('id', 'DESC')->limit(1)
                            ->get('tb_loading_kk')->row();
        $urut = $last ? ((int) substr($last->kode, -4)) + 1 : 1;
        return $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }

    public function generate_kode_lk()
    {
        $prefix = 'LK' . date('dmy');
        $last   = $this->db->like('kode', $prefix, 'after')
                            ->order_by('id', 'DESC')->limit(1)
                            ->get('tb_loading_lk')->row();
        $urut = $last ? ((int) substr($last->kode, -4)) + 1 : 1;
        return $prefix . str_pad($urut, 4, '0', STR_PAD_LEFT);
    }

    public function get_list_kk()
    {
        return $this->db->where('is_archived', 0)->order_by('id', 'ASC')->get('tb_loading_kk')->result_array();
    }

    public function get_arsip_kk()
    {
        return $this->db->where('is_archived', 1)->order_by('archived_at', 'DESC')->get('tb_loading_kk')->result_array();
    }

    public function get_kk_by_id($id)
    {
        return $this->db->get_where('tb_loading_kk', ['id' => $id])->row_array();
    }

    public function create_kk($data)      { return $this->db->insert('tb_loading_kk', $data); }

    public function update_kk($id, $data)
    {
        // Jika status berubah ke DO_SELESAI → isi waktu_do_selesai otomatis
        if (isset($data['status'])) {
            if ($data['status'] === 'DO_SELESAI') {
                $data['waktu_do_selesai'] = date('Y-m-d H:i:s');
            } elseif ($data['status'] === 'CETAK_DO') {
                // Jika dikembalikan ke CETAK_DO → hapus waktu_do_selesai
                $data['waktu_do_selesai'] = null;
            }
        }
        return $this->db->where('id', $id)->update('tb_loading_kk', $data);
    }

    public function start_kk($id, $nik, $nama, $pintu = null)
    {
        $data = [
            'status'  => 'PROSES_LOADING',
            'progres' => 0,
        ];
        if ($pintu) $data['pintu'] = $pintu;
        return $this->db->where('id', $id)->update('tb_loading_kk', $data);
    }

    public function update_progres_kk($id, $progres)
    {
        return $this->db->where('id', $id)->update('tb_loading_kk', ['progres' => $progres]);
    }

    public function done_kk($id)
    {
        return $this->db->where('id', $id)->update('tb_loading_kk', [
            'status'        => 'DONE',
            'progres'       => 100,
            'waktu_selesai' => date('Y-m-d H:i:s'),
        ]);
    }

    public function archive_kk($id, $by)
    {
        return $this->db->where('id', $id)->update('tb_loading_kk', [
            'is_archived' => 1, 'archived_at' => date('Y-m-d H:i:s'), 'archived_by' => $by,
        ]);
    }

    // ================================================================
    // LOADING LK
    // ================================================================
    public function get_list_lk()
    {
        return $this->db->where('is_archived', 0)->order_by('id', 'ASC')->get('tb_loading_lk')->result_array();
    }

    public function get_arsip_lk()
    {
        return $this->db->where('is_archived', 1)->order_by('archived_at', 'DESC')->get('tb_loading_lk')->result_array();
    }

    public function get_lk_by_id($id)
    {
        return $this->db->get_where('tb_loading_lk', ['id' => $id])->row_array();
    }

    public function create_lk($data)      { return $this->db->insert('tb_loading_lk', $data); }
    
    public function update_lk($id, $data)
    {
        // Jika status berubah ke DO_SELESAI → isi waktu_do_selesai otomatis
        if (isset($data['status'])) {
            if ($data['status'] === 'DO_SELESAI') {
                $data['waktu_do_selesai'] = date('Y-m-d H:i:s');
            } elseif ($data['status'] === 'CETAK_DO') {
                // Jika dikembalikan ke CETAK_DO → hapus waktu_do_selesai
                $data['waktu_do_selesai'] = null;
            }
        }
        return $this->db->where('id', $id)->update('tb_loading_lk', $data);
    }
    public function start_lk($id, $nik, $nama, $pintu = null)
    {
        $data = [
            'status'  => 'PROSES_LOADING',
            'progres' => 0,
        ];
        if ($pintu) $data['pintu'] = $pintu;
        return $this->db->where('id', $id)->update('tb_loading_lk', $data);
    }

    public function update_progres_lk($id, $progres)
    {
        return $this->db->where('id', $id)->update('tb_loading_lk', ['progres' => $progres]);
    }

    public function done_lk($id)
    {
        return $this->db->where('id', $id)->update('tb_loading_lk', [
            'status'        => 'DONE',
            'progres'       => 100,
            'waktu_selesai' => date('Y-m-d H:i:s'),
        ]);
    }

    public function archive_lk($id, $by)
    {
        return $this->db->where('id', $id)->update('tb_loading_lk', [
            'is_archived' => 1, 'archived_at' => date('Y-m-d H:i:s'), 'archived_by' => $by,
        ]);
    }

    // ================================================================
    // ARCHIVE SEMUA YANG SUDAH DONE (tidak terbatas hari ini)
    // ================================================================
    public function archive_all_done($by)
    {
        $now      = date('Y-m-d H:i:s');
        $data_arc = ['is_archived' => 1, 'archived_at' => $now, 'archived_by' => $by];

        // Bongkaran: semua status DONE belum diarsipkan
        $this->db->where('is_archived', 0)
                 ->where('status', 'DONE')
                 ->update('tb_bongkaran', $data_arc);
        $b = $this->db->affected_rows();

        // Loading KK: semua status DONE belum diarsipkan
        $this->db->where('is_archived', 0)
                 ->where('status', 'DONE')
                 ->update('tb_loading_kk', $data_arc);
        $k = $this->db->affected_rows();

        // Loading LK: semua status DONE belum diarsipkan
        $this->db->where('is_archived', 0)
                 ->where('status', 'DONE')
                 ->update('tb_loading_lk', $data_arc);
        $l = $this->db->affected_rows();

        return ['bongkaran' => $b, 'kk' => $k, 'lk' => $l];
    }

    // Cek apakah checker ini punya job aktif di KK atau LK (PROSES_LOADING)
    public function get_active_loading_by_checker($nik)
    {
        $kk = $this->db->where('nik_checker', $nik)
                    ->where_in('status', ['PROSES_LOADING', 'PENYIAPAN_BARANG'])
                    ->where('is_archived', 0)
                    ->get('tb_loading_kk')->row();
        if ($kk) return 'KK';

        $lk = $this->db->where('nik_checker', $nik)
                    ->where_in('status', ['PROSES_LOADING', 'PENYIAPAN_BARANG'])
                    ->where('is_archived', 0)
                    ->get('tb_loading_lk')->row();
        if ($lk) return 'LK';

        return null;
    }

    // ── BONGKARAN ──────────────────────────────────────────────
    public function edit_bongkaran($id, $keterangan)
    {
        return $this->db->where('id', $id)
                        ->update('tb_bongkaran', ['keterangan' => $keterangan]);
    }
 
    public function hapus_bongkaran($id)
    {
        return $this->db->where('id', $id)
                        ->delete('tb_bongkaran');
    }
 
    // ── LOADING KK ─────────────────────────────────────────────
    public function edit_kk($id, $keterangan)
    {
        return $this->db->where('id', $id)
                        ->update('tb_loading_kk', ['keterangan' => $keterangan]);
    }
 
    public function hapus_kk($id)
    {
        return $this->db->where('id', $id)
                        ->delete('tb_loading_kk');
    }
 
    // ── LOADING LK ─────────────────────────────────────────────
    public function edit_lk($id, $keterangan)
    {
        return $this->db->where('id', $id)
                        ->update('tb_loading_lk', ['keterangan' => $keterangan]);
    }
 
    public function hapus_lk($id)
    {
        return $this->db->where('id', $id)
                        ->delete('tb_loading_lk');
    }

    // ================================================================
    // PAUSE / RESUME — BONGKARAN
    // ================================================================

    public function pause_bongkaran($id)
    {
        $row = $this->db->get_where('tb_bongkaran_checker', [
            'id_bongkaran'   => $id,
            'status_checker' => 'PROSES',
            'is_paused'      => 0,
        ])->row_array();
 
        if (!$row) return false;
 
        return $this->db->where('id_bongkaran', $id)
                        ->update('tb_bongkaran_checker', [
                            'is_paused'    => 1,
                            'paused_at'    => date('Y-m-d H:i:s'),
                            'pernah_pause' => 1,
                        ]);
    }

    public function resume_bongkaran($id)
    {
        $row = $this->db->get_where('tb_bongkaran_checker', ['id_bongkaran' => $id])->row_array();
        if (!$row || !$row['is_paused'] || empty($row['paused_at'])) return false;
 
        $tambah = time() - strtotime($row['paused_at']);
        $total  = (int)$row['total_pause_secs'] + max(0, $tambah);
 
        return $this->db->where('id_bongkaran', $id)
                        ->update('tb_bongkaran_checker', [
                            'is_paused'        => 0,
                            'paused_at'        => null,
                            'total_pause_secs' => $total,
                        ]);
    }
 
    public function pause_kk($id)
    {
        return $this->db->where('id', $id)
                        ->where('status', 'PROSES_LOADING')
                        ->where('is_paused', 0)
                        ->update('tb_loading_kk', [
                            'is_paused'    => 1,
                            'paused_at'    => date('Y-m-d H:i:s'),
                            'pernah_pause' => 1,
                        ]);
    }
 
    public function resume_kk($id)
    {
        $row = $this->db->get_where('tb_loading_kk', ['id' => $id])->row_array();
        if (!$row || !$row['is_paused'] || empty($row['paused_at'])) return false;
 
        $tambah = time() - strtotime($row['paused_at']);
        $total  = (int)$row['total_pause_secs'] + max(0, $tambah);
 
        return $this->db->where('id', $id)
                        ->update('tb_loading_kk', [
                            'is_paused'        => 0,
                            'paused_at'        => null,
                            'total_pause_secs' => $total,
                        ]);
    }
 
    public function pause_lk($id)
    {
        return $this->db->where('id', $id)
                        ->where('status', 'PROSES_LOADING')
                        ->where('is_paused', 0)
                        ->update('tb_loading_lk', [
                            'is_paused'    => 1,
                            'paused_at'    => date('Y-m-d H:i:s'),
                            'pernah_pause' => 1,
                        ]);
    }
 
    public function resume_lk($id)
    {
        $row = $this->db->get_where('tb_loading_lk', ['id' => $id])->row_array();
        if (!$row || !$row['is_paused'] || empty($row['paused_at'])) return false;
 
        $tambah = time() - strtotime($row['paused_at']);
        $total  = (int)$row['total_pause_secs'] + max(0, $tambah);
 
        return $this->db->where('id', $id)
                        ->update('tb_loading_lk', [
                            'is_paused'        => 0,
                            'paused_at'        => null,
                            'total_pause_secs' => $total,
                        ]);
    }

    // ================================================================
    // PENYIAPAN BARANG — LOADING KK
    // ================================================================

    public function start_siapkan_kk($id, $nik, $nama, $pintu = null)
    {
        $data = [
            'status'              => 'PENYIAPAN_BARANG',
            'nik_checker'         => $nik,
            'nm_checker'          => $nama,
            'progres_siapkan'     => 0,
            'waktu_mulai_siapkan' => date('Y-m-d H:i:s'),
            'waktu_mulai'         => date('Y-m-d H:i:s'), // ← waktu mulai dihitung dari sini
        ];
        if ($pintu) $data['pintu'] = $pintu;
        return $this->db->where('id', $id)->update('tb_loading_kk', $data);
    }

    public function update_progres_siapkan_kk($id, $progres)
    {
        return $this->db->where('id', $id)
                        ->update('tb_loading_kk', ['progres_siapkan' => $progres]);
    }

    public function done_siapkan_kk($id)
    {
        return $this->db->where('id', $id)->update('tb_loading_kk', [
            'status'               => 'SIAP_LOADING',
            'progres_siapkan'      => 100,
            'waktu_selesai_siapkan'=> date('Y-m-d H:i:s'),
            'is_paused_siapkan'    => 0,
            'paused_at_siapkan'    => null,
        ]);
    }

    public function pause_siapkan_kk($id)
    {
        return $this->db->where('id', $id)
                        ->where('status', 'PENYIAPAN_BARANG')
                        ->where('is_paused_siapkan', 0)
                        ->update('tb_loading_kk', [
                            'is_paused_siapkan'    => 1,
                            'paused_at_siapkan'    => date('Y-m-d H:i:s'),
                            'pernah_pause_siapkan' => 1,
                        ]);
    }

    public function resume_siapkan_kk($id)
    {
        $row = $this->db->get_where('tb_loading_kk', ['id' => $id])->row_array();
        if (!$row || !$row['is_paused_siapkan'] || empty($row['paused_at_siapkan'])) return false;

        $tambah = time() - strtotime($row['paused_at_siapkan']);
        $total  = (int)$row['total_pause_secs_siapkan'] + max(0, $tambah);

        return $this->db->where('id', $id)->update('tb_loading_kk', [
            'is_paused_siapkan'        => 0,
            'paused_at_siapkan'        => null,
            'total_pause_secs_siapkan' => $total,
        ]);
    }

    // ================================================================
    // PENYIAPAN BARANG — LOADING LK
    // ================================================================

    public function start_siapkan_lk($id, $nik, $nama, $pintu = null)
    {
        $data = [
            'status'              => 'PENYIAPAN_BARANG',
            'nik_checker'         => $nik,
            'nm_checker'          => $nama,
            'progres_siapkan'     => 0,
            'waktu_mulai_siapkan' => date('Y-m-d H:i:s'),
            'waktu_mulai'         => date('Y-m-d H:i:s'), // ← waktu mulai dihitung dari sini
        ];
        if ($pintu) $data['pintu'] = $pintu;
        return $this->db->where('id', $id)->update('tb_loading_lk', $data);
    }

    public function update_progres_siapkan_lk($id, $progres)
    {
        return $this->db->where('id', $id)
                        ->update('tb_loading_lk', ['progres_siapkan' => $progres]);
    }

    public function done_siapkan_lk($id)
    {
        return $this->db->where('id', $id)->update('tb_loading_lk', [
            'status'               => 'SIAP_LOADING',
            'progres_siapkan'      => 100,
            'waktu_selesai_siapkan'=> date('Y-m-d H:i:s'),
            'is_paused_siapkan'    => 0,
            'paused_at_siapkan'    => null,
        ]);
    }

    public function pause_siapkan_lk($id)
    {
        return $this->db->where('id', $id)
                        ->where('status', 'PENYIAPAN_BARANG')
                        ->where('is_paused_siapkan', 0)
                        ->update('tb_loading_lk', [
                            'is_paused_siapkan'    => 1,
                            'paused_at_siapkan'    => date('Y-m-d H:i:s'),
                            'pernah_pause_siapkan' => 1,
                        ]);
    }

    public function resume_siapkan_lk($id)
    {
        $row = $this->db->get_where('tb_loading_lk', ['id' => $id])->row_array();
        if (!$row || !$row['is_paused_siapkan'] || empty($row['paused_at_siapkan'])) return false;

        $tambah = time() - strtotime($row['paused_at_siapkan']);
        $total  = (int)$row['total_pause_secs_siapkan'] + max(0, $tambah);

        return $this->db->where('id', $id)->update('tb_loading_lk', [
            'is_paused_siapkan'        => 0,
            'paused_at_siapkan'        => null,
            'total_pause_secs_siapkan' => $total,
        ]);
    }
}