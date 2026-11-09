<?php
defined('BASEPATH') or exit('No direct script access allowed');

/** Serializes normal issuance and explicit, audited asset reissue. */
class M_Catalog_qr_issue extends CI_Model
{
    public function issue($id, $date, $actor, $reissue, $reason, $expectedFile, callable $validate, callable $generate, $missingOnly = false)
    {
        $this->db->db_debug=false;
        $dt=DateTime::createFromFormat('!Y-m-d',(string)$date);
        if ((int)$id<=0 || !$dt || $dt->format('Y-m-d')!==$date) throw new RuntimeException('Identitas barang/tanggal QR tidak valid.');
        if ($missingOnly && !$reissue) throw new RuntimeException('Pemulihan file hilang wajib melalui reassign.');
        if ($reissue && (!is_string($reason) || mb_strlen(trim($reason),'UTF-8')<5 || mb_strlen($reason,'UTF-8')>500 || preg_match('/[\x00-\x1f]/',$reason))) throw new RuntimeException('Alasan resign wajib 5-500 karakter tanpa karakter kontrol.');
        foreach(['stockopname_catalog_qrcode','stockopname_catalog_qr_issue','tb_master_barang_all'] as $table) {
            $engine=$this->db->query('SELECT ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=?',[$table])->row_array();
            if(!$engine || $engine['ENGINE']!=='InnoDB') throw new RuntimeException('Migration QR issue belum siap; semua tabel wajib InnoDB.');
        }
        $locked=false;$tx=false;$path=null;
        try {
            $lock=$this->db->query("SELECT GET_LOCK('stockopname_master_barang_kiubr',0) locked")->row_array();
            if(!$lock || (int)$lock['locked']!==1) throw new RuntimeException('Impor/generate QR lain sedang berjalan. Coba kembali.');
            $locked=true;$this->db->trans_begin();$tx=true;
            $this->db->query('SELECT id FROM tb_master_barang_all WHERE id=? FOR UPDATE',[(int)$id]);
            $saved=$this->db->query('SELECT * FROM stockopname_catalog_qrcode WHERE catalog_id=? AND expired_date=? FOR UPDATE',[(int)$id,$date])->row_array();
            if ($missingOnly) {
                if ($saved) clearstatcache(true,FCPATH.$saved['qrcode_file']);
                if (!$saved || is_file(FCPATH.$saved['qrcode_file'])) {
                    $this->db->trans_rollback();$tx=false;
                    return ['status'=>false,'skipped'=>true,'message'=>'QR belum tercatat atau file sudah tersedia; dilewati.'];
                }
            }
            if(!$reissue && $saved) {
                $this->db->trans_rollback();$tx=false;
                return ['status'=>false,'skipped'=>true,'message'=>'QR sudah pernah digenerate. Gunakan fitur Resign QR dengan alasan, termasuk bila file hilang.'];
            }
            if($reissue && (!$saved || !is_string($expectedFile) || !hash_equals($saved['qrcode_file'],$expectedFile))) throw new RuntimeException('Status QR berubah/tidak ditemukan. Muat ulang katalog sebelum resign.');
            // Check active source after acquiring the import lock, never rely on client fields.
            $row=$validate();
            if(empty($row['status'])) throw new RuntimeException($row['message']??'Referensi QR tidak aktif.');
            $value='MB|'.(int)$id.'|'.$date;
            $path='assets/qrcode/stockopname/MB_'.(int)$id.'_'.str_replace('-','',$date).'_'.bin2hex(random_bytes(12)).'.png';
            if(!$generate($value,FCPATH.$path) || !is_file(FCPATH.$path)) throw new RuntimeException('Gagal membuat aset QR lokal.');
            $qr=['qrcode_value'=>$value,'qrcode_file'=>$path,'generated_at'=>gmdate('Y-m-d H:i:s')];
            if($saved) $this->db->where('catalog_id',(int)$id)->where('expired_date',$date)->update('stockopname_catalog_qrcode',$qr);
            else $this->db->insert('stockopname_catalog_qrcode',array_merge(['catalog_id'=>(int)$id,'expired_date'=>$date],$qr));
            $this->db->insert('stockopname_catalog_qr_issue',['catalog_id'=>(int)$id,'expired_date'=>$date,'operation'=>$reissue?'resign':'generate','actor'=>mb_substr($actor,0,128,'UTF-8'),'reason'=>$reissue?trim($reason):'Penerbitan pertama','previous_file'=>$saved?$saved['qrcode_file']:null,'qrcode_file'=>$path,'qrcode_value'=>$value,'issued_at'=>gmdate('Y-m-d H:i:s')]);
            $this->db->where('id',(int)$id)->update('tb_master_barang_all',['qrcode'=>$path]);
            if(!$this->db->trans_status()) throw new RuntimeException('Transaksi QR gagal; QR sebelumnya tetap aktif.');
            if(!$this->db->trans_commit()) throw new RuntimeException('Commit QR gagal.');
            $tx=false;
            return ['status'=>true,'path'=>$path,'barang'=>$row['barang'],'message'=>$reissue?'QR berhasil di-resign; aset lama dan histori tetap disimpan.':'QR berhasil digenerate.'];
        } finally {
            if($tx) { $this->db->trans_rollback(); if($path && is_file(FCPATH.$path)) unlink(FCPATH.$path); }
            if($locked)$this->db->query("SELECT RELEASE_LOCK('stockopname_master_barang_kiubr')");
        }
    }
}
