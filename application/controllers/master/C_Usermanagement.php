<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Usermanagement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('master/M_Usermanagement', 'users');
        $this->load->model('master/M_Jobdesk', 'jobdeskModel');
        $this->load->model('master/M_Akseslevel', 'aksesModel');
        $this->load->library('permission');
        $this->guard();
    }

    private function guard()
    {
        if (!$this->session->userdata('logged_in')) {
            redirect('Auth');
        }
        $this->permission->guard('master/user-management');
    }

    private function json($status, $message, $data = [])
    {
        while (ob_get_level()) ob_end_clean();
        $this->output->set_content_type('application/json')->set_output(json_encode([
            'status' => (bool)$status,
            'message' => $message,
            'data' => $data,
        ]));
    }

    private function post()
    {
        return $this->input->post(null, true) ?: [];
    }

    public function index()
    {
        $data['page_title'] = 'KARISMA ERP - User Management';
        $data['jobdesk'] = $this->jobdeskModel->options();
        $data['akses_level'] = $this->aksesModel->options();
        $data['departemen'] = $this->users->distinct_options('departemen');
        $data['tim'] = $this->users->distinct_options('tim');
        $data['wilayah'] = $this->users->distinct_options('wilayah');
        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/master/usermanagement/index.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function list()
    {
        $this->output->set_content_type('application/json')->set_output(json_encode($this->users->datatable($this->post())));
    }

    public function detail($id = null)
    {
        $row = $this->users->find((int)$id);
        $this->json((bool)$row, $row ? 'Data ditemukan' : 'Data tidak ditemukan', $row ?: []);
    }

    private function validate_user($input, $id = null)
    {
        foreach (['nik' => 'NIK', 'nm_karyawan' => 'Nama karyawan', 'username' => 'Username', 'jobdesk' => 'Jobdesk', 'akses_lv' => 'Akses level'] as $field => $label) {
            if (trim((string)($input[$field] ?? '')) === '' && trim((string)($input[$field . '_id'] ?? '')) === '') {
                return $label . ' wajib diisi.';
            }
        }
        if (!$id && strlen((string)($input['password'] ?? '')) < 6) return 'Password minimum 6 karakter.';
        if (!$this->users->unique('username', $input['username'] ?? '', $id)) return 'Username sudah digunakan.';
        if (!$this->users->unique('nik', $input['nik'] ?? '', $id)) return 'NIK sudah digunakan.';
        return true;
    }

    private function uploaded_photo()
    {
        if (empty($_FILES['foto']['name'])) return null;
        $dir = FCPATH . 'uploads/profile/';
        if (!is_dir($dir)) mkdir($dir, 0755, true);
        $config = [
            'upload_path' => $dir,
            'allowed_types' => 'jpg|jpeg|png|webp',
            'max_size' => 2048,
            'encrypt_name' => true,
        ];
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('foto')) {
            return ['error' => strip_tags($this->upload->display_errors())];
        }
        return ['path' => 'uploads/profile/' . $this->upload->data('file_name')];
    }

    public function save()
    {
        $input = $this->post();
        $photo = $this->uploaded_photo();
        if (isset($photo['error'])) return $this->json(false, $photo['error']);
        if (isset($photo['path'])) $input['foto'] = $photo['path'];

        $valid = $this->validate_user($input);
        if ($valid !== true) return $this->json(false, $valid);
        $id = $this->users->save($input);
        $this->json((bool)$id, $id ? 'Data berhasil disimpan' : 'Data gagal disimpan', ['id' => $id]);
    }

    public function update($id = null)
    {
        $input = $this->post();
        $photo = $this->uploaded_photo();
        if (isset($photo['error'])) return $this->json(false, $photo['error']);
        if (isset($photo['path'])) $input['foto'] = $photo['path'];

        $valid = $this->validate_user($input, (int)$id);
        if ($valid !== true) return $this->json(false, $valid);
        $ok = $this->users->update((int)$id, $input);
        $this->json($ok, $ok ? 'Data berhasil diperbarui' : 'Data gagal diperbarui');
    }

    public function delete($id = null)
    {
        $ok = $this->users->delete((int)$id);
        $this->json($ok, $ok ? 'Data berhasil dihapus' : 'Data gagal dihapus');
    }

    public function reset_password($id = null)
    {
        $password = trim((string)$this->input->post('password', true));
        if (strlen($password) < 6) return $this->json(false, 'Password minimum 6 karakter.');
        $ok = $this->users->reset_password((int)$id, $password);
        $this->json($ok, $ok ? 'Password berhasil direset' : 'Password gagal direset');
    }

    public function toggle_status($id = null)
    {
        $ok = $this->users->toggle_status((int)$id);
        $this->json($ok, $ok ? 'Status user berhasil diperbarui' : 'Kolom status belum tersedia atau data tidak ditemukan');
    }

    public function select_options()
    {
        $data = [
            'jobdesk' => $this->jobdeskModel->options(),
            'akses_level' => $this->aksesModel->options(),
            'departemen' => $this->users->distinct_options('departemen'),
            'tim' => $this->users->distinct_options('tim'),
            'wilayah' => $this->users->distinct_options('wilayah'),
        ];
        $this->json(true, 'OK', $data);
    }
}
