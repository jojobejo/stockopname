<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Schedule extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Hrd');
    }

    public function index()
    {
        $data['page_title']  = 'Schedule Direktur';
        $data['getschedule'] = $this->M_Hrd->getdataschedule()->result();
        $data['tamu'] = $this->M_Hrd->getdataschedule()->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/schedule/body.php', $data);
        $this->load->view('content/schedule/ajaxschedule.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function act_schedule($act)
    {
        switch ($act) {
            case 'addschedule':

                $tanggal        = $this->input->post('tgl');
                $jam            = $this->input->post('jam');
                $instansi       = $this->input->post('instansi');
                $pic            = $this->input->post('pic');
                $estimasiwaktu  = $this->input->post('estimasi');
                $tujuan         = $this->input->post('tujuan');
                $keterangan     = $this->input->post('keterangan');

                $addschedule = array(
                    'tanggal'       => $tanggal,
                    'jam'           => $jam,
                    'suplier'       => $instansi,
                    'pic'           => $pic,
                    'estimasi_end'  => $estimasiwaktu,
                    'tujuan'        => $tujuan,
                    'status'        => '1',
                    'keterangan'    => $keterangan,
                );
                $this->M_Hrd->insertchedule($addschedule);
                redirect('schedule_direktur');
                break;

            case 'editschedule':
                $id             = $this->input->post('id');
                $tanggal        = $this->input->post('tgl');
                $jam            = $this->input->post('jam');
                $instansi       = $this->input->post('instansi');
                $pic            = $this->input->post('pic');
                $estimasiwaktu  = $this->input->post('estimasi');
                $tujuan         = $this->input->post('tujuan');
                $keterangan     = $this->input->post('keterangan');

                $addschedule = array(
                    'tanggal'       => $tanggal,
                    'jam'           => $jam,
                    'suplier'       => $instansi,
                    'pic'           => $pic,
                    'estimasi_end'  => $estimasiwaktu,
                    'tujuan'        => $tujuan,
                    'status'        => '1',
                    'keterangan'    => $keterangan
                );
                $this->M_Hrd->editchedule($id, $addschedule);
                redirect('schedule_direktur');
                break;

            case 'reschedule':
                $id             = $this->input->post('id');
                $tanggal        = $this->input->post('tgl');
                $jam            = $this->input->post('jam');
                $keterangan     = $this->input->post('keterangan');

                $addschedule = array(
                    'tanggal'       => $tanggal,
                    'jam'           => $jam,
                    'status'        => '1',
                    'keterangan'    => $keterangan,
                );
                $this->M_Hrd->reschedule($id, $addschedule);
                redirect('schedule_direktur');
                break;

            case 'cancelschedule':
                $id             = $this->input->post('id');

                $addschedule = array(
                    'status'        => '2',
                    'keterangan'    => 'cancel',
                );
                $this->M_Hrd->reschedule($id, $addschedule);
                redirect('schedule_direktur');
                break;

            case 'deleteschedule':
                $id             = $this->input->post('id');

                $this->M_Hrd->deleteschedule($id);

                redirect('schedule_direktur');
                break;

            case 'scheduledone':
                $id             = $this->input->post('id');

                $addschedule = array(
                    'status'        => '3',
                    'keterangan'    => 'DONE',
                );
                $this->M_Hrd->reschedule($id, $addschedule);
                redirect('schedule_direktur');
                break;

            case 'archived':
                $id             = $this->input->post('id');

                $addschedule = array(
                    'status'        => '4',
                    'keterangan'    => 'DONE-ARCHIVED',
                );
                $this->M_Hrd->reschedule($id, $addschedule);
                redirect('schedule_direktur');
                break;
        }
    }
}
