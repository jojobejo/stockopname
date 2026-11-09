<?php
defined('BASEPATH') or exit('No direct script access allowed');
class M_Zahir_snapshot extends CI_Model
{
    public function ready()
    {
        $this->db->db_debug=false;
        foreach (['stockopname_zahir_batch','stockopname_zahir_active','stockopname_zahir_qrlotexp','stockopname_zahir_audit'] as $t) if (!$this->db->table_exists($t)) throw new RuntimeException('Migration snapshot belum dipasang. Hubungi administrator.');
        if (!$this->db->field_exists('input_format','stockopname_zahir_batch')) throw new RuntimeException('Migration penyesuaian impor CSV belum dipasang.');
        $engines=$this->db->query("SELECT TABLE_NAME,ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME IN ('stockopname_zahir_batch','stockopname_zahir_active','stockopname_zahir_qrlotexp','stockopname_zahir_audit')")->result_array();
        foreach($engines as $engine) if($engine['ENGINE']!=='InnoDB') throw new RuntimeException('Semua tabel impor wajib InnoDB.');
        $s=$this->db->query("SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='tb_master_barang_all'")->row_array();
        if (!$s || $s['ENGINE']!=='InnoDB') throw new RuntimeException('Master wajib InnoDB.');
        $cols=$this->db->query("SHOW FULL COLUMNS FROM tb_master_barang_all")->result_array();
        $fields=[];foreach($cols as $col)$fields[$col['Field']]=$col;
        if (($fields['kd_barang']['Type']??'')!=='varchar(25)' || ($fields['kd_barang']['Collation']??'')!=='utf8mb4_unicode_ci') throw new RuntimeException('Skema kode master berubah; audit kompatibilitas diperlukan.');
        foreach(['p','l','t','dimensi'] as $k) if(!preg_match('/^int(?:\([0-9]+\))?$/D',$fields[$k]['Type']??'')) throw new RuntimeException('Domain dimensi master berubah; audit diperlukan.');
    }
    public function active()
    {
        return $this->db->query('SELECT b.* FROM stockopname_zahir_active a JOIN stockopname_zahir_batch b ON b.id=a.batch_id WHERE a.source_instance=? AND a.scope=?',[$this->config->item('zahir_import_source_instance'),$this->config->item('zahir_import_scope')])->row_array();
    }
    public function sourceRows($id)
    {
        return $this->db->query('SELECT r.master_id,r.kode_barang,r.nama_normalized AS nama_barang,r.expired_date,r.qty_lot,m.p,m.l,m.t,m.dimensi FROM stockopname_zahir_qrlotexp r LEFT JOIN tb_master_barang_all m ON m.id=r.master_id WHERE r.batch_id=?',[$id])->result_array();
    }
    public function audit($v,$checksum,$actor,$result,$detail)
    {
        $d=$v['document']??[];
        $safe=function($value,$length){return is_scalar($value)?mb_substr((string)$value,0,$length,'UTF-8'):'';};
        $this->db->insert('stockopname_zahir_audit',['occurred_at'=>gmdate('Y-m-d H:i:s'),'actor'=>$safe($actor,128),'checksum'=>$checksum,'snapshot_id'=>$safe($d['snapshot_id']??'',36),'source_instance'=>$safe($d['source_instance']??'',128),'scope'=>$safe($d['scope']??'',128),'result'=>$result,'detail'=>json_encode($detail)]);
    }
    public function preview($v,$checksum)
    {
        $this->ready(); $d=$v['document'];
        if ($d['source_instance']!==$this->config->item('zahir_import_source_instance') || $d['scope']!==$this->config->item('zahir_import_scope')) throw new RuntimeException('Sumber/cakupan berbeda dari konfigurasi imported.');
        $prior=$this->db->group_start()->where('snapshot_id',$d['snapshot_id'])->or_where('checksum',$checksum)->group_end()->get('stockopname_zahir_batch')->row_array();
        $active=$this->active();
        if ($prior && $prior['checksum']!==$checksum) throw new RuntimeException('Identitas impor telah dipakai dengan isi berbeda.');
        if ($prior && ($prior['source_instance']!==$d['source_instance'] || $prior['scope']!==$d['scope'] || (int)$prior['row_count']!==$d['row_count'])) throw new RuntimeException('Metadata pengiriman ulang berbeda dari batch tersimpan.');
        if ($prior && (!$active || $active['id']!==$prior['id'])) throw new RuntimeException('Snapshot historis tidak boleh diaktifkan kembali melalui impor biasa.');
        $new=[]; $updates=[]; $conflicts=[]; $state=[]; $mapping=[]; $byCode=[]; $comparison=[];
        foreach ($v['rows'] as $r) $byCode[strtoupper(trim($r['kode_barang']))]=$r;
        foreach ($byCode as $key=>$r) {
            // Database collation decides equality; never pick MIN(id).
            $matches=$this->db->query('SELECT * FROM tb_master_barang_all WHERE TRIM(kd_barang)=? ORDER BY id',[trim($r['kode_barang'])])->result_array();
            $state[$key]=$matches;
            if (count($matches)>1) { $conflicts[]=['code'=>$r['kode_barang'],'reason'=>'Beberapa ID master cocok']; continue; }
            $values=['nama_barang'=>$r['nama_normalized']];
            if (!$matches) { $new[]=['code'=>$r['kode_barang'],'values'=>$values]; $comparison[]=['code'=>$r['kode_barang'],'status'=>'missing_local','csv_name'=>$r['nama_normalized']]; continue; }
            $m=$matches[0]; $mapping[$key]=(int)$m['id']; $changes=[];
            foreach ($values as $k=>$val) if ((string)$m[$k] !== (string)$val) $changes[$k]=['before'=>$m[$k],'after'=>$val];
            if ($changes) $updates[]=['id'=>$m['id'],'code'=>$r['kode_barang'],'changes'=>$changes];
            $comparison[]=['code'=>$r['kode_barang'],'local_id'=>$m['id'],'status'=>$changes?'name_changed':'matched','local_name'=>$m['nama_barang'],'csv_name'=>$r['nama_normalized'],'local_dimensions_preserved'=>['p'=>$m['p'],'l'=>$m['l'],'t'=>$m['t'],'dimensi'=>$m['dimensi']]];
        }
        $old=$active?$this->sourceRows($active['id']):[];
        $grain=function($r){return strtoupper(trim($r['kode_barang'])).'|'.$r['expired_date'];};
        $newkeys=array_fill_keys(array_map($grain,$v['rows']),true); $missing=[];
        foreach($old as $r) if(!isset($newkeys[$grain($r)])) $missing[]=$r;
        $this->load->library('Zahir_lot_catalog');
        $before=$this->zahir_lot_catalog->groupRows($old); $after=$this->zahir_lot_catalog->groupRows($v['rows']);
        $groups=function($rows){$a=[];foreach($rows as $r)$a[strtoupper($r['nama_barang']).'|'.$r['expired_date']]=true;return $a;};
        $bg=$groups($before);$ag=$groups($after);
        return ['checksum'=>$checksum,'snapshot_id'=>$prior?$prior['snapshot_id']:$d['snapshot_id'],'source_instance'=>$d['source_instance'],'scope'=>$d['scope'],'exported_at'=>null,'comparison_mode'=>$this->config->item('zahir_compare_local')?'compare_local':'csv_authoritative','local_comparison'=>$this->config->item('zahir_compare_local')?$comparison:[],'source_count'=>$v['source_count'],'valid_count'=>$v['valid_count'],'error_count'=>count($v['errors']),'existing_count'=>count($mapping),'errors'=>$v['errors'],'duplicates'=>$v['duplicates'],'new'=>$prior?[]:$new,'updates'=>$prior?[]:$updates,'idempotent_note'=>$prior?'CSV identik sudah aktif; perbedaan lokal hanya laporan, tidak disinkronkan ulang.':null,'conflicts'=>$conflicts,'missing'=>$missing,'active_groups'=>count($after),'activated_groups'=>count(array_diff_key($ag,$bg)),'deactivated_groups'=>count(array_diff_key($bg,$ag)),'idempotent'=>(bool)$prior,'fingerprint'=>hash('sha256',json_encode([$state,$active,$checksum,$this->config->item('zahir_compare_local'),$this->config->item('zahir_catalog_mode')])),'mapping'=>$mapping];
    }
    public function commitSnapshot($v,$checksum,$fingerprint,$actor)
    {
        $this->ready(); $locked=false; $tx=false;
        try {
            $lock=$this->db->query("SELECT GET_LOCK('stockopname_master_barang_kiubr',0) locked")->row_array();
            if (!$lock || (int)$lock['locked']!==1) throw new RuntimeException('Impor/master lain sedang berjalan. Coba lagi.');
            $locked=true; $this->db->trans_begin(); $tx=true;
            // Lock all existing master records, including gaps, against concurrent edits/inserts.
            $this->db->query('SELECT id FROM tb_master_barang_all ORDER BY id FOR UPDATE');
            $p=$this->preview($v,$checksum);
            if (!hash_equals($fingerprint,$p['fingerprint'])) throw new RuntimeException('Keadaan master/snapshot berubah. Ulangi dry-run.');
            if ($p['errors'] || $p['conflicts']) throw new RuntimeException('Masih ada error/konflik; seluruh impor dibatalkan.');
            if ($p['idempotent']) {
                $this->db->trans_rollback(); $tx=false;
                $auditV=$v;$auditV['document']['snapshot_id']=$p['snapshot_id'];
                $this->audit($auditV,$checksum,$actor,'noop',['message'=>'CSV identik sudah aktif; tidak ada perubahan master/referensi.']);
                return $p;
            }
            $previousActive=$this->active();
            $changes=[];
            foreach($p['updates'] as $u) {
                $values=[];foreach($u['changes'] as $k=>$change)$values[$k]=$change['after'];
                $this->db->where('id',$u['id'])->update('tb_master_barang_all',$values);$changes[]=$u;
            }
            $max=0;
            foreach($this->db->query("SELECT kode_barang_system FROM tb_master_barang_all WHERE kode_barang_system REGEXP '^KIUBR[0-9]+$'")->result_array() as $m)$max=max($max,(int)substr($m['kode_barang_system'],5));
            $mapping=$p['mapping'];
            foreach($p['new'] as $n) {
                $max++; $system='KIUBR'.str_pad($max,4,'0',STR_PAD_LEFT);
                if(strlen($system)>50) throw new RuntimeException('Kode sistem melebihi kapasitas.');
                $row=array_merge($n['values'],['kd_barang'=>trim($n['code']),'kode_barang_system'=>$system,'kd_supplier'=>'-','bhn_aktif'=>'-','satuan'=>'-','berat'=>0,'kubikasi'=>'0','qty_min'=>0,'hpp'=>'0.00','p'=>0,'l'=>0,'t'=>0,'dimensi'=>0]);
                $this->db->insert('tb_master_barang_all',$row);$id=$this->db->insert_id();
                $mapping[strtoupper(trim($n['code']))]=$id;$changes[]=['id'=>$id,'created'=>true,'after'=>$row];
            }
            $d=$v['document'];
            $this->db->insert('stockopname_zahir_batch',['snapshot_id'=>strtolower($d['snapshot_id']),'checksum'=>$checksum,'source_instance'=>$d['source_instance'],'scope'=>$d['scope'],'exported_at'=>$d['exported_at'],'exported_epoch'=>null,'input_format'=>'csv-v1','source_file'=>mb_substr(basename($d['source_file']??'import.csv'),0,255,'UTF-8'),'imported_at'=>gmdate('Y-m-d H:i:s'),'actor'=>substr($actor,0,128),'row_count'=>count($v['rows']),'master_changes'=>json_encode($changes)]);
            $batch=$this->db->insert_id();
            $this->db->where('id',$batch)->update('stockopname_zahir_batch',['previous_batch_id'=>$previousActive?$previousActive['id']:null]);
            foreach($v['rows'] as $i=>$r) {
                $this->db->insert('stockopname_zahir_qrlotexp',['batch_id'=>$batch,'source_row'=>$i+1,'kode_barang'=>$r['kode_barang'],'master_id'=>$mapping[strtoupper(trim($r['kode_barang']))],'nama_sumber'=>$r['nama_barang'],'nama_normalized'=>$r['nama_normalized'],'expired_date'=>$r['expired_date'],'qty_lot'=>$r['qty_lot']]);
            }
            $this->db->query('INSERT INTO stockopname_zahir_active(source_instance,scope,batch_id) VALUES(?,?,?) ON DUPLICATE KEY UPDATE batch_id=VALUES(batch_id)',[$d['source_instance'],$d['scope'],$batch]);
            $this->audit($v,$checksum,$actor,'committed',['batch_id'=>$batch,'changes'=>$changes]);
            if(!$this->db->trans_status()) throw new RuntimeException('Transaksi gagal. Tidak ada snapshot yang diaktifkan.');
            $this->db->trans_commit();$tx=false;return $p;
        } catch(Throwable $e) { if($tx)$this->db->trans_rollback(); throw $e; }
        finally { if($locked)$this->db->query("SELECT RELEASE_LOCK('stockopname_master_barang_kiubr')"); }
    }

