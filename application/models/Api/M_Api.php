<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Api extends CI_Model
{
    protected $preDoTable = 'tb_pre_do';
    protected $prePoTable = 'tb_pre_po';
    protected $prePoDiscountHistoryTable = 'tb_pre_po_diskon_history';
    protected $prePoInvoiceAdjustmentTable = 'tb_pre_po_invoice_adjustment';
    protected $syncCacheFile;

    public function __construct()
    {
        parent::__construct();
        $this->syncCacheFile = APPPATH . 'cache/pre_po_sync_last.json';
    }

    public function get_all($limit = 100, $offset = 0)
    {
        return $this->db
            ->limit($limit, $offset)
            ->order_by('id', 'DESC')
            ->get($this->preDoTable)
            ->result_array();
    }

    public function get_by_kode_faktur($kode_faktur)
    {
        return $this->db
            ->where('kode_faktur', $kode_faktur)
            ->get($this->preDoTable)
            ->row_array();
    }

    public function get_by_kdupdate($kdupdate)
    {
        return $this->db
            ->where('kdupdate', $kdupdate)
            ->get($this->preDoTable)
            ->result_array();
    }

    public function get_recent_pre_po($limit = 100)
    {
        $limit = (int) $limit;
        if ($limit <= 0) {
            $limit = 100;
        }

        return $this->db
            ->select('id_pre_po, no_po, kd_po, tgl_transaksi, kd_suplier, kd_barang, satuan, qty, hrg_satuan, harga_total, status, create_at')
            ->from($this->prePoTable)
            ->order_by('id_pre_po', 'DESC')
            ->limit($limit)
            ->get()
            ->result_array();
    }

    public function get_last_sync_info()
    {
        if (!is_file($this->syncCacheFile)) {
            return null;
        }

        $content = @file_get_contents($this->syncCacheFile);
        if ($content === false || $content === '') {
            return null;
        }

        $decoded = json_decode($content, true);

        return json_last_error() === JSON_ERROR_NONE ? $decoded : null;
    }

    public function sync_pre_po_from_remote($url)
    {
        $fetched = $this->fetch_remote_json($url);

        if (!$fetched['status']) {
            return [
                'status'    => false,
                'message'   => $fetched['message'],
                'http_code' => (int) ($fetched['http_code'] ?: 500),
                'skipped'   => 0
            ];
        }

        $rows = $this->unwrap_api_rows($fetched['data']);

        if (!is_array($rows)) {
            return [
                'status'    => false,
                'message'   => 'Format data API tidak sesuai',
                'http_code' => 422,
                'skipped'   => 0
            ];
        }

        $result = $this->sync_pre_po_payload($rows, $url);

        return $result;
    }

    public function sync_pre_po_payload(array $rows, $sourceUrl = '')
    {
        $this->ensure_pre_po_discount_history_table();
        $this->ensure_pre_po_invoice_adjustment_table();

        $dedupedRows = [];
        $discountHistoryMap = $this->collect_discount_histories($rows);
        $invoiceAdjustmentRows = $this->collect_invoice_adjustment_rows($rows);
        $skipped = 0;

        foreach ($rows as $row) {
            $normalized = $this->normalize_pre_po_row($row);

            if ($normalized === null) {
                $skipped++;
                continue;
            }

            $key = $this->build_composite_key($normalized['no_po'], $normalized['kd_barang']);
            $dedupedRows[$key] = $normalized;
        }

        if (empty($dedupedRows)) {
            return [
                'status'        => true,
                'message'       => 'Tidak ada data baru untuk disinkronkan',
                'inserted'      => 0,
                'updated'       => 0,
                'skipped'       => $skipped,
                'total_fetched' => count($rows),
                'sync_time'     => date('Y-m-d H:i:s'),
                'http_code'     => 200
            ];
        }

        $existingMap = $this->get_existing_pre_po_map($dedupedRows);
        $insertBatch = [];
        $updateBatch = [];
        $inserted = 0;
        $updated = 0;

        foreach ($dedupedRows as $key => $row) {
            if (!isset($existingMap[$key])) {
                $row['create_at'] = date('Y-m-d H:i:s');
                $insertBatch[] = $row;
                continue;
            }

            $existing = $existingMap[$key];

            if ((int) $existing['status'] === 2) {
                $skipped++;
                continue;
            }

            $payloadForUpdate = $row;
            $payloadForUpdate['id_pre_po'] = (int) $existing['id_pre_po'];

            if ($this->is_pre_po_changed($existing, $row)) {
                $updateBatch[] = $payloadForUpdate;
            } else {
                $skipped++;
            }
        }

        $this->db->trans_strict(true);
        $this->db->trans_begin();

        if (!empty($insertBatch)) {
            foreach (array_chunk($insertBatch, 500) as $chunk) {
                $ok = $this->db->insert_batch($this->prePoTable, $chunk);
                if ($ok === false) {
                    $error = $this->db->error();
                    $this->db->trans_rollback();

                    return [
                        'status'    => false,
                        'message'   => 'Gagal insert data sinkronisasi: ' . ($error['message'] ?? 'Unknown database error'),
                        'http_code' => 500,
                        'skipped'   => $skipped
                    ];
                }

                $inserted += count($chunk);
            }
        }

        if (!empty($updateBatch)) {
            foreach (array_chunk($updateBatch, 500) as $chunk) {
                $ok = $this->db->update_batch($this->prePoTable, $chunk, 'id_pre_po');
                if ($ok === false) {
                    $error = $this->db->error();
                    $this->db->trans_rollback();

                    return [
                        'status'    => false,
                        'message'   => 'Gagal update data sinkronisasi: ' . ($error['message'] ?? 'Unknown database error'),
                        'http_code' => 500,
                        'skipped'   => $skipped
                    ];
                }

                $updated += count($chunk);
            }
        }

        if ($this->db->trans_status() === false) {
            $error = $this->db->error();
            $this->db->trans_rollback();

            return [
                'status'    => false,
                'message'   => 'Transaksi database gagal: ' . ($error['message'] ?? 'Unknown database error'),
                'http_code' => 500,
                'skipped'   => $skipped
            ];
        }

        $discountSync = $this->replace_discount_histories($discountHistoryMap);
        if (!$discountSync['status']) {
            $this->db->trans_rollback();

            return [
                'status'    => false,
                'message'   => $discountSync['message'],
                'http_code' => 500,
                'skipped'   => $skipped
            ];
        }

        $invoiceAdjustmentSync = $this->upsert_invoice_adjustments($invoiceAdjustmentRows);
        if (!$invoiceAdjustmentSync['status']) {
            $this->db->trans_rollback();

            return [
                'status'    => false,
                'message'   => $invoiceAdjustmentSync['message'],
                'http_code' => 500,
                'skipped'   => $skipped
            ];
        }

        $this->db->trans_commit();

        $syncTime = date('Y-m-d H:i:s');
        $syncInfo = [
            'sync_time'     => $syncTime,
            'source_url'    => $sourceUrl,
            'inserted'      => $inserted,
            'updated'       => $updated,
            'skipped'       => $skipped,
            'discount_rows' => (int) $discountSync['rows'],
            'invoice_adjustment_rows' => (int) $invoiceAdjustmentSync['rows'],
            'total_fetched' => count($rows)
        ];
        $this->save_last_sync_info($syncInfo);

        return [
            'status'        => true,
            'message'       => 'Sinkronisasi berhasil',
            'inserted'      => $inserted,
            'updated'       => $updated,
            'skipped'       => $skipped,
            'discount_rows' => (int) $discountSync['rows'],
            'invoice_adjustment_rows' => (int) $invoiceAdjustmentSync['rows'],
            'total_fetched' => count($rows),
            'sync_time'     => $syncTime,
            'http_code'     => 200
        ];
    }

    protected function fetch_remote_json($url)
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 120);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Accept: application/json']);

            $response = curl_exec($ch);
            $curlError = curl_error($ch);
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($response === false || $curlError) {
                return [
                    'status'    => false,
                    'message'   => 'Gagal mengakses API sumber: ' . ($curlError ?: 'Unknown cURL error'),
                    'http_code' => 500
                ];
            }
        } else {
            $context = stream_context_create([
                'http' => [
                    'method'  => 'GET',
                    'timeout' => 120,
                    'header'  => "Accept: application/json\r\n"
                ]
            ]);

            $response = @file_get_contents($url, false, $context);
            $httpCode = 200;

            if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $matches)) {
                $httpCode = (int) $matches[1];
            }

            if ($response === false) {
                return [
                    'status'    => false,
                    'message'   => 'Gagal mengakses API sumber menggunakan file_get_contents',
                    'http_code' => 500
                ];
            }
        }

        if ($httpCode >= 400) {
            return [
                'status'    => false,
                'message'   => 'API sumber mengembalikan HTTP ' . $httpCode,
                'http_code' => $httpCode
            ];
        }

        $decoded = json_decode($response, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [
                'status'    => false,
                'message'   => 'Response API bukan JSON yang valid',
                'http_code' => 422
            ];
        }

        return [
            'status'    => true,
            'data'      => $decoded,
            'http_code' => $httpCode
        ];
    }

    protected function unwrap_api_rows($payload)
    {
        if (isset($payload['data']) && is_array($payload['data'])) {
            return $payload['data'];
        }

        if (isset($payload['result']) && is_array($payload['result'])) {
            return $payload['result'];
        }

        if (isset($payload['rows']) && is_array($payload['rows'])) {
            return $payload['rows'];
        }

        if (isset($payload['po']) && is_array($payload['po'])) {
            return $payload['po'];
        }

        return is_array($payload) ? $payload : [];
    }

    protected function normalize_pre_po_row($row)
    {
        if (!is_array($row)) {
            return null;
        }

        $noPo = trim((string) $this->pick_value($row, ['no_po', 'nomor_po']));
        $kdPo = trim((string) $this->pick_value($row, ['kd_po', 'kode_po']));
        $tglTransaksi = trim((string) $this->pick_value($row, ['tgl_transaksi', 'tanggal', 'tanggal_transaksi']));
        $kdSuplier = trim((string) $this->pick_value($row, ['kd_suplier', 'kdsupp', 'kode_supplier', 'kd_supplier']));
        $kdBarang = trim((string) $this->pick_value($row, ['kd_barang', 'kode_barang']));
        $satuan = trim((string) $this->pick_value($row, ['satuan']));

        if ($noPo === '' || $kdBarang === '') {
            return null;
        }

        $qty = (int) $this->sanitize_numeric($this->pick_value($row, ['qty', 'jumlah']));
        $hargaSatuan = (int) $this->sanitize_numeric($this->pick_value($row, ['hrg_satuan', 'harga_satuan']));
        $hargaTotalValue = $this->pick_value($row, ['hrg_total', 'harga_total']);
        $hargaTotal = $hargaTotalValue === null || $hargaTotalValue === ''
            ? ($qty * $hargaSatuan)
            : (int) $this->sanitize_numeric($hargaTotalValue);
        $status = (int) $this->sanitize_numeric($this->pick_value($row, ['status']));

        if ($status !== 2) {
            $status = 1;
        }

        return [
            'no_po'         => $noPo,
            'kd_po'         => $kdPo,
            'tgl_transaksi' => $tglTransaksi,
            'kd_suplier'    => $kdSuplier,
            'kd_barang'     => $kdBarang,
            'satuan'        => $satuan,
            'qty'           => $qty,
            'hrg_satuan'    => $hargaSatuan,
            'harga_total'   => $hargaTotal,
            'status'        => $status
        ];
    }

    protected function collect_invoice_adjustment_rows(array $rows)
    {
        $map = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $normalized = $this->normalize_invoice_adjustment_row($row);
            if ($normalized === null) {
                continue;
            }

            $key = $this->build_invoice_adjustment_key($normalized['kd_po'], $normalized['kd_barang']);
            $map[$key] = $normalized;
        }

        return array_values($map);
    }

    protected function normalize_invoice_adjustment_row(array $row)
    {
        $kdPo = trim((string) $this->pick_value($row, ['kd_po', 'kode_po']));
        $kdBarang = trim((string) $this->pick_value($row, ['kd_barang', 'kode_barang']));

        if ($kdPo === '' || $kdBarang === '') {
            return null;
        }

        $qty = $this->sanitize_decimal($this->pick_value($row, ['qty', 'jumlah']));
        $hargaSatuan = $this->sanitize_decimal($this->pick_value($row, ['harga_satuan', 'hrg_satuan']));
        $harga = $this->sanitize_decimal($this->pick_value($row, ['harga', 'hrg_satuan', 'harga_satuan']));
        $hargaDiskon = $this->sanitize_decimal($this->pick_value($row, ['harga_diskon', 'hrg_diskon']));
        $totalHargaValue = $this->pick_value($row, ['total_harga', 'hrg_total', 'harga_total']);
        $totalHargaDiskonValue = $this->pick_value($row, ['total_harga_diskon', 'hrg_total_diskon', 'harga_total_diskon']);
        $totalHarga = $totalHargaValue === null || $totalHargaValue === ''
            ? $qty * $hargaSatuan
            : $this->sanitize_decimal($totalHargaValue);
        $totalHargaDiskon = $totalHargaDiskonValue === null || $totalHargaDiskonValue === ''
            ? $qty * $hargaDiskon
            : $this->sanitize_decimal($totalHargaDiskonValue);
        $taxPercent = $this->sanitize_decimal($this->pick_value($row, ['tax', 'pajak']));
        $tax = ($taxPercent / 100) * $totalHarga;
        $taxDiskonValue = $this->pick_value($row, ['tax_diskon', 'pajak_diskon']);
        $taxDiskon = $taxDiskonValue === null || $taxDiskonValue === ''
            ? ($taxPercent / 100) * $totalHargaDiskon
            : $this->sanitize_decimal($taxDiskonValue);
        $grandTotalValue = $this->pick_value($row, ['grand_total']);
        $grandTotalDiskonValue = $this->pick_value($row, ['grand_total_diskon']);

        return [
            'no_po'              => trim((string) $this->pick_value($row, ['no_po', 'nomor_po'])),
            'kd_po'              => $kdPo,
            'tgl_transaksi'      => trim((string) $this->pick_value($row, ['tgl_transaksi', 'tanggal', 'tanggal_transaksi'])),
            'kd_suplier'         => trim((string) $this->pick_value($row, ['kd_suplier', 'kdsupp', 'kode_supplier', 'kd_supplier'])),
            'kd_barang'          => $kdBarang,
            'satuan'             => trim((string) $this->pick_value($row, ['satuan'])),
            'qty'                => $qty,
            'harga_satuan'       => $hargaSatuan,
            'harga'              => $harga,
            'harga_diskon'       => $hargaDiskon,
            'total_harga'        => $totalHarga,
            'total_harga_diskon' => $totalHargaDiskon,
            'tax_percent'        => $taxPercent,
            'tax'                => $tax,
            'tax_diskon'         => $taxDiskon,
            'grand_total'        => $grandTotalValue === null || $grandTotalValue === '' ? $totalHarga + $tax : $this->sanitize_decimal($grandTotalValue),
            'grand_total_diskon' => $grandTotalDiskonValue === null || $grandTotalDiskonValue === '' ? $totalHargaDiskon + $taxDiskon : $this->sanitize_decimal($grandTotalDiskonValue),
            'source_payload'     => json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'synced_at'          => date('Y-m-d H:i:s')
        ];
    }

    protected function collect_discount_histories(array $rows)
    {
        $map = [];

        foreach ($rows as $row) {
            if (!is_array($row)) {
                continue;
            }

            $kdPo = trim((string) $this->pick_value($row, ['kd_po', 'kode_po']));
            if ($kdPo === '') {
                continue;
            }

            $histories = $this->pick_value($row, [
                'histori_diskon',
                'history_diskon',
                'diskon_history',
                'discount_history',
                'diskon',
                'discounts'
            ]);

            if (!is_array($histories)) {
                continue;
            }

            $normalizedRows = [];
            foreach ($histories as $history) {
                $normalized = $this->normalize_discount_history_row($kdPo, $history);
                if ($normalized !== null) {
                    $normalizedRows[] = $normalized;
                }
            }

            if (!empty($normalizedRows)) {
                $map[$kdPo] = $normalizedRows;
            }
        }

        return $map;
    }

    protected function normalize_discount_history_row($kdPo, $row)
    {
        if (!is_array($row)) {
            return null;
        }

        $keterangan = trim((string) $this->pick_value($row, ['keterangan', 'description', 'note']));
        $nominal = $this->sanitize_decimal($this->pick_value($row, ['nominal', 'nilai', 'amount']));

        if ($keterangan === '' && $nominal == 0) {
            return null;
        }

        return [
            'kd_po'            => $kdPo,
            'id_diskon_source' => (int) $this->sanitize_numeric($this->pick_value($row, ['id_diskon', 'id'])),
            'kd_suplier'       => trim((string) $this->pick_value($row, ['kd_suplier', 'kd_supplier', 'kdsupp'])),
            'no_po'            => trim((string) $this->pick_value($row, ['no_po', 'nomor_po'])),
            'tgl_transaksi'    => trim((string) $this->pick_value($row, ['tgl_transaksi', 'tanggal', 'tanggal_transaksi'])),
            'nama_suplier'     => trim((string) $this->pick_value($row, ['nama_suplier', 'nm_suplier', 'nama_supplier'])),
            'keterangan'       => $keterangan,
            'nominal'          => $nominal,
            'source_payload'   => json_encode($row, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
            'synced_at'        => date('Y-m-d H:i:s')
        ];
    }

    protected function replace_discount_histories(array $discountHistoryMap)
    {
        if (empty($discountHistoryMap)) {
            return ['status' => true, 'rows' => 0];
        }

        $kdPoList = array_keys($discountHistoryMap);
        $this->db->where_in('kd_po', $kdPoList)->delete($this->prePoDiscountHistoryTable);

        if ($this->db->error()['code']) {
            $error = $this->db->error();
            return [
                'status'  => false,
                'message' => 'Gagal hapus histori diskon lama: ' . ($error['message'] ?? 'Unknown database error')
            ];
        }

        $insertBatch = [];
        foreach ($discountHistoryMap as $rows) {
            foreach ($rows as $row) {
                $insertBatch[] = $row;
            }
        }

        $inserted = 0;
        foreach (array_chunk($insertBatch, 500) as $chunk) {
            $ok = $this->db->insert_batch($this->prePoDiscountHistoryTable, $chunk);
            if ($ok === false) {
                $error = $this->db->error();
                return [
                    'status'  => false,
                    'message' => 'Gagal simpan histori diskon: ' . ($error['message'] ?? 'Unknown database error')
                ];
            }

            $inserted += count($chunk);
        }

        return ['status' => true, 'rows' => $inserted];
    }

    protected function ensure_pre_po_discount_history_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->prePoDiscountHistoryTable}` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `kd_po` varchar(255) NOT NULL,
            `id_diskon_source` int(11) DEFAULT NULL,
            `kd_suplier` varchar(35) DEFAULT NULL,
            `no_po` varchar(255) DEFAULT NULL,
            `tgl_transaksi` varchar(25) DEFAULT NULL,
            `nama_suplier` varchar(255) DEFAULT NULL,
            `keterangan` varchar(255) DEFAULT NULL,
            `nominal` double NOT NULL DEFAULT 0,
            `source_payload` text DEFAULT NULL,
            `synced_at` datetime NOT NULL,
            PRIMARY KEY (`id`),
            KEY `idx_kd_po` (`kd_po`),
            KEY `idx_id_diskon_source` (`id_diskon_source`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

        $this->db->query($sql);
    }

    protected function upsert_invoice_adjustments(array $rows)
    {
        if (empty($rows)) {
            return ['status' => true, 'rows' => 0];
        }

        foreach (array_chunk($rows, 500) as $chunk) {
            $values = [];
            foreach ($chunk as $row) {
                $values[] = $this->db->escape($row['no_po']) . ',' .
                    $this->db->escape($row['kd_po']) . ',' .
                    $this->db->escape($row['tgl_transaksi']) . ',' .
                    $this->db->escape($row['kd_suplier']) . ',' .
                    $this->db->escape($row['kd_barang']) . ',' .
                    $this->db->escape($row['satuan']) . ',' .
                    (float) $row['qty'] . ',' .
                    (float) $row['harga_satuan'] . ',' .
                    (float) $row['harga'] . ',' .
                    (float) $row['harga_diskon'] . ',' .
                    (float) $row['total_harga'] . ',' .
                    (float) $row['total_harga_diskon'] . ',' .
                    (float) $row['tax_percent'] . ',' .
                    (float) $row['tax'] . ',' .
                    (float) $row['tax_diskon'] . ',' .
                    (float) $row['grand_total'] . ',' .
                    (float) $row['grand_total_diskon'] . ',' .
                    $this->db->escape($row['source_payload']) . ',' .
                    $this->db->escape($row['synced_at']);
            }

            $sql = "INSERT INTO `{$this->prePoInvoiceAdjustmentTable}` (
                    `no_po`, `kd_po`, `tgl_transaksi`, `kd_suplier`, `kd_barang`, `satuan`, `qty`,
                    `harga_satuan`, `harga`, `harga_diskon`, `total_harga`, `total_harga_diskon`,
                    `tax_percent`, `tax`, `tax_diskon`, `grand_total`, `grand_total_diskon`,
                    `source_payload`, `synced_at`
                ) VALUES (" . implode('),(', $values) . ")
                ON DUPLICATE KEY UPDATE
                    `no_po` = VALUES(`no_po`),
                    `tgl_transaksi` = VALUES(`tgl_transaksi`),
                    `kd_suplier` = VALUES(`kd_suplier`),
                    `satuan` = VALUES(`satuan`),
                    `qty` = VALUES(`qty`),
                    `harga_satuan` = VALUES(`harga_satuan`),
                    `harga` = VALUES(`harga`),
                    `harga_diskon` = VALUES(`harga_diskon`),
                    `total_harga` = VALUES(`total_harga`),
                    `total_harga_diskon` = VALUES(`total_harga_diskon`),
                    `tax_percent` = VALUES(`tax_percent`),
                    `tax` = VALUES(`tax`),
                    `tax_diskon` = VALUES(`tax_diskon`),
                    `grand_total` = VALUES(`grand_total`),
                    `grand_total_diskon` = VALUES(`grand_total_diskon`),
                    `source_payload` = VALUES(`source_payload`),
                    `synced_at` = VALUES(`synced_at`)";

            $ok = $this->db->query($sql);
            if ($ok === false) {
                $error = $this->db->error();
                return [
                    'status'  => false,
                    'message' => 'Gagal simpan data invoice adjustment: ' . ($error['message'] ?? 'Unknown database error')
                ];
            }
        }

        return ['status' => true, 'rows' => count($rows)];
    }

    protected function ensure_pre_po_invoice_adjustment_table()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `{$this->prePoInvoiceAdjustmentTable}` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `no_po` varchar(255) DEFAULT NULL,
            `kd_po` varchar(255) NOT NULL,
            `tgl_transaksi` varchar(25) DEFAULT NULL,
            `kd_suplier` varchar(35) DEFAULT NULL,
            `kd_barang` varchar(35) NOT NULL,
            `satuan` varchar(50) DEFAULT NULL,
            `qty` double NOT NULL DEFAULT 0,
            `harga_satuan` double NOT NULL DEFAULT 0,
            `harga` double NOT NULL DEFAULT 0,
            `harga_diskon` double NOT NULL DEFAULT 0,
            `total_harga` double NOT NULL DEFAULT 0,
            `total_harga_diskon` double NOT NULL DEFAULT 0,
            `tax_percent` double NOT NULL DEFAULT 0,
            `tax` double NOT NULL DEFAULT 0,
            `tax_diskon` double NOT NULL DEFAULT 0,
            `grand_total` double NOT NULL DEFAULT 0,
            `grand_total_diskon` double NOT NULL DEFAULT 0,
            `source_payload` text DEFAULT NULL,
            `synced_at` datetime NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uniq_kd_po_barang` (`kd_po`, `kd_barang`),
            KEY `idx_kd_po` (`kd_po`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

        $this->db->query($sql);
    }

    protected function get_existing_pre_po_map(array $dedupedRows)
    {
        $noPoList = [];
        $kdBarangList = [];

        foreach ($dedupedRows as $row) {
            $noPoList[] = $row['no_po'];
            $kdBarangList[] = $row['kd_barang'];
        }

        $existingRows = $this->db
            ->select('id_pre_po, no_po, kd_po, tgl_transaksi, kd_suplier, kd_barang, satuan, qty, hrg_satuan, harga_total, status')
            ->from($this->prePoTable)
            ->where_in('no_po', array_values(array_unique($noPoList)))
            ->where_in('kd_barang', array_values(array_unique($kdBarangList)))
            ->get()
            ->result_array();

        $map = [];
        foreach ($existingRows as $existing) {
            $key = $this->build_composite_key($existing['no_po'], $existing['kd_barang']);
            $map[$key] = $existing;
        }

        return $map;
    }

    protected function is_pre_po_changed(array $existing, array $incoming)
    {
        $fields = [
            'no_po',
            'kd_po',
            'tgl_transaksi',
            'kd_suplier',
            'kd_barang',
            'satuan',
            'qty',
            'hrg_satuan',
            'harga_total',
            'status'
        ];

        foreach ($fields as $field) {
            $existingValue = isset($existing[$field]) ? (string) $existing[$field] : '';
            $incomingValue = isset($incoming[$field]) ? (string) $incoming[$field] : '';

            if ($existingValue !== $incomingValue) {
                return true;
            }
        }

        return false;
    }

    protected function save_last_sync_info(array $syncInfo)
    {
        $encoded = json_encode($syncInfo, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if ($encoded === false) {
            return false;
        }

        $written = @file_put_contents($this->syncCacheFile, $encoded, LOCK_EX);

        if ($written === false) {
            log_message('error', 'Gagal menyimpan metadata sync pre PO ke ' . $this->syncCacheFile);
            return false;
        }

        return true;
    }

    protected function build_composite_key($noPo, $kdBarang)
    {
        return trim((string) $noPo) . '||' . trim((string) $kdBarang);
    }

    protected function build_invoice_adjustment_key($kdPo, $kdBarang)
    {
        return trim((string) $kdPo) . '||' . trim((string) $kdBarang);
    }

    protected function pick_value(array $row, array $keys)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row)) {
                return $row[$key];
            }
        }

        return null;
    }

    protected function sanitize_numeric($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_numeric($value)) {
            return $value;
        }

        return preg_replace('/[^\d\-]/', '', (string) $value);
    }

    protected function sanitize_decimal($value)
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $cleaned = preg_replace('/[^\d\.\,\-]/', '', (string) $value);
        $cleaned = str_replace(',', '.', $cleaned);

        return is_numeric($cleaned) ? (float) $cleaned : 0;
    }
}
