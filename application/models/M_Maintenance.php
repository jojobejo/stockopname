<?php
defined('BASEPATH') or exit('No direct script access allowed');


class M_Maintenance extends CI_Model
{
    public function getAll()
    {
        return $this->db->get('tb_user')->result();
    }

    public function addDataInventaris($data)
    {
        return $this->db->insert('tb_inventaris', $data);
    }

    public function editInventaris($iduser, $data)
    {
        $this->db->where('id_inven', $iduser);
        return $this->db->update('tb_inventaris', $data);
    }

    public function deleteInventaris($iduser)
    {
        $this->db->where('id_inven', $iduser);
        return $this->db->delete('tb_inventaris');
    }
}
