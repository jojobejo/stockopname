<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Coba1 extends CI_Controller
{

    public function index()
    {
        $this->load->model('M_Coba1');
        $this->load->library('pagination');

        // Konfigurasi pagination
        $config['total_rows'] = $this->M_Coba1->count_filtered_data();
        $config['per_page'] = 10; // Jumlah data per halaman
        $config['uri_segment'] = 3;

        // Styling pagination (opsional)
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';

        $this->pagination->initialize($config);

        // Ambil data
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['stocks'] = $this->M_Coba1->get_filtered_data($config['per_page'], $page);
        $data['pagination'] = $this->pagination->create_links();

        // Load view
        $this->load->view('content/keuangan/cobapagination.php', $data);
    }
}
