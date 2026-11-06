<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Zahir_lot_catalog
{
    private $ci;
    private $lotCache;
    private $dimensionCache;

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->config('zahir_stock_lot');
        $this->lotCache = APPPATH . 'cache/zahir_master_barang_lot_qty_v2.json';
        $this->dimensionCache = APPPATH . 'cache/zahir_master_barang_dimensions.json';
    }

    public function rows($refresh = false)
    {
        $lots = $this->cachedSource($this->lotCache, $refresh, 'fetchLotRows');
        if (!$lots['status']) {
            return $lots;
        }
        $dimensions = $this->cachedSource($this->dimensionCache, $refresh, 'fetchDimensions');
        if (!$dimensions['status']) {
            return $dimensions;
        }

        $rows = $lots['data'];
        foreach ($rows as &$row) {
            $key = strtoupper(trim((string)$row['kode_barang']));
            $size = $dimensions['data'][$key] ?? null;
            $row['p'] = $size['p'] ?? null;
            $row['l'] = $size['l'] ?? null;
            $row['t'] = $size['t'] ?? null;
            $row['dimensi'] = $size ? $size['p'] * $size['l'] * $size['t'] : null;
        }
        unset($row);
        return [
            'status' => true,
            'data' => $rows,
            'stale' => !empty($lots['stale']) || !empty($dimensions['stale']),
        ];
    }

    private function cachedSource($file, $refresh, $fetchMethod)
    {
        if (!$refresh && is_file($file)) {
            $data = json_decode(file_get_contents($file), true);
            if (is_array($data)) {
                return ['status' => true, 'data' => $data, 'stale' => filemtime($file) <= time() - 300];
            }
        }
        $result = $this->$fetchMethod();
        if ($result['status'] && is_writable(dirname($file))) {
            file_put_contents($file, json_encode($result['data']), LOCK_EX);
        }
        return $result;
    }

    private function fetchHtml($configKey)
    {
        if (!function_exists('curl_init')) {
            return ['status' => false, 'message' => 'Ekstensi cURL PHP belum tersedia.'];
        }
        $curl = curl_init($this->ci->config->item($configKey));
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);
        $html = curl_exec($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($html === false || $code !== 200) {
            return ['status' => false, 'message' => 'Gagal membaca data ZahirDigital (HTTP ' . $code . ').'];
        }
        $document = new DOMDocument();
        $previous = libxml_use_internal_errors(true);
        $loaded = $document->loadHTML('<?xml encoding="UTF-8"?>' . $html);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);
        if (!$loaded) {
            return ['status' => false, 'message' => 'Format data ZahirDigital tidak valid.'];
        }
        return ['status' => true, 'data' => new DOMXPath($document)];
    }

    private function fetchLotRows()
    {
        $source = $this->fetchHtml('zahir_lot_page_url');
        if (!$source['status']) {
            return $source;
        }
        $nodes = $source['data']->query('//table[@id="lotBarangV2Table"]/tbody/tr');
        if (!$nodes || !$nodes->length) {
            return ['status' => false, 'message' => 'Tabel LOT ZahirDigital tidak ditemukan.'];
        }
        $sourceRows = [];
        foreach ($nodes as $node) {
            $cells = $node->getElementsByTagName('td');
            if ($cells->length < 10) {
                continue;
            }
            $kode = trim($cells->item(0)->textContent);
            $nama = trim($cells->item(1)->textContent);
            $date = DateTime::createFromFormat('!j/n/Y', trim($cells->item(9)->textContent));
            $expired = $date ? $date->format('Y-m-d') : trim($cells->item(9)->textContent);
            $quantity = (float)str_replace(',', '.', trim($cells->item(5)->textContent));
            $sourceRows[] = ['kode_barang' => $kode, 'nama_barang' => $nama, 'expired_date' => $expired, 'qty_lot' => $quantity];
        }
        return ['status' => true, 'data' => $this->mergeLotRows($sourceRows), 'stale' => false];
    }

    private function mergeLotRows(array $sourceRows)
    {
        $unique = [];
        foreach ($sourceRows as $sourceRow) {
            $kode = $sourceRow['kode_barang'];
            $nama = trim(preg_replace('/\s+/u', ' ', str_replace('*', '', $sourceRow['nama_barang'])));
            if ($kode === '' || $nama === '') { continue; }
            $expired = $sourceRow['expired_date'];
            $quantity = $sourceRow['qty_lot'];
            $key = json_encode([strtoupper($nama), $expired]);
            if (!isset($unique[$key])) {
                $unique[$key] = ['nama_barang' => $nama, 'expired_date' => $expired, 'qty_lot' => 0.0, 'codes' => []];
            }
            $codeKey = strtoupper($kode);
            if (!isset($unique[$key]['codes'][$codeKey])) {
                $unique[$key]['codes'][$codeKey] = ['kode_barang' => $kode, 'nama_barang' => $nama, 'qty_lot' => 0.0];
            }
            $unique[$key]['codes'][$codeKey]['qty_lot'] += $quantity;
            $unique[$key]['qty_lot'] += $quantity;
        }
        $rows = [];
        foreach ($unique as $row) {
            if ($row['qty_lot'] > 0) {
                $selected = null;
                foreach ($row['codes'] as $code) {
                    if ($code['qty_lot'] <= 0) { continue; }
                    if ($selected === null || $this->preferCode($code['kode_barang'], $selected['kode_barang'])) {
                        $selected = $code;
                    }
                }
                $rows[] = [
                    'kode_barang' => $selected['kode_barang'],
                    'nama_barang' => $selected['nama_barang'],
                    'expired_date' => $row['expired_date'],
                ];
            }
        }
        usort($rows, function ($a, $b) {
            return [$a['kode_barang'], $a['nama_barang'], $a['expired_date']] <=> [$b['kode_barang'], $b['nama_barang'], $b['expired_date']];
        });
        return $rows;
    }

    private function preferCode($candidate, $current)
    {
        $candidateQ = strtoupper(substr($candidate, 0, 1)) === 'Q';
        $currentQ = strtoupper(substr($current, 0, 1)) === 'Q';
        if ($candidateQ !== $currentQ) {
            return $candidateQ;
        }
        return strcasecmp($candidate, $current) < 0;
    }

    private function fetchDimensions()
    {
        $source = $this->fetchHtml('zahir_master_barang_url');
        if (!$source['status']) {
            return $source;
        }
        $nodes = $source['data']->query('//table[1]/tr[position()>1]');
        if (!$nodes || !$nodes->length) {
            return ['status' => false, 'message' => 'Master barang ZahirDigital tidak ditemukan.'];
        }
        $dimensions = [];
        foreach ($nodes as $node) {
            $cells = $node->getElementsByTagName('td');
            if ($cells->length < 14) {
                continue;
            }
            $kode = strtoupper(trim($cells->item(0)->textContent));
            if ($kode === '') {
                continue;
            }
            $dimensions[$kode] = [
                'p' => (float)trim($cells->item(11)->textContent),
                'l' => (float)trim($cells->item(12)->textContent),
                't' => (float)trim($cells->item(13)->textContent),
            ];
        }
        return ['status' => true, 'data' => $dimensions, 'stale' => false];
    }
}
