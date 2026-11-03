<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->model('M_User');
    }

    public function index()
    {
        $data['page_title'] = 'User Account';
        $data['alluser']    = $this->M_User->getAll();
        $data['kduser']     = $this->M_User->kduser();
        
        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/user/admin.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/user/ajaxUser.php');
    }

    public function addUser()
    {
        $kduser     = $this->input->post('kd_isi');
        $nmuser     = $this->input->post('nama_isi');
        $username   = $this->input->post('username_isi');
        $password   = $this->input->post('password_isi');
        $departemen = $this->input->post('dep_isi');
        $pass       = password_hash($password, PASSWORD_DEFAULT);

        $dataUser = array(
            'kode_user' => $kduser,
            'nama_user' => $nmuser,
            'departemen' => $departemen,
            'username'  => $username,
            'password'  => $pass,
            'akses_lv'  => '2',
        );

        $this->M_User->addUser($dataUser);
        redirect('userAdmin');
    }

    public function editeUser()
    {
        $username   = $this->input->post();
        $password   = $this->input->post();
        $nm_user    = $this->input->post();
        $kduser     = $this->input->post();
        $departemen = $this->input->post();

        $dataUser = array(
            'kode_user' => $kduser,
            'username'  => $username,
            'password'  => $password,
            'nama_user' => $nm_user,
            'departemen' => $departemen
        );

        $this->M_User->editedUser($dataUser, $kduser);
        redirect('userAdmin');
    }

    public function deleteUser()
    {
        $kduser = $this->input->post();
        $this->M_User->deleteUser($kduser);
        redirect('userAdmin');
    }
}
