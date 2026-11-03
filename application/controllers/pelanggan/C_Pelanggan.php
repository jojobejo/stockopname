<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Pelanggan extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Pelanggan');
    }

    public function index()
    {
        $data['page_title'] = 'KARISMA';

        $this->load->view('partial/main/headeripkp.php', $data);
        $this->load->view('content/pelanggan/body.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function input_nilai($nilai)
    {
        date_default_timezone_set('Asia/Jakarta');
        $tgl = date("Y-m-d");
        $jm  = date("H:i");
        $t  = $this->input->post('a');

        $ainput = array(
            'nilai_kp'  => $nilai,
            'tgl_input' => $tgl,
            'jam_input' => $jm,
            'create_at' => $t
        );

        $this->M_Pelanggan->addnilai($ainput);
        redirect('kepuasan_pelanggan');
    }

    public function rating_review()
    {
        $data['page_title'] = 'KARISMA';


        $data['dataplanggan'] = $this->M_Pelanggan->getdatapelanggan();
        $data['nilai'] = $this->M_Pelanggan->nilaiall()->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/pelanggan/bodyreview.php', $data);
        $this->load->view('partial/main/footer.php');
    }
}
