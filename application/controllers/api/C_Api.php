<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Api extends CI_Controller
{
    protected $syncPrePoUrl = 'http://localhost/kiu_po/get_data_pre_po_erp';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Api/M_Api', 'apiModel');
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        $limit  = (int) $this->input->get('limit', true);
        $offset = (int) $this->input->get('offset', true);

        if ($limit <= 0) {
            $limit = 100;
        }

        if ($offset < 0) {
            $offset = 0;
        }

        $data = $this->apiModel->get_all($limit, $offset);

        return $this->_json_response([
            'status' => true,
            'total'  => count($data),
            'data'   => $data
        ]);
    }

    public function faktur($kode_faktur = null)
    {
        if (!$kode_faktur) {
            return $this->_json_response([
                'status'  => false,
                'message' => 'Kode faktur wajib diisi'
            ], 400);
        }

        $data = $this->apiModel->get_by_kode_faktur($kode_faktur);

        return $this->_json_response([
            'status' => !empty($data),
            'data'   => $data
        ]);
    }

    public function kdupdate($kdupdate = null)
    {
        if (!$kdupdate) {
            return $this->_json_response([
                'status'  => false,
                'message' => 'kdupdate wajib diisi'
            ], 400);
        }

        $data = $this->apiModel->get_by_kdupdate($kdupdate);

        return $this->_json_response([
            'status' => true,
            'total'  => count($data),
            'data'   => $data
        ]);
    }

    public function sync_pre_po_erp()
    {
        if (strtoupper($this->input->method(true)) !== 'POST') {
            return $this->_json_response([
                'status'  => false,
                'message' => 'Method tidak diizinkan'
            ], 405);
        }

        try {
            $result = $this->apiModel->sync_pre_po_from_remote($this->syncPrePoUrl);

            if (!$result['status']) {
                return $this->_json_response([
                    'status'   => false,
                    'message'  => $result['message'],
                    'inserted' => 0,
                    'updated'  => 0,
                    'skipped'  => (int) ($result['skipped'] ?? 0)
                ], $result['http_code']);
            }

            return $this->_json_response([
                'status'        => true,
                'message'       => 'Sinkronisasi berhasil',
                'inserted'      => (int) $result['inserted'],
                'updated'       => (int) $result['updated'],
                'skipped'       => (int) $result['skipped'],
                'discount_rows' => (int) ($result['discount_rows'] ?? 0),
                'invoice_adjustment_rows' => (int) ($result['invoice_adjustment_rows'] ?? 0),
                'total_fetched' => (int) $result['total_fetched'],
                'sync_time'     => $result['sync_time'],
                'rows'          => $this->apiModel->get_recent_pre_po(100),
                'last_sync'     => $this->apiModel->get_last_sync_info()
            ]);
        } catch (Exception $e) {
            log_message('error', 'sync_pre_po_erp exception: ' . $e->getMessage());

            return $this->_json_response([
                'status'   => false,
                'message'  => 'Gagal sinkronisasi: ' . $e->getMessage(),
                'inserted' => 0,
                'updated'  => 0,
                'skipped'  => 0
            ], 500);
        }
    }

    private function _json_response(array $payload, $httpCode = 200)
    {
        return $this->output
            ->set_status_header($httpCode)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}
