<?php
defined('BASEPATH') or exit('No direct script access allowed');


class M_Distribusi extends CI_Model
{

    public function tonase_all_do_done()
    {
        return $this->db->query("SELECT
            r.kd_rute AS rute,
            COALESCE(ROUND(SUM(CASE WHEN p.data_sts = '3' AND p.delivery_at <> '0000-00-00' THEN (b.berat * p.qty) ELSE 0 END ) / 1000000, 3), 0) AS tonase_terkirim,
            COALESCE(ROUND(SUM(CASE WHEN p.data_sts <> '3'THEN (b.berat * p.qty)ELSE 0 END) / 1000000, 3),0) AS tonase_belum_terkirim,
            COALESCE(ROUND(SUM(b.berat * p.qty) / 1000000, 3),0) AS total_tonase,
            COUNT(DISTINCT CASE WHEN p.data_sts = '3' AND p.delivery_at <> '0000-00-00' THEN p.kd_faktur END) AS total_faktur_terkirim,
            COUNT(DISTINCT CASE WHEN p.data_sts != '3' THEN p.kd_faktur END) AS total_faktur_pending,
            COUNT(DISTINCT p.kd_faktur) AS total_faktur
        FROM tb_rutecs r
        LEFT JOIN tb_pre_do p
            ON p.kd_rute = r.kd_rute
        LEFT JOIN tb_master_barang_all b
            ON b.kd_barang = p.kd_barang
        GROUP BY r.kd_rute
        ORDER BY tonase_terkirim DESC;
        ")->result();
    }

    public function total_kirim_do($tanggal = null, $ket_status = null)
    {
        $this->db->select("r.kd_rute AS rute,
            COUNT(DISTINCT CASE 
                WHEN p.data_sts = '3' 
                THEN p.kd_faktur 
            END) AS total_faktur_terkirim,
            COUNT(DISTINCT CASE 
                WHEN p.data_sts <> '3' 
                THEN p.kd_faktur 
            END) AS total_faktur_pending,
            COUNT(DISTINCT p.kd_faktur) AS total_faktur
        ", false);
        $this->db->from('tb_rutecs r');
        $this->db->join(
            'tb_pre_do p',
            "p.kd_rute = r.kd_rute 
            AND p.delivery_at IS NOT NULL 
            AND p.delivery_at <> '0000-00-00'",
            'left'
        );

        if (!empty($ket_status)) {
            $this->db->where('r.ket_status', $ket_status);
        }

        if (!empty($tanggal)) {
            $tgl = explode(' - ', $tanggal);
            if (count($tgl) === 2) {
                $this->db->where('p.delivery_at >=', $tgl[0] . ' 00:00:00');
                $this->db->where('p.delivery_at <=', $tgl[1] . ' 23:59:59');
            }
        }

        $this->db->group_by("r.kd_rute");
        $this->db->order_by('r.kd_rute', 'ASC');

        return $this->db->get()->result();
    }

    public function get_driver_productif($tanggal = null, $ket_status = null)
    {
        $this->db->select('kd_rute');
        $this->db->from('tb_rutecs');
        if (!empty($ket_status)) {
            $this->db->where('ket_status', $ket_status);
        }
        $this->db->order_by('kd_rute', 'ASC');
        $rute = $this->db->get()->result();

        $all_driver = $this->db->select('kd_driver, nama_driver')->from('tb_op_driver')->order_by('nama_driver', 'ASC')->get()->result();

        $this->db->select('
            o.driver AS kd_driver,
            d.nama_driver AS nama_driver,
            det.kd_rute AS kd_rute,
            COUNT(DISTINCT o.kd_do) AS total_do
        ', false);
        $this->db->from('tb_do o');
        $this->db->join('tb_detail_do det', 'det.kd_do = o.kd_do', 'inner');
        $this->db->join('tb_op_driver d', 'd.kd_driver = o.driver', 'inner');
        $this->db->join('tb_rutecs r', 'r.kd_rute = det.kd_rute', 'inner');
        $this->db->where('o.status', '2');
        if (!empty($ket_status)) {
            $this->db->where('r.ket_status', $ket_status);
        }

        if (!empty($tanggal)) {
            $tgl = explode(' - ', $tanggal);
            if (count($tgl) === 2) {
                $this->db->where('o.tgl_pengiriman >=', $tgl[0]);
                $this->db->where('o.tgl_pengiriman <=', $tgl[1]);
            } else {
                $this->db->where('o.tgl_pengiriman', $tanggal);
            }
        }

        $this->db->group_by(['o.driver', 'd.nama_driver', 'det.kd_rute']);
        $this->db->order_by('d.nama_driver', 'ASC');

        $rows = $this->db->get()->result();

        $map = [];
        foreach ($rows as $row) {
            $map[$row->kd_driver][$row->kd_rute] = (int) $row->total_do;
        }

        $data = [];
        foreach ($all_driver as $drv) {
            $kd_driver = $drv->kd_driver;
            $nama_driver = $drv->nama_driver;
            $total_kirim = 0;
            if (isset($map[$kd_driver])) {
                foreach ($map[$kd_driver] as $val) {
                    $total_kirim += (int) $val;
                }
            }
            $data[] = [
                'kd_driver' => $kd_driver,
                'nama_driver' => $nama_driver,
                'rute' => $map[$kd_driver] ?? [],
                'total_kirim' => $total_kirim
            ];
        }

        $rank = $data;
        usort($rank, function ($a, $b) {
            if ($a['total_kirim'] === $b['total_kirim']) {
                return strcmp($a['nama_driver'], $b['nama_driver']);
            }
            return $b['total_kirim'] <=> $a['total_kirim'];
        });

        $top = array_slice($rank, 0, 3);
        $bottom_rank = $rank;
        usort($bottom_rank, function ($a, $b) {
            if ($a['total_kirim'] === $b['total_kirim']) {
                return strcmp($a['nama_driver'], $b['nama_driver']);
            }
            return $a['total_kirim'] <=> $b['total_kirim'];
        });
        $bottom = array_slice($bottom_rank, 0, 3);

        return [
            'rute' => $rute,
            'data' => $data,
            'top' => $top,
            'bottom' => $bottom
        ];
    }

    public function persentase_faktur()
    {
        return $this->db->query("SELECT
            total_faktur,
            total_terkirim,
            total_belum,
            
            IF(total_faktur = 0, 0,
                ROUND((total_terkirim / total_faktur) * 100, 2)
            ) AS persen_terkirim,
            
            IF(total_faktur = 0, 0,
                ROUND((total_belum / total_faktur) * 100, 2)
            ) AS persen_belum_terkirim

        FROM (
            SELECT
                COUNT(DISTINCT kd_faktur) AS total_faktur,
                COUNT(DISTINCT CASE WHEN data_sts = '3' THEN kd_faktur END) AS total_terkirim,
                COUNT(DISTINCT CASE WHEN data_sts = '1' THEN kd_faktur END) AS total_belum
            FROM tb_pre_do
        ) x;")->result();
    }

    public function dashboard_faktur_summary($start, $end, $rute = null)
    {
        $start_dt = $this->db->escape($start . ' 00:00:00');
        $end_dt = $this->db->escape($end . ' 23:59:59');
        $start_date = $this->db->escape($start);
        $end_date = $this->db->escape($end);
        $rute_clause = '';
        if (!empty($rute)) {
            $rute_clause = ' AND kd_rute = ' . $this->db->escape($rute);
        }

        $sql = "
            SELECT
                COUNT(DISTINCT CASE 
                    WHEN data_sts = '3'
                        AND delivery_at IS NOT NULL
                        AND delivery_at <> '0000-00-00'
                        AND delivery_at BETWEEN {$start_dt} AND {$end_dt}
                        {$rute_clause}
                    THEN kd_faktur
                END) AS total_terkirim,
                COUNT(DISTINCT CASE 
                    WHEN data_sts = '1'
                        AND (
                            (delivery_at IS NOT NULL
                                AND delivery_at <> '0000-00-00'
                                AND delivery_at BETWEEN {$start_dt} AND {$end_dt})
                            OR (
                                (delivery_at IS NULL OR delivery_at = '0000-00-00')
                                AND STR_TO_DATE(tgl_inputer, '%e/%c/%Y') BETWEEN {$start_date} AND {$end_date}
                            )
                        )
                        {$rute_clause}
                    THEN kd_faktur
                END) AS total_pending
            FROM tb_pre_do
        ";

        return $this->db->query($sql)->row();
    }

    public function dashboard_faktur_series($start, $end, $rute = null)
    {
        $this->db->select([
            "DATE(delivery_at) AS tgl",
            "COUNT(DISTINCT kd_faktur) AS total_terkirim"
        ], false);
        $this->db->from('tb_pre_do');
        $this->db->where('data_sts', '3');
        $this->db->where('delivery_at IS NOT NULL', null, false);
        $this->db->where('delivery_at <>', '0000-00-00');
        $this->db->where('delivery_at >=', $start . ' 00:00:00');
        $this->db->where('delivery_at <=', $end . ' 23:59:59');
        if (!empty($rute)) {
            $this->db->where('kd_rute', $rute);
        }
        $this->db->group_by('DATE(delivery_at)');
        $this->db->order_by('DATE(delivery_at)', 'ASC');

        return $this->db->get()->result();
    }

    public function dashboard_driver_productif_top($start, $end, $limit = 5, $rute = null)
    {
        $this->db->select([
            'd.kd_driver',
            'd.nama_driver',
            'COUNT(DISTINCT o.kd_do) AS total_do'
        ], false);
        $this->db->from('tb_do o');
        $this->db->join('tb_op_driver d', 'd.kd_driver = o.driver', 'inner');
        if (!empty($rute)) {
            $this->db->join('tb_detail_do det', 'det.kd_do = o.kd_do', 'inner');
            $this->db->where('det.kd_rute', $rute);
        }
        $this->db->where('o.status', '2');
        $this->db->where('o.tgl_pengiriman >=', $start);
        $this->db->where('o.tgl_pengiriman <=', $end);
        $this->db->group_by(['d.kd_driver', 'd.nama_driver']);
        $this->db->order_by('total_do', 'DESC');
        $this->db->limit((int) $limit);

        return $this->db->get()->result();
    }

    public function dashboard_driver_productif_count($start, $end, $rute = null)
    {
        $this->db->select('COUNT(DISTINCT o.driver) AS total_driver', false);
        $this->db->from('tb_do o');
        if (!empty($rute)) {
            $this->db->join('tb_detail_do det', 'det.kd_do = o.kd_do', 'inner');
            $this->db->where('det.kd_rute', $rute);
        }
        $this->db->where('o.status', '2');
        $this->db->where('o.tgl_pengiriman >=', $start);
        $this->db->where('o.tgl_pengiriman <=', $end);

        return $this->db->get()->row();
    }

    public function dashboard_rute_rank($start, $end, $ket_status = null, $limit = 5, $order = 'DESC', $min_total = null, $exclude_zero = false)
    {
        $this->db->select([
            'r.kd_rute',
            'COUNT(DISTINCT p.kd_faktur) AS total_faktur'
        ], false);
        $this->db->from('tb_rutecs r');
        $this->db->join(
            'tb_pre_do p',
            "p.kd_rute = r.kd_rute 
            AND p.data_sts = '3' 
            AND p.delivery_at IS NOT NULL 
            AND p.delivery_at <> '0000-00-00'
            AND p.delivery_at >= " . $this->db->escape($start . ' 00:00:00') . "
            AND p.delivery_at <= " . $this->db->escape($end . ' 23:59:59'),
            'left'
        );

        if (!empty($ket_status)) {
            $this->db->where('r.ket_status', $ket_status);
        }

        $this->db->group_by('r.kd_rute');
        if ($min_total !== null) {
            $this->db->having('COUNT(DISTINCT p.kd_faktur) <', (int) $min_total, false);
            if ($exclude_zero) {
                $this->db->having('COUNT(DISTINCT p.kd_faktur) >', 0, false);
            }
        }
        $this->db->order_by('total_faktur', $order);
        $this->db->limit((int) $limit);

        return $this->db->get()->result();
    }
    public function total_tonase_by_rute()
    {
        return $this->db->query("SELECT
            r.kd_rute AS rute,
            COALESCE(ROUND(SUM(CASE WHEN p.data_sts = '3' AND p.delivery_at BETWEEN '2026-01-20' AND '2026-01-22' THEN (b.berat * p.qty) ELSE 0 END ) / 1000000, 3),0) AS tonase_terkirim,
            COALESCE(ROUND(SUM(CASE WHEN p.data_sts <> '3' AND p.delivery_at BETWEEN '2026-01-20' AND '2026-01-22' THEN (b.berat * p.qty) ELSE 0 END) / 1000000, 3),0) AS tonase_belum_terkirim,
            COALESCE(ROUND(SUM(CASE WHEN p.delivery_at BETWEEN '2026-01-20' AND '2026-01-22' THEN (b.berat * p.qty) ELSE 0 END) / 1000000, 3),0) AS total_tonase,
            COUNT(DISTINCT CASE WHEN p.data_sts = '3' AND p.delivery_at BETWEEN '2026-01-20' AND '2026-01-22' THEN p.kd_faktur END) AS total_faktur_terkirim,
            COUNT(DISTINCT CASE WHEN p.data_sts <> '3' AND p.delivery_at BETWEEN '2026-01-20' AND '2026-01-22' THEN p.kd_faktur END) AS total_faktur_pending,
            COUNT(DISTINCT CASE WHEN p.delivery_at BETWEEN '2026-01-20' AND '2026-01-22' THEN p.kd_faktur END) AS total_faktur
        FROM tb_rutecs r
        LEFT JOIN tb_pre_do p
            ON p.kd_rute = r.kd_rute
        LEFT JOIN tb_master_barang_all b
            ON b.kd_barang = p.kd_barang
        GROUP BY r.kd_rute
        ORDER BY tonase_terkirim DESC;")->result();
    }

    public function get_driver_rute_matrix($tanggal)
    {
        $rute   = $this->db->get('tb_rutecs')->result();
        $driver = $this->db->get('tb_op_driver')->result();

        $this->db->select('
        a.driver AS driver,
        b.kd_rute AS rute,
        COUNT(DISTINCT a.kd_do) AS total
        ');

        $this->db->from('tb_do a');
        $this->db->join('tb_detail_do b', 'b.kd_do = a.kd_do');
        $this->db->where('a.status !=', '1');
        $this->db->where('b.status !=', '1');
        $this->db->where('a.regional !=', 'onsite');

        if (!empty($tanggal)) {
            $tgl = explode(' - ', $tanggal);
            $this->db->where('a.tgl_pengiriman >=', $tgl[0]);
            $this->db->where('a.tgl_pengiriman <=', $tgl[1]);
        }

        $this->db->group_by(['a.driver', 'b.kd_rute']);
        $do = $this->db->get()->result();

        $map = [];
        foreach ($do as $d) {
            $map[$d->driver][$d->rute] = (int)$d->total;
        }

        $data = [];
        foreach ($driver as $drv) {
            $row = [
                'kd_driver'   => $drv->kd_driver,
                'nama_driver' => $drv->nama_driver,
                'rute'        => $map[$drv->kd_driver] ?? []
            ];
            $data[] = $row;
        }

        return [
            'rute' => $rute,
            'data' => $data
        ];
    }


    public function get_driver_ready($tanggal, $rute)
    {
        $tgl = explode(' - ', $tanggal);

        // subquery: driver yang SUDAH dipakai
        $sub = $this->db->select('driver')
            ->from('tb_do')
            ->where('status', '2')
            ->where('regional', $rute)
            ->where('tgl_pengiriman >=', $tgl[0])
            ->where('tgl_pengiriman <=', $tgl[1])
            ->group_by('driver')
            ->get_compiled_select();

        // main query: driver yang BELUM ada di subquery
        $this->db->select('kd_driver, nama_driver');
        $this->db->from('tb_op_driver');
        $this->db->where("kd_driver NOT IN ($sub)", null, false);
        $this->db->order_by('nama_driver', 'ASC');

        return $this->db->get()->result();
    }

    public function all_driver()
    {
        return $this->db->query("SELECT * 
        FROM `tb_op_driver`
        ")->result();
    }

    public function all_rute()
    {
        return $this->db->query("SELECT * FROM `tb_rutecs`")->result();
    }

    public function ploting_rute($rute, $tanggal)
    {
        $this->db->select("
        d.kd_driver,
        d.nama_driver AS nama,
        COALESCE(do.tgl_pengiriman, 'BELUM ADA') AS tanggal_pengiriman
    ", false);

        $this->db->from('tb_op_driver d');
        $join = '
        do.driver = d.kd_driver
        AND do.status = "2"
        AND do.regional = ' . $this->db->escape($rute);

        if (!empty($tanggal)) {
            $tgl = explode(' - ', $tanggal);
            $join .= ' AND do.tgl_pengiriman BETWEEN '
                . $this->db->escape($tgl[0])
                . ' AND '
                . $this->db->escape($tgl[1]);
        }

        $this->db->join('tb_do do', $join, 'left');

        $this->db->order_by('d.nama_driver', 'ASC');

        return $this->db->get()->result();
    }

    public function detail_faktur($kdrute)
    {
        return $this->db->query("SELECT
        a.*
        FROM tb_pre_do a
        WHERE a.kd_rute = '$kdrute'
        GROUP BY kd_faktur
        ")->result();
    }

    public function driver_histori($rute)
    {
        return $this->db->query("SELECT 
            d.kd_driver,
            d.nama_driver AS nama,
            COALESCE(do.tgl_pengiriman, 'BELUM ADA') AS tanggal_pengiriman
        FROM tb_op_driver d
        LEFT JOIN tb_do do 
            ON do.driver = d.kd_driver
            AND do.regional = '$rute'
            AND do.status = '2'
            AND YEARWEEK(do.tgl_pengiriman, 1) = YEARWEEK(CURDATE(), 1)
        ORDER BY d.nama_driver;")->result();
    }

    public function tmplate()
    {
        return $this->db->query("")->result();
    }

    public function get_list_do_by_status($status)
    {
        $this->db->select([
            'c.kd_do AS kode_do',
            'a.kd_faktur',
            'a.kd_rute',
            'a.kd_customer',
            'a.nama_barang',
            'a.qty',
            'a.tgl_exp',
            'b.nama_kios',
            "DATE_FORMAT(
            STR_TO_DATE(a.tgl_inputer, '%e/%c/%Y'),
            '%d/%m/%Y'
        ) AS tgl_inputer_fmt"
        ], false);

        $this->db->from('tb_pre_do a');

        $this->db->join(
            'tb_customer b',
            'b.kd_customer = a.kd_customer',
            'left'
        );

        if ($status == 3) {
            $this->db->join(
                'tb_ics_do c',
                'c.kd_faktur = a.kd_faktur 
             AND c.kd_do IS NOT NULL 
             AND c.kd_do != ""',
                'inner'
            );
        } else {
            $this->db->join(
                'tb_ics_do c',
                'c.kd_faktur = a.kd_faktur',
                'left'
            );
        }

        $this->db->where('a.data_sts', $status);

        $this->db->group_by([
            'a.kd_faktur',
            'a.kd_rute',
            'a.kd_customer',
            'a.kd_barang',
            'a.tgl_exp',
            'a.no_lot'
        ]);

        $this->db->order_by(
            "STR_TO_DATE(a.tgl_inputer, '%e/%c/%Y')",
            'DESC',
            false
        );

        $this->db->order_by('a.kd_faktur', 'DESC');

        return $this->db->get()->result();
    }


    public function total_tonase()
    {
        return $this->db->query("SELECT 
            ROUND(SUM(b.berat * d.qty) / 1000000, 3) AS total_tonase
        FROM tb_detail_do d
        JOIN tb_master_barang_all b 
            ON b.kd_barang = d.kd_barang
        JOIN tb_do o 
            ON o.kd_do = d.kd_do
        WHERE o.tgl_pengiriman BETWEEN '2026-01-01' AND '2026-01-31' AND o.status = '2'")->result();
    }

    public function tonase_terkirim()
    {
        return $this->db->query("SELECT 
            ROUND(SUM(b.berat * d.qty) / 1000000, 3) AS tonase_terkirim
        FROM tb_detail_do d
        JOIN tb_master_barang_all b 
            ON b.kd_barang = d.kd_barang
        JOIN tb_do o 
            ON o.kd_do = d.kd_do
        WHERE d.status = '4'
        AND o.tgl_pengiriman BETWEEN '2026-01-01' AND '2026-01-31' AND o.status = '2';
        ")->result();
    }

    public function detail_tonase_rute($rute)
    {
        return $this->db->query("SELECT
        a.kd_faktur,
        a.kd_customer,
        COUNT(a.kd_barang) AS total_barang,
        COALESCE(ROUND(SUM(b.berat * a.qty) / 1000000, 3), 0) AS total_tonase,
        CASE 
            WHEN a.data_sts = '3' THEN 'Terkirim'
            WHEN a.data_sts = '1' THEN 'Belum Terkirim'
            ELSE 'Status Tidak Dikenal'
        END AS keterangan_status
    FROM tb_pre_do a
    LEFT JOIN tb_master_barang_all b 
        ON a.kd_barang = b.kd_barang
    WHERE a.kd_rute = '$rute'
    GROUP BY 
        a.kd_faktur,
        a.kd_customer,
        a.data_sts;
        ")->result();
    }
}
