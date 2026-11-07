<?php

use JetBrains\PhpStorm\Internal\ReturnTypeContract;

defined('BASEPATH') or exit('No direct script access allowed');


class M_Logistik extends CI_Model
{
    //Truck Plat Config
    public function getallplat()
    {
        return $this->db->get('tb_op_plat')->result();
    }
    public function addnoplatbaru($data)
    {
        return $this->db->insert('tb_op_plat', $data);
    }
    public function editnoplat($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_op_plat', $data);
    }
    public function deletenoplat($id)
    {
        return $this->db->delete('tb_op_plat', array("id" => $id));
    }
    //Helper Config
    public function getallhelper()
    {
        return $this->db->get('tb_op_helper')->result();
    }
    public function addhelperbaru($data)
    {
        return $this->db->insert('tb_op_helper', $data);
    }
    public function edithelper($id, $data)
    {
        $this->db->where('kd_helper', $id);
        return $this->db->update('tb_op_helper', $data);
    }
    public function hapushelper($id)
    {
        return $this->db->delete('tb_op_helper', array("id" => $id));
    }
    function kd_helper()
    {
        $cd = $this->db->query("SELECT MAX(RIGHT(kd_helper,4)) AS kd_max FROM tb_op_helper WHERE DATE(create_at)=CURDATE()");
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
        return 'KIUH' . date('dmy') . $kd;
    }
    //Driver Config
    public function getalldriver()
    {
        return $this->db->get('tb_op_driver')->result();
    }
    public function getnorutdriveractive()
    {
        $this->db->select('*');
        $this->db->from('tb_op_driver');
        $this->db->where('status', 'ACTIVE');
        $this->db->order_by('no_urut_hr_i', 'ASC');
        return $this->db->get()->result();
    }
    public function adddriverbaru($data)
    {
        return $this->db->insert('tb_op_driver', $data);
    }
    public function editdriver($id, $data)
    {
        $this->db->where('kd_driver', $id);
        return $this->db->update('tb_op_driver', $data);
    }
    public function hapusdriver($id)
    {
        return $this->db->delete('tb_op_driver', array("id" => $id));
    }
    function kd_driver()
    {
        $cd = $this->db->query("SELECT MAX(RIGHT(kd_driver,4)) AS kd_max FROM tb_op_driver WHERE DATE(create_at)=CURDATE()");
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
    function get_kd_order()
    {
        $cd = $this->db->query("SELECT MAX(RIGHT(kd_order,4)) as kd_max FROM tb_order_tracking_driver WHERE DATE(create_at)=CURDATE()");
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
        return 'KIUD' . date('dmy') . $kd;
    }
    function insert_detail_order_driver($data)
    {
        return $this->db->insert('tb_det_tracking_driver', $data);
    }
    function insert_deliveri_order($data)
    {
        return $this->db->insert('tb_order_tracking_driver', $data);
    }

    function get_deliv_logistik()
    {
        return $this->db->query("SELECT 
        *, 
        COUNT(CASE WHEN  b.sts_driver = 'READY' then 1 ELSE NULL END) as 'd_ready' ,
        COUNT(CASE WHEN  b.sts_driver = 'PENDING' then 1 ELSE NULL END) as 'd_pending',
        COUNT(CASE WHEN b.sts_driver = 'ON THE ROAD' THEN 1 ELSE NULL END) as 'd_otr',
        COUNT(CASE WHEN b.sts_driver = 'WAITING' THEN 1 ELSE NULL END) as 'd_wait'
        FROM tb_order_tracking_driver a
        JOIN tb_det_tracking_driver b ON b.kd_deliveri = a.kd_order
        GROUP BY a.kd_order
        ORDER BY a.id
        ");
    }
    function get_all_do()
    {
        return $this->db->query("SELECT 
       *, 
       COUNT(CASE WHEN  b.sts_driver = 'READY' then 1 ELSE NULL END) as 'd_ready' ,
       COUNT(CASE WHEN  b.sts_driver = 'PENDING' then 1 ELSE NULL END) as 'd_pending',
       COUNT(CASE WHEN b.sts_driver = 'ON THE ROAD' THEN 1 ELSE NULL END) as 'd_otr'
       FROM tb_order_tracking_driver a
       JOIN tb_det_tracking_driver b ON b.kd_deliveri = a.kd_order
       WHERE  YEARWEEK(a.create_at, 1) = YEARWEEK(CURDATE(), 1)
       GROUP BY a.kd_order
       ORDER BY a.id
       ");
    }
    public function get_driver($kduser)
    {
        $this->db->select('*');
        $this->db->limit('6');
        $this->db->from('tb_op_driver');
        $this->db->like('nama_driver', $kduser);
        return $this->db->get()->result_array();
    }
    function get_order($kd)
    {
        $this->db->select('*');
        $this->db->from('tb_order_tracking_driver');
        $this->db->where('kd_order', $kd);
        $this->db->limit(1);
        return $this->db->get()->result();
    }
    function get_det_deliv($kd)
    {
        return $this->db->query("SELECT 
        a.kd_helper,a.jml_kios,a.tonase,a.kubikasi,d.nama_helper,a.id,a.norut,a.tgl_jalan, a.nm_toko, a.kd_deliveri , a.kd_driver ,b.nama_driver, a.kd_truk , COALESCE(c.noplat,'-') as noplat , a.destinasi , a.sts_driver , COALESCE(NULLIF(a.keterangan,''),'-') as keterangan 
        FROM tb_det_tracking_driver a JOIN tb_op_driver b ON b.kd_driver = a.kd_driver LEFT JOIN tb_op_plat c ON c.nm_truk = a.kd_truk LEFT JOIN tb_op_helper d ON d.kd_helper = a.kd_helper 
        WHERE a.kd_deliveri = '$kd' 
        GROUP BY a.kd_driver");
    }
    function export_tracking()
    {
        return $this->db->query("SELECT a.id,a.norut,a.tgl_jalan, a.nm_toko, a.kd_deliveri,b.nama_driver,d.nama_helper ,a.kd_truk , COALESCE(c.noplat,'-') as noplat , a.destinasi ,a.jml_kios,a.tonase,a.kubikasi ,a.sts_driver , COALESCE(NULLIF(a.keterangan,''),'-') as keterangan 
        FROM tb_det_tracking_driver a 
        JOIN tb_op_driver b ON b.kd_driver = a.kd_driver 
        JOIN tb_op_helper d ON d.kd_helper = a.kd_helper
        LEFT JOIN tb_op_plat c ON c.nm_truk = a.kd_truk");
    }
    function get_det_jalan_driver($kdorder, $driver)
    {
        return $this->db->query("SELECT a.id, a.kd_deliveri , a.destinasi,a.tgl_jalan ,a.kd_driver ,b.nama_driver, a.kd_truk , c.noplat , a.nm_toko FROM tb_det_tracking_driver a JOIN tb_op_driver b ON b.kd_driver = a.kd_driver JOIN tb_op_plat c ON c.nm_truk = a.kd_truk WHERE a.kd_deliveri = '$kdorder' AND a.kd_driver = '$driver';");
    }
    public function get_kd($kd)
    {
        return $this->db->query("SELECT a.id, a.kd_deliveri , a.destinasi,a.tgl_jalan ,a.kd_driver ,b.nama_driver, a.kd_truk , c.noplat , a.nm_toko FROM tb_det_tracking_driver a JOIN tb_op_driver b ON b.kd_driver = a.kd_driver JOIN tb_op_plat c ON c.nm_truk = a.kd_truk WHERE a.kd_deliveri = '$kd' LIMIT 1");
    }
    function insert_pnd_driver($data)
    {
        return $this->db->insert_batch('tb_driver_pending', $data);
    }
    function delete_tr_detail_driver($id)
    {
        return $this->db->delete('tb_det_tracking_driver', array("id" => $id));
    }
    function get_pnd_driver()
    {
        return $this->db->query("SELECT a.kd_deliveri , a.tgl_jalan , c.nama_driver , b.noplat , a.kd_truk , a.destinasi , COUNT(a.nm_toko) AS jml_toko ,a.note_pending, a.kd_driver
        FROM tb_driver_pending a
        join tb_op_plat b ON b.nm_truk = a.kd_truk
        JOIN tb_op_driver c ON c.kd_driver = a.kd_driver
        GROUP BY a.kd_deliveri , a.kd_driver
        ");
    }
    function get_det_driver_pnd($kd1, $kd2)
    {
        return $this->db->query("SELECT a.id,a.kd_deliveri , a.tgl_jalan , c.nama_driver , b.noplat , a.kd_truk , a.destinasi ,a.nm_toko ,a.note_pending, a.kd_driver
        FROM tb_driver_pending a
        join tb_op_plat b ON b.nm_truk = a.kd_truk
        JOIN tb_op_driver c ON c.kd_driver = a.kd_driver
        WHERE a.kd_deliveri = '$kd1' AND a.kd_driver = '$kd2'");
    }
    public function get_kd_det_pnd($kd)
    {
        return $this->db->query("SELECT a.id, a.kd_deliveri , a.destinasi,a.tgl_jalan ,a.kd_driver ,b.nama_driver, a.kd_truk , c.noplat , a.nm_toko FROM tb_det_tracking_driver a JOIN tb_op_driver b ON b.kd_driver = a.kd_driver JOIN tb_op_plat c ON c.nm_truk = a.kd_truk WHERE a.kd_deliveri = '$kd' LIMIT 1");
    }
    public function get_all_driver()
    {
        $this->db->select('*');
        $this->db->from('tb_op_driver');
        $this->db->where('status', 'ACTIVE');
        $this->db->order_by("no_urut_hr_i", "asc");
        return $this->db->get()->result();
    }
    public function select_kd_truk()
    {
        $this->db->select('*');
        $this->db->from('tb_op_plat');
        return $this->db->get()->result();
    }
    public function select_kd_helper()
    {
        $this->db->select('*');
        $this->db->from('tb_op_helper');
        $this->db->where('status', 'ACTIVE');
        return $this->db->get()->result();
    }
    public function get_data_driver()
    {
        return $this->db->query("SELECT b.nama_driver , b.kd_driver, 
        COUNT(CASE WHEN  a.sts_driver = 'READY' then 1 ELSE NULL END) + COUNT(CASE WHEN  a.sts_driver = 'ON THE ROAD' then 1 ELSE NULL END)  as 'd_ready',
        COUNT(CASE WHEN  a.sts_driver = 'PENDING' then 1 ELSE NULL END) as 'd_pending',
        ROUND((COUNT(CASE WHEN  a.sts_driver = 'READY' then 1 ELSE NULL END) + COUNT(CASE WHEN  a.sts_driver = 'ON THE ROAD' then 1 ELSE NULL END)) / (COUNT(CASE WHEN  a.sts_driver = 'READY' then 1 ELSE NULL END) + COUNT(CASE WHEN  a.sts_driver = 'ON THE ROAD' then 1 ELSE NULL END) + COUNT(CASE WHEN  a.sts_driver = 'PENDING' then 1 ELSE NULL END)) * 100 , 2) AS persentase
        FROM tb_det_tracking_driver a
        JOIN tb_op_driver b ON b.kd_driver = a.kd_driver
        GROUP BY a.kd_driver         
        ");
    }

    public function get_det_tracking($kd)
    {
        return $this->db->query("SELECT a.sts_driver,d.nama_helper,a.kd_deliveri , a.tgl_jalan ,a.kd_truk , COALESCE(c.noplat,'-') AS noplat , a.destinasi,COALESCE(NULLIF(a.keterangan,''),'-') AS keterangan
        FROM tb_det_tracking_driver a
        LEFT JOIN tb_op_driver b ON b.kd_driver = a.kd_driver
        LEFT JOIN tb_op_plat c on c.nm_truk = a.kd_truk
        LEFT JOIN tb_op_helper d on d.kd_helper = a.kd_helper
        WHERE b.kd_driver = '$kd'
        GROUP BY a.kd_deliveri
        ");
    }

    public function get_det_data_driver($kd)
    {
        return $this->db->query("SELECT b.nama_driver , b.kd_driver
        FROM tb_det_tracking_driver a
        JOIN tb_op_driver b ON b.kd_driver = a.kd_driver
        WHERE b.kd_driver = '$kd' 
        GROUP BY a.kd_driver 
        ");
    }

    public function get_deliv($kd)
    {
        $this->db->select('*');
        $this->db->from('tb_det_tracking_driver a');
        $this->db->join('tb_op_driver b', 'b.kd_driver = a.kd_driver', 'left');
        $this->db->join('tb_op_plat c', 'c.nm_truk = a.kd_truk', 'left');
        $this->db->join('tb_op_helper d', 'd.kd_helper = a.kd_helper', 'left');
        $this->db->where('kd_deliveri', $kd);
        return $this->db->get()->result();
    }

    public function detail_deliv($kd)
    {
        $this->db->select('*');
        $this->db->from('tb_order_tracking_driver');
        $this->db->where('kd_order', $kd);
        return $this->db->get()->result();
    }

    public function edit_detail_order_driver($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_det_tracking_driver', $data);
    }

    public function deleteorder($id)
    {
        return $this->db->delete('tb_order_tracking_driver', array("kd_order" => $id));
    }

    public function deletedetailorder($id)
    {
        return $this->db->delete('tb_det_tracking_driver', array('kd_deliveri' => $id));
    }

    public function export_lap_distribusi()
    {
        return $this->db->get('tb_lap_distribusi')->result();
    }

    function get_tmp_distribusi()
    {
        return $this->db->query("SELECT a.*,b.noplat,b.nm_truk,c.nama_driver,d.nama_helper
        FROM tb_tmp_lap_distribusi a
        join tb_op_plat b ON b.nm_truk = a.kd_truk
        JOIN tb_op_driver c ON c.kd_driver = a.kd_driver
        JOIN tb_op_helper d ON d.kd_helper = a.kd_helper
        WHERE a.status ='ready'
        ");
    }

    public function insert_lap_distribusi($data)
    {
        return $this->db->insert('tb_lap_distribusi', $data);
    }

    public function insert_do_detail_ics($data)
    {
        return $this->db->insert('tb_ics_do', $data);
    }

    public function insert_batch_do_detail_ics($data)
    {
        return $this->db->insert_batch('tb_ics_do', $data);
    }


    public function edited_tmp_lap_dis($id, $data)
    {
        $this->db->where('id_lap_dis', $id);
        return $this->db->update('tb_tmp_lap_distribusi', $data);
    }

    public function delete_tmp_lap_dis($id)
    {
        return $this->db->delete('tb_tmp_lap_distribusi', array('id_lap_dis' => $id));
    }

    public function get_grouped_regions()
    {
        $this->db->select('nama_regional, GROUP_CONCAT(id SEPARATOR ",") as ids');
        $this->db->from('your_table'); // Ganti dengan nama tabel yang sesuai
        $this->db->group_by('nama_regional');
        return $this->db->get()->result();
    }

    public function get_updated_data_preparation()
    {
        return $this->db->query("SELECT
        a.*,
        b.data_sts AS statusdata
        FROM tb_stock_status a
        JOIN tb_pre_do b ON b.kdupdate = a.kd_update
        WHERE a.gudangid = '6'
        LIMIT 1
        ")->result();
    }

    // public function get_data_penjualan_zahir()
    // {
    //     $this->db->select('
    //         a.tgl_inputer,
    //         a.kd_faktur,
    //         b.nama_customer,
    //         b.nama_kios,
    //         b.alamat_kios,
    //         b.regional,
    //         a.kd_rute,
    //         c.keterangan AS keterangan_rute,
    //         COUNT(DISTINCT a.kd_barang) AS total_barang,
    //         a.data_sts 
    //     ');
    //     $this->db->from('tb_pre_do a');
    //     $this->db->join('tb_customer b', 'b.kd_customer = a.kd_customer', 'inner');
    //     $this->db->join('tb_rutecs c', 'c.kd_rute = a.kd_rute', 'inner');
    //     $this->db->join('tb_detail_do d', 'd.kd_faktur = a.kd_faktur', 'left');
    //     $this->db->where('a.data_sts', 1);
    //     $this->db->where('d.kd_faktur IS NULL', null, false);
    //     $this->db->group_by('a.kd_faktur');
    //     $this->db->order_by('total_barang', 'DESC');

    //     $query = $this->db->get();
    //     return $query->result();
    // }

    public function get_data_penjualan_zahir()
    {
        $sql = "
            SELECT
                so.no_faktur                                        AS kd_faktur,
                so.tanggal_transaksi                                AS tgl_inputer,
                DATE_FORMAT(so.tanggal_transaksi, '%d/%m/%Y')       AS tgl_inputer_fmt,
                so.kd_customer,
                so.status                                           AS data_sts,
                c.nama_customer,
                c.nama_kios,
                c.alamat_kios,
                c.regional,
                COALESCE(r.kd_rute, c.regional)                    AS kd_rute,
                COALESCE(r.keterangan, c.regional)                  AS keterangan_rute,
                COUNT(DISTINCT sod.kd_barang)                       AS total_barang
            FROM tbso_sales_order so
            INNER JOIN tbso_sales_order_detail sod
                ON sod.no_so = so.no_so
            INNER JOIN tb_customer c
                ON c.kd_customer = so.kd_customer
            LEFT JOIN tb_rutecs r
                ON r.kd_rute = c.regional
            WHERE so.status IN ('approved', 'draft')
            AND NOT EXISTS (
                SELECT 1 FROM tb_detail_do d
                WHERE d.kd_faktur = so.no_faktur
            )
            AND NOT EXISTS (
                SELECT 1 FROM tb_tmp_detaildo t
                WHERE t.kd_faktur = so.no_faktur
            )
            GROUP BY
                so.no_so,
                so.no_faktur,
                so.tanggal_transaksi,
                so.kd_customer,
                so.status,
                c.nama_customer,
                c.nama_kios,
                c.alamat_kios,
                c.regional,
                r.kd_rute,
                r.keterangan
            ORDER BY so.tanggal_transaksi DESC
        ";

        return $this->db->query($sql)->result();
    }

    public function insert_ledger_do_draft(array $detailRows, $kd_do, $gudang_id = null)
    {
        if (empty($detailRows)) return false;

        $gudang_id = $gudang_id ?? '6';
        $rows      = [];

        foreach ($detailRows as $row) {
            $rows[] = [
                'kd_barang'    => $row['kd_barang'],
                'gudang_id'    => $gudang_id,
                'no_lot'       => $row['no_lot']      ?? null,
                'expired_date' => $row['tgl_exp']     ?? null,
                'qty'          => $row['qty'],
                'tipe'         => 'RESERVE',
                'ref_no'       => $kd_do,
                'ref_type'     => 'DELIVERY_ORDER',
                'created_at'   => date('Y-m-d H:i:s'),
            ];
        }

        return $this->db->insert_batch('tberp_stock_ledger', $rows);
    }

    public function finalize_ledger_do(array $detailRows, $kd_do, array $kd_faktur_list, $gudang_id = '6')
    {
        if (empty($detailRows)) return false;

        $now = date('Y-m-d H:i:s');

        // 1. Update tipe RESERVE → RELEASE untuk semua ledger SALES_ORDER lama
        if (!empty($kd_faktur_list)) {
            $this->db->where_in('ref_no', $kd_faktur_list);
            $this->db->where('ref_type', 'SALES_ORDER');
            $this->db->where('tipe', 'RESERVE');
            $this->db->update('tberp_stock_ledger', ['tipe' => 'RELEASE']);
        }

        // 2. Insert RESERVE baru dengan ref_type DELIVERY_ORDER
        $rows = [];
        foreach ($detailRows as $row) {
            $rows[] = [
                'kd_barang'    => $row['kd_barang'],
                'gudang_id'    => $gudang_id,
                'no_lot'       => $row['no_lot']   ?? null,
                'expired_date' => $row['tgl_exp']  ?? null,
                'qty'          => $row['qty'],
                'tipe'         => 'RESERVE',
                'ref_no'       => $kd_do,
                'ref_type'     => 'DELIVERY_ORDER',
                'created_at'   => $now,
            ];
        }

        return $this->db->insert_batch('tberp_stock_ledger', $rows);
    }

    public function get_detail_do_for_ledger($kd_do)
    {
        return $this->db->query("
            SELECT
                d.kd_barang,
                d.no_lot,
                d.tgl_exp,
                SUM(d.qty) AS qty
            FROM tb_detail_do d
            WHERE d.kd_do = ?
            GROUP BY d.kd_barang, d.no_lot, d.tgl_exp
        ", [$kd_do])->result_array();
    }

    public function get_master_barang_not_listed()
    {
        return $this->db->query("SELECT 
            a.tgl_inputer,
            DATE_FORMAT(STR_TO_DATE(a.tgl_inputer, '%e/%c/%Y'), '%d/%m/%Y') AS tgl_inputer_fmt,
            a.kd_faktur,
            b.nama_customer,
            b.nama_kios,
            a.kd_barang,
            a.nama_barang,
            COUNT(*) AS total_row
        FROM tb_pre_do a
        INNER JOIN tb_customer b
            ON b.kd_customer = a.kd_customer
        LEFT JOIN tb_master_barang_all m
            ON m.kd_barang = a.kd_barang
        WHERE m.kd_barang IS NULL
        GROUP BY a.kd_faktur, a.kd_barang
        ORDER BY STR_TO_DATE(a.tgl_inputer, '%e/%c/%Y') DESC, a.kd_faktur DESC;")->result();
    }

    public function update_kd_barang_pre_do_by_faktur($kd_faktur, $old_kd_barang, $new_kd_barang)
    {
        $this->db->where('kd_faktur', $kd_faktur);
        $this->db->where('kd_barang', $old_kd_barang);
        $this->db->update('tb_pre_do', [
            'kd_barang' => $new_kd_barang
        ]);

        return $this->db->affected_rows();
    }

    public function get_barang_not_listed()
    {
        return $this->db->query("SELECT
            a.tgl_inputer,
            DATE_FORMAT(
                STR_TO_DATE(a.tgl_inputer, '%e/%c/%Y'),
                '%d/%m/%Y'
            ) AS tgl_inputer_fmt,
            a.kd_faktur,
            b.nama_customer,
            b.nama_kios,
            b.alamat_kios,
            b.regional,
            a.kd_rute,
            c.keterangan AS keterangan_rute,
            COUNT(DISTINCT a.kd_barang) AS total_barang,
            a.data_sts,
            'Master Barang Tidak Ada' AS status_validasi
        FROM tb_pre_do a
        INNER JOIN tb_customer b
            ON b.kd_customer = a.kd_customer
        INNER JOIN tb_rutecs c
            ON c.kd_rute = a.kd_rute
        AND EXISTS (
            SELECT 1
            FROM tb_pre_do x
            LEFT JOIN tb_master_barang_all m 
                ON m.kd_barang = x.kd_barang
            WHERE x.kd_faktur = a.kd_faktur
            AND m.kd_barang IS NULL
        )
        GROUP BY 
            a.kd_faktur,
            a.tgl_inputer,
            b.nama_customer,
            b.nama_kios,
            b.alamat_kios,
            b.regional,
            a.kd_rute,
            c.keterangan,
            a.data_sts
        ORDER BY STR_TO_DATE(a.tgl_inputer, '%e/%c/%Y') DESC;")->result();
    }

    // public function get_list_by_rute($rute)
    // {
    //     $this->db->select('
    //         a.tgl_inputer,
    //         a.kd_faktur,
    //         b.nama_customer,
    //         b.nama_kios,
    //         b.alamat_kios,
    //         b.regional,
    //         a.kd_rute,
    //         c.keterangan AS keterangan_rute,
    //         COUNT(DISTINCT a.kd_barang) AS total_barang,
    //         a.data_sts 
    //     ');
    //     $this->db->from('tb_pre_do a');
    //     $this->db->join('tb_customer b', 'b.kd_customer = a.kd_customer', 'inner');
    //     $this->db->join('tb_rutecs c', 'c.kd_rute = a.kd_rute', 'inner');
    //     $this->db->join('tb_detail_do d', 'd.kd_faktur = a.kd_faktur', 'left');
    //     $this->db->where('a.data_sts', 1);
    //     $this->db->where('d.kd_faktur IS NULL', null, false);
    //     $this->db->where('a.kd_rute', $rute);
    //     $this->db->group_by('a.kd_faktur');

    //     $query = $this->db->get();
    //     return $query->result();
    // }

    public function get_list_by_rute()
    {
        return $this->db->query("
            SELECT
                DATE_FORMAT(so.tanggal_transaksi, '%d/%m/%Y') AS tgl_inputer,
                so.no_faktur        AS kd_faktur,
                c.nama_customer,
                c.nama_kios,
                c.alamat_kios,
                c.regional,
                COALESCE(r.kd_rute, c.regional)     AS kd_rute,
                COALESCE(r.keterangan, c.regional)   AS keterangan_rute,
                COUNT(DISTINCT sod.kd_barang)        AS total_barang,
                so.status                            AS data_sts
            FROM tbso_sales_order so
            JOIN tbso_sales_order_detail sod ON sod.no_so = so.no_so
            JOIN tb_customer c ON c.kd_customer = so.kd_customer
            LEFT JOIN tb_rutecs r ON r.kd_rute = c.regional
            LEFT JOIN tb_detail_do d ON d.kd_faktur = so.no_faktur
            WHERE so.status IN ('approved', 'draft')
            AND d.kd_faktur IS NULL
            GROUP BY so.no_so, so.no_faktur
        ")->result();
    }

    public function get_do_cust($kd_faktur)
    {
        return $this->db->query("
            SELECT DISTINCT
                so.no_faktur            AS kd_faktur,
                so.kd_customer,
                c.regional              AS kd_rute,
                so.tanggal_transaksi    AS tgl_inputer,
                so.status
            FROM tbso_sales_order so
            INNER JOIN tb_customer c
                ON c.kd_customer = so.kd_customer
            WHERE so.no_faktur = ?
            LIMIT 1
        ", [$kd_faktur])->result();
    }

    public function sync_so_status_by_faktur($kd_faktur, $so_status)
    {
        $this->db->where('no_faktur', $kd_faktur);
        return $this->db->update('tbso_sales_order', ['status' => $so_status]);
    }

    public function insert_tmp_detdo_batch($data)
    {
        return $this->db->insert_batch('tb_tmp_detaildo', $data);
    }

    public function insert_fakturfrom_draft_batch($data)
    {
        return $this->db->insert_batch('tb_detail_do', $data);
    }

    public function get_do_cust_byfaktur($kd_faktur)
    {
        return $this->db->query("
            SELECT
                so.no_so                                            AS id,
                so.no_faktur                                        AS kd_faktur,
                so.kd_customer,
                COALESCE(r.kd_rute, c.regional)                    AS kd_rute,
                so.tanggal_transaksi                                AS tgl_inputer,
                sod.kd_barang,
                mb.nama_barang,
                sod.qty                                             AS qty,
                COALESCE(sod.satuan, 'PCS')                         AS satuan,
                COALESCE(sod.no_lot, '')                            AS no_lot,
                sod.expired_date                                    AS tgl_exp,
                COALESCE(sod.hrg_satuan, 0)                         AS nominal_p,
                0                                                   AS jtempo,
                1                                                   AS barang_sts
            FROM tbso_sales_order so
            INNER JOIN tbso_sales_order_detail sod
                ON sod.no_so = so.no_so
            INNER JOIN tb_customer c
                ON c.kd_customer = so.kd_customer
            LEFT JOIN tb_rutecs r
                ON r.kd_rute = c.regional
            INNER JOIN tb_master_barang_all mb
                ON mb.kd_barang = sod.kd_barang
            WHERE so.no_faktur = ?
        ", [$kd_faktur])->result();
    }

    public function get_detail_do_not_exist_saldo_awal($kd_faktur)
    {
        $sql = "SELECT
            m.kode_barang_system as kode_barang_system ,
            d.kd_barang AS kode_barang_zahir,
            d.nama_barang AS nama_barang,
            s.wilayah_id AS wilayah_id,
            s.koordinat_id AS koordinat_id,
            s.barang_pic AS barang_pic,
            d.qty AS qty,
            d.no_lot AS nolot,
            DATE_FORMAT(STR_TO_DATE(d.tgl_exp, '%m/%d/%Y'),'%m/%d/%Y') AS exp_date
        FROM tb_detail_do d
        LEFT JOIN tb_saldo_awal s
            ON s.kode_barang_zahir = d.kd_barang
        AND s.exp_date = DATE_FORMAT(
                STR_TO_DATE(d.tgl_exp, '%m/%d/%Y'),
                '%m/%d/%Y')
        LEFT JOIN tb_master_barang_all m
            ON m.kd_barang = d.kd_barang
        WHERE d.kd_do = '$kd_faktur'
        AND s.exp_date IS NULL;";
        return $this->db->query($sql, [$kd_faktur])->result();
    }


    public function get_do_cust_byfaktur_ics($kd_do)
    {
        return $this->db->query("
            SELECT
                a.kd_do,
                a.kd_faktur,
                a.tgl_transaksi,
                a.kd_barang,
                a.nama_barang,
                a.qty,
                a.tgl_exp,
                a.no_lot
            FROM tb_detail_do a
            WHERE a.kd_do = ?
        ", [$kd_do])->result();
    }

    public function update_pre_do_delivery_at($kd_faktur_list, $dateprenow)
    {
        $this->db->where_in('kd_faktur', $kd_faktur_list);
        return $this->db->update('tb_pre_do', [
            'delivery_at' => $dateprenow
        ]);
    }


    public function det_do_cust($kd)
    {
        // Gunakan kembali logika yang sama dengan get_do_cust_byfaktur
        return $this->get_do_cust_byfaktur($kd);
    }

    public function insert_tmp_do($data)
    {
        return $this->db->insert('tb_tmp_do', $data);
    }

    public function insertlog_do($data)
    {
        return $this->db->insert('tb_log_do', $data);
    }

    public function insert_tmp_detdo($data)
    {
        if (isset($data['barang_sts']) && $data['barang_sts'] != 3) {
            return $this->db->insert('tb_tmp_detaildo', $data);
        }
    }

    public function update_sts_pre_do($kd_faktur, $data)
    {
        $status_map = [
            '1' => 'draft',
            '2' => 'in_delivery',
            '3' => 'in_progress',
            '4' => 'in_delivery',
        ];

        $so_status = null;
        if (isset($data['data_sts'])) {
            $so_status = $status_map[$data['data_sts']] ?? null;
        }

        if ($so_status !== null) {
            $this->db->where('no_faktur', $kd_faktur);
            return $this->db->update('tbso_sales_order', ['status' => $so_status]);
        }

        return false;
    }

    public function updatedsts($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_pre_do', $data);
    }

    public function edit_faktur_customer($kd, $data)
    {
        $this->db->where('kd_faktur', $kd);
        return $this->db->update('tb_pre_do', $data);
    }

    public function updated_repost_do($kd, $data)
    {
        $this->db->where('kd_do', $kd);
        return $this->db->update('tb_detail_do', $data);
    }

    public function deletetmp_detdo($kd)
    {
        return $this->db->delete('tb_tmp_do', array("kd_do" => $kd));
    }
    public function delete_faktur_cus($kd)
    {
        return $this->db->delete('tb_detail_do', array("kd_faktur" => $kd));
    }
    public function delete_faktur_customer($kd)
    {
        return $this->db->delete('tb_pre_do', array("kd_faktur" => $kd));
    }

    public function deletetmp_do($kd)
    {
        return $this->db->delete('tb_tmp_detaildo', array("kd_do" => $kd));
    }
    public function del_tmp_do($kd)
    {
        return $this->db->delete('tb_tmp_do', array("kd_faktur" => $kd));
    }

    public function del_tmp_do_det($kd)
    {
        return $this->db->delete('tb_tmp_detaildo', array("kd_faktur" => $kd));
    }

    public function truncateitm($kd, $sts)
    {
        return $this->db->delete('tb_pre_do', array("kdupdate" => $kd, "upload_sts" => $sts));
    }
    public function truncatests($kd)
    {
        return $this->db->delete('tb_stock_status', array("kd_update" => $kd));
    }

    public function get_tmp_do()
    {
        return $this->db->query("
            SELECT
                a.id,
                a.kd_faktur,
                a.norut_do,
                c.nama_customer,
                c.alamat_kios,
                c.regional,
                COALESCE(r.kd_rute, c.regional) AS kdrute,
                c.telp1,
                c.telp2,
                COALESCE(c.jam_buka_tutup, '-')        AS jam_buka_tutup,
                COALESCE(c.karakteristik_kios, '-')    AS toko
            FROM tb_tmp_do a
            JOIN tbso_sales_order so ON so.no_faktur = a.kd_faktur
            JOIN tb_customer c ON c.kd_customer = so.kd_customer
            LEFT JOIN tb_rutecs r ON r.kd_rute = c.regional
            GROUP BY a.kd_faktur
        ")->result();
    }

    public function getkdfaktur($kd)
    {
        return $this->db->query("
            SELECT
                a.kd_faktur,
                a.norut_do
            FROM tb_tmp_do a 
            WHERE a.kd_faktur = ?
        ", [$kd]);
    }

    public function update_sts_detail_checker($data, $id)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_detail_do', $data);
    }

    public function update_note_faktur($kd_faktur, $note_faktur)
    {
        $this->db->where('kd_faktur', $kd_faktur);
        return $this->db->update('tb_detail_do', [
            'note_faktur' => $note_faktur
        ]);
    }

    public function update_checker_detail_done($kd, $sts, $data)
    {
        $this->db->where('kd_do', $kd);
        $this->db->where('status', $sts);
        return $this->db->update('tb_detail_do', $data);
    }
    public function update_checker_done($kd, $data)
    {
        $this->db->where('kd_do', $kd);
        return $this->db->update('tb_do', $data);
    }

    /**
     * Update status konfirmasi sales pada tb_do
     */
    public function update_sales_confirm($kd_do, $action, $confirm_by, $note = '')
    {
        $now = date('Y-m-d H:i:s');

        // Update tb_do
        $this->db->where('kd_do', $kd_do);
        $this->db->update('tb_do', [
            'sales_confirm_status' => $action,       // 'siap' atau 'belum_siap'
            'sales_confirm_by'     => $confirm_by,
            'sales_confirm_at'     => $now,
            'sales_confirm_note'   => $note,
            // Jika siap → status 3 (On Delivery), jika belum siap tetap 2
            'status'               => ($action === 'siap') ? 3 : 2
        ]);

        // Insert log
        $this->db->insert('tb_log_confirm_sales', [
            'kd_do'      => $kd_do,
            'action'     => $action,
            'note'       => $note,
            'confirm_by' => $confirm_by,
            'confirm_at' => $now
        ]);

        return $this->db->affected_rows();
    }

    /**
     * Ambil list DO untuk halaman Sales — hanya status 2 (menunggu konfirmasi)
     * dan status 3 (sudah on delivery)
     */
    public function get_do_for_sales()
    {
        return $this->db->query("
            SELECT
                a.kd_do                     AS kddo,
                a.tgl_create                AS createat,
                a.tgl_pengiriman            AS tglkirim,
                a.nolambung                 AS nopol,
                a.regional                  AS rute,
                a.status,
                a.sales_confirm_status,
                a.sales_confirm_by,
                a.sales_confirm_at,
                a.sales_confirm_note,
                (
                    SELECT COUNT(DISTINCT kd_barang)
                    FROM tb_detail_do
                    WHERE kd_do = a.kd_do
                ) AS totalbarang,
                (
                    SELECT COUNT(DISTINCT kd_faktur)
                    FROM tb_detail_do
                    WHERE kd_do = a.kd_do
                ) AS totalfaktur
            FROM tb_do a
            WHERE a.status IN (2, 3)
            AND (
                SELECT COUNT(DISTINCT kd_faktur)
                FROM tb_detail_do
                WHERE kd_do = a.kd_do
            ) > 0
            ORDER BY a.tgl_create DESC
        ")->result();
    }

    /**
     * Ambil list DO di body.php — sekarang status 1=draft, 2=menunggu sales, 3=on delivery
     */
    public function getdo()
    {
        return $this->db->query("
            SELECT
                a.kd_do                     AS kddo,
                a.tgl_create                AS createat,
                a.tgl_pengiriman            AS tglkirim,
                a.nolambung                 AS nopol,
                a.regional                  AS rute,
                a.status,
                a.sales_confirm_status,
                a.sales_confirm_by,
                a.sales_confirm_at,
                (
                    SELECT COUNT(DISTINCT kd_barang)
                    FROM tb_detail_do
                    WHERE kd_do = a.kd_do
                ) AS totalbarang,
                (
                    SELECT COUNT(DISTINCT kd_faktur)
                    FROM tb_detail_do
                    WHERE kd_do = a.kd_do
                ) AS totalfaktur
            FROM tb_do a
            WHERE (
                SELECT COUNT(DISTINCT kd_faktur)
                FROM tb_detail_do
                WHERE kd_do = a.kd_do
            ) > 0
            ORDER BY a.tgl_create DESC
        ")->result();
    }

    /**
     * Ambil log konfirmasi sales untuk satu DO
     */
    public function get_log_confirm_sales($kd_do)
    {
        return $this->db->query("
            SELECT * FROM tb_log_confirm_sales
            WHERE kd_do = ?
            ORDER BY confirm_at DESC
        ", [$kd_do])->result();
    }

    public function get_tmp_dokd($kd)
    {
        $query = $this->db->get_where('tb_tmp_detaildo', ['kd_do' => $kd]);
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return false;
        }
    }

    public function insert_det_do($data)
    {
        return $this->db->insert('tb_detail_do', $data);
    }

    public function insertics_det_do($data)
    {
        return $this->db->insert_batch('tb_ics_do', $data);
    }

    public function insert_do($data)
    {
        return $this->db->insert('tb_do', $data);
    }

    function generate_kd_do()
    {
        $cd1 = $this->db->query("SELECT MAX(RIGHT(kd_do,4)) AS kd_max FROM tb_do WHERE DATE(tgl_create)=CURDATE()");
        $kd1 = "";
        if ($cd1->num_rows() > 0) {
            foreach ($cd1->result() as $k) {
                $tmp = ((int)$k->kd_max) + 1;
                $kd1 = sprintf("%04s", $tmp);
            }
        } else {
            $kd1 = "0001";
        }
        date_default_timezone_set('Asia/Jakarta');
        $kdnk1 = 'KIUDO' . date('dmy') . $kd1;
        return $kdnk1;
    }
    function generate_kd_do_onsite()
    {
        $cd1 = $this->db->query("SELECT MAX(RIGHT(kd_do,4)) AS kd_max FROM tb_do WHERE DATE(tgl_create)=CURDATE()");
        $kd1 = "";
        if ($cd1->num_rows() > 0) {
            foreach ($cd1->result() as $k) {
                $tmp = ((int)$k->kd_max) + 1;
                $kd1 = sprintf("%04s", $tmp);
            }
        } else {
            $kd1 = "0001";
        }
        date_default_timezone_set('Asia/Jakarta');
        $kdnk1 = 'KIUDOOTS' . date('dmy') . $kd1;
        return $kdnk1;
    }

    public function edited_rute_do($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_do', $data);
    }

    public function get_tonase_kubikasi($kd_do = null)
    {
        if (!$kd_do) {
            $this->db->select('kd_do');
            $this->db->from('tb_tmp_detaildo');
            $this->db->order_by('id_pre_do', 'DESC');
            $this->db->limit(1);
            $last_kd_do = $this->db->get()->row();
            $kd_do = $last_kd_do ? $last_kd_do->kd_do : null;
        }

        if (!$kd_do) {
            return [];
        }

        $this->db->select('d.kd_do, 
                       SUM(d.qty * m.berat) AS total_tonase_kg, 
                       SUM(d.qty * m.kubikasi) AS total_kubikasi_m3');
        $this->db->from('tb_tmp_detaildo d');
        $this->db->join('tb_master_barang_all m', 'd.kd_barang = m.kd_barang');
        $this->db->where('d.kd_do', $kd_do);
        $this->db->group_by('d.kd_do');

        return $this->db->get()->result();
    }

    public function detail_fk($kd)
    {
        // Coba ambil dari tbso_sales_order_detail dulu
        $result = $this->db->query("
            SELECT
                sod.id                              AS id,
                so.no_faktur                        AS kd_faktur,
                sod.kd_barang,
                mb.nama_barang,
                sod.qty,
                mb.berat                            AS gr_berat,
                (mb.berat / 1000)                   AS convert_kg,
                (sod.qty * (mb.berat / 1000))       AS total_berat,
                sod.satuan,
                sod.no_lot,
                sod.expired_date                    AS tgl_exp,
                1                                   AS barang_sts
            FROM tbso_sales_order so
            JOIN tbso_sales_order_detail sod ON sod.no_so = so.no_so
            JOIN tb_master_barang_all mb ON mb.kd_barang = sod.kd_barang
            WHERE so.no_faktur = ?
        ", [$kd])->result();

        // Fallback ke tb_pre_do jika kosong
        if (empty($result)) {
            $result = $this->db->query("
                SELECT
                    a.id, a.kd_faktur, a.kd_barang,
                    c.nama_barang,
                    a.qty,
                    c.berat     AS gr_berat,
                    (c.berat/1000)          AS convert_kg,
                    (a.qty * (c.berat/1000)) AS total_berat,
                    a.satuan, a.no_lot, a.tgl_exp, a.barang_sts
                FROM tb_pre_do a
                LEFT JOIN tb_master_barang_all c ON c.kd_barang = a.kd_barang
                WHERE a.kd_faktur = ?
                GROUP BY a.id
            ", [$kd])->result();
        }

        return $result;
    }

    public function det_customer($kd)
    {
        // Coba dari tbso_sales_order dulu
        $result = $this->db->query("
            SELECT
                c.nama_customer,
                c.nama_kios,
                c.regional,
                so.status       AS upload_sts,
                so.status       AS data_sts,
                1               AS barang_sts
            FROM tbso_sales_order so
            JOIN tb_customer c ON c.kd_customer = so.kd_customer
            WHERE so.no_faktur = ?
            LIMIT 1
        ", [$kd])->result();

        // Fallback ke tb_pre_do
        if (empty($result)) {
            $result = $this->db->query("
                SELECT
                    b.nama_customer, b.nama_kios, b.regional,
                    a.upload_sts, a.data_sts, a.barang_sts
                FROM tb_pre_do a
                JOIN tb_customer b ON b.kd_customer = a.kd_customer
                WHERE a.kd_faktur = ?
                LIMIT 1
            ", [$kd])->result();
        }

        return $result;
    }

    public function select_driver()
    {
        $this->db->select('*');
        $this->db->from('tb_op_driver');
        return $this->db->get()->result_array();
    }

    public function select_plat()
    {
        $this->db->select('*');
        $this->db->from('tb_op_plat');
        return $this->db->get()->result_array();
    }

    public function get_driver_name_by_kd($kd_driver)
    {
        return $this->db
            ->select('nama_driver')
            ->from('tb_op_driver')
            ->where('kd_driver', $kd_driver)
            ->limit(1)
            ->get()
            ->row();
    }

    public function get_truck_plate_by_id($truck_id)
    {
        return $this->db
            ->select('noplat')
            ->from('tb_op_plat')
            ->where('id', $truck_id)
            ->limit(1)
            ->get()
            ->row();
    }

    

    public function getbarangics()
    {
        return $this->db->query("SELECT 
        b.kd_system,
        a.nm_barang AS nama_barang,
        (b.p * b.l * b.t) AS dimensi,
        a.exp_date,
        SUM(a.qty) AS tot_qty,
        FLOOR(SUM(a.qty) / (b.p * b.l * b.t)) AS qty_box,
        (SUM(a.qty) - FLOOR(SUM(a.qty) / (b.p * b.l * b.t)) * (b.p * b.l * b.t)) AS qty_pcs
    FROM tb_qty_lot a
    JOIN tb_master_barang_all b ON b.nama_barang = a.nm_barang
    JOIN tb_suplier c ON c.kd_suplier = a.suplier
    GROUP BY a.nm_barang, a.exp_date, b.kd_system, b.p, b.l, b.t ")->result();
    }

    public function list_item_ics()
    {
        return $this->db->query("SELECT
            a.*
            FROM v_ics_all a
        ");
    }

    public function getAllICS()
    {
        return $this->db->query("SELECT
        x.nama_barang,
        x.exp_date,
        x.dimensi
        FROM
        (
            SELECT
            a.nama_barang,
            a.exp_date,
            b.dimensi
            FROM tb_ics a
            JOIN tb_mbarang b ON b.nm_barang = a.nama_barang
        ) AS x
        ")->result();
    }
    public function getinputopname($user)
    {
        return $this->db->query("SELECT * FROM `tb_ics_opname` WHERE inputer = '$user'
        ")->result();
    }
    public function getBarangByNama($nama)
    {
        return $this->db->get_where('tb_mbarang', ['nm_barang' => $nama])->row();
    }
    public function getDimensi($nama)
    {
        $barang = $this->getBarangByNama($nama);
        return $barang->p * $barang->l * $barang->t;
    }
    public function insertOpname($data)
    {
        $this->db->insert('tb_ics_opname', $data);
    }

    public function logInput($log)
    {
        $this->db->insert('tb_log_ics', $log);
    }

    public function compareOpname()
    {
        $sql = "SELECT 
                    o.nama_barang, o.exp_date, o.qty AS qty_fisik,
                    i.qty AS qty_saldo,
                    IF(o.qty = i.qty, 'MATCH', 'NOT MATCH') AS status
                FROM tb_ics_opname o
                LEFT JOIN tb_ics i ON o.nama_barang = i.nama_barang AND o.exp_date = i.exp_date";
        return $this->db->query($sql)->result();
    }

    public function querysql_not_ci()
    {
        // public function compareFEFO()
        // {
        //     $sql = "SELECT 
        //         COALESCE(a.nama_barang, b.nama_barang) AS nama_barang,
        //         COALESCE(a.exp_date, b.exp_date) AS exp_date,
        //         IFNULL(a.qty_fisik, 0) AS qty_fisik,
        //         IFNULL(b.qty_saldo, 0) AS qty_saldo,
        //         CASE 
        //             WHEN IFNULL(a.qty_fisik, 0) = IFNULL(b.qty_saldo, 0) THEN 'MATCH'
        //             ELSE 'NOT MATCH'
        //         END AS status
        //     FROM 
        //         (
        //             SELECT nama_barang, exp_date, SUM(qty) AS qty_fisik 
        //             FROM tb_ics_opname 
        //             GROUP BY nama_barang, exp_date
        //         ) a
        //     LEFT JOIN 
        //         (
        //             SELECT nama_barang, exp_date, SUM(qty) AS qty_saldo 
        //             FROM tb_ics 
        //             GROUP BY nama_barang, exp_date
        //         ) b 
        //         ON a.nama_barang = b.nama_barang AND a.exp_date = b.exp_date

        //     UNION

        //     SELECT 
        //         COALESCE(a.nama_barang, b.nama_barang) AS nama_barang,
        //         COALESCE(a.exp_date, b.exp_date) AS exp_date,
        //         IFNULL(a.qty_fisik, 0) AS qty_fisik,
        //         IFNULL(b.qty_saldo, 0) AS qty_saldo,
        //         CASE 
        //             WHEN IFNULL(a.qty_fisik, 0) = IFNULL(b.qty_saldo, 0) THEN 'MATCH'
        //             ELSE 'NOT MATCH'
        //         END AS status
        //     FROM 
        //         (
        //             SELECT nama_barang, exp_date, SUM(qty) AS qty_fisik 
        //             FROM tb_ics_opname 
        //             GROUP BY nama_barang, exp_date
        //         ) a
        //     RIGHT JOIN 
        //         (
        //             SELECT nama_barang, exp_date, SUM(qty) AS qty_saldo 
        //             FROM tb_ics 
        //             GROUP BY nama_barang, exp_date
        //         ) b 
        //         ON a.nama_barang = b.nama_barang AND a.exp_date = b.exp_date

        //     ORDER BY nama_barang, exp_date;";

        //     return $this->db->query($sql)->result();
        // }

        // by All Barang (tanpa exp_date)
        // public function compareAllBarang()
        // {
        //     $sql = "SELECT 
        //         COALESCE(a.nama_barang, b.nama_barang) AS nama_barang,
        //         IFNULL(a.qty_fisik, 0) AS qty_fisik,
        //         IFNULL(b.qty_saldo, 0) AS qty_saldo,
        //         CASE 
        //             WHEN IFNULL(a.qty_fisik, 0) = IFNULL(b.qty_saldo, 0) THEN 'MATCH'
        //             ELSE 'NOT MATCH'
        //         END AS STATUS
        //     FROM 
        //         (
        //             SELECT nama_barang, SUM(qty) AS qty_fisik 
        //             FROM tb_ics_opname 
        //             GROUP BY nama_barang
        //         ) a
        //     LEFT JOIN 
        //         (
        //             SELECT nama_barang, SUM(qty) AS qty_saldo 
        //             FROM tb_ics 
        //             GROUP BY nama_barang
        //         ) b ON a.nama_barang = b.nama_barang

        //     UNION

        //     SELECT 
        //         COALESCE(a.nama_barang, b.nama_barang) AS nama_barang,
        //         IFNULL(a.qty_fisik, 0) AS qty_fisik,
        //         IFNULL(b.qty_saldo, 0) AS qty_saldo,
        //         CASE 
        //             WHEN IFNULL(a.qty_fisik, 0) = IFNULL(b.qty_saldo, 0) THEN 'MATCH'
        //             ELSE 'NOT MATCH'
        //         END AS STATUS
        //     FROM 
        //         (
        //             SELECT nama_barang, SUM(qty) AS qty_fisik 
        //             FROM tb_ics_opname 
        //             GROUP BY nama_barang
        //         ) a
        //     RIGHT JOIN 
        //         (
        //             SELECT nama_barang, SUM(qty) AS qty_saldo 
        //             FROM tb_ics 
        //             GROUP BY nama_barang
        //         ) b ON a.nama_barang = b.nama_barang

        //     ORDER BY nama_barang;
        //     ";
        //     return $this->db->query($sql)->result();
        // }
    }

    public function admin_compareuser_exp()
    {
        return $this->db->query("SELECT 
            COALESCE(m.kd_system, '-') AS kd_barang,
            base.nama_barang,
            base.exp_date,
            COALESCE(t1.qty_fisik_tim1, 0) AS qty_fisik_tim1,
            COALESCE(t2.qty_fisik_tim2, 0) AS qty_fisik_tim2,
            COALESCE(base.qty_zahir, 0) AS qty_zahir,
            COALESCE(p.qty_pending, 0) AS qty_pending,
            COALESCE(supp.qty_supp, 0) AS qty_supp,
            (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(supp.qty_supp, 0)) AS qty_sistem,
            (COALESCE(t1.qty_fisik_tim1, 0) - (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(supp.qty_supp, 0))) AS selisih_tim1,
            (COALESCE(t2.qty_fisik_tim2, 0) - (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(supp.qty_supp, 0))) AS selisih_tim2,
            CASE
                WHEN COALESCE(t1.qty_fisik_tim1, 0) = 
                    (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(supp.qty_supp, 0))
                THEN 'MATCH' ELSE 'NOT MATCH'
            END AS status_tim1,
            CASE
                WHEN COALESCE(t2.qty_fisik_tim2, 0) = 
                    (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(supp.qty_supp, 0))
                THEN 'MATCH' ELSE 'NOT MATCH'
            END AS status_tim2
        FROM (
            SELECT nama_barang, exp_date, SUM(qty) AS qty_zahir
            FROM tb_ics
            GROUP BY nama_barang, exp_date
        ) base
        LEFT JOIN tb_mbarang m ON base.nama_barang = m.nm_barang
        LEFT JOIN (
            SELECT nama_barang, exp_date, SUM(qty) AS qty_pending
            FROM tb_ics_do
            GROUP BY nama_barang, exp_date
        ) p ON p.nama_barang = base.nama_barang AND p.exp_date = base.exp_date
        LEFT JOIN (
            SELECT nama_barang, exp_date, SUM(qty) AS qty_supp
            FROM tb_ics_supp
            GROUP BY nama_barang, exp_date
        ) supp ON supp.nama_barang = base.nama_barang AND supp.exp_date = base.exp_date
        LEFT JOIN (
            SELECT nama_barang, exp_date, SUM(qty) AS qty_fisik_tim1
            FROM tb_ics_opname
            WHERE tim = '1'
            GROUP BY nama_barang, exp_date
        ) t1 ON t1.nama_barang = base.nama_barang AND t1.exp_date = base.exp_date
        LEFT JOIN (
            SELECT nama_barang, exp_date, SUM(qty) AS qty_fisik_tim2
            FROM tb_ics_opname
            WHERE tim = '2'
            GROUP BY nama_barang, exp_date
        ) t2 ON t2.nama_barang = base.nama_barang AND t2.exp_date = base.exp_date
        ORDER BY base.nama_barang, base.exp_date;")->result();
    }

    public function opname_pending()
    {
        return $this->db->query("SELECT
        a.*
        FROM tb_ics_do a ")->result();
    }

    public function admin_compareuser_all()
    {
        return $this->db->query("SELECT 
            COALESCE(m.kd_system, '-') AS kd_barang,
            i.nama_barang,
            COALESCE(t1.qty_fisik_tim1, 0) AS qty_fisik_tim1,
            COALESCE(t2.qty_fisik_tim2, 0) AS qty_fisik_tim2,
            COALESCE(s.qty_zahir, 0) AS qty_zahir,
            COALESCE(p.qty_pending, 0) AS qty_pending,
            COALESCE(sp.qty_supp, 0) AS qty_supp,
            (COALESCE(s.qty_zahir, 0) + COALESCE(p.qty_pending, 0)) AS qty_sistem,
            (COALESCE(s.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(sp.qty_supp, 0)) AS qty_sistem_final,
            (COALESCE(t1.qty_fisik_tim1, 0) - (COALESCE(s.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(sp.qty_supp, 0))) AS selisih_qty_tim1,
            (COALESCE(t2.qty_fisik_tim2, 0) - (COALESCE(s.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(sp.qty_supp, 0))) AS selisih_qty_tim2,
            CASE
                WHEN COALESCE(t1.qty_fisik_tim1, 0) = 
                    (COALESCE(s.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(sp.qty_supp, 0))
                THEN 'MATCH' ELSE 'NOT MATCH'
            END AS status_tim1,
            CASE
                WHEN COALESCE(t2.qty_fisik_tim2, 0) = 
                    (COALESCE(s.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(sp.qty_supp, 0))
                THEN 'MATCH' ELSE 'NOT MATCH'
            END AS status_tim2
        FROM (
            SELECT DISTINCT nama_barang
            FROM tb_ics
        ) i
        LEFT JOIN (
            SELECT nama_barang, SUM(qty) AS qty_zahir
            FROM tb_ics
            GROUP BY nama_barang
        ) s ON s.nama_barang = i.nama_barang
        LEFT JOIN (
            SELECT nama_barang, SUM(qty) AS qty_pending
            FROM tb_ics_do
            GROUP BY nama_barang
        ) p ON p.nama_barang = i.nama_barang
        LEFT JOIN (
            SELECT nama_barang, SUM(qty) AS qty_supp
            FROM tb_ics_supp
            GROUP BY nama_barang
        ) sp ON sp.nama_barang = i.nama_barang
        LEFT JOIN (
            SELECT nama_barang, SUM(qty) AS qty_fisik_tim1
            FROM tb_ics_opname
            WHERE tim = '1'
            GROUP BY nama_barang
        ) t1 ON t1.nama_barang = i.nama_barang
        LEFT JOIN (
            SELECT nama_barang, SUM(qty) AS qty_fisik_tim2
            FROM tb_ics_opname
            WHERE tim = '2'
            GROUP BY nama_barang
        ) t2 ON t2.nama_barang = i.nama_barang
        LEFT JOIN tb_mbarang m ON m.nm_barang = i.nama_barang
        ORDER BY i.nama_barang;")->result();
    }

    public function compareinputer($tim)
    {
        // Fisik opname hanya dari User StockOpname 1
        $this->db->select('nama_barang, SUM(qty) AS qty_fisik');
        $this->db->from('tb_ics_opname');
        $this->db->where('tim', $tim);
        $this->db->group_by('nama_barang');
        $subquery_fisik = $this->db->get_compiled_select();
        $this->db->reset_query();

        // Saldo dari tb_ics (qty buku)
        $this->db->select('nama_barang, SUM(qty) AS qty_buku');
        $this->db->from('tb_ics');
        $this->db->group_by('nama_barang');
        $subquery_buku = $this->db->get_compiled_select();
        $this->db->reset_query();

        // Saldo dari tb_pending
        $this->db->select('nama_barang, SUM(qty) AS qty_pending');
        $this->db->from('tb_ics_do');
        $this->db->group_by('nama_barang');
        $subquery_pending = $this->db->get_compiled_select();
        $this->db->reset_query();

        $sql = "SELECT 
                COALESCE(f.nama_barang, b.nama_barang, p.nama_barang) AS nama_barang,
                IFNULL(f.qty_fisik, 0) AS qty_fisik,
                IFNULL(b.qty_buku, 0) AS qty_buku,
                IFNULL(p.qty_pending, 0) AS qty_pending,
                CASE 
                    WHEN IFNULL(f.qty_fisik, 0) = (IFNULL(b.qty_buku, 0) + IFNULL(p.qty_pending, 0)) THEN 'MATCH'
                    ELSE 'NOT MATCH'
                END AS status
            FROM ($subquery_fisik) f
            LEFT JOIN ($subquery_buku) b ON f.nama_barang = b.nama_barang
            LEFT JOIN ($subquery_pending) p ON f.nama_barang = p.nama_barang

            UNION

            SELECT 
                COALESCE(f.nama_barang, b.nama_barang, p.nama_barang) AS nama_barang,
                IFNULL(f.qty_fisik, 0) AS qty_fisik,
                IFNULL(b.qty_buku, 0) AS qty_buku,
                IFNULL(p.qty_pending, 0) AS qty_pending,
                CASE 
                    WHEN IFNULL(f.qty_fisik, 0) = (IFNULL(b.qty_buku, 0) + IFNULL(p.qty_pending, 0)) THEN 'MATCH'
                    ELSE 'NOT MATCH'
                END AS status
            FROM ($subquery_buku) b
            LEFT JOIN ($subquery_fisik) f ON b.nama_barang = f.nama_barang
            LEFT JOIN ($subquery_pending) p ON b.nama_barang = p.nama_barang

            UNION

            SELECT 
                COALESCE(f.nama_barang, b.nama_barang, p.nama_barang) AS nama_barang,
                IFNULL(f.qty_fisik, 0) AS qty_fisik,
                IFNULL(b.qty_buku, 0) AS qty_buku,
                IFNULL(p.qty_pending, 0) AS qty_pending,
                CASE 
                    WHEN IFNULL(f.qty_fisik, 0) = (IFNULL(b.qty_buku, 0) + IFNULL(p.qty_pending, 0)) THEN 'MATCH'
                    ELSE 'NOT MATCH'
                END AS status
            FROM ($subquery_pending) p
            LEFT JOIN ($subquery_fisik) f ON p.nama_barang = f.nama_barang
            LEFT JOIN ($subquery_buku) b ON p.nama_barang = b.nama_barang

            ORDER BY nama_barang
            ";

        return $this->db->query($sql)->result();
    }

    public function list_final_data()
    {
        return $this->db->query("SELECT 
            i.nama_barang,
            COALESCE(m.kd_system, '-') AS kd_barang,
            COALESCE(z.qty_zahir, 0) AS qty_zahir,
            COALESCE(p.qty_pending, 0) AS qty_pending,
            COALESCE(s.qty_supp, 0) AS qty_supp,
            (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0)) AS qty_sistem,
            COALESCE(t1.qty_fisik_tim1, 0) AS qty_fisik_tim1,
            COALESCE(t2.qty_fisik_tim2, 0) AS qty_fisik_tim2,
            CASE
                WHEN COALESCE(t1.qty_fisik_tim1, 0) = 
                    (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                THEN 'MATCH'
                ELSE 'NOT MATCH'
            END AS status_tim1,
            CASE
                WHEN COALESCE(t2.qty_fisik_tim2, 0) = 
                    (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                THEN 'MATCH'
                ELSE 'NOT MATCH'
            END AS status_tim2,
            CASE
                WHEN COALESCE(t1.qty_fisik_tim1, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                    AND COALESCE(t2.qty_fisik_tim2, 0) != (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                THEN 'Ambil dari Tim 1'     
                WHEN COALESCE(t1.qty_fisik_tim1, 0) != (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                    AND COALESCE(t2.qty_fisik_tim2, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                THEN 'Ambil dari Tim 2'
                WHEN COALESCE(t1.qty_fisik_tim1, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                    AND COALESCE(t2.qty_fisik_tim2, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                THEN 'MATCH KEDUANYA'
                ELSE 'CEK ULANG'
            END AS keterangan
        FROM (
            SELECT DISTINCT nama_barang FROM tb_ics
        ) i
        LEFT JOIN tb_mbarang m ON m.nm_barang = i.nama_barang
        LEFT JOIN (
            SELECT nama_barang, SUM(qty) AS qty_zahir
            FROM tb_ics
            GROUP BY nama_barang
        ) z ON z.nama_barang = i.nama_barang
        LEFT JOIN (
            SELECT nama_barang, SUM(qty) AS qty_pending
            FROM tb_ics_do
            GROUP BY nama_barang
        ) p ON p.nama_barang = i.nama_barang
        LEFT JOIN (
            SELECT nama_barang, SUM(qty) AS qty_supp
            FROM tb_ics_supp
            GROUP BY nama_barang
        ) s ON s.nama_barang = i.nama_barang
        LEFT JOIN (
            SELECT nama_barang, SUM(qty) AS qty_fisik_tim1
            FROM tb_ics_opname
            WHERE tim = '1'
            GROUP BY nama_barang
        ) t1 ON t1.nama_barang = i.nama_barang
        LEFT JOIN (
            SELECT nama_barang, SUM(qty) AS qty_fisik_tim2
            FROM tb_ics_opname
            WHERE tim = '2'
            GROUP BY nama_barang
        ) t2 ON t2.nama_barang = i.nama_barang
        WHERE 
            (COALESCE(t1.qty_fisik_tim1, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0)))
            OR
            (COALESCE(t2.qty_fisik_tim2, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0)))
            OR
            (COALESCE(t1.qty_fisik_tim1, 0) != (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
            AND COALESCE(t2.qty_fisik_tim2, 0) != (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0)))
        ORDER BY i.nama_barang;")->result();
    }

    public function list_final_datafefo()
    {
        return $this->db->query("SELECT 
            base.nama_barang,
            base.exp_date,
            COALESCE(m.kd_system, '-') AS kd_barang,
            COALESCE(base.qty_zahir, 0) AS qty_zahir,
            COALESCE(p.qty_pending, 0) AS qty_pending,
            COALESCE(s.qty_supp, 0) AS qty_supp,
            (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0)) AS qty_sistem,
            COALESCE(t1.qty_fisik_tim1, 0) AS qty_fisik_tim1,
            COALESCE(t2.qty_fisik_tim2, 0) AS qty_fisik_tim2,
            CASE
                WHEN COALESCE(t1.qty_fisik_tim1, 0) = 
                    (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                THEN 'MATCH'
                ELSE 'NOT MATCH'
            END AS status_tim1,
            CASE
                WHEN COALESCE(t2.qty_fisik_tim2, 0) = 
                    (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                THEN 'MATCH'
                ELSE 'NOT MATCH'
            END AS status_tim2,
            CASE
                WHEN COALESCE(t1.qty_fisik_tim1, 0) = (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                    AND COALESCE(t2.qty_fisik_tim2, 0) != (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                THEN 'Ambil dari Tim 1'
                WHEN COALESCE(t1.qty_fisik_tim1, 0) != (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                    AND COALESCE(t2.qty_fisik_tim2, 0) = (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                THEN 'Ambil dari Tim 2'
                WHEN COALESCE(t1.qty_fisik_tim1, 0) = (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                    AND COALESCE(t2.qty_fisik_tim2, 0) = (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                THEN 'MATCH KEDUANYA'
                ELSE 'CEK ULANG'
            END AS keterangan
        FROM (
            SELECT nama_barang, exp_date, SUM(qty) AS qty_zahir
            FROM tb_ics
            GROUP BY nama_barang, exp_date
        ) base
        LEFT JOIN tb_mbarang m ON m.nm_barang = base.nama_barang
        LEFT JOIN (
            SELECT nama_barang, exp_date, SUM(qty) AS qty_pending
            FROM tb_ics_do
            GROUP BY nama_barang, exp_date
        ) p ON p.nama_barang = base.nama_barang AND p.exp_date = base.exp_date
        LEFT JOIN (
            SELECT nama_barang, exp_date, SUM(qty) AS qty_supp
            FROM tb_ics_supp
            GROUP BY nama_barang, exp_date
        ) s ON s.nama_barang = base.nama_barang AND s.exp_date = base.exp_date
        LEFT JOIN (
            SELECT nama_barang, exp_date, SUM(qty) AS qty_fisik_tim1
            FROM tb_ics_opname
            WHERE tim = '1'
            GROUP BY nama_barang, exp_date
        ) t1 ON t1.nama_barang = base.nama_barang AND t1.exp_date = base.exp_date
        LEFT JOIN (
            SELECT nama_barang, exp_date, SUM(qty) AS qty_fisik_tim2
            FROM tb_ics_opname
            WHERE tim = '2'
            GROUP BY nama_barang, exp_date
        ) t2 ON t2.nama_barang = base.nama_barang AND t2.exp_date = base.exp_date
        WHERE 
            (COALESCE(t1.qty_fisik_tim1, 0) = (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0)))
            OR
            (COALESCE(t2.qty_fisik_tim2, 0) = (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0)))
            OR
            (COALESCE(t1.qty_fisik_tim1, 0) != (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0)) AND COALESCE(t2.qty_fisik_tim2, 0) != (COALESCE(base.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0)))
        ORDER BY base.nama_barang, base.exp_date;")->result();
    }

    public function final_opname_expdate_statis()
    {
        return $this->db->query("SELECT
            COUNT(*) AS total_barang,
            SUM(CASE
                WHEN keterangan IN ('MATCH KEDUANYA', 'Ambil dari Tim 1', 'Ambil dari Tim 2') THEN 1
                ELSE 0
            END) AS total_match,
            SUM(CASE
                WHEN keterangan = 'CEK ULANG' THEN 1
                ELSE 0
            END) AS total_notmatch
        FROM (
            SELECT 
                base.nama_barang,
                base.exp_date,
                COALESCE(z.qty_zahir, 0) AS qty_zahir,
                COALESCE(p.qty_pending, 0) AS qty_pending,
                COALESCE(s.qty_supp, 0) AS qty_supp,
                (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0)) AS qty_sistem,
                COALESCE(t1.qty_fisik_tim1, 0) AS qty_fisik_tim1,
                COALESCE(t2.qty_fisik_tim2, 0) AS qty_fisik_tim2,

                -- Keterangan berdasarkan kecocokan qty_fisik dan qty_sistem
                CASE
                    WHEN COALESCE(t1.qty_fisik_tim1, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                        AND COALESCE(t2.qty_fisik_tim2, 0) != (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                    THEN 'Ambil dari Tim 1'

                    WHEN COALESCE(t1.qty_fisik_tim1, 0) != (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                        AND COALESCE(t2.qty_fisik_tim2, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                    THEN 'Ambil dari Tim 2'

                    WHEN COALESCE(t1.qty_fisik_tim1, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                        AND COALESCE(t2.qty_fisik_tim2, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                    THEN 'MATCH KEDUANYA'

                    ELSE 'CEK ULANG'
                END AS keterangan
            FROM (
                SELECT nama_barang, exp_date FROM tb_ics GROUP BY nama_barang, exp_date
            ) base
            LEFT JOIN (
                SELECT nama_barang, exp_date, SUM(qty) AS qty_zahir
                FROM tb_ics
                GROUP BY nama_barang, exp_date
            ) z ON z.nama_barang = base.nama_barang AND z.exp_date = base.exp_date
            LEFT JOIN (
                SELECT nama_barang, exp_date, SUM(qty) AS qty_pending
                FROM tb_ics_do
                GROUP BY nama_barang, exp_date
            ) p ON p.nama_barang = base.nama_barang AND p.exp_date = base.exp_date
            LEFT JOIN (
                SELECT nama_barang, exp_date, SUM(qty) AS qty_supp
                FROM tb_ics_supp
                GROUP BY nama_barang, exp_date
            ) s ON s.nama_barang = base.nama_barang AND s.exp_date = base.exp_date
            LEFT JOIN (
                SELECT nama_barang, exp_date, SUM(qty) AS qty_fisik_tim1
                FROM tb_ics_opname
                WHERE tim = '1'
                GROUP BY nama_barang, exp_date
            ) t1 ON t1.nama_barang = base.nama_barang AND t1.exp_date = base.exp_date
            LEFT JOIN (
                SELECT nama_barang, exp_date, SUM(qty) AS qty_fisik_tim2
                FROM tb_ics_opname
                WHERE tim = '2'
                GROUP BY nama_barang, exp_date
            ) t2 ON t2.nama_barang = base.nama_barang AND t2.exp_date = base.exp_date
        ) hasil;")->result();
    }

    public function final_opname_allbarang_statis()
    {
        return $this->db->query("SELECT
            COUNT(*) AS total_barang,
            SUM(CASE
                WHEN keterangan IN ('MATCH KEDUANYA', 'Ambil dari Tim 1', 'Ambil dari Tim 2') THEN 1
                ELSE 0
            END) AS total_match,
            SUM(CASE
                WHEN keterangan = 'CEK ULANG' THEN 1
                ELSE 0
            END) AS total_notmatch
        FROM (
            SELECT 
                i.nama_barang,
                COALESCE(z.qty_zahir, 0) AS qty_zahir,
                COALESCE(p.qty_pending, 0) AS qty_pending,
                COALESCE(s.qty_supp, 0) AS qty_supp,
                (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0)) AS qty_sistem,
                COALESCE(t1.qty_fisik_tim1, 0) AS qty_fisik_tim1,
                COALESCE(t2.qty_fisik_tim2, 0) AS qty_fisik_tim2,
                CASE
                    WHEN COALESCE(t1.qty_fisik_tim1, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                        AND COALESCE(t2.qty_fisik_tim2, 0) != (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                    THEN 'Ambil dari Tim 1'

                    WHEN COALESCE(t1.qty_fisik_tim1, 0) != (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                        AND COALESCE(t2.qty_fisik_tim2, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                    THEN 'Ambil dari Tim 2'

                    WHEN COALESCE(t1.qty_fisik_tim1, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                        AND COALESCE(t2.qty_fisik_tim2, 0) = (COALESCE(z.qty_zahir, 0) + COALESCE(p.qty_pending, 0) - COALESCE(s.qty_supp, 0))
                    THEN 'MATCH KEDUANYA'

                    ELSE 'CEK ULANG'
                END AS keterangan
            FROM (
                SELECT DISTINCT nama_barang FROM tb_ics
            ) i
            LEFT JOIN (
                SELECT nama_barang, SUM(qty) AS qty_zahir
                FROM tb_ics
                GROUP BY nama_barang
            ) z ON z.nama_barang = i.nama_barang
            LEFT JOIN (
                SELECT nama_barang, SUM(qty) AS qty_pending
                FROM tb_ics_do
                GROUP BY nama_barang
            ) p ON p.nama_barang = i.nama_barang
            LEFT JOIN (
                SELECT nama_barang, SUM(qty) AS qty_supp
                FROM tb_ics_supp
                GROUP BY nama_barang
            ) s ON s.nama_barang = i.nama_barang
            LEFT JOIN (
                SELECT nama_barang, SUM(qty) AS qty_fisik_tim1
                FROM tb_ics_opname
                WHERE tim = '1'
                GROUP BY nama_barang
            ) t1 ON t1.nama_barang = i.nama_barang
            LEFT JOIN (
                SELECT nama_barang, SUM(qty) AS qty_fisik_tim2
                FROM tb_ics_opname
                WHERE tim = '2'
                GROUP BY nama_barang
            ) t2 ON t2.nama_barang = i.nama_barang
        ) hasil
        ")->result();
    }

    public function compareAllBarang()
    {

        $this->db->select('nama_barang, SUM(qty) AS qty_fisik');
        $this->db->from('tb_ics_opname');
        $this->db->group_by('nama_barang');
        $subquery_fisik = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->select('nama_barang, SUM(qty) AS qty_buku');
        $this->db->from('tb_ics');
        $this->db->group_by('nama_barang');
        $subquery_buku = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->select('nama_barang, SUM(qty) AS qty_pending');
        $this->db->from('tb_ics_do');
        $this->db->group_by('nama_barang');
        $subquery_pending = $this->db->get_compiled_select();
        $this->db->reset_query();

        $sql = "
        SELECT 
            COALESCE(f.nama_barang, b.nama_barang, p.nama_barang) AS nama_barang,
            IFNULL(f.qty_fisik, 0) AS qty_fisik,
            IFNULL(b.qty_buku, 0) AS qty_buku,
            IFNULL(p.qty_pending, 0) AS qty_pending,
            CASE 
                WHEN IFNULL(f.qty_fisik, 0) = (IFNULL(b.qty_buku, 0) + IFNULL(p.qty_pending, 0)) THEN 'MATCH'
                ELSE 'NOT MATCH'
            END AS status
        FROM ($subquery_fisik) f
        LEFT JOIN ($subquery_buku) b ON f.nama_barang = b.nama_barang
        LEFT JOIN ($subquery_pending) p ON f.nama_barang = p.nama_barang

        UNION

        SELECT 
            COALESCE(f.nama_barang, b.nama_barang, p.nama_barang) AS nama_barang,
            IFNULL(f.qty_fisik, 0) AS qty_fisik,
            IFNULL(b.qty_buku, 0) AS qty_buku,
            IFNULL(p.qty_pending, 0) AS qty_pending,
            CASE 
                WHEN IFNULL(f.qty_fisik, 0) = (IFNULL(b.qty_buku, 0) + IFNULL(p.qty_pending, 0)) THEN 'MATCH'
                ELSE 'NOT MATCH'
            END AS status
        FROM ($subquery_buku) b
        LEFT JOIN ($subquery_fisik) f ON b.nama_barang = f.nama_barang
        LEFT JOIN ($subquery_pending) p ON b.nama_barang = p.nama_barang

        UNION

        SELECT 
            COALESCE(f.nama_barang, b.nama_barang, p.nama_barang) AS nama_barang,
            IFNULL(f.qty_fisik, 0) AS qty_fisik,
            IFNULL(b.qty_buku, 0) AS qty_buku,
            IFNULL(p.qty_pending, 0) AS qty_pending,
            CASE 
                WHEN IFNULL(f.qty_fisik, 0) = (IFNULL(b.qty_buku, 0) + IFNULL(p.qty_pending, 0)) THEN 'MATCH'
                ELSE 'NOT MATCH'
            END AS status
        FROM ($subquery_pending) p
        LEFT JOIN ($subquery_fisik) f ON p.nama_barang = f.nama_barang
        LEFT JOIN ($subquery_buku) b ON p.nama_barang = b.nama_barang

        ORDER BY nama_barang
    ";
        return $this->db->query($sql)->result();
    }

    public function compareinputerexp($tim)
    {

        $this->db->select('nama_barang, exp_date, SUM(qty) AS qty_fisik');
        $this->db->from('tb_ics_opname');
        $this->db->where('tim', $tim);
        $this->db->group_by(['nama_barang', 'exp_date']);
        $sub_fisik = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->select('nama_barang, exp_date, SUM(qty) AS qty_buku');
        $this->db->from('tb_ics');
        $this->db->group_by(['nama_barang', 'exp_date']);
        $sub_buku = $this->db->get_compiled_select();
        $this->db->reset_query();

        $this->db->select('nama_barang, exp_date, SUM(qty) AS qty_pending');
        $this->db->from('tb_ics_do');
        $this->db->group_by(['nama_barang', 'exp_date']);
        $sub_pending = $this->db->get_compiled_select();
        $this->db->reset_query();

        $sql = "
        SELECT
            COALESCE(f.nama_barang, b.nama_barang, p.nama_barang) AS nama_barang,
            COALESCE(f.exp_date ,  b.exp_date ,  p.exp_date )     AS exp_date,
            IFNULL(f.qty_fisik , 0)                               AS qty_fisik,
            IFNULL(b.qty_buku , 0)                                AS qty_buku,
            IFNULL(p.qty_pending , 0)                             AS qty_pending,
            '$tim'                                                AS tim,
            CASE
                WHEN IFNULL(f.qty_fisik ,0) =
                     (IFNULL(b.qty_buku ,0) + IFNULL(p.qty_pending ,0))
                THEN 'MATCH' ELSE 'NOT MATCH'
            END                                                   AS status
        FROM ($sub_fisik)     f
        LEFT JOIN ($sub_buku) b ON b.nama_barang = f.nama_barang
                               AND b.exp_date   = f.exp_date
        LEFT JOIN ($sub_pending) p ON p.nama_barang = f.nama_barang
                                   AND p.exp_date   = f.exp_date

        UNION

        SELECT
            COALESCE(f.nama_barang, b.nama_barang, p.nama_barang),
            COALESCE(f.exp_date ,  b.exp_date ,  p.exp_date ),
            IFNULL(f.qty_fisik , 0),
            IFNULL(b.qty_buku , 0),
            IFNULL(p.qty_pending , 0),
            '$tim',
            CASE
                WHEN IFNULL(f.qty_fisik ,0) =
                     (IFNULL(b.qty_buku ,0) + IFNULL(p.qty_pending ,0))
                THEN 'MATCH' ELSE 'NOT MATCH'
            END
        FROM ($sub_buku)      b
        LEFT JOIN ($sub_fisik) f ON f.nama_barang = b.nama_barang
                                AND f.exp_date   = b.exp_date
        LEFT JOIN ($sub_pending) p ON p.nama_barang = b.nama_barang
                                   AND p.exp_date   = b.exp_date

        UNION

        SELECT
            COALESCE(f.nama_barang, b.nama_barang, p.nama_barang),
            COALESCE(f.exp_date ,  b.exp_date ,  p.exp_date ),
            IFNULL(f.qty_fisik , 0),
            IFNULL(b.qty_buku , 0),
            IFNULL(p.qty_pending , 0),
            '$tim',
            CASE
                WHEN IFNULL(f.qty_fisik ,0) =
                     (IFNULL(b.qty_buku ,0) + IFNULL(p.qty_pending ,0))
                THEN 'MATCH' ELSE 'NOT MATCH'
            END
        FROM ($sub_pending)   p
        LEFT JOIN ($sub_fisik) f ON f.nama_barang = p.nama_barang
                                AND f.exp_date   = p.exp_date
        LEFT JOIN ($sub_buku)  b ON b.nama_barang = p.nama_barang
                                AND b.exp_date   = p.exp_date
        ORDER BY nama_barang, exp_date
    ";

        return $this->db->query($sql)->result();
    }



    public function compareFEFO()
    {
        // Fisik opname
        $this->db->select('nama_barang, exp_date, SUM(qty) AS qty_fisik');
        $this->db->from('tb_ics_opname');
        $this->db->group_by(['nama_barang', 'exp_date']);
        $subquery_fisik = $this->db->get_compiled_select();
        $this->db->reset_query();

        // Saldo buku dari tb_ics
        $this->db->select('nama_barang, exp_date, SUM(qty) AS qty_buku');
        $this->db->from('tb_ics');
        $this->db->group_by(['nama_barang', 'exp_date']);
        $subquery_buku = $this->db->get_compiled_select();
        $this->db->reset_query();

        // Pending berdasarkan nama_barang + exp_date
        $this->db->select('nama_barang, exp_date, SUM(qty) AS qty_pending');
        $this->db->from('tb_ics_do');
        $this->db->group_by(['nama_barang', 'exp_date']);
        $subquery_pending = $this->db->get_compiled_select();
        $this->db->reset_query();

        $sql = "
        SELECT 
            COALESCE(f.nama_barang, b.nama_barang, p.nama_barang) AS nama_barang,
            COALESCE(f.exp_date, b.exp_date, p.exp_date) AS exp_date,
            IFNULL(f.qty_fisik, 0) AS qty_fisik,
            IFNULL(b.qty_buku, 0) AS qty_buku,
            IFNULL(p.qty_pending, 0) AS qty_pending,
            CASE 
                WHEN IFNULL(f.qty_fisik, 0) = (IFNULL(b.qty_buku, 0) + IFNULL(p.qty_pending, 0)) THEN 'MATCH'
                ELSE 'NOT MATCH'
            END AS status
        FROM ($subquery_fisik) f
        LEFT JOIN ($subquery_buku) b ON f.nama_barang = b.nama_barang AND f.exp_date = b.exp_date
        LEFT JOIN ($subquery_pending) p ON f.nama_barang = p.nama_barang AND f.exp_date = p.exp_date

        UNION

        SELECT 
            COALESCE(f.nama_barang, b.nama_barang, p.nama_barang) AS nama_barang,
            COALESCE(f.exp_date, b.exp_date, p.exp_date) AS exp_date,
            IFNULL(f.qty_fisik, 0) AS qty_fisik,
            IFNULL(b.qty_buku, 0) AS qty_buku,
            IFNULL(p.qty_pending, 0) AS qty_pending,
            CASE 
                WHEN IFNULL(f.qty_fisik, 0) = (IFNULL(b.qty_buku, 0) + IFNULL(p.qty_pending, 0)) THEN 'MATCH'
                ELSE 'NOT MATCH'
            END AS status
        FROM ($subquery_buku) b
        LEFT JOIN ($subquery_fisik) f ON b.nama_barang = f.nama_barang AND b.exp_date = f.exp_date
        LEFT JOIN ($subquery_pending) p ON b.nama_barang = p.nama_barang AND b.exp_date = p.exp_date

        UNION

        SELECT 
            COALESCE(f.nama_barang, b.nama_barang, p.nama_barang) AS nama_barang,
            COALESCE(f.exp_date, b.exp_date, p.exp_date) AS exp_date,
            IFNULL(f.qty_fisik, 0) AS qty_fisik,
            IFNULL(b.qty_buku, 0) AS qty_buku,
            IFNULL(p.qty_pending, 0) AS qty_pending,
            CASE 
                WHEN IFNULL(f.qty_fisik, 0) = (IFNULL(b.qty_buku, 0) + IFNULL(p.qty_pending, 0)) THEN 'MATCH'
                ELSE 'NOT MATCH'
            END AS status
        FROM ($subquery_pending) p
        LEFT JOIN ($subquery_fisik) f ON p.nama_barang = f.nama_barang AND p.exp_date = f.exp_date
        LEFT JOIN ($subquery_buku) b ON p.nama_barang = b.nama_barang AND p.exp_date = b.exp_date

        ORDER BY nama_barang, exp_date
    ";

        return $this->db->query($sql)->result();
    }
    public function statistikFEFO()
    {
        $sql = "SELECT
    COUNT(*) AS total,
    SUM(IF(qty_fisik = qty_saldo, 1, 0)) AS match_count,
    SUM(IF(qty_fisik != qty_saldo, 1, 0)) AS not_match_count
FROM (
    SELECT 
        o.nama_barang,
        o.exp_date,
        SUM(o.qty) AS qty_fisik,
        IFNULL(SUM(i.qty), 0) AS qty_saldo
    FROM tb_ics_opname o
    LEFT JOIN tb_ics i ON o.nama_barang = i.nama_barang AND o.exp_date = i.exp_date
    GROUP BY o.nama_barang, o.exp_date
) AS sub

    ";
        $result = $this->db->query($sql)->result();

        return $this->_hitungStatistik($result);
    }

    public function statistikAllBarang()
    {
        $sql = "SELECT 
            o.nama_barang,
            SUM(o.qty) AS qty_fisik,
            IFNULL(SUM(i.qty), 0) AS qty_saldo,
            IF(SUM(o.qty) = IFNULL(SUM(i.qty), 0), 'MATCH', 'NOT MATCH') AS status
        FROM tb_ics_opname o
        LEFT JOIN tb_ics i ON o.nama_barang = i.nama_barang
        GROUP BY o.nama_barang
    ";

        $result = $this->db->query($sql)->result();
        return $this->_hitungStatistik($result);
    }

    public function getallopnametodo($kdbr)
    {
        return $this->db->query("SELECT
        a.nama_barang,
        a.exp_date,
        SUM(a.qty) AS qty_zahir,
        COALESCE(pending.qty_pending, 0) AS qty_pending,
        SUM(a.qty) + COALESCE(pending.qty_pending, 0) AS qty_final,
        COALESCE(op1.qtyinput_1, 0) AS qtyinput_1,
        COALESCE(op2.qtyinput_2, 0) AS qtyinput_2,
        CASE
            WHEN (SUM(a.qty) + COALESCE(pending.qty_pending, 0)) = COALESCE(op1.qtyinput_1, 0) THEN 'Match'
            ELSE 'Not Match'
        END AS status_tim1,
        CASE
            WHEN (SUM(a.qty) + COALESCE(pending.qty_pending, 0)) = COALESCE(op2.qtyinput_2, 0) THEN 'Match'
            ELSE 'Not Match'
        END AS status_tim2
        FROM
        tb_ics AS a
        JOIN tb_mbarang AS b ON b.nm_barang = a.nama_barang
        LEFT JOIN (
            SELECT
                d.nama_barang,
                d.exp_date,
                SUM(d.qty) AS qty_pending
            FROM
                tb_ics_do d
                JOIN tb_mbarang mb ON mb.nm_barang = d.nama_barang
            WHERE
                mb.kd_system = '$kdbr'
            GROUP BY
                d.nama_barang,
                d.exp_date
        ) AS pending ON pending.nama_barang = a.nama_barang
        AND pending.exp_date = a.exp_date
        LEFT JOIN (
            SELECT
                nama_barang,
                exp_date,
                SUM(qty) AS qtyinput_1
            FROM
                tb_ics_opname
            WHERE
                tim = 1
            GROUP BY
                nama_barang,
                exp_date
        ) AS op1 ON op1.nama_barang = a.nama_barang
        AND op1.exp_date = a.exp_date
        LEFT JOIN (
            SELECT
                nama_barang,
                exp_date,
                SUM(qty) AS qtyinput_2
            FROM
                tb_ics_opname
            WHERE
                tim = 2
            GROUP BY
                nama_barang,
                exp_date
        ) AS op2 ON op2.nama_barang = a.nama_barang
        AND op2.exp_date = a.exp_date
        WHERE
            b.kd_system = '$kdbr'
        GROUP BY
            a.nama_barang,
            a.exp_date
        ORDER BY
            a.nama_barang,
            a.exp_date;")->result();
    }

    public function rekapopnamebarang($kdbr, $tim)
    {
        return $this->db->query("SELECT
            COUNT(*) AS total_data,
            SUM(CASE
                WHEN (qty_final = qtyinput_1) THEN 1
                ELSE 0
            END) AS total_match,
            SUM(CASE
                WHEN (qtyinput_1 IS NOT NULL AND qty_final != qtyinput_1) THEN 1
                ELSE 0
            END) AS total_not_match
        FROM (
            SELECT
                a.nama_barang,
                a.exp_date,
                SUM(a.qty) + COALESCE(pending.qty_pending, 0) AS qty_final,
                COALESCE(op1.qtyinput_1, 0) AS qtyinput_1
            FROM tb_ics a
            JOIN tb_mbarang b ON b.nm_barang = a.nama_barang
            LEFT JOIN (
                SELECT d.nama_barang, d.exp_date, SUM(d.qty) AS qty_pending
                FROM tb_ics_do d
                JOIN tb_mbarang mb ON mb.nm_barang = d.nama_barang
                WHERE mb.kd_system = '$kdbr'
                GROUP BY d.nama_barang, d.exp_date
            ) AS pending
            ON pending.nama_barang = a.nama_barang AND pending.exp_date = a.exp_date
            LEFT JOIN (
                SELECT nama_barang, exp_date, SUM(qty) AS qtyinput_1
                FROM tb_ics_opname
                WHERE tim = '$tim'
                GROUP BY nama_barang, exp_date
            ) AS op1
            ON op1.nama_barang = a.nama_barang AND op1.exp_date = a.exp_date
            WHERE b.kd_system = '$kdbr'
            GROUP BY a.nama_barang, a.exp_date) AS hasil ")->result();
    }

    public function detail_opname_barang($kdbr, $tim)
    {
        return $this->db->query("SELECT 
            b.kd_system AS kd_barang,
            a.nama_barang,
            SUM(a.qty) AS qty_zahir,
            (SUM(a.qty) + COALESCE(pending.qty_pending, 0)) AS qty_zahirwith_pnd,
            COALESCE(pending.qty_pending, 0) AS qty_pending,
            COALESCE(supp.qty_supp, 0) AS qty_supp,
            COALESCE(opname.qty_fisik, 0) AS qty_fisik,
            IF((SUM(a.qty) + COALESCE(pending.qty_pending, 0)) - COALESCE(supp.qty_supp, 0) = COALESCE(opname.qty_fisik, 0), 1, 0) AS status
        FROM tb_ics a
        JOIN tb_mbarang b ON b.nm_barang = a.nama_barang
        LEFT JOIN (
            SELECT nama_barang, SUM(qty) AS qty_pending
            FROM tb_ics_do
            GROUP BY nama_barang
        ) pending ON pending.nama_barang = a.nama_barang
        LEFT JOIN (
            SELECT nama_barang, SUM(qty) AS qty_fisik
            FROM tb_ics_opname
            WHERE tim = '$tim'
            GROUP BY nama_barang
        ) opname ON opname.nama_barang = a.nama_barang
        LEFT JOIN (
            SELECT nama_barang, SUM(qty) AS qty_supp
            FROM tb_ics_supp
            GROUP BY nama_barang
        ) supp ON supp.nama_barang = a.nama_barang
        WHERE b.kd_system = '$kdbr'
        GROUP BY a.nama_barang")->result();
    }

    private function _hitungStatistik($data)
    {
        $total = count($data);
        $match = 0;
        $not_match = 0;

        foreach ($data as $row) {
            if (isset($row->status) && $row->status === 'MATCH') {
                $match++;
            } else {
                $not_match++;
            }
        }

        $persen_match = $total > 0 ? round(($match / $total) * 100, 2) : 0;
        $persen_not = 100 - $persen_match;

        return [
            'total' => $total,
            'match' => $match,
            'not_match' => $not_match,
            'persen_match' => $persen_match,
            'persen_not' => $persen_not
        ];
    }

    public function trackingopname($user)
    {
        return $this->db->query("SELECT
        a.*
        FROM tb_ics_opname a
        WHERE a.inputer = '$user'
        ")->result();
    }

    public function adm_trackingopname($tim)
    {
        return $this->db->query("SELECT
        a.*
        FROM tb_ics_opname a
        WHERE a.tim = '$tim'
        ")->result();
    }

    public function get_requestbr($id)
    {
        return $this->db->get_where('tb_req_opname', ['id' => $id])->row();
    }

    public function opname_req_user()
    {
        return $this->db->query("SELECT
        a.*
        FROM tb_req_opname a
        WHERE a.status = '1'
        ")->result();
    }

    public function all_barang_match_t1()
    {
        return $this->db->query("SELECT
            COUNT(DISTINCT ics.nama_barang) AS total_barang,
            SUM((COALESCE(op.qty_input, 0) = (ics.qty_buku + COALESCE(pending.qty_pending, 0))) + 0) AS total_match,
            SUM((COALESCE(op.qty_input, 0) != (ics.qty_buku + COALESCE(pending.qty_pending, 0))) + 0) AS total_notmatch
        FROM (
            SELECT 
                nama_barang, 
                SUM(qty) AS qty_buku
            FROM tb_ics
            GROUP BY nama_barang
        ) AS ics
        LEFT JOIN (
            SELECT 
                nama_barang, 
                SUM(qty) AS qty_input
            FROM tb_ics_opname
            WHERE tim = '1'
            GROUP BY nama_barang
        ) AS op ON ics.nama_barang = op.nama_barang
        LEFT JOIN (
            SELECT
                nama_barang,
                SUM(qty) AS qty_pending
            FROM tb_ics_do
            GROUP BY nama_barang
        ) AS pending ON pending.nama_barang = ics.nama_barang;")->result();
    }

    public function all_barang_match_t2()
    {
        return $this->db->query("SELECT
            COUNT(DISTINCT ics.nama_barang) AS total_barang,
            SUM((COALESCE(op.qty_input, 0) = (ics.qty_buku + COALESCE(pending.qty_pending, 0))) + 0) AS total_match,
            SUM((COALESCE(op.qty_input, 0) != (ics.qty_buku + COALESCE(pending.qty_pending, 0))) + 0) AS total_notmatch
        FROM (
            SELECT 
                nama_barang, 
                SUM(qty) AS qty_buku
            FROM tb_ics
            GROUP BY nama_barang
        ) AS ics
        LEFT JOIN (
            SELECT 
                nama_barang, 
                SUM(qty) AS qty_input
            FROM tb_ics_opname
            WHERE tim = '2'
            GROUP BY nama_barang
        ) AS op ON ics.nama_barang = op.nama_barang
        LEFT JOIN (
            SELECT
                nama_barang,
                SUM(qty) AS qty_pending
            FROM tb_ics_do
            GROUP BY nama_barang
        ) AS pending ON pending.nama_barang = ics.nama_barang;")->result();
    }

    public function get_wilayah()
    {
        return $this->db->query("SELECT
        a.nm_wilayah as wilayah,
        a.id AS id
        FROM tb_gdg_kordinat a
        ")->result();
    }

    public function list_opname_user_wilayah($wilayah)
    {
        return $this->db->query("SELECT
            nama_barang,
            exp_date,
            SUM(CASE WHEN tim = '1' THEN qty ELSE 0 END) AS fisik_tim1,
            SUM(CASE WHEN tim = '2' THEN qty ELSE 0 END) AS fisik_tim2
        FROM tb_ics_opname
        WHERE wilayah = '$wilayah'
        GROUP BY nama_barang, exp_date;")->result();
    }

    public function get_nmbarang($kdbarang)
    {
        return $this->db->query("SELECT
        a.nm_barang AS nama_barang,
        a.kd_system AS kdbarang
        FROM tb_mbarang a
        WHERE a.kd_system = '$kdbarang'
        ")->result();
    }

    public function getreqbr_opname($kdbarang, $tim)
    {
        return $this->db->query("SELECT
        a.*
        FROM tb_req_opname a
        LEFT JOIN tb_mbarang b ON a.nama_barang = b.nm_barang
        WHERE b.kd_system = '$kdbarang' AND a.tim = '$tim' AND a.status = '1'
        ")->result();
    }

    public function deleteopnameinputuser($id)
    {
        return $this->db->delete('tb_ics_opname', array("id" => $id));
    }

    public function getdataopname($id)
    {
        return $this->db->select('a.inputer, a.nama_barang, a.exp_date, a.qty, a.qty_box, a.qty_pcs')
            ->from('tb_ics_opname a')
            ->join('tb_mbarang b', 'b.nm_barang = a.nama_barang')
            ->where('a.id', $id)
            ->get()
            ->row();
    }

    public function countrequseropname($kd_system, $tim)
    {
        $this->db->select('COUNT(a.id) AS total_request');
        $this->db->from('tb_req_opname a');
        $this->db->join('tb_mbarang b', 'b.nm_barang = a.nama_barang', 'left');
        $this->db->where('b.kd_system', $kd_system);
        $this->db->where('a.tim', $tim);
        $this->db->where('a.status', '1');

        $query = $this->db->get();
        return $query->row()->total_request;
    }

    public function list_inputer_by_allbarang($kdbarang, $tim)
    {
        return $this->db->query("SELECT	
        a.id,
        b.kd_system,
        a.nama_barang,
        a.qty,
        a.qty_box,
        a.qty_pcs,
        a.tim,
        (b.p*b.l*b.t) AS dimensi,
        a.inputer
        FROM tb_ics_opname a
        LEFT JOIN tb_mbarang b ON a.nama_barang = b.nm_barang
        WHERE b.kd_system = '$kdbarang' AND a.tim = '$tim'
        ")->result();
    }

    public function list_inputer_by_expdate($kdbarang, $tim)
    {
        return $this->db->query("SELECT	
            a.id,
            b.kd_system,
            a.nama_barang,
            a.exp_date,
            COALESCE(zahir.qty_zahir, 0) AS qty_zahir,
            COALESCE(pending.qty_pending, 0) AS qty_pending,
            (COALESCE(zahir.qty_zahir, 0) + COALESCE(pending.qty_pending, 0)-COALESCE(supp.qty_supp,0)) AS qty_with_pending,
            COALESCE(opname.qty_fisik, 0) AS qty_fisik,
            COALESCE(supp.qty_supp,0) AS qty_supp,
            IF(COALESCE(zahir.qty_zahir, 0) + COALESCE(pending.qty_pending, 0) = COALESCE(opname.qty_fisik, 0),1,0) AS status,
            a.qty,
            a.qty_box,
            a.qty_pcs,
            a.tim,
            (b.p * b.l * b.t) AS dimensi,
            a.inputer,
            log.keterangan
            FROM tb_ics_opname a
            JOIN tb_mbarang b ON b.nm_barang = a.nama_barang
            LEFT JOIN(
                SELECT nama_barang, exp_date , keterangan
                FROM tb_log_ics
                GROUP BY nama_barang, exp_date
            ) AS log ON log.nama_barang = a.nama_barang AND log.exp_date = a.exp_date
            LEFT JOIN (
                SELECT nama_barang, exp_date, SUM(qty) AS qty_zahir
                FROM tb_ics
                GROUP BY nama_barang, exp_date
            ) AS zahir ON zahir.nama_barang = a.nama_barang AND zahir.exp_date = a.exp_date
            LEFT JOIN (
                SELECT d.nama_barang, d.exp_date, SUM(d.qty) AS qty_pending
                FROM tb_ics_do d
                JOIN tb_ics i ON i.nama_barang = d.nama_barang AND i.exp_date = d.exp_date
                GROUP BY d.nama_barang, d.exp_date
            ) AS pending ON pending.nama_barang = a.nama_barang AND pending.exp_date = a.exp_date
            LEFT JOIN (
                SELECT nama_barang, exp_date, SUM(qty) AS qty_fisik
                FROM tb_ics_opname
                WHERE tim = '$tim'
                GROUP BY nama_barang, exp_date
            ) AS opname ON opname.nama_barang = a.nama_barang AND opname.exp_date = a.exp_date
            LEFT JOIN (
                SELECT nama_barang, exp_date, SUM(qty) AS qty_supp
                FROM tb_ics_supp
                GROUP BY nama_barang, exp_date
                ) AS supp ON supp.nama_barang = a.nama_barang AND supp.exp_date = a.exp_date
            WHERE b.kd_system = '$kdbarang'
            AND a.tim = '$tim'
            ORDER BY a.nama_barang, a.exp_date")->result();
    }

    public function fefo_match_t1()
    {
        return $this->db->query("SELECT
            COUNT(*) AS total_barang,
            SUM((COALESCE(op.qty_input, 0) = (ics.qty_buku + COALESCE(pending.qty_pending, 0))) + 0) AS total_match,
            SUM((COALESCE(op.qty_input, 0) != (ics.qty_buku + COALESCE(pending.qty_pending, 0))) + 0) AS total_notmatch
        FROM (
            SELECT 
                nama_barang, 
                exp_date,
                SUM(qty) AS qty_buku
            FROM tb_ics
            GROUP BY nama_barang, exp_date
        ) AS ics
        LEFT JOIN (
            SELECT 
                nama_barang, 
                exp_date,
                SUM(qty) AS qty_input
            FROM tb_ics_opname
            WHERE tim = '1'
            GROUP BY nama_barang, exp_date
        ) AS op ON ics.nama_barang = op.nama_barang AND ics.exp_date = op.exp_date
        LEFT JOIN (
            SELECT
                nama_barang,
                exp_date,
                SUM(qty) AS qty_pending
            FROM tb_ics_do 
            GROUP BY nama_barang, exp_date
        ) AS pending ON pending.nama_barang = ics.nama_barang AND pending.exp_date = ics.exp_date;")->result();
    }

    public function fefo_match_t2()
    {
        return $this->db->query("SELECT
            COUNT(*) AS total_barang,
            SUM((COALESCE(op.qty_input, 0) = (ics.qty_buku + COALESCE(pending.qty_pending, 0))) + 0) AS total_match,
            SUM((COALESCE(op.qty_input, 0) != (ics.qty_buku + COALESCE(pending.qty_pending, 0))) + 0) AS total_notmatch
        FROM (
            SELECT 
                nama_barang, 
                exp_date,
                SUM(qty) AS qty_buku
            FROM tb_ics
            GROUP BY nama_barang, exp_date
        ) AS ics
        LEFT JOIN (
            SELECT 
                nama_barang, 
                exp_date,
                SUM(qty) AS qty_input
            FROM tb_ics_opname
            WHERE tim = '1'
            GROUP BY nama_barang, exp_date
        ) AS op ON ics.nama_barang = op.nama_barang AND ics.exp_date = op.exp_date
        LEFT JOIN (
            SELECT
                nama_barang,
                exp_date,
                SUM(qty) AS qty_pending
            FROM tb_ics_do 
            GROUP BY nama_barang, exp_date
        ) AS pending ON pending.nama_barang = ics.nama_barang AND pending.exp_date = ics.exp_date;")->result();
    }

    public function create_view_ics()
    {
        return $this->db->query("SELECT 
        x.nama_barang,
        x.exp_date,
        IFNULL(s.qty, 0) AS saldo_awal,
        IFNULL(p.qty, 0) AS qty_masuk,
        IFNULL(d.total_do, 0) AS qty_keluar,
        (IFNULL(s.qty, 0) + IFNULL(p.qty, 0) - IFNULL(d.total_do, 0)) AS sistem_qty,
        IFNULL(o.qty, 0) AS fisik_qty,
        ((IFNULL(s.qty, 0) + IFNULL(p.qty, 0) - IFNULL(d.total_do, 0)) - IFNULL(o.qty, 0)) AS selisih,

        CASE 
            WHEN ((IFNULL(s.qty, 0) + IFNULL(p.qty, 0) - IFNULL(d.total_do, 0)) = IFNULL(o.qty, 0)) 
            THEN 'sama'
            ELSE 'beda'
        END AS keterangan,

        mb.dimensi,
        FLOOR((IFNULL(s.qty, 0) + IFNULL(p.qty, 0) - IFNULL(d.total_do, 0)) / NULLIF(mb.dimensi, 0)) AS qty_box,
        ((IFNULL(s.qty, 0) + IFNULL(p.qty, 0) - IFNULL(d.total_do, 0)) % NULLIF(mb.dimensi, 0)) AS qty_pcs

    FROM (
        SELECT nama_barang, exp_date FROM tb_ics
        UNION
        SELECT nama_barang, exp_date FROM tb_ics_po
        UNION
        SELECT nama_barang, tgl_exp AS exp_date FROM tb_detail_do
        UNION
        SELECT nama_barang, exp_date FROM tb_ics_opname
    ) AS x

    LEFT JOIN (
        SELECT nama_barang, exp_date, SUM(qty) AS qty
        FROM tb_ics
        GROUP BY nama_barang, exp_date
    ) s ON x.nama_barang = s.nama_barang AND x.exp_date = s.exp_date

    LEFT JOIN (
        SELECT nama_barang, exp_date, SUM(qty) AS qty
        FROM tb_ics_po
        GROUP BY nama_barang, exp_date
    ) p ON x.nama_barang = p.nama_barang AND x.exp_date = p.exp_date

    LEFT JOIN (
        SELECT nama_barang, tgl_exp AS exp_date, SUM(qty) AS total_do
        FROM tb_detail_do
        GROUP BY nama_barang, tgl_exp
    ) d ON x.nama_barang = d.nama_barang AND x.exp_date = d.exp_date

    LEFT JOIN (
        SELECT nama_barang, exp_date, SUM(qty) AS qty
        FROM tb_ics_opname
        GROUP BY nama_barang, exp_date
    ) o ON x.nama_barang = o.nama_barang AND x.exp_date = o.exp_date

    LEFT JOIN (
        SELECT nm_barang, MIN(p * l * t) AS dimensi
        FROM tb_master_barang
        GROUP BY nm_barang
    ) mb ON x.nama_barang = mb.nm_barang

    ORDER BY x.nama_barang, x.exp_date
        ");
    }

    public function is_exist($kd_faktur)
    {
        // Cek apakah no_so sudah ada di tbso_sales_order
        $this->db->where('no_so', $kd_faktur);
        return $this->db->get('tbso_sales_order')->num_rows() > 0;
    }

    public function insert_data($data)
    {
        return $this->db->insert('tb_pre_do', $data);
    }

    public function insert_pnd_pre_batch($data)
    {
        return $this->db->insert_batch('tb_pre_do', $data);
    }

    public function insert_pnd_batch($data)
    {
        return $this->db->insert_batch('tb_pnd_do', $data);
    }

    public function get_detail_pnd_do($kd)
    {
        return $this->db->query("SELECT
            a.*
            FROM tb_pnd_do a
            WHERE a.kd_faktur = '$kd'
            GROUP BY a.kd_faktur
        ")->result();
    }

    public function get_do_cust_byfaktur_pnd($kd)
    {
        return $this->db->query("SELECT
            a.*,b.nama_barang
            FROM tb_pnd_do a
            JOIN tb_master_barang_all b ON b.kd_barang = a.kd_barang
            WHERE a.kd_faktur = '$kd'
        ")->result();
    }

    public function update_sts_pnd_detail($kd, $data)
    {
        $this->db->where('kd_faktur', $kd);
        return $this->db->update('tb_pnd_do', $data);
    }

    public function get_faktur_master($kd)
    {
        return $this->db->query("SELECT
            a.id,
            a.kd_faktur,
            a.kd_barang,
            c.nama_barang,
            a.qty,
            c.berat as gr_berat,
            (c.berat/1000) as convert_kg,
            (a.qty * (c.berat/1000)) AS total_berat,
            a.satuan,
            a.no_lot,
            a.tgl_exp,
            a.barang_sts
            FROM tb_pre_do a
            LEFT JOIN tb_customer b ON b.kd_customer = a.kd_customer
            LEFT JOIN tb_master_barang_all c ON c.kd_barang = a.kd_barang
            WHERE a.kd_faktur = '$kd'
        ")->result();
    }

    public function detail_pending_fk($kd)
    {
        return $this->db->query("SELECT
            a.id,
            a.kd_faktur,
            a.kd_barang,
            c.nama_barang,
            a.qty,
            c.berat as gr_berat,
            (c.berat/1000) as convert_kg,
            (a.qty * (c.berat/1000)) AS total_berat,
            a.satuan,
            a.no_lot,
            a.tgl_exp,
            a.barang_sts
            FROM tb_pnd_do a
            LEFT JOIN tb_master_barang_all c ON c.kd_barang = a.kd_barang
            WHERE a.kd_faktur = '$kd'
        ")->result();
    }

    public function get_faktur_bintang()
    {
        return $this->db->query("SELECT a.*
        FROM tb_pre_do a 
        WHERE a.kd_customer = 'BINT31' AND data_sts = '1'
        GROUP BY a.kd_faktur")->result();
    }

    public function get_cust_bintang($id)
    {
        return $this->db
            ->select('
            tb_pre_do.*,
            tb_customer.nama_customer,
            tb_customer.kd_customer
        ')
            ->from('tb_pre_do')
            ->join(
                'tb_customer',
                'tb_customer.kd_customer = tb_pre_do.kd_customer',
                'left'
            )
            ->where('tb_pre_do.id', $id)
            ->get()
            ->row();
    }

    public function get_customer_bintang($search = null)
    {
        if ($search) {
            $this->db->like('nama_customer', $search);
        }

        return $this->db
            ->limit(20)
            ->get('tb_customer')
            ->result();
    }

    public function update_customer_by_faktur($kd_faktur, $kd_customer)
    {
        return $this->db
            ->where('kd_faktur', $kd_faktur)
            ->update('tb_pre_do', [
                'kd_customer' => $kd_customer
            ]);
    }

    public function get_customer_by_kd($kd_customer)
    {
        return $this->db
            ->where('kd_customer', $kd_customer)
            ->get('tb_customer')
            ->row();
    }
    // model/M_logistik

    public function get_data_po($date1 = null, $date2 = null)
    {
        $sql = "SELECT
            pp.no_po,
            pp.kd_po,
            MAX(pp.tgl_transaksi) AS tgl_transaksi,
            pp.kd_suplier,
            MAX(s.nama_suplier) AS nama_suplier,
            COUNT(pp.kd_barang) AS jumlah_barang,
            SUM(CASE WHEN COALESCE(sub.qty_masuk,0) > 0 THEN 1 ELSE 0 END) AS jumlah_barang_masuk,
            MAX(sub.last_input) AS last_input,
            SUM(pp.qty) AS total_qty_order,
            SUM(COALESCE(sub.qty_masuk,0)) AS total_qty_masuk,
            CASE 
                WHEN SUM(COALESCE(sub.qty_masuk,0)) = 0 THEN 'OPEN'
                WHEN SUM(COALESCE(sub.qty_masuk,0)) < SUM(pp.qty) THEN 'PARTIAL'
                ELSE 'CLOSED'
            END AS status_po
        FROM tb_pre_po pp
        LEFT JOIN tb_suplier s 
            ON s.kd_suplier = pp.kd_suplier
        LEFT JOIN (
            SELECT 
                no_po, 
                kd_po,
                kd_barang,
                SUM(qty_diterima) AS qty_masuk,
                MAX(create_at) AS last_input
            FROM tb_po_received
            GROUP BY no_po, kd_po, kd_barang
        ) sub 
            ON sub.no_po = pp.no_po 
            AND sub.kd_po = pp.kd_po 
            AND sub.kd_barang = pp.kd_barang
        WHERE 1=1";

        $params = [];

        if (!empty($date1) && !empty($date2)) {
            $date1_formatted = date('Y-m-d', strtotime($date1));
            $date2_formatted = date('Y-m-d', strtotime($date2));

            $sql .= " AND STR_TO_DATE(pp.tgl_transaksi, '%d/%m/%Y') BETWEEN ? AND ?";
            $params[] = $date1_formatted;
            $params[] = $date2_formatted;
        }

        $sql .= " GROUP BY pp.no_po, pp.kd_po, pp.kd_suplier";
        $sql .= " HAVING total_qty_order > total_qty_masuk";
        $sql .= " ORDER BY MAX(pp.tgl_transaksi) DESC, pp.no_po DESC";

        return $this->db->query($sql, $params)->result_array();
    }

    public function get_lpb($date1 = null, $date2 = null)
    {
        $sql = "SELECT
            po.kd_po,
            po.tgl_transaksi,
            po.no_po,
            po.kdsupp,
            CASE
                WHEN po.nm_suplier IS NULL OR po.nm_suplier = '' THEN po.kdsupp
                ELSE po.nm_suplier
            END AS nm_suplier,
            po.total_barang_order,
            COALESCE(rcv.total_barang_diterima, 0) AS total_barang_diterima,
            po.total_qty_order,
            COALESCE(rcv.total_qty_diterima, 0) AS total_qty_diterima,
            CASE
                WHEN rcv.input_terakhir IS NULL OR rcv.input_terakhir = '' THEN '-'
                ELSE rcv.input_terakhir
            END AS input_terakhir,
            CASE
                WHEN po.total_qty_order <= 0 THEN 0
                WHEN COALESCE(rcv.total_qty_diterima, 0) >= po.total_qty_order THEN 100
                ELSE ROUND((COALESCE(rcv.total_qty_diterima, 0) / po.total_qty_order) * 100, 2)
            END AS progress_persen,
            CASE
                WHEN COALESCE(rcv.total_qty_diterima, 0) <= 0 THEN 'belum'
                WHEN COALESCE(rcv.total_qty_diterima, 0) < po.total_qty_order THEN 'partial'
                ELSE 'done'
            END AS status
        FROM (
            SELECT
                pp.kd_po,
                MAX(pp.tgl_transaksi) AS tgl_transaksi,
                MAX(pp.no_po) AS no_po,
                MAX(pp.kd_suplier) AS kdsupp,
                MAX(supp.nama_suplier) AS nm_suplier,
                COUNT(DISTINCT pp.kd_barang) AS total_barang_order,
                SUM(pp.qty * (mb.p*mb.l*mb.t)) AS total_qty_order
            FROM tb_pre_po pp
            LEFT JOIN tb_suplier supp
                ON supp.kd_suplier = pp.kd_suplier
            LEFT JOIN tb_master_barang_all mb 
                ON mb.kd_barang = pp.kd_barang
            WHERE 1=1";

        $params = [];

        if (!empty($date1) && !empty($date2)) {
            $date1_formatted = date('Y-m-d', strtotime($date1));
            $date2_formatted = date('Y-m-d', strtotime($date2));

            $sql .= " AND STR_TO_DATE(pp.tgl_transaksi, '%d/%m/%Y') BETWEEN ? AND ?";
            $params[] = $date1_formatted;
            $params[] = $date2_formatted;
        }

        $sql .= "
            GROUP BY pp.kd_po
        ) po
        LEFT JOIN (
            SELECT
                h.kd_po,
                COUNT(DISTINCT d.kd_barang) AS total_barang_diterima,
                SUM(d.qty_diterima) AS total_qty_diterima,
                MAX(COALESCE(d.input_at, h.input_at)) AS input_terakhir
            FROM tb_lpb h
            INNER JOIN tb_lpb_detail d
                ON d.id_lpb = h.id_lpb
            GROUP BY h.kd_po
        ) rcv
            ON rcv.kd_po = po.kd_po
        ORDER BY STR_TO_DATE(po.tgl_transaksi, '%d/%m/%Y') DESC, po.no_po DESC";

        return $this->db->query($sql, $params)->result_array();
    }

    public function get_lpb_admin_po($date1 = null, $date2 = null)
    {
        $sql = "SELECT
            po.kd_po,
            po.tgl_transaksi,
            po.no_po,
            po.kdsupp,
            CASE
                WHEN po.nm_suplier IS NULL OR po.nm_suplier = '' THEN po.kdsupp
                ELSE po.nm_suplier
            END AS nm_suplier,
            po.total_qty_order,
            COALESCE(rcv.total_qty_diterima, 0) AS total_qty_diterima,
            CASE
                WHEN po.total_qty_order <= 0 THEN 0
                WHEN COALESCE(rcv.total_qty_diterima, 0) >= po.total_qty_order THEN 100
                ELSE ROUND((COALESCE(rcv.total_qty_diterima, 0) / po.total_qty_order) * 100, 2)
            END AS progress_persen,
            CASE
                WHEN COALESCE(rcv.total_qty_diterima, 0) <= 0 THEN 'belum'
                WHEN COALESCE(rcv.total_qty_diterima, 0) < po.total_qty_order THEN 'partial'
                ELSE 'done'
            END AS status
        FROM (
            SELECT
                pp.kd_po,
                MAX(pp.tgl_transaksi) AS tgl_transaksi,
                MAX(pp.no_po) AS no_po,
                MAX(pp.kd_suplier) AS kdsupp,
                MAX(supp.nama_suplier) AS nm_suplier,
                SUM(pp.qty * (mb.p*mb.l*mb.t)) AS total_qty_order
            FROM tb_pre_po pp
            LEFT JOIN tb_suplier supp
                ON supp.kd_suplier = pp.kd_suplier
            LEFT JOIN tb_master_barang_all mb
                ON mb.kd_barang = pp.kd_barang
            WHERE 1=1";

        $params = [];

        if (!empty($date1) && !empty($date2)) {
            $date1_formatted = date('Y-m-d', strtotime($date1));
            $date2_formatted = date('Y-m-d', strtotime($date2));

            $sql .= " AND STR_TO_DATE(pp.tgl_transaksi, '%d/%m/%Y') BETWEEN ? AND ?";
            $params[] = $date1_formatted;
            $params[] = $date2_formatted;
        }

        $sql .= "
            GROUP BY pp.kd_po
        ) po
        LEFT JOIN (
            SELECT
                h.kd_po,
                SUM(d.qty_diterima) AS total_qty_diterima
            FROM tb_lpb h
            INNER JOIN tb_lpb_detail d
                ON d.id_lpb = h.id_lpb
            GROUP BY h.kd_po
        ) rcv
            ON rcv.kd_po = po.kd_po
        ORDER BY
            CASE
                WHEN COALESCE(rcv.total_qty_diterima, 0) > 0
                    AND COALESCE(rcv.total_qty_diterima, 0) < po.total_qty_order THEN 1
                WHEN COALESCE(rcv.total_qty_diterima, 0) <= 0 THEN 2
                ELSE 3
            END ASC,
            STR_TO_DATE(po.tgl_transaksi, '%d/%m/%Y') DESC,
            po.no_po DESC";

        return $this->db->query($sql, $params)->result_array();
    }

    public function get_barang_by_po()
    {
        while (ob_get_level()) ob_end_clean();

        $no_po      = $this->input->get('no_po', TRUE);
        $kd_suplier = $this->input->get('kd_suplier', TRUE);
        $kd_po      = $this->input->get('kd_po', TRUE);

        if (empty($no_po) || empty($kd_po)) {
            echo json_encode(['status' => 'error', 'message' => 'Parameter tidak lengkap']);
            exit;
        }

        // Query untuk mendapatkan detail barang berdasarkan kd_po yang spesifik
        $sql = "
            SELECT
                pp.kd_po,
                pp.kd_barang,
                COALESCE(mb.nama_barang, '-') AS nama_barang,
                pp.qty AS qty_order,
                pp.satuan,
                COALESCE(sub.qty_masuk, 0) AS qty_masuk,
                (pp.qty - COALESCE(sub.qty_masuk, 0)) AS sisa
            FROM tb_pre_po pp
            LEFT JOIN tb_master_barang_all mb ON mb.kd_barang = pp.kd_barang
            LEFT JOIN (
                SELECT kd_po, kd_barang, SUM(qty_diterima) AS qty_masuk
                FROM tb_po_received
                WHERE no_po = ? AND kd_po = ?
                GROUP BY kd_po, kd_barang
            ) sub ON sub.kd_po = pp.kd_po AND sub.kd_barang = pp.kd_barang
            WHERE pp.no_po = ? 
                AND pp.kd_po = ?
                AND pp.kd_suplier = ?
            HAVING sisa > 0
            ORDER BY pp.kd_barang
        ";

        $params = [$no_po, $kd_po, $no_po, $kd_po, $kd_suplier];
        $result = $this->db->query($sql, $params)->result_array();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result);
        exit;
    }

    public function save_detail_lpb($data)
    {
        $insert = [
            'no_po'        => $data['no_po'],
            'kd_barang'    => $data['kd_barang'],
            'qty_diterima' => (int) $data['qty_diterima'],
            'no_lot'       => $data['no_lot']   ?? null,
            'exp_date'     => !empty($data['exp_date']) ? $data['exp_date'] : null,
            'create_at'    => date('Y-m-d H:i:s'),
        ];

        return $this->db->insert('tb_po_received', $insert);
    }

    public function get_detail_by_po($no_po)
    {
        $this->db->select('
            dl.*, 
            mb.nama_barang
        ');
        $this->db->from('tb_po_received dl');
        $this->db->join(
            'tb_master_barang_all mb',
            'mb.kd_barang = dl.kd_barang',
            'left'
        );
        $this->db->where('dl.no_po', $no_po);
        $this->db->order_by('dl.id_detail_lpb', 'ASC');

        return $this->db->get()->result_array();
    }

    public function detail_po_received($nopo, $kdsup)
    {
        $sql = "SELECT 
                a.id_pre_po AS id,
                a.no_po,
                a.kd_po,
                a.kd_barang,
                b.nama_barang,
                (b.p * b.l * b.t) AS dimensi_br,
                a.qty * (b.p * b.l * b.t) AS qty_kecil, 
                a.qty AS qty_besar,
                a.satuan,
                a.hrg_satuan,
                a.harga_total,
                COALESCE(r.qty_diterima, 0) AS qty_diterima,
                COALESCE(r.qty_diterima, 0) AS qty_kecil_diterima,
                GREATEST((a.qty * (b.p * b.l * b.t)) - COALESCE(r.qty_diterima, 0), 0) AS qty_sisa,
                GREATEST((a.qty * (b.p * b.l * b.t)) - COALESCE(r.qty_diterima, 0), 0) AS qty_kecil_sisa,
                COALESCE(r.total_lpb_record, 0) AS total_lpb_record,
                
                CASE 
                    WHEN COALESCE(r.qty_diterima, 0) = 0 THEN 'BELUM'
                    WHEN  a.qty * (b.p * b.l * b.t) - COALESCE(r.qty_diterima, 0) != a.qty THEN 'PARTIAL'
                    ELSE 'FULL'
                END AS status_barang
                          
            FROM tb_pre_po a
            LEFT JOIN tb_master_barang_all b 
                ON b.kd_barang = a.kd_barang
            LEFT JOIN (
                SELECT 
                    h.no_po,
                    h.kd_po,
                    d.kd_barang,
                    SUM(d.qty_diterima) AS qty_diterima,
                    COUNT(DISTINCT h.id_lpb) AS total_lpb_record
                FROM tb_lpb_detail d
                INNER JOIN tb_lpb h ON h.id_lpb = d.id_lpb
                GROUP BY h.no_po, h.kd_po, d.kd_barang
            ) r 
                ON r.no_po = a.no_po
                AND r.kd_po = a.kd_po
                AND r.kd_barang = a.kd_barang
            WHERE a.no_po = ?
                AND a.kd_suplier = ?";

        return $this->db->query($sql, [$nopo, $kdsup])->result_array();
    }

    public function get_lpb_records_by_kd_po($kd_po)
    {
        $sql = "SELECT
                h.id_lpb,
                h.kd_po,
                h.nosj,
                h.tgl_sj,
                h.no_po,
                h.no_invoice,
                h.gudang_id,
                COALESCE(g.nama_gudang, '-') AS nama_gudang,
                h.keterangan,
                h.input_at,
                COUNT(d.id_detail_lpb) AS total_baris,
                COUNT(DISTINCT d.kd_barang) AS total_item,
                COALESCE(SUM(d.qty_diterima), 0) AS total_qty
            FROM tb_lpb h
            LEFT JOIN tb_lpb_detail d ON d.id_lpb = h.id_lpb
            LEFT JOIN tb_gudang g ON g.id_gudang = h.gudang_id
            WHERE h.kd_po = ?
            GROUP BY
                h.id_lpb,
                h.kd_po,
                h.nosj,
                h.tgl_sj,
                h.no_po,
                h.no_invoice,
                h.gudang_id,
                g.nama_gudang,
                h.keterangan,
                h.input_at
            ORDER BY h.input_at DESC, h.id_lpb DESC";

        return $this->db->query($sql, [$kd_po])->result_array();
    }

    public function get_lpb_record_header($id_lpb)
    {
        $sql = "SELECT
                h.id_lpb,
                h.kd_po,
                h.no_po,
                h.nosj,
                h.tgl_sj,
                h.no_invoice,
                h.gudang_id,
                COALESCE(g.nama_gudang, '-') AS nama_gudang,
                h.keterangan,
                h.input_at,
                COUNT(d.id_detail_lpb) AS total_baris,
                COUNT(DISTINCT d.kd_barang) AS total_item,
                COALESCE(SUM(d.qty_diterima), 0) AS total_qty
            FROM tb_lpb h
            LEFT JOIN tb_lpb_detail d ON d.id_lpb = h.id_lpb
            LEFT JOIN tb_gudang g ON g.id_gudang = h.gudang_id
            WHERE h.id_lpb = ?
            GROUP BY
                h.id_lpb,
                h.kd_po,
                h.no_po,
                h.nosj,
                h.tgl_sj,
                h.no_invoice,
                h.gudang_id,
                g.nama_gudang,
                h.keterangan,
                h.input_at
            LIMIT 1";

        return $this->db->query($sql, [$id_lpb])->row_array();
    }

    public function get_lpb_record_detail_rows($id_lpb)
    {
        $sql = "SELECT
                d.id_detail_lpb,
                d.id_lpb,
                d.kd_barang,
                COALESCE(mb.nama_barang, '-') AS nama_barang,
                d.qty_diterima,
                d.no_lot,
                d.expired_date,
                d.input_at
            FROM tb_lpb_detail d
            LEFT JOIN tb_master_barang_all mb ON mb.kd_barang = d.kd_barang
            WHERE d.id_lpb = ?
            ORDER BY d.id_detail_lpb ASC";

        return $this->db->query($sql, [$id_lpb])->result_array();
    }

    public function get_tmp_po_received_item($kd_po, $kd_barang)
    {
        return $this->db
            ->from('tb_tmp_po_received')
            ->where('kd_po', $kd_po)
            ->where('kd_barang', $kd_barang)
            ->order_by('id_tmp_recieved', 'ASC')
            ->get()
            ->result_array();
    }

    public function replace_tmp_po_received_item($kd_po, $kd_barang, $rows)
    {
        $this->db->where('kd_po', $kd_po);
        $this->db->where('kd_barang', $kd_barang);
        $this->db->delete('tb_tmp_po_received');

        if (empty($rows)) {
            return TRUE;
        }

        return $this->db->insert_batch('tb_tmp_po_received', $rows);
    }

    public function get_tmp_po_received_summary($no_po, $kd_suplier)
    {
        $this->db->select('
            t.id_tmp_recieved,
            t.kd_po,
            t.kd_barang,
            COALESCE(mb.nama_barang, "-") AS nama_barang,
            t.qty_diterima,
            t.satuan,
            t.no_lot,
            t.expired_date
        ');
        $this->db->from('tb_tmp_po_received t');
        $this->db->join(
            'tb_pre_po pp',
            'pp.kd_po = t.kd_po AND pp.kd_barang = t.kd_barang AND pp.kd_suplier = t.kd_suplier',
            'inner'
        );
        $this->db->join('tb_master_barang_all mb', 'mb.kd_barang = t.kd_barang', 'left');
        $this->db->where('pp.no_po', $no_po);
        $this->db->where('t.kd_suplier', $kd_suplier);
        $this->db->order_by('t.kd_barang', 'ASC');
        $this->db->order_by('t.id_tmp_recieved', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_tmp_po_received_summary_by_item($kd_po, $kd_barang)
    {
        $this->db->select('
            t.id_tmp_recieved,
            t.kd_po,
            t.kd_barang,
            COALESCE(mb.nama_barang, "-") AS nama_barang,
            t.qty_diterima,
            t.satuan,
            t.no_lot,
            t.expired_date
        ');
        $this->db->from('tb_tmp_po_received t');
        $this->db->join('tb_master_barang_all mb', 'mb.kd_barang = t.kd_barang', 'left');
        $this->db->where('t.kd_po', $kd_po);
        $this->db->where('t.kd_barang', $kd_barang);
        $this->db->order_by('t.id_tmp_recieved', 'ASC');

        return $this->db->get()->result_array();
    }

    public function delete_tmp_po_received_row($idTmpReceived, $kdSuplier = '', $noPo = '')
    {
        $idTmpReceived = (int) $idTmpReceived;

        if ($idTmpReceived <= 0) {
            return FALSE;
        }

        $this->db->from('tb_tmp_po_received t');
        $this->db->join(
            'tb_pre_po pp',
            'pp.kd_po = t.kd_po AND pp.kd_barang = t.kd_barang AND pp.kd_suplier = t.kd_suplier',
            'inner'
        );
        $this->db->where('t.id_tmp_recieved', $idTmpReceived);

        if ($kdSuplier !== '') {
            $this->db->where('t.kd_suplier', $kdSuplier);
        }

        if ($noPo !== '') {
            $this->db->where('pp.no_po', $noPo);
        }

        $row = $this->db->get()->row_array();

        if (!$row) {
            return FALSE;
        }

        $this->db->where('id_tmp_recieved', $idTmpReceived);

        return $this->db->delete('tb_tmp_po_received');
    }

    public function get_po_remaining_qty($no_po, $kd_suplier)
    {
        $sql = "SELECT
                pp.kd_po,
                pp.kd_barang,
                pp.qty * (mb.p * mb.l * mb.t) AS qty_order,
                COALESCE(rcv.qty_diterima, 0) AS qty_diterima,
                GREATEST(pp.qty * (mb.p * mb.l * mb.t) - COALESCE(rcv.qty_diterima, 0), 0) AS qty_sisa,
                GREATEST(pp.qty * (mb.p * mb.l * mb.t) - COALESCE(rcv.qty_diterima, 0), 0) AS qty_kecil_sisa
            FROM tb_pre_po pp
            LEFT JOIN tb_master_barang_all mb
                ON mb.kd_barang = pp.kd_barang
            LEFT JOIN (
                SELECT
                    h.no_po,
                    d.kd_barang,
                    SUM(d.qty_diterima) AS qty_diterima
                FROM tb_lpb_detail d
                INNER JOIN tb_lpb h ON h.id_lpb = d.id_lpb
                GROUP BY h.no_po, d.kd_barang
            ) rcv
                ON rcv.no_po = pp.no_po
                AND rcv.kd_barang = pp.kd_barang
            WHERE pp.no_po = ?
                AND pp.kd_suplier = ?
        ";

        return $this->db->query($sql, [$no_po, $kd_suplier])->result_array();
    }

    public function get_po_remaining_qty_by_item($kd_po, $kd_barang)
    {
        $sql = "SELECT
                pp.no_po,
                pp.kd_suplier,
                pp.kd_po,
                pp.kd_barang,
                pp.qty * (mb.p * mb.l * mb.t) AS qty_order,
                COALESCE(rcv.qty_diterima, 0) AS qty_diterima,
                GREATEST(pp.qty * (mb.p * mb.l * mb.t) - COALESCE(rcv.qty_diterima, 0), 0) AS qty_sisa,
                GREATEST(pp.qty * (mb.p * mb.l * mb.t) - COALESCE(rcv.qty_diterima, 0), 0) AS qty_kecil_sisa
            FROM tb_pre_po pp
            LEFT JOIN tb_master_barang_all mb
                ON mb.kd_barang = pp.kd_barang
            LEFT JOIN (
                SELECT
                    h.no_po,
                    d.kd_barang,
                    SUM(d.qty_diterima) AS qty_diterima
                FROM tb_lpb_detail d
                INNER JOIN tb_lpb h ON h.id_lpb = d.id_lpb
                GROUP BY h.no_po, d.kd_barang
            ) rcv
                ON rcv.no_po = pp.no_po
                AND rcv.kd_barang = pp.kd_barang
            WHERE pp.kd_po = ?
                AND pp.kd_barang = ?
            LIMIT 1
        ";

        return $this->db->query($sql, [$kd_po, $kd_barang])->row_array();
    }

    public function get_tmp_po_received_posting_rows($no_po, $kd_suplier)
    {
        $this->db->select('
            t.id_tmp_recieved,
            t.kd_po,
            t.kd_suplier,
            t.kd_barang,
            t.qty_diterima,
            t.satuan,
            t.no_lot,
            t.expired_date
        ');
        $this->db->from('tb_tmp_po_received t');
        $this->db->join(
            'tb_pre_po pp',
            'pp.kd_po = t.kd_po AND pp.kd_barang = t.kd_barang AND pp.kd_suplier = t.kd_suplier',
            'inner'
        );
        $this->db->where('pp.no_po', $no_po);
        $this->db->where('t.kd_suplier', $kd_suplier);
        $this->db->order_by('t.id_tmp_recieved', 'ASC');

        return $this->db->get()->result_array();
    }

    public function update_pre_po_status_by_kd_po($kd_po, $status)
    {
        return $this->db
            ->where('kd_po', $kd_po)
            ->update('tb_pre_po', ['status' => $status]);
    }

    public function get_pre_po_adjustment($kd_po)
    {
        $this->db->select('
            pp.id_pre_po,
            pp.no_po,
            pp.kd_po,
            pp.kd_barang,
            COALESCE(mb.nama_barang, "-") AS nama_barang,
            pp.qty,
            pp.satuan,
            pp.hrg_satuan,
            pp.harga_total,
            pp.status
        ');
        $this->db->from('tb_pre_po pp');
        $this->db->join('tb_master_barang_all mb', 'mb.kd_barang = pp.kd_barang', 'left');
        $this->db->where('pp.kd_po', $kd_po);
        $this->db->order_by('pp.id_pre_po', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_pre_po_invoice_adjustment($kd_po)
    {
        $hasAdjustmentTable = $this->db->table_exists('tb_pre_po_invoice_adjustment');

        $this->db->select('
            pp.id_pre_po,
            pp.no_po,
            pp.kd_po,
            pp.kd_barang,
            COALESCE(mb.nama_barang, "-") AS nama_barang,
            ' . ($hasAdjustmentTable ? '
                COALESCE(adj.qty, pp.qty, 0) AS qty,
                COALESCE(adj.satuan, pp.satuan, "-") AS satuan,
                COALESCE(adj.harga_satuan, pp.hrg_satuan, 0) AS harga_satuan,
                COALESCE(adj.total_harga, (pp.qty * pp.hrg_satuan), 0) AS harga_total,
                COALESCE(adj.harga, pp.hrg_satuan, 0) AS harga,
                COALESCE(adj.harga_diskon, pp.hrg_satuan, 0) AS harga_diskon,
                COALESCE(adj.total_harga_diskon, (pp.qty * COALESCE(adj.harga_diskon, pp.hrg_satuan)), 0) AS harga_total_diskon,
                COALESCE(adj.tax_percent, 0) AS tax_percent,
                COALESCE(adj.tax, 0) AS tax,
                COALESCE(adj.tax_diskon, 0) AS tax_diskon,
                COALESCE(adj.grand_total, COALESCE(adj.total_harga, (pp.qty * pp.hrg_satuan)) + COALESCE(adj.tax, 0), 0) AS grand_total,
                COALESCE(adj.grand_total_diskon, COALESCE(adj.total_harga_diskon, (pp.qty * COALESCE(adj.harga_diskon, pp.hrg_satuan))) + COALESCE(adj.tax_diskon, 0), 0) AS grand_total_diskon
            ' : '
                pp.qty AS qty,
                pp.satuan AS satuan,
                pp.hrg_satuan AS harga_satuan,
                (pp.qty * pp.hrg_satuan) AS harga_total,
                pp.hrg_satuan AS harga,
                pp.hrg_satuan AS harga_diskon,
                (pp.qty * pp.hrg_satuan) AS harga_total_diskon,
                0 AS tax_percent,
                0 AS tax,
                0 AS tax_diskon,
                (pp.qty * pp.hrg_satuan) AS grand_total,
                (pp.qty * pp.hrg_satuan) AS grand_total_diskon
            ') . '
        ');
        $this->db->from('tb_pre_po pp');
        $this->db->join('tb_master_barang_all mb', 'mb.kd_barang = pp.kd_barang', 'left');
        if ($hasAdjustmentTable) {
            $this->db->join('tb_pre_po_invoice_adjustment adj', 'adj.kd_po = pp.kd_po AND adj.kd_barang = pp.kd_barang', 'left');
        }
        $this->db->where('pp.kd_po', $kd_po);
        $this->db->order_by('pp.id_pre_po', 'ASC');

        return $this->db->get()->result_array();
    }

    public function get_pre_po_invoice_adjustment_summary($kd_po, $rows = null)
    {
        if ($rows === null) {
            $rows = $this->get_pre_po_invoice_adjustment($kd_po);
        }

        $summary = [
            'total_harga' => 0,
            'total_harga_diskon' => 0,
            'tax_percent' => 0,
            'tax' => 0,
            'tax_diskon' => 0,
            'grand_total' => 0,
            'grand_total_diskon' => 0
        ];

        foreach ($rows as $row) {
            $summary['total_harga'] += (float) ($row['harga_total'] ?? 0);
            $summary['total_harga_diskon'] += (float) ($row['harga_total_diskon'] ?? 0);
            if ((float) ($row['tax_percent'] ?? 0) > 0) {
                $summary['tax_percent'] = (float) $row['tax_percent'];
            }
        }

        $summary['tax'] = ($summary['tax_percent'] / 100) * $summary['total_harga'];
        $summary['tax_diskon'] = ($summary['tax_percent'] / 100) * $summary['total_harga_diskon'];
        $summary['grand_total'] = $summary['total_harga'] + $summary['tax'];
        $summary['grand_total_diskon'] = $summary['total_harga_diskon'] + $summary['tax_diskon'];

        return $summary;
    }

    public function submit_adjustment($payload)
    {
        $row = $this->db
            ->where('kd_po', $payload['kd_po'])
            ->where('kd_barang', $payload['kd_barang'])
            ->limit(1)
            ->get('tb_pre_po')
            ->row_array();

        if (!$row) {
            return FALSE;
        }

        $qty = (float) $row['qty'];
        $hargaBaru = (float) $payload['harga_satuan_baru'];
        $totalBaru = $qty * $hargaBaru;

        $this->db->where('id_pre_po', $row['id_pre_po']);
        $updated = $this->db->update('tb_pre_po', [
            'hrg_satuan'  => $hargaBaru,
            'harga_total' => $totalBaru
        ]);

        if (!$updated) {
            return FALSE;
        }

        if (!$this->update_pre_po_status_by_kd_po($row['kd_po'], 2)) {
            return FALSE;
        }

        return $this->db->insert('tb_pre_po_adjustment_log', [
            'kd_po'              => $row['kd_po'],
            'kd_barang'          => $row['kd_barang'],
            'harga_satuan_lama'  => $row['hrg_satuan'],
            'harga_satuan_baru'  => $hargaBaru,
            'harga_total_lama'   => $row['harga_total'],
            'harga_total_baru'   => $totalBaru,
            'alasan'             => $payload['alasan'],
            'dilakukan_oleh'     => $payload['dilakukan_oleh'],
            'dilakukan_pada'     => date('Y-m-d H:i:s')
        ]);
    }

    public function get_history_adjustment($kd_po, $kd_barang = '')
    {
        $this->db->from('tb_pre_po_adjustment_log');
        $this->db->where('kd_po', $kd_po);

        if ($kd_barang !== '') {
            $this->db->where('kd_barang', $kd_barang);
        }

        $this->db->order_by('dilakukan_pada', 'DESC');
        if ($this->db->field_exists('id', 'tb_pre_po_adjustment_log')) {
            $this->db->order_by('id', 'DESC');
        }

        return $this->db->get()->result_array();
    }

    public function get_history_invoice($kd_po)
    {
        $this->db->where('kd_po', $kd_po);
        $this->db->order_by('dilakukan_pada', 'DESC');
        if ($this->db->field_exists('id', 'tb_lpb_log')) {
            $this->db->order_by('id', 'DESC');
        }

        return $this->db->get('tb_lpb_log')->result_array();
    }

    public function get_history_diskon_sync($kd_po)
    {
        if (!$this->db->table_exists('tb_pre_po_diskon_history')) {
            return [];
        }

        $this->db->where('kd_po', $kd_po);
        $this->db->order_by('id_diskon_source', 'ASC');
        $this->db->order_by('id', 'ASC');

        return $this->db->get('tb_pre_po_diskon_history')->result_array();
    }

    public function update_invoice_lpb($payload)
    {
        $row = $this->db
            ->where('id_lpb', (int) $payload['id_lpb'])
            ->limit(1)
            ->get('tb_lpb')
            ->row_array();

        if (!$row) {
            return FALSE;
        }

        $updated = $this->db
            ->where('id_lpb', (int) $payload['id_lpb'])
            ->update('tb_lpb', [
                'no_invoice' => $payload['no_invoice'],
                'nosj'       => $payload['nosj'],
                'tgl_sj'     => $this->_normalizeDate($payload['tgl_sj']),
                'keterangan' => $payload['keterangan']
            ]);

        if (!$updated) {
            return FALSE;
        }

        $logged = $this->db->insert('tb_lpb_log', [
            'kd_po'          => $row['kd_po'],
            'no_invoice'     => $payload['no_invoice'],
            'action_type'    => 'UPDATE_INVOICE',
            'keterangan'     => $payload['keterangan'],
            'dilakukan_oleh' => $payload['dilakukan_oleh'],
            'dilakukan_pada' => date('Y-m-d H:i:s')
        ]);

        if (!$logged) {
            return FALSE;
        }

        return $this->update_pre_po_status_by_kd_po($row['kd_po'], 2);
    }

    private function _normalizeDate($raw)
    {
        $raw = trim((string)$raw);
        if ($raw === '') {
            return null;
        }
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw)) {
            return $raw;
        }
        if (preg_match('/^(\d{2})[\/\-](\d{2})[\/\-](\d{4})$/', $raw, $m)) {
            return $m[3] . '-' . $m[2] . '-' . $m[1];
        }
        return $raw;
    }

    public function create_lpb_from_tmp($header, $detailRows)
    {
        $headerInsert = [
            'kd_po'       => $header['kd_po'],
            'no_po'       => $header['no_po'],
            'nosj'        => $header['nosj'],
            'tgl_sj'      => $this->_normalizeDate($header['tgl_sj'] ?? ''),
            'no_invoice'  => $header['no_invoice'],
            'gudang_id'   => $header['gudang_id'],
            'keterangan'  => $header['keterangan'],
            'input_at'    => date('Y-m-d H:i:s')
        ];

        $this->db->insert('tb_lpb', $headerInsert);
        $idLpb = $this->db->insert_id();

        if (!$idLpb) {
            return FALSE;
        }

        foreach ($detailRows as $row) {
            $detailInsert = [
                'id_lpb'        => $idLpb,
                'kd_barang'     => $row['kd_barang'],
                'qty_diterima'  => $row['qty_diterima'],
                'no_lot'        => $row['no_lot'],
                'expired_date'  => $row['expired_date'],
                'input_at'      => date('Y-m-d H:i:s')
            ];

            $this->db->insert('tb_lpb_detail', $detailInsert);
            $idDetailLpb = $this->db->insert_id();

            if (!$idDetailLpb) {
                return FALSE;
            }

            $batchInsert = [
                'id_detail_lpb' => $idDetailLpb,
                'no_lot'        => $row['no_lot'],
                'expired_date'  => $row['expired_date'],
                'qty'           => $row['qty_diterima']
            ];

            $this->db->insert('tb_lpb_batch', $batchInsert);

            $stockQty = (float) $row['qty_diterima'];
            $normalizedExpired = $this->_normalizeDate($row['expired_date'] ?? '');
            $stockNoLot = trim((string) ($row['no_lot'] ?? ''));

            if ($this->db->table_exists('tberp_stock_batch')) {
                $this->db->where('kd_barang', $row['kd_barang']);
                $this->db->where('gudang_id', $header['gudang_id']);
                $this->db->where('no_lot', $stockNoLot);
                if ($normalizedExpired !== null) {
                    $this->db->where('expired_date', $normalizedExpired);
                } else {
                    $this->db->where('expired_date', null);
                }

                $existingStockBatch = $this->db->get('tberp_stock_batch')->row_array();

                if ($existingStockBatch) {
                    $this->db->where('id', $existingStockBatch['id']);
                    $this->db->set('qty_on_hand', 'qty_on_hand + ' . $stockQty, FALSE);
                    $this->db->set('update_at', date('Y-m-d H:i:s'));
                    $this->db->update('tberp_stock_batch');
                } else {
                    $this->db->insert('tberp_stock_batch', [
                        'kd_barang'    => $row['kd_barang'],
                        'gudang_id'    => $header['gudang_id'],
                        'no_lot'       => $stockNoLot,
                        'expired_date' => $normalizedExpired,
                        'qty_on_hand'  => $stockQty,
                        'qty_reserved' => 0,
                    ]);
                }
            }

            if ($this->db->table_exists('tberp_stock_ledger')) {
                $this->db->insert('tberp_stock_ledger', [
                    'kd_barang'    => $row['kd_barang'],
                    'gudang_id'    => $header['gudang_id'],
                    'no_lot'       => $stockNoLot,
                    'expired_date' => $normalizedExpired,
                    'qty'          => $stockQty,
                    'tipe'         => 'IN',
                    'ref_no'       => $header['kd_po'],
                    'ref_type'     => 'PO_RECEIVED',
                    'created_at'   => date('Y-m-d H:i:s'),
                ]);
            }
        }

        $this->db->where('kd_suplier', $header['kd_suplier']);
        $this->db->where_in('id_tmp_recieved', array_column($detailRows, 'id_tmp_recieved'));
        $this->db->delete('tb_tmp_po_received');

        return $idLpb;
    }

    public function get_riwayat_barang_masuk($date1 = null, $date2 = null)
    {
        $this->db->select('
            dl.id_detail_lpb,
            dl.no_po,
            dl.kd_po,
            dl.kd_barang,
            mb.nama_barang,
            pp.kd_suplier,
            s.nama_suplier,
            dl.qty_diterima,
            dl.satuan,
            dl.no_lot,
            dl.exp_date,
            dl.create_at
        ');
        $this->db->from('tb_po_received dl');
        $this->db->join('tb_master_barang_all mb', 'mb.kd_barang = dl.kd_barang', 'left');

        // Join ke tb_pre_po untuk dapat kd_suplier
        $this->db->join('tb_pre_po pp', 'pp.no_po = dl.no_po AND pp.kd_barang = dl.kd_barang', 'left');

        // Join ke tb_suplier untuk nama_suplier
        $this->db->join('tb_suplier s', 's.kd_suplier = pp.kd_suplier', 'left');

        if (!empty($date1) && !empty($date2)) {
            $this->db->where('DATE(dl.create_at) >=', $date1);
            $this->db->where('DATE(dl.create_at) <=', $date2);
        }

        $this->db->order_by('dl.id_detail_lpb', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_lpb_print_headers_by_kd_po($kd_po)
    {
        $sql = "SELECT
                h.id_lpb,
                h.kd_po,
                h.no_po,
                h.nosj,
                h.tgl_sj,
                h.no_invoice,
                h.keterangan,
                h.input_at,
                COUNT(DISTINCT d.kd_barang) AS total_item,
                COUNT(d.id_detail_lpb) AS total_baris,
                COALESCE(SUM(d.qty_diterima), 0) AS total_qty
            FROM tb_lpb h
            LEFT JOIN tb_lpb_detail d ON d.id_lpb = h.id_lpb
            WHERE h.kd_po = ?
            GROUP BY
                h.id_lpb,
                h.kd_po,
                h.no_po,
                h.nosj,
                h.tgl_sj,
                h.no_invoice,
                h.keterangan,
                h.input_at
            ORDER BY h.input_at DESC, h.id_lpb DESC";

        return $this->db->query($sql, [$kd_po])->result_array();
    }
}
