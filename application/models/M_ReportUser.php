<?php
defined('BASEPATH') or exit('No direct script access allowed');


class M_ReportUser extends CI_Model
{
    public function get_by_user($user_id)
    {
        return $this->db->where('user_id', $user_id)
            ->order_by('tanggal', 'DESC')
            ->get('tb_laporan_harian')
            ->result();
    }

    public function insert($data)
    {
        return $this->db->insert('tb_laporan_harian', $data);
    }
}
