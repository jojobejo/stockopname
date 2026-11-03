<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Coba1 extends CI_Model
{

    public function get_filtered_data($limit, $offset)
    {
        $this->db->select('
        tb_suplier.nama_suplier,
        tb_master_barang.nm_barang,
        tb_dailystock.gudang,
        tb_dailystock.qty,
        tb_master_barang.qty_min,
        ');
        $this->db->from('tb_dailystock');
        $this->db->join('tb_master_barang', 'tb_dailystock.kd_barang = tb_master_barang.kode_barang');
        $this->db->join('tb_suplier', 'tb_master_barang.kd_suplier = tb_suplier.kd_suplier');
        $this->db->where('tb_dailystock.qty >', 'tb_master_barang.qty_min');
        $this->db->limit($limit, $offset); // Pagination
        $query = $this->db->get();

        return $query->result_array();
    }

    public function count_filtered_data()
    {
        $this->db->from('tb_dailystock');
        $this->db->join('tb_master_barang', 'tb_dailystock.kd_barang = tb_master_barang.kode_barang');
        $this->db->join('tb_suplier', 'tb_master_barang.kd_suplier = tb_suplier.kd_suplier');
        $this->db->where('tb_dailystock.qty >=', 'tb_master_barang.qty_min');
        return $this->db->count_all_results();
    }
}
