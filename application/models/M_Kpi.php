<?php
defined('BASEPATH') or exit('No direct script access allowed');


class M_Kpi extends CI_Model
{
    public function getkpi($kduser)
    {
        return $this->db->query("SELECT * 
        FROM tb_kpi_indikator WHERE kode_user = '$kduser'
        ");
    }
    public function add_indikator_kpi($data)
    {
        return $this->db->insert('tb_kpi_indikator', $data);
    }
    public function edit_indikator_kpi($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_kpi_indikator', $data);
    }
    public function hapus_indikator_kpi($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->delete('tb_kpi_indikator', $data);
    }
}
