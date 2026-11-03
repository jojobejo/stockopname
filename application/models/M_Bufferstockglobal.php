<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Bufferstockglobal extends CI_Model

{

    var $column_order = array('kodebarang', 'nmsuplier', 'nmbarang', 'qty_box', 'qty_pcs');
    var $column_search = array('nmsuplier', 'nmbarang');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query()
    {
        $this->db->select([
            'x.nmsuplier',
            'x.nmbarang',
            'x.qty_box',
            'x.qty_pcs',
            'x.kdbarang'
        ]);
        $this->db->from('(SELECT 
                a.kd_barang AS kdbarang,
                b.nama_suplier AS nmsuplier,
                c.nm_barang AS nmbarang,
                c.satuan AS satuan,
                c.qty_min AS qtymin,
                (SELECT SUM(d.qty) FROM tb_dailystock_global d WHERE d.kd_barang = a.kd_barang) AS qty,
                (c.p * c.l * c.t) AS dimensi,
                FLOOR(SUM(a.qty) / (c.p * c.l * c.t)) AS qty_box,
                (SUM(a.qty) - FLOOR(SUM(a.qty) / (c.p * c.l * c.t)) * (c.p * c.l * c.t)) AS qty_pcs
            FROM tb_dailystock_global a
            JOIN tb_suplier b ON b.kd_suplier = a.kd_suplier
            JOIN tb_master_barang c ON c.kode_barang = a.kd_barang
            GROUP BY c.nm_barang , c.kode_barang) AS x', false);
        $this->db->where('x.qty >', 0);

        $i = 0;

        foreach ($this->column_search as $item) {
            if ($_POST['search']['value']) {

                if ($i === 0) {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->select([
            'x.nmsuplier',
            'x.nmbarang',
            'x.qty_box',
            'x.qty_pcs'
        ]);
        $this->db->from('(SELECT 
                a.kd_barang AS kdbarang,
                b.nama_suplier AS nmsuplier,
                c.nm_barang AS nmbarang,
                c.satuan AS satuan,
                c.qty_min AS qtymin,
                (SELECT SUM(d.qty) FROM tb_dailystock_global d WHERE d.kd_barang = a.kd_barang) AS qty,
                (c.p * c.l * c.t) AS dimensi,
                FLOOR(SUM(a.qty) / (c.p * c.l * c.t)) AS qty_box,
                (SUM(a.qty) - FLOOR(SUM(a.qty) / (c.p * c.l * c.t)) * (c.p * c.l * c.t)) AS qty_pcs
            FROM tb_dailystock_global a
            JOIN tb_suplier b ON b.kd_suplier = a.kd_suplier
            JOIN tb_master_barang c ON c.kode_barang = a.kd_barang
            GROUP BY c.nm_barang,c.kode_barang) AS x', false);
        $this->db->where('x.qty >', 0);

        return $this->db->count_all_results();
    }
}
