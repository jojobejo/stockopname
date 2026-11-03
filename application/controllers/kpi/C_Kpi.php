<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Kpi extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Kpi');
    }

    public function index()
    {
        $data['page_title'] = 'KPI DIGITAL';
        $kd_user = $this->session->userdata('kduser');

        $data['kpi'] = $this->M_Kpi->getkpi($kd_user)->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/kpi/dashboard_kpi.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function indikator_kpi()
    {
        $action = $this->input->post('action');

        switch ($action) {
            case 'add_indikator_kpi':
                $kduser         = $this->input->post('kduser');
                $nmindikator    = $this->input->post('whatindikator');
                $bobot          = $this->input->post('nilaiindi');
                $tgl            = date('Y-m-d');

                $datakpi = array(
                    'kode_user'     => $kduser,
                    'nm_poin'       => $nmindikator,
                    'bobot_poin'    => $bobot,
                    'create_at'     => $tgl,
                    'last_updatedby' => $kduser
                );

                $this->M_Kpi->add_indikator_kpi($datakpi);
                redirect('dashboardkpi');
                break;
            case 'edit_indikator_kpi':
                $id             = $this->input->post('id');
                $kduser         = $this->input->post('kduser');
                $nmindikator    = $this->input->post('whatindikator');
                $bobot          = $this->input->post('nilaiindi');

                $datakpi = array(
                    'nm_poin'       => $nmindikator,
                    'bobot_poin'    => $bobot,
                    'last_updatedby' => $kduser
                );
                $this->M_Kpi->edit_indikator_kpi($id, $datakpi);

                redirect('dashboardkpi');
                break;
            case 'hapus_indikator_kpi':
                $id             = $this->input->post('id');

                $this->M_Kpi->edit_indikator_kpi($id);
                redirect('dashboardkpi');
                break;
        }
    }
}
