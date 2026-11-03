<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Akseslevel extends CI_Model
{
    private $table = 'tb_akses_level';
    private $accessTable = 'tb_akses_menu';

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

    private function idField()
    {
        foreach (['id_akses_level', 'akses_lv_id', 'id'] as $field) {
            if ($this->has($field)) return $field;
        }
        return 'id_akses_level';
    }

    private function nameField()
    {
        foreach (['nama_akses_level', 'akses_lv', 'nama', 'name'] as $field) {
            if ($this->has($field)) return $field;
        }
        return null;
    }

    private function menuIdField()
    {
        foreach (['id_menu', 'menu_id', 'idmenu'] as $field) {
            if ($this->has($field, $this->accessTable)) return $field;
        }
        return null;
    }

    private function menuTableIdField()
    {
        foreach (['id_menu', 'menu_id', 'id'] as $field) {
            if ($this->has($field, 'tb_menu')) return $field;
        }
        return null;
    }

    private function menuUrlField()
    {
        foreach (['url', 'link', 'link_menu', 'menu_url', 'route'] as $field) {
            if ($this->has($field, 'tb_menu')) return $field;
        }
        return null;
    }

    private function normalize($row)
    {
        $nameField = $this->nameField();
        return [
            'id_akses_level' => $row[$this->idField()] ?? '',
            'nama_akses_level' => $nameField ? ($row[$nameField] ?? '') : (string)($row[$this->idField()] ?? ''),
            'deskripsi' => $row['deskripsi'] ?? '',
            'status' => $row['status'] ?? 1,
        ];
    }

    public function datatable($post)
    {
        if (!$this->db->table_exists($this->table)) {
            return ['draw' => (int)($post['draw'] ?? 1), 'recordsTotal' => 0, 'recordsFiltered' => 0, 'data' => []];
        }
        $search = trim((string)($post['search']['value'] ?? ''));
        $length = (int)($post['length'] ?? 10);
        $start = (int)($post['start'] ?? 0);
        $name = $this->nameField();
        $orderField = $name ?: $this->idField();

        $total = $this->db->count_all($this->table);
        $this->db->from($this->table);
        if ($search !== '' && $name) {
            $this->db->group_start()->like($name, $search);
            if ($this->has('deskripsi')) $this->db->or_like('deskripsi', $search);
            $this->db->group_end();
        }
        $filtered = $this->db->count_all_results('', false);
        $this->db->order_by($orderField, 'ASC');
        if ($length > 0) $this->db->limit($length, $start);
        $rows = array_map([$this, 'normalize'], $this->db->get()->result_array());
        return ['draw' => (int)($post['draw'] ?? 1), 'recordsTotal' => $total, 'recordsFiltered' => $filtered, 'data' => $rows];
    }

    public function options()
    {
        if ($this->db->table_exists($this->table)) {
            if ($this->has('status')) $this->db->where('status', 1);
            $this->db->order_by($this->nameField() ?: $this->idField(), 'ASC');
            return array_map([$this, 'normalize'], $this->db->get($this->table)->result_array());
        }
        return $this->db->select('akses_lv AS id_akses_level, akses_lv AS nama_akses_level')->distinct()->where('akses_lv IS NOT NULL', null, false)->order_by('akses_lv', 'ASC')->get('tb_karyawan')->result_array();
    }

    public function find($id)
    {
        if (!$this->db->table_exists($this->table)) return null;
        $row = $this->db->where($this->idField(), $id)->get($this->table)->row_array();
        return $row ? $this->normalize($row) : null;
    }

    private function payload($input)
    {
        $data = [
            'deskripsi' => trim((string)($input['deskripsi'] ?? '')),
            'status' => (int)($input['status'] ?? 1),
        ];
        $nameField = $this->nameField();
        if ($nameField) {
            $data[$nameField] = trim((string)($input['nama_akses_level'] ?? ''));
        }
        return array_intersect_key($data, array_flip($this->fields()));
    }

    public function save($input)
    {
        $this->db->insert($this->table, $this->payload($input));
        return $this->db->insert_id();
    }

    public function update($id, $input)
    {
        return $this->db->where($this->idField(), $id)->update($this->table, $this->payload($input));
    }

    public function delete($id)
    {
        return $this->db->where($this->idField(), $id)->delete($this->table);
    }

    public function matrix($aksesLevelId)
    {
        $this->load->model('master/M_Menu');
        $menus = $this->M_Menu->all_active();
        if (!$this->db->table_exists($this->accessTable)) {
            return array_map(function ($menu) {
                return $menu + ['can_view' => 0, 'can_add' => 0, 'can_edit' => 0, 'can_delete' => 0, 'can_approve' => 0, 'can_print' => 0];
            }, $menus);
        }
        $menuIdField = $this->menuIdField();
        if (!$menuIdField) {
            return array_map(function ($menu) {
                return $menu + ['can_view' => 0, 'can_add' => 0, 'can_edit' => 0, 'can_delete' => 0, 'can_approve' => 0, 'can_print' => 0];
            }, $menus);
        }

        $permissions = $this->db->where('akses_lv_id', $aksesLevelId)->get($this->accessTable)->result_array();
        $map = [];
        foreach ($permissions as $permission) {
            $map[$permission[$menuIdField]] = $permission;
        }
        return array_map(function ($menu) use ($map) {
            $permission = $map[$menu['id_menu']] ?? [];
            foreach (['can_view', 'can_add', 'can_edit', 'can_delete', 'can_approve', 'can_print'] as $key) {
                $menu[$key] = (int)($permission[$key] ?? 0);
            }
            return $menu;
        }, $menus);
    }

    public function save_permission($aksesLevelId, $menuId, $key, $value)
    {
        if (!$this->db->table_exists($this->accessTable)) return false;
        if (!in_array($key, ['can_view', 'can_add', 'can_edit', 'can_delete', 'can_approve', 'can_print'], true)) return false;
        $menuIdField = $this->menuIdField();
        if (!$menuIdField) return false;

        $existing = $this->db->where('akses_lv_id', $aksesLevelId)->where($menuIdField, $menuId)->get($this->accessTable)->row_array();
        if ($existing) {
            return $this->db->where('akses_lv_id', $aksesLevelId)->where($menuIdField, $menuId)->update($this->accessTable, [$key => (int)$value]);
        }
        return $this->db->insert($this->accessTable, [
            'akses_lv_id' => $aksesLevelId,
            $menuIdField => $menuId,
            $key => (int)$value,
        ]);
    }

    public function has_permission($aksesLevelId, $url, $permission = 'can_view')
    {
        if (!$aksesLevelId || !$this->db->table_exists('tb_menu') || !$this->db->table_exists($this->accessTable)) return true;
        $menuIdField = $this->menuIdField();
        $menuTableIdField = $this->menuTableIdField();
        $menuUrlField = $this->menuUrlField();
        if (!$menuIdField || !$menuTableIdField || !$menuUrlField) return true;
        $this->db->select('am.' . $permission);
        $this->db->from('tb_menu m');
        $this->db->join($this->accessTable . ' am', 'am.' . $menuIdField . ' = m.' . $menuTableIdField, 'inner');
        $this->db->where('am.akses_lv_id', $aksesLevelId);
        $this->db->where('m.' . $menuUrlField, $url);
        $row = $this->db->get()->row_array();
        return !$row || (int)$row[$permission] === 1;
    }
}
