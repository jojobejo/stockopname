<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Sales extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Sales');
    }


    public function  dashboard_sales()
    {
        $data['page_title']     = 'DELIVERY ORDER - SALES';

        $this->load->view('partial/main/header.php', $data);
        $this->load->view("content/sales/dashboard_sales");
        $this->load->view('partial/main/footer.php');
    }
}
