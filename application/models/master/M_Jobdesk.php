<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Jobdesk extends CI_Model
{
    private $table = 'tb_jobdesk';

    private function fields()
    {
        static $fields;
        if ($fields === null) {
            $fields = $this->db->table_exists($this->table) ? $this->db->list_fields($this->table) : [];
        }
        return $fields;
    }

    private function has($field)
    {
        return in_array($field, $this->fields(), true);
    }

    private function idField()
    {
        foreach (['id_jobdesk', 'jobdesk_id', 'id'] as $field) {
            if ($this->has($field)) return $field;
        }
        return 'id_jobdesk';
    }

    private function nameField()
    {
        foreach (['nama_jobdesk', 'jobdesk', 'nama', 'name'] as $field) {
            if ($this->has($field)) return $field;
        }
        return 'nama_jobdesk';
    }

    private function normalize($row)
    {
        return [
            'id_jobdesk' => $row[$this->idField()] ?? '',
            'nama_jobdesk' => $row[$this->nameField()] ?? '',
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

        $total = $this->db->count_all($this->table);
        $this->db->from($this->table);
        if ($search !== '') {
            $this->db->group_start()->like($name, $search);
            if ($this->has('deskripsi')) $this->db->or_like('deskripsi', $search);
            $this->db->group_end();
        }
        $filtered = $this->db->count_all_results('', false);
        $this->db->order_by($name, 'ASC');
        if ($length > 0) $this->db->limit($length, $start);
        $rows = array_map([$this, 'normalize'], $this->db->get()->result_array());

        return ['draw' => (int)($post['draw'] ?? 1), 'recordsTotal' => $total, 'recordsFiltered' => $filtered, 'data' => $rows];
    }

    public function options()
    {
        if ($this->db->table_exists($this->table)) {
            if ($this->has('status')) $this->db->where('status', 1);
            $this->db->order_by($this->nameField(), 'ASC');
            return array_map([$this, 'normalize'], $this->db->get($this->table)->result_array());
        }
        return $this->db->select('jobdesk AS nama_jobdesk')->distinct()->where('jobdesk IS NOT NULL', null, false)->order_by('jobdesk', 'ASC')->get('tb_karyawan')->result_array();
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
            $this->nameField() => trim((string)($input['nama_jobdesk'] ?? '')),
            'deskripsi' => trim((string)($input['deskripsi'] ?? '')),
            'status' => (int)($input['status'] ?? 1),
        ];
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
}
