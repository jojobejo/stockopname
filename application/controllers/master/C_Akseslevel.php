<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Akseslevel extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('master/M_Akseslevel', 'model');
        $this->load->library('permission');
        if (!$this->session->userdata('logged_in')) redirect('Auth');
        $this->permission->guard('master/akses-level');
    }

    private function json($status, $message, $data = [])
    {
        while (ob_get_level()) ob_end_clean();
        $this->output->set_content_type('application/json')->set_output(json_encode(['status' => (bool)$status, 'message' => $message, 'data' => $data]));
    }

    public function index()
    {
        $data['page_title'] = 'KARISMA ERP - Akses Level';
        $data['akses_level'] = $this->model->options();
        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/master/akseslevel/index.php', $data);
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
        if (trim((string)($input['nama_akses_level'] ?? '')) === '') return $this->json(false, 'Nama akses level wajib diisi.');
        $id = $this->model->save($input);
        $this->json((bool)$id, $id ? 'Data berhasil disimpan' : 'Data gagal disimpan', ['id' => $id]);
    }

    public function update($id = null)
    {
        $input = $this->input->post(null, true) ?: [];
        if (trim((string)($input['nama_akses_level'] ?? '')) === '') return $this->json(false, 'Nama akses level wajib diisi.');
        $ok = $this->model->update((int)$id, $input);
        $this->json($ok, $ok ? 'Data berhasil diperbarui' : 'Data gagal diperbarui');
    }

    public function delete($id = null)
    {
        $ok = $this->model->delete((int)$id);
        $this->json($ok, $ok ? 'Data berhasil dihapus' : 'Data gagal dihapus');
    }

    public function matrix($id = null)
    {
        $this->json(true, 'OK', $this->model->matrix((int)$id));
    }

    public function update_permission()
    {
        $akses = (int)$this->input->post('akses_lv_id', true);
        $menu = (int)$this->input->post('id_menu', true);
        $key = trim((string)$this->input->post('permission', true));
        $value = (int)$this->input->post('value', true);
        $ok = $this->model->save_permission($akses, $menu, $key, $value);
        $this->json($ok, $ok ? 'Permission berhasil diperbarui' : 'Permission gagal diperbarui');
    }
}
