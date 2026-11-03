<?php
defined('BASEPATH') or exit('No direct script access allowed');


class M_Inventaris extends CI_Model
{
    public function getAllinventaris()
    {
        return $this->db->get('tb_inventaris')->result();
    } 

    public function addInventaris($data)
    {
        return $this->db->insert('tb_inventaris', $data);
    }

    public function editinventaris($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_inventaris', $data);
    }

    public function hapusinventaris($id)
    {
        return $this->db->delete('tb_inventaris', array("id" => $id));
    }
    public function getUser($kduser)
    {
        $this->db->select('*');
        $this->db->limit('6');
        $this->db->from('tb_inventaris');
        $this->db->like('nama_user', $kduser);
        return $this->db->get()->result_array();
    }
} 
