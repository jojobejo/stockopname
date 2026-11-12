<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Stockopname extends CI_Controller
{
    public function is_superadmin()
    {
        $username = strtolower(trim((string)$this->session->userdata('username')));
        $jobdesk = strtoupper(trim((string)$this->session->userdata('jobdesk')));
        return ($username === 'superadmin' || $jobdesk === 'SUPERADMIN');
    }

    public function is_stockopname()
    {
        $username = strtolower(trim((string)$this->session->userdata('username')));
        $jobdesk = strtoupper(trim((string)$this->session->userdata('jobdesk')));
        return ($username === 'stockopname' || $jobdesk === 'STOCKOPNAME');
    }

    private function require_superadmin_master_opname()
    {
        if (!$this->is_superadmin() && !$this->is_stockopname()) {
            if ($this->input->is_ajax_request() || strtoupper((string)$this->input->method(true)) === 'POST') {
                $this->json(false, 'Akses ditolak. Data Master Opname hanya dapat diakses oleh Superadmin dan User Stockopname.');
                exit;
            }
            show_error('Anda tidak memiliki akses ke Data Master Opname.', 403, 'Akses Ditolak');
            exit;
        }
    }

    public function __construct()
    {
        parent::__construct();
        $this->load->model('admin/M_Stockopname', 'stockopname');
        $this->load->library('Karisma_code_generator');
        $this->stockopname->set_pending_calculation_mode($this->session->userdata('stockopname_pending_mode'));
        $this->guard();
    }

    private function guard()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('Auth');
        }

        $lvuser = (int)$this->session->userdata('lv');
        $jobdesk = $this->session_jobdesk_code();
        $username = strtolower(trim((string)$this->session->userdata('username')));
        $isStockopname = ($username === 'stockopname' || $jobdesk === 'STOCKOPNAME');
        $isAdminDashboard = (bool)$this->session->userdata('is_admin_dashboard') || in_array($username, ['admin', 'admin1', 'superadmin', 'stockopname'], true) || ($lvuser === 1 && $jobdesk === 'ADMIN') || $isStockopname;

        $method = $this->router->fetch_method();
        $inputMethods = [
            'input_opname',
            'history_input',
            'ajax_delete_history_input',
            'ajax_input_lookup',
            'ajax_input_save',
            'ajax_manual_barang',
            'ajax_manual_lot',
            'ajax_manual_expired',
            'ajax_manual_save',
            'ajax_request_save',
        ];
        $isStockopnameInputer = $jobdesk === 'STOCKOPNAME' && in_array($method, $inputMethods, true);
        $supervisorMethods = [
            'supervisor_opname',
            'supervisor_tracking',
            'ajax_supervisor_tracking_list',
            'ajax_input_lookup',
            'ajax_manual_barang',
            'ajax_manual_expired',
            'ajax_request_save',
        ];
        $isSupervisorOpname = $jobdesk === 'SUPERVISOR_OPNAME' && in_array($method, $supervisorMethods, true);

        if (!$isAdminDashboard && !$isStockopnameInputer && !$isSupervisorOpname) {
            show_error('Anda tidak memiliki akses ke dashboard admin stockopname.', 403, 'Akses Ditolak');
        }
    }

    private function json($status, $message, $data = [])
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'status' => (bool)$status,
                'message' => $message,
                'data' => $data,
                'csrf' => isset($this->config) && $this->config->item('csrf_protection') ? ['name'=>$this->security->get_csrf_token_name(),'hash'=>$this->security->get_csrf_hash()] : null,
            ]));
    }

    private function post()
    {
        return $this->input->post(null, true) ?: [];
    }

    private function raw_json($data)
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    private function numeric_value($value)
    {
        return str_replace(',', '.', trim((string)$value));
    }

    private function valid_date($value)
    {
        $date = DateTime::createFromFormat('Y-m-d', (string)$value);
        return $date && $date->format('Y-m-d') === $value;
    }

    private function master_action_token()
    {
        $token = $this->session->userdata('master_action_token');
        if (!$token) { $token = bin2hex(random_bytes(32)); $this->session->set_userdata('master_action_token', $token); }
        return $token;
    }

    private function master_action_allowed()
    {
        return strtoupper((string)$this->input->method(true)) === 'POST'
            && hash_equals($this->master_action_token(), (string)$this->input->post('action_token', true));
    }

    private function clean_asset_filename($value)
    {
        $value = preg_replace('/[^A-Za-z0-9_-]+/', '_', trim((string)$value));
        $value = trim($value, '_') ?: 'barang';
        return substr($value, 0, 80);
    }

    private function qrcode_scan_value($row)
    {
        return 'OP|' . trim((string)($row['kode_barang'] ?? '')) . '|' . (int)($row['id'] ?? 0);
    }

    private function qrcode_relative_path($row)
    {
        $safeCode = $this->clean_asset_filename($row['kode_barang'] ?? '');
        return 'assets/qrcode/stockopname/OP_' . $safeCode . '_' . (int)($row['id'] ?? 0) . '.png';
    }

    private function qrcode_percent($total, $remaining)
    {
        $total = (int)$total;
        if ($total <= 0) {
            return 100;
        }

        return max(0, min(100, (int)round((($total - (int)$remaining) / $total) * 100)));
    }

    private function asset_payload($path)
    {
        $path = trim((string)$path);
        $exists = $path !== '' && is_file(FCPATH . $path);

        return [
            'path' => $path,
            'url' => $exists ? base_url($path) : '',
            'exists' => $exists,
        ];
    }

    private function tanggal_indo($date = null)
    {
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];

        $timestamp = $date ? strtotime((string)$date) : time();
        if (!$timestamp) {
            $timestamp = time();
        }

        return date('d', $timestamp) . ' ' . $months[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp);
    }

    private function excel_status_label($status)
    {
        $labels = [
            'all_match' => 'All Match',
            'tim_1' => 'Tim 1 Match',
            'tim_2' => 'Tim 2 Match',
            'not_match' => 'Tidak Match',
            're_check' => 'Re-Check',
        ];

        return $labels[$status] ?? (string)$status;
    }

    private function excel_date_value($value)
    {
        $value = trim((string)$value);
        if ($value === '' || $value === '0000-00-00') {
            return '-';
        }

        return $value;
    }

    private function excel_column_letter($index)
    {
        $index = (int)$index;
        $letter = '';
        while ($index > 0) {
            $index--;
            $letter = chr(65 + ($index % 26)) . $letter;
            $index = (int)floor($index / 26);
        }

        return $letter ?: 'A';
    }

    private function excel_fill_sheet($sheet, $title, $headers, $rows)
    {
        $sheet->setTitle(substr($title, 0, 31));

        $column = 1;
        foreach ($headers as $header) {
            $sheet->setCellValueByColumnAndRow($column, 1, $header);
            $column++;
        }

        $rowNumber = 2;
        foreach ($rows as $row) {
            $column = 1;
            foreach (array_keys($headers) as $key) {
                $sheet->setCellValueByColumnAndRow($column, $rowNumber, $row[$key] ?? '');
                $column++;
            }
            $rowNumber++;
        }

        $lastColumn = count($headers);
        $lastRow = max(1, $rowNumber - 1);
        $lastColumnLetter = $this->excel_column_letter($lastColumn);

        $sheet->getStyle('A1:' . $lastColumnLetter . '1')->getFont()->setBold(true);
        $sheet->getStyle('A1:' . $lastColumnLetter . '1')->getFill()
            ->setFillType('solid')
            ->getStartColor()->setARGB('FFE8EEF7');
        $sheet->getStyle('A1:' . $lastColumnLetter . $lastRow)->getBorders()->getAllBorders()
            ->setBorderStyle('thin');
        $sheet->setAutoFilter('A1:' . $lastColumnLetter . $lastRow);
        $sheet->freezePane('A2');

        for ($i = 1; $i <= $lastColumn; $i++) {
            $sheet->getColumnDimensionByColumn($i)->setAutoSize(true);
        }
    }

    private function excel_output_html($filename, $sections)
    {
        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        echo '<html><head><meta charset="utf-8"></head><body>';
        foreach ($sections as $section) {
            echo '<h3>' . htmlspecialchars($section['title'], ENT_QUOTES, 'UTF-8') . '</h3>';
            echo '<table border="1">';
            echo '<thead><tr>';
            foreach ($section['headers'] as $header) {
                echo '<th style="background:#e8eef7;font-weight:bold;">' . htmlspecialchars($header, ENT_QUOTES, 'UTF-8') . '</th>';
            }
            echo '</tr></thead><tbody>';
            foreach ($section['rows'] as $row) {
                echo '<tr>';
                foreach (array_keys($section['headers']) as $key) {
                    echo '<td>' . htmlspecialchars((string)($row[$key] ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table><br>';
        }
        echo '</body></html>';
        exit;
    }

    private function delete_qrcode_files($paths)
    {
        $root = realpath(FCPATH);
        $deleted = 0;
        $failed = 0;

        foreach (array_unique($paths) as $path) {
            $path = trim((string)$path);
            if ($path === '' || preg_match('#^[a-z][a-z0-9+.-]*://#i', $path)) {
                continue;
            }

            $candidate = $path[0] === DIRECTORY_SEPARATOR ? $path : FCPATH . $path;
            $realFile = realpath($candidate);
            if (!$root || !$realFile || !is_file($realFile)) {
                continue;
            }

            if (strpos($realFile, $root . DIRECTORY_SEPARATOR) !== 0) {
                $failed++;
                continue;
            }

            if (@unlink($realFile)) {
                $deleted++;
            } else {
                $failed++;
            }
        }

        return [
            'deleted' => $deleted,
            'failed' => $failed,
        ];
    }

    public function index()
    {
        $data['page_title'] = 'KARISMA ERP - Admin Stockopname';
        $data['pending_summary'] = $this->stockopname->pending_summary();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_dashboard.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function monitoring()
    {
        $data['page_title'] = 'KARISMA ERP - Opname Monitoring';
        $data['monitoring_summary'] = $this->stockopname->monitoring_summary();
        $data['pending_summary'] = $this->stockopname->pending_summary();
        $data['pending_mode'] = $this->stockopname->pending_calculation_mode();
        $data['activity_logs'] = $this->stockopname->monitoring_activity(5);

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_monitoring.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    private function session_jobdesk_code()
    {
        $jobdesk = strtoupper(trim((string)$this->session->userdata('jobdesk')));
        $jobdesk = str_replace(['-', ' '], '_', $jobdesk);
        return $jobdesk === 'SUPERVISIOR_OPNAME' ? 'SUPERVISOR_OPNAME' : $jobdesk;
    }

    public function monitoring_pending_opname()
    {
        $keyword = trim((string)$this->input->get('keyword', true));
        $perPage = 10;
        $page = (int)$this->input->get('page', true);
        $page = $page > 0 ? $page : 1;
        $totalRows = $this->stockopname->pending_count($keyword);
        $totalPages = max(1, (int)ceil($totalRows / $perPage));
        $page = min($page, $totalPages);

        $data['page_title'] = 'KARISMA ERP - Detail Pending Opname';
        $data['keyword'] = $keyword;
        $data['summary'] = $this->stockopname->pending_summary();
        $data['pending_mode'] = $this->stockopname->pending_calculation_mode();
        $data['pending_rows'] = $this->stockopname->pending_rows($keyword, $perPage, ($page - 1) * $perPage);
        $data['pagination'] = [
            'page' => $page,
            'per_page' => $perPage,
            'total_rows' => $totalRows,
            'total_pages' => $totalPages,
        ];

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_pending_opname_detail.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function ajax_set_pending_calculation_mode()
    {
        $mode = $this->stockopname->normalize_pending_calculation_mode($this->input->post('mode', true));
        $this->session->set_userdata('stockopname_pending_mode', $mode);
        $this->stockopname->set_pending_calculation_mode($mode);
        $sync = $this->stockopname->resync_all_pending();

        $this->json(true, 'Mode perhitungan pending berhasil disimpan.', [
            'mode' => $mode,
            'sync' => $sync,
            'pending_summary' => $this->stockopname->pending_summary(),
        ]);
    }

    public function monitoring_activity_log()
    {
        $wilayah = trim((string)$this->input->get('wilayah', true));
        $tim = (int)$this->input->get('tim', true);
        $tim = in_array($tim, [1, 2], true) ? $tim : 0;

        $data['page_title'] = 'KARISMA ERP - Log Aktifitas Stock Opname';
        $data['selected_wilayah'] = $wilayah;
        $data['selected_tim'] = $tim;
        $data['wilayah_options'] = $this->stockopname->monitoring_activity_wilayah_options();
        $data['activity_logs'] = $this->stockopname->monitoring_activity_log($wilayah, $tim, 300);
        $data['activity_compare_rows'] = $this->stockopname->monitoring_activity_compare_tim($wilayah, 300);

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_activity_log.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function monitoring_request_opname()
    {
        $data['page_title'] = 'KARISMA ERP - Request Opname User';
        $requestTab = $this->input->get('tab', true);
        $requestTab = $requestTab === 'affirmed' ? 'affirmed' : 'pending';
        $filters = [
            'tim' => (int)$this->input->get('tim'),
            'wilayah' => trim((string)$this->input->get('wilayah', true)),
            'input_by' => trim((string)$this->input->get('input_by', true)),
        ];
        $requestLogsAll = array_merge(
            $this->stockopname->monitoring_request_opname_rows(1000),
            $this->stockopname->monitoring_affirmed_request_opname_rows(1000)
        );
        $data['filters'] = $filters;
        $data['request_active_tab'] = $requestTab;
        $data['request_pending_count'] = $this->stockopname->monitoring_request_opname_count_by_status('Request Master Item', $filters);
        $data['request_affirmed_count'] = $this->stockopname->monitoring_request_opname_count_by_status('DONE', $filters);
        $data['request_logs'] = $requestTab === 'affirmed'
            ? $this->stockopname->monitoring_affirmed_request_opname_rows(500, $filters)
            : $this->stockopname->monitoring_request_opname_rows(500, $filters);
        $data['request_tim_options'] = [1, 2];
        $data['request_wilayah_options'] = array_values(array_unique(array_filter(array_map(static function ($row) {
            return trim((string)($row['wilayah'] ?? ''));
        }, $requestLogsAll))));
        sort($data['request_wilayah_options']);
        $data['request_inputer_options'] = array_values(array_unique(array_filter(array_map(static function ($row) {
            return trim((string)($row['requested_by'] ?? ''));
        }, $requestLogsAll))));
        sort($data['request_inputer_options']);

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_request_opname.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function ajax_affirm_request_opname_bulk()
    {
        $input = $this->post();
        $requestIds = $input['request_ids'] ?? [];
        if (!is_array($requestIds)) {
            $requestIds = [$requestIds];
        }

        $actor = $this->session->userdata('nama') ?: $this->session->userdata('username') ?: $this->session->userdata('nik') ?: 'system';
        $result = $this->stockopname->affirm_request_opname_bulk($requestIds, $actor);
        $this->json($result['status'], $result['message'], $result['data'] ?? []);
    }

    public function monitoring_manual_opname()
    {
        $data['page_title'] = 'KARISMA ERP - Input Manual Opname User';
        $manualLogsAll = $this->stockopname->monitoring_manual_opname_rows(500);
        $filters = [
            'tim' => (int)$this->input->get('tim'),
            'wilayah' => trim((string)$this->input->get('wilayah', true)),
            'input_by' => trim((string)$this->input->get('input_by', true)),
        ];
        $data['filters'] = $filters;
        $data['manual_logs'] = $this->stockopname->monitoring_manual_opname_rows(500, $filters);
        $data['manual_tim_options'] = [1, 2];
        $data['manual_wilayah_options'] = array_values(array_unique(array_filter(array_map(static function ($row) {
            return trim((string)($row['wilayah'] ?? ''));
        }, $manualLogsAll))));
        sort($data['manual_wilayah_options']);
        $data['manual_inputer_options'] = array_values(array_unique(array_filter(array_map(static function ($row) {
            return trim((string)($row['input_by'] ?? ''));
        }, $manualLogsAll))));
        sort($data['manual_inputer_options']);

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_manual_opname.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function ajax_affirm_manual_opname_bulk()
    {
        $input = $this->post();
        $manualMasterIds = $input['manual_master_ids'] ?? [];
        if (!is_array($manualMasterIds)) {
            $manualMasterIds = [$manualMasterIds];
        }

        $actor = $this->session->userdata('nama') ?: $this->session->userdata('username') ?: $this->session->userdata('nik') ?: 'system';
        $result = $this->stockopname->affirm_manual_opname_bulk($manualMasterIds, $actor);
        $this->json($result['status'], $result['message'], $result['data'] ?? []);
    }

    public function monitoring_summary()
    {
        $summary = $this->stockopname->monitoring_summary();
        $summary['pending_summary'] = $this->stockopname->pending_summary();
        $this->json(true, 'Summary monitoring stockopname berhasil dimuat.', $summary);
    }

    public function monitoring_activity()
    {
        $this->json(true, 'Log aktifitas opname berhasil dimuat.', $this->stockopname->monitoring_activity(5));
    }

    public function monitoring_compare_all()
    {
        $this->raw_json($this->stockopname->monitoring_compare_all_datatable($this->post()));
    }

    public function monitoring_compare_lot()
    {
        $this->raw_json($this->stockopname->monitoring_compare_lot_datatable($this->post()));
    }

    public function monitoring_export_excel($type = 'compare-all')
    {
        $type = strtolower(trim((string)$type));
        $section = null;

        if ($type === 'compare-all') {
            $rows = array_map(function ($row) {
                return [
                    'kode_barang' => $row['kode_barang'] ?? '',
                    'nama_barang' => $row['nama_barang'] ?? '',
                    'qty_buku' => (int)($row['qty_buku'] ?? 0),
                    'qty_tim_1' => (int)($row['qty_tim_1'] ?? 0),
                    'qty_tim_2' => (int)($row['qty_tim_2'] ?? 0),
                    'status_opname' => $this->excel_status_label($row['status_opname'] ?? ''),
                    'input_tim_1' => (int)($row['input_tim_1'] ?? 0),
                    'input_tim_2' => (int)($row['input_tim_2'] ?? 0),
                    'inputers' => $row['inputers'] ?? '-',
                    'wilayah' => $row['wilayah'] ?? '-',
                    'last_input' => $this->excel_date_value($row['last_input'] ?? ''),
                ];
            }, $this->stockopname->monitoring_compare_all_export_rows());

            $section = [
                'title' => 'Compare Stock Buku vs Stock Opname - All Barang',
                'headers' => [
                    'kode_barang' => 'Kode Barang',
                    'nama_barang' => 'Nama Barang',
                    'qty_buku' => 'Stock Buku',
                    'qty_tim_1' => 'Qty Tim 1',
                    'qty_tim_2' => 'Qty Tim 2',
                    'status_opname' => 'Status',
                    'input_tim_1' => 'Input Tim 1',
                    'input_tim_2' => 'Input Tim 2',
                    'inputers' => 'Inputers',
                    'wilayah' => 'Wilayah',
                    'last_input' => 'Input Terakhir',
                ],
                'rows' => $rows,
                'sheet' => 'Compare All Barang',
                'filename' => 'Compare Stock Buku vs Stock Opname - All Barang',
            ];
        } elseif ($type === 'compare-expired') {
            $rows = array_map(function ($row) {
                return [
                    'kode_barang' => $row['kode_barang'] ?? '',
                    'nama_barang' => $row['nama_barang'] ?? '',
                    'expired_date' => $this->excel_date_value($row['expired_date'] ?? ''),
                    'qty_buku' => (int)($row['qty_buku'] ?? 0),
                    'qty_tim_1' => (int)($row['qty_tim_1'] ?? 0),
                    'qty_tim_2' => (int)($row['qty_tim_2'] ?? 0),
                    'status_opname' => $this->excel_status_label($row['status_opname'] ?? ''),
                    'input_tim_1' => (int)($row['input_tim_1'] ?? 0),
                    'input_tim_2' => (int)($row['input_tim_2'] ?? 0),
                    'inputers' => $row['inputers'] ?? '-',
                    'wilayah' => $row['wilayah'] ?? '-',
                    'last_input' => $this->excel_date_value($row['last_input'] ?? ''),
                ];
            }, $this->stockopname->monitoring_compare_lot_export_rows());

            $section = [
                'title' => 'Compare Stock Buku vs Stock Opname - By Expired Date',
                'headers' => [
                    'kode_barang' => 'Kode Barang',
                    'nama_barang' => 'Nama Barang',
                    'expired_date' => 'Expired Date',
                    'qty_buku' => 'Stock Buku',
                    'qty_tim_1' => 'Qty Tim 1',
                    'qty_tim_2' => 'Qty Tim 2',
                    'status_opname' => 'Status',
                    'input_tim_1' => 'Input Tim 1',
                    'input_tim_2' => 'Input Tim 2',
                    'inputers' => 'Inputers',
                    'wilayah' => 'Wilayah',
                    'last_input' => 'Input Terakhir',
                ],
                'rows' => $rows,
                'sheet' => 'Compare Expired Date',
                'filename' => 'Compare Stock Buku vs Stock Opname - By Expired Date',
            ];
        } elseif ($type === 'master-all') {
            $rows = array_map(function ($row) {
                return [
                    'nama_barang' => $row['nama_barang'] ?? '',
                    'qty_all_barang' => (int)($row['qty_all_barang'] ?? 0),
                ];
            }, $this->stockopname->monitoring_master_opname_all_export_rows());

            $section = [
                'title' => 'Data master Opname All Barang',
                'headers' => [
                    'nama_barang' => 'Nama Barang',
                    'qty_all_barang' => 'Qty All Barang',
                ],
                'rows' => $rows,
                'sheet' => 'Master All Barang',
                'filename' => 'Data master Opname All Barang',
            ];
        } elseif ($type === 'master-expired') {
            $rows = array_map(function ($row) {
                return [
                    'nama_barang' => $row['nama_barang'] ?? '',
                    'expired_date' => $this->excel_date_value($row['expired_date'] ?? ''),
                    'qty_all_expired_date' => (int)($row['qty_all_expired_date'] ?? 0),
                ];
            }, $this->stockopname->monitoring_master_opname_expired_export_rows());

            $section = [
                'title' => 'Data Master Opname Barang with Expired Date',
                'headers' => [
                    'nama_barang' => 'Nama Barang',
                    'expired_date' => 'Expired Date',
                    'qty_all_expired_date' => 'Qty All Expired Date',
                ],
                'rows' => $rows,
                'sheet' => 'Master Expired Date',
                'filename' => 'Data Master Opname Barang with Expired Date',
            ];
        } elseif ($type === 'opname-input') {
            $rows = array_map(function ($row) {
                return [
                    'kode_barang' => $row['kode_barang'] ?? '',
                    'nama_barang' => $row['nama_barang'] ?? '',
                    'expired_date' => $this->excel_date_value($row['expired_date'] ?? ''),
                    'qty' => (int)($row['qty'] ?? 0),
                    'qty_pcs' => (int)($row['qty_pcs'] ?? 0),
                    'qty_box' => (int)($row['qty_box'] ?? 0),
                    'input_by' => $row['input_by'] ?? '',
                    'wilayah' => $row['wilayah'] ?? '',
                    'tim_opname' => $row['tim_opname'] ?? '',
                ];
            }, $this->stockopname->monitoring_opname_export_rows());

            $section = [
                'title' => 'Data Opname',
                'headers' => [
                    'kode_barang' => 'Kode Barang',
                    'nama_barang' => 'Nama Barang',
                    'expired_date' => 'Expired Date',
                    'qty' => 'Qty',
                    'qty_pcs' => 'Qty Pcs',
                    'qty_box' => 'Qty Box',
                    'input_by' => 'Input By',
                    'wilayah' => 'Wilayah',
                    'tim_opname' => 'Tim Opname',
                ],
                'rows' => $rows,
                'sheet' => 'Data Opname',
                'filename' => 'Data Opname',
            ];
        }

        if (!$section) {
            show_404();
            return;
        }

        $sections = [$section];
        $safeFilename = preg_replace('/[^A-Za-z0-9_-]+/', '_', $section['filename']);
        $safeFilename = trim($safeFilename, '_') ?: 'stockopname_export';

        if (!is_file(APPPATH . 'third_party/PhpSpreadsheet/src/Bootstrap.php')) {
            $this->excel_output_html($safeFilename . '_' . date('Ymd_His') . '.xls', $sections);
        }

        require_once APPPATH . 'libraries/PhpSpreadsheet.php';

        $ps = new PhpSpreadsheetLib();
        $spreadsheet = $ps->spreadsheet();

        foreach ($sections as $index => $section) {
            $sheet = $index === 0 ? $spreadsheet->getActiveSheet() : $spreadsheet->createSheet();
            $this->excel_fill_sheet($sheet, $section['sheet'], $section['headers'], $section['rows']);
        }

        $spreadsheet->setActiveSheetIndex(0);

        while (ob_get_level()) {
            ob_end_clean();
        }

        $filename = $safeFilename . '_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $writer = $ps->writer($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    public function detail_input_opname($key = '')
    {
        $namaBarang = trim((string)$this->input->get('nama_barang', true));
        $kodeBarang = trim((string)$this->input->get('kode_barang', true));
        $itemKey = $key ?: ($namaBarang ?: $kodeBarang);
        $itemKey = rawurldecode(trim((string)$itemKey));
        if ($itemKey === '') {
            show_404();
        }

        $data['page_title'] = 'KARISMA ERP - Detail Input Opname';
        $data['compare'] = $this->stockopname->monitoring_compare_all_detail($itemKey);
        $data['master_items'] = $this->stockopname->lot_compare_by_kode_barang($itemKey);
        $data['master_item_options'] = $this->stockopname->master_item_options_by_kode_barang($itemKey);
        $data['pending_totals'] = $this->stockopname->pending_totals_by_kode_barang($itemKey);
        $data['input_rows'] = $this->stockopname->input_opname_by_kode_barang($itemKey);
        $data['recycle_rows'] = $this->stockopname->recycle_input_by_kode_barang($itemKey);
        $data['request_rows'] = $this->stockopname->request_item_by_kode_barang($itemKey);
        $data['edit_logs'] = $this->stockopname->opname_edit_logs_by_kode_barang($itemKey);
        $data['pending_mode'] = $this->stockopname->pending_calculation_mode();

        $realNamaBarang = $data['compare']['nama_barang'] ?? ($data['master_items'][0]['nama_barang'] ?? ($data['input_rows'][0]['nama_barang'] ?? $itemKey));
        $realKodeBarang = $data['compare']['kode_barang'] ?? ($data['master_items'][0]['kode_barang'] ?? ($data['input_rows'][0]['kode_barang'] ?? ''));
        $data['nama_barang'] = $realNamaBarang;
        $data['kode_barang'] = $realKodeBarang ?: $itemKey;

        if (empty($data['compare']) && empty($data['master_items']) && empty($data['input_rows'])) {
            show_error('Data input opname untuk barang ' . html_escape($itemKey) . ' tidak ditemukan.', 404, 'Data Tidak Ditemukan');
        }

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_detail_input_opname.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function ajax_update_input_opname()
    {
        $input = $this->post();
        $id = $input['id'] ?? '';
        $kodeBarang = trim((string)($input['kode_barang'] ?? ''));

        if (!ctype_digit((string)$id) || (int)$id <= 0) {
            return $this->json(false, 'ID input opname tidak valid.');
        }

        if ($kodeBarang === '') {
            return $this->json(false, 'Kode barang tidak valid.');
        }

        $qtyBox = $this->numeric_value($input['qty_box'] ?? '0');
        $qtyPcs = $this->numeric_value($input['qty_pcs'] ?? '0');
        foreach (['Qty box' => $qtyBox, 'Qty pcs' => $qtyPcs] as $label => $value) {
            if ($value === '' || !ctype_digit((string)$value)) {
                return $this->json(false, $label . ' harus berupa angka bulat 0 atau lebih.');
            }
        }

        $payload = [
            'qty_box' => (int)$qtyBox,
            'qty_pcs' => (int)$qtyPcs,
        ];

        $actor = $this->session->userdata('nama') ?: $this->session->userdata('username') ?: $this->session->userdata('nik') ?: 'system';
        $actionType = strtoupper(trim((string)($input['action_type'] ?? 'EDIT_QTY')));
        if (!in_array($actionType, ['EDIT_QTY', 'ADJUSTMENT'], true)) {
            return $this->json(false, 'Jenis perubahan input opname tidak valid.');
        }

        $updated = $this->stockopname->update_input_opname((int)$id, $kodeBarang, $payload, $actor, $actionType);
        if (!$updated['status']) {
            return $this->json(false, $updated['message'] ?? 'Gagal update data input opname.');
        }

        $this->json(true, 'Data input opname berhasil diperbarui.', $updated['data'] ?? []);
    }

    public function ajax_delete_input_opname()
    {
        $input = $this->post();
        $id = $input['id'] ?? '';
        $kodeBarang = trim((string)($input['kode_barang'] ?? ''));
        if (!ctype_digit((string)$id) || (int)$id <= 0 || $kodeBarang === '') {
            return $this->json(false, 'Data input opname tidak valid.');
        }

        $actor = $this->session->userdata('nama') ?: $this->session->userdata('username') ?: $this->session->userdata('nik') ?: 'system';
        $result = $this->stockopname->delete_input_opname((int)$id, $kodeBarang, $actor);
        $this->json($result['status'], $result['message'], $result['data'] ?? []);
    }

    public function ajax_repost_input_opname()
    {
        $input = $this->post();
        $id = $input['id'] ?? '';
        $kodeBarang = trim((string)($input['kode_barang'] ?? ''));
        if (!ctype_digit((string)$id) || (int)$id <= 0 || $kodeBarang === '') {
            return $this->json(false, 'Data recycle bin tidak valid.');
        }

        $actor = $this->session->userdata('nama') ?: $this->session->userdata('username') ?: $this->session->userdata('nik') ?: 'system';
        $result = $this->stockopname->repost_input_opname((int)$id, $kodeBarang, $actor);
        $this->json($result['status'], $result['message'], $result['data'] ?? []);
    }

    public function ajax_add_request_item()
    {
        $input = $this->post();
        $kodeBarang = trim((string)($input['kode_barang'] ?? ''));
        $expiredDate = $this->normalize_request_expired_date($input['expired_date'] ?? '');
        $noLot = trim((string)($input['no_lot'] ?? ''));
        if ($noLot === '') { return $this->json(false, 'Nomor lot wajib tersedia. Muat ulang halaman.'); }
        if ($kodeBarang === '' || $expiredDate === '') {
            return $this->json(false, 'Data request item tidak valid.');
        }

        $actor = $this->session->userdata('nama') ?: $this->session->userdata('username') ?: $this->session->userdata('nik') ?: 'system';
        $result = $this->stockopname->add_request_item_to_opname($kodeBarang, $expiredDate, $noLot, [
            'wilayah' => (int)($this->session->userdata('wilayah') ?: 0),
            'manual_master_id' => (int)($input['manual_master_id'] ?? 0),
        ], $actor);
        $this->json($result['status'], $result['message'], $result['data'] ?? []);
    }

    public function ajax_delete_request_item()
    {
        $input = $this->post();
        $kodeBarang = trim((string)($input['kode_barang'] ?? ''));
        $expiredDate = $this->normalize_request_expired_date($input['expired_date'] ?? '');
        $noLot = trim((string)($input['no_lot'] ?? ''));
        if ($noLot === '') { return $this->json(false, 'Nomor lot wajib tersedia. Muat ulang halaman.'); }
        if ($kodeBarang === '' || $expiredDate === '') {
            return $this->json(false, 'Data request item tidak valid.');
        }

        $actor = $this->session->userdata('nama') ?: $this->session->userdata('username') ?: $this->session->userdata('nik') ?: 'system';
        $result = $this->stockopname->delete_request_item_group($kodeBarang, $expiredDate, $noLot, $actor, (int)($input['manual_master_id'] ?? 0));
        $this->json($result['status'], $result['message'], $result['data'] ?? []);
    }

    public function ajax_add_input_opname_detail()
    {
        $input = $this->post();
        $kodeBarang = trim((string)($input['kode_barang'] ?? ''));
        $masterId = $input['master_id'] ?? '';
        $timOpname = (int)($input['tim_opname'] ?? 0);

        if ($kodeBarang === '') {
            return $this->json(false, 'Kode barang tidak valid.');
        }
        if (!ctype_digit((string)$masterId) || (int)$masterId <= 0) {
            return $this->json(false, 'Pilih expired date terlebih dahulu.');
        }
        if (!in_array($timOpname, [1, 2], true)) {
            return $this->json(false, 'Tim opname harus Tim 1 atau Tim 2.');
        }

        $qtyBox = $this->numeric_value($input['qty_box'] ?? '0');
        $qtyPcs = $this->numeric_value($input['qty_pcs'] ?? '0');
        $qtyBox = $qtyBox === '' ? '0' : $qtyBox;
        $qtyPcs = $qtyPcs === '' ? '0' : $qtyPcs;
        foreach (['Qty box' => $qtyBox, 'Qty pcs' => $qtyPcs] as $label => $value) {
            if ($value === '' || !ctype_digit((string)$value)) {
                return $this->json(false, $label . ' harus berupa angka bulat 0 atau lebih.');
            }
        }
        if (((int)$qtyBox + (int)$qtyPcs) <= 0) {
            return $this->json(false, 'Isi Qty Box atau Qty PCS terlebih dahulu.');
        }

        $keterangan = trim((string)($input['keterangan'] ?? ''));
        if ($keterangan === '') {
            return $this->json(false, 'Keterangan wajib diisi.');
        }

        $this->stockopname->ensure_master_code_columns();
        $row = $this->stockopname->get_master_barang_by_id((int)$masterId);
        if (!$row || (string)($row['kode_barang'] ?? '') !== $kodeBarang) {
            return $this->json(false, 'Data master barang tidak ditemukan untuk kode barang ini.');
        }

        $inputBy = $this->session->userdata('username') ?: $this->session->userdata('nama') ?: $this->session->userdata('nik') ?: 'system';
        $sourceUsername = preg_replace('/[^A-Za-z0-9]+/', '_', strtolower(trim((string)$inputBy)));
        $sourceKeterangan = preg_replace('/[^A-Za-z0-9]+/', '_', strtolower($keterangan));
        $inputSource = trim($sourceUsername, '_') . '_manual_' . trim($sourceKeterangan, '_');
        $inputSource = substr($inputSource, 0, 50);

        $saved = $this->stockopname->save_mobile_opname($row, [
            'qty_pcs' => (int)$qtyPcs,
            'qty_box' => (int)$qtyBox,
            'input_by' => $inputBy,
            'wilayah' => $this->session->userdata('wilayah') ?: 0,
            'tim_opname' => $timOpname,
            'input_source' => $inputSource,
        ]);

        if (!$saved) {
            return $this->json(false, 'Gagal menyimpan data input opname.');
        }

        $this->json(true, 'Input opname berhasil ditambahkan.', [
            'id' => $saved,
            'kode_barang' => $row['kode_barang'],
            'nama_barang' => $row['nama_barang'],
            'expired_date' => $row['expired_date'],
            'no_lot' => $row['no_lot'],
            'qty_box' => (int)$qtyBox,
            'qty_pcs' => (int)$qtyPcs,
        ]);
    }

    public function ajax_update_detail_dimensi()
    {
        $input = $this->post();
        $kodeBarang = trim((string)($input['kode_barang'] ?? ''));
        $dimensi = $this->numeric_value($input['dimensi'] ?? '');

        if ($kodeBarang === '') {
            return $this->json(false, 'Kode barang tidak valid.');
        }
        if ($dimensi === '' || !ctype_digit((string)$dimensi)) {
            return $this->json(false, 'Dimensi harus berupa angka bulat 0 atau lebih.');
        }

        $updated = $this->stockopname->update_dimensi_by_kode_barang($kodeBarang, (int)$dimensi);
        if (!$updated) {
            return $this->json(false, 'Gagal memperbarui dimensi barang.');
        }

        $this->json(true, 'Dimensi barang berhasil diperbarui.', [
            'kode_barang' => $kodeBarang,
            'dimensi' => (int)$dimensi,
        ]);
    }

    public function ajax_delete_master_item_detail()
    {
        $input = $this->post();
        $kodeBarang = trim((string)($input['kode_barang'] ?? ''));
        $expiredDate = $this->normalize_request_expired_date($input['expired_date'] ?? '');
        $noLot = trim((string)($input['no_lot'] ?? ''));
        if ($noLot === '') { return $this->json(false, 'Nomor lot wajib tersedia. Muat ulang halaman.'); }

        if ($kodeBarang === '') {
            return $this->json(false, 'Kode barang tidak valid.');
        }
        if ($expiredDate === '') {
            return $this->json(false, 'Expired date stock buku tidak valid.');
        }

        $result = $this->stockopname->delete_master_item_by_lot($kodeBarang, $expiredDate, $noLot);
        $this->json($result['status'], $result['message'], $result['data'] ?? []);
    }

    public function widgets()
    {
        $summary = $this->stockopname->summary();
        $expiredLotResult = $this->stockopname->expired_lot_result_summary();
        $this->json(true, 'Data dashboard stockopname berhasil dimuat.', [
            'summary' => $summary,
            'master_barang' => $this->stockopname->master_barang_summary(),
            'pending_summary' => $this->stockopname->pending_summary(),
            'all_barang_result' => $this->stockopname->all_barang_result_summary(),
            'expired_lot_result' => $expiredLotResult,
            'fefo_result' => $expiredLotResult,
        ]);
    }

    public function list()
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($this->stockopname->datatable($this->post())));
    }

    public function demo_preview()
    {
        $input = $this->post();
        if (trim((string)($input['kode_barang'] ?? '')) === '' || trim((string)($input['nama_barang'] ?? '')) === '') {
            return $this->json(false, 'Kode barang dan nama barang wajib diisi.');
        }

        if (!is_numeric($input['qty_buku'] ?? null) || !is_numeric($input['qty_fisik'] ?? null)) {
            return $this->json(false, 'Qty sistem dan qty fisik harus berupa angka.');
        }

        $this->json(true, 'Simulasi stockopname berhasil dihitung.', $this->stockopname->demo_preview($input));
    }

    public function input_opname()
    {
        if ($this->session_jobdesk_code() === 'SUPERVISOR_OPNAME') {
            return $this->supervisor_opname();
        }

        $data['page_title'] = 'KARISMA ERP - Input Stockopname';
        $wilayah = $this->stockopname->wilayah_by_id($this->session->userdata('wilayah'));
        $data['nama_wilayah'] = $wilayah['nama_wilayah'] ?? '-';
        $this->stockopname->ensure_master_code_columns();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_input_mobile.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function history_input()
    {
        $inputBy = $this->session->userdata('nama') ?: $this->session->userdata('username') ?: $this->session->userdata('nik') ?: '';
        $search = trim((string)$this->input->get('search', true));
        $page = (int)$this->input->get('page', true);
        $page = $page > 0 ? $page : 1;
        $perPage = 10;
        $totalRows = $this->stockopname->count_history_input_by($inputBy, $search);
        $totalPages = max(1, (int)ceil($totalRows / $perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
        }
        $offset = ($page - 1) * $perPage;

        $data['page_title'] = 'KARISMA ERP - Histori Input Stockopname';
        $data['input_by'] = $inputBy;
        $data['search'] = $search;
        $data['current_page'] = $page;
        $data['per_page'] = $perPage;
        $data['total_rows'] = $totalRows;
        $data['total_pages'] = $totalPages;
        $data['histori'] = $this->stockopname->history_input_by($inputBy, $perPage, $offset, $search);

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_history_input.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    private function clean_scan_value($value)
    {
        $value = trim((string)$value);
        if ($value === '') {
            return '';
        }

        if (ctype_digit($value)) {
            return $value;
        }

        if (preg_match('/(?:^|[?&\/#=])(\d+)(?:$|[&\/#])/', $value, $matches)) {
            return $matches[1];
        }

        return $value;
    }

    public function supervisor_opname()
    {
        $data['page_title'] = 'KARISMA ERP - Supervisi Opname';
        [$profile, $wilayahIds] = $this->supervisor_profile_and_wilayah_ids();
        $wilayahRows = $this->stockopname->wilayah_by_ids($wilayahIds);
        $wilayahFilter = (int)$this->input->get('wilayah', true);
        if (!in_array($wilayahFilter, $wilayahIds, true)) $wilayahFilter = 0;
        $requestKeyword = trim((string)$this->input->get('keyword', true));
        $page = max(1, (int)$this->input->get('page', true));
        $perPage = 5;
        $total = $this->stockopname->supervisor_request_opname_count($wilayahIds, $wilayahFilter, $requestKeyword);
        $totalPages = max(1, (int)ceil($total / $perPage));
        $page = min($page, $totalPages);
        $data['supervisor_nama'] = $profile['nm_karyawan'] ?? ($this->session->userdata('nama') ?: '-');
        $data['supervisor_tim'] = $profile['tim'] ?? ($this->session->userdata('tim') ?: '-');
        $data['nama_wilayah'] = implode(', ', array_column($wilayahRows, 'nama_wilayah')) ?: '-';
        $data['wilayah_rows'] = $wilayahRows;
        $data['wilayah_filter'] = $wilayahFilter;
        $data['request_keyword'] = $requestKeyword;
        $data['request_total'] = $total;
        $data['current_page'] = $page;
        $data['total_pages'] = $totalPages;
        $data['request_rows'] = $this->stockopname->supervisor_request_opname_rows($wilayahIds, $wilayahFilter, $perPage, ($page - 1) * $perPage, $requestKeyword);
        $data['result_charts'] = $this->stockopname->supervisor_result_charts($wilayahIds);
        $this->stockopname->ensure_master_code_columns();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_supervisor.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    private function supervisor_profile_and_wilayah_ids()
    {
        $profile = $this->stockopname->supervisor_opname_profile($this->session->userdata('id_karyawan')) ?: [];
        preg_match_all('/\d+/', (string)($profile['wilayah_id'] ?? ''), $matches);
        $ids = array_values(array_unique(array_filter(array_map('intval', $matches[0] ?? []))));
        if (empty($ids) && !empty($profile['wilayah'])) $ids[] = (int)$profile['wilayah'];
        return [$profile, $ids];
    }

    public function ajax_supervisor_affirm_request()
    {
        $requestId = (int)$this->input->post('id', true);
        [, $wilayahIds] = $this->supervisor_profile_and_wilayah_ids();
        if ($requestId <= 0 || empty($wilayahIds)) return $this->json(false, 'Data afirmasi tidak valid.');
        $actor = $this->session->userdata('nama') ?: $this->session->userdata('username') ?: 'supervisor';
        $result = $this->stockopname->affirm_supervisor_request($requestId, $wilayahIds, $actor);
        $this->json($result['status'], $result['message'], $result['data'] ?? []);
    }

    public function supervisor_tracking()
    {
        [, $wilayahIds] = $this->supervisor_profile_and_wilayah_ids();
        $wilayahRows = $this->stockopname->wilayah_by_ids($wilayahIds);
        $wilayahFilter = (int)$this->input->get('wilayah', true);
        if (!in_array($wilayahFilter, $wilayahIds, true)) {
            $wilayahFilter = (int)($wilayahIds[0] ?? 0);
        }
        $wilayahMap = array_column($wilayahRows, 'nama_wilayah', 'id');
        $data['page_title'] = 'KARISMA ERP - Tracking Inputer Wilayah';
        $data['wilayah_rows'] = $wilayahRows;
        $data['wilayah_filter'] = $wilayahFilter;
        $data['nama_wilayah'] = $wilayahMap[$wilayahFilter] ?? '-';

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_supervisor_tracking.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function ajax_supervisor_tracking_list()
    {
        [, $wilayahIds] = $this->supervisor_profile_and_wilayah_ids();
        $wilayahFilter = (int)$this->input->get('wilayah', true);
        if (!in_array($wilayahFilter, $wilayahIds, true)) {
            $wilayahFilter = (int)($wilayahIds[0] ?? 0);
        }

        $draw = (int)$this->input->get('draw', true);
        $start = max(0, (int)$this->input->get('start', true));
        $length = (int)$this->input->get('length', true);
        $length = $length > 0 ? min($length, 100) : 10;
        $searchValue = $this->input->get('search', true);
        $search = is_array($searchValue) ? trim((string)($searchValue['value'] ?? '')) : '';
        $customSearch = trim((string)$this->input->get('keyword', true));
        if ($customSearch !== '') {
            $search = $customSearch;
        }
        $statusFilter = strtoupper(trim((string)$this->input->get('status', true)));
        if (!in_array($statusFilter, ['SAMA', 'RE-CHECK'], true)) {
            $statusFilter = '';
        }

        $filters = ['search' => $search, 'status' => $statusFilter];
        $recordsTotal = $this->stockopname->supervisor_wilayah_compare_count($wilayahFilter);
        $recordsFiltered = $this->stockopname->supervisor_wilayah_compare_count($wilayahFilter, $filters);
        $rows = $this->stockopname->supervisor_wilayah_compare($wilayahFilter, $length, $start, $filters);

        $data = array_map(function ($row) {
            $same = ($row['status_compare'] ?? '') === 'SAMA';
            return [
                'nama_barang' => html_escape($row['nama_barang'] ?? '-'),
                'expired' => html_escape($this->format_tracking_expired($row['expired_date'] ?? '')),
                'qty_tim_1' => number_format((int)($row['qty_tim_1'] ?? 0), 0, ',', '.'),
                'qty_tim_2' => number_format((int)($row['qty_tim_2'] ?? 0), 0, ',', '.'),
                'status' => '<span class="badge badge-' . ($same ? 'success' : 'warning') . '">' . ($same ? 'SAMA' : 'RE-CHECK') . '</span>',
            ];
        }, $rows);

        while (ob_get_level()) {
            ob_end_clean();
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode([
                'draw' => $draw,
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]));
    }

    private function format_tracking_expired($value)
    {
        $value = trim((string)$value);
        if ($value === '' || $value === '0000-00-00') {
            return '-';
        }

        foreach (['Y-m-d', 'Y-m-d H:i:s', 'd/m/Y'] as $format) {
            $date = DateTime::createFromFormat($format, $value);
            if ($date instanceof DateTime) {
                return $date->format('d/m/Y');
            }
        }

        $timestamp = strtotime($value);
        return $timestamp ? date('d/m/Y', $timestamp) : $value;
    }

    private function normalize_request_expired_date($value)
    {
        $value = trim((string)$value);
        if ($value === '') {
            return '';
        }

        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $value, $matches)) {
            return checkdate((int)$matches[2], (int)$matches[1], (int)$matches[3])
                ? $matches[3] . '-' . $matches[2] . '-' . $matches[1]
                : '';
        }

        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value, $matches)) {
            return checkdate((int)$matches[2], (int)$matches[3], (int)$matches[1]) ? $value : '';
        }

        return '';
    }

    private function scan_master_barang_item($barang, $expiredDate)
    {
        $this->load->library('Zahir_lot_catalog');
        $source = $this->zahir_lot_catalog->rows(false);
        if (!empty($source['stale'])) {
            $source = $this->zahir_lot_catalog->rows(true);
        }
        if (empty($source['status'])) {
            return ['status' => false, 'message' => $source['message'] ?? 'Data Master Barang tidak tersedia.'];
        }
        foreach ($source['data'] as $item) {
            if ($this->normalized_zahir_name($item['nama_barang'] ?? '') === $this->normalized_zahir_name($barang['nama_barang'])
                && trim((string)($item['expired_date'] ?? '')) === $expiredDate) {
                return ['status' => true, 'item' => $item];
            }
        }
        return ['status' => false, 'message' => 'Nama barang atau expired date QR tidak sesuai Data Master Barang. Refresh master dan generate ulang QR.'];
    }

    private function normalized_zahir_name($name)
    {
        return trim(preg_replace('/\s+/u', ' ', str_replace('*', '', (string)$name)));
    }

    private function compare_local_zahir()
    {
        return !defined('ENVIRONMENT') || ENVIRONMENT !== 'production';
    }

    private function scan_catalog_qrcode($value)
    {
        if (!preg_match('/^MB\|([1-9][0-9]*)\|(\d{4}-\d{2}-\d{2})$/', $value, $matches)
            || !$this->valid_date($matches[2])) {
            return ['status' => false, 'message' => 'QR barang lama atau format QR tidak valid. Generate QR baru dari Data Master Barang.'];
        }
        $barang = $this->stockopname->catalog_item((int)$matches[1]);
        if (!$barang) {
            return ['status' => false, 'message' => 'Barang QR tidak ditemukan pada master lokal.'];
        }
        $matched = $this->scan_master_barang_item($barang, $matches[2]);
        if (empty($matched['status'])) { return $matched; }
        return ['status' => true, 'item' => array_merge($matched['item'], [
            'barang_id' => (int)$barang['barang_id'],
            'dimensi' => (int)$barang['dimensi'],
            'scan_code' => $value,
        ])];
    }

    public function ajax_input_lookup()
    {
        $scanValue = $this->clean_scan_value($this->input->post('scan_value', true));
        if ($scanValue === '') {
            return $this->json(false, 'QRCode belum terbaca.');
        }

        $result = $this->scan_catalog_qrcode($scanValue);
        if (empty($result['status'])) { return $this->json(false, $result['message']); }
        $this->json(true, 'Data Master Barang ditemukan.', $result['item']);
    }

    public function ajax_input_save()
    {
        $input = $this->post();
        $scanValue = trim((string)($input['scan_value'] ?? ''));
        if ($scanValue === '') {
            return $this->json(false, 'Scan QRCode barang terlebih dahulu.');
        }

        $qtyPcs = $this->numeric_value($input['qty_pcs'] ?? '0');
        $qtyBox = $this->numeric_value($input['qty_box'] ?? '0');
        $qtyPcs = $qtyPcs === '' ? '0' : $qtyPcs;
        $qtyBox = $qtyBox === '' ? '0' : $qtyBox;
        if ($qtyPcs === '' || !ctype_digit((string)$qtyPcs)) {
            return $this->json(false, 'Qty pcs harus berupa angka bulat 0 atau lebih.');
        }
        if ($qtyBox === '' || !ctype_digit((string)$qtyBox)) {
            return $this->json(false, 'Qty box harus berupa angka bulat 0 atau lebih.');
        }

        $result = $this->scan_catalog_qrcode($scanValue);
        if (empty($result['status'])) { return $this->json(false, $result['message']); }
        $row = $result['item'];

        $error = $this->validate_input_context($row, (int)$qtyBox);
        if ($error !== '') { return $this->json(false, $error); }
        $qtyPcs = (int)$qtyPcs;
        $qtyBox = (int)$qtyBox;
        if (trim((string)($input['qty_pcs'] ?? '')) === '' && trim((string)($input['qty_box'] ?? '')) === '') {
            return $this->json(false, 'Isi qty pcs atau qty box terlebih dahulu.');
        }

        $saved = $this->stockopname->save_mobile_opname($row, [
            'qty_pcs' => $qtyPcs,
            'qty_box' => $qtyBox,
            'input_by' => $this->session->userdata('nama') ?: $this->session->userdata('username') ?: $this->session->userdata('nik') ?: 'system',
            'wilayah' => $this->session->userdata('wilayah') ?: 0,
            'tim_opname' => $this->session->userdata('tim') ?: 0,
            'input_source' => 'scan_qrcode',
        ]);

        if (!$saved) {
            return $this->json(false, 'Gagal menyimpan data opname.');
        }

        $this->json(true, 'Data opname berhasil disimpan.', [
            'id' => $saved,
            'kode_barang' => $row['kode_barang'],
            'nama_barang' => $row['nama_barang'],
            'qty_pcs' => $qtyPcs,
            'qty_box' => $qtyBox,
            'dimensi' => (int)($row['dimensi'] ?? 0),
        ]);
    }

    public function ajax_manual_barang()
    {
        $term = trim((string)$this->input->get('q', true));
        if ($term === '') {
            $term = trim((string)$this->input->post('q', true));
        }

        $page = (int)($this->input->get('page', true) ?: 1);
        $this->stockopname->ensure_master_code_columns();
        $this->stockopname->ensure_manual_tables();
        $result = $this->stockopname->manual_barang_options($term, $page);
        $this->raw_json($result);
    }

    public function ajax_manual_lot()
    {
        $kodeBarang = trim((string)$this->input->post('kode_barang', true));
        if ($kodeBarang === '') {
            return $this->json(false, 'Pilih nama barang terlebih dahulu.');
        }

        $this->stockopname->ensure_master_code_columns();
        $this->stockopname->ensure_manual_tables();
        $this->json(true, 'Data no lot berhasil dimuat.', $this->stockopname->manual_lot_options($kodeBarang));
    }

    public function ajax_manual_expired()
    {
        $kodeBarang = trim((string)$this->input->post('kode_barang', true));
        if ($kodeBarang === '') {
            return $this->json(false, 'Pilih nama barang terlebih dahulu.');
        }
        $barang = $this->stockopname->catalog_item(0, $kodeBarang);
        if (!$barang) { return $this->json(false, 'Barang tidak ditemukan pada master lokal.'); }
        $this->load->library('Zahir_lot_catalog');
        $source = $this->zahir_lot_catalog->rows(false);
        if (!empty($source['stale'])) { $source = $this->zahir_lot_catalog->rows(true); }
        if (empty($source['status'])) { return $this->json(false, $source['message'] ?? 'Data Master Barang tidak tersedia.'); }
        $options = [];
        foreach ($source['data'] as $item) {
            if (strcasecmp(trim((string)$item['kode_barang']), $barang['kode_barang']) === 0
                && (!$this->compare_local_zahir() || trim((string)$item['nama_barang']) === trim((string)$barang['nama_barang']))) {
                $date = (string)$item['expired_date'];
                $options[$date] = ['id' => $date, 'expired_date' => $date, 'no_lot' => '-', 'dimensi' => (int)$barang['dimensi']];
            }
        }
        ksort($options);
        $this->json(true, 'Expired date Data Master Barang berhasil dimuat.', array_values($options));
    }

    public function ajax_manual_save()
    {
        $input = $this->post();
        $kodeBarang = trim((string)($input['manual_kode_barang'] ?? ''));
        $expiredDate = trim((string)($input['manual_expired_id'] ?? ''));
        if ($kodeBarang === '' || !$this->valid_date($expiredDate)) {
            return $this->json(false, 'Lengkapi nama barang dan expired date terlebih dahulu.');
        }

        $qtyPcs = $this->numeric_value($input['qty_pcs'] ?? '0');
        $qtyBox = $this->numeric_value($input['qty_box'] ?? '0');
        $qtyPcs = $qtyPcs === '' ? '0' : $qtyPcs;
        $qtyBox = $qtyBox === '' ? '0' : $qtyBox;
        if ($qtyPcs === '' || !ctype_digit((string)$qtyPcs)) {
            return $this->json(false, 'Qty pcs harus berupa angka bulat 0 atau lebih.');
        }
        if ($qtyBox === '' || !ctype_digit((string)$qtyBox)) {
            return $this->json(false, 'Qty box harus berupa angka bulat 0 atau lebih.');
        }

        $qtyPcs = (int)$qtyPcs;
        $qtyBox = (int)$qtyBox;
        if (trim((string)($input['qty_pcs'] ?? '')) === '' && trim((string)($input['qty_box'] ?? '')) === '') {
            return $this->json(false, 'Isi qty pcs atau qty box terlebih dahulu.');
        }

        $barang = $this->stockopname->catalog_item(0, $kodeBarang);
        if (!$barang) { return $this->json(false, 'Barang tidak ditemukan pada master lokal.'); }
        $matched = $this->scan_master_barang_item($barang, $expiredDate);
        if (empty($matched['status'])) { return $this->json(false, $matched['message']); }
        $row = array_merge($matched['item'], [
            'dimensi' => (int)$barang['dimensi'],
            'scan_code' => 'MANUAL|' . (int)$barang['barang_id'] . '|' . $expiredDate,
        ]);

        $error = $this->validate_input_context($row, $qtyBox);
        if ($error !== '') { return $this->json(false, $error); }

        $saved = $this->stockopname->save_manual_input_to_opname($row, [
            'qty_pcs' => $qtyPcs,
            'qty_box' => $qtyBox,
            'input_by' => $this->session->userdata('nama') ?: $this->session->userdata('username') ?: $this->session->userdata('nik') ?: 'system',
            'wilayah' => $this->session->userdata('wilayah') ?: 0,
            'tim_opname' => $this->session->userdata('tim') ?: 0,
        ]);

        if (empty($saved['status'])) {
            return $this->json(false, $saved['message'] ?? 'Gagal menyimpan data opname manual.');
        }

        $this->json(true, 'Data input manual berhasil langsung masuk ke hasil opname.', [
            'id' => $saved['data']['id'] ?? 0,
            'manual_input_id' => $saved['data']['manual_input_id'] ?? 0,
            'kode_barang' => $row['kode_barang'],
            'nama_barang' => $row['nama_barang'],
            'qty_pcs' => $qtyPcs,
            'qty_box' => $qtyBox,
            'dimensi' => (int)($row['dimensi'] ?? 0),
            'input_source' => $saved['data']['input_source'] ?? 'manual_input',
        ]);
    }

    public function ajax_request_save()
    {
        $input = $this->post();
        $kodeBarang = trim((string)($input['request_kode_barang'] ?? ''));
        $noLot = trim((string)($input['request_no_lot'] ?? ''));
        if ($noLot === '' || strlen($noLot) > 100) { return $this->json(false, 'Nomor lot wajib diisi, maksimal 100 karakter.'); }
        $expiredDate = $this->normalize_request_expired_date($input['request_expired_date'] ?? '');

        if ($kodeBarang === '') {
            return $this->json(false, 'Pilih nama barang terlebih dahulu.');
        }
        if ($expiredDate === '') {
            return $this->json(false, 'Expired date wajib format tanggal/bulan/tahun, contoh 15/06/2026.');
        }

        $isSupervisor = $this->session_jobdesk_code() === 'SUPERVISOR_OPNAME';
        $qtyPcs = $this->numeric_value($input['qty_pcs'] ?? '0');
        $qtyBox = $this->numeric_value($input['qty_box'] ?? '0');
        $qtyPcs = $qtyPcs === '' ? '0' : $qtyPcs;
        $qtyBox = $qtyBox === '' ? '0' : $qtyBox;
        if ($qtyPcs === '' || !ctype_digit((string)$qtyPcs)) {
            return $this->json(false, 'Qty pcs harus berupa angka bulat 0 atau lebih.');
        }
        if ($qtyBox === '' || !ctype_digit((string)$qtyBox)) {
            return $this->json(false, 'Qty box harus berupa angka bulat 0 atau lebih.');
        }

        $qtyPcs = (int)$qtyPcs;
        $qtyBox = (int)$qtyBox;
        if (trim((string)($input['qty_pcs'] ?? '')) === '' && trim((string)($input['qty_box'] ?? '')) === '') {
            return $this->json(false, 'Isi qty pcs atau qty box terlebih dahulu.');
        }

        $this->stockopname->ensure_master_code_columns();
        $this->stockopname->ensure_manual_tables();
        $row = $this->stockopname->catalog_item(0, $kodeBarang);
        if (!$row) {
            return $this->json(false, 'Data master barang request tidak ditemukan.');
        }

        $error = $this->validate_input_context($row, $qtyBox);
        if ($error !== '') { return $this->json(false, $error); }
        // source_id tetap merujuk master opname; barang baru belum memiliki source_id.
        $row['id'] = 0;
        $saved = $this->stockopname->save_manual_master_item_queue($row, [
            'no_lot' => $noLot,
            'expired_date' => $expiredDate,
            'qty_pcs' => $qtyPcs,
            'qty_box' => $qtyBox,
            'input_by' => $this->session->userdata('nama') ?: $this->session->userdata('username') ?: $this->session->userdata('nik') ?: 'system',
            'wilayah' => $this->session->userdata('wilayah') ?: 0,
            'tim_opname' => $this->session->userdata('tim') ?: 0,
        ], 'Request Master Item');

        if (empty($saved['status'])) {
            return $this->json(false, $saved['message'] ?? 'Gagal menyimpan opname request.');
        }

        $this->json(true, $saved['message'] ?? 'Opname request berhasil disimpan.', $saved['data'] ?? []);
    }

    public function master_barang()
    {
        $isSuperadmin = $this->is_superadmin();
        $isStockopname = $this->is_stockopname();
        if ($isStockopname) {
            $data['page_title'] = 'KARISMA ERP - Master Opname Stockopname';
            $data['page_heading'] = 'Master Opname';
            $data['table_title'] = 'Data Master Opname';
            $data['route_base'] = 'admin/stockopname/master_opname';
            $data['qrcode_route_base'] = 'admin/stockopname/qrcode';
            $data['qty_zero_count'] = 0;
            $data['show_qty_zero_widget'] = false;
            $data['show_reset_qrcode'] = false;
            $data['show_stock_card_range_print'] = false;
            $data['is_superadmin'] = false;
            $data['is_stockopname'] = true;
            $data['master_action_token'] = $this->master_action_token();
            $this->stockopname->ensure_qrcode_columns();

            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/admin/stockopname_master_barang.php', $data);
            $this->load->view('partial/main/footer.php');
            return;
        }
        if (!$isSuperadmin) {
            $data['page_title'] = 'KARISMA ERP - Master Barang';
            $data['page_heading'] = 'Master Barang';
            $data['table_title'] = 'Data Master Barang';
            $data['route_base'] = 'admin/stockopname/master_opname';
            $data['qrcode_route_base'] = 'admin/stockopname/qrcode';
            $data['qty_zero_count'] = 0;
            $data['show_qty_zero_widget'] = false;
            $data['show_reset_qrcode'] = false;
            $data['show_stock_card_range_print'] = false;
            $data['is_superadmin'] = false;
            $data['is_stockopname'] = false;
            $data['master_action_token'] = $this->master_action_token();
            $this->stockopname->ensure_qrcode_columns();

            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/admin/stockopname_master_barang.php', $data);
            $this->load->view('partial/main/footer.php');
            return;
        }

        $data['page_title'] = 'KARISMA ERP - Master Opname Stockopname';
        $data['page_heading'] = 'Master Opname';
        $data['table_title'] = 'Data Master Opname';
        $data['route_base'] = 'admin/stockopname/master_opname';
        $data['qrcode_route_base'] = 'admin/stockopname/qrcode';
        $data['qty_zero_count'] = 0;
        $data['show_qty_zero_widget'] = true;
        $data['show_reset_qrcode'] = true;
        $data['show_stock_card_range_print'] = true;
        $data['is_superadmin'] = true;
        $data['is_stockopname'] = false;
        $data['master_action_token'] = $this->master_action_token();
        $this->stockopname->ensure_qrcode_columns();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_master_barang.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function ajax_delete_history_input()
    {
        $id = $this->post()['id'] ?? '';
        if (!ctype_digit((string)$id) || (int)$id <= 0) {
            return $this->json(false, 'Data histori opname tidak valid.');
        }

        $inputBy = $this->session->userdata('nama') ?: $this->session->userdata('username') ?: $this->session->userdata('nik') ?: '';
        if ($inputBy === '') {
            return $this->json(false, 'Identitas pengguna tidak ditemukan.');
        }

        $result = $this->stockopname->delete_history_input((int)$id, $inputBy, $inputBy);
        $this->json($result['status'], $result['message'], $result['data'] ?? []);
    }

    private function validate_input_context($row, $qtyBox)
    {
        if (!in_array((int)$this->session->userdata('tim'), [1, 2], true)
            || !$this->stockopname->wilayah_by_id((int)$this->session->userdata('wilayah'))) {
            return 'Tim opname (1 atau 2) dan wilayah akun harus terdaftar sebelum input.';
        }
        $barang = $this->stockopname->catalog_item(0, $row['kode_barang']);
        if (!$barang) { return 'Barang tidak tersedia pada master barang.'; }
        if ($qtyBox > 0 && (int)$barang['dimensi'] <= 0) {
            return 'P, L, dan T master barang harus valid sebelum input box.';
        }
        return '';
    }

    public function catalog_qrcode($id = 0)
    {
        $date=(string)$this->input->get('expired_date',true);
        $row=$this->stockopname->catalog_item((int)$id);
        if(!$row || !$this->valid_date($date)) {show_404();return;}
        $matched=$this->scan_master_barang_item($row,$date);
        $saved=$this->stockopname->catalog_qrcode_saved((int)$id,$date);
        if(empty($matched['status']) || !$saved || !is_file(FCPATH.$saved['qrcode_file'])) {show_error('QR belum tersedia/aktif. Gunakan Generate atau Resign dari katalog.',409);return;}
        $row['expired_date']=$date;
        if(!$this->compare_local_zahir()) $row['nama_barang']=$matched['item']['nama_barang'];
        $this->load->view('content/admin/stockopname_catalog_qrcode',['barang'=>$row,'qr_path'=>$saved['qrcode_file']]);
    }

    private function catalog_qrcode_path($id, $expiredDate)
    {
        $saved=$this->stockopname->catalog_qrcode_saved($id,$expiredDate);
        if($saved) return $saved['qrcode_file'];
        return 'assets/qrcode/stockopname/MB_' . $id . '_' . str_replace('-', '', $expiredDate) . '.png';
    }

    private function catalog_qrcode_for_master_row($row)
    {
        $catalog = $this->stockopname->catalog_item(0, '', (string)($row['nama_barang'] ?? ''));
        if (!$catalog) {
            $catalog = $this->stockopname->catalog_item(0, (string)($row['kode_barang'] ?? ''));
        }
        if (!$catalog || !$this->valid_date((string)($row['expired_date'] ?? ''))) {
            return ['value' => '', 'asset' => $this->asset_payload('')];
        }
        $active=$this->scan_master_barang_item($catalog,(string)$row['expired_date']);
        if(empty($active['status'])) return ['value'=>'','asset'=>$this->asset_payload('')];
        $value = 'MB|' . (int)$catalog['barang_id'] . '|' . $row['expired_date'];
        return ['value' => $value, 'asset' => $this->asset_payload($this->catalog_qrcode_path((int)$catalog['barang_id'], $row['expired_date']))];
    }

    private function create_catalog_qrcode($id, $expiredDate, $reissue = false, $reason = '', $expectedFile = '', $missingOnly = false)
    {
        try {
            $this->load->model('admin/M_Catalog_qr_issue','catalog_qr_issue');
            return $this->catalog_qr_issue->issue($id,$expiredDate,(string)$this->session->userdata('username'),$reissue,$reason,$expectedFile,
                function() use($id,$expiredDate) {
                    $row=$this->stockopname->catalog_item($id);
                    if(!$row) return ['status'=>false,'message'=>'Master lokal tidak ditemukan.'];
                    $matched=$this->scan_master_barang_item($row,$expiredDate);
                    $row['expired_date']=$expiredDate;
                    return !empty($matched['status'])?['status'=>true,'barang'=>$row]:$matched;
                },
                function($value,$path) {
                    if(!function_exists('imagecreatetruecolor') || !is_file(APPPATH.'libraries/stockopnameqr/qrcode.php')) throw new RuntimeException('Generator QR lokal/GD wajib tersedia.');
                    return $this->karisma_code_generator->qrcode($value,$path);
                }, $missingOnly);
        } catch(Throwable $e) {return ['status'=>false,'message'=>$e->getMessage()];}
    }

    public function ajax_resign_catalog_qrcode()
    {
        if(!$this->master_action_allowed()) return $this->json(false,'Request atau token keamanan tidak valid.');
        $id=(string)$this->input->post('id',true);
        if(!ctype_digit($id) || (int)$id<=0) return $this->json(false,'ID QR tidak valid.');
        $result=$this->create_catalog_qrcode((int)$id,(string)$this->input->post('expired_date',true),true,(string)$this->input->post('reason',true),(string)$this->input->post('expected_file',true));
        return $this->json(!empty($result['status']),$result['message'],!empty($result['status'])?['url'=>base_url($result['path'])]:[]);
    }

    public function ajax_catalog_qr_history()
    {
        $id=(string)$this->input->get('id',true);
        $date=(string)$this->input->get('expired_date',true);
        if(!ctype_digit($id) || (int)$id<=0 || !$this->valid_date($date)) return $this->json(false,'Identitas QR tidak valid.');
        if(!$this->db->table_exists('stockopname_catalog_qr_issue')) return $this->json(false,'Migration riwayat penerbitan QR belum dipasang.');
        $rows=$this->db->select('operation,actor,reason,issued_at,previous_file,qrcode_file')->where('catalog_id',(int)$id)->where('expired_date',$date)->order_by('id','DESC')->limit(50)->get('stockopname_catalog_qr_issue')->result_array();
        return $this->json(true,'Riwayat terbaru (waktu UTC).',$rows);
    }
    public function ajax_generate_all_catalog_qrcode()
    {
        if (!$this->master_action_allowed()) { return $this->json(false, 'Request atau token keamanan tidak valid.'); }
        $offset = max(0, (int)$this->input->post('offset', true));
        $this->load->library('Zahir_lot_catalog');
        $source = $this->zahir_lot_catalog->rows(false);
        if (empty($source['status'])) { return $this->json(false, $source['message'] ?? 'Data Zahir tidak tersedia.'); }
        $rows = $source['data'];
        $total = count($rows);
        $batch = array_slice($rows, $offset, 10);
        $counts = ['success' => 0, 'skipped' => 0, 'failed' => 0];
        $errors = [];
        foreach ($batch as $item) {
            $local = $this->stockopname->catalog_item(0, '', $item['nama_barang']) ?: $this->stockopname->catalog_item(0, $item['kode_barang']);
            if (!$local && !empty($item['p']) && !empty($item['l']) && !empty($item['t'])) {
                $created = $this->stockopname->create_master_barang_catalog([
                    'kd_barang' => $item['kode_barang'], 'nama_barang' => $item['nama_barang'],
                    'p' => (int)$item['p'], 'l' => (int)$item['l'], 't' => (int)$item['t'],
                ]);
                $local = $this->stockopname->catalog_item(0, '', $item['nama_barang']) ?: $this->stockopname->catalog_item(0, $item['kode_barang']);
            }
            if (!$local) {
                $counts['failed']++;
                $errors[] = $item['nama_barang'] . ': Master Barang lokal tidak cocok atau belum tersedia.';
                continue;
            }
            $result = $this->create_catalog_qrcode((int)$local['barang_id'], $item['expired_date']);
            $key = !empty($result['skipped']) ? 'skipped' : (empty($result['status']) ? 'failed' : 'success');
            $counts[$key]++;
            if ($key === 'failed') { $errors[] = $item['nama_barang'] . ': ' . $result['message']; }
        }
        $this->json(true, 'Batch selesai.', ['total' => $total, 'next_offset' => min($total, $offset + count($batch)), 'counts' => $counts, 'errors' => array_slice($errors, 0, 10)]);
    }

    public function ajax_reassign_missing_catalog_qrcode()
    {
        if (!$this->master_action_allowed()) return $this->json(false,'Request atau token keamanan tidak valid.');
        $offset=max(0,(int)$this->input->post('offset',true));
        $this->load->library('Zahir_lot_catalog');
        $source=$this->zahir_lot_catalog->rows(false);
        if (empty($source['status']) || !empty($source['stale'])) return $this->json(false,$source['message']??'Referensi katalog belum dapat diverifikasi.');
        $rows=$source['data'];
        $fingerprint=hash('sha256',json_encode([$source['source']['mode']??'direct',$source['source']['snapshot_id']??null,$rows]));
        $expected=(string)$this->input->post('catalog_fingerprint',true);
        if (($offset>0 || $expected!=='') && !hash_equals($fingerprint,$expected)) return $this->json(false,'Katalog berubah. Jalankan kembali Reassign QR Hilang.');
        $batch=array_slice($rows,$offset,10);
        $counts=['success'=>0,'skipped'=>0,'failed'=>0];$errors=[];
        foreach ($batch as $item) {
            $local=isset($item['master_id'])?$this->stockopname->catalog_item((int)$item['master_id']):($this->stockopname->catalog_item(0,'',$item['nama_barang'])?:$this->stockopname->catalog_item(0,$item['kode_barang']));
            if (!$local) {$counts['skipped']++;continue;}
            $saved=$this->stockopname->catalog_qrcode_saved((int)$local['barang_id'],$item['expired_date']);
            if ($saved) clearstatcache(true,FCPATH.$saved['qrcode_file']);
            if (!$saved || is_file(FCPATH.$saved['qrcode_file'])) {$counts['skipped']++;continue;}
            $result=$this->create_catalog_qrcode((int)$local['barang_id'],$item['expired_date'],true,'Reassign massal: file QR hilang',$saved['qrcode_file'],true);
            $key=!empty($result['skipped'])?'skipped':(!empty($result['status'])?'success':'failed');$counts[$key]++;
            if ($key==='failed') $errors[]=$item['nama_barang'].': '.$result['message'];
        }
        return $this->json(true,'Batch reassign selesai.',['total'=>count($rows),'next_offset'=>min(count($rows),$offset+count($batch)),'catalog_fingerprint'=>$fingerprint,'counts'=>$counts,'errors'=>$errors]);
    }

    public function ajax_generate_catalog_qrcode()
    {
        if (!$this->master_action_allowed()) return $this->json(false,'Request atau token keamanan tidak valid.');
        $id = (string)$this->input->post('id', true);
        $kode = trim((string)$this->input->post('kode_barang', true));
        $nama = trim((string)$this->input->post('nama_barang', true));
        $expired = trim((string)$this->input->post('expired_date', true));
        $createdLocal = false;
        if ($id !== '' && (!ctype_digit($id) || (int)$id <= 0)) {
            return $this->json(false, 'ID master barang tidak valid.');
        }
        if ($id === '') {
            $existing = $this->stockopname->catalog_item(0, '', $nama) ?: ($kode !== '' ? $this->stockopname->catalog_item(0, $kode) : null);
            if ($existing) {
                $id = (string)$existing['barang_id'];
            } else {
                if ($kode === '' || strlen($kode) > 25) {
                    return $this->json(false, 'Kode barang Zahir tidak valid untuk master lokal.');
                }
                $this->load->library('Zahir_lot_catalog');
                $source = $this->zahir_lot_catalog->rows(false);
                if (empty($source['status'])) {
                    return $this->json(false, $source['message'] ?? 'Data Zahir tidak tersedia.');
                }
                $barang = null;
                foreach ($source['data'] as $candidate) {
                    if ($this->normalized_zahir_name($candidate['nama_barang']) === $this->normalized_zahir_name($nama)
                        && $candidate['expired_date'] === $expired) {
                        $barang = $candidate;
                        break;
                    }
                }
                if (!$barang || trim((string)$barang['nama_barang']) === '') {
                    return $this->json(false, 'Barang tidak ditemukan pada data LOT Zahir yang berqty positif.');
                }
                foreach (['p', 'l', 't'] as $dimension) {
                    $value = $barang[$dimension] ?? null;
                    if (!is_numeric($value) || (float)$value <= 0 || (float)$value != floor((float)$value)) {
                        return $this->json(false, 'Panjang, lebar, dan tinggi Zahir harus bilangan bulat positif untuk master lokal.');
                    }
                }
                if ((float)$barang['dimensi'] > 2147483647) {
                    return $this->json(false, 'Dimensi Zahir melebihi batas kolom master lokal.');
                }
                $saved = $this->stockopname->create_master_barang_catalog([
                    'kd_barang' => $barang['kode_barang'],
                    'nama_barang' => $barang['nama_barang'],
                    'p' => (int)$barang['p'],
                    'l' => (int)$barang['l'],
                    't' => (int)$barang['t'],
                ]);
                if (empty($saved['status'])) {
                    // Permintaan serentak dapat membuat kode yang sama lebih dahulu.
                    $existing = $this->stockopname->catalog_item(0, $kode);
                    if (!$existing) {
                        return $this->json(false, $saved['message'] ?? 'Gagal membuat master lokal.');
                    }
                    $id = (string)$existing['barang_id'];
                } else {
                    $id = (string)$saved['data']['id'];
                    $createdLocal = true;
                }
            }
        }
        $result = $this->create_catalog_qrcode((int)$id, $expired);
        if (empty($result['status'])) {
            $message = $createdLocal ? 'Master lokal sudah dibuat, tetapi ' . $result['message'] : $result['message'];
            return $this->json(false, $message);
        }
        $message = $createdLocal ? 'Master lokal dan QR Code barang berhasil dibuat.' : 'QR Code barang berhasil digenerate.';
        $this->json(true, $message, ['url' => base_url($result['path'])]);
    }

    public function zahir_snapshot()
    {
        if ($this->input->method(true)==='GET' && $this->input->get('status_only')==='1') {
            return $this->load->view('content/admin/stockopname_zahir_status');
        }
        $this->load->config('zahir_stock_lot');
        $this->load->model('admin/M_Zahir_snapshot', 'zahir_snapshot');
        $this->load->library('Zahir_snapshot_validator');
        $report=null; $message=''; $canCommit=false; $importCompleted=false;
        $actor=(string)$this->session->userdata('username');
        $v=[]; $checksum='';
        try {
            $this->zahir_snapshot->ready();
            if ($this->input->method(true)==='POST') {
                if (!$this->master_action_allowed()) throw new RuntimeException('Token keamanan tidak valid.');
                if ($this->input->post('operation')==='restore') {
                    if ($this->input->post('confirm_restore')!=='1') throw new RuntimeException('Konfirmasi pemulihan wajib.');
                    $this->zahir_snapshot->restorePrevious((string)$this->input->post('expected_snapshot',true),$actor);
                    $importCompleted=true;
                    $message='Snapshot sebelumnya dipulihkan. Master baru, QR dan histori tetap disimpan.';
                } elseif ($this->input->post('operation')==='commit') {
                    if ($this->input->post('confirm_full')!=='1') throw new RuntimeException('Konfirmasikan bahwa CSV adalah pengganti lengkap cakupan aktif.');
                    $stage=$this->session->userdata('zahir_snapshot_stage');
                    if (!$stage || $stage['expires']<time() || !is_file($stage['path'])) throw new RuntimeException('Preview kedaluwarsa. Upload ulang.');
                    if (empty($stage['preview_token']) || !hash_equals($stage['preview_token'], (string)$this->input->post('preview_token',true))) throw new RuntimeException('Preview berbeda atau telah diganti. Upload dan review ulang.');
                    $bytes=file_get_contents($stage['path']);
                    $checksum=hash('sha256',$bytes);
                    if (!hash_equals($stage['checksum'],$checksum)) throw new RuntimeException('File staging berubah.');
                    $v=$this->zahir_snapshot_validator->decode($bytes,$stage['extension'],$stage['metadata']);
                    $report=$this->zahir_snapshot->commitSnapshot($v,$checksum,$stage['fingerprint'],$actor);
                    $importCompleted=true;
                    unlink($stage['path']); $this->session->unset_userdata('zahir_snapshot_stage');
                    $message=$report['idempotent']?'CSV identik sudah aktif; tidak ada penambahan.':'CSV berhasil diaktifkan. Hasil opname dan ukuran master existing tidak diubah.';
                } else {
                    $previous=$this->session->userdata('zahir_snapshot_stage');
                    if($previous && is_file($previous['path'])) unlink($previous['path']);
                    $this->session->unset_userdata('zahir_snapshot_stage');
                    $f=$_FILES['snapshot_file']??null;
                    if (!$f || $f['error']!==UPLOAD_ERR_OK || !is_uploaded_file($f['tmp_name']) || $f['size']>Zahir_snapshot_validator::MAX_BYTES) throw new RuntimeException('Upload gagal atau melebihi 5 MiB.');
                    $extension=strtolower(pathinfo($f['name'],PATHINFO_EXTENSION));
                    $metadata=['source_instance'=>$this->config->item('zahir_import_source_instance'),'scope'=>$this->config->item('zahir_import_scope'),'source_file'=>basename($f['name'])];
                    $bytes=file_get_contents($f['tmp_name']); $checksum=hash('sha256',$bytes);
                    $v=['document'=>$metadata];
                    $v=$this->zahir_snapshot_validator->decode($bytes,$extension,$metadata);
                    $metadata=$v['document']; unset($metadata['rows']);
                    $report=$this->zahir_snapshot->preview($v,$checksum);
                    $directImport=$this->input->post('operation')==='import';
                    $this->zahir_snapshot->audit($v,$checksum,$actor,$directImport?'validated':'dry_run',$report);
                    $canCommit=!$report['errors'] && !$report['conflicts'];
                    if ($directImport) {
                        if ($canCommit) {
                            $report=$this->zahir_snapshot->commitSnapshot($v,$checksum,$report['fingerprint'],$actor);
                            $importCompleted=true;
                            $message=$report['idempotent']?'File ini sudah diimpor. Data tidak digandakan.':'Impor CSV berhasil.';
                        } else {
                            $message='Impor dibatalkan. Perbaiki data yang ditandai lalu coba lagi.';
                        }
                        $canCommit=false;
                    } elseif ($canCommit) {
                        $path=tempnam(sys_get_temp_dir(),'so_zahir_');
                        if (!$path) throw new RuntimeException('Gagal membuat staging privat.');
                        $privateDir=strtolower(str_replace('\\','/',realpath(dirname($path))));
                        foreach ([FCPATH, $_SERVER['DOCUMENT_ROOT'] ?? FCPATH] as $publicRoot) {
                            $root=strtolower(rtrim(str_replace('\\','/',realpath($publicRoot)), '/'));
                            if($root!=='' && ($privateDir===$root || strpos($privateDir,$root.'/')===0)) { if($path && is_file($path)) unlink($path); throw new RuntimeException('TEMP wajib berada di luar document root.'); }
                        }
                        if (!$path || file_put_contents($path,$bytes,LOCK_EX)!==strlen($bytes)) throw new RuntimeException('Gagal menyimpan staging privat.');
                        chmod($path,0600);
                        $this->session->set_userdata('zahir_snapshot_stage',['preview_token'=>bin2hex(random_bytes(32)),'path'=>$path,'expires'=>time()+3600,'checksum'=>$checksum,'extension'=>$extension,'metadata'=>$metadata,'fingerprint'=>$report['fingerprint']]);
                    }
                    if (!$directImport) $message=$canCommit?'Dry-run selesai. Periksa seluruh perubahan sebelum konfirmasi.':'Aktivasi ditahan: perbaiki seluruh error/konflik.';
                }
            }
        } catch (Throwable $e) {
            $message=$e->getMessage();
            if ($this->db->table_exists('stockopname_zahir_audit')) $this->zahir_snapshot->audit($v,$checksum,$actor,'rejected',['error'=>$message]);
        }
        $active=null;
        try {$active=$this->zahir_snapshot->active();} catch(Throwable $e) {}
        $this->load->view('content/admin/stockopname_zahir_import',['report'=>$report,'message'=>$message,'canCommit'=>$canCommit,'active'=>$active,'action_token'=>$this->master_action_token(),'preview_token'=>($this->session->userdata('zahir_snapshot_stage')['preview_token']??''),'embedded'=>$this->input->get('embedded')==='1','import_completed'=>$importCompleted]);
    }

    public function master_barang_catalog()
    {
        $data['page_title'] = 'KARISMA ERP - Master Barang';
        $data['page_heading'] = 'Master Barang';
        $data['master_action_token'] = $this->master_action_token();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_master_barang_catalog.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function ajax_import_master_opname()
    {
        $this->require_superadmin_master_opname();
        if (!$this->master_action_allowed()) { return $this->json(false, 'Request atau token keamanan tidak valid.'); }
        $source = (string)$this->input->post('input_source', true);
        if (!in_array($source, ['opname_master', 'opname_it'], true)) { return $this->json(false, 'Sumber impor tidak valid.'); }
        if ($source === 'opname_it' && !$this->is_superadmin()) {
            return $this->json(false, 'Akses ditolak. Fitur Upload Master Opname IT hanya dapat diakses oleh Superadmin.');
        }
        $label = $source === 'opname_it' ? 'Opname IT' : 'Opname Master';
        if (empty($_FILES['file']['tmp_name']) || !is_uploaded_file($_FILES['file']['tmp_name']) || $_FILES['file']['size'] > 5242880 || strtolower(pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION)) !== 'csv') {
            return $this->json(false, 'Pilih berkas CSV maksimal 5 MB.');
        }
        $handle = fopen($_FILES['file']['tmp_name'], 'rb');
        if (!$handle) { return $this->json(false, 'Berkas impor tidak dapat dibaca.'); }
        $header = fgetcsv($handle);
        $expected = ['kode_barang', 'nama_barang', 'qty', 'qty_box', 'qty_pcs', 'expired_date'];
        if ($header) { $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]); }
        $header = array_map('trim', $header ?: []);
        if ($header !== $expected) { fclose($handle); return $this->json(false, 'Header harus tepat: ' . implode(', ', $expected)); }
        $fields = $this->stockopname->master_import_fields();
        $rows = []; $errors = []; $line = 1;
        while (($values = fgetcsv($handle)) !== false) {
            $line++;
            if (count($values) === 1 && trim($values[0]) === '') { continue; }
            if (count($values) !== 6) { $errors[] = 'Baris ' . $line . ': jumlah kolom harus 6.'; continue; }
            $values = array_map('trim', $values);
            if ($values[0] === '' || mb_strlen($values[0]) > 50 || $values[1] === '') { $errors[] = 'Baris ' . $line . ': kode atau nama barang kosong / kode terlalu panjang.'; continue; }
            $bad = false;
            foreach ([2, 3, 4] as $index) { if (!ctype_digit($values[$index]) || (float)$values[$index] > 2147483647) { $bad = true; } }
            if ($bad || !$this->valid_date($values[5])) { $errors[] = 'Baris ' . $line . ': qty harus bilangan bulat 0..2147483647 dan expired_date YYYY-MM-DD valid.'; continue; }
            $row = array_combine($expected, $values);
            foreach (['qty', 'qty_box', 'qty_pcs'] as $field) { $row[$field] = (int)$row[$field]; }
            $row['input_source'] = $source;
            foreach ($fields as $field) {
                if (array_key_exists($field->name, $row) || $field->name === 'id' || in_array($field->name, ['created_at', 'updated_at'], true)) { continue; }
                if ($field->name === 'qrcode_status') { $row[$field->name] = 'PENDING'; continue; }
                if (in_array($field->name, ['qrcode', 'barcode', 'qrcode_value', 'qrcode_file', 'qrcode_error_message', 'qrcode_generated_at', 'qrcode_updated_at', 'request_status'], true)) { continue; }
                $row[$field->name] = preg_match('/int|decimal|float|double|bit/i', $field->type) ? 0 : '0';
            }
            // Konsolidasi akumulasi qty jika nama barang dan expired date sama
            $dedupKey = $this->stockopname->normalized_master_name($row['nama_barang']) . '|' . $row['expired_date'];
            if (isset($rows[$dedupKey])) {
                $rows[$dedupKey]['qty'] += (int)$row['qty'];
                $rows[$dedupKey]['qty_box'] += (int)$row['qty_box'];
                $rows[$dedupKey]['qty_pcs'] += (int)$row['qty_pcs'];
            } else {
                $rows[$dedupKey] = $row;
            }
            if (count($rows) > 10000) { $errors[] = 'Berkas melebihi batas 10.000 baris.'; break; }
        }
        fclose($handle);
        $rows = array_values($rows);
        if ($errors) { return $this->json(false, $label . ': impor dibatalkan; perbaiki kesalahan berikut.', ['imported' => 0, 'errors' => array_slice($errors, 0, 30)]); }
        if (!$rows) { return $this->json(false, $label . ': tidak ada baris data.'); }
        if (!$this->stockopname->insert_master_import($rows)) { return $this->json(false, $label . ': gagal menyimpan data; transaksi dibatalkan.'); }
        $this->json(true, $label . ': ' . count($rows) . ' baris berhasil diimpor.', ['source' => $source, 'imported' => count($rows), 'errors' => []]);
    }

    public function ajax_master_delete_count()
    {
        $this->require_superadmin_master_opname();
        if (!$this->master_action_allowed()) { return $this->json(false, 'Request atau token keamanan tidak valid.'); }
        $scope = (string)$this->input->post('scope', true);
        if (!in_array($scope, ['all', 'opname_master', 'opname_it'], true)) { return $this->json(false, 'Cakupan tidak valid.'); }
        if ($scope !== 'opname_master' && !$this->is_superadmin()) {
            return $this->json(false, 'Akses ditolak. Penghapusan data ini hanya dapat dilakukan oleh Superadmin.');
        }
        $this->json(true, 'Jumlah data ditemukan.', ['count' => $this->stockopname->master_delete_count($scope)]);
    }

    public function ajax_master_delete()
    {
        $this->require_superadmin_master_opname();
        if (!$this->master_action_allowed()) { return $this->json(false, 'Request atau token keamanan tidak valid.'); }
        $scope = (string)$this->input->post('scope', true);
        if (!in_array($scope, ['all', 'opname_master', 'opname_it'], true)) { return $this->json(false, 'Cakupan tidak valid.'); }
        if ($scope !== 'opname_master' && !$this->is_superadmin()) {
            return $this->json(false, 'Akses ditolak. Penghapusan data ini hanya dapat dilakukan oleh Superadmin.');
        }
        $count = $this->stockopname->master_delete_count($scope);
        if (!$this->stockopname->delete_master_scope($scope)) { return $this->json(false, 'Penghapusan gagal; periksa relasi data dan izin database.'); }
        $this->json(true, $count . ' baris berhasil dihapus.', ['scope' => $scope, 'deleted' => $count]);
    }

    public function download_master_template()
    {
        $this->require_superadmin_master_opname();
        $filename = 'template_master_opname.csv';

        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        // Tambahkan UTF-8 BOM untuk kompatibilitas Microsoft Excel
        fputs($output, "\xEF\xBB\xBF");
        fputcsv($output, ['kode_barang', 'nama_barang', 'qty', 'qty_box', 'qty_pcs', 'expired_date']);
        fputcsv($output, ['BRG-001', 'OBAT CONTOH TABLET 500MG', 100, 2, 50, '2026-12-31']);
        fputcsv($output, ['BRG-002', 'SIRUP CONTOH BOTOL 60ML', 50, 5, 10, '2027-06-30']);
        fclose($output);
        exit;
    }

    public function ajax_master_barang_catalog_list()
    {
        $draw = max(0, (int)$this->input->get('draw'));
        $start = max(0, (int)$this->input->get('start'));
        $length = max(1, min(50, (int)$this->input->get('length') ?: 10));
        $search = trim((string)$this->input->get('search', true));
        $this->load->library('Zahir_lot_catalog');
        $result = $this->zahir_lot_catalog->rows($this->input->get('refresh') === '1');
        if (!$result['status']) {
            $this->output->set_status_header(502);
            return $this->raw_json(['draw' => $draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [], 'error' => $result['message']]);
        }
        if (!empty($result['stale'])) {
            $this->output->set_status_header(502);
            return $this->raw_json(['draw' => $draw, 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => [], 'error' => 'Sumber Zahir belum dapat diverifikasi. Data cache tidak ditampilkan sebagai hasil terbaru. Klik Refresh.']);
        }
        $rows = $this->group_catalog_rows($this->catalog_rows_with_qrcode($result['data'], empty($result['stale']) && empty($result['cached'])));
        $total = count($rows);
        $generatedCount = 0;
        $cardCount = 0;
        foreach ($rows as $row) {
            $cardCount += count($row['variants']);
            foreach ($row['variants'] as $variant) {
                $generatedCount += $variant['qrcode_status'] === 'generated' ? 1 : 0;
            }
        }
        $status = (string)$this->input->get('qrcode_status', true);
        if (in_array($status, ['generated', 'not_generated'], true)) {
            $rows = array_values(array_filter($rows, function ($row) use ($status) {
                return $status === 'generated' ? $row['qrcode_status'] === 'generated'
                    : $row['qrcode_status'] !== 'generated';
            }));
        }
        if ($search !== '') {
            $rows = array_values(array_filter($rows, function ($row) use ($search) {
                return stripos($row['kode_barang'], $search) !== false || stripos($row['nama_barang'], $search) !== false || stripos($row['expired_date'], $search) !== false;
            }));
        }
        $this->raw_json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => count($rows),
            'data' => array_slice($rows, $start, $length),
            'stale' => !empty($result['stale']),
            'cached' => !empty($result['cached']),
            'source' => $result['source'] ?? ['mode'=>'direct'],
            'widgets' => [
                'total' => $total,
                'qty_zero' => $this->stockopname->count_all_master_barang('zero'),
                'generated' => $generatedCount,
                'pending' => $cardCount - $generatedCount,
            ],
        ]);
    }

    private function group_catalog_rows(array $rows)
    {
        $groups = [];
        foreach ($rows as $row) {
            $key = json_encode([trim((string)$row['nama_barang']), trim((string)$row['expired_date'])]);
            if (!isset($groups[$key])) {
                $groups[$key] = $row;
                $groups[$key]['variants'] = [];
                $groups[$key]['codes'] = [];
            }
            foreach (['p', 'l', 't', 'dimensi'] as $field) {
                if ($groups[$key][$field] !== $row[$field]) {
                    $groups[$key][$field] = 'Beragam';
                }
            }
            $groups[$key]['variants'][] = $row;
            $groups[$key]['codes'][] = $row['kode_barang'];
        }
        foreach ($groups as &$group) {
            $group['kode_barang'] = implode(', ', $group['codes']);
            $ready = count(array_filter($group['variants'], function ($variant) {
                return $variant['qrcode_status'] === 'generated';
            }));
            $group['qrcode_status'] = $ready === count($group['variants']) ? 'generated'
                : ($ready > 0 ? 'partial' : ($group['variants'][0]['qrcode_status'] ?? 'not_generated'));
            $group['qrcode_url'] = '';
            foreach ($group['variants'] as $variant) {
                if ($variant['qrcode_url'] !== '') {
                    $group['qrcode_url'] = $variant['qrcode_url'];
                    break;
                }
            }
        }
        unset($group);
        $groups = array_values($groups);
        usort($groups, function ($a, $b) {
            return [$a['nama_barang'], $a['expired_date']] <=> [$b['nama_barang'], $b['expired_date']];
        });
        return $groups;
    }

    private function catalog_rows_with_qrcode(array $rows, $sourceVerified = true)
    {
        $catalog = $this->stockopname->catalog_qrcode_by_names(array_column($rows, 'nama_barang'));
        $variants = $this->stockopname->catalog_qrcode_variants(array_column($catalog, 'id'));
        foreach ($rows as &$row) {
            $norm = $this->stockopname->normalized_master_name($row['nama_barang']);
            $local = $catalog[$norm] ?? null;
            $row['catalog_id'] = $local ? (int)$local['id'] : 0;
            $saved = $variants[(int)($local['id'] ?? 0) . '|' . $row['expired_date']] ?? null;
            $path = $saved ? $saved['qrcode_file'] : '';
            $row['qrcode_file'] = $path;
            $row['qrcode_recorded'] = (bool)$saved;
            $ready = $local && $saved && $saved['qrcode_file'] === $path && $saved['qrcode_value'] === 'MB|' . (int)$local['id'] . '|' . $row['expired_date'] && is_file(FCPATH . $path);
            $row['qrcode_url'] = $sourceVerified && $ready ? base_url($path) : '';
            $row['qrcode_status'] = !$sourceVerified ? 'unverified' : (!$local ? 'not_in_local'
                : ($ready ? 'generated' : ($saved ? 'file_missing' : 'not_generated')));
            $row['qrcode_reason'] = !$sourceVerified ? 'Sumber Zahir sedang gagal diverifikasi; gunakan Refresh setelah pulih.'
                : (!$local ? 'Nama barang tidak ditemukan pada Master Barang lokal.'
                : ($ready ? 'Data sesuai dan file QR tersedia.'
                    : ($saved ? 'QR tercatat, tetapi file QR hilang atau tidak sesuai.' : 'Data sesuai; QR untuk expired date ini belum dibuat.')));
        }
        unset($row);
        return $rows;
    }

    public function print_catalog_stock_cards()
    {
        if (strtoupper((string)$this->input->method(true)) !== 'POST') {
            show_error('Permintaan cetak harus menggunakan POST.', 405);
            return;
        }
        $mode = (string)$this->input->post('mode', true);
        if (!in_array($mode, ['selected', 'all'], true)) {
            show_error('Mode cetak tidak valid.', 400);
            return;
        }
        $this->load->library('Zahir_lot_catalog');
        $source = $this->zahir_lot_catalog->rows(false);
        if (!empty($source['stale'])) { $source = $this->zahir_lot_catalog->rows(true); }
        if (empty($source['status']) || !empty($source['stale'])) {
            show_error($source['message'] ?? 'Sumber Zahir belum dapat diverifikasi.', 502);
            return;
        }
        $rows = $this->catalog_rows_with_qrcode($source['data']);
        $ready = [];
        foreach ($rows as $row) {
            if ($row['qrcode_status'] !== 'generated') { continue; }
            $key = json_encode([$row['kode_barang'], $row['nama_barang'], $row['expired_date']]);
            $ready[$key] = $row;
        }
        if ($mode === 'all') {
            $items = array_values($ready);
        } else {
            $selection = json_decode((string)$this->input->post('selected_items', false), true);
            if (!is_array($selection) || !$selection || count($selection) > 5000) {
                show_error('Pilihan kartu tidak valid.', 400);
                return;
            }
            $items = [];
            foreach ($selection as $values) {
                if (!is_array($values) || count($values) !== 3) {
                    show_error('Pilihan kartu tidak valid.', 400);
                    return;
                }
                $key = json_encode(array_values($values));
                if (!isset($ready[$key])) {
                    show_error('Ada kartu terpilih yang tidak memiliki QR siap cetak.', 400);
                    return;
                }
                $items[$key] = $ready[$key];
            }
            $items = array_values($items);
        }
        if (!$items || count($items) > 5000) {
            show_error('Tidak ada QR siap cetak atau jumlah kartu melebihi batas 5.000.', 400);
            return;
        }
        $this->load->view('content/admin/stockopname_catalog_stock_cards', ['items' => $items, 'mode' => $mode]);
    }

    public function ajax_create_master_barang_catalog()
    {
        $input = $this->post();
        $kodeBarang = trim((string)($input['kd_barang'] ?? ''));
        $namaBarang = trim((string)($input['nama_barang'] ?? ''));
        if ($kodeBarang === '' || strlen($kodeBarang) > 25) {
            return $this->json(false, 'Kode barang wajib diisi, maksimal 25 karakter.');
        }
        if ($namaBarang === '') {
            return $this->json(false, 'Nama barang wajib diisi.');
        }

        foreach (['p' => 'Panjang', 'l' => 'Lebar', 't' => 'Tinggi'] as $field => $label) {
            $value = trim((string)($input[$field] ?? ''));
            if ($value === '' || !ctype_digit($value) || (int)$value <= 0) {
                return $this->json(false, $label . ' harus berupa bilangan bulat lebih dari 0.');
            }
        }

        $saved = $this->stockopname->create_master_barang_catalog([
            'kd_barang' => $kodeBarang,
            'nama_barang' => $namaBarang,
            'p' => (int)$input['p'],
            'l' => (int)$input['l'],
            't' => (int)$input['t'],
        ]);
        if (empty($saved['status'])) {
            return $this->json(false, $saved['message'] ?? 'Gagal menyimpan master barang.');
        }

        $this->json(true, $saved['message'], $saved['data']);
    }

    public function ajax_update_master_barang_catalog()
    {
        $input = $this->post();
        $id = trim((string)($input['id'] ?? ''));
        $kodeBarang = trim((string)($input['kd_barang'] ?? ''));
        $namaBarang = trim((string)($input['nama_barang'] ?? ''));
        if ($id === '' || !ctype_digit($id) || (int)$id <= 0) {
            return $this->json(false, 'ID master barang tidak valid.');
        }
        if ($kodeBarang === '' || strlen($kodeBarang) > 25) {
            return $this->json(false, 'Kode barang wajib diisi, maksimal 25 karakter.');
        }
        if ($namaBarang === '') {
            return $this->json(false, 'Nama barang wajib diisi.');
        }

        foreach (['p' => 'Panjang', 'l' => 'Lebar', 't' => 'Tinggi'] as $field => $label) {
            $value = trim((string)($input[$field] ?? ''));
            if ($value === '' || !ctype_digit($value) || (int)$value <= 0) {
                return $this->json(false, $label . ' harus berupa bilangan bulat lebih dari 0.');
            }
        }

        $saved = $this->stockopname->update_master_barang_catalog((int)$id, [
            'kd_barang' => $kodeBarang,
            'nama_barang' => $namaBarang,
            'p' => (int)$input['p'],
            'l' => (int)$input['l'],
            't' => (int)$input['t'],
        ]);
        if (empty($saved['status'])) {
            return $this->json(false, $saved['message'] ?? 'Gagal memperbarui master barang.');
        }

        $this->json(true, $saved['message'], $saved['data']);
    }

    public function barang_pending()
    {
        $this->stockopname->ensure_pending_tables();
        $data['page_title'] = 'KARISMA ERP - Barang Pending Stockopname';
        $data['page_heading'] = 'Barang Pending';
        $data['summary'] = $this->stockopname->pending_summary();
        $data['pending_master_options'] = $this->stockopname->pending_master_options();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_barang_pending.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function ajax_barang_pending_list()
    {
        $keyword = trim((string)$this->input->get('keyword', true));
        $perPage = 10;
        $page = (int)$this->input->get('page', true);
        $page = $page > 0 ? $page : 1;
        $totalRows = $this->stockopname->pending_count($keyword);
        $totalPages = max(1, (int)ceil($totalRows / $perPage));
        $page = min($page, $totalPages);

        $this->json(true, 'Data barang pending berhasil dimuat.', [
            'rows' => $this->stockopname->pending_rows($keyword, $perPage, ($page - 1) * $perPage),
            'summary' => $this->stockopname->pending_summary(),
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total_rows' => $totalRows,
                'total_pages' => $totalPages,
            ],
        ]);
    }

    public function ajax_barang_pending_detail()
    {
        $id = $this->input->post('id', true);
        if (!ctype_digit((string)$id) || (int)$id <= 0) {
            return $this->json(false, 'ID barang pending tidak valid.');
        }

        $row = $this->stockopname->pending_by_id((int)$id);
        if (!$row) {
            return $this->json(false, 'Data barang pending tidak ditemukan.');
        }

        $this->json(true, 'Detail barang pending berhasil dimuat.', $row);
    }

    public function ajax_save_barang_pending()
    {
        $input = $this->post();
        $id = trim((string)($input['id'] ?? ''));
        if ($id !== '' && (!ctype_digit($id) || (int)$id <= 0)) {
            return $this->json(false, 'ID barang pending tidak valid.');
        }

        $required = [
            'kd_do' => 'Kode faktur',
            'kode_barang' => 'Kode barang',
            'expired_date' => 'Expired date',
        ];
        foreach ($required as $field => $label) {
            if (trim((string)($input[$field] ?? '')) === '') {
                return $this->json(false, $label . ' wajib diisi.');
            }
        }
        if (!$this->valid_date(trim((string)$input['expired_date']))) {
            return $this->json(false, 'Expired date wajib memakai format tanggal yang valid.');
        }

        foreach (['qty_pcs' => 'Qty pcs', 'qty_box' => 'Qty box'] as $field => $label) {
            $value = trim((string)($input[$field] ?? ''));
            if ($value === '' || !ctype_digit($value)) {
                return $this->json(false, $label . ' harus berupa bilangan bulat 0 atau lebih.');
            }
        }

        $masterOption = $this->stockopname->pending_master_option($input['kode_barang'], $input['expired_date'], $input['no_lot'] ?? '-');
        if (!$masterOption) {
            return $this->json(false, 'Kode barang dan expired date wajib dipilih dari data master opname.');
        }

        $dimensi = (int)($masterOption['dimensi'] ?? 0);
        $qtyPcs = (int)$input['qty_pcs'];
        $qtyBox = (int)$input['qty_box'];
        if ($qtyBox > 0 && $dimensi <= 0) {
            return $this->json(false, 'Dimensi master opname belum tersedia sehingga qty box tidak bisa dihitung.');
        }
        $qty = ($qtyBox * max(0, $dimensi)) + $qtyPcs;
        if ($qty <= 0 && $qtyPcs + $qtyBox <= 0) {
            return $this->json(false, 'Isi minimal salah satu nilai qty pcs atau qty box.');
        }

        $actor = $this->session->userdata('nama') ?: $this->session->userdata('username') ?: 'system';
        $saved = $this->stockopname->save_pending([
            'id' => $id === '' ? 0 : (int)$id,
            'kd_do' => trim((string)$input['kd_do']),
            'kode_barang' => trim((string)$masterOption['kode_barang']),
            'nama_barang' => trim((string)$masterOption['nama_barang']),
            'expired_date' => trim((string)$masterOption['expired_date']),
            'no_lot' => trim((string)($masterOption['no_lot'] ?? '-')),
            'qty' => $qty,
            'qty_pcs' => $qtyPcs,
            'qty_box' => $qtyBox,
        ], $actor);

        if (empty($saved['status'])) {
            return $this->json(false, $saved['message'] ?? 'Gagal menyimpan barang pending.');
        }

        $this->json(true, $saved['message'], $saved['data'] ?? []);
    }

    public function ajax_delete_barang_pending()
    {
        $id = $this->input->post('id', true);
        if (!ctype_digit((string)$id) || (int)$id <= 0) {
            return $this->json(false, 'ID barang pending tidak valid.');
        }

        $deleted = $this->stockopname->delete_pending((int)$id);
        $this->json(!empty($deleted['status']), $deleted['message'] ?? 'Barang pending diproses.', $deleted['data'] ?? []);
    }

    public function barang_pending_export_csv()
    {
        $rows = $this->stockopname->pending_rows('', 2000);
        $filename = 'barang-pending-stockopname-' . date('Ymd-His') . '.csv';

        while (ob_get_level()) {
            ob_end_clean();
        }
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Kode Faktur', 'Kode Barang', 'Expired Date', 'Nama Barang', 'Qty', 'Qty PCS', 'Qty Box', 'Master ID', 'Created By', 'Created At']);
        foreach ($rows as $row) {
            fputcsv($output, [
                $row['kd_do'] ?? '',
                $row['kode_barang'] ?? '',
                $row['expired_date'] ?? '',
                $row['nama_barang'] ?? '',
                $row['qty'] ?? 0,
                $row['qty_pcs'] ?? 0,
                $row['qty_box'] ?? 0,
                $row['master_id'] ?? '',
                $row['created_by'] ?? '',
                $row['created_at'] ?? '',
            ]);
        }
        fclose($output);
        exit;
    }

    public function master_barang_qty_zero()
    {
        $this->require_superadmin_master_opname();
        $data['page_title'] = 'KARISMA ERP - Master Opname Qty 0';
        $data['page_heading'] = 'Master Opname Qty 0';
        $data['table_title'] = 'Data Master Opname Qty 0';
        $data['route_base'] = 'admin/stockopname/master_opname/qty-zero';
        $data['qrcode_route_base'] = 'admin/stockopname/qrcode/qty-zero';
        $data['qty_zero_count'] = $this->stockopname->count_all_master_barang('zero');
        $data['show_qty_zero_widget'] = false;
        $data['show_reset_qrcode'] = false;
        $data['show_stock_card_range_print'] = false;
        $this->stockopname->ensure_qrcode_columns();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/admin/stockopname_master_barang.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function master_barang_widgets()
    {
        $this->require_superadmin_master_opname();
        $this->stockopname->ensure_qrcode_columns();
        $this->json(true, 'Data master opname berhasil dimuat.', [
            'summary' => $this->stockopname->master_barang_summary(),
        ]);
    }

    public function master_barang_list()
    {
        return $this->ajax_master_barang_list();
    }

    public function ajax_master_barang_list()
    {
        $this->master_barang_list_response('positive');
    }

    public function ajax_master_barang_qty_zero_list()
    {
        $this->master_barang_list_response('zero');
    }

    private function master_barang_list_response($qtyMode)
    {
        $this->require_superadmin_master_opname();
        session_write_close();
        $this->stockopname->ensure_qrcode_columns();
        $this->load->library('Zahir_lot_catalog');
        $catalog = $this->zahir_lot_catalog->rows((string)$this->input->post('refresh_catalog', true) === '1');
        $byName = null;
        if (!empty($catalog['status']) && empty($catalog['stale']) && empty($catalog['cached'])) {
            $byName = [];
            foreach ($catalog['data'] as $item) {
                $normName = $this->stockopname->normalized_master_name($item['nama_barang'] ?? '');
                if ($normName !== '') { $byName[$normName][] = $item; }
                $code = strtoupper(trim((string)($item['kode_barang'] ?? '')));
                if ($code !== '') { $byName[$code][] = $item; }
            }
        }
        $result = $this->stockopname->get_master_barang_datatable($this->post(), $qtyMode, $byName);
        $result['catalog_stale'] = !empty($catalog['stale']);
        $result['catalog_cached'] = !empty($catalog['cached']);
        $result['catalog_error'] = empty($catalog['status']) ? ($catalog['message'] ?? 'Data Master Barang tidak tersedia.') : '';
        $this->raw_json($result);
    }

    public function ajax_master_barang_detail()
    {
        $this->require_superadmin_master_opname();
        $id = $this->input->post('id', true);
        if (!ctype_digit((string)$id)) {
            return $this->json(false, 'ID barang tidak valid.');
        }

        $this->stockopname->ensure_qrcode_columns();
        $row = $this->stockopname->get_master_barang_by_id((int)$id, true);
        if (!$row) {
            return $this->json(false, 'Data barang tidak ditemukan.');
        }

        $this->json(true, 'Detail barang berhasil dimuat.', $row);
    }

    public function ajax_master_barang_qty_zero_detail()
    {
        $this->require_superadmin_master_opname();
        $id = $this->input->post('id', true);
        if (!ctype_digit((string)$id)) {
            return $this->json(false, 'ID barang tidak valid.');
        }

        $this->stockopname->ensure_qrcode_columns();
        $row = $this->stockopname->get_master_barang_by_id((int)$id, 'zero');
        if (!$row) {
            return $this->json(false, 'Data barang qty 0 tidak ditemukan.');
        }

        $this->json(true, 'Detail barang qty 0 berhasil dimuat.', $row);
    }

    public function ajax_update_master_barang()
    {
        $this->require_superadmin_master_opname();
        $input = $this->post();
        $id = $input['id'] ?? '';

        if (!ctype_digit((string)$id) || (int)$id <= 0) {
            return $this->json(false, 'ID barang tidak valid.');
        }

        // Mode Edit Data Master Opname (stockopname_master_item)
        if (isset($input['expired_date']) || isset($input['qty']) || isset($input['qty_box']) || isset($input['qty_pcs'])) {
            $kodeBarang = trim((string)($input['kode_barang'] ?? $input['kd_barang'] ?? ''));
            $namaBarang = trim((string)($input['nama_barang'] ?? ''));
            $expiredDate = trim((string)($input['expired_date'] ?? ''));
            $noLot = trim((string)($input['no_lot'] ?? '-'));
            if ($noLot === '') {
                $noLot = '-';
            }

            if ($kodeBarang === '' || mb_strlen($kodeBarang) > 50) {
                return $this->json(false, 'Kode barang wajib diisi (maksimal 50 karakter).');
            }
            if ($namaBarang === '') {
                return $this->json(false, 'Nama barang wajib diisi.');
            }
            if (!$this->valid_date($expiredDate)) {
                return $this->json(false, 'Expired date wajib diisi dengan format YYYY-MM-DD yang valid.');
            }

            $qty = $this->numeric_value($input['qty'] ?? '0');
            $qtyPcs = $this->numeric_value($input['qty_pcs'] ?? '0');
            $qtyBox = $this->numeric_value($input['qty_box'] ?? '0');

            foreach (['Qty' => $qty, 'Qty Pcs' => $qtyPcs, 'Qty Box' => $qtyBox] as $label => $val) {
                if ($val === '' || !ctype_digit((string)$val)) {
                    return $this->json(false, $label . ' harus berupa angka bulat 0 atau lebih.');
                }
            }

            $existing = $this->stockopname->get_master_barang_by_id((int)$id);
            if (!$existing) {
                return $this->json(false, 'Data master opname tidak ditemukan.');
            }

            $data = [
                'kode_barang' => $kodeBarang,
                'nama_barang' => $namaBarang,
                'expired_date' => $expiredDate,
                'no_lot' => $noLot,
                'qty' => (int)$qty,
                'qty_pcs' => (int)$qtyPcs,
                'qty_box' => (int)$qtyBox,
            ];

            if (!$this->stockopname->update_master_barang((int)$id, $data)) {
                return $this->json(false, 'Gagal memperbarui data master opname.');
            }

            return $this->json(true, 'Data master opname berhasil diperbarui.', array_merge($data, [
                'id' => (int)$id,
            ]));
        }

        $kdBarang = trim((string)($input['kd_barang'] ?? ''));
        $namaBarang = trim((string)($input['nama_barang'] ?? ''));
        if ($kdBarang === '') {
            return $this->json(false, 'Kode barang wajib diisi.');
        }
        if ($namaBarang === '') {
            return $this->json(false, 'Nama barang wajib diisi.');
        }

        $numericFields = ['p', 'l', 't', 'berat'];
        foreach ($numericFields as $field) {
            $value = $this->numeric_value($input[$field] ?? '');
            if ($value === '' || !is_numeric($value)) {
                return $this->json(false, strtoupper($field) . ' harus berupa angka.');
            }
            $input[$field] = $value;
        }

        $kubikasi = $this->numeric_value($input['kubikasi'] ?? '0');
        if ($kubikasi !== '' && !is_numeric($kubikasi)) {
            return $this->json(false, 'Kubikasi harus berupa angka.');
        }

        $data = [
            'kd_barang' => $kdBarang,
            'nama_barang' => $namaBarang,
            'satuan' => trim((string)($input['satuan'] ?? '')),
            'p' => $input['p'] + 0,
            'l' => $input['l'] + 0,
            't' => $input['t'] + 0,
            'berat' => $input['berat'] + 0,
            'kubikasi' => $kubikasi === '' ? 0 : $kubikasi,
        ];

        if (!$this->stockopname->update_master_barang((int)$id, $data)) {
            return $this->json(false, 'Gagal update master barang.');
        }

        $this->json(true, 'Master barang berhasil diperbarui.', [
            'id' => (int)$id,
        ]);
    }

    public function ajax_master_barang_source_search()
    {
        $this->require_superadmin_master_opname();
        $keyword = trim((string)$this->input->get_post('keyword', true));
        if (mb_strlen($keyword) < 2) {
            return $this->json(true, 'Masukkan minimal 2 karakter untuk mencari barang.', []);
        }

        $this->json(true, 'Data barang berhasil ditemukan.', $this->stockopname->search_master_barang_source($keyword));
    }

    public function ajax_create_master_barang()
    {
        $this->require_superadmin_master_opname();
        $input = $this->post();
        $sourceId = $input['source_id'] ?? '';
        if (!ctype_digit((string)$sourceId) || (int)$sourceId <= 0) {
            return $this->json(false, 'Pilih barang dari hasil pencarian terlebih dahulu.');
        }

        $expiredDate = trim((string)($input['expired_date'] ?? ''));
        $noLot = trim((string)($input['no_lot'] ?? ''));
        if (!$this->valid_date($expiredDate)) {
            return $this->json(false, 'Expired date wajib diisi dengan format tanggal yang valid.');
        }
        if ($noLot === '') {
            return $this->json(false, 'No. lot wajib diisi.');
        }

        $qtyPcs = $input['qty_pcs'] ?? '';
        $qtyBox = $input['qty_box'] ?? '';
        foreach (['qty_pcs' => $qtyPcs, 'qty_box' => $qtyBox] as $field => $value) {
            if ($value === '' || !ctype_digit((string)$value)) {
                return $this->json(false, ($field === 'qty_pcs' ? 'Qty pcs' : 'Qty box') . ' harus berupa bilangan bulat 0 atau lebih.');
            }
        }
        if ((int)$qtyPcs + (int)$qtyBox <= 0) {
            return $this->json(false, 'Isi minimal Qty pcs atau Qty box.');
        }

        $this->stockopname->ensure_qrcode_columns();
        $saved = $this->stockopname->create_master_barang_from_source((int)$sourceId, [
            'expired_date' => $expiredDate,
            'no_lot' => $noLot,
            'qty_pcs' => (int)$qtyPcs,
            'qty_box' => (int)$qtyBox,
        ]);
        if (empty($saved['status'])) {
            return $this->json(false, $saved['message'] ?? 'Gagal menambahkan master opname.');
        }

        $this->json(true, $saved['message'], $saved['data']);
    }

    private function ensure_qrcode_ready()
    {
        $ready = $this->stockopname->ensure_qrcode_columns();
        if (empty($ready['success'])) {
            $this->raw_json([
                'success' => false,
                'message' => $ready['message'] ?? 'Struktur database QRCode belum siap.',
                'total' => 0,
                'done' => 0,
                'pending' => 0,
                'failed' => 0,
            ]);
            return false;
        }

        return true;
    }

    private function qrcode_summary_response($qtyMode = 'positive')
    {
        if (!$this->ensure_qrcode_ready()) {
            return;
        }

        // Lepas session lock agar request lain dapat berjalan paralel tanpa antre
        session_write_close();

        $this->stockopname->reset_stale_qrcode_process(1);
        $summary = $this->stockopname->qrcode_summary($qtyMode);
        $this->raw_json([
            'success' => true,
            'total' => $summary['total'],
            'done' => $summary['done'],
            'pending' => $summary['pending'],
            'failed' => $summary['failed'],
            'qty_zero_total' => $this->stockopname->count_all_master_barang('zero'),
        ]);
    }

    public function qrcode_summary()
    {
        $this->require_superadmin_master_opname();
        $this->qrcode_summary_response('positive');
    }

    public function qrcode_qty_zero_summary()
    {
        $this->require_superadmin_master_opname();
        $this->qrcode_summary_response('zero');
    }

    private function process_qrcode_row($row, $force = false)
    {
        return ['success' => false, 'message' => 'Generate QR dipindahkan ke Master Barang. Gunakan tombol QR / Cetak pada barang.'];
    }

    private function run_qrcode_batch($mode = 'normal', $qtyMode = 'positive')
    {
        return $this->raw_json(['success' => false, 'message' => 'Generate QR tersedia pada Master Barang.']);
    }

    public function qrcode_generate_batch()
    {
        $this->require_superadmin_master_opname();
        $this->run_qrcode_batch('normal');
    }

    public function qrcode_qty_zero_generate_batch()
    {
        $this->require_superadmin_master_opname();
        $this->run_qrcode_batch('normal', 'zero');
    }

    public function qrcode_retry_failed()
    {
        $this->require_superadmin_master_opname();
        $this->run_qrcode_batch('retry');
    }

    public function qrcode_qty_zero_retry_failed()
    {
        $this->require_superadmin_master_opname();
        $this->run_qrcode_batch('retry', 'zero');
    }

    private function qrcode_failed_list_response($qtyMode = 'positive')
    {
        if (!$this->ensure_qrcode_ready()) {
            return;
        }

        $rows = $this->stockopname->failed_qrcode_list((int)$this->input->get('limit', true) ?: 100, $qtyMode);
        $this->raw_json([
            'success' => true,
            'total' => count($rows),
            'data' => $rows,
        ]);
    }

    public function qrcode_failed_list()
    {
        $this->require_superadmin_master_opname();
        $this->qrcode_failed_list_response('positive');
    }

    public function qrcode_qty_zero_failed_list()
    {
        $this->require_superadmin_master_opname();
        $this->qrcode_failed_list_response('zero');
    }

    public function qrcode_reset()
    {
        $this->require_superadmin_master_opname();
        if (strtoupper((string)$this->input->method(true)) !== 'POST') {
            return $this->json(false, 'Request reset harus menggunakan POST.');
        }

        if (!$this->ensure_qrcode_ready()) {
            return;
        }

        $paths = $this->stockopname->qrcode_file_paths_for_reset();
        foreach (glob(FCPATH . 'assets/qrcode/stockopname/OP_*.png') ?: [] as $file) {
            if (is_file($file)) {
                $paths[] = $file;
            }
        }

        $reset = $this->stockopname->reset_qrcode_opname_data();
        if (empty($reset['success'])) {
            return $this->json(false, $reset['message'] ?? 'Gagal reset QRCode opname.');
        }

        $files = $this->delete_qrcode_files($paths);
        $summary = $this->stockopname->qrcode_summary();

        $this->json(true, 'Reset QRCode opname berhasil diproses.', [
            'deleted_files' => $files['deleted'],
            'failed_files' => $files['failed'],
            'opname_rows_deleted' => (int)($reset['opname_rows_deleted'] ?? 0),
            'master_rows_reset' => (int)($reset['master_rows_reset'] ?? 0),
            'summary' => $summary,
        ]);
    }

    public function ajax_generate_qrcode()
    {
        $this->require_superadmin_master_opname();
        $this->generate_asset('qrcode');
    }

    public function ajax_generate_qrcode_qty_zero()
    {
        $this->generate_asset('qrcode', 'zero');
    }

    public function ajax_generate_all_qrcode()
    {
        $this->require_superadmin_master_opname();
        $this->run_qrcode_batch('normal');
    }

    public function ajax_generate_barcode()
    {
        $this->require_superadmin_master_opname();
        $this->generate_asset('barcode');
    }

    private function asset_relative_path($row, $type)
    {
        $safeCode = $this->clean_asset_filename(($row['kode_barang'] ?? '') . '_' . ($row['nama_barang'] ?? ''));
        $id = (int)($row['id'] ?? 0);

        if ($type === 'barcode') {
            return 'assets/codegenerator/barcode/barcode_' . $safeCode . '_' . $id . '.png';
        }

        return 'assets/codegenerator/qrcode/qr_' . $safeCode . '_' . $id . '.png';
    }

    private function generate_asset($type, $qtyMode = 'positive')
    {
        if ($type === 'qrcode') { return $this->json(false, 'Generate QR tersedia pada Master Barang.'); }

        $id = $this->input->post('id', true);
        $regenerate = (string)$this->input->post('regenerate', true) === '1';

        if (!ctype_digit((string)$id)) {
            return $this->json(false, 'ID barang tidak valid.');
        }

        if ($type === 'qrcode') {
            $ready = $this->stockopname->ensure_qrcode_columns();
            if (empty($ready['success'])) {
                return $this->json(false, $ready['message'] ?? 'Struktur database QRCode belum siap.');
            }
        } else {
            $this->stockopname->ensure_master_code_columns();
        }
        $row = $this->stockopname->get_master_barang_by_id((int)$id, $qtyMode);
        if (!$row) {
            return $this->json(false, 'Data barang tidak ditemukan.');
        }

        $kodeBarang = trim((string)($row['kode_barang'] ?? ''));
        if ($kodeBarang === '') {
            return $this->json(false, 'Kode barang wajib diisi sebelum generate asset.');
        }

        $field = $type === 'barcode' ? 'barcode' : 'qrcode';
        if ($type === 'qrcode' && !$regenerate && strtoupper((string)($row['qrcode_status'] ?? '')) === 'DONE' && trim((string)($row['qrcode_file'] ?? $row['qrcode'] ?? '')) !== '') {
            return $this->json(false, 'QRCode sudah ada. Gunakan mode regenerate jika perlu membuat ulang.');
        }
        if ($type !== 'qrcode' && !$regenerate && trim((string)($row[$field] ?? '')) !== '') {
            return $this->json(false, ucfirst($field) . ' sudah ada. Gunakan mode regenerate jika perlu membuat ulang.');
        }

        $scanValue = $type === 'qrcode' ? $this->qrcode_scan_value($row) : (string)(int)$row['id'];
        $relativePath = $type === 'qrcode' ? $this->qrcode_relative_path($row) : $this->asset_relative_path($row, $type);
        $targetFile = FCPATH . $relativePath;

        try {
            if ($type === 'barcode') {
                $created = $this->karisma_code_generator->barcode($scanValue, $targetFile);
            } else {
                $created = $this->karisma_code_generator->qrcode($scanValue, $targetFile);
            }
        } catch (Exception $e) {
            return $this->json(false, $e->getMessage());
        }

        if (!$created || !is_file($targetFile)) {
            return $this->json(false, 'Gagal membuat file asset.');
        }

        $updated = $type === 'qrcode'
            ? $this->stockopname->mark_qrcode_success((int)$id, $scanValue, $relativePath)
            : $this->stockopname->update_asset_master_barang((int)$id, [$field => $relativePath]);

        if (!$updated) {
            return $this->json(false, 'Asset dibuat, tetapi gagal update path ke database.');
        }

        $this->json(true, ucfirst($field) . ' berhasil digenerate.', [
            'id' => (int)$id,
            'type' => $field,
            'value' => $scanValue,
            'path' => $relativePath,
            'url' => base_url($relativePath),
            'qrcode' => $this->asset_payload($field === 'qrcode' ? $relativePath : ($row['qrcode'] ?? '')),
            'barcode' => $this->asset_payload($field === 'barcode' ? $relativePath : ($row['barcode'] ?? '')),
        ]);
    }

    private function preview_asset_response($qtyMode = 'positive')
    {
        $id = $this->input->post('id', true);
        if (!ctype_digit((string)$id)) {
            return $this->json(false, 'ID barang tidak valid.');
        }

        $this->stockopname->ensure_master_code_columns();
        $row = $this->stockopname->get_master_barang_by_id((int)$id, $qtyMode);
        if (!$row) {
            return $this->json(false, 'Data tidak ditemukan');
        }

        $qr = $this->catalog_qrcode_for_master_row($row);
        $this->json(true, 'Preview asset berhasil dimuat.', [
            'id' => (int)$row['id'],
            'kode_barang' => $row['kode_barang'],
            'nama_barang' => $row['nama_barang'],
            'expired_date' => $row['expired_date'],
            'no_lot' => $row['no_lot'],
            'qty' => $row['qty'],
            'qty_pcs' => $row['qty_pcs'],
            'qty_box' => $row['qty_box'],
            'inventory_date' => $this->tanggal_indo(),
            'value' => $qr['value'],
            'qrcode' => $qr['asset'],
            'barcode' => $this->asset_payload($row['barcode'] ?? ''),
        ]);
    }

    public function ajax_preview_asset()
    {
        $this->preview_asset_response('positive');
    }

    public function ajax_preview_asset_qty_zero()
    {
        $this->preview_asset_response('zero');
    }

    private function print_qrcode_response($id = null, $qtyMode = 'positive')
    {
        if (!ctype_digit((string)$id)) {
            show_error('ID barang tidak valid.', 400, 'Data Tidak Valid');
        }

        $this->stockopname->ensure_master_code_columns();
        $row = $this->stockopname->get_master_barang_by_id((int)$id, $qtyMode);
        if (!$row) {
            show_error('Data barang tidak ditemukan.', 404, 'Data Tidak Ditemukan');
        }
        $qr = $this->catalog_qrcode_for_master_row($row);

        $data = [
            'page_title' => 'Print Kartu Stock - ' . $row['nama_barang'],
            'barang' => $row,
            'qrcode' => $qr['asset'],
            'scan_value' => $qr['value'],
            'inventory_date' => $this->tanggal_indo(),
        ];

        $this->load->view('content/admin/stockopname_print_qrcode.php', $data);
    }

    public function print_qrcode($id = null)
    {
        $this->require_superadmin_master_opname();
        $this->print_qrcode_response($id, 'positive');
    }

    public function print_qrcode_qty_zero($id = null)
    {
        $this->require_superadmin_master_opname();
        $this->print_qrcode_response($id, 'zero');
    }

    private function print_preview_asset_response($qtyMode = 'positive')
    {
        $this->stockopname->ensure_qrcode_columns();
        $rows = $this->stockopname->get_master_barang_print_assets($qtyMode);
        $items = [];
        foreach ($rows as $row) {
            $qr = $this->catalog_qrcode_for_master_row($row);
            if($qr['value']==='') continue;
            $items[] = [
                'barang' => $row,
                'qrcode' => $qr['asset'],
                'scan_value' => $qr['value'],
            ];
        }

        $data = [
            'page_title' => 'Print Preview Asset Stockopname',
            'items' => $items,
            'inventory_date' => $this->tanggal_indo(),
        ];

        $this->load->view('content/admin/stockopname_print_preview_asset.php', $data);
    }

    public function print_preview_asset()
    {
        $this->print_preview_asset_response('positive');
    }

    public function print_preview_asset_qty_zero()
    {
        $this->print_preview_asset_response('zero');
    }

    public function ajax_master_barang_positive_qty_pcs_ids()
    {
        $this->require_superadmin_master_opname();
        $ids = $this->stockopname->get_master_barang_ids_with_positive_qty_pcs('positive');
        $this->json(true, count($ids) . ' barang dengan Qty Pcs lebih dari 0 dipilih.', [
            'ids' => $ids,
            'total' => count($ids),
        ]);
    }

    public function print_kartu_stock_sebagian()
    {
        $this->require_superadmin_master_opname();
        if (strtoupper((string)$this->input->method(true)) !== 'POST') {
            show_error('Request print sebagian harus menggunakan POST.', 405, 'Metode Tidak Diizinkan');
        }

        $rawIds = (string)$this->input->post('selected_ids', false);
        $decodedIds = json_decode($rawIds, true);
        if (!is_array($decodedIds)) {
            show_error('Daftar barang yang dipilih tidak valid.', 400, 'Data Tidak Valid');
        }

        $ids = array_values(array_unique(array_filter(array_map('intval', $decodedIds), function ($id) {
            return $id > 0;
        })));
        if (empty($ids)) {
            show_error('Belum ada barang yang dipilih untuk print.', 400, 'Data Tidak Valid');
        }
        if (count($ids) > 5000) {
            show_error('Maksimal 5.000 barang dalam satu proses print.', 400, 'Terlalu Banyak Data');
        }

        @set_time_limit(0);
        $this->stockopname->ensure_qrcode_columns();
        $rows = $this->stockopname->get_master_barang_by_ids($ids, 'positive');
        $items = [];
        $failed = 0;

        foreach ($rows as $row) {
            $qr = $this->catalog_qrcode_for_master_row($row);
            if (empty($qr['asset']['exists'])) { $failed++; }
            $items[] = [
                'barang' => $row,
                'qrcode' => $qr['asset'],
                'scan_value' => $qr['value'],
            ];
        }

        $data = [
            'page_title' => 'Print Sebagian Kartu Stock',
            'print_heading' => 'Print Sebagian Kartu Stock',
            'print_description' => count($items) . ' kartu terpilih, QR belum tersedia: ' . $failed,
            'items' => $items,
            'inventory_date' => $this->tanggal_indo(),
        ];

        $this->load->view('content/admin/stockopname_print_preview_asset.php', $data);
    }

    public function print_kartu_stock_3075_3267()
    {
        $this->require_superadmin_master_opname();
        $startId = 3075;
        $endId = 3267;

        @set_time_limit(0);
        $this->stockopname->ensure_qrcode_columns();
        $rows = $this->stockopname->get_master_barang_by_id_range($startId, $endId);
        $items = [];
        $failed = 0;

        foreach ($rows as $row) {
            $qr = $this->catalog_qrcode_for_master_row($row);
            if (empty($qr['asset']['exists'])) { $failed++; }
            $items[] = [
                'barang' => $row,
                'qrcode' => $qr['asset'],
                'scan_value' => $qr['value'],
            ];
        }

        $data = [
            'page_title' => 'Print Kartu Stock ID ' . $startId . '-' . $endId,
            'print_heading' => 'Print Kartu Stock ID ' . $startId . '-' . $endId,
            'print_description' => count($items) . ' kartu, QR belum tersedia: ' . $failed,
            'items' => $items,
            'inventory_date' => $this->tanggal_indo(),
        ];

        $this->load->view('content/admin/stockopname_print_preview_asset.php', $data);
    }

    public function ajax_delete_catalog_qrcode()
    {
        if (!$this->is_superadmin()) {
            return $this->json(false, 'Hanya Superadmin yang memiliki hak akses menghapus QR Code.');
        }
        if (!$this->master_action_allowed()) {
            return $this->json(false, 'Request atau token keamanan tidak valid.');
        }
        $id = (int)$this->input->post('id', true);
        $expiredDate = trim((string)$this->input->post('expired_date', true));
        if ($id <= 0 || !$this->valid_date($expiredDate)) {
            return $this->json(false, 'Data barang atau tanggal kadaluarsa tidak valid.');
        }

        $result = $this->stockopname->delete_catalog_qrcode($id, $expiredDate, (string)$this->session->userdata('username'));
        return $this->json(!empty($result['status']), $result['message'] ?? 'QR Code berhasil dihapus.', $result);
    }

    public function ajax_regenerate_catalog_qrcode()
    {
        if (!$this->is_superadmin()) {
            return $this->json(false, 'Hanya Superadmin yang memiliki hak akses generate ulang QR Code.');
        }
        if (!$this->master_action_allowed()) {
            return $this->json(false, 'Request atau token keamanan tidak valid.');
        }
        $id = (int)$this->input->post('id', true);
        $expiredDate = trim((string)$this->input->post('expired_date', true));
        if ($id <= 0 || !$this->valid_date($expiredDate)) {
            return $this->json(false, 'Data barang atau tanggal kadaluarsa tidak valid.');
        }

        $this->stockopname->delete_catalog_qrcode($id, $expiredDate, (string)$this->session->userdata('username'));
        $result = $this->create_catalog_qrcode($id, $expiredDate, false);
        return $this->json(!empty($result['status']), !empty($result['status']) ? 'QR Code berhasil digenerate ulang.' : ($result['message'] ?? 'Gagal generate ulang QR Code.'), !empty($result['status']) ? ['url' => base_url($result['path'])] : []);
    }

    public function ajax_regenerate_all_catalog_qrcode()
    {
        if (!$this->is_superadmin()) {
            return $this->json(false, 'Hanya Superadmin yang memiliki hak akses generate ulang batch.');
        }
        if (!$this->master_action_allowed()) {
            return $this->json(false, 'Request atau token keamanan tidak valid.');
        }

        $offset = max(0, (int)$this->input->post('offset', true));
        $this->load->library('Zahir_lot_catalog');
        $source = $this->zahir_lot_catalog->rows(false);
        if (empty($source['status'])) {
            return $this->json(false, $source['message'] ?? 'Data Zahir tidak tersedia.');
        }

        $rows = $source['data'];
        $total = count($rows);
        $batchSize = 100;
        $batch = array_slice($rows, $offset, $batchSize);
        $actualBatchCount = count($batch);
        $counts = ['success' => 0, 'skipped' => 0, 'failed' => 0];
        $errors = [];
        $actor = (string)$this->session->userdata('username');

        foreach ($batch as $item) {
            $local = $this->stockopname->catalog_item(0, '', $item['nama_barang']) ?: $this->stockopname->catalog_item(0, $item['kode_barang']);
            if (!$local && !empty($item['p']) && !empty($item['l']) && !empty($item['t'])) {
                $this->stockopname->create_master_barang_catalog([
                    'kd_barang' => $item['kode_barang'],
                    'nama_barang' => $item['nama_barang'],
                    'p' => (int)$item['p'],
                    'l' => (int)$item['l'],
                    't' => (int)$item['t'],
                ]);
                $local = $this->stockopname->catalog_item(0, '', $item['nama_barang']) ?: $this->stockopname->catalog_item(0, $item['kode_barang']);
            }

            if (!$local) {
                $counts['failed']++;
                $errors[] = $item['nama_barang'] . ': Master Barang lokal tidak cocok atau belum tersedia.';
                continue;
            }

            $this->stockopname->delete_catalog_qrcode((int)$local['barang_id'], $item['expired_date'], $actor);
            $result = $this->create_catalog_qrcode((int)$local['barang_id'], $item['expired_date'], false);
            $key = !empty($result['status']) ? 'success' : 'failed';
            $counts[$key]++;
            if ($key === 'failed') {
                $errors[] = $item['kode_barang'] . ': ' . ($result['message'] ?? 'Gagal membuat QR.');
            }
        }

        $nextOffset = min($total, $offset + $actualBatchCount);
        return $this->json(true, 'Batch generate ulang selesai.', [
            'total'             => $total,
            'offset'            => $offset,
            'batch_size'        => $actualBatchCount,
            'next_offset'       => $nextOffset,
            'counts'            => $counts,
            'errors'            => array_slice($errors, 0, 10),
            'is_last_batch'     => ($nextOffset >= $total)
        ]);
    }

    public function ajax_print_queue_list()
    {
        $this->load->model('admin/M_Stockopname_print_queue', 'print_queue');
        $username = (string)$this->session->userdata('username');
        $rows = $this->print_queue->get_queue($username);

        foreach ($rows as &$item) {
            $local = $this->stockopname->catalog_item(0, $item['kode_barang']);
            $saved = $local ? $this->stockopname->catalog_qrcode_saved((int)$local['barang_id'], $item['expired_date']) : null;
            $item['qrcode_url'] = ($saved && is_file(FCPATH . $saved['qrcode_file'])) ? base_url($saved['qrcode_file']) : '';
            $item['qrcode_ready'] = !empty($item['qrcode_url']);
        }
        unset($item);

        return $this->json(true, 'Data antrian cetak berhasil dimuat.', $rows);
    }

    public function ajax_print_queue_add()
    {
        if (!$this->master_action_allowed()) {
            return $this->json(false, 'Request atau token keamanan tidak valid.');
        }

        $this->load->model('admin/M_Stockopname_print_queue', 'print_queue');
        $username = (string)$this->session->userdata('username');
        if ($username === '') {
            return $this->json(false, 'Sesi pengguna tidak valid.');
        }

        $rawItems = $this->input->post('items', true);
        $items = [];
        if (is_string($rawItems)) {
            $decoded = json_decode($rawItems, true);
            if (is_array($decoded)) {
                $items = $decoded;
            }
        } elseif (is_array($rawItems)) {
            $items = $rawItems;
        }

        if (empty($items)) {
            $singleCode = trim((string)$this->input->post('kode_barang', true));
            if ($singleCode !== '') {
                $items[] = [
                    'kode_barang'  => $singleCode,
                    'nama_barang'  => trim((string)$this->input->post('nama_barang', true)),
                    'expired_date' => trim((string)$this->input->post('expired_date', true)),
                    'copies'       => max(1, (int)$this->input->post('copies', true)),
                ];
            }
        }

        if (empty($items)) {
            return $this->json(false, 'Tidak ada barang yang dipilih untuk dimasukkan antrian.');
        }

        $addedCount = 0;
        foreach ($items as $item) {
            $code = trim((string)($item['kode_barang'] ?? ''));
            $name = trim((string)($item['nama_barang'] ?? ''));
            $exp = trim((string)($item['expired_date'] ?? ''));
            $copies = max(1, (int)($item['copies'] ?? 1));

            if ($code !== '') {
                $this->print_queue->add_item($username, $code, $name, $exp, $copies);
                $addedCount++;
            }
        }

        return $this->json(true, $addedCount . ' barang berhasil dimasukkan ke antrian cetak.');
    }

    public function ajax_print_queue_update_copies()
    {
        if (!$this->master_action_allowed()) {
            return $this->json(false, 'Request atau token keamanan tidak valid.');
        }

        $this->load->model('admin/M_Stockopname_print_queue', 'print_queue');
        $username = (string)$this->session->userdata('username');
        $id = (int)$this->input->post('id', true);
        $copies = max(1, (int)$this->input->post('copies', true));

        $ok = $this->print_queue->update_copies($username, $id, $copies);
        return $this->json($ok, $ok ? 'Jumlah salinan berhasil diperbarui.' : 'Gagal memperbarui jumlah salinan.');
    }

    public function ajax_print_queue_remove()
    {
        if (!$this->master_action_allowed()) {
            return $this->json(false, 'Request atau token keamanan tidak valid.');
        }

        $this->load->model('admin/M_Stockopname_print_queue', 'print_queue');
        $username = (string)$this->session->userdata('username');
        $id = (int)$this->input->post('id', true);

        $ok = $this->print_queue->remove_item($username, $id);
        return $this->json($ok, $ok ? 'Barang berhasil dihapus dari antrian.' : 'Gagal menghapus barang dari antrian.');
    }

    public function ajax_print_queue_clear()
    {
        if (!$this->master_action_allowed()) {
            return $this->json(false, 'Request atau token keamanan tidak valid.');
        }

        $this->load->model('admin/M_Stockopname_print_queue', 'print_queue');
        $username = (string)$this->session->userdata('username');

        $this->print_queue->clear_queue($username);
        return $this->json(true, 'Antrian cetak berhasil dikosongkan.');
    }
}