    /** Separate audited recovery: undo the active batch only, preserving all IDs. */
    public function restorePrevious($expectedSnapshot,$actor)
    {
        $this->ready();$locked=false;$tx=false;
        try {
            $lock=$this->db->query("SELECT GET_LOCK('stockopname_master_barang_kiubr',0) locked")->row_array();
            if(!$lock || (int)$lock['locked']!==1) throw new RuntimeException('Impor/master lain sedang berjalan.');
            $locked=true;$this->db->trans_begin();$tx=true;
            $this->db->query('SELECT id FROM tb_master_barang_all ORDER BY id FOR UPDATE');
            $active=$this->active();
            if(!$active || !hash_equals($active['snapshot_id'],$expectedSnapshot)) throw new RuntimeException('Snapshot aktif berubah; periksa ulang status.');
            $previous=$this->db->query('SELECT * FROM stockopname_zahir_batch WHERE source_instance=? AND scope=? AND id=?',[$active['source_instance'],$active['scope'],$active['previous_batch_id']])->row_array();
            if(!$previous) throw new RuntimeException('Tidak ada snapshot sebelumnya. Pulihkan backup terverifikasi secara terpisah.');
            $changes=json_decode($active['master_changes'],true);
            foreach($changes as $change) {
                if(!empty($change['created'])) continue; // New masters/QR/history must survive recovery.
                $row=$this->db->where('id',$change['id'])->get('tb_master_barang_all')->row_array();$values=[];
                foreach($change['changes'] as $k=>$delta) {
                    if(!$row || (string)$row[$k] !== (string)$delta['after']) throw new RuntimeException('Master telah berubah setelah impor; rollback otomatis ditahan.');
                    $values[$k]=$delta['before'];
                }
                $this->db->where('id',$change['id'])->update('tb_master_barang_all',$values);
            }
            $this->db->where('source_instance',$active['source_instance'])->where('scope',$active['scope'])->update('stockopname_zahir_active',['batch_id'=>$previous['id']]);
            $this->audit(['document'=>$active],$active['checksum'],$actor,'restored_previous',['from'=>$active['id'],'to'=>$previous['id'],'retained_new_masters'=>true]);
            if(!$this->db->trans_status()) throw new RuntimeException('Rollback transaksi gagal.');
            $this->db->trans_commit();$tx=false;
        } finally {if($tx)$this->db->trans_rollback();if($locked)$this->db->query("SELECT RELEASE_LOCK('stockopname_master_barang_kiubr')");}
    }
}
