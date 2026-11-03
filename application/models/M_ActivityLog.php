<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * M_ActivityLog.php
 * Model untuk mencatat setiap aksi pada Sales Order.
 *
 * Cara pakai di controller:
 *   $this->load->model('M_ActivityLog');
 *   $this->M_ActivityLog->log('SO2504001', 'INV001', 'CREATE', 'SO baru dibuat', 'Budi');
 */
class M_ActivityLog extends CI_Model
{
    const TABLE = 'tbso_activity_log';

    /**
     * Catat satu baris activity log.
     *
     * @param string $no_so         Nomor SO
     * @param string $no_faktur     Nomor faktur
     * @param string $aksi          Jenis aksi: CREATE | UPDATE | CANCEL | APPROVE | REJECT
     * @param string $keterangan    Detail keterangan bebas
     * @param string $oleh          Nama user yang melakukan
     */
    public function log($no_so, $no_faktur, $aksi, $keterangan = '', $oleh = 'system', $detail_produk = '')
    {
        $this->db->insert(self::TABLE, [
            'no_so'          => $no_so          ?: null,
            'no_faktur'      => $no_faktur      ?: null,
            'aksi'           => strtoupper($aksi),
            'keterangan'     => $keterangan     ?: null,
            'detail_produk'  => $detail_produk  ?: null,
            'dilakukan_oleh' => $oleh           ?: 'system',
            'ip_address'     => $this->input->ip_address(),
            'created_at'     => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Ambil semua log untuk satu nomor SO, urut terbaru.
     */
    public function get_by_no_so($no_so)
    {
        return $this->db
            ->where('no_so', $no_so)
            ->order_by('created_at', 'DESC')
            ->get(self::TABLE)
            ->result_array();
    }

    /**
     * Ambil semua log (untuk halaman audit/admin).
     */
    public function get_all($limit = 200, $offset = 0)
    {
        return $this->db
            ->order_by('created_at', 'DESC')
            ->limit($limit, $offset)
            ->get(self::TABLE)
            ->result_array();
    }

    /**
     * Filter log dengan berbagai kriteria + pagination.
     */
    public function get_filtered($filter = [], $limit = 20, $offset = 0)
    {
        $this->_applyFilter($filter);
        return $this->db
            ->order_by('created_at', 'DESC')
            ->limit($limit, $offset)
            ->get(self::TABLE)
            ->result_array();
    }

    public function count_filtered($filter = [])
    {
        $this->_applyFilter($filter);
        return $this->db->count_all_results(self::TABLE);
    }

    private function _applyFilter($filter)
    {
        if (!empty($filter['no_so']))   $this->db->like('no_so',   $filter['no_so']);
        if (!empty($filter['aksi']))    $this->db->where('aksi',   strtoupper($filter['aksi']));
        if (!empty($filter['tanggal'])) $this->db->where('DATE(created_at)', $filter['tanggal']);
        if (!empty($filter['keyword'])) {
            $this->db->group_start();
            $this->db->like('keterangan',     $filter['keyword']);
            $this->db->or_like('dilakukan_oleh', $filter['keyword']);
            $this->db->or_like('no_faktur',   $filter['keyword']);
            $this->db->group_end();
        }
    }
}