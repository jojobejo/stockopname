<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class M_Extravaganza extends CI_Model
{
    function cek_username($username)
    {
        $this->db->where('username', $username);
        return $this->db->get('tb_karyawan');
    }

    function get_win($kategori, $noundi)
    {
        return $this->db
            ->where('kat_undi', $kategori)
            ->where('noundi', $noundi)
            ->get('tb_customer_list_undian')
            ->row();
    }
}
