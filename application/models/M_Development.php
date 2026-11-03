<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class M_Development extends CI_Model
{
    public function generate_update()
    {
        $cd = $this->db->query("SELECT MAX(RIGHT(kd_update,4)) AS kd_max FROM tb_stock_status WHERE DATE(create_at)=CURDATE()");
        $kd = "";
        if ($cd->num_rows() > 0) {
            foreach ($cd->result() as $k) {
                $tmp = ((int)$k->kd_max) + 1;
                $kd = sprintf("%04s", $tmp);
            }
        } else {
            $kd = "0001";
        }
        date_default_timezone_set('Asia/Jakarta');
        return 'UPD' . date('dmy') . $kd;
    }

    public function get_data_penjualan_zahir()
    {
        $this->db->select('
            a.tgl_inputer,
            a.kd_faktur,
            b.nama_customer,
            b.nama_kios,
            b.alamat_kios,
            b.regional,
            a.kd_rute,
            c.keterangan AS keterangan_rute,
            COUNT(a.kd_barang) AS total_barang,
            a.data_sts 
        ');
        $this->db->from('tb_pre_do a');
        $this->db->join('tb_customer b', 'b.kd_customer = a.kd_customer', 'inner');
        $this->db->join('tb_rutecs c', 'c.kd_rute = a.kd_rute', 'inner');
        $this->db->where('a.data_sts =', 1);
        $this->db->group_by('a.kd_faktur');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_updated_data_preparation()
    {
        return $this->db->query("SELECT
        a.*,
        b.data_sts AS statusdata
        FROM tb_stock_status a
        JOIN tb_pre_do b ON b.kdupdate = a.kd_update
        WHERE a.gudangid = '6'
        LIMIT 1
        ")->result();
    }

    public function getdo()
    {
        return $this->db->query("SELECT 
        a.kd_do AS kddo,
        a.tgl_create AS createat,
        a.tgl_pengiriman AS tglkirim,
        a.nolambung AS nopol,
        a.regional AS rute,
        (
            SELECT COUNT(DISTINCT kd_barang) 
            FROM tb_detail_do 
            WHERE kd_do = a.kd_do
        ) AS totalbarang,
        (
            SELECT COUNT(DISTINCT kd_faktur) 
            FROM tb_detail_do 
            WHERE kd_do = a.kd_do
        ) AS totalfaktur,
        a.status AS status
        FROM tb_do a
        ")->result();
    }
}
