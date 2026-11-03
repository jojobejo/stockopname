<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Maintenance extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->model('');
    }

    public function index()
    {
        $data['page_title'] = 'KIU MAINTENANCE';

        $this->load->view('partial/main/header.php',$data);
        $this->load->view('content/body.php', $data);
        $this->load->view('partial/main/footer.php');
    }
    
}
