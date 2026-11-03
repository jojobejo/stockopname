<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Auth');
    }

    function index()
    {
        $this->load->view("partial/login/header");
        $this->load->view("content/login/body");
        $this->load->view("partial/login/footer");
    }

    function process()
    {
        $username = trim((string) $this->input->post('user_isi'));
        $password = (string) $this->input->post('pass_isi');
        $auth_candidates = method_exists($this->M_Auth, 'get_auth_candidates')
            ? $this->M_Auth->get_auth_candidates($username)
            : array_filter([$this->M_Auth->get_auth_user($username)]);
        $key = null;

        if (empty($auth_candidates)) {
            $this->M_Auth->log_login((object)['username' => $username], 'failed', 'Username tidak ditemukan');
            $this->session->set_flashdata("gagal", "username salah");
            redirect('Auth');
            return;
        }

        foreach ($auth_candidates as $candidate) {
            if ($this->M_Auth->verify_password($password, $candidate->password ?? '')) {
                $key = $candidate;
                break;
            }
        }

        if (!$key) {
            $this->M_Auth->log_login($auth_candidates[0], 'failed', 'Password salah');
            $this->session->set_flashdata("gagal", "username / password salah!!!");
            redirect('Auth');
            return;
        }

        if (isset($key->status) && (int)$key->status !== 1) {
            $this->M_Auth->log_login($key, 'blocked', 'User nonaktif');
            $this->session->set_flashdata("gagal", "User nonaktif. Hubungi administrator.");
            redirect('Auth');
            return;
        }

        $auth_source = $key->auth_source ?? 'tb_karyawan';
        $akses_lv = (int)($key->akses_lv ?? $key->level ?? 0);
        $jobdesk_hrd = strtolower(trim((string)($key->jobdesk_hrd ?? '')));
        $jobdesk = strtoupper(trim((string)($key->jobdesk ?? $key->jabatan ?? '')));
        $username_login = strtolower(trim((string)($key->username ?? '')));
        $is_admin_dashboard = ($username_login === 'admin' || ($akses_lv === 1 && $jobdesk === 'ADMIN'));
        $nama = $key->nm_karyawan ?? $key->nama_lngkp ?? $key->nama_user ?? $key->username;
        $departemen = $key->departemen ?? $key->departement ?? null;

        $data_session = array(
            'id'            => $key->id,
            'id_karyawan'   => $auth_source === 'tb_karyawan' ? $key->id : null,
            'auth_source'   => $auth_source,
            'nik'           => $key->nik ?? null,
            'username'      => $key->username,
            'departemen'    => $departemen,
            'lv'            => $akses_lv,
            'akses_lv'      => $akses_lv,
            'akses_lv_id'   => $key->akses_lv_id ?? $akses_lv,
            'jobdesk'       => $jobdesk,
            'jobdesk_hrd'   => $jobdesk_hrd,
            'jobdesk_id'    => $key->jobdesk_id ?? null,
            'nama'          => $nama,
            'nama_user'     => $nama,
            'tim'           => $key->tim ?? null,
            'wilayah'       => $key->wilayah ?? null,
            'is_admin_dashboard' => $is_admin_dashboard,
            'logged_in'     => true
        );

        $this->session->set_userdata($data_session);
        if ($this->M_Auth->password_should_be_hashed($key->password ?? '')) {
            $this->M_Auth->update_password_hash($key->id, $auth_source, $password);
        }
        $this->M_Auth->update_last_login($key->id, $auth_source);
        $this->M_Auth->log_login($key, 'success', 'Login berhasil');

        if ($jobdesk_hrd === 'inputer_laporan') {
            redirect('penilaian_lingkungan');
        } else if ($auth_source === 'tb_users' && !empty($key->default_redirect)) {
            redirect($key->default_redirect);
        } else if ($is_admin_dashboard) {
            redirect('dashboard');
        } else if ($jobdesk == 'LOGISTIK') {
            redirect('logistik');
        } else if ($jobdesk == 'ADMINICS') {
            redirect('ics/ics_diffrent');
        } else if ($jobdesk == 'ADMINKEU') {
            redirect('keuangan');
        } else if ($jobdesk == 'ADMINPURCHASING') {
            redirect('keuangan');
        } else if ($jobdesk == 'DIREKTUR') {
            redirect('dashboard');
        } else if ($jobdesk == 'ADMINGA') {
            redirect('schedule_direktur');
        } else if ($jobdesk == 'ADMINKEUTC') {
            redirect('keuangan');
        } else if ($jobdesk == 'STOCKOPNAME') {
            redirect('stockopname/input');
        } else if ($jobdesk == 'SUPERVISIOR_OPNAME') {
            redirect('supervisi-opname');
        } else if ($jobdesk == 'SALESONLINE') {
            redirect('stock');
        } else if ($jobdesk == 'SALESCOUNTER') {
            redirect('sales_report');
        } else if ($jobdesk == 'SALES') {
            redirect('kiu_katalog');
        } else if ($jobdesk == 'DISTRIBUSI') {
            redirect('logistik/distibusi');
        } else if ($jobdesk == 'ADMINLOGLPB') {
            redirect('ics/icspo');
        } else if ($jobdesk == 'ADMIN PO') {
            redirect('ics/icspo');
        } else if ($jobdesk == 'ADMLOG') {
            redirect('checker');
        } else if ($jobdesk == 'CHECKER') {
            redirect('checker');
        } else if ($jobdesk == 'MANAGERWH') {
            redirect('checker');
        } else if ($jobdesk == 'SALESCK') {
            redirect('checker');
        } else if ($jobdesk == 'DIREKTURCK') {
            redirect('checker/dashboard');
        } else if ($jobdesk == 'MANAGERCK') {
            redirect('checker');
        } else if ($jobdesk == 'SC') {
            redirect('sales_order');
        } else if ($jobdesk == 'SUPERADMIN') {
            redirect('hrd/penilaian_lingkungan/admin');
        } else if ($jobdesk == 'KARYAWAN') {
            redirect('mobile-erp');
        } else {
            redirect('dashboard');
        }
    }

    function logout()
    {
        $this->session->set_flashdata("logout", "Berhasil Log Out");
        $this->session->sess_destroy();
        redirect('Auth');
    }
}
