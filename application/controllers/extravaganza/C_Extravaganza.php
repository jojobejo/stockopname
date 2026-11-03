<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Extravaganza extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Extravaganza');
        $this->load->helper('tanggal_helper');
    }

    public function Index()
    {
        $data['page_title'] = 'KARISMA';

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/extravaganza/body.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    // EXTRAVAGANZA UNDIAN
    public function undian()
    {
        $data['page_title'] = 'KARISMA UNDIAN';

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/extravaganza/undian/body.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function save_win()
    {
        header('Content-Type: application/json');

        $noundi = $this->input->post('noundi');
        $kat_undi = 1;
        $ket_undi = 1;

        $nm_win = $this->M_Extravaganza->get_win($kat_undi, $noundi);

        if (!$nm_win) {
            echo json_encode(['status' => 'notfound']);
            return;
        }

        // Validasi format (2 digit angka)
        if (!preg_match('/^\d{1,2}$/', $noundi) || $noundi == 0) {
            http_response_code(400);
            echo json_encode(['status' => 'invalid']);
            return;
        }

        $exists = $this->db->get_where('tb_pemenang', ['noundi' => $noundi])->num_rows();
        if ($exists > 0) {
            echo json_encode(['status' => 'exists']);
            return;
        }

        $data = [
            'kat_undi' => $kat_undi,
            'noundi'   => $noundi,
            'nama_win' => $nm_win->nama_customer,
            'ket_undi' => $ket_undi
        ];

        $insert = $this->db->insert('tb_pemenang', $data);

        if ($insert) {
            echo json_encode([
                'status' => 'ok',
                'nama' => $nm_win->nama_customer
            ]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error']);
        }
    }

    public function get_customer_by_nomor()
    {
        $noundi = $this->input->post('noundi');
        $query = $this->db->get_where('tb_customer_list_undian', ['noundi' => $noundi]);
        $result = $query->row();

        if ($result) {
            echo json_encode(['status' => 'success', 'nama_customer' => $result->nama_customer]);
        } else {
            echo json_encode(['status' => 'error', 'nama_customer' => null]);
        }
    }
}
