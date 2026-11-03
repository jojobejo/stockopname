<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Menu extends CI_Model
{
    private $table = 'tb_menu';
    private $accessTable = 'tb_akses_menu';

    private function fields($table)
    {
        static $cache = [];
        if (!isset($cache[$table])) {
            $cache[$table] = $this->db->table_exists($table) ? $this->db->list_fields($table) : [];
        }
        return $cache[$table];
    }

    private function has($table, $field)
    {
        return in_array($field, $this->fields($table), true);
    }

    private function idField()
    {
        foreach (['id_menu', 'menu_id', 'id'] as $field) {
            if ($this->has($this->table, $field)) {
                return $field;
            }
        }
        return 'id_menu';
    }

    private function nameField()
    {
        foreach (['nama_menu', 'menu_name', 'title', 'label'] as $field) {
            if ($this->has($this->table, $field)) {
                return $field;
            }
        }
        return 'nama_menu';
    }

    private function accessMenuField()
    {
        foreach (['id_menu', 'menu_id', 'idmenu'] as $field) {
            if ($this->has($this->accessTable, $field)) {
                return $field;
            }
        }
        return null;
    }

    private function normalize($row)
    {
        $id = $this->idField();
        $name = $this->nameField();
        return [
            'id_menu' => $row[$id] ?? '',
            'parent_id' => $row['parent_id'] ?? 0,
            'nama_menu' => $row[$name] ?? '',
            'icon' => $row['icon'] ?? 'fas fa-circle',
            'url' => $row['url'] ?? '#',
            'urutan' => $row['urutan'] ?? 0,
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
        $id = $this->idField();
        $name = $this->nameField();

        $this->db->from($this->table);
        $total = $this->db->count_all_results('', false);
        $this->db->reset_query();

        $this->db->from($this->table);
        if ($search !== '') {
            $this->db->group_start()->like($name, $search);
            if ($this->has($this->table, 'url')) {
                $this->db->or_like('url', $search);
            }
            $this->db->group_end();
        }
        $filtered = $this->db->count_all_results('', false);
        $this->db->order_by($this->has($this->table, 'urutan') ? 'urutan' : $id, 'ASC');
        if ($length > 0) {
            $this->db->limit($length, $start);
        }
        $rows = array_map([$this, 'normalize'], $this->db->get()->result_array());

        return ['draw' => (int)($post['draw'] ?? 1), 'recordsTotal' => $total, 'recordsFiltered' => $filtered, 'data' => $rows];
    }

    public function all_active()
    {
        if (!$this->db->table_exists($this->table)) {
            return [];
        }
        if ($this->has($this->table, 'status')) {
            $this->db->where('status', 1);
        }
        $this->db->order_by($this->has($this->table, 'urutan') ? 'urutan' : $this->idField(), 'ASC');
        return array_map([$this, 'normalize'], $this->db->get($this->table)->result_array());
    }

    public function find($id)
    {
        if (!$this->db->table_exists($this->table)) {
            return null;
        }
        $row = $this->db->where($this->idField(), $id)->get($this->table)->row_array();
        return $row ? $this->normalize($row) : null;
    }

    private function payload($input)
    {
        $map = [
            'parent_id' => (int)($input['parent_id'] ?? 0),
            $this->nameField() => trim((string)($input['nama_menu'] ?? '')),
            'icon' => trim((string)($input['icon'] ?? 'fas fa-circle')),
            'url' => trim((string)($input['url'] ?? '#')),
            'urutan' => (int)($input['urutan'] ?? 0),
            'status' => (int)($input['status'] ?? 1),
        ];
        return array_intersect_key($map, array_flip($this->fields($this->table)));
    }

    public function save($input)
    {
        $data = $this->payload($input);
        $this->db->insert($this->table, $data);
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

    public function sidebar_tree($aksesLevelId = null)
    {
        $menus = $this->all_active();
        if (!$aksesLevelId || !$this->db->table_exists($this->accessTable)) {
            return $this->build_tree($menus);
        }

        $accessMenuField = $this->accessMenuField();
        if (!$accessMenuField) {
            return $this->build_tree($menus);
        }

        $menuIds = $this->db
            ->select($accessMenuField . ' AS id_menu')
            ->where('akses_lv_id', $aksesLevelId)
            ->where('can_view', 1)
            ->get($this->accessTable)
            ->result_array();
        $allowed = array_column($menuIds, 'id_menu');
        $menus = array_values(array_filter($menus, function ($menu) use ($allowed) {
            return in_array($menu['id_menu'], $allowed);
        }));
        return $this->build_tree($menus);
    }

    private function build_tree($menus)
    {
        $children = [];
        foreach ($menus as $menu) {
            $children[(int)$menu['parent_id']][] = $menu;
        }
        $walk = function ($parent) use (&$walk, &$children) {
            $branch = [];
            foreach ($children[$parent] ?? [] as $menu) {
                $menu['children'] = $walk((int)$menu['id_menu']);
                $branch[] = $menu;
            }
            return $branch;
        };
        return $walk(0);
    }
}
