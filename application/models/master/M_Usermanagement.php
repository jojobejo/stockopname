<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Usermanagement extends CI_Model
{
    private $table = 'tb_karyawan';

    private function fields($table = null)
    {
        static $cache = [];
        $table = $table ?: $this->table;
        if (!isset($cache[$table])) {
            $cache[$table] = $this->db->table_exists($table) ? $this->db->list_fields($table) : [];
        }
        return $cache[$table];
    }

    private function has($field, $table = null)
    {
        return in_array($field, $this->fields($table), true);
    }

    private function select_base()
    {
        $select = ['k.id', 'k.nik', 'k.nm_karyawan', 'k.departemen', 'k.jobdesk', 'k.username', 'k.tim', 'k.wilayah', 'k.akses_lv'];
        foreach (['jobdesk_id', 'akses_lv_id', 'status', 'foto', 'last_login'] as $field) {
            if ($this->has($field)) $select[] = 'k.' . $field;
        }
        $this->db->select(implode(',', $select));
        $this->db->from($this->table . ' k');
    }

    private function apply_filters($post)
    {
        $search = trim((string)($post['search']['value'] ?? $post['search'] ?? ''));
        if ($search !== '') {
            $this->db->group_start()
                ->like('k.nik', $search)
                ->or_like('k.nm_karyawan', $search)
                ->or_like('k.username', $search)
                ->or_like('k.jobdesk', $search)
                ->or_like('k.departemen', $search)
                ->group_end();
        }
        foreach (['departemen', 'jobdesk', 'akses_lv'] as $field) {
            $value = trim((string)($post[$field] ?? ''));
            if ($value !== '') $this->db->where('k.' . $field, $value);
        }
        if ($this->has('jobdesk_id') && trim((string)($post['jobdesk_id'] ?? '')) !== '') {
            $this->db->where('k.jobdesk_id', $post['jobdesk_id']);
        }
        if ($this->has('akses_lv_id') && trim((string)($post['akses_lv_id'] ?? '')) !== '') {
            $this->db->where('k.akses_lv_id', $post['akses_lv_id']);
        }
        if ($this->has('status') && trim((string)($post['status'] ?? '')) !== '') {
            $this->db->where('k.status', $post['status']);
        }
    }

    public function datatable($post)
    {
        $length = (int)($post['length'] ?? 10);
        $start = (int)($post['start'] ?? 0);

        $total = $this->db->count_all($this->table);
        $this->select_base();
        $this->apply_filters($post);
        $filtered = $this->db->count_all_results('', false);
        $this->db->order_by('k.id', 'DESC');
        if ($length > 0) $this->db->limit($length, $start);
        $rows = $this->db->get()->result_array();

        return [
            'draw' => (int)($post['draw'] ?? 1),
            'recordsTotal' => $total,
            'recordsFiltered' => $filtered,
            'data' => $rows,
        ];
    }

    public function find($id)
    {
        $this->select_base();
        return $this->db->where('k.id', $id)->get()->row_array();
    }

    public function unique($field, $value, $excludeId = null)
    {
        if (!$this->has($field)) return true;
        $this->db->where($field, $value);
        if ($excludeId) $this->db->where('id !=', $excludeId);
        return $this->db->count_all_results($this->table) === 0;
    }

    public function resolve_jobdesk_name($id, $fallback)
    {
        $fallback = trim((string)$fallback);
        if ($id && $this->db->table_exists('tb_jobdesk')) {
            $fields = $this->fields('tb_jobdesk');
            $idField = in_array('id_jobdesk', $fields, true) ? 'id_jobdesk' : (in_array('id', $fields, true) ? 'id' : null);
            $nameField = in_array('nama_jobdesk', $fields, true) ? 'nama_jobdesk' : (in_array('jobdesk', $fields, true) ? 'jobdesk' : null);
            if ($idField && $nameField) {
                $row = $this->db->select($nameField)->where($idField, $id)->get('tb_jobdesk')->row_array();
                if ($row) return $row[$nameField];
            }
        }
        return $fallback;
    }

    public function resolve_akses_name($id, $fallback)
    {
        $fallback = trim((string)$fallback);
        if ($id && $this->db->table_exists('tb_akses_level')) {
            $fields = $this->fields('tb_akses_level');
            $idField = in_array('id_akses_level', $fields, true) ? 'id_akses_level' : (in_array('id', $fields, true) ? 'id' : null);
            $nameField = in_array('nama_akses_level', $fields, true) ? 'nama_akses_level' : (in_array('akses_lv', $fields, true) ? 'akses_lv' : null);
            if ($idField && $nameField) {
                $row = $this->db->select($nameField)->where($idField, $id)->get('tb_akses_level')->row_array();
                if ($row) return $row[$nameField];
            }
        }
        return $fallback;
    }

    private function payload($input, $isUpdate = false)
    {
        $jobdeskId = trim((string)($input['jobdesk_id'] ?? ''));
        $aksesId = trim((string)($input['akses_lv_id'] ?? ''));
        $jobdesk = $this->resolve_jobdesk_name($jobdeskId, $input['jobdesk'] ?? '');
        $akses = $this->resolve_akses_name($aksesId, $input['akses_lv'] ?? '');

        $data = [
            'nik' => trim((string)($input['nik'] ?? '')),
            'nm_karyawan' => trim((string)($input['nm_karyawan'] ?? '')),
            'departemen' => trim((string)($input['departemen'] ?? '')),
            'jobdesk' => $jobdesk,
            'username' => trim((string)($input['username'] ?? '')),
            'tim' => (int)($input['tim'] ?? 0),
            'wilayah' => (int)($input['wilayah'] ?? 0),
            'akses_lv' => $akses,
            'jobdesk_id' => $jobdeskId !== '' ? $jobdeskId : null,
            'akses_lv_id' => $aksesId !== '' ? $aksesId : null,
            'status' => (int)($input['status'] ?? 1),
            'foto' => $input['foto'] ?? null,
        ];
        if (!$isUpdate || trim((string)($input['password'] ?? '')) !== '') {
            $data['password'] = password_hash((string)$input['password'], PASSWORD_DEFAULT);
        }
        return array_intersect_key($data, array_flip($this->fields()));
    }

    public function save($input)
    {
        $this->db->insert($this->table, $this->payload($input, false));
        return $this->db->insert_id();
    }

    public function update($id, $input)
    {
        return $this->db->where('id', $id)->update($this->table, $this->payload($input, true));
    }

    public function delete($id)
    {
        return $this->db->where('id', $id)->delete($this->table);
    }

    public function reset_password($id, $password)
    {
        return $this->db->where('id', $id)->update($this->table, ['password' => password_hash($password, PASSWORD_DEFAULT)]);
    }

    public function toggle_status($id)
    {
        if (!$this->has('status')) return false;
        $row = $this->db->select('status')->where('id', $id)->get($this->table)->row_array();
        if (!$row) return false;
        return $this->db->where('id', $id)->update($this->table, ['status' => ((int)$row['status'] === 1 ? 0 : 1)]);
    }

    public function reference($table, $valueField, $textField)
    {
        if (!$this->db->table_exists($table)) return [];
        $fields = $this->fields($table);
        if (!in_array($valueField, $fields, true) || !in_array($textField, $fields, true)) return [];
        if (in_array('status', $fields, true)) $this->db->where('status', 1);
        return $this->db->select($valueField . ' AS id,' . $textField . ' AS text')->order_by($textField, 'ASC')->get($table)->result_array();
    }

    public function distinct_options($field)
    {
        if (!$this->has($field)) return [];
        return $this->db->select($field . ' AS id, ' . $field . ' AS text')->distinct()->where($field . ' IS NOT NULL', null, false)->order_by($field, 'ASC')->get($this->table)->result_array();
    }
}
