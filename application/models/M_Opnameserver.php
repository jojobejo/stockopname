<?php

class M_Opnameserver extends CI_Model
{
    var $table = 'tb_ics a';
    var $column_order = array(null, 'a.nama_barang', 'a.exp_date'); // urutan kolom untuk sorting
    var $column_search = array('a.nama_barang', 'a.exp_date'); // kolom untuk pencarian
    var $order = array('a.nama_barang' => 'asc');

    private function _get_datatables_query()
    {
        $this->db->select("a.*, (b.p * b.l * b.t) AS dimensi");
        $this->db->from($this->table);
        $this->db->join('tb_mbarang b', 'b.nm_barang = a.nama_barang');

        $i = 0;
        $search_value = isset($_POST['search']['value']) ? $_POST['search']['value'] : null;

        if ($search_value) {
            foreach ($this->column_search as $item) {
                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $search_value);
                } else {
                    $this->db->or_like($item, $search_value);
                }
                if ($i === count($this->column_search) - 1)
                    $this->db->group_end();
                $i++;
            }
        }

        if (isset($_POST['order'])) {
            $col_index = $_POST['order']['0']['column'];
            $col_dir = $_POST['order']['0']['dir'];
            if (isset($this->column_order[$col_index])) {
                $this->db->order_by($this->column_order[$col_index], $col_dir);
            }
        } else if ($this->order) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();

        $length = isset($_POST['length']) ? (int)$_POST['length'] : 10;
        $start = isset($_POST['start']) ? (int)$_POST['start'] : 0;

        if ($length != -1) {
            $this->db->limit($length, $start);
        }

        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->get()->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        $this->db->join('tb_mbarang b', 'b.nm_barang = a.nama_barang');
        return $this->db->count_all_results();
    }
}
