<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('Auth');
        }

        $this->load->model('M_Logistik');
        $this->load->model('M_Hrd');
    }

    public function index()
    {

        $lvuser     = (int)$this->session->userdata('lv');
        $jobdesk    = strtoupper(trim((string)$this->session->userdata('jobdesk')));
        $username   = strtolower(trim((string)$this->session->userdata('username')));
        $is_admin_dashboard = (bool)$this->session->userdata('is_admin_dashboard') || $username === 'admin' || ($lvuser === 1 && $jobdesk === 'ADMIN');

        $data['page_title'] = 'KARISMA';
        $data['is_admin_dashboard'] = $is_admin_dashboard;

        // LV-1 = ADMIN
        // LV-2 = karyawan
        // LV-3 = Kadep
        // LV-4 = kusus
        // LV-5 = Direktur

        if ($is_admin_dashboard) {
            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/dashboard.php', $data);
            $this->load->view('partial/main/footer.php');
        } elseif ($lvuser === 1 && $jobdesk == 'LOGISTIK') {
            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/logistik/body.php', $data);
            $this->load->view('partial/main/footer.php');
        } elseif ($lvuser === 1 && $jobdesk == 'ADMINKEU') {
            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/dashboard.php', $data);
            $this->load->view('partial/main/footer.php');
        } elseif ($lvuser === 1 && $jobdesk == 'ADMINGA') {
            $data['page_title']  = 'Schedule Direktur';
            $data['getschedule'] = $this->M_Hrd->getdataschedule()->result();
            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/schedule/body.php', $data);
            $this->load->view('content/schedule/ajaxschedule.php', $data);
            $this->load->view('partial/main/footer.php');
        } elseif ($lvuser === 5 && $jobdesk == 'DIREKTUR') {
            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/dashboard.php', $data);
            $this->load->view('partial/main/footer.php');
        } else {
            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/body-karyawan.php', $data);
            $this->load->view('partial/main/footer.php');
        }
    }
}
