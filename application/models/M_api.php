<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Api extends CI_Model
{
    private $table = 'tb_pre_do';

    public function get_all($limit = 100, $offset = 0)
    {
        return $this->db
            ->limit($limit, $offset)
            ->order_by('id', 'DESC')
            ->get($this->table)
            ->result_array();
    }

    public function get_by_kode_faktur($kode_faktur)
    {
        return $this->db
            ->where('kode_faktur', $kode_faktur)
            ->get($this->table)
            ->row_array();
    }

    public function get_by_kdupdate($kdupdate)
    {
        return $this->db
            ->where('kdupdate', $kdupdate)
            ->get($this->table)
            ->result_array();
    }
}
