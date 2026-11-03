<?php
defined('BASEPATH') or exit('No direct script access allowed');
/**
 *
 */
class M_Hrd extends CI_Model
{
    public function get_all_laporan()
    {
        return $this->db->query("SELECT a.*
        FROM tb_lap_distribusi a
        ");
    }
    public function getalltamulb()
    {
        return $this->db->query("SELECT a.*
        FROM tb_tamu_lby a
        ");
    }
    public function get_lap_id($id)
    {
        return $this->db->query("SELECT a.*
        FROM tb_lap_distribusi a
        WHERE id = $id
        ");
    }
    public function addlapdistribusihrd($data)
    {
        return $this->db->insert('tb_lap_distribusi', $data);
    }
    public function editlapdistribusihrd($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_lap_distribusi', $data);
    }
    public function hapus_lap_distribusi_hrd($id)
    {
        return $this->db->delete('tb_lap_distribusi', array("id" => $id));
    }

    public function konfirmtamulb($data)
    {
        return $this->db->insert('tb_tamu', $data);
    }

    public function get_all_tamu_lb()
    {
        return $this->db->query("SELECT a.*
        FROM tb_tamu_lby a
        ");
    }

    public function hapus_lap_tamu_lby($id)
    {
        return $this->db->delete('tb_tamu_lby', array("id" => $id));
    }
    public function addlaptamuhrd($data)
    {
        return $this->db->insert('tb_tamu_lby', $data);
    }
    public function editlaptamu($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_tamu', $data);
    }
    public function hapus_lap_tamu_lb($id)
    {
        return $this->db->delete('tb_tamu_lby', array("id" => $id));
    }
    public function hapus_lap_tamu_hrd($id)
    {
        return $this->db->delete('tb_tamu', array("id" => $id));
    }
    public function get_all_tamu()
    {
        return $this->db->query("SELECT a.*
        FROM tb_tamu a
        ");
    }

    //karyawan keluar masuk 

    public function get_all_laporan_karykm()
    {
        return $this->db->query("SELECT a.*
        FROM tb_karyawan_keluarmasuk a
        ");
    }
    public function addlapkarykm($data)
    {
        return $this->db->insert('tb_karyawan_keluarmasuk', $data);
    }
    public function editlapkarykm($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_karyawan_keluarmasuk', $data);
    }
    public function hapuslapkarykm($id)
    {
        return $this->db->delete('tb_karyawan_keluarmasuk', array("id" => $id));
    }

    //laporan Expedisi

    public function get_all_laporan_expedisi()
    {
        return $this->db->query("SELECT a.*
         FROM tb_expedisi a
         ");
    }
    public function addlapexpedisi($data)
    {
        return $this->db->insert('tb_expedisi', $data);
    }
    public function editlapexpedisi($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_expedisi', $data);
    }
    public function hapuslapexpedisi($id)
    {
        return $this->db->delete('tb_expedisi', array("id" => $id));
    }

    //issue

    public function get_all_laporan_issue()
    {
        return $this->db->query("SELECT a.*
        FROM tb_issue a
        ");
    }
    public function export_lap_issue()
    {
        return $this->db->get('tb_issue')->result();
    }
    public function export_lap_km_karyawan()
    {
        return $this->db->get('tb_karyawan_keluarmasuk')->result();
    }
    public function addlapissue($data)
    {
        return $this->db->insert('tb_issue', $data);
    }
    public function editlapissue($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_issue', $data);
    }
    public function hapuslapissue($id)
    {
        return $this->db->delete('tb_issue', array("id" => $id));
    }
    public function cari_lap_distribusi($v1, $v2)
    {
        return $this->db->query("SELECT a.*
        FROM tb_lap_distribusi a
        WHERE $v1 LIKE '$v2'
        ");
    }
    public function get_all_truk_service_histori()
    {
        return $this->db->query("SELECT a.*
        FROM tb_service_truk a
        ");
    }
    public function update_km_service($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_service_truk', $data);
    }
    public function get_all_karyawan()
    {
        return $this->db->query("SELECT a.*
        FROM tb_user a WHERE id > '4'
        ");
    }
    public function update_karyawan($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_user', $data);
    }
    public function add_karyawan($data)
    {
        return $this->db->insert('tb_user', $data);
    }



    public function get_locations()
    {
        return $this->db->select('*')
            ->from('tbhrd_lokasi')
            ->where('is_active', 1)
            ->order_by('name', 'ASC')
            ->get();
    }

    public function get_ratings()
    {
        return $this->db->select('*')
            ->from('tbhrd_issue_rating')
            ->order_by('score', 'DESC')
            ->get();
    }

    public function get_all_locations()
    {
        return $this->db->select('*')
            ->from('tbhrd_lokasi')
            ->order_by('name', 'ASC')
            ->get();
    }

    public function get_location_by_id($id)
    {
        return $this->db->get_where('tbhrd_lokasi', array('id' => $id))->row();
    }

    public function save_location($data)
    {
        if (!empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            $this->db->where('id', $id);
            return $this->db->update('tbhrd_lokasi', $data);
        }
        return $this->db->insert('tbhrd_lokasi', $data);
    }

    public function delete_location($id)
    {
        return $this->db->delete('tbhrd_lokasi', array('id' => $id));
    }

    public function get_all_ratings()
    {
        return $this->db->select('*')
            ->from('tbhrd_issue_rating')
            ->order_by('score', 'DESC')
            ->get();
    }

    public function get_rating_by_id($id)
    {
        return $this->db->get_where('tbhrd_issue_rating', array('id' => $id))->row();
    }

    public function save_rating($data)
    {
        if (!empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            $this->db->where('id', $id);
            return $this->db->update('tbhrd_issue_rating', $data);
        }
        return $this->db->insert('tbhrd_issue_rating', $data);
    }

    public function delete_rating($id)
    {
        return $this->db->delete('tbhrd_issue_rating', array('id' => $id));
    }

    public function get_statuses()
    {
        return $this->db->select('*')
            ->from('tbhrd_issue_status')
            ->order_by('id', 'ASC')
            ->get();
    }

    public function insert_environment_issue($data)
    {
        return $this->db->insert('tbhrd_environment_issues', $data);
    }

    public function insert_issue_evidence($data)
    {
        return $this->db->insert('tbhrd_issue_evidences', $data);
    }

    public function insert_issue_log($data)
    {
        return $this->db->insert('tbhrd_issue_logs', $data);
    }

    private function apply_issue_filters($filters = array(), $alias = 'e')
    {
        if (!is_array($filters)) {
            $filters = array();
        }

        if (array_key_exists('location_id', $filters) && $filters['location_id'] !== '' && $filters['location_id'] !== null) {
            $this->db->where($alias . '.location_id', $filters['location_id']);
        }
        if (array_key_exists('status_id', $filters) && $filters['status_id'] !== '' && $filters['status_id'] !== null) {
            $this->db->where($alias . '.status_id', $filters['status_id']);
        }
        if (array_key_exists('rating_id', $filters) && $filters['rating_id'] !== '' && $filters['rating_id'] !== null) {
            $this->db->where($alias . '.rating_id', $filters['rating_id']);
        }
        if (array_key_exists('created_by', $filters) && $filters['created_by'] !== '' && $filters['created_by'] !== null) {
            $this->db->where($alias . '.created_by', $filters['created_by']);
        }
        if (!empty($filters['date_from'])) {
            $this->db->where('DATE(' . $alias . '.report_datetime) >=', $filters['date_from']);
        }
        if (!empty($filters['date_to'])) {
            $this->db->where('DATE(' . $alias . '.report_datetime) <=', $filters['date_to']);
        }
    }

    public function get_issue_list($filters = array())
    {
        $this->db->select('e.*, e.created_by AS created_by_id, l.name AS location_name, r.name AS rating_name, r.score, s.name AS status_name')
            ->select("COALESCE(NULLIF(k.nm_karyawan, ''), NULLIF(k.username, ''), NULLIF(u.username, ''), CONCAT('User #', e.created_by)) AS created_by", false)
            ->from('tbhrd_environment_issues e')
            ->join('tbhrd_lokasi l', 'e.location_id = l.id', 'left')
            ->join('tbhrd_issue_rating r', 'e.rating_id = r.id', 'left')
            ->join('tbhrd_issue_status s', 'e.status_id = s.id', 'left')
            ->join('tb_karyawan k', 'e.created_by = k.id', 'left')
            ->join('tb_user u', 'e.created_by = u.id', 'left');

        $this->apply_issue_filters($filters, 'e');

        return $this->db->order_by('e.report_datetime', 'DESC')->get();
    }

    public function get_issue_by_id($id)
    {
        return $this->db->select('e.*, e.created_by AS created_by_id, l.name AS location_name, r.name AS rating_name, r.score, s.name AS status_name')
            ->select("COALESCE(NULLIF(k.nm_karyawan, ''), NULLIF(k.username, ''), NULLIF(u.username, ''), CONCAT('User #', e.created_by)) AS created_by_name", false)
            ->from('tbhrd_environment_issues e')
            ->join('tbhrd_lokasi l', 'e.location_id = l.id', 'left')
            ->join('tbhrd_issue_rating r', 'e.rating_id = r.id', 'left')
            ->join('tbhrd_issue_status s', 'e.status_id = s.id', 'left')
            ->join('tb_karyawan k', 'e.created_by = k.id', 'left')
            ->join('tb_user u', 'e.created_by = u.id', 'left')
            ->where('e.id', $id)
            ->get()
            ->row();
    }

    public function get_issue_evidences($issue_id)
    {
        return $this->db->select('*')
            ->from('tbhrd_issue_evidences')
            ->where('issue_id', $issue_id)
            ->order_by('id', 'ASC')
            ->get()
            ->result();
    }

    public function get_issue_logs($issue_id)
    {
        return $this->db->select('l.*, s.name AS status_name')
            ->select("COALESCE(NULLIF(k.nm_karyawan, ''), NULLIF(k.username, ''), NULLIF(u.username, ''), CONCAT('User #', l.changed_by)) AS changed_by_name", false)
            ->from('tbhrd_issue_logs l')
            ->join('tbhrd_issue_status s', 'l.status_id = s.id', 'left')
            ->join('tb_karyawan k', 'l.changed_by = k.id', 'left')
            ->join('tb_user u', 'l.changed_by = u.id', 'left')
            ->where('l.issue_id', $issue_id)
            ->order_by('l.changed_at', 'DESC')
            ->get()
            ->result();
    }

    public function update_environment_issue($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tbhrd_environment_issues', $data);
    }

    public function get_issue_counts_by_location($filters = array())
    {
        $this->db->select('e.location_id, l.name AS location_name, COUNT(e.id) AS total')
            ->from('tbhrd_environment_issues e')
            ->join('tbhrd_lokasi l', 'e.location_id = l.id', 'left');
        $this->apply_issue_filters($filters, 'e');

        return $this->db->group_by('e.location_id')
            ->order_by('total', 'DESC')
            ->get();
    }

    public function get_issue_counts_by_status($filters = array())
    {
        $this->db->select('e.status_id, s.name AS status_name, COUNT(e.id) AS total')
            ->from('tbhrd_environment_issues e')
            ->join('tbhrd_issue_status s', 'e.status_id = s.id', 'left');
        $this->apply_issue_filters($filters, 'e');

        return $this->db->group_by('e.status_id')
            ->order_by('total', 'DESC')
            ->get();
    }

    public function get_issue_operational_summary($filters = array())
    {
        $resolvedExpression = "LOWER(COALESCE(s.name, '')) LIKE '%selesai%' OR LOWER(COALESCE(s.name, '')) LIKE '%done%' OR LOWER(COALESCE(s.name, '')) LIKE '%closed%' OR LOWER(COALESCE(s.name, '')) LIKE '%resolved%'";

        $this->db->select('COUNT(e.id) AS reports')
            ->select("SUM(CASE WHEN {$resolvedExpression} THEN 0 ELSE 1 END) AS pending", false)
            ->select("SUM(CASE WHEN {$resolvedExpression} THEN 1 ELSE 0 END) AS done", false)
            ->from('tbhrd_environment_issues e')
            ->join('tbhrd_issue_status s', 'e.status_id = s.id', 'left');
        $this->apply_issue_filters($filters, 'e');

        $row = $this->db->get()->row();
        return array(
            'reports' => $row ? intval($row->reports) : 0,
            'pending' => $row ? intval($row->pending) : 0,
            'done' => $row ? intval($row->done) : 0,
        );
    }

    public function get_issue_counts_by_rating($filters = array())
    {
        $this->db->select('e.rating_id, r.name AS rating_name, r.score, COUNT(e.id) AS total')
            ->from('tbhrd_environment_issues e')
            ->join('tbhrd_issue_rating r', 'e.rating_id = r.id', 'left')
            ->where('e.rating_id >', 0);
        $this->apply_issue_filters($filters, 'e');

        return $this->db->group_by('e.rating_id')
            ->order_by('r.score', 'DESC')
            ->get();
    }

    public function get_issue_breakdown_summary($filters = array())
    {
        $this->db->select('COUNT(e.id) AS total_issues')
            ->select("SUM(CASE WHEN LOWER(COALESCE(s.name, '')) LIKE '%selesai%' OR LOWER(COALESCE(s.name, '')) LIKE '%done%' OR LOWER(COALESCE(s.name, '')) LIKE '%closed%' OR LOWER(COALESCE(s.name, '')) LIKE '%resolved%' THEN 1 ELSE 0 END) AS total_resolved", false)
            ->select("SUM(CASE WHEN LOWER(COALESCE(s.name, '')) LIKE '%progress%' OR LOWER(COALESCE(s.name, '')) LIKE '%proses%' OR LOWER(COALESCE(s.name, '')) LIKE '%sedang%' THEN 1 ELSE 0 END) AS total_progress", false)
            ->select("SUM(CASE WHEN LOWER(COALESCE(s.name, '')) LIKE '%open%' OR LOWER(COALESCE(s.name, '')) LIKE '%belum%' OR e.status_id = 0 THEN 1 ELSE 0 END) AS total_open", false)
            ->select("SUM(CASE WHEN LOWER(COALESCE(s.name, '')) LIKE '%pending%' OR LOWER(COALESCE(s.name, '')) LIKE '%menunggu%' THEN 1 ELSE 0 END) AS total_pending", false)
            ->from('tbhrd_environment_issues e')
            ->join('tbhrd_issue_status s', 'e.status_id = s.id', 'left');
        $this->apply_issue_filters($filters, 'e');

        return $this->db->get()->row();
    }

    var $table = 'tb_lap_distribusi'; //nama tabel dari database
    var $column_order = array('tglkeluar', 'tglmasuk', 'nopol', 'nolambung', 'namadriver', 'namahelper', 'tujuan', 'jamkeluar', 'kmkeluar', 'jammasuk', 'kmmasuk', 'keterangan', 'id'); //field yang ada di table user
    var $column_search = array('nopol', 'namadriver', 'namahelper', 'tujuan'); //field yang diizin untuk pencarian 
    var $order = array('id' => 'asc'); // default order 

    private function _get_datatables_query()
    {

        $this->db->from($this->table);

        $i = 0;

        foreach ($this->column_search as $item) // looping awal
        {
            if ($_POST['search']['value']) // jika datatable mengirimkan pencarian dengan metode POST
            {

                if ($i === 0) // looping awal
                {
                    $this->db->group_start();
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }

                if (count($this->column_search) - 1 == $i)
                    $this->db->group_end();
            }
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    
    public function insertchedule($data)
    {
        return $this->db->insert('tb_schedule_dirut', $data);
    }
    public function getdataschedule()
    {
        return $this->db->query("SELECT a.*
        FROM tb_schedule_dirut a
        ");
    }
    public function editchedule($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_schedule_dirut', $data);
    }
    public function reschedule($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('tb_schedule_dirut', $data);
    }
    public function deleteschedule($id)
    {
        return $this->db->delete('tb_schedule_dirut', array("id" => $id));
    }
}
