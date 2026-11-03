<?php
defined('BASEPATH') or exit('No direct script access allowed');


class M_User extends CI_Model
{
    public function getAll()
    {
        return $this->db->get('tb_user')->result();
    }

    public function addUser($data)
    {
        return $this->db->insert('tb_user', $data);
    }

    public function editUser($iduser, $data)
    {
        $this->db->where('id', $iduser);
        return $this->db->update('tb_user', $data);
    }

    public function deleteUser($iduser)
    {
        $this->db->where('id', $iduser);
        return $this->db->delete('tb_user');
    }
    function kduser()
    {
        $cd = $this->db->query("SELECT MAX(RIGHT(kode_user,4)) AS kd_max FROM tb_user WHERE DATE(create_at)=CURDATE()");
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
        return 'KIU' . date('dmy') . $kd;
    }
}
