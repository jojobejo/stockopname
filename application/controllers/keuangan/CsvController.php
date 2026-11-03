<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CsvController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper(array('form', 'url'));
        $this->load->library('upload');
        $this->load->database();
    }

    // Tampilkan form upload
    public function index() {
        $this->load->view('upload_csv');
    }

    // Proses file CSV
    public function upload() {
        // Konfigurasi upload file
        $config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'csv';
        $config['max_size'] = 2048; // Maksimal ukuran file dalam KB

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('csv_file')) {
            // Tampilkan error jika upload gagal
            $error = array('error' => $this->upload->display_errors());
            $this->load->view('upload_csv', $error);
        } else {
            // Ambil data file yang diupload
            $fileData = $this->upload->data();
            $filePath = './uploads/' . $fileData['file_name'];

            // Panggil fungsi untuk memproses CSV
            $this->processCSV($filePath);

            // Berikan feedback sukses
            $data['success'] = 'File berhasil diupload dan diproses.';
            $this->load->view('upload_csv', $data);
        }
    }

    // Fungsi untuk memproses file CSV
    private function processCSV($filePath) {
        $handle = fopen($filePath, "r");
        if ($handle !== FALSE) {
            // Lewati baris pertama jika itu header
            fgetcsv($handle);

            // Iterasi baris CSV
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $csvData = array(
                    'nm_suplier' => $data[0],
                    'nm_barang'  => $data[1],
                    'satuan'     => $data[2],
                    'qty'        => $data[3],
                    'dimensi'    => $data[4]
                );

                // Masukkan data ke database
                $this->db->insert('tb_dailystock', $csvData);
            }

            fclose($handle);
        }

        // Hapus file setelah diproses
        unlink($filePath);
    }
}
