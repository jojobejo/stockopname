<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Menu extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('master/M_Menu', 'model');
        $this->load->library('permission');
        if (!$this->session->userdata('logged_in')) redirect('Auth');
        $this->permission->guard('master/menu');
    }

    private function json($status, $message, $data = [])
    {
        while (ob_get_level()) ob_end_clean();
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => (bool)$status, 'message' => $message, 'data' => $data]));
    }

    public function index()
    {
        $data['page_title'] = 'KARISMA ERP - Master Menu';
        $data['parents'] = $this->model->all_active();
        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/master/menu/index.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function list()
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->model->datatable($this->input->post(null, true) ?: [])));
    }

    public function detail($id = null)
    {
        $row = $this->model->find((int)$id);
        $this->json((bool)$row, $row ? 'Data ditemukan' : 'Data tidak ditemukan', $row ?: []);
    }

    public function save()
    {
        $input = $this->input->post(null, true) ?: [];
        if (trim((string)($input['nama_menu'] ?? '')) === '') return $this->json(false, 'Nama menu wajib diisi.');
        $id = $this->model->save($input);
        $this->json((bool)$id, $id ? 'Data berhasil disimpan' : 'Data gagal disimpan', ['id' => $id]);
    }

    public function update($id = null)
    {
        $input = $this->input->post(null, true) ?: [];
        if (trim((string)($input['nama_menu'] ?? '')) === '') return $this->json(false, 'Nama menu wajib diisi.');
        $ok = $this->model->update((int)$id, $input);
        $this->json($ok, $ok ? 'Data berhasil diperbarui' : 'Data gagal diperbarui');
    }

    public function delete($id = null)
    {
        $ok = $this->model->delete((int)$id);
        $this->json($ok, $ok ? 'Data berhasil dihapus' : 'Data gagal dihapus');
    }

    public function sidebar()
    {
        $akses = $this->session->userdata('akses_lv_id') ?: $this->session->userdata('lv');
        $this->json(true, 'OK', $this->model->sidebar_tree($akses));
    }
}
