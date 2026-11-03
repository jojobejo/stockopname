<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Keuangan extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Keuangan');
        $this->load->model('M_Logistik');
        $this->load->model('M_Stockbuffer');
        $this->load->model('M_Bufferstockglobal');
        $this->load->helper(array('form', 'url'));
        $this->load->library('upload');
        $this->load->database();
    }

    public function index()
    {
        $data['page_title']     = 'KARISMA - KEUANGAN';
        $data['count_gudang']   = $this->M_Keuangan->get_data_gdg();
        $data['updated']        = $this->M_Keuangan->get_updated();
        $data['listdo']         = $this->M_Logistik->getdo();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/keuangan/body.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function insertmodule()
    {
        $data['page_title']     = 'KARISMA - KEUANGAN';
        $data['kd']             = $this->M_Keuangan->generate_update();
        $data['updated']        = $this->M_Keuangan->get_updated_upload();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/keuangan/coba1.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function insermodule_lot()
    {
        $data['page_title']     = 'KARISMA - KEUANGAN';
        $data['kd']             = $this->M_Keuangan->generate_update();
        $data['updated']        = $this->M_Keuangan->get_updated_upload();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/keuangan/updt_lot.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function import()
    {
        $session    = $this->session->userdata('jobdesk');

        if ($session == 'ADMINKEU') {
            $file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
            fgetcsv($file_data);

            $data = [];
            while ($row = fgetcsv($file_data)) {
                $data[] = [
                    'kd_suplier'    => $row[0],
                    'kd_barang'     => $row[1],
                    'gudang'        => $row[2],
                    'qty'           => $row[3]
                ];
            }

            $gdgid   = $this->input->post('gdgid');

            if (!empty($data) && $gdgid != '1') {
                $this->update_data();
                $this->M_Keuangan->insert_batch($data);
                $this->session->set_flashdata('message', 'Data imported successfully.');
            } else if (!empty($data) && $gdgid == '1') {
                $this->update_data();
                $this->M_Keuangan->batch_global($data);
                $this->session->set_flashdata('message', 'Data imported successfully.');
            } else {
                $this->session->set_flashdata('message', 'Failed to import data.');
            }
            redirect('insertmodule');
        } elseif ($session == 'LOGISTIK') {
            if (isset($_FILES['csv_file']['name']) && $_FILES['csv_file']['size'] > 0) {
                $file = fopen($_FILES['csv_file']['tmp_name'], 'r');
                $header = fgetcsv($file); // skip header

                $insertCount = 0;
                $revisiCount = 0;

                while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {

                    if (count($row) < 9) {
                        continue;
                    }

                    $kd_faktur = $row[1];
                    $kd_barang = $row[4];
                    $qty       = $row[5];
                    $nolot     = $row[7];
                    $tgl_exp   = $row[8];

                    // Cek apakah data sudah ada
                    $kdupdate = $this->input->post('kdgenerates');
                    $existing = $this->M_Keuangan->get_by_faktur_barang($kd_faktur, $kd_barang, $qty, $nolot, $tgl_exp);

                    $newData = [
                        'kdupdate'    => $kdupdate,
                        'tgl_inputer' => $row[0],
                        'kd_faktur'   => $row[1],
                        'kd_rute'     => $row[2],
                        'kd_customer' => $row[3],
                        'kd_barang'   => $row[4],
                        'nama_barang' => $row[5],
                        'qty'         => $row[6],
                        'satuan'      => $row[7],
                        'no_lot'      => $row[8],
                        'tgl_exp'     => $row[9],
                        'nominal_p'   => $row[10],
                        'jtempo'      => $row[11],
                        'upload_sts'  => 1,
                        'data_sts'    => 1,
                        'barang_sts'  => 1,
                        'create_at'   => date('Y-m-d H:i:s'),
                    ];

                    if ($existing) {

                        unset($existing->kd_faktur);
                        $existing_arr = (array) $existing;

                        $diff = array_diff_assoc($newData, $existing_arr);
                        if (!empty($diff)) {
                            // Ada perbedaan → update
                            $newData['barang_sts'] = 1;
                            $newData['upload_sts'] = 2;

                            $this->M_Keuangan->update_by_faktur($kd_faktur, $kd_barang, $newData);
                            $revisiCount++;
                        }
                    } else {
                        // Belum ada → insert baru
                        $this->M_Keuangan->insert($newData);
                        $insertCount++;
                    }
                }

                fclose($file);
                $this->session->set_flashdata('message', "Import selesai: {$insertCount} data baru, {$revisiCount} direvisi.");
            } else {
                $this->session->set_flashdata('message', 'File tidak valid!');
            }
            redirect('logistik');
        } else {
            $this->session->set_flashdata("SESI BERAKHIR", "Silahkan login lagi ");
            redirect('Auth');
        }
    }

    public function preview_csv()
    {
        if (!isset($_FILES['file_csv']['name']) || $_FILES['file_csv']['size'] <= 0) {
            $this->session->set_flashdata('error', 'Pilih file CSV terlebih dahulu.');
            redirect('pre_do');
        }

        $file = fopen($_FILES['file_csv']['tmp_name'], 'r');
        $header = fgetcsv($file); // skip header

        $data_baru = [];
        $data_duplikat = [];

        while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
            $kd_faktur = trim($row[0]);
            $tgl_faktur = trim($row[1]);
            $nama_customer = trim($row[2]);
            $alamat = trim($row[3]);

            $row_data = [
                'kd_faktur' => $kd_faktur,
                'tgl_faktur' => $tgl_faktur,
                'nama_customer' => $nama_customer,
                'alamat' => $alamat
            ];

            if ($this->Pre_do_model->is_exist($kd_faktur)) {
                $data_duplikat[] = $row_data;
            } else {
                $data_baru[] = $row_data;
            }
        }
        fclose($file);

        $data['data_baru'] = $data_baru;
        $data['data_duplikat'] = $data_duplikat;

        // Simpan data_baru ke session untuk konfirmasi insert
        $this->session->set_userdata('data_baru_csv', $data_baru);

        $this->load->view('pre_do_preview', $data);
    }

    public function insertmodule_pnd()
    {
        $data['page_title']     = 'KARISMA - KEUANGAN';
        $data['kd']             = $this->M_Keuangan->generate_update();
        $data['updated']        = $this->M_Keuangan->get_updated_upload();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/keuangan/update_stock_pnd.php', $data);
        $this->load->view('partial/main/footer.php');
    }
    public function csv_import_po_pnd()
    {
        $session    = $this->session->userdata('jobdesk');

        if ($session == 'ADMINKEU') {
            $file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
            fgetcsv($file_data); // Skip header row

            $data = [];
            while ($row = fgetcsv($file_data)) {
                $data[] = [
                    'nopo'                  => $row[0],
                    'tanggal'               => $row[1],
                    'kd_sup'                => $row[2],
                    'kd_barang'             => $row[3],
                    'qty_order'             => $row[4],
                    'qty_order_success'     => $row[5],
                    'qty_kurang'            => $row[6],
                ];
            }

            $gdgid   = $this->input->post('gdgid');

            if (!empty($data) && $gdgid != '1') {
                $this->update_data();
                $this->M_Keuangan->insert_po_pending($data);
                $this->session->set_flashdata('message', 'Data imported successfully.');
            } else if (!empty($data) && $gdgid == '1') {
                $this->update_data();
                $this->M_Keuangan->batch_global($data);
                $this->session->set_flashdata('message', 'Data imported successfully.');
            } else {
                $this->session->set_flashdata('message', 'Failed to import data.');
            }
            redirect('insertmodule_pnd');
        } else {
            $this->session->set_flashdata("SESI BERAKHIR", "Silahkan login lagi ");
            redirect('Auth');
        }
    }

    public function csv_import_lot()
    {
        $session    = $this->session->userdata('jobdesk');

        if ($session == 'ADMINKEU') {
            $file_data = fopen($_FILES['csv_file']['tmp_name'], 'r');
            fgetcsv($file_data); // Skip header row

            $data = [];
            while ($row = fgetcsv($file_data)) {
                $data[] = [
                    'kd_barang' => $row[0],
                    'nm_barang' => $row[1],
                    'gudang'    => $row[2],
                    'qty'       => $row[3],
                    'unit'      => $row[4],
                    'no_lot'    => $row[5],
                    'exp_date'  => $row[6],
                    'suplier'   => $row[7]
                ];
            }

            $gdgid   = $this->input->post('gdgid');

            if (!empty($data) && $gdgid != '1') {
                $this->update_data();
                $this->M_Keuangan->insert_batch_lot($data);
                $this->session->set_flashdata('message', 'Data imported successfully.');
            } else if (!empty($data) && $gdgid == '1') {
                $this->update_data();
                $this->M_Keuangan->batch_global($data);
                $this->session->set_flashdata('message', 'Data imported successfully.');
            } else {
                $this->session->set_flashdata('message', 'Failed to import data.');
            }
            redirect('insermodule_lot');
        } else {
            $this->session->set_flashdata("SESI BERAKHIR", "Silahkan login lagi ");
            redirect('Auth');
        }
    }

    private function update_data()
    {
        $kd      = $this->input->post('kdgenerates');
        $gdgid   = $this->input->post('gdgid');
        $date    = $this->input->post('dateupload');

        if ($gdgid == 1) {
            $gudang = 'Global';
        } else if ($gdgid == 2) {
            $gudang = 'Gdg. Induk';
        } else if ($gdgid == 3) {
            $gudang = 'Gdg. Rusak';
        } else if ($gdgid == 4) {
            $gudang = 'exp_lot';
        } else if ($gdgid == 5) {
            $gudang = 'pendingpo';
        } else if ($gdgid == 6) {
            $gudang = 'Preparation DO';
        }


        $data  = array(
            'kd_update'     => $kd,
            'gudangid'      => $gdgid,
            'gudang'        => $gudang,
            'last_update'   => $date
        );

        $this->M_Keuangan->insertupdate($data);
    }

    public function truncateitm($kd, $id)
    {
        $this->M_Keuangan->truncateitm($id);
        $this->M_Keuangan->deleteupdateed($kd);

        redirect('keuangan');
    }
    public function deletedata($id)
    {
        $this->M_Keuangan->truncateitm($id);

        redirect('keuangan');
    }

    function get_stock_a($id)
    {
        $list = $this->M_Stockbuffer->get_datatables($id);
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = array();

            $row[] = $field->nmsuplier;
            $row[] = $field->nmbarang;
            $row[] = $field->qty_box;
            $row[] = $field->qty_pcs;

            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_Stockbuffer->count_all($id),
            "recordsFiltered" => $this->M_Stockbuffer->count_filtered($id),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    //  GUDANG KARISMAERP
    public function gudang($id)
    {
        if ($id == '1') {

            $gudangid = $id;
            $gudang = 'Global';
            $data['page_title']     = 'KARISMA - KEUANGAN';
            $data['gudang']         = $gudang;
            $data['gudangid']       = $gudangid;
            $data['updated']        = $this->M_Keuangan->get_last_update($id);

            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/keuangan/gudang.php', $data);
            $this->load->view('partial/main/footergdg.php');
        } else if ($id == '2') {

            $gudangid = $id;
            $gudang = 'Gdg. Induk';
            $data['page_title']     = 'KARISMA - KEUANGAN';
            $data['gudang']         = $gudang;
            $data['gudangid']       = $gudangid;
            $data['updated']        = $this->M_Keuangan->get_last_update($id);

            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/keuangan/gudang.php', $data);
            $this->load->view('partial/main/footergdg.php');
        } else if ($id == '3') {

            $gudangid = $id;
            $gudang = 'Gdg. Rusak';
            $data['page_title']     = 'KARISMA - KEUANGAN';
            $data['gudang']         = $gudang;
            $data['gudangid']       = $gudangid;
            $data['updated']        = $this->M_Keuangan->get_last_update($id);

            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/keuangan/gudang.php', $data);
            $this->load->view('partial/main/footergdg.php');
        } else if ($id == '4') {

            $gudangid = $id;
            $gudang = 'Stock Expired & LOT';
            $data['page_title']     = 'KARISMA - KEUANGAN';
            $data['gudang']         = $gudang;
            $data['gudangid']       = $gudangid;
            $data['updated']        = $this->M_Keuangan->get_last_update($id);

            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/keuangan/gudang.php', $data);
            $this->load->view('partial/main/footergdg.php');
        }
    }

    public function get_data_global()
    {
        $list = $this->M_Bufferstockglobal->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $field) {
            $no++;
            $row = array();

            $row[] = $field->nmsuplier;
            $row[] = $field->nmbarang;
            $row[] = $field->qty_box;
            $row[] = $field->qty_pcs;
            $row[] = '<a href="' . base_url('detail_stock/') . $field->kdbarang . '"class="btn btn-primary" style="align-items: center;"><i class="fas fa-eye"></i></a>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_Bufferstockglobal->count_all(),
            "recordsFiltered" => $this->M_Bufferstockglobal->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function list_stock_minimum($id)
    {
        if ($id == '1') {
            $gudangid = $id;
            $gudang = 'STOCK MINIMUM - Global';
            $data['page_title']     = 'KARISMA - KEUANGAN';
            $data['gudang']         = $gudang;
            $data['gudangid']       = $gudangid;
            $data['updated']        = $this->M_Keuangan->get_last_update($id);
            $data['stock']          = $this->M_Keuangan->get_stock_global();

            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/keuangan/stock_minimum.php', $data);
            $this->load->view('partial/main/footergdg.php');
        } elseif ($id == '2') {
            $gudangid = $id;
            $gudang = 'STOCK MINIMUM - Induk';
            $gdg    = 'Gdg. Induk';
            $data['page_title']     = 'KARISMA - KEUANGAN';
            $data['gudang']         = $gudang;
            $data['gudangid']       = $gudangid;
            $data['gdg']            = $gdg;
            $data['updated']        = $this->M_Keuangan->get_last_update($id);
            $data['stock']          = $this->M_Keuangan->get_stockmin_gdg($gdg);

            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/keuangan/stock_minimum.php', $data);
            $this->load->view('partial/main/footergdg.php');
        } elseif ($id == '3') {
            $gudangid = $id;
            $gudang = 'STOCK MINIMUM - Rusak';
            $gdg    = 'Gdg. Rusak';
            $data['page_title']     = 'KARISMA - KEUANGAN';
            $data['gudang']         = $gudang;
            $data['gudangid']       = $gudangid;
            $data['gdg']            = $gdg;
            $data['updated']        = $this->M_Keuangan->get_last_update($id);
            $data['stock']          = $this->M_Keuangan->get_stockmin_gdg($gdg);

            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/keuangan/stock_minimum.php', $data);
            $this->load->view('partial/main/footergdg.php');
        }
    }

    public function stock_suplier($gdg, $id)
    {
        if ($id == '1') {
            $kd = 'BASFI01';
        } elseif ($id == '2') {
            $kd = 'SYNGE01';
        } elseif ($id == '3') {
            $kd = 'NUFAR01';
        } elseif ($id == '4') {
            $kd = 'BAYER01';
        }

        if ($gdg == '1') {

            $gudangid = $id;
            $suplier = $kd;
            $gudang = 'Global';
            $gudangs = $gdg;

            $data['page_title']     = 'KARISMA - KEUANGAN';
            $data['gudang']         = $gudang;
            $data['gudangs']        = $gudangs;
            $data['gudangid']       = $gudangid;
            $data['suplier']        = $suplier;
            $data['gdg']            = $gdg;
            $data['updated']        = $this->M_Keuangan->get_last_update($id);
            $data['stock_sup']      = $this->M_Keuangan->get_stock_by_sup_global($suplier);

            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/keuangan/stock_by_suplier.php', $data);
            $this->load->view('partial/main/footergdg.php');
        } else if ($gdg == '2') {

            $gudangid = $id;
            $suplier = $kd;
            $gudang = 'Gdg. Induk';
            $gudangs = $gdg;

            $data['page_title']     = 'KARISMA - KEUANGAN';
            $data['gudang']         = $gudang;
            $data['gudangs']        = $gudangs;
            $data['gudangid']       = $gudangid;
            $data['suplier']        = $suplier;
            $data['gdg']            = $gdg;
            $data['updated']        = $this->M_Keuangan->get_last_update($id);
            $data['stock_sup']      = $this->M_Keuangan->get_stock_by_sup($suplier, $gudang);

            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/keuangan/stock_by_suplier.php', $data);
            $this->load->view('partial/main/footergdg.php');
        } else if ($gdg == '3') {

            $gudangid = $id;
            $suplier = $kd;
            $gudang = 'Gdg. Rusak';
            $gudangs = $gdg;

            $data['page_title']     = 'KARISMA - KEUANGAN';
            $data['gudang']         = $gudang;
            $data['gudangs']        = $gudangs;
            $data['gudangid']       = $gudangid;
            $data['suplier']        = $suplier;
            $data['gdg']            = $gdg;
            $data['updated']        = $this->M_Keuangan->get_last_update($id);
            $data['stock_sup']      = $this->M_Keuangan->get_stock_by_sup($suplier, $gudang);

            $this->load->view('partial/main/header.php', $data);
            $this->load->view('content/keuangan/stock_by_suplier.php', $data);
            $this->load->view('partial/main/footergdg.php');
        }
    }

    public function pendingpo()
    {
        $data['page_title']     = 'KARISMA';
        $data['stocklist']      = $this->M_Keuangan->get_list_po_pending();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/keuangan/pendingpo.php', $data);
        $this->load->view('partial/main/footergdg.php');
    }
    public function daily_stock_lot()
    {
        $data['page_title']     = 'KARISMA';
        $data['stocklist']      = $this->M_Keuangan->get_list_stock_lot();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/keuangan/stock_exp_lot.php', $data);
        $this->load->view('partial/main/footergdg.php');
    }

    public function detail_lot($kd)
    {
        $data['page_title']     = 'KARISMA';
        $data['detail_lot']      = $this->M_Keuangan->get_detail_lot($kd);
        $data['barang']      = $this->M_Keuangan->getsuplierlot($kd);

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/keuangan/detail_lot.php', $data);
        $this->load->view('partial/main/footergdg.php');
    }

    public function master_barang()
    {
        $data['page_title']         = 'KARISMA';

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/keuangan/master_barang.php', $data);
        $this->load->view('partial/main/footergdg.php');
        $this->load->view('content/keuangan/ajax/ajax_master_barang.php', $data);
    }

    private function response_json($payload = [], $code = 200)
    {
        $this->output
            ->set_status_header($code)
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }

    private function master_barang_payload()
    {
        return [
            'kode_barang' => trim((string)$this->input->post('kode_barang', true)),
            'nama_barang' => trim((string)$this->input->post('nama_barang', true)),
            'bahan_aktif' => trim((string)$this->input->post('bahan_aktif', true)),
            'satuan'      => trim((string)$this->input->post('satuan', true)),
            'berat'       => (float)$this->input->post('berat', true),
            'kubikasi'    => (float)$this->input->post('kubikasi', true),
            'p'           => (int)$this->input->post('p', true),
            'l'           => (int)$this->input->post('l', true),
            't'           => (int)$this->input->post('t', true),
        ];
    }

    public function master_barang_list()
    {
        $draw   = (int)$this->input->post('draw');
        $start  = (int)$this->input->post('start');
        $length = (int)$this->input->post('length');
        $searchInput = $this->input->post('search');
        $search = '';
        if (is_array($searchInput) && isset($searchInput['value'])) {
            $search = trim((string)$searchInput['value']);
        }

        if ($length <= 0) {
            $length = 10;
        }

        $rows = $this->M_Keuangan->master_barang_all($length, $start, $search);
        $data = [];

        foreach ($rows as $row) {
            $data[] = [
                'kode_barang' => $row->kode_barang,
                'nama_barang' => $row->nama_barang,
                'bahan_aktif' => $row->bahan_aktif,
                'satuan'      => $row->satuan,
                'berat'       => $row->berat,
                'kubikasi'    => $row->kubikasi,
                'dimensi'     => $row->dimensi,
                'aksi'        => '<button type="button" class="btn btn-sm btn-warning btn-edit-master mr-1" data-id="' . (int)$row->id . '"><i class="fas fa-pen"></i></button>
                                  <button type="button" class="btn btn-sm btn-danger btn-delete-master" data-id="' . (int)$row->id . '" data-nama="' . html_escape($row->nama_barang) . '"><i class="fas fa-trash"></i></button>',
            ];
        }

        $this->response_json([
            'draw'            => $draw,
            'recordsTotal'    => $this->M_Keuangan->master_barang_count_all(),
            'recordsFiltered' => $this->M_Keuangan->master_barang_count_filtered($search),
            'data'            => $data,
        ]);
    }

    public function master_barang_detail()
    {
        $id = (int)$this->input->post('id', true);
        if ($id <= 0) {
            return $this->response_json([
                'status' => false,
                'message' => 'ID tidak valid.',
            ], 422);
        }

        $detail = $this->M_Keuangan->master_barang_by_id($id);
        if (!$detail) {
            return $this->response_json([
                'status' => false,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        $this->response_json([
            'status' => true,
            'data' => $detail,
        ]);
    }

    public function master_barang_store()
    {
        $payload = $this->master_barang_payload();
        if ($payload['kode_barang'] === '' || $payload['nama_barang'] === '') {
            return $this->response_json([
                'status' => false,
                'message' => 'Kode barang dan nama barang wajib diisi.',
            ], 422);
        }

        $ok = $this->M_Keuangan->master_barang_store($payload);

        $this->response_json([
            'status' => (bool)$ok,
            'message' => $ok ? 'Data master barang berhasil disimpan.' : 'Gagal menyimpan data.',
        ], $ok ? 200 : 500);
    }

    public function master_barang_update()
    {
        $id = (int)$this->input->post('id', true);
        if ($id <= 0) {
            return $this->response_json([
                'status' => false,
                'message' => 'ID tidak valid.',
            ], 422);
        }

        $payload = $this->master_barang_payload();
        if ($payload['kode_barang'] === '' || $payload['nama_barang'] === '') {
            return $this->response_json([
                'status' => false,
                'message' => 'Kode barang dan nama barang wajib diisi.',
            ], 422);
        }

        $ok = $this->M_Keuangan->master_barang_update($id, $payload);

        $this->response_json([
            'status' => (bool)$ok,
            'message' => $ok ? 'Data master barang berhasil diupdate.' : 'Gagal update data.',
        ], $ok ? 200 : 500);
    }

    public function master_barang_delete()
    {
        $id = (int)$this->input->post('id', true);
        if ($id <= 0) {
            return $this->response_json([
                'status' => false,
                'message' => 'ID tidak valid.',
            ], 422);
        }

        $ok = $this->M_Keuangan->master_barang_delete($id);

        $this->response_json([
            'status' => (bool)$ok,
            'message' => $ok ? 'Data master barang berhasil dihapus.' : 'Gagal menghapus data.',
        ], $ok ? 200 : 500);
    }

    public function master_customer()
    {
        $data['page_title'] = 'KARISMA';

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/keuangan/master_customer.php', $data);
        $this->load->view('partial/main/footergdg.php');
        $this->load->view('content/keuangan/ajax/ajax_master_customer.php', $data);
    }

    private function master_customer_payload()
    {
        return [
            'kd_customer'         => trim((string)$this->input->post('kd_customer', true)),
            'nama_customer'       => trim((string)$this->input->post('nama_customer', true)),
            'nama_kios'           => trim((string)$this->input->post('nama_kios', true)),
            'alamat_kios'         => trim((string)$this->input->post('alamat_kios', true)),
            'telp1'               => trim((string)$this->input->post('telp1', true)),
            'telp2'               => trim((string)$this->input->post('telp2', true)),
            'regional'            => trim((string)$this->input->post('regional', true)),
            'jam_buka_tutup'      => trim((string)$this->input->post('jam_buka_tutup', true)),
            'karakteristik_kios'  => trim((string)$this->input->post('karakteristik_kios', true)),
        ];
    }

    public function master_customer_list()
    {
        $draw   = (int)$this->input->post('draw');
        $start  = (int)$this->input->post('start');
        $length = (int)$this->input->post('length');
        $searchInput = $this->input->post('search');
        $search = '';
        if (is_array($searchInput) && isset($searchInput['value'])) {
            $search = trim((string)$searchInput['value']);
        }

        if ($length <= 0) {
            $length = 10;
        }

        $rows = $this->M_Keuangan->master_customer_all($length, $start, $search);
        $data = [];

        foreach ($rows as $row) {
            $data[] = [
                'kd_customer'        => $row->kd_customer,
                'nama_customer'      => $row->nama_customer,
                'nama_kios'          => $row->nama_kios,
                'alamat_kios'        => $row->alamat_kios,
                'telp1'              => $row->telp1,
                'telp2'              => $row->telp2,
                'regional'           => $row->regional,
                'jam_buka_tutup'     => $row->jam_buka_tutup,
                'karakteristik_kios' => $row->karakteristik_kios,
                'aksi'               => '<button type="button" class="btn btn-sm btn-warning btn-edit-customer mr-1" data-id="' . (int)$row->id . '"><i class="fas fa-pen"></i></button>
                                          <button type="button" class="btn btn-sm btn-danger btn-delete-customer" data-id="' . (int)$row->id . '" data-nama="' . html_escape($row->nama_customer) . '"><i class="fas fa-trash"></i></button>',
            ];
        }

        $this->response_json([
            'draw'            => $draw,
            'recordsTotal'    => $this->M_Keuangan->master_customer_count_all(),
            'recordsFiltered' => $this->M_Keuangan->master_customer_count_filtered($search),
            'data'            => $data,
        ]);
    }

    public function master_customer_detail()
    {
        $id = (int)$this->input->post('id', true);
        if ($id <= 0) {
            return $this->response_json([
                'status' => false,
                'message' => 'ID tidak valid.',
            ], 422);
        }

        $detail = $this->M_Keuangan->master_customer_by_id($id);
        if (!$detail) {
            return $this->response_json([
                'status' => false,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        $this->response_json([
            'status' => true,
            'data' => $detail,
        ]);
    }

    public function master_customer_store()
    {
        $payload = $this->master_customer_payload();
        if ($payload['kd_customer'] === '' || $payload['nama_customer'] === '') {
            return $this->response_json([
                'status' => false,
                'message' => 'Kode customer dan nama customer wajib diisi.',
            ], 422);
        }

        $ok = $this->M_Keuangan->master_customer_store($payload);

        $this->response_json([
            'status' => (bool)$ok,
            'message' => $ok ? 'Data master customer berhasil disimpan.' : 'Gagal menyimpan data.',
        ], $ok ? 200 : 500);
    }

    public function master_customer_update()
    {
        $id = (int)$this->input->post('id', true);
        if ($id <= 0) {
            return $this->response_json([
                'status' => false,
                'message' => 'ID tidak valid.',
            ], 422);
        }

        $payload = $this->master_customer_payload();
        if ($payload['kd_customer'] === '' || $payload['nama_customer'] === '') {
            return $this->response_json([
                'status' => false,
                'message' => 'Kode customer dan nama customer wajib diisi.',
            ], 422);
        }

        $ok = $this->M_Keuangan->master_customer_update($id, $payload);

        $this->response_json([
            'status' => (bool)$ok,
            'message' => $ok ? 'Data master customer berhasil diupdate.' : 'Gagal update data.',
        ], $ok ? 200 : 500);
    }

    public function master_customer_delete()
    {
        $id = (int)$this->input->post('id', true);
        if ($id <= 0) {
            return $this->response_json([
                'status' => false,
                'message' => 'ID tidak valid.',
            ], 422);
        }

        $ok = $this->M_Keuangan->master_customer_delete($id);

        $this->response_json([
            'status' => (bool)$ok,
            'message' => $ok ? 'Data master customer berhasil dihapus.' : 'Gagal menghapus data.',
        ], $ok ? 200 : 500);
    }

    public function pricelist_online()
    {
        $data['page_title']     = 'KARISMA - KEUANGAN';
        $data['pricelist']      = $this->M_Keuangan->get_pricelist();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/keuangan/body_pl.php', $data);
        $this->load->view('partial/main/footer.php');
    }
}
