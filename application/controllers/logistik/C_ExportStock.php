<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_ExportStock extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function export()
    {
        $sql = "SELECT
        x.id,
        x.nama_barang,
        x.exp_date,
        (
            COALESCE(x.saldo_awal_qty, 0) +
            COALESCE(p.qty_in, 0) -
            COALESCE(d.qty_out, 0)
        ) AS saldo_akhir_qty,
        FLOOR((
            COALESCE(x.saldo_awal_qty, 0) +
            COALESCE(p.qty_in, 0) -
            COALESCE(d.qty_out, 0)
        ) / NULLIF(mb.p * mb.l * mb.t, 0)) AS saldo_akhir_box,
        MOD((
            COALESCE(x.saldo_awal_qty, 0) +
            COALESCE(p.qty_in, 0) -
            COALESCE(d.qty_out, 0)
        ), NULLIF(mb.p * mb.l * mb.t, 0)) AS saldo_akhir_pcs,
        COALESCE(o.qty_opname, 0) AS fisik_ics,
        FLOOR(COALESCE(o.qty_opname, 0) / NULLIF(mb.p * mb.l * mb.t, 0)) AS fisik_box,
        MOD(COALESCE(o.qty_opname, 0), NULLIF(mb.p * mb.l * mb.t, 0)) AS fisik_pcs,
        (
            COALESCE(o.qty_opname, 0) -
            (
                COALESCE(x.saldo_awal_qty, 0) +
                COALESCE(p.qty_in, 0) -
                COALESCE(d.qty_out, 0)
            )
        ) AS qty_selisih,
        FLOOR((
            COALESCE(o.qty_opname, 0) -
            (
                COALESCE(x.saldo_awal_qty, 0) +
                COALESCE(p.qty_in, 0) -
                COALESCE(d.qty_out, 0)
            )
        ) / NULLIF(mb.p * mb.l * mb.t, 0)) AS selisih_box,
        MOD((
            COALESCE(o.qty_opname, 0) -
            (
                COALESCE(x.saldo_awal_qty, 0) +
                COALESCE(p.qty_in, 0) -
                COALESCE(d.qty_out, 0)
            )
        ), NULLIF(mb.p * mb.l * mb.t, 0)) AS selisih_pcs,
        CASE
            WHEN (
                COALESCE(x.saldo_awal_qty, 0) +
                COALESCE(p.qty_in, 0) -
                COALESCE(d.qty_out, 0)
            ) = COALESCE(o.qty_opname, 0)
            THEN 'KLOP'
            ELSE 'TIDAK'
        END AS status_kesesuaian
        FROM (
            SELECT id,nama_barang, exp_date, SUM(qty) AS saldo_awal_qty , lokasi
            FROM tb_saldo_awal
            GROUP BY nama_barang, exp_date
        ) x
        LEFT JOIN (
            SELECT nama_barang, exp_date, SUM(qty) AS qty_in
            FROM tb_ics_po
            GROUP BY nama_barang, exp_date
        ) p ON p.nama_barang = x.nama_barang AND p.exp_date = x.exp_date
        LEFT JOIN (
            SELECT nama_barang, exp_date, SUM(qty) AS qty_out
            FROM tb_ics_do
            GROUP BY nama_barang, exp_date
        ) d ON d.nama_barang = x.nama_barang AND d.exp_date = x.exp_date
        LEFT JOIN (
            SELECT nama_barang, exp_date, SUM(qty) AS qty_opname
            FROM tb_ics_opname
            GROUP BY nama_barang, exp_date
        ) o ON o.nama_barang = x.nama_barang AND o.exp_date = x.exp_date
        LEFT JOIN (
            SELECT nm_barang, MAX(p) AS p, MAX(l) AS l, MAX(t) AS t , kd_system as kd
            FROM tb_master_barang
            GROUP BY nm_barang
        ) mb ON mb.nm_barang = x.nama_barang
        ORDER BY x.nama_barang, x.exp_date";

        $query = $this->db->query($sql)->result_array();

        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=report_stock_opname.xls");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo "<table border='1'>";
        echo "<tr>
                <th>ID</th>
                <th>Nama Barang</th>
                <th>Exp Date</th>
                <th>Saldo Akhir Qty</th>
                <th>Saldo Akhir Box</th>
                <th>Saldo Akhir Pcs</th>
                <th>Fisik ICS</th>
                <th>Fisik Box</th>
                <th>Fisik Pcs</th>
                <th>Selisih Qty</th>
                <th>Selisih Box</th>
                <th>Selisih Pcs</th>
                <th>Status</th>
            </tr>";

        foreach ($query as $row) {
            echo "<tr>";
            foreach ($row as $val) {
                echo "<td>" . htmlspecialchars($val) . "</td>";
            }
            echo "</tr>";
        }

        echo "</table>";
    }
}
