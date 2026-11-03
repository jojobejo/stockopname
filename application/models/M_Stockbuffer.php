<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Stockbuffer extends CI_Model

{

    var $column_order = array('kodebarang', 'nmsuplier', 'nmbarang', 'qty_box', 'qty_pcs');
    var $column_search = array('nmsuplier', 'nmbarang');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_datatables_query($where)
    {
        $this->db->select("
        x.nmsuplier,
        x.nmbarang,
        x.nmgudang,
        x.satuan,
        x.qty,
        x.qtymin,
        x.dimensi,
        FLOOR(x.qty / x.dimensi) AS qty_box,
        x.qty - (FLOOR(x.qty / x.dimensi)) * (x.dimensi) AS qty_pcs");
        $this->db->from("(SELECT 
        c.nama_suplier AS nmsuplier,
        b.nm_barang AS nmbarang,
        a.gudang AS nmgudang,
        b.satuan AS satuan,
        SUM(a.qty) AS qty,
        b.qty_min AS qtymin,
        (b.p * b.l * b.t) AS dimensi,
            CASE
                WHEN a.gudang = 'Gdg. Induk' THEN 2
                WHEN a.gudang = 'Gdg. Rusak' THEN 3
            END AS idgudang
        FROM tb_dailystock a
        JOIN tb_master_barang b ON b.kode_barang = a.kd_barang
        JOIN tb_suplier c ON c.kd_suplier = a.kd_suplier
        GROUP BY a.gudang, b.nm_barang
        ) AS x");
        $this->db->where('x.idgudang', "$where");
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

    function get_datatables($id)
    {
        $this->_get_datatables_query($id);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($id)
    {
        $this->_get_datatables_query($id);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($id)
    {
        $this->db->select("
        x.nmsuplier,
        x.nmbarang,
        x.nmgudang,
        x.satuan,
        x.qty,
        x.qtymin,
        x.dimensi,
        FLOOR(x.qty / x.dimensi) AS qty_box,
        x.qty - (FLOOR(x.qty / x.dimensi)) * (x.dimensi) AS qty_pcs");
        $this->db->from("(SELECT 
        c.nama_suplier AS nmsuplier,
        b.nm_barang AS nmbarang,
        a.gudang AS nmgudang,
        b.satuan AS satuan,
        SUM(a.qty) AS qty,
        b.qty_min AS qtymin,
        (b.p * b.l * b.t) AS dimensi,
            CASE
                WHEN a.gudang = 'Gdg. Induk' THEN 2
                WHEN a.gudang = 'Gdg. Rusak' THEN 3
            END AS idgudang
        FROM tb_dailystock a
        JOIN tb_master_barang b ON b.kode_barang = a.kd_barang
        JOIN tb_suplier c ON c.kd_suplier = a.kd_suplier
        GROUP BY a.gudang, b.nm_barang
        ) AS x");
        $this->db->where('x.idgudang', "$id");
        $this->db->where('x.qty >', 0);
        $query = $this->db->get();
        return $query->result();
        return $this->db->count_all_results();
    }
}
