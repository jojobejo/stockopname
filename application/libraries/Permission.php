<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permission
{
    protected $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('master/M_Akseslevel', 'permissionAksesModel');
    }

    public function can($url, $permission = 'can_view')
    {
        $aksesLevelId = $this->CI->session->userdata('akses_lv_id') ?: $this->CI->session->userdata('lv');
        return $this->CI->permissionAksesModel->has_permission($aksesLevelId, $url, $permission);
    }

    public function guard($url, $permission = 'can_view')
    {
        if (!$this->CI->session->userdata('logged_in')) {
            redirect('Auth');
            return false;
        }
        if (!$this->can($url, $permission)) {
            show_error('Anda tidak memiliki akses ke halaman ini.', 403);
            return false;
        }
        return true;
    }
}
