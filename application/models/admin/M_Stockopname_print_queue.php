<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Stockopname_print_queue extends CI_Model
{
    private $table = 'stockopname_print_queue';

    public function __construct()
    {
        parent::__construct();
        $this->ensure_table();
    }

    public function ensure_table()
    {
        if (!$this->db->table_exists($this->table)) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `{$this->table}` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `username` VARCHAR(100) NOT NULL,
                `kode_barang` VARCHAR(50) NOT NULL,
                `nama_barang` VARCHAR(255) NOT NULL,
                `expired_date` VARCHAR(20) NOT NULL,
                `copies` INT NOT NULL DEFAULT 1,
                `created_at` DATETIME NOT NULL,
                `updated_at` DATETIME NOT NULL,
                INDEX `idx_user` (`username`),
                UNIQUE KEY `uq_user_item` (`username`, `kode_barang`, `expired_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
        }
    }

    public function get_queue($username)
    {
        $username = trim((string)$username);
        if ($username === '') {
            return [];
        }

        return $this->db
            ->where('username', $username)
            ->order_by('id', 'ASC')
            ->get($this->table)
            ->result_array();
    }

    public function add_item($username, $kodeBarang, $namaBarang, $expiredDate, $copies = 1)
    {
        $username = trim((string)$username);
        $kodeBarang = trim((string)$kodeBarang);
        $namaBarang = trim((string)$namaBarang);
        $expiredDate = trim((string)$expiredDate);
        $copies = max(1, (int)$copies);

        if ($username === '' || $kodeBarang === '') {
            return false;
        }

        $now = date('Y-m-d H:i:s');
        $existing = $this->db
            ->where('username', $username)
            ->where('kode_barang', $kodeBarang)
            ->where('expired_date', $expiredDate)
            ->get($this->table)
            ->row_array();

        if ($existing) {
            $newCopies = max(1, (int)$existing['copies'] + $copies);
            $this->db->where('id', $existing['id'])->update($this->table, [
                'nama_barang' => $namaBarang !== '' ? $namaBarang : $existing['nama_barang'],
                'copies'      => $newCopies,
                'updated_at'  => $now,
            ]);
            return (int)$existing['id'];
        }

        $this->db->insert($this->table, [
            'username'     => $username,
            'kode_barang'  => $kodeBarang,
            'nama_barang'  => $namaBarang,
            'expired_date' => $expiredDate,
            'copies'       => $copies,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        return (int)$this->db->insert_id();
    }

    public function update_copies($username, $id, $copies)
    {
        $username = trim((string)$username);
        $id = (int)$id;
        $copies = max(1, (int)$copies);

        if ($username === '' || $id <= 0) {
            return false;
        }

        return $this->db
            ->where('id', $id)
            ->where('username', $username)
            ->update($this->table, [
                'copies'     => $copies,
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public function remove_item($username, $id)
    {
        $username = trim((string)$username);
        $id = (int)$id;

        if ($username === '' || $id <= 0) {
            return false;
        }

        return $this->db
            ->where('id', $id)
            ->where('username', $username)
            ->delete($this->table);
    }

    public function clear_queue($username)
    {
        $username = trim((string)$username);
        if ($username === '') {
            return false;
        }

        return $this->db
            ->where('username', $username)
            ->delete($this->table);
    }
}
