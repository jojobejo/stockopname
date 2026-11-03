<?php
defined('BASEPATH') or exit('No direct script access allowed');


class M_Sales extends CI_Model
{
    public function getAll()
    {
        return $this->db->get('tb_user')->result();
    }
}
