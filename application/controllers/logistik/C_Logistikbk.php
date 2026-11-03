<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Logistik extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Logistik');
        $this->load->model('M_Keuangan');
        $this->load->helper('stock_helper');
        $this->load->helper('login_auth');
        is_logged_in();
    }

    public function index()
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['dataplat']   = $this->M_Logistik->getallplat();
        $data['driver']    = $this->M_Logistik->getAllDriver();
        $data['driverurut']   = $this->M_Logistik->getnorutdriveractive();
        $data['helper']    = $this->M_Logistik->getAllHelper();
        $data['kd_driver']  = $this->M_Logistik->kd_driver();
        $data['kd_helper']  = $this->M_Logistik->kd_helper();


        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/bodylogistik.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ajaxlogistik');
    }


    public function addplat()
    {
        $kdplat = $this->input->post('kd_plat_isi');
        $noplat = $this->input->post('plat_isi');

        $dataplat = array(
            'noplat'    => $noplat,
            'nm_truk'   => $kdplat
        );
        $this->M_Logistik->addnoplatbaru($dataplat);
        redirect('truckoprational');
    }
    public function editplat()
    {
        $id     = $this->input->post('id_isi');
        $kdplat = $this->input->post('kd_plat_isi');
        $noplat = $this->input->post('plat_isi');

        $dataplat = array(
            'noplat'    => $noplat,
            'nm_truk'   => $kdplat
        );
        $this->M_Logistik->editnoplat($id, $dataplat);
        redirect('truckoprational');
    }
    public function hapusplat()
    {
        $id     = $this->input->post('id_isi');

        $this->M_Logistik->deletenoplat($id);
        redirect('truckoprational');
    }
    public function addriver()
    {
        $kd_driver  = $this->input->post('kd_driver');
        $nmdriver   = $this->input->post('driver_isi');
        $stsisi     = $this->input->post('sts_select');

        $dataplat = array(
            'kd_driver' => $kd_driver,
            'nama_driver'   => $nmdriver,
            'status'   => $stsisi
        );
        $this->M_Logistik->adddriverbaru($dataplat);
        redirect('truckoprational');
    }
    public function editdriver()
    {
        $kd_driver  = $this->input->post('kd_driver');
        $nmdriver   = $this->input->post('driver_isi');

        $dataplat = array(
            'kd_driver' => $kd_driver,
            'nama_driver'   => $nmdriver,
        );
        $this->M_Logistik->editdriver($kd_driver, $dataplat);
        redirect('truckoprational');
    }
    public function activedrver($kd_driver)
    {
        $dataupdate = array(
            'status' => 'ACTIVE'
        );
        $this->M_Logistik->editdriver($kd_driver, $dataupdate);
        redirect('truckoprational');
    }
    public function nonactivedriver($kd_driver)
    {
        $dataupdate = array(
            'status' => 'NON-ACTIVE'
        );
        $this->M_Logistik->editdriver($kd_driver, $dataupdate);
        redirect('truckoprational');
    }
    public function hapusdriver()
    {
        $id     = $this->input->post('id_isi');

        $this->M_Logistik->hapusdriver($id);
        redirect('truckoprational');
    }
    public function tambahpenggunadriver()
    {
    }
    public function deleveriorder()
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['deliveri']   = $this->M_Logistik->get_deliv_logistik()->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/orderdriver.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ajaxorderdriver');
    }
    public function tambahorderdriver()
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['driver'] = $this->M_Logistik->get_all_driver();
        $data['helper'] = $this->M_Logistik->select_kd_helper();
        $data['kdorder'] = $this->M_Logistik->get_kd_order();
        $data['kdtruk'] = $this->M_Logistik->select_kd_truk();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/tambahorderdriver.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ajaxorderdriver');
    }
    public function editdeliv($kd)
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['deliv'] = $this->M_Logistik->get_deliv($kd);
        $data['kdtruk'] = $this->M_Logistik->select_kd_truk();
        $data['driver'] = $this->M_Logistik->get_all_driver();
        $data['helper'] = $this->M_Logistik->select_kd_helper();
        $data['status'] = $this->M_Logistik->detail_deliv($kd);

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/editorderdriver.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ajaxorderdriver');
    }
    // public function addorderdeliv1()
    // {
    //     $kdorder    = $this->input->post('kd_order_i');
    //     $tglorder   = $this->input->post('tgl_deliv_i');
    //     $nmdriver   = $this->input->post('nm_driver_i');
    //     $kdtruk     = $this->input->post('kd_truk_i');
    //     $destinasi  = $this->input->post('destinsasi_i');
    //     $nmtoko     = $this->input->post('nm_toko_i');
    //     if (!empty($kdorder) && !empty($tglorder) && !empty($nmdriver) && !empty($kdtruk) && !empty($destinasi) && !empty($nmtoko)) {
    //         foreach ($kdorder as $key => $value) {
    //             $this->db->insert('tb_log_tracking', $value);
    //         }
    //     }
    //     redirect('deliveriorder');
    // }

    public function addorderdeliv()
    {
        $kdorder    = $this->input->post('kd_order_i');
        $tglorder   = $this->input->post('tgl_deliv_i');

        $dataOrder  = array(
            'kd_order'  => $kdorder,
            'tgl_jalan' => $tglorder,
        );

        $jumlah = count($this->input->post('nm_driver_i[]'));
        for ($i = 0; $i < $jumlah; $i++) {
            $data = array(
                'norut' => $this->input->post('no_urut_i')[$i],
                'kd_deliveri' => $this->input->post('kd_order_i'),
                'tgl_jalan' => $this->input->post('tgl_deliv_i'),
                'kd_driver' => $this->input->post('kd_driver_i')[$i],
                'kd_helper' => $this->input->post('helper_i')[$i],
                'kd_truk' => $this->input->post('kd_truk_i')[$i],
                'destinasi' => $this->input->post('destinsasi_i')[$i],
                'jml_kios' => $this->input->post('jml_kios')[$i],
                'tonase' => $this->input->post('tonase_i')[$i],
                'kubikasi' => $this->input->post('kubikasi_i')[$i],
                'sts_driver' => $this->input->post('sts_driver[]')[$i],
                'keterangan' => $this->input->post('keterangan_i[]')[$i]
            );

            $datatmp = array(
                'kd_deliveri' => $this->input->post('kd_order_i'),
                'tgl_jalan' => $this->input->post('tgl_deliv_i'),
                'kd_driver' => $this->input->post('kd_driver_i')[$i],
                'kd_helper' => $this->input->post('helper_i')[$i],
                'kd_truk' => $this->input->post('kd_truk_i')[$i],
                'destinasi' => $this->input->post('destinsasi_i')[$i],
                'status' => $this->input->post('sts_driver[]')[$i],
            );
            $this->M_Logistik->insert_tmp_lap_distribusi($datatmp);
            $this->M_Logistik->insert_detail_order_driver($data);
        }
        $this->M_Logistik->insert_deliveri_order($dataOrder);
        redirect('deliveriorder');
    }
    public function editorderdeliver()
    {
        $kdorder    = $this->input->post('kd_order_i');
        $tglorder   = $this->input->post('tgl_deliv_i');

        $dataOrder  = array(
            'kd_order'  => $kdorder,
            'tgl_jalan' => $tglorder,
        );

        $jumlah = count($this->input->post('nm_driver_i[]'));
        for ($i = 0; $i < $jumlah; $i++) {
            $data = array(
                'norut' => $this->input->post('no_urut_i')[$i],
                'kd_deliveri' => $this->input->post('kd_order_i'),
                'tgl_jalan' => $this->input->post('tgl_deliv_i'),
                'kd_driver' => $this->input->post('kd_driver_i')[$i],
                'kd_helper' => $this->input->post('helper_i')[$i],
                'kd_truk' => $this->input->post('kd_truk_i')[$i],
                'destinasi' => $this->input->post('destinsasi_i')[$i],
                'jml_kios' => $this->input->post('jml_kios')[$i],
                'tonase' => $this->input->post('tonase_i')[$i],
                'kubikasi' => $this->input->post('kubikasi_i')[$i],
                'sts_driver' => $this->input->post('sts_driver[]')[$i],
                'keterangan' => $this->input->post('keterangan_i[]')[$i]
            );
            $this->M_Logistik->insert_detail_order_driver($data);
        }
        $this->M_Logistik->insert_deliveri_order($dataOrder);
        redirect('deliveriorder');
    }

    function select_kd_truk()
    {
        $nm_truk = $this->input->post('nm_truk[]');
        $data = $this->M_Logistik->select_kd_truk($nm_truk);

        echo json_encode($data);
    }

    // public function addorderdeliv()
    // {
    //     $kdorder    = $this->input->post('kd_order_i');
    //     $tglorder   = $this->input->post('tgl_deliv_i');

    //     $dataOrder  = array(
    //         'kd_order'  => $kdorder,
    //         'tgl_jalan' => $tglorder,
    //     );

    //     $jumlah = count($this->input->post('nm_driver_i'));
    //     for ($i = 0; $i < $jumlah; $i++) {
    //         $data = array(
    //             'kd_deliveri' => $this->input->post('kd_order_i'),
    //             'tgl_jalan' => $this->input->post('tgl_deliv_i'),
    //             'kd_driver' => $this->input->post('nm_driver_i')[$i],
    //             'kd_truk' => $this->input->post('kd_truk_i')[$i],
    //             'destinasi' => $this->input->post('destinsasi_i')[$i],
    //             'nm_toko' => $this->input->post('nm_toko_i')[$i]
    //         );
    //         $this->M_Logistik->insert_detail_order_driver($data);
    //     }
    //     $this->M_Logistik->insert_deliveri_order($dataOrder);
    //     redirect('deliveriorder');
    // }

    function select2driver()
    {
        $kduser = $this->input->post('nm_driver_i')[''];
        $data   = $this->M_Logistik->get_driver($kduser);
        echo json_encode($data);
    }

    public function det_deliveri($kd_deliveri)
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['kd'] = $kd_deliveri;
        $data['order_deliv'] = $this->M_Logistik->get_order($kd_deliveri);
        $data['detail']   = $this->M_Logistik->get_det_deliv($kd_deliveri)->result();
        $data['helper'] = $this->M_Logistik->select_kd_helper();
        $data['kdtruk'] = $this->M_Logistik->select_kd_truk();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/detailorder.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ajaxlogistik.php');
        $this->load->view('content/logistik/modaldetaildriverorder.php');
    }
    public function add_pending_driver()
    {
        $id    = $this->input->post('id_isi');
        $pending    = $this->input->post('pnd_isi');

        $result = array();
        foreach ($_POST['kd_deliv_isi'] as $i => $val) {
            $result[] = array(
                'kd_deliveri' => $this->input->post('kd_deliv_isi')[$i],
                'tgl_jalan' => $this->input->post('tgl_isi')[$i],
                'kd_driver' => $this->input->post('driver_isi')[$i],
                'kd_truk' => $this->input->post('truk_isi')[$i],
                'destinasi' => $this->input->post('destinasi_isi')[$i],
                'nm_toko' => $this->input->post('tko_isi')[$i],
                'note_pending' => $pending
            );
        }
        $this->db->insert_batch('tb_driver_pending', $result);
        $this->db->where_in('id', $id);
        $this->db->delete('tb_det_tracking_driver');

        redirect('deliveriorder');
    }
    public function det_driver($kdorder, $kddriver)
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['kd'] = $this->M_Logistik->get_kd($kdorder)->result();
        $data['detail']   = $this->M_Logistik->get_det_jalan_driver($kdorder, $kddriver)->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/detaildriver.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ajaxlogistik.php');
        $this->load->view('content/logistik/modaldetaildriverorder.php');
    }
    public function driver_pending()
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['driver'] = $this->M_Logistik->get_pnd_driver()->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/driverpending.php', $data);
        $this->load->view('partial/main/footer.php');
    }
    public function det_pnd_driver($kdorder, $kddriver)
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['kd']         = $this->M_Logistik->get_kd_det_pnd($kdorder)->result();
        $data['detail']     = $this->M_Logistik->get_det_driver_pnd($kdorder, $kddriver)->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/det_pnd_driver.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ajaxlogistik.php');
        $this->load->view('content/logistik/modaldetaildriverorder.php');
    }
    public function tracking_driver()
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['driver'] = $this->M_Logistik->get_data_driver()->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/driver_tracking.php', $data);
        $this->load->view('partial/main/footer.php');
    }
    public function detail_tracking_driver($kd)
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['driver'] = $this->M_Logistik->get_det_data_driver($kd)->result();
        $data['detail'] = $this->M_Logistik->get_det_tracking($kd)->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/det_tracking_driver.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function export_tracking_driver()
    {
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        $excel = new PHPExcel();
        $excel->getProperties()->setCreator('it_karisma')
            ->setLastModifiedBy('tracking_driver_')
            ->setTitle("Tracking All Driver")
            ->setSubject("Tracking All Driver")
            ->setDescription("Tracking All Driver")
            ->setKeywords("Tracking Driver");

        $style_col = array(
            'font' => array('bold' => true),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
            )
        );

        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "Tracking Driver");
        $excel->getActiveSheet()->mergeCells('A1:J1');
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "KODE DELIVERI");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "TANGGAL JALAN");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "NO JALAN");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "NAMA DRIVER");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "KODE TRUK");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "NOMOR PLAT");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "STATUS DRIVER");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "DESTINASI");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "KETERANGAN");

        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);

        $tracking = $this->M_Logistik->export_tracking()->result();

        $no = 1;
        $numrow = 4;
        foreach ($tracking as $data) {
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data->kd_deliveri);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, format_indo($data->tgl_jalan));
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data->norut);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $data->nama_driver);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data->kd_truk);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $data->noplat);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $data->sts_driver);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, $data->destinasi);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow, $data->keterangan);
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J' . $numrow)->applyFromArray($style_row);
            $no++;
            $numrow++;
        }

        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(11);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $excel->getActiveSheet(0)->setTitle("Rekap Tracking Driver ");
        $excel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="rekap_tracking_driver.xlsx"');
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
    public function export_lap_distribusi()
    {
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        $excel = new PHPExcel();
        $excel->getProperties()->setCreator('it_karisma')
            ->setLastModifiedBy('lap_distribusi_')
            ->setTitle("Laporan Keluar Masuk Distribusi")
            ->setSubject("Laporan Distribusi Logistik")
            ->setDescription("Laporan Keluar Masuk Distribusi Logistik")
            ->setKeywords("Laporan Distribusi");

        $style_col = array(
            'font' => array('bold' => true),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
            )
        );

        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "Laporan Distribusi");
        $excel->getActiveSheet()->mergeCells('A1:M1');
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "NOPOL");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "NOMOR LAMBUNG");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "NAMA DRIVER");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "NAMA HELPER");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "TUJUAN");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "TGL KELUAR");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "JAM KELUAR");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "KM KELUAR");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "TGL MASUK");
        $excel->setActiveSheetIndex(0)->setCellValue('K3', "JAM MASUK");
        $excel->setActiveSheetIndex(0)->setCellValue('L3', "KM MASUK");
        $excel->setActiveSheetIndex(0)->setCellValue('M3', "KETERANGAN");

        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M3')->applyFromArray($style_col);

        $export = $this->M_Logistik->export_lap_distribusi();

        $no = 1;
        $numrow = 4;
        foreach ($export as $data) {
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data->nopol);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data->nolambung);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data->namadriver);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $data->namahelper);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data->tujuan);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $data->tglkeluar);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $data->jamkeluar);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, $data->kmkeluar);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow, $data->tglmasuk);
            $excel->setActiveSheetIndex(0)->setCellValue('K' . $numrow, $data->jammasuk);
            $excel->setActiveSheetIndex(0)->setCellValue('L' . $numrow, $data->kmmasuk);
            $excel->setActiveSheetIndex(0)->setCellValue('M' . $numrow, $data->keterangan);
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('K' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('L' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('M' . $numrow)->applyFromArray($style_row);
            $no++;
            $numrow++;
        }

        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $excel->getActiveSheet(0)->setTitle("Laporan Keluar Masuk Kendaraan ");
        $excel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="laporan_keluar_masuk_kendaraan.xlsx"');
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }
    public function editnorut()
    {
        $id    = $this->input->post('id_isi');
        $no    = $this->input->post('norut');

        $result = array();
        foreach ($_POST['id_isi'] as $i => $val) {
            $result[] = array(
                'id' => $this->input->post('id_isi')[$i],
                'no_urut_hr_i' => $this->input->post('norut')[$i],
            );
        }
        $this->db->update_batch('tb_op_driver', $result, 'id');

        redirect('truckoprational');
    }
    public function tambahhelper()
    {
        $kd_driver  = $this->input->post('kd_driver');
        $nmdriver   = $this->input->post('driver_isi');
        $stsisi     = $this->input->post('sts_select');

        $dataplat = array(
            'kd_helper' => $kd_driver,
            'nama_helper'   => $nmdriver,
            'status'   => $stsisi
        );
        $this->M_Logistik->addhelperbaru($dataplat);
        redirect('truckoprational');
    }
    public function edithelper()
    {
        $kd_driver  = $this->input->post('kd_driver');
        $nmdriver   = $this->input->post('driver_isi');

        $dataplat = array(
            'kd_helper' => $kd_driver,
            'nama_helper'   => $nmdriver,
        );
        $this->M_Logistik->edithelper($kd_driver, $dataplat);
        redirect('truckoprational');
    }
    public function activehelper($kd_driver)
    {
        $dataupdate = array(
            'status' => 'ACTIVE'
        );
        $this->M_Logistik->edithelper($kd_driver, $dataupdate);
        redirect('truckoprational');
    }
    public function nonactivehelper($kd_driver)
    {
        $dataupdate = array(
            'status' => 'NON-ACTIVE'
        );
        $this->M_Logistik->edithelper($kd_driver, $dataupdate);
        redirect('truckoprational');
    }
    public function hapushelper()
    {
        $id     = $this->input->post('id_isi');

        $this->M_Logistik->hapushelper($id);
        redirect('truckoprational');
    }
    public function editdetaildriver()
    {
        $kd         = $this->input->post('kdorder');
        $id         = $this->input->post('id_i');
        $norut      = $this->input->post('no_jalan_i');
        $kdriver    = $this->input->post('kddriver');
        $kdhelper   = $this->input->post('helper_i');
        $kdtruk     = $this->input->post('kd_truk_i');
        $destinasi  = $this->input->post('destinasi_i');
        $jmlkios    = $this->input->post('jml_kios_i');
        $tonase     = $this->input->post('tonase_i');
        $kubikasi   = $this->input->post('kubikasi_i');
        $stsdriver  = $this->input->post('sts_isi');
        $keterangan = $this->input->post('keterangan_i');

        $data = array(
            'norut'     => $norut,
            'kd_driver' => $kdriver,
            'kd_helper' => $kdhelper,
            'kd_truk'   => $kdtruk,
            'destinasi' => $destinasi,
            'jml_kios'  => $jmlkios,
            'tonase'    => $tonase,
            'kubikasi'  => $kubikasi,
            'sts_driver' => $stsdriver,
            'keterangan' => $keterangan
        );
        $this->M_Logistik->edit_detail_order_driver($id, $data);
        redirect('detail_deliveri/' . $kd);
    }
    public function hapus_detail_order()
    {
        $kd = $this->input->post('id_isi');

        $this->M_Logistik->deletedetailorder($kd);
        $this->M_Logistik->deleteorder($kd);
        redirect('deliveriorder');
    }
    public function export_rekap_laporan_driver()
    {
        include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
        $excel = new PHPExcel();
        $excel->getProperties()->setCreator('it_karisma')
            ->setLastModifiedBy('rekap_driver_order_')
            ->setTitle("Rekap Laporan Driver Order")
            ->setSubject("Rekap Laporan Driver Order")
            ->setDescription("Rekap Laporan DO")
            ->setKeywords("Rekap DO");

        $style_col = array(
            'font' => array('bold' => true),
            'alignment' => array(
                'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
            )
        );

        $style_row = array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
            ),
            'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
            )
        );

        $excel->setActiveSheetIndex(0)->setCellValue('A1', "Rekap Laporan Driver Order");
        $excel->getActiveSheet()->mergeCells('A1:J1');
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
        $excel->setActiveSheetIndex(0)->setCellValue('B3', "KODE DELIVERI");
        $excel->setActiveSheetIndex(0)->setCellValue('C3', "TANGGAL JALAN");
        $excel->setActiveSheetIndex(0)->setCellValue('D3', "NO JALAN");
        $excel->setActiveSheetIndex(0)->setCellValue('E3', "NAMA DRIVER");
        $excel->setActiveSheetIndex(0)->setCellValue('F3', "NAMA HELPER");
        $excel->setActiveSheetIndex(0)->setCellValue('G3', "KODE TRUK");
        $excel->setActiveSheetIndex(0)->setCellValue('H3', "NOMOR PLAT");
        $excel->setActiveSheetIndex(0)->setCellValue('I3', "DESTINASI");
        $excel->setActiveSheetIndex(0)->setCellValue('J3', "JUMLAH KIOS");
        $excel->setActiveSheetIndex(0)->setCellValue('K3', "TONASE");
        $excel->setActiveSheetIndex(0)->setCellValue('L3', "KUBIKASI");
        $excel->setActiveSheetIndex(0)->setCellValue('M3', "STATUS DRIVER");
        $excel->setActiveSheetIndex(0)->setCellValue('N3', "KETERANGAN");

        $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('J3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('K3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('L3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('M3')->applyFromArray($style_col);
        $excel->getActiveSheet()->getStyle('N3')->applyFromArray($style_col);

        $tracking = $this->M_Logistik->export_tracking()->result();

        $no = 1;
        $numrow = 4;
        foreach ($tracking as $data) {
            $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
            $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data->kd_deliveri);
            $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data->tgl_jalan);
            $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data->norut);
            $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $data->nama_driver);
            $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data->nama_helper);
            $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $data->kd_truk);
            $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $data->noplat);
            $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, $data->destinasi);
            $excel->setActiveSheetIndex(0)->setCellValue('J' . $numrow, $data->jml_kios);
            $excel->setActiveSheetIndex(0)->setCellValue('K' . $numrow, $data->tonase);
            $excel->setActiveSheetIndex(0)->setCellValue('L' . $numrow, $data->kubikasi);
            $excel->setActiveSheetIndex(0)->setCellValue('M' . $numrow, $data->sts_driver);
            $excel->setActiveSheetIndex(0)->setCellValue('N' . $numrow, $data->keterangan);
            $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('H' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('I' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('J' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('K' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('L' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('M' . $numrow)->applyFromArray($style_row);
            $excel->getActiveSheet()->getStyle('N' . $numrow)->applyFromArray($style_row);
            $no++;
            $numrow++;
        }

        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(4);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
        $excel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
        $excel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $excel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
        $excel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
        $excel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
        $excel->getActiveSheet()->getColumnDimension('H')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
        $excel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
        $excel->getActiveSheet()->getColumnDimension('K')->setWidth(8);
        $excel->getActiveSheet()->getColumnDimension('L')->setWidth(9);
        $excel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
        $excel->getActiveSheet()->getColumnDimension('N')->setWidth(55);
        $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $excel->getActiveSheet(0)->setTitle("Rekap Driver Order");
        $excel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="rekap_driver_order.xlsx"');
        header('Cache-Control: max-age=0');

        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $write->save('php://output');
    }

    public function tmp_lap_distribusi()
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['data'] = $this->M_Logistik->get_tmp_distribusi()->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/tmp_lap_distribusi', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ajaxlogistik');
    }
    public function edit_laporan_tmp_dis()
    {
        $id         = $this->input->post('id_isi');
        $tglmasuk   = $this->input->post('tgl_masuk_i');
        $jmin       = $this->input->post('jm_masuk');
        $kmin       = $this->input->post('km_masuk');
        $tglout     = $this->input->post('tgl_keluar');
        $jmout      = $this->input->post('jm_keluar');
        $kmout      = $this->input->post('km_keluar');

        $datatmp = array(
            'tgl_masuk' => $tglmasuk,
            'jm_masuk'  => $jmin,
            'km_masuk'  => $kmin,
            'tgl_keluar' => $tglout,
            'jm_keluar' => $jmout,
            'km_keluar' => $kmout
        );
        $this->M_Logistik->edited_tmp_lap_dis($id, $datatmp);
        redirect('tmp_logistik_distribusi');
    }
    public function insert_laporan_tmp_dis()
    {
        $id         = $this->input->post('id_isi');
        $nmdriver   = $this->input->post('driver_isi');
        $nmhelper   = $this->input->post('helper_isi');
        $nolambung  = $this->input->post('nmlambung');
        $noplat     = $this->input->post('plat_isi');
        $destinasi  = $this->input->post('destinasi_i');
        $tglmasuk   = $this->input->post('tgl_masuk_i');
        $jmin       = $this->input->post('jm_masuk');
        $kmin       = $this->input->post('km_masuk');
        $tglout     = $this->input->post('tgl_keluar');
        $jmout      = $this->input->post('jm_keluar');
        $kmout      = $this->input->post('km_keluar');
        $ket        = $this->input->post('keterangan');

        $datatmp = array(
            'nopol'      => $noplat,
            'nolambung'  => $nolambung,
            'namadriver' => $nmdriver,
            'namahelper' => $nmhelper,
            'tujuan'     => $destinasi,
            'tglkeluar' => $tglout,
            'jamkeluar'  => $jmout,
            'kmkeluar' => $kmout,
            'tglmasuk' => $tglmasuk,
            'jammasuk'  => $jmin,
            'kmmasuk'  => $kmin,
            'keterangan' => $ket,
            'inputer'   => 'Security'

        );
        $this->M_Logistik->insert_lap_distribusi($datatmp);
        $this->M_Logistik->delete_tmp_lap_dis($id);
        redirect('tmp_logistik_distribusi');
    }

    // PENTING UNTUK DI INGAT DAN DI PAHAMI 
    // DATA PRE-DO DIDAPATI DARI ZAHIR DIGITAL

    // DATA STATUS PRE-DO 
    // 1   = DATA TIDAK ADA DI DRAFT
    // 2   = DATA TERINPUT DI DRAFT LIST

    // 3   = PENDING DATA BARANG / TIDAK DIMASUKAN PADA DATA FAKTUR YANG TELAH TERINPUT PADA DRAFT

    // DATA STATUS UPLOAD UPDATE PENJUALAN
    // 1   = DATA UPDATE PAGI
    // 2   = DATA UPDATE SORE

    public function delivery_order()
    {
        $data['page_title']     = 'KARISMA - LOGISTIK';
        $data['kdgenerate']     = $this->M_Keuangan->generate_update();
        $data['list_faktur']    = $this->M_Logistik->get_data_penjualan_zahir();
        $data['updated']        = $this->M_Logistik->get_updated_data_preparation();
        $data['listdo']         = $this->M_Logistik->getdo();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/body.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function create_do()
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['list_faktur'] = $this->M_Logistik->get_data_penjualan_zahir();
        $data['tmp_faktur'] = $this->M_Logistik->get_tmp_do();
        $data['generate_do'] = $this->M_Logistik->generate_kd_do();
        $data['qcount_tonase_kubikasi'] = $this->M_Logistik->get_tonase_kubikasi();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/createdo.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function cancel_fk($kd_faktur, $kd_do)
    {
        $this->M_Logistik->delete_faktur_customer($kd_faktur);
        $this->M_Logistik->delete_faktur_cus($kd_faktur);
        redirect('detail_do/' . $kd_do);
    }

    public function detail_do($kd_do)
    {
        $query = $this->db->query("SELECT 
				a.norut, d.nama_kios, d.telp1, d.telp2, a.kd_rute ,d.regional, a.id,
                a.kd_faktur,a.tgl_transaksi, c.nm_barang, a.no_lot, a.nominal_p , a.jtempo, 
                a.tgl_exp, a.satuan, a.status, a.kd_do,
                (SELECT SUM(f.qty) FROM tb_detail_do f WHERE a.kd_faktur = f.kd_faktur 
                AND a.kd_barang = f.kd_barang AND a.no_lot = f.no_lot ) AS qty,
                (c.p*c.l*c.t) AS dimensi,
                FLOOR((SELECT SUM(f.qty) FROM tb_detail_do f WHERE a.kd_faktur = f.kd_faktur 
                AND a.kd_barang = f.kd_barang AND a.no_lot = f.no_lot )/(c.p*c.l*c.t)) AS qty_box,
                ((SELECT SUM(f.qty) FROM tb_detail_do f WHERE a.kd_faktur = f.kd_faktur 
                AND a.kd_barang = f.kd_barang AND a.no_lot = f.no_lot)-((FLOOR((SELECT SUM(f.qty) FROM tb_detail_do f WHERE a.kd_faktur = f.kd_faktur 
                AND a.kd_barang = f.kd_barang AND a.no_lot = f.no_lot )/(c.p*c.l*c.t)))*(c.p*c.l*c.t))) AS qty_pcs
                FROM tb_detail_do a
                JOIN tb_do b ON b.kd_do = a.kd_do
                JOIN tb_master_barang c ON c.kode_barang = a.kd_barang
                JOIN tb_customer d ON d.kd_customer = a.kd_customer
                WHERE b.kd_do = '$kd_do'
                GROUP BY a.kd_barang , a.no_lot
                ORDER BY a.norut
            ", array($kd_do));

        $querytc = $this->db->query("SELECT 
				a.norut, d.nama_kios, d.telp1, d.telp2, a.kd_rute ,d.regional, a.id,
                a.kd_faktur,a.tgl_transaksi, a.nominal_p , a.jtempo, 
                a.tgl_exp, a.satuan, a.status, a.kd_do
                FROM tb_detail_do a
                JOIN tb_customer d ON d.kd_customer = a.kd_customer
                JOIN tb_do b ON b.kd_do = a.kd_do
                WHERE b.kd_do = '$kd_do'
                GROUP BY a.kd_faktur
                ORDER BY a.norut
            ", array($kd_do));

        $querysts = $this->db->query("SELECT a.* FROM tb_do a where a.kd_do = '$kd_do'")->result();

        $query1 = $this->db->query("SELECT
            b.kd_do,
            b.regional,
            b.nolambung,
            b.driver,
            COUNT(DISTINCT a.kd_barang) AS total_barang,
            ROUND(SUM(a.qty * c.berat)/1000000,3) AS total_tonase_faktur,
            ROUND(SUM(a.qty * c.kubikasi),2) AS total_kubikasi,
            COUNT(DISTINCT a.kd_faktur) AS totalfaktur
        FROM
            tb_detail_do a
        JOIN tb_do b ON b.kd_do = a.kd_do
        JOIN tb_master_barang c ON c.kode_barang = a.kd_barang
        WHERE
            b.kd_do = '$kd_do'
        GROUP BY
            b.kd_do, b.regional, b.nolambung, b.driver
        ");

        $query2 = $this->db->where('kd_do', $kd_do)->get('tb_do');
        $query3 = $this->db->where('kd_do', $kd_do)->get('tb_detail_do');

        $data['page_title']  = 'KARISMA - LOGISTIK';
        $data['kdo'] = $query1->result();
        $data['dostatus'] = $query2->result();
        $data['dostatuss'] = $query3->result();
        $data['data_list'] = $query->result();
        $data['datatc'] = $querytc->result();
        $data['doprintsts'] = $querysts;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/body_detaildo.php', $data);
        $this->load->view('partial/main/footer.php');
    }
    public function acc_check($id, $action, $kd)
    {
        switch ($action) {

            case '1':
                $update_sts = array(
                    'status'    => '2'
                );
                $this->M_Logistik->update_sts_detail_checker($update_sts, $id);
                redirect('detail_do/' . $kd);
                break;

            case '2':
                $update_sts = array(
                    'status'    => '3'
                );
                $this->M_Logistik->update_sts_detail_checker($update_sts, $id);
                redirect('detail_do/' . $kd);
                break;
            case '3':
                $update_sts = array(
                    'status'    => '1   '
                );
                $this->M_Logistik->update_sts_detail_checker($update_sts, $id);
                redirect('detail_do/' . $kd);
                break;
        }
    }

    public function rekam_order_check()
    {
        $kd = $this->input->post('kd_do');
        $nolambung = $this->input->post('platno');
        $tgldeliv = $this->input->post('tgl_krim');
        $driver = $this->input->post('driver');
        $datenow = date("Y-m-d");

        if (!$kd || !$nolambung || !$tgldeliv || !$driver) {
            echo json_encode(['msg' => 'error', 'message' => 'Data tidak lengkap']);
            return;
        }

        $dataupdated_do = [
            'nolambung' => $nolambung,
            'driver' => $driver,
            'tgl_pengiriman' => $tgldeliv,
            'status' => 2
        ];
        $dataupdateddetail_do = [
            'dt_status' => 1,
            'status' => 4,
            'input_at' => $datenow
        ];

        $this->M_Logistik->update_checker_done($kd, $dataupdated_do);
        $this->M_Logistik->update_checker_detail_done($kd, 1, $dataupdateddetail_do);

        echo json_encode(['msg' => 'success', 'message' => 'Data berhasil diperbarui']);
    }

    public function list_faktur_sortby_rute($kdfaktur, $rute)
    {
        $data['page_title']     = 'KARISMA - LOGISTIK';
        $data['kdfaktur']       = $kdfaktur;
        $data['list_faktur']    = $this->M_Logistik->get_list_by_rute($rute);

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/list_faktur_by_rute.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function insertfromdraft($kddo, $kdfaktur, $rute)
    {
        date_default_timezone_set("Asia/Jakarta");
        $get_pre_do = $this->M_Logistik->get_do_cust($kdfaktur);
        $getdetail  = $this->M_Logistik->get_do_cust_byfaktur($kdfaktur);

        $now = date("Y-m-d H:i:s");

        if ($get_pre_do) {
            $data_tmp_det_do = [];
            $kdfaktur = null;

            foreach ($get_pre_do as $g) {
                $kdfaktur = $g->kd_faktur;
            }

            if ($getdetail) {
                foreach ($getdetail as $det) {
                    $data_tmp_det_do[] = array(
                        'id_pre_do'     => $det->id,
                        'kd_do'         => $kddo,
                        'kd_faktur'     => $det->kd_faktur,
                        'tgl_transaksi' => $det->tgl_inputer,
                        'kd_rute'       => $det->kd_rute,
                        'kd_customer'   => $det->kd_customer,
                        'kd_barang'     => $det->kd_barang,
                        'qty'           => $det->qty,
                        'satuan'        => $det->satuan,
                        'no_lot'        => $det->no_lot,
                        'tgl_exp'       => $det->tgl_exp,
                        'norut'         => 0,
                        'dt_status'     => 1,
                        'status'        => 1,
                        'create_at'     => $now
                    );
                }

                if (!empty($data_tmp_det_do)) {
                    $this->M_Logistik->insert_fakturfrom_draft_batch($data_tmp_det_do);
                }

                $update_pre_do = array(
                    'data_sts' => '2'
                );
                $this->M_Logistik->update_sts_pre_do($kdfaktur, $update_pre_do);
            }
        }
        redirect('list_faktur/' . $kddo . '/' . $rute);
    }

    public function print_do($kd_do)
    {
        $query = $this->db->query("SELECT 
				a.norut, d.nama_kios, d.telp1, d.telp2, a.kd_rute ,d.regional, a.id,
                a.kd_faktur, a.tgl_transaksi, c.nm_barang, a.no_lot, 
                a.tgl_exp, a.satuan, a.status, a.kd_do,d.jam_buka_tutup AS jam_buka_tutup, d.karakteristik_kios AS karakteristik_kios,
                (SELECT SUM(f.qty) FROM tb_detail_do f WHERE a.kd_faktur = f.kd_faktur 
                AND a.kd_barang = f.kd_barang AND a.no_lot = f.no_lot ) AS qty,
                (c.p*c.l*c.t) AS dimensi,
                FLOOR((SELECT SUM(f.qty) FROM tb_detail_do f WHERE a.kd_faktur = f.kd_faktur 
                AND a.kd_barang = f.kd_barang AND a.no_lot = f.no_lot )/(c.p*c.l*c.t)) AS qty_box,
                ((SELECT SUM(f.qty) FROM tb_detail_do f WHERE a.kd_faktur = f.kd_faktur 
                AND a.kd_barang = f.kd_barang AND a.no_lot = f.no_lot)-((FLOOR((SELECT SUM(f.qty) FROM tb_detail_do f WHERE a.kd_faktur = f.kd_faktur 
                AND a.kd_barang = f.kd_barang AND a.no_lot = f.no_lot )/(c.p*c.l*c.t)))*(c.p*c.l*c.t))) AS qty_pcs
                FROM tb_detail_do a
                JOIN tb_do b ON b.kd_do = a.kd_do
                JOIN tb_master_barang c ON c.kode_barang = a.kd_barang
                JOIN tb_customer d ON d.kd_customer = a.kd_customer
                WHERE b.kd_do = '$kd_do'
                GROUP BY a.kd_barang , a.no_lot
                ORDER BY a.norut
            ", array($kd_do));

        $querysts = $this->db->query("SELECT
                b.kd_do,
                b.regional,
                b.nolambung,
                b.driver,
                b.tgl_pengiriman,
                COUNT(DISTINCT a.kd_barang) AS total_barang,
                ROUND(SUM(a.qty * c.berat)/1000,2) AS total_tonase_faktur,
                COUNT(DISTINCT a.kd_faktur) AS totalfaktur
            FROM
                tb_detail_do a
            JOIN tb_do b ON b.kd_do = a.kd_do
            JOIN tb_master_barang c ON c.kode_barang = a.kd_barang
            WHERE
                b.kd_do = '$kd_do'
            GROUP BY
                b.kd_do, b.regional, b.nolambung, b.driver
            ")->result();

        $data['page_title']  = 'KARISMA - LOGISTIK';
        $query1 = $this->db->where('kd_do', $kd_do)->limit(1)->get('tb_detail_do');
        $query2 = $this->db->where('kd_do', $kd_do)->get('tb_do');
        $data['kdo'] = $query1->result();
        $data['dostatus'] = $query2->result();
        $data['data_list'] = $query->result();
        $data['doprintsts'] = $querysts;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/printout_do.php', $data);
        $this->load->view('partial/main/footerprint.php');
    }

    public function print_regis($kd_do)
    {
        $query = $this->db->query("SELECT 
				a.norut, d.nama_kios, d.telp1, d.telp2, a.kd_rute ,d.regional, a.id,
                a.kd_faktur, a.tgl_transaksi, c.nm_barang, a.no_lot, 
                a.tgl_exp, a.satuan,a.nominal_p AS valuep, a.jtempo AS tempo,
                a.status, a.kd_do,d.jam_buka_tutup AS jam_buka_tutup, d.karakteristik_kios AS karakteristik_kios,
                (SELECT SUM(f.qty) FROM tb_detail_do f WHERE a.kd_faktur = f.kd_faktur 
                AND a.kd_barang = f.kd_barang AND a.no_lot = f.no_lot ) AS qty,
                (c.p*c.l*c.t) AS dimensi,
                FLOOR((SELECT SUM(f.qty) FROM tb_detail_do f WHERE a.kd_faktur = f.kd_faktur 
                AND a.kd_barang = f.kd_barang AND a.no_lot = f.no_lot )/(c.p*c.l*c.t)) AS qty_box,
                ((SELECT SUM(f.qty) FROM tb_detail_do f WHERE a.kd_faktur = f.kd_faktur 
                AND a.kd_barang = f.kd_barang AND a.no_lot = f.no_lot)-((FLOOR((SELECT SUM(f.qty) FROM tb_detail_do f WHERE a.kd_faktur = f.kd_faktur 
                AND a.kd_barang = f.kd_barang AND a.no_lot = f.no_lot )/(c.p*c.l*c.t)))*(c.p*c.l*c.t))) AS qty_pcs
                FROM tb_detail_do a
                JOIN tb_do b ON b.kd_do = a.kd_do
                JOIN tb_master_barang c ON c.kode_barang = a.kd_barang
                JOIN tb_customer d ON d.kd_customer = a.kd_customer
                WHERE b.kd_do = '$kd_do'
                GROUP BY a.kd_barang , a.no_lot
                ORDER BY a.norut
            ", array($kd_do));

        $querytc = $this->db->query("SELECT 
				a.norut, d.nama_kios, d.telp1, d.telp2, a.kd_rute ,d.regional, a.id,
                a.kd_faktur,a.tgl_transaksi, a.nominal_p , a.jtempo, 
                a.tgl_exp, a.satuan, a.status, a.kd_do,d.alamat_kios
                FROM tb_detail_do a
                JOIN tb_customer d ON d.kd_customer = a.kd_customer
                JOIN tb_do b ON b.kd_do = a.kd_do
                WHERE b.kd_do = '$kd_do'
                GROUP BY a.kd_faktur
                ORDER BY a.norut
            ", array($kd_do));

        $querysts = $this->db->query("SELECT
                b.kd_do,
                b.regional,
                b.nolambung,
                b.driver,
                b.tgl_pengiriman,
                COUNT(DISTINCT a.kd_barang) AS total_barang,
                ROUND(SUM(a.qty * c.berat)/1000,2) AS total_tonase_faktur,
                COUNT(DISTINCT a.kd_faktur) AS totalfaktur
            FROM
                tb_detail_do a
            JOIN tb_do b ON b.kd_do = a.kd_do
            JOIN tb_master_barang c ON c.kode_barang = a.kd_barang
            WHERE
                b.kd_do = '$kd_do'
            GROUP BY
                b.kd_do, b.regional, b.nolambung, b.driver
            ")->result();

        $data['page_title']  = 'KARISMA - LOGISTIK';
        $query1 = $this->db->where('kd_do', $kd_do)->limit(1)->get('tb_detail_do');
        $query2 = $this->db->where('kd_do', $kd_do)->get('tb_do');
        $data['kdo'] = $query1->result();
        $data['dostatus'] = $query2->result();
        $data['data_list'] = $query->result();
        $data['datatc'] = $querytc->result();
        $data['doprintsts'] = $querysts;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/printout_regis.php', $data);
        $this->load->view('partial/main/footerprint.php');
    }

    public function get_driver()
    {
        $data = $this->M_Logistik->select_driver();
        echo json_encode($data);
    }

    public function get_noplat()
    {
        $data = $this->M_Logistik->select_plat();
        echo json_encode($data);
    }

    public function insert_tmp($kd, $action)
    {
        switch ($action) {

            case 'formdetail':

                date_default_timezone_set("Asia/Jakarta");
                $get_pre_do = $this->M_Logistik->get_do_cust($kd);
                $det_pre_do = $this->M_Logistik->det_do_cust($kd);
                $kddo       = $this->M_Logistik->generate_kd_do();
                $now        = date("Y-m-d h:i:sa");

                if ($get_pre_do) {
                    foreach ($get_pre_do as $g) {
                        $kdfaktur = $g->kd_faktur;
                    }

                    if ($det_pre_do) {
                        foreach ($det_pre_do as $det) {
                            $tmp_det_do  = array(
                                'id_pre_do'     => $det->id,
                                'kd_do'         => $kddo,
                                'tgl_transaksi' => $det->tgl_inputer,
                                'kd_faktur'     => $det->kd_faktur,
                                'kd_rute'       => $det->kd_rute,
                                'kd_customer'   => $det->kd_customer,
                                'kd_barang'     => $det->kd_barang,
                                'nm_barang'     => $det->nm_barang,
                                'qty'           => $det->qty,
                                'satuan'        => $det->satuan,
                                'no_lot'        => $det->no_lot,
                                'tgl_exp'       => $det->tgl_exp,
                                'nominal_p'     => $det->nominal_p,
                                'jtempo'        => $det->jtempo,
                                'barang_sts'    => $det->barang_sts,
                            );
                            $this->M_Logistik->insert_tmp_detdo($tmp_det_do);
                        }
                    }

                    $datainsert = array(
                        'kd_do'      => $kddo,
                        'kd_faktur'  => $kdfaktur,
                        'input_at'   => $now
                    );

                    $update_pre_do = array(
                        'data_sts'    => '2'
                    );

                    $this->M_Logistik->insert_tmp_do($datainsert);
                    $this->M_Logistik->update_sts_pre_do($kdfaktur, $update_pre_do);
                    redirect('detail_fk/' . $kd);
                }
                break;

            case 'formlist':
                date_default_timezone_set("Asia/Jakarta");
                $get_pre_do = $this->M_Logistik->get_do_cust($kd);
                $getdetail  = $this->M_Logistik->get_do_cust_byfaktur($kd);
                $kddo       = $this->M_Logistik->generate_kd_do();
                $now = date("Y-m-d H:i:s");

                if ($get_pre_do) {
                    $data_tmp_det_do = [];
                    $kdfaktur = null;

                    foreach ($get_pre_do as $g) {
                        $kdfaktur = $g->kd_faktur;
                    }

                    if ($getdetail) {
                        foreach ($getdetail as $det) {
                            $data_tmp_det_do[] = array(
                                'id_pre_do'     => $det->id,
                                'kd_do'         => $kddo,
                                'tgl_transaksi' => $det->tgl_inputer,
                                'kd_faktur'     => $det->kd_faktur,
                                'kd_rute'       => $det->kd_rute,
                                'kd_customer'   => $det->kd_customer,
                                'kd_barang'     => $det->kd_barang,
                                'nm_barang'     => $det->nm_barang,
                                'qty'           => $det->qty,
                                'satuan'        => $det->satuan,
                                'no_lot'        => $det->no_lot,
                                'tgl_exp'       => $det->tgl_exp,
                                'nominal_p'     => $det->nominal_p,
                                'jtempo'        => $det->jtempo,
                                'barang_sts'    => $det->barang_sts,
                                'create_at'     => $now
                            );
                        }

                        if (!empty($data_tmp_det_do)) {
                            $this->M_Logistik->insert_tmp_detdo_batch($data_tmp_det_do);
                        }

                        $datainsert = array(
                            'kd_do'      => $kddo,
                            'kd_faktur'  => $kdfaktur,
                            'input_at'   => $now
                        );

                        $this->M_Logistik->insert_tmp_do($datainsert);

                        $update_pre_do = array(
                            'data_sts' => '2'
                        );
                        $this->M_Logistik->update_sts_pre_do($kdfaktur, $update_pre_do);
                    }
                }
                redirect('create_do');
        }
    }

    public function revert_do($kd, $action)
    {
        switch ($action) {
            case 'revertdetail':
                $update_pre_do = array(
                    'data_sts'    => '1',
                    'barang_sts'  => '1'
                );

                $this->M_Logistik->update_sts_pre_do($kd, $update_pre_do);
                $this->M_Logistik->del_tmp_do($kd);
                $this->M_Logistik->del_tmp_do_det($kd);

                redirect('detail_fk/' . $kd);
                break;
            case 'formlist':
                $update_pre_do = array(
                    'data_sts'    => '1',
                    'barang_sts'  => '1'
                );

                $this->M_Logistik->update_sts_pre_do($kd, $update_pre_do);
                $this->M_Logistik->del_tmp_do($kd);
                $this->M_Logistik->del_tmp_do_det($kd);

                redirect('create_do');
                break;
        }
    }

    public function truncatelog($kdupdate, $sts)
    {

        $this->M_Logistik->truncateitm($kdupdate, $sts);
        $this->M_Logistik->truncatests($kdupdate);

        redirect('logistik');
    }

    public function get_tmp_do()
    {
        $data = $this->M_Logistik->get_tmp_do();
        echo json_encode($data);
    }

    public function rekam_do()
    {
        date_default_timezone_set("Asia/Jakarta");

        $kd_do = $this->input->post('kd_do');
        $nolambung = $this->input->post('platno');
        $tgldeliv = $this->input->post('tgl_krim');
        $kota = $this->input->post('kota');
        $driver = $this->input->post('driver');
        $now = date("Y-m-d H:i:s");

        $tmpdetail = $this->M_Logistik->get_tmp_dokd($kd_do);

        $datado = array(
            'kd_do' => $kd_do,
            'nolambung' => $nolambung,
            'regional' => $kota,
            'driver' => $driver,
            'tgl_pengiriman' => $tgldeliv,
            'tgl_create' => $now,
            'status'    => '1'
        );

        $this->M_Logistik->insert_do($datado);

        if ($tmpdetail) {
            $update_ids = [];

            foreach ($tmpdetail as $tmp) {
                $kdfaktur = $tmp->kd_faktur;
                $getkdfaktur = $this->M_Logistik->getkdfaktur($kdfaktur)->result();

                foreach ($getkdfaktur as $get_faktur_detail) {
                    $norut  = $get_faktur_detail->norut_do;
                }

                $detaildo = array(
                    'id_pre_do'     => $tmp->id_pre_do,
                    'kd_do'         => $kd_do,
                    'kd_faktur'     => $tmp->kd_faktur,
                    'tgl_transaksi' => $tmp->tgl_transaksi,
                    'kd_rute'       => $tmp->kd_rute,
                    'kd_customer'   => $tmp->kd_customer,
                    'kd_barang'     => $tmp->kd_barang,
                    'nama_barang'   => $tmp->nm_barang,
                    'qty'           => $tmp->qty,
                    'satuan'        => $tmp->satuan,
                    'no_lot'        => $tmp->no_lot,
                    'tgl_exp'       => $tmp->tgl_exp,
                    'norut'         => $norut,
                    'nominal_p'     => $tmp->nominal_p,
                    'jtempo'        => $tmp->jtempo,
                    'dt_status'     => '1',
                    'status'        => '1',
                    'create_at'     => $tmp->create_at
                );

                $this->M_Logistik->insert_det_do($detaildo);

                $update_ids[] = $tmp->id_pre_do;
            }

            if (!empty($update_ids)) {
                $this->db->where_in('id', $update_ids);
                $this->db->update('tb_pre_do', ['data_sts' => 3]);
            }

            $this->M_Logistik->deletetmp_detdo($kd_do);
            $this->M_Logistik->deletetmp_do($kd_do);

            echo json_encode(['msg' => 'success']);
        } else {
            echo json_encode(['msg' => 'error', 'message' => 'Data tidak ditemukan']);
        }
        exit;
    }


    public function detail_fk($kd)
    {
        $data['page_title']     = 'KARISMA - LOGISTIK';
        $data['detail_fk']      = $this->M_Logistik->detail_fk($kd);
        $data['customer']       = $this->M_Logistik->det_customer($kd);
        $data['kdfaktur']       = $kd;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/detailfk.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function get_barang()
    {
        $idbarang = $this->input->post('id');

        $this->db->select('a.*, b.nm_barang');
        $this->db->from('tb_pre_do a');
        $this->db->join('tb_master_barang b', 'b.kode_barang = a.kd_barang', 'left');
        $this->db->where('a.id', $idbarang);

        $data = $this->db->get()->row();

        echo json_encode($data);
    }

    public function get_tmpdonorut()
    {
        $idtmp = $this->input->post('id');

        $this->db->select('a.id,a.norut_do');
        $this->db->from('tb_tmp_do a');
        $this->db->where('a.id', $idtmp);

        $data = $this->db->get()->row();

        echo json_encode($data);
    }

    public function update_norut()
    {
        $idbarang  = $this->input->post('id');

        $data = [
            'norut_do'       => $this->input->post('nourut'),
        ];

        $this->db->where('id', $idbarang);
        $this->db->update('tb_tmp_do', $data);

        echo json_encode(['status' => 'success']);
    }

    public function update_barang()
    {

        date_default_timezone_set("Asia/Jakarta");
        $idbarang  = $this->input->post('id');
        $items = $this->db->get_where('tb_pre_do', ['id' => $idbarang])->row();

        $log_data = [
            'kd_faktur'    => $items->kd_faktur,
            'kd_customer'  => $items->kd_customer,
            'kd_barang'    => $items->kd_barang,
            'keterangan'   => "Edited:" . $items->kd_faktur . $items->kd_barang,
            'edit_by'      => $this->session->userdata('nik'),
            'edit_at'      => date('Y-m-d H:i:s')
        ];
        $this->db->insert('tb_editlog_faktur', $log_data);

        $data = [
            'qty'       => $this->input->post('qty'),
            'satuan'    => $this->input->post('satuan'),
            'no_lot'    => $this->input->post('no_lot'),
            'tgl_exp'   => $this->input->post('tgl_exp')
        ];

        $this->db->where('id', $idbarang);
        $this->db->update('tb_pre_do', $data);
        echo json_encode(['status' => 'success']);
    }

    public function pnd_br_detpo($id, $kd, $action)
    {
        switch ($action) {
            case 'pending':
                $updatebr = array(
                    'barang_sts'     => '3 '
                );
                $this->M_Logistik->updatedsts($id, $updatebr);
                redirect('detail_fk/' . $kd);
                break;
            case 'revert':
                $updatebr = array(
                    'barang_sts'     => '1'
                );
                $this->M_Logistik->updatedsts($id, $updatebr);
                redirect('detail_fk/' . $kd);
                break;
        }
    }

    public function admstocktracking()
    {
        $data['page_title'] = 'KARISMA - ICS';
        $result_t1 = $this->M_Logistik->all_barang_match_t1();
        $result_t2 = $this->M_Logistik->all_barang_match_t2();
        $resultexp_t1 = $this->M_Logistik->fefo_match_t1();
        $resultexp_t2 = $this->M_Logistik->fefo_match_t2();

        $res_t1 = $result_t1[0];
        $res_t2 = $result_t2[0];

        $resexp_t1 = $resultexp_t1[0];
        $resexp_t2 = $resultexp_t2[0];

        $data['stat_t1'] = [
            'total_barang'   => $res_t1->total_barang,
            'total_match'    => $res_t1->total_match,
            'total_notmatch' => $res_t1->total_notmatch,
            'persen_match'   => $res_t1->total_barang > 0 ? round(($res_t1->total_match / $res_t1->total_barang) * 100, 2) : 0,
            'persen_notmatch' => $res_t1->total_barang > 0 ? round(($res_t1->total_notmatch / $res_t1->total_barang) * 100, 2) : 0
        ];

        $data['statexp_t1'] = [
            'total_barang'   => $resexp_t1->total_barang,
            'total_match'    => $resexp_t1->total_match,
            'total_notmatch' => $resexp_t1->total_notmatch,
            'persen_match'   => $resexp_t1->total_barang > 0 ? round(($resexp_t1->total_match / $resexp_t1->total_barang) * 100, 2) : 0,
            'persen_notmatch' => $resexp_t1->total_barang > 0 ? round(($resexp_t1->total_notmatch / $resexp_t1->total_barang) * 100, 2) : 0
        ];

        $data['stat_t2'] = [
            'total_barang'   => $res_t2->total_barang,
            'total_match'    => $res_t2->total_match,
            'total_notmatch' => $res_t2->total_notmatch,
            'persen_match'   => $res_t2->total_barang > 0 ? round(($res_t2->total_match / $res_t2->total_barang) * 100, 2) : 0,
            'persen_notmatch' => $res_t2->total_barang > 0 ? round(($res_t2->total_notmatch / $res_t2->total_barang) * 100, 2) : 0
        ];

        $data['statexp_t2'] = [
            'total_barang'   => $resexp_t2->total_barang,
            'total_match'    => $resexp_t2->total_match,
            'total_notmatch' => $resexp_t2->total_notmatch,
            'persen_match'   => $resexp_t2->total_barang > 0 ? round(($resexp_t2->total_match / $resexp_t2->total_barang) * 100, 2) : 0,
            'persen_notmatch' => $resexp_t2->total_barang > 0 ? round(($resexp_t2->total_notmatch / $resexp_t2->total_barang) * 100, 2) : 0
        ];

        $data['all_t1'] = $result_t1;
        $data['all_t2'] = $result_t2;

        $data['allexp_t1'] = $resultexp_t1;
        $data['allexp_t2'] = $resultexp_t2;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/ics/adminstockopname.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ics/ajaxics.php', $data);
    }

    public function compareall_tim()
    {
        $data['page_title'] = 'KARISMA - ICS';

        $data['allbarang'] = $this->M_Logistik->admin_compareuser_all();

        $result_t1 = $this->M_Logistik->all_barang_match_t1();
        $result_t2 = $this->M_Logistik->all_barang_match_t2();

        $res_t1 = $result_t1[0];
        $res_t2 = $result_t2[0];

        $data['stat_t1'] = [
            'total_barang'   => $res_t1->total_barang,
            'total_match'    => $res_t1->total_match,
            'total_notmatch' => $res_t1->total_notmatch,
            'persen_match'   => $res_t1->total_barang > 0 ? round(($res_t1->total_match / $res_t1->total_barang) * 100, 2) : 0,
            'persen_notmatch' => $res_t1->total_barang > 0 ? round(($res_t1->total_notmatch / $res_t1->total_barang) * 100, 2) : 0
        ];
        $data['stat_t2'] = [
            'total_barang'   => $res_t2->total_barang,
            'total_match'    => $res_t2->total_match,
            'total_notmatch' => $res_t2->total_notmatch,
            'persen_match'   => $res_t2->total_barang > 0 ? round(($res_t2->total_match / $res_t2->total_barang) * 100, 2) : 0,
            'persen_notmatch' => $res_t2->total_barang > 0 ? round(($res_t2->total_notmatch / $res_t2->total_barang) * 100, 2) : 0
        ];

        $data['all_t1'] = $result_t1;
        $data['all_t2'] = $result_t2;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/ics/compare_allbarang.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ics/ajaxics.php', $data);
    }

    public function detail_tracking_input($kdbarang, $action)
    {

        switch ($action) {
            case 'allbarang':
                $data['page_title'] = 'Opname Detail Inputer';
                $data['list'] = $this->M_Logistik->list_inputer_by_allbarang($kdbarang);

                $this->load->view('partial/main/header.php', $data);
                $this->load->view('content/logistik/ics/detail_tracking.php', $data);
                $this->load->view('partial/main/footer.php');
                $this->load->view('content/logistik/ics/ajaxics.php', $data);
                break;
            case 'expdate':

                $data['page_title'] = 'Opname Detail Inputer';
                $data['list'] = $this->M_Logistik->list_inputer_by_expdate($kdbarang);

                $this->load->view('partial/main/header.php', $data);
                $this->load->view('content/logistik/ics/detail_tracking_exp.php', $data);
                $this->load->view('partial/main/footer.php');
                $this->load->view('content/logistik/ics/ajaxics.php', $data);
                break;
        }
    }

    public function compare_exp()
    {
        $data['page_title'] = 'KARISMA - ICS';
        $data['expired_date'] = $this->M_Logistik->admin_compareuser_exp();

        $resultexp_t1 = $this->M_Logistik->fefo_match_t1();
        $resultexp_t2 = $this->M_Logistik->fefo_match_t2();

        $resexp_t1 = $resultexp_t1[0];
        $resexp_t2 = $resultexp_t2[0];

        $data['statexp_t1'] = [
            'total_barang'   => $resexp_t1->total_barang,
            'total_match'    => $resexp_t1->total_match,
            'total_notmatch' => $resexp_t1->total_notmatch,
            'persen_match'   => $resexp_t1->total_barang > 0 ? round(($resexp_t1->total_match / $resexp_t1->total_barang) * 100, 2) : 0,
            'persen_notmatch' => $resexp_t1->total_barang > 0 ? round(($resexp_t1->total_notmatch / $resexp_t1->total_barang) * 100, 2) : 0
        ];

        $data['statexp_t2'] = [
            'total_barang'   => $resexp_t2->total_barang,
            'total_match'    => $resexp_t2->total_match,
            'total_notmatch' => $resexp_t2->total_notmatch,
            'persen_match'   => $resexp_t2->total_barang > 0 ? round(($resexp_t2->total_match / $resexp_t2->total_barang) * 100, 2) : 0,
            'persen_notmatch' => $resexp_t2->total_barang > 0 ? round(($resexp_t2->total_notmatch / $resexp_t2->total_barang) * 100, 2) : 0
        ];

        $data['allexp_t1'] = $resultexp_t1;
        $data['allexp_t2'] = $resultexp_t2;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/ics/compare_expdate.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ics/ajaxics.php', $data);
    }

    public function ics($gudang)
    {
        switch ($gudang) {
            case 'global':

                $data['page_title'] = 'KARISMA - ICS';
                $data['listics'] = $this->M_Logistik->getbarangics();
                $data['data_ics'] = $this->M_Logistik->getAllICS();

                $data['fefo'] = $this->M_Logistik->compareFEFO();
                $data['allbarang'] = $this->M_Logistik->compareAllBarang();
                $data['stat_fefo'] = $this->M_Logistik->statistikFEFO();
                $data['stat_allbarang'] = $this->M_Logistik->statistikAllBarang();

                $this->load->view('partial/main/header.php', $data);
                $this->load->view('content/logistik/ics/ics.php', $data);
                $this->load->view('partial/main/footer.php');
                $this->load->view('content/logistik/ics/ajaxics.php', $data);
                break;

            case 'induk':
                break;
        }
    }

    public function stockopname()
    {
        $data['page_title'] = 'KARISMA - ICS';
        $data['data_ics'] = $this->M_Logistik->getAllICS();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/ics/icsopname.php', $data);
        $this->load->view('partial/main/footeropname.php');
    }

    public function searchbarang()
    {
        $search = $this->input->get('search');
        $result = $this->db->like('nama_barang', $search)
            ->group_by('nama_barang')
            ->get('tb_ics')->result();

        $data = [];
        foreach ($result as $row) {
            $data[] = ['id' => $row->nama_barang, 'text' => $row->nama_barang];
        }
        echo json_encode($data);
    }

    public function search_get_exp_date()
    {
        $nama_barang = $this->input->post('nama_barang');

        $exp_dates = $this->db->select('exp_date')
            ->where('nama_barang', $nama_barang)
            ->group_by('exp_date')
            ->order_by('exp_date', 'ASC')
            ->get('tb_ics')
            ->result();

        $dimensi = $this->db->select('p, l, t')
            ->where('nm_barang', $nama_barang)
            ->get('tb_mbarang')
            ->row();

        $data_dimensi = [
            'p' => $dimensi ? $dimensi->p : null,
            'l' => $dimensi ? $dimensi->l : null,
            't' => $dimensi ? $dimensi->t : null,
        ];

        $result = [
            'exp_dates' => $exp_dates,
            'dimensi' => $data_dimensi
        ];

        echo json_encode($result);
    }

    public function save_opname()
    {
        $nmbarang   = $this->input->post('nama_barang');
        $box        = $this->input->post('qty_box');
        $pcs        = $this->input->post('qty_pcs');

        $dimensi    = $this->M_Logistik->getDimensi($nmbarang);
        $total_qty  = hitung_qty($box, $pcs, $dimensi);

        $opname = [
            'nama_barang'   => $nmbarang,
            'exp_date'      => $this->input->post('exp_date'),
            'qty'           => $total_qty,
            'qty_box'       => $box,
            'qty_pcs'       => $pcs,
            'inputer'       => $this->session->userdata('nama'),
            'tim'           => $this->session->userdata('tim'),
            'input_at'      => date('Y-m-d H:i:s')
        ];

        $log = [
            'nama_user'     => $this->session->userdata('nama'),
            'nama_barang'   => $nmbarang,
            'qty'           => $total_qty,
            'qty_box'       => $box,
            'qty_pcs'       => $pcs,
            'no_lot'        => '-',
            'exp_date'      => $this->input->post('exp_date'),
            'inputer'       => $this->session->userdata('nik'),
            'tgl_input'     => date('Y-m-d'),
            'keterangan'    => 'Stock Opname'
        ];

        $this->db->insert('tb_ics_opname', $opname);
        $this->db->insert('tb_log_ics', $log);
        echo 'ok';
    }

    // public function insertopname()
    // {
    //     $nama_barang    = $this->input->post('nmbarang');
    //     $exp_date       = $this->input->post('expdate');
    //     $qty_box        = (int)$this->input->post('qtybox');
    //     $qty_pcs        = (int)$this->input->post('qtypcs');
    //     $dimensi        = (int)$this->input->post('dimensi');
    //     $inputer        = $this->session->userdata('nama');
    //     $inputernik     = $this->session->userdata('nik');
    //     $tim            = $this->session->userdata('tim');

    //     $dimensi = $this->M_Logistik->getDimensi($nama_barang);
    //     $total_qty = hitung_qty($qty_box, $qty_pcs, $dimensi);

    //     $this->M_Logistik->insertOpname([
    //         'nama_barang' => $nama_barang,
    //         'exp_date' => $exp_date,
    //         'qty' => $total_qty,
    //         'qty_box'   => $qty_box,
    //         'qty_pcs'   => $qty_pcs,
    //         'inputer'   => $inputer,
    //         'tim'       => $tim,
    //         'input_at'  => date('Y-m-d H:i:s')
    //     ]);

    //     $this->M_Logistik->logInput([
    //         'nama_user' => $inputer,
    //         'nama_barang' => $nama_barang,
    //         'qty' => $total_qty,
    //         'qty_box' => $qty_box,
    //         'qty_pcs' => $qty_pcs,
    //         'no_lot' => '-',
    //         'exp_date' => $exp_date,
    //         'inputer' => $inputernik,
    //         'tgl_input' => date('Y-m-d'),
    //         'keterangan' => 'Stock Opname'
    //     ]);

    //     redirect('stockopname');
    // }

    public function forminput($nama_barang, $exp_date)
    {
        $data['barang'] = $this->M_Logistik->getBarangByNama($nama_barang);
        $data['exp_date'] = $exp_date;
        $this->load->view('content/logistik/ics/modalopname.php', $data);
    }

    public function stkopname_tracking()
    {
        $user                   = $this->session->userdata('nama');
        $data['page_title']     = 'KARISMA - ICS';
        $data['trkopname']      = $this->M_Logistik->trackingopname($user);
        $data['resultcompare']  = $this->M_Logistik->compareinputer($user);

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/ics/stocktracking.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ics/ajaxics.php', $data);
    }

    public function admtrackingtim($tim)

    {
        $data['page_title']             = 'KARISMA - ICS';

        $data['trkopname']              = $this->M_Logistik->adm_trackingopname($tim);
        $data['resultcomparebyexp']     = $this->M_Logistik->compareinputerexp($tim);
        $data['resultcompare']          = $this->M_Logistik->compareinputer($tim);

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/ics/admstocktracking_tim.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ics/ajaxics.php', $data);
    }

    public function detailbarang($kdbarang)
    {
        $data['page_title'] = 'KARISMA - ICS';
        $data['detailbr']   = $this->M_Logistik->detailbrics($kdbarang)->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/ics/deatilics.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/ics/ajaxics.php', $data);
    }
}
