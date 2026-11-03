<?php
defined('BASEPATH') or exit('No direct script access allowed');


class M_Pelanggan extends CI_Model
{
    public function getkpi($kduser)
    {
        return $this->db->query("SELECT * FROM tb_kpiwhat
        ");
    }
    public function addnilai($data)
    {
        return $this->db->insert('tb_ipkp', $data);
    }
    public function getwhtkpi($kduser, $bln)
    {
        return $this->db->query("SELECT * FROM tb_kpi_wht WHERE kd_user = '$kduser' AND bln_pembuatan = '$bln'
        ");
    }
    public function get_tujuan_kpi($kduser, $bln)
    {
        return $this->db->query("SELECT * FROM tb_kpi_wht WHERE kd_user = '$kduser' AND bln_pembuatan = '$bln'
        ");
    }
    public function getdatapelanggan()
    {
        return $this->db->get('tb_ipkp')->result();
    }
    public function nilaiall()
    {
        return $this->db->query("SELECT 
        COUNT(a.id) as tot_isi,
        SUM(a.nilai_kp) as nilai_kp_all,
        SUM(a.nilai_kp) / COUNT(a.id) as hasil_nilai
        FROM tb_ipkp a        
        ");
    }
}
