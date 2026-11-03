<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Zahir extends CI_Controller
{

    public function index()
    {
        $this->firebird = $this->load->database('firebird', TRUE);
        $query = $this->firebird->query('SELECT FIRST 10 * FROM PENJUALAN');

        echo "<pre>";
        print_r($query->result());
        echo "</pre>";
    }
    
}
