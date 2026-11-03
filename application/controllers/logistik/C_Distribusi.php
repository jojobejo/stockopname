<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Distribusi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_Ics');
        $this->load->model('M_Logistik');
        $this->load->model('M_Distribusi');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $data['page_title']             = 'KARISMA - LOGISTIK';
        $data['faktur']                 = $this->M_Distribusi->persentase_faktur();
        $data['total_tonase']           = $this->M_Distribusi->tonase_all_do_done();
        $data['all_rute']               = $this->M_Distribusi->all_rute();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/distribusi/distribusi.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/distribusi/ajax_distribusi.php');
    }

    public function get_ploting_rute()
    {
        $rute    = $this->input->post('rute');
        $tanggal = $this->input->post('tanggal');

        $data = $this->M_Distribusi->ploting_rute($rute, $tanggal);

        echo json_encode($data);
    }

    public function driver_rute_matrix()
    {
        $tanggal = $this->input->post('tanggal');
        $result = $this->M_Distribusi->get_driver_rute_matrix($tanggal);
        echo json_encode($result);
    }

    public function driver_ready()
    {
        $tanggal = $this->input->post('tanggal');
        $rute    = $this->input->post('rute');

        $data = $this->M_Distribusi->get_driver_ready($tanggal, $rute);

        echo json_encode($data);
    }


    public function detail_list_faktur()
    {
        $data['page_title']         = 'KARISMA - LOGISTIK';
        $data['total_tonase']       = $this->M_Distribusi->total_tonase_by_rute();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/detail_list_faktur.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function list_faktur_status()
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/distribusi/list_faktur_status.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/distribusi/ajax_list_faktur_status.php');
    }

    public function list_total_kirim_do()
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/distribusi/list_total_kirim_do.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/distribusi/ajax_total_kirim_do.php');
    }

    public function driver_productif()
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/distribusi/driver_productif.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/distribusi/ajax_driver_productif.php');
    }

    public function ajax_driver_productif()
    {
        $tanggal = trim((string) $this->input->post('tanggal'));
        $ket_status = trim((string) $this->input->post('ket_status'));
        if (!in_array($ket_status, ['LK', 'KK'], true)) {
            $ket_status = null;
        }

        $result = $this->M_Distribusi->get_driver_productif($tanggal, $ket_status);

        echo json_encode([
            'status' => true,
            'rute' => $result['rute'] ?? [],
            'data' => $result['data'] ?? [],
            'top' => $result['top'] ?? [],
            'bottom' => $result['bottom'] ?? []
        ]);
    }

    public function ajax_total_kirim_do()
    {
        $tanggal = trim((string) $this->input->post('tanggal'));
        $ket_status = trim((string) $this->input->post('ket_status'));
        if (!in_array($ket_status, ['LK', 'KK'], true)) {
            $ket_status = null;
        }

        $data = $this->M_Distribusi->total_kirim_do($tanggal, $ket_status);

        echo json_encode([
            'status' => true,
            'data' => $data
        ]);
    }

    public function ajax_list_faktur_status()
    {
        $status = trim((string) $this->input->post('status'));
        if (!in_array($status, ['1', '3'], true)) {
            $status = '1';
        }

        $data = $this->M_Distribusi->get_list_do_by_status($status);

        echo json_encode([
            'status' => true,
            'data' => $data
        ]);
    }

    public function list_do_status_2()
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['listdo_status2'] = [];

        $listdo = $this->M_Logistik->getdo();
        foreach ($listdo as $row) {
            if (isset($row->status) && (string) $row->status === '2') {
                $data['listdo_status2'][] = $row;
            }
        }

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/distribusi/list_do_status_2.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function detail_rute($kd)
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';

        $data['faktur'] = $this->M_Distribusi->get_faktur_byrute($kd);

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/distribusi/detail_rute.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function detail_tonase_by_rute($rute)
    {
        $data['page_title'] = 'KARISMA - LOGISTIK';
        $data['rute'] = $rute;

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/logistik/distribusi/detail_tonase.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/logistik/distribusi/ajax_detail_tonase.php', $data);
    }

    public function ajax_detail_tonase_by_rute()
    {
        $rute = trim((string) $this->input->post('rute'));
        $data = $this->M_Distribusi->detail_tonase_rute($rute);

        echo json_encode([
            'status' => true,
            'data' => $data
        ]);
    }

    public function ajax_dashboard_distribusi()
    {
        $start = trim((string) $this->input->post('start'));
        $end = trim((string) $this->input->post('end'));
        $ket_status = trim((string) $this->input->post('ket_status'));
        if (!in_array($ket_status, ['LK', 'KK'], true)) {
            $ket_status = null;
        }
        $rute = trim((string) $this->input->post('rute'));
        if ($rute === '') {
            $rute = null;
        }

        if ($start === '' || $end === '') {
            $start = date('Y-m-d');
            $end = date('Y-m-d');
        }

        $summary = $this->M_Distribusi->dashboard_faktur_summary($start, $end, $rute);
        $series = $this->M_Distribusi->dashboard_faktur_series($start, $end, $rute);
        $top_driver = $this->M_Distribusi->dashboard_driver_productif_top($start, $end, 5, $rute);
        $driver_count = $this->M_Distribusi->dashboard_driver_productif_count($start, $end, $rute);
        $top_rute = $this->M_Distribusi->dashboard_rute_rank($start, $end, $ket_status, 5, 'DESC');
        $bottom_rute = $this->M_Distribusi->dashboard_rute_rank($start, $end, $ket_status, 5, 'ASC', 2, true);

        $total_terkirim = (int) ($summary->total_terkirim ?? 0);
        $total_pending = (int) ($summary->total_pending ?? 0);
        $total_faktur = $total_terkirim + $total_pending;

        echo json_encode([
            'status' => true,
            'summary' => [
                'total_terkirim' => $total_terkirim,
                'total_pending' => $total_pending,
                'total_faktur' => $total_faktur,
                'total_driver' => (int) ($driver_count->total_driver ?? 0),
            ],
            'series' => $series,
            'top_driver' => $top_driver,
            'top_rute' => $top_rute,
            'bottom_rute' => $bottom_rute
        ]);
    }

    public function export_driver_productif()
    {
        require_once APPPATH . 'libraries/PhpSpreadsheet.php';
        $tanggal = trim((string) $this->input->get('tanggal'));
        $ket_status = trim((string) $this->input->get('ket_status'));
        if (!in_array($ket_status, ['LK', 'KK'], true)) {
            $ket_status = null;
        }

        if ($tanggal === '') {
            show_error('Tanggal tidak valid', 400);
            return;
        }

        $result = $this->M_Distribusi->get_driver_productif($tanggal, $ket_status);
        $rute = $result['rute'] ?? [];
        $data = $result['data'] ?? [];

        $ps = new PhpSpreadsheetLib();
        $spreadsheet = $ps->spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Nama Driver');
        $sheet->setCellValue('B1', 'Total Kirim');
        $col = 3;
        foreach ($rute as $r) {
            $sheet->setCellValueByColumnAndRow($col, 1, $r->kd_rute);
            $col++;
        }

        $row = 2;
        $totalsByRute = [];
        foreach ($data as $d) {
            $sheet->setCellValueByColumnAndRow(1, $row, $d['nama_driver']);
            $sheet->setCellValueByColumnAndRow(2, $row, $d['total_kirim']);
            $col = 3;
            foreach ($rute as $r) {
                $val = 0;
                if (isset($d['rute'][$r->kd_rute])) {
                    $val = (int) $d['rute'][$r->kd_rute];
                }
                $totalsByRute[$r->kd_rute] = ($totalsByRute[$r->kd_rute] ?? 0) + $val;
                $sheet->setCellValueByColumnAndRow($col, $row, $val);
                $col++;
            }
            $row++;
        }

        $sheet->setCellValueByColumnAndRow(1, $row, 'Total per Rute');
        $sheet->setCellValueByColumnAndRow(2, $row, '-');
        $col = 3;
        foreach ($rute as $r) {
            $sheet->setCellValueByColumnAndRow($col, $row, $totalsByRute[$r->kd_rute] ?? 0);
            $col++;
        }

        $filename = 'driver_productif_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = $ps->writer($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function export_total_kirim_do()
    {
        require_once APPPATH . 'libraries/PhpSpreadsheet.php';
        $tanggal = trim((string) $this->input->get('tanggal'));
        $ket_status = trim((string) $this->input->get('ket_status'));
        if (!in_array($ket_status, ['LK', 'KK'], true)) {
            $ket_status = null;
        }

        $rows = $this->M_Distribusi->total_kirim_do($tanggal, $ket_status);

        $ps = new PhpSpreadsheetLib();
        $spreadsheet = $ps->spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'Rute');
        $sheet->setCellValue('B1', 'Total Faktur');
        $sheet->setCellValue('C1', 'Faktur Terkirim');
        $sheet->setCellValue('D1', 'Faktur Pending');

        $row = 2;
        foreach ($rows as $r) {
            $sheet->setCellValueByColumnAndRow(1, $row, $r->rute);
            $sheet->setCellValueByColumnAndRow(2, $row, $r->total_faktur);
            $sheet->setCellValueByColumnAndRow(3, $row, $r->total_faktur_terkirim);
            $sheet->setCellValueByColumnAndRow(4, $row, $r->total_faktur_pending);
            $row++;
        }

        $filename = 'total_kirim_do_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = $ps->writer($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
