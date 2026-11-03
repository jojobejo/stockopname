<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Hrd extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Hrd');
        $this->load->helper('tanggal_helper');
    }

    private function _require_hak_akses(array $allowed)
    {
        $akses = intval($this->session->userdata('lv'));
        if (!$akses || !in_array($akses, $allowed, true)) {
            show_error('Akses tidak diizinkan', 403, 'Forbidden');
        }
    }

    private function _get_current_user_id()
    {
        return $this->session->userdata('id') ?: $this->session->userdata('kode') ?: null;
    }

    private function _is_karyawan_session()
    {
        return strtoupper(trim((string) $this->session->userdata('jobdesk'))) === 'KARYAWAN';
    }

    private function _apply_karyawan_issue_scope(array $filters = array())
    {
        if ($this->_is_karyawan_session()) {
            $filters['created_by'] = $this->_get_current_user_id() ?: 0;
        }

        return $filters;
    }

    private function _get_default_issue_status_id()
    {
        $status = $this->M_Hrd->get_statuses()->row();
        return $status ? intval($status->id) : 1;
    }

    private function _render_mobile($contentView, array $data = array())
    {
        $data['content_view'] = $contentView;
        $this->load->view('partial/mobile/header.php', $data);
        $this->load->view('partial/mobile/content.php', $data);
        $this->load->view('partial/mobile/footer.php', $data);
    }

    private function _upload_issue_files($issue_id)
    {
        $results = [];
        if (empty($_FILES['evidence']['name'])) {
            return $results;
        }

        $uploadPath = FCPATH . 'uploads/issues_lingkungan/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $config = array(
            'upload_path' => $uploadPath,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size' => 5120,
            'encrypt_name' => true,
            'remove_spaces' => true,
        );

        $this->load->library('upload', $config);

        foreach ($_FILES['evidence']['name'] as $index => $fileName) {
            if (empty($fileName)) {
                continue;
            }

            $_FILES['file']['name'] = $fileName;
            $_FILES['file']['type'] = $_FILES['evidence']['type'][$index];
            $_FILES['file']['tmp_name'] = $_FILES['evidence']['tmp_name'][$index];
            $_FILES['file']['error'] = $_FILES['evidence']['error'][$index];
            $_FILES['file']['size'] = $_FILES['evidence']['size'][$index];

            $this->upload->initialize($config);
            if (!$this->upload->do_upload('file')) {
                return array('error' => $this->upload->display_errors('', ''));
            }

            $uploadData = $this->upload->data();
            $filePath = 'uploads/issues_lingkungan/' . $uploadData['file_name'];
            $this->M_Hrd->insert_issue_evidence(array(
                'issue_id' => $issue_id,
                'file_path' => $filePath,
                'file_name' => $uploadData['client_name'],
                'uploaded_at' => date('Y-m-d H:i:s'),
            ));
            $results[] = $filePath;
        }

        return $results;
    }

    public function index()
    {
        $data['page_title'] = 'KARISMA';

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/body.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    // TAMPILAN HALAMAN ADD

    public function hrd_add_tamu()
    {
        $data['page_title'] = 'KARISMA';
        $data['tamu'] = $this->M_Hrd->getalltamulb()->result();
        $data['laporan']    = $this->M_Hrd->get_all_tamu_lb()->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/v_add_tamu.php', $data);
        $this->load->view('partial/main/footer.php');
    }


    // TAMPILAN HALAMAN LAP DISTRIBUSI
    public function lap_distribusi()
    {
        $data['page_title'] = 'KARISMA';

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/lapbody.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/hrd/ajaxhrd.php');
    }

    function get_lap_distribusi()
    {
        $list = $this->M_Hrd->get_datatables();
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $field) {
            $no++;
            $row = array();
            $row[] = $field->tglkeluar;
            $row[] = $field->tglmasuk;
            $row[] = $field->nopol;
            $row[] = $field->nolambung;
            $row[] = $field->namadriver;
            $row[] = $field->namahelper;
            $row[] = $field->tujuan;
            $row[] = $field->jamkeluar;
            $row[] = $field->kmkeluar;
            $row[] = $field->jammasuk;
            $row[] = $field->kmmasuk;
            $row[] = $field->keterangan;
            $row[] =  '<td>
            <a href="' . base_url('edit_lap_distribusi_hrd/' . $field->id . '') . '" class="btn btn-warning btn-sm">
            <i class="fa fa-solid fa-pencil-alt"></i>
            </a>
            </td>
            <a href="' . base_url('hapus_lap_distribusi_hrd/' . $field->id . '') . '" class="btn btn-danger btn-sm">
            <i class="fa fa-solid fa-trash-alt"></i>
            </a>
            </td>';
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_Hrd->count_all(),
            "recordsFiltered" => $this->M_Hrd->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }


    // FUNGSI CRUD
    public function input_lap_distribusi()
    {
        $nopol = $this->input->post('nopol');
        $nolambung = $this->input->post('nolambung');
        $nm_driver = $this->input->post('nm_driver');
        $nm_helper = $this->input->post('nm_helper');
        $tujuan = $this->input->post('tujuan');
        $tgl_keluar = $this->input->post('tgl_keluar');
        $jm_keluar = $this->input->post('jm_keluar');
        $km_keluar = $this->input->post('km_keluar');
        $tgl_masuk = $this->input->post('tgl_masuk');
        $jm_masuk = $this->input->post('jm_masuk');
        $km_masuk = $this->input->post('km_masuk');
        $keterangan = $this->input->post('keterangan');
        $inputer = $this->session->userdata('nama_user');

        $data = array(
            'nopol' => $nopol,
            'nolambung' => $nolambung,
            'namadriver' => $nm_driver,
            'namahelper' => $nm_helper,
            'tujuan' => $tujuan,
            'tglkeluar' => $tgl_keluar,
            'jamkeluar' => $jm_keluar,
            'kmkeluar' => $km_keluar,
            'tglmasuk' => $tgl_masuk,
            'jammasuk' => $jm_masuk,
            'kmmasuk' => $km_masuk,
            'keterangan' => $keterangan,
            'inputer' => $inputer,
        );
        $this->M_Hrd->addlapdistribusihrd($data);
        redirect('hrd_lap_distribusi');
    }

    public function v_edit_lap_distribusi($id)
    {
        $data['page_title'] = 'KARISMA';
        $data['laporan'] = $this->M_Hrd->get_lap_id($id)->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/v_lap_dis.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function v_hapus_lap_distribusi_hrd($id)
    {
        $data['page_title'] = 'KARISMA';
        $data['laporan'] = $this->M_Hrd->get_lap_id($id)->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/v_lap_dis_hapus.php', $data);
        $this->load->view('partial/main/footer.php');
    }

    public function edit_lap_distribusi()
    {
        $id = $this->input->post('id_isi');
        $nopol = $this->input->post('nopol');
        $nolambung = $this->input->post('nolambung');
        $nm_driver = $this->input->post('nm_driver');
        $nm_helper = $this->input->post('nm_helper');
        $tujuan = $this->input->post('tujuan');
        $tgl_keluar = $this->input->post('tgl_keluar');
        $jm_keluar = $this->input->post('jm_keluar');
        $km_keluar = $this->input->post('km_keluar');
        $tgl_masuk = $this->input->post('tgl_masuk');
        $jm_masuk = $this->input->post('jm_masuk');
        $km_masuk = $this->input->post('km_masuk');
        $keterangan = $this->input->post('keterangan');
        $inputer = $this->session->userdata('nama_user');

        $data = array(
            'nopol' => $nopol,
            'nolambung' => $nolambung,
            'namadriver' => $nm_driver,
            'namahelper' => $nm_helper,
            'tujuan' => $tujuan,
            'tglkeluar' => $tgl_keluar,
            'jamkeluar' => $jm_keluar,
            'kmkeluar' => $km_keluar,
            'tglmasuk' => $tgl_masuk,
            'jammasuk' => $jm_masuk,
            'kmmasuk' => $km_masuk,
            'keterangan' => $keterangan,
            'inputer' => $inputer,
        );
        $this->M_Hrd->editlapdistribusihrd($id, $data);
        redirect('hrd_lap_distribusi');
    }
    public function hapus_lap_distribusi_hrd()
    {
        $id     = $this->input->post('id_isi');

        $this->M_Hrd->hapus_lap_distribusi_hrd($id);
        redirect('hrd_lap_distribusi');
    }


    public function lap_tamu()
    {
        $data['page_title'] = 'KARISMA';
        $data['laporan']    = $this->M_Hrd->get_all_tamu()->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/laptamubody.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/hrd/ajaxhrd.php');
    }

    // FUNGSI CRUD

    public function tambah_lap_tamu()
    {

        date_default_timezone_set('Asia/Jakarta');
        $tgl = date("Y-m-d");
        $jm  = date("H:i");
        $nama = $this->input->post('nama');
        $perusahaan = $this->input->post('perusahaan');
        $alamat = $this->input->post('alamat');
        $jumlahpersonil = $this->input->post('jumlahpersonil');
        $tujuan = $this->input->post('tujuan');
        $keterangan = $this->input->post('keterangan');

        $data = array(
            'tanggal' => $tgl,
            'nama' => $nama,
            'perusahaan' => $perusahaan,
            'alamat' => $alamat,
            'jumlahpersonil' => $jumlahpersonil,
            'tujuan' => $tujuan,
            'jammasuk' => $jm,
            'keterangan' => $keterangan,
        );

        $this->M_Hrd->addlaptamuhrd($data);
        redirect('hrd_add_tamu');
    }

    public function konfirm_buku_tamu()
    {
        date_default_timezone_set('Asia/Jakarta');
        $jm  = date("H:i");

        $id = $this->input->post('id');
        $tanggal = $this->input->post('tanggal');
        $nama = $this->input->post('nama');
        $perusahaan = $this->input->post('perusahaan');
        $alamat = $this->input->post('alamat');
        $jumlahpersonil = $this->input->post('jumlahpersonil');
        $tujuan = $this->input->post('tujuan');
        $jammasuk = $this->input->post('jammasuk');
        $keterangan = $this->input->post('keterangan');

        $data = array(
            'tanggal' => $tanggal,
            'nama' => $nama,
            'perusahaan' => $perusahaan,
            'alamat' => $alamat,
            'jumlahpersonil' => $jumlahpersonil,
            'tujuan' => $tujuan,
            'jammasuk' => $jammasuk,
            'jamkeluar' => $jm,
            'keterangan' => $keterangan,
        );

        $this->M_Hrd->konfirmtamulb($data);
        $this->M_Hrd->hapus_lap_tamu_lb($id);
        redirect('hrd_add_tamu');
    }

    public function edit_lap_tamu()
    {
        $id = $this->input->post('id');
        $tanggal = $this->input->post('tanggal');
        $nama = $this->input->post('nama');
        $perusahaan = $this->input->post('perusahaan');
        $alamat = $this->input->post('alamat');
        $jumlahpersonil = $this->input->post('jumlahpersonil');
        $tujuan = $this->input->post('tujuan');
        $jammasuk = $this->input->post('jammasuk');
        $jamkeluar = $this->input->post('jamkeluar');
        $keterangan = $this->input->post('keterangan');

        $data = array(
            'id' => $id,
            'tanggal' => $tanggal,
            'nama' => $nama,
            'perusahaan' => $perusahaan,
            'alamat' => $alamat,
            'jumlahpersonil' => $jumlahpersonil,
            'tujuan' => $tujuan,
            'jammasuk' => $jammasuk,
            'jamkeluar' => $jamkeluar,
            'keterangan' => $keterangan,
        );
        $this->M_Hrd->editlaptamu($id, $data);
        redirect('hrd_lap_tamu');
    }
    public function hapus_lap_tamu_hrd()
    {
        $id     = $this->input->post('id_isi');

        $this->M_Hrd->hapus_lap_tamu_hrd($id);
        redirect('hrd_lap_tamu');
    }



    //karyawan keluar masuk
    public function lap_karykm()
    {
        $data['page_title'] = 'KARISMA';
        $data['laporan']    = $this->M_Hrd->get_all_laporan_karykm()->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/lapkaryawankmbody.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/hrd/ajaxhrd.php');
    }

    // FUNGSI CRUD
    public function tambah_lap_karykm()
    {
        $tanggal = $this->input->post('tanggal');
        $nama = $this->input->post('nama');
        $departemen = $this->input->post('departemen');
        $status = $this->input->post('status');
        $jammasuk = $this->input->post('jammasuk');
        $jamkeluar = $this->input->post('jamkeluar');
        $nopol = $this->input->post('nopol');
        $keterangan = $this->input->post('keterangan');


        $data = array(
            'tanggal' => $tanggal,
            'nama' => $nama,
            'departemen' => $departemen,
            'status' => $status,
            'jammasuk' => $jammasuk,
            'jamkeluar' => $jamkeluar,
            'nopol' => $nopol,
            'keterangan' => $keterangan,

        );
        $this->M_Hrd->addlapkarykm($data);
        redirect('hrd_lap_Karyawan_KM');
    }
    public function edit_lap_karykm()
    {
        $id = $this->input->post('id');
        $tanggal = $this->input->post('tanggal');
        $nama = $this->input->post('nama');
        $departemen = $this->input->post('departemen');
        $status = $this->input->post('status');
        $jammasuk = $this->input->post('jammasuk');
        $jamkeluar = $this->input->post('jamkeluar');
        $nopol = $this->input->post('nopol');
        $keterangan = $this->input->post('keterangan');

        $data = array(
            'id' => $id,
            'tanggal' => $tanggal,
            'nama' => $nama,
            'status' => $status,
            'departemen' => $departemen,
            'jammasuk' => $jammasuk,
            'jamkeluar' => $jamkeluar,
            'nopol' => $nopol,
            'keterangan' => $keterangan,
        );
        $this->M_Hrd->editlapkarykm($id, $data);
        redirect('hrd_lap_Karyawan_KM');
    }
    public function hapus_lap_karykm()
    {
        $id     = $this->input->post('id_isi');

        $this->M_Hrd->hapuslapkarykm($id);
        redirect('hrd_lap_Karyawan_KM');
    }


    //karyawan expedisi
    public function lap_expedisi()
    {
        $data['page_title'] = 'KARISMA';
        $data['laporan']    = $this->M_Hrd->get_all_laporan_expedisi()->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/lapexpedisibody.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/hrd/ajaxhrd.php');
    }

    public function kpi_digital()
    {
        $kduser = $this->session->userdata('kode');
        $data['kpi']    = $this->M_hrd->getkpi($kduser);
    }


    // FUNGSI CRUD
    public function tambah_lap_expedisi()
    {
        $tanggal = $this->input->post('tanggal');
        $jammasuk = $this->input->post('jammasuk');
        $jamkeluar = $this->input->post('jamkeluar');
        $nopol = $this->input->post('nopol');
        $namadriver = $this->input->post('namadriver');
        $notlpndriver = $this->input->post('notlpndriver');
        $perusahaanpengirim = $this->input->post('perusahaanpengirim');
        $namabarang = $this->input->post('namabarang');
        $jumlahbarang = $this->input->post('jumlahbarang');
        $keterangan = $this->input->post('keterangan');


        $data = array(
            'tanggal' => $tanggal,
            'jammasuk' => $jammasuk,
            'jamkeluar' => $jamkeluar,
            'nopol' => $nopol,
            'namadriver' => $namadriver,
            'notlpndriver' => $notlpndriver,
            'perusahaanpengirim' => $perusahaanpengirim,
            'namabarang' => $namabarang,
            'jumlahbarang' => $jumlahbarang,
            'keterangan' => $keterangan,

        );
        $this->M_Hrd->addlapexpedisi($data);
        redirect('hrd_lap_expedisi');
    }
    public function edit_lap_expedisi()
    {
        $id = $this->input->post('id');
        $tanggal = $this->input->post('tanggal');
        $jammasuk = $this->input->post('jammasuk');
        $jamkeluar = $this->input->post('jamkeluar');
        $nopol = $this->input->post('nopol');
        $namadriver = $this->input->post('namadriver');
        $notlpndriver = $this->input->post('notlpndriver');
        $perusahaanpengirim = $this->input->post('perusahaanpengirim');
        $namabarang = $this->input->post('namabarang');
        $jumlahbarang = $this->input->post('jumlahbarang');
        $keterangan = $this->input->post('keterangan');

        $data = array(
            'id' => $id,
            'tanggal' => $tanggal,
            'jammasuk' => $jammasuk,
            'jamkeluar' => $jamkeluar,
            'nopol' => $nopol,
            'namadriver' => $namadriver,
            'notlpndriver' => $notlpndriver,
            'perusahaanpengirim' => $perusahaanpengirim,
            'namabarang' => $namabarang,
            'jumlahbarang' => $jumlahbarang,
            'keterangan' => $keterangan,
        );
        $this->M_Hrd->editlapexpedisi($id, $data);
        redirect('hrd_lap_expedisi');
    }
    public function hapus_lap_expedisi()
    {
        $id     = $this->input->post('id_isi');

        $this->M_Hrd->hapuslapexpedisi($id);
        redirect('hrd_lap_expedisi');
    }


    //laporan issue
    public function lap_issue()
    {
        $data['page_title'] = 'KARISMA';
        $data['laporan']    = $this->M_Hrd->export_lap_issue();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/lapissuebody.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/hrd/ajaxhrd.php');
    }

    // FUNGSI CRUD
    public function tambah_lap_issue()
    {
        $tanggal = $this->input->post('tanggal');
        $issue = $this->input->post('issue');
        $lokasi = $this->input->post('lokasi');
        $nama = $this->input->post('nama');

        $data = array(
            'tanggal' => $tanggal,
            'issue' => $issue,
            'lokasi' => $lokasi,
            'nama' => $nama,
        );
        $this->M_Hrd->addlapissue($data);
        redirect('hrd_lap_issue');
    }
    public function edit_lap_issue()
    {
        $id = $this->input->post('id');
        $tanggal = $this->input->post('tanggal');
        $issue = $this->input->post('issue');
        $lokasi = $this->input->post('lokasi');
        $nama = $this->input->post('nama');

        $data = array(
            'id' => $id,
            'tanggal' => $tanggal,
            'issue' => $issue,
            'lokasi' => $lokasi,
            'nama' => $nama,
        );
        $this->M_Hrd->editlapissue($id, $data);
        redirect('hrd_lap_issue');
    }
    public function hapus_lap_issue()
    {
        $id     = $this->input->post('id_isi');

        $this->M_Inventaris->hapuslapissue($id);
        redirect('hrd_lap_issue');
    }
    public function search_lap_distribusi()
    {
        $vkolom = $this->input->post('sc_bar');
        $vcari  = $this->input->post('nmsearch');
        $_SESSION['varkolom'] = $vkolom;
        $_SESSION['varcari'] = $vcari;

        redirect('v_cari_lap_distribusi');
    }
    public function v_cari_lap_distribusi()
    {
        $data['page_title'] = 'KARISMA';
        $valkolom       = $_SESSION['varkolom'];
        $varcari        = $_SESSION['varcari'];
        $data['vcari']    = $this->M_Hrd->cari_lap_distribusi($valkolom, $varcari)->result();
        $data['laporan']    = $this->M_Hrd->get_all_laporan()->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/v_cari_lap_distribusi.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/hrd/ajaxhrd.php');
    }
    public function hrd_all_karyawan()
    {
        $data['page_title'] = 'HISTORI SERVICE TRUK';
        $data['karyawan']    = $this->M_Hrd->get_all_karyawan()->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/karyawanall.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/hrd/ajaxhrd.php');
    }
    public function hrd_data_truk()
    {
        $data['page_title'] = 'HISTORI SERVICE TRUK';
        $data['truk']    = $this->M_Hrd->get_all_truk_service_histori()->result();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/trukbodyhrd.php', $data);
        $this->load->view('partial/main/footer.php');
        $this->load->view('content/hrd/ajaxhrd.php');
    }
    public function update_km_now_service_truk()
    {
        $id         = $this->input->post('id');
        $kmsekarang = $this->input->post('kmnow');

        $dataupdate = array(
            'km_sekarang' => $kmsekarang
        );
        $this->M_Hrd->update_km_service($id, $dataupdate);
        redirect('hrd_data_truk');
    }
    public function update_km_past_service_truk()
    {
        $id         = $this->input->post('id');
        $kmsekarang = $this->input->post('kmnow');

        $dataupdate = array(
            'km_sebelum' => $kmsekarang
        );
        $this->M_Hrd->update_km_service($id, $dataupdate);
        redirect('hrd_data_truk');
    }

    public function add_karyawan()
    {
        $nik = $this->input->post('nik_isi');
        $nmkaryawan = $this->input->post('nm_isi');
        $departemen = $this->input->post('departemen_i');
        $almt_isi = $this->input->post('alamat_isi');
        $tgl_lahir = $this->input->post('tgl_isi');

        $dataupdate = array(
            'nik'       => $nik,
            'nama_lengkap' => $nmkaryawan,
            'departemen' => $departemen,
            'alamat'    => $almt_isi,
            'tgl_lahir' => $tgl_lahir
        );
        $this->M_Hrd->add_karyawan($dataupdate);
        redirect('hrd_all_karyawan');
    }

    public function edit_karyawan()
    {
        $id         = $this->input->post('id');
        $nik = $this->input->post('nik_isi');
        $nmkaryawan = $this->input->post('nm_isi');
        $departemen = $this->input->post('departemen_i');
        $almt_isi = $this->input->post('alamat_isi');
        $tgl_lahir = $this->input->post('tgl_isi');

        $dataupdate = array(
            'nik'       => $nik,
            'nama_lengkap' => $nmkaryawan,
            'departemen' => $departemen,
            'alamat'    => $almt_isi,
            'tgl_lahir' => $tgl_lahir
        );
        $this->M_Hrd->update_karyawan($id, $dataupdate);
        redirect('hrd_all_karyawan');
    }

    // public function export_laporan_issue()
    // {
    //     include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
    //     $excel = new PHPExcel();
    //     $excel->getProperties()->setCreator('it_karisma')
    //         ->setLastModifiedBy('lap_issue_hrd_')
    //         ->setTitle("Rekap Laporan Issue")
    //         ->setSubject("Rekap Laporan Issue")
    //         ->setDescription("Rekap Laporan Issue")
    //         ->setKeywords("Laporan Issue");

    //     $style_col = array(
    //         'font' => array('bold' => true),
    //         'alignment' => array(
    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    //         ),
    //         'borders' => array(
    //             'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
    //             'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
    //             'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
    //             'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
    //         )
    //     );

    //     $style_row = array(
    //         'alignment' => array(
    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    //         ),
    //         'borders' => array(
    //             'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
    //             'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
    //             'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
    //             'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
    //         )
    //     );

    //     $excel->setActiveSheetIndex(0)->setCellValue('A1', "Rekap Laporan Issue");
    //     $excel->getActiveSheet()->mergeCells('A1:E1');
    //     $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
    //     $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
    //     $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    //     $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
    //     $excel->setActiveSheetIndex(0)->setCellValue('B3', "TANGGAL");
    //     $excel->setActiveSheetIndex(0)->setCellValue('C3', "DESKRIPSI ISU");
    //     $excel->setActiveSheetIndex(0)->setCellValue('D3', "LOKASI");
    //     $excel->setActiveSheetIndex(0)->setCellValue('E3', "NAMA PENEMU");

    //     $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
    //     $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
    //     $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
    //     $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
    //     $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);

    //     $export = $this->M_Hrd->export_lap_issue();

    //     $no = 1;
    //     $numrow = 4;
    //     foreach ($export as $data) {
    //         $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
    //         $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data->tanggal);
    //         $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data->issue);
    //         $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data->lokasi);
    //         $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $data->nama);
    //         $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
    //         $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
    //         $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
    //         $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
    //         $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
    //         $no++;
    //         $numrow++;
    //     }

    //     $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    //     $excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
    //     $excel->getActiveSheet()->getColumnDimension('C')->setWidth(85);
    //     $excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
    //     $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    //     $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
    //     $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    //     $excel->getActiveSheet(0)->setTitle("Rekap Laporan Issue HRD");
    //     $excel->setActiveSheetIndex(0);
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment; filename="laporan_issue_hrd.xlsx"');
    //     header('Cache-Control: max-age=0');


    //     $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    //     $write->save('php://output');
    // }

    // public function export_laporan_karyawan()
    // {
    //     include APPPATH . 'third_party/PHPExcel/PHPExcel.php';
    //     $excel = new PHPExcel();
    //     $excel->getProperties()->setCreator('it_karisma')
    //         ->setLastModifiedBy('lap_km_karyawan_hrd_')
    //         ->setTitle("Rekap Laporan karyawankm")
    //         ->setSubject("Rekap Laporan karyawankm")
    //         ->setDescription("Rekap Laporan karyawankm")
    //         ->setKeywords("karyawankm");

    //     $style_col = array(
    //         'font' => array('bold' => true),
    //         'alignment' => array(
    //             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    //         ),
    //         'borders' => array(
    //             'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
    //             'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
    //             'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
    //             'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
    //         )
    //     );

    //     $style_row = array(
    //         'alignment' => array(
    //             'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
    //         ),
    //         'borders' => array(
    //             'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
    //             'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
    //             'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
    //             'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN)
    //         )
    //     );

    //     $excel->setActiveSheetIndex(0)->setCellValue('A1', "Rekap Laporan Keluar Masuk Karyawan");
    //     $excel->getActiveSheet()->mergeCells('A1:I1');
    //     $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE);
    //     $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
    //     $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    //     $excel->setActiveSheetIndex(0)->setCellValue('A3', "NO");
    //     $excel->setActiveSheetIndex(0)->setCellValue('B3', "TANGGAL");
    //     $excel->setActiveSheetIndex(0)->setCellValue('C3', "NAMA");
    //     $excel->setActiveSheetIndex(0)->setCellValue('D3', "DEPARTEMEN");
    //     $excel->setActiveSheetIndex(0)->setCellValue('E3', "STATUS");
    //     $excel->setActiveSheetIndex(0)->setCellValue('F3', "JAM KELUAR");
    //     $excel->setActiveSheetIndex(0)->setCellValue('G3', "JAM MASUK");
    //     $excel->setActiveSheetIndex(0)->setCellValue('H3', "NOPOL");
    //     $excel->setActiveSheetIndex(0)->setCellValue('I3', "KETERANGAN");

    //     $excel->getActiveSheet()->getStyle('A3')->applyFromArray($style_col);
    //     $excel->getActiveSheet()->getStyle('B3')->applyFromArray($style_col);
    //     $excel->getActiveSheet()->getStyle('C3')->applyFromArray($style_col);
    //     $excel->getActiveSheet()->getStyle('D3')->applyFromArray($style_col);
    //     $excel->getActiveSheet()->getStyle('E3')->applyFromArray($style_col);
    //     $excel->getActiveSheet()->getStyle('F3')->applyFromArray($style_col);
    //     $excel->getActiveSheet()->getStyle('G3')->applyFromArray($style_col);
    //     $excel->getActiveSheet()->getStyle('H3')->applyFromArray($style_col);
    //     $excel->getActiveSheet()->getStyle('I3')->applyFromArray($style_col);

    //     $export = $this->M_Hrd->export_lap_km_karyawan();

    //     $no = 1;
    //     $numrow = 4;
    //     foreach ($export as $data) {
    //         $excel->setActiveSheetIndex(0)->setCellValue('A' . $numrow, $no);
    //         $excel->setActiveSheetIndex(0)->setCellValue('B' . $numrow, $data->tanggal);
    //         $excel->setActiveSheetIndex(0)->setCellValue('C' . $numrow, $data->nama);
    //         $excel->setActiveSheetIndex(0)->setCellValue('D' . $numrow, $data->departemen);
    //         $excel->setActiveSheetIndex(0)->setCellValue('E' . $numrow, $data->status);
    //         $excel->setActiveSheetIndex(0)->setCellValue('F' . $numrow, $data->jamkeluar);
    //         $excel->setActiveSheetIndex(0)->setCellValue('G' . $numrow, $data->jammasuk);
    //         $excel->setActiveSheetIndex(0)->setCellValue('H' . $numrow, $data->nopol);
    //         $excel->setActiveSheetIndex(0)->setCellValue('I' . $numrow, $data->keterangan);
    //         $excel->getActiveSheet()->getStyle('A' . $numrow)->applyFromArray($style_row);
    //         $excel->getActiveSheet()->getStyle('B' . $numrow)->applyFromArray($style_row);
    //         $excel->getActiveSheet()->getStyle('C' . $numrow)->applyFromArray($style_row);
    //         $excel->getActiveSheet()->getStyle('D' . $numrow)->applyFromArray($style_row);
    //         $excel->getActiveSheet()->getStyle('E' . $numrow)->applyFromArray($style_row);
    //         $excel->getActiveSheet()->getStyle('F' . $numrow)->applyFromArray($style_row);
    //         $excel->getActiveSheet()->getStyle('G' . $numrow)->applyFromArray($style_row);
    //         $excel->getActiveSheet()->getStyle('H' . $numrow)->applyFromArray($style_row);
    //         $excel->getActiveSheet()->getStyle('I' . $numrow)->applyFromArray($style_row);
    //         $no++;
    //         $numrow++;
    //     }

    //     $excel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
    //     $excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
    //     $excel->getActiveSheet()->getColumnDimension('C')->setWidth(85);
    //     $excel->getActiveSheet()->getColumnDimension('D')->setWidth(35);
    //     $excel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
    //     $excel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
    //     $excel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
    //     $excel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
    //     $excel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
    //     $excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
    //     $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
    //     $excel->getActiveSheet(0)->setTitle("Laporan Keluar Masuk Karyawan");
    //     $excel->setActiveSheetIndex(0);
    //     header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    //     header('Content-Disposition: attachment; filename="laporan_keluar_masuk_karyawan.xlsx"');
    //     header('Cache-Control: max-age=0');


    //     $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
    //     $write->save('php://output');
    // }

    // SERVERSIDE SYSTEM 

    function get_server_issue()
    {

        $list = $this->M_Hrd->get_datatables();
        $data = array();
        foreach ($list as $field) {
            $row = array();
            $row[] = $field->kode_barang;
            $row[] = $field->nama_barang;
            $row[] = $field->qty_box;
            $row[] = $field->qty_pcs;
            $data[] = $row;
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->M_Inventor->count_all(),
            "recordsFiltered" => $this->M_Inventor->count_filtered(),
            "data" => $data,
        );
        //output dalam format JSON
        echo json_encode($output);
    }

    public function dashboard_penilaian()
    {
        $data['page_title'] = 'KARISMA';
        $data['lokasi'] = $this->M_Hrd->get_locations()->result();
        $data['status'] = $this->M_Hrd->get_statuses()->result();
        $data['rating'] = $this->M_Hrd->get_ratings()->result();
        $data['default_status_id'] = $this->_get_default_issue_status_id();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/penilaian_lingkungan/admin_dashboard.php', $data);
        $this->load->view('content/hrd/penilaian_lingkungan/js');
        $this->load->view('partial/main/footer.php');
    }

    public function penilaian_lingkungan()
    {
        // temporarily disabled access check for penilaian lingkungan
        // $this->_require_hak_akses(array(1, 2));
        $data['page_title'] = 'Penilaian Lingkungan Kantor';
        $data['page_heading'] = 'Input Laporan';
        $data['module_label'] = 'HRD Mobile';
        $data['mobile_active'] = 'transaksi';
        $data['lokasi'] = $this->M_Hrd->get_locations()->result();
        $data['rating'] = $this->M_Hrd->get_ratings()->result();
        $data['created_by'] = $this->session->userdata('username');

        $this->_render_mobile('content/mobile_erp/form_laporan.php', $data);
    }

    public function mobile_erp_dashboard()
    {
        $data['page_title'] = 'Mobile ERP';
        $data['page_heading'] = 'Dashboard';
        $data['module_label'] = 'Karisma ERP';
        $data['mobile_active'] = 'home';

        $filters = $this->_apply_karyawan_issue_scope();
        $data['stats'] = $this->M_Hrd->get_issue_operational_summary($filters);

        $this->_render_mobile('content/mobile_erp/dashboard.php', $data);
    }

    public function mobile_erp_list()
    {
        $data['page_title'] = 'List Laporan';
        $data['page_heading'] = 'Laporan';
        $data['module_label'] = 'Karisma ERP';
        $data['mobile_active'] = 'laporan';

        $this->_render_mobile('content/mobile_erp/list_data.php', $data);
    }

    public function mobile_erp_detail($id = 0)
    {
        $data['page_title'] = 'Detail Laporan';
        $data['page_heading'] = 'Detail Data';
        $data['module_label'] = 'Karisma ERP';
        $data['mobile_active'] = 'laporan';
        $data['issue_id'] = $id;

        $this->_render_mobile('content/mobile_erp/detail_data.php', $data);
    }

    public function mobile_erp_profile()
    {
        $data['page_title'] = 'Profile User';
        $data['page_heading'] = 'Profile';
        $data['module_label'] = 'Karisma ERP';
        $data['mobile_active'] = 'profile';
        $data['profile_stats'] = $this->M_Hrd->get_issue_operational_summary($this->_apply_karyawan_issue_scope());

        $this->_render_mobile('content/mobile_erp/profile.php', $data);
    }

    public function penilaian_lingkungan_admin()
    {
        // temporarily disabled access check for admin GA dashboard
        // $this->_require_hak_akses(array(1));
        $data['page_title'] = 'Admin GA - Penilaian Lingkungan';
        $data['lokasi'] = $this->M_Hrd->get_locations()->result();
        $data['status'] = $this->M_Hrd->get_statuses()->result();
        $data['rating'] = $this->M_Hrd->get_ratings()->result();
        $data['default_status_id'] = $this->_get_default_issue_status_id();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/penilaian_lingkungan/admin_dashboard.php', $data);
        $this->load->view('content/hrd/penilaian_lingkungan/js');
        $this->load->view('partial/main/footer.php');
    }

    public function penilaian_lingkungan_monitoring()
    {
        // temporarily disabled access check for monitoring
        // $this->_require_hak_akses(array(1, 3));
        $data['page_title'] = 'Monitoring Direksi - Penilaian Lingkungan';
        $data['status'] = $this->M_Hrd->get_statuses()->result();
        $data['ratings'] = $this->M_Hrd->get_ratings()->result();
        $data['default_status_id'] = $this->_get_default_issue_status_id();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view('content/hrd/penilaian_lingkungan/monitoring.php', $data);
        $this->load->view('content/hrd/penilaian_lingkungan/js');
        $this->load->view('partial/main/footer.php');
    }

    public function submit_environment_issue()
    {
        // temporarily disabled access check for submit_environment_issue
        // $this->_require_hak_akses(array(1, 2));
        $this->output->set_content_type('application/json');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('location_id', 'Lokasi', 'required');
        $this->form_validation->set_rules('description', 'Deskripsi', 'required');

        if ($this->form_validation->run() === false) {
            echo json_encode(array('status' => false, 'message' => validation_errors('', '')));
            return;
        }

        if (empty($_FILES['evidence']['name'][0])) {
            echo json_encode(array('status' => false, 'message' => 'Silakan upload minimal satu bukti foto.'));
            return;
        }

        $created_by = $this->_get_current_user_id();
        $defaultStatusId = $this->_get_default_issue_status_id();
        $issueData = array(
            'location_id' => $this->input->post('location_id'),
            'rating_id' => $this->input->post('rating_id') !== null ? $this->input->post('rating_id') : 0,
            'description' => $this->input->post('description'),
            'report_datetime' => date('Y-m-d H:i:s'),
            'status_id' => $defaultStatusId,
            'created_by' => $created_by,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        );

        if (!$this->M_Hrd->insert_environment_issue($issueData)) {
            echo json_encode(array('status' => false, 'message' => 'Gagal menyimpan issue.'));
            return;
        }

        $issueId = $this->db->insert_id();
        $uploadResult = $this->_upload_issue_files($issueId);
        if (isset($uploadResult['error'])) {
            echo json_encode(array('status' => false, 'message' => 'Upload file gagal: ' . $uploadResult['error']));
            return;
        }

        $this->M_Hrd->insert_issue_log(array(
            'issue_id' => $issueId,
            'status_id' => $defaultStatusId,
            'note' => 'Issue dibuat dan menunggu ditangani.',
            'changed_by' => $created_by,
            'changed_at' => date('Y-m-d H:i:s'),
        ));

        echo json_encode(array('status' => true, 'message' => 'Issue berhasil dikirim.'));
    }

    public function get_environment_issue_list()
    {
        // temporarily disabled access check for get_environment_issue_list
        // $this->_require_hak_akses(array(1, 3));
        $this->output->set_content_type('application/json');
        $filters = array(
            'location_id' => $this->input->get_post('location_id'),
            'status_id' => $this->input->get_post('status_id'),
            'rating_id' => $this->input->get_post('rating_id'),
            'date_from' => $this->input->get_post('date_from'),
            'date_to' => $this->input->get_post('date_to'),
        );
        $filters = $this->_apply_karyawan_issue_scope($filters);
        $issues = $this->M_Hrd->get_issue_list($filters)->result();
        echo json_encode(array('data' => $issues));
    }

    public function get_environment_issue_detail($id)
    {
        // temporarily disabled access check for get_environment_issue_detail
        // $this->_require_hak_akses(array(1, 3));
        $this->output->set_content_type('application/json');
        $issue = $this->M_Hrd->get_issue_by_id($id);
        if (!$issue) {
            echo json_encode(array('status' => false, 'message' => 'Issue tidak ditemukan.'));
            return;
        }
        if ($this->_is_karyawan_session() && intval($issue->created_by) !== intval($this->_get_current_user_id())) {
            echo json_encode(array('status' => false, 'message' => 'Laporan tidak tersedia untuk user ini.'));
            return;
        }
        $evidence = $this->M_Hrd->get_issue_evidences($id);
        $logs = $this->M_Hrd->get_issue_logs($id);

        echo json_encode(array('status' => true, 'issue' => $issue, 'evidence' => $evidence, 'logs' => $logs));
    }

    public function update_environment_issue()
    {
        // temporarily disabled access check for update_environment_issue
        // $this->_require_hak_akses(array(1));
        $this->output->set_content_type('application/json');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('issue_id', 'Issue ID', 'required');
        $this->form_validation->set_rules('status_id', 'Status', 'required');

        if ($this->form_validation->run() === false) {
            echo json_encode(array('status' => false, 'message' => validation_errors('', '')));
            return;
        }

        $issueId = $this->input->post('issue_id');
        $updatedData = array(
            'status_id' => $this->input->post('status_id'),
            'due_date' => $this->input->post('due_date') ?: null,
            'updated_at' => date('Y-m-d H:i:s'),
        );

        if ($this->input->post('rating_id') !== null && $this->input->post('rating_id') !== '') {
            $updatedData['rating_id'] = $this->input->post('rating_id');
        }

        if (!$this->M_Hrd->update_environment_issue($issueId, $updatedData)) {
            echo json_encode(array('status' => false, 'message' => 'Gagal memperbarui issue.'));
            return;
        }

        $changedBy = $this->_get_current_user_id();
        $this->M_Hrd->insert_issue_log(array(
            'issue_id' => $issueId,
            'status_id' => $this->input->post('status_id'),
            'note' => $this->input->post('note') ?: 'Status diperbarui.',
            'changed_by' => $changedBy,
            'changed_at' => date('Y-m-d H:i:s'),
        ));

        $uploadResult = $this->_upload_issue_files($issueId);
        if (isset($uploadResult['error'])) {
            echo json_encode(array('status' => false, 'message' => 'Perubahan berhasil, tetapi upload tambahan gagal: ' . $uploadResult['error']));
            return;
        }

        echo json_encode(array('status' => true, 'message' => 'Issue berhasil diperbarui.'));
    }

    public function get_environment_issue_stats()
    {
        // temporarily disabled access check for get_environment_issue_stats
        // $this->_require_hak_akses(array(1, 3));
        $this->output->set_content_type('application/json');
        $filters = array(
            'location_id' => $this->input->get('location_id'),
            'status_id' => $this->input->get('status_id'),
            'rating_id' => $this->input->get('rating_id'),
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to'),
        );
        $byLocation = $this->M_Hrd->get_issue_counts_by_location($filters)->result();
        $byRating = $this->M_Hrd->get_issue_counts_by_rating($filters)->result();
        $statusRows = $this->M_Hrd->get_issue_counts_by_status($filters)->result();

        $openCount = 0;
        $resolvedCount = 0;
        $pendingCount = 0;
        $inProgressCount = 0;
        foreach ($statusRows as $statusRow) {
            $name = strtolower($statusRow->status_name ?: 'belum diproses');
            $isResolved = strpos($name, 'selesai') !== false || strpos($name, 'done') !== false || strpos($name, 'closed') !== false || strpos($name, 'resolved') !== false;
            $isProgress = strpos($name, 'progress') !== false || strpos($name, 'proses') !== false || strpos($name, 'sedang') !== false;
            $isPending = strpos($name, 'pending') !== false || strpos($name, 'menunggu') !== false;

            if ($isResolved) {
                $resolvedCount += $statusRow->total;
            } elseif ($isProgress) {
                $inProgressCount += $statusRow->total;
            } elseif ($isPending) {
                $pendingCount += $statusRow->total;
            } else {
                $openCount += $statusRow->total;
            }
        }

        echo json_encode(array(
            'status' => true,
            'location_count' => count($byLocation),
            'open_count' => $openCount,
            'pending_count' => $pendingCount,
            'in_progress_count' => $inProgressCount,
            'resolved_count' => $resolvedCount,
            'by_location' => $byLocation,
            'by_rating' => $byRating,
        ));
    }

    public function get_environment_issue_breakdown()
    {
        $this->output->set_content_type('application/json');

        $type = strtolower(trim($this->input->get('type')));
        $id = intval($this->input->get('id'));

        if (!in_array($type, array('location', 'rating')) || $id <= 0) {
            echo json_encode(array('status' => false, 'message' => 'Parameter detail tidak valid.'));
            return;
        }

        $filters = array(
            'location_id' => $this->input->get('location_id'),
            'status_id' => $this->input->get('status_id'),
            'rating_id' => $this->input->get('rating_id'),
            'date_from' => $this->input->get('date_from'),
            'date_to' => $this->input->get('date_to'),
        );

        if ($type === 'location') {
            $filters['location_id'] = $id;
        } else {
            $filters['rating_id'] = $id;
        }

        $issues = $this->M_Hrd->get_issue_list($filters)->result();
        $summary = $this->M_Hrd->get_issue_breakdown_summary($filters);

        $title = 'Detail Issue';
        if (!empty($issues)) {
            if ($type === 'location') {
                $title = 'Lokasi: ' . $issues[0]->location_name;
            } else {
                $title = 'Prioritas: ' . $issues[0]->rating_name . ' (' . $issues[0]->score . ')';
            }
        }

        echo json_encode(array(
            'status' => true,
            'title' => $title,
            'type' => $type,
            'summary' => $summary,
            'data' => $issues,
        ));
    }

    public function get_hrd_locations()
    {
        $this->output->set_content_type('application/json');
        $locations = $this->M_Hrd->get_all_locations()->result();
        echo json_encode(array('status' => true, 'data' => $locations));
    }

    public function save_hrd_location()
    {
        $this->output->set_content_type('application/json');
        $name = trim($this->input->post('name'));
        $isActive = $this->input->post('is_active') !== null ? intval($this->input->post('is_active')) : 1;
        $id = $this->input->post('id');

        if ($name === '') {
            echo json_encode(array('status' => false, 'message' => 'Nama lokasi tidak boleh kosong.'));
            return;
        }

        $data = array(
            'name' => $name,
            'is_active' => $isActive,
        );

        if (!empty($id)) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        if (!$this->M_Hrd->save_location(array_merge($data, array('id' => $id)))) {
            echo json_encode(array('status' => false, 'message' => 'Gagal menyimpan lokasi.'));
            return;
        }

        echo json_encode(array('status' => true, 'message' => 'Lokasi berhasil disimpan.'));
    }

    public function delete_hrd_location()
    {
        $this->output->set_content_type('application/json');
        $id = $this->input->post('id');
        if (empty($id)) {
            echo json_encode(array('status' => false, 'message' => 'ID lokasi tidak valid.'));
            return;
        }

        if (!$this->M_Hrd->delete_location($id)) {
            echo json_encode(array('status' => false, 'message' => 'Gagal menghapus lokasi.'));
            return;
        }

        echo json_encode(array('status' => true, 'message' => 'Lokasi berhasil dihapus.'));
    }

    public function get_hrd_ratings()
    {
        $this->output->set_content_type('application/json');
        $ratings = $this->M_Hrd->get_all_ratings()->result();
        echo json_encode(array('status' => true, 'data' => $ratings));
    }

    public function save_hrd_rating()
    {
        $this->output->set_content_type('application/json');
        $name = trim($this->input->post('name'));
        $score = $this->input->post('score');
        $id = $this->input->post('id');

        if ($name === '') {
            echo json_encode(array('status' => false, 'message' => 'Nama rating tidak boleh kosong.'));
            return;
        }
        if (!is_numeric($score)) {
            echo json_encode(array('status' => false, 'message' => 'Skor rating tidak valid.'));
            return;
        }

        $data = array(
            'name' => $name,
            'score' => intval($score),
        );

        if (!empty($id)) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        if (!$this->M_Hrd->save_rating(array_merge($data, array('id' => $id)))) {
            echo json_encode(array('status' => false, 'message' => 'Gagal menyimpan rating.'));
            return;
        }

        echo json_encode(array('status' => true, 'message' => 'Rating berhasil disimpan.'));
    }

    public function delete_hrd_rating()
    {
        $this->output->set_content_type('application/json');
        $id = $this->input->post('id');
        if (empty($id)) {
            echo json_encode(array('status' => false, 'message' => 'ID rating tidak valid.'));
            return;
        }

        if (!$this->M_Hrd->delete_rating($id)) {
            echo json_encode(array('status' => false, 'message' => 'Gagal menghapus rating.'));
            return;
        }

        echo json_encode(array('status' => true, 'message' => 'Rating berhasil dihapus.'));
    }
}
