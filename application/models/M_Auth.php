<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class M_Auth extends CI_Model
{
    private function fields($table)
    {
        static $cache = [];
        if (!isset($cache[$table])) {
            $cache[$table] = $this->db->table_exists($table) ? $this->db->list_fields($table) : [];
        }
        return $cache[$table];
    }

    private function has($table, $field)
    {
        return in_array($field, $this->fields($table), true);
    }

    function cek_username($username)
    {
        $this->db->where('username', $username);
        return $this->db->get('tb_karyawan');
    }

    function cek_password($username)
    {
        $this->db->where('username', $username);
        return $this->db->get('tb_karyawan')->result();
    }

    public function get_auth_user($username)
    {
        $candidates = $this->get_auth_candidates($username);
        return $candidates[0] ?? null;
    }

    public function get_auth_candidates($username)
    {
        $username = trim((string) $username);
        if ($username === '') {
            return [];
        }

        $candidates = [];
        $seen = [];

        $tbUsers = $this->get_tb_users_user($username);
        if ($tbUsers) {
            $key = $tbUsers->auth_source . ':' . $tbUsers->id;
            $candidates[] = $tbUsers;
            $seen[$key] = true;
        }

        $tbKaryawan = $this->get_tb_karyawan_user($username);
        if ($tbKaryawan) {
            $this->apply_tb_users_hrd_access($tbKaryawan);
            $key = $tbKaryawan->auth_source . ':' . $tbKaryawan->id;
            if (!isset($seen[$key])) {
                $candidates[] = $tbKaryawan;
                $seen[$key] = true;
            }
        }

        return $candidates;
    }

    public function get_tb_users_user($username)
    {
        if (!$this->db->table_exists('tb_users')) {
            return null;
        }

        $this->db->where('username', $username);
        $row = $this->db->get('tb_users')->row();
        if (!$row) {
            return null;
        }

        $row->auth_source = 'tb_users';
        return $row;
    }

    public function get_tb_karyawan_user($username)
    {
        $this->db->where('username', $username);
        $row = $this->db->get('tb_karyawan')->row();
        if (!$row) {
            return null;
        }

        $row->auth_source = 'tb_karyawan';
        return $row;
    }

    private function apply_tb_users_hrd_access($karyawan)
    {
        if (!$karyawan || !$this->db->table_exists('tb_users') || !$this->has('tb_users', 'jobdesk_hrd')) {
            return $karyawan;
        }

        $this->db->group_start();
        if (!empty($karyawan->username) && $this->has('tb_users', 'username')) {
            $this->db->or_where('username', $karyawan->username);
        }
        if (!empty($karyawan->nm_karyawan) && $this->has('tb_users', 'nama_lngkp')) {
            $this->db->or_where('nama_lngkp', $karyawan->nm_karyawan);
        }
        if (!empty($karyawan->nik) && $this->has('tb_users', 'nik')) {
            $this->db->or_where('nik', $karyawan->nik);
        }
        $this->db->group_end();

        $this->db->where('jobdesk_hrd', 'inputer_laporan');
        if ($this->has('tb_users', 'status')) {
            $this->db->where('status', 1);
        }

        $tbUsers = $this->db->get('tb_users')->row();
        if (!$tbUsers) {
            return $karyawan;
        }

        $karyawan->jobdesk_hrd = $tbUsers->jobdesk_hrd ?? 'inputer_laporan';
        $karyawan->default_redirect = $tbUsers->default_redirect ?? 'penilaian_lingkungan';
        $karyawan->tb_users_match_id = $tbUsers->id ?? null;

        return $karyawan;
    }

    public function verify_password($plainPassword, $storedPassword)
    {
        $plainPassword = (string) $plainPassword;
        $storedPassword = (string) $storedPassword;

        if ($storedPassword === '') {
            return false;
        }

        $info = password_get_info($storedPassword);
        if (!empty($info['algo'])) {
            return password_verify($plainPassword, $storedPassword);
        }

        return hash_equals($storedPassword, $plainPassword);
    }

    public function password_should_be_hashed($storedPassword)
    {
        $storedPassword = (string) $storedPassword;
        if ($storedPassword === '') {
            return false;
        }

        $info = password_get_info($storedPassword);
        if ((int) $info['algo'] === 0) {
            return true;
        }

        return password_needs_rehash($storedPassword, PASSWORD_DEFAULT);
    }

    public function update_password_hash($id, $table, $plainPassword)
    {
        if (!$id || !$this->has($table, 'password')) {
            return false;
        }

        return $this->db
            ->where('id', $id)
            ->update($table, ['password' => password_hash((string) $plainPassword, PASSWORD_DEFAULT)]);
    }

    public function update_last_login($id, $table = 'tb_karyawan')
    {
        if (!$this->has($table, 'last_login')) {
            return true;
        }
        return $this->db->where('id', $id)->update($table, ['last_login' => date('Y-m-d H:i:s')]);
    }

    public function log_login($user, $status, $message = null)
    {
        if (!$this->db->table_exists('tb_login_log')) {
            return true;
        }

        $payload = [
            'id_karyawan' => $user->id ?? null,
            'nik' => $user->nik ?? null,
            'username' => $user->username ?? null,
            'ip_address' => $this->input->ip_address(),
            'user_agent' => $this->input->user_agent(),
            'status' => $status,
            'message' => $message,
            'created_at' => date('Y-m-d H:i:s'),
        ];

        $payload = array_intersect_key($payload, array_flip($this->fields('tb_login_log')));
        return empty($payload) ? true : $this->db->insert('tb_login_log', $payload);
    }
}
