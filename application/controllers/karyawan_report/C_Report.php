<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Report extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_ReportUser');
        $this->load->library(['form_validation', 'session', 'upload']);
    }

    public function index()
    {
        $data['page_title'] = 'KARISMA';
        $data['laporan'] = $this->M_ReportUser->get_by_user($this->session->userdata('id'));

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/karyawan_report/dashboard.php', $data);
        $this->load->view('partial/main/footer.php');
    }
}
