<?php
defined('BASEPATH') or exit('No direct script access allowed');

class C_Development extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('M_Development');
    }

    function dashboard_do()
    {
        $data['page_title']     = 'DELIVERY ORDER';
        $data['kdgenerate']     = $this->M_Development->generate_update();
        $data['list_faktur']    = $this->M_Development->get_data_penjualan_zahir();
        $data['updated']        = $this->M_Development->get_updated_data_preparation();
        $data['listdo']         = $this->M_Development->getdo();

        $this->load->view('partial/main/header.php', $data);
        $this->load->view("content/development/body");
        $this->load->view('partial/main/footer.php');
    }
}

    


//  saya mau membuat aplikasi deliver order (DO) , saya membutuhkan module atau fitur yang berguna untuk :

//  1. import data csv dari excel kedalam tabel tb_pre_do dengan format header (tgl_inputer	kd_faktur	kd_rute	kd_customer	kd_barang	qty	satuan	no_lot	tgl_exp	upload_sts	data_sts	barang_sts	create_at)
//  2. isi data import yaitu faktur penjualan , data dapat di kelola untuk menjadi draft faktur delivery
//  3. admin do melakukan pengecekan faktur penjualan customer , cek list data faktur dan konfirmasi pada admin sales 
//  4. apabila terdapat perubahan faktur penjualan admin do melakukan import ulang
//  5. import ulang data faktur penjualan , cek by sistem apabila terdapat perubahan data pada faktur tersebut dan berikan tanda keterangan revisi-sales
//  6. data draft yang telah terkonfirmasi lalu melakukan create faktur delivery order
//  7. admin checker melakukan persiapan barang dan pengecekan barang terjual berdasar kan data faktur delivery order yang telah terkonfirmasi 
//  8. cheker telah menyiapkan barang penjualan
//  9. admin do melakukan penentuan driver , tanggal pengiriman , dan print faktur  

//  database : 
//  - tb_pre_do 
//  tgl_inputer	kd_faktur	kd_rute	kd_customer	kd_barang	qty	satuan	no_lot	tgl_exp	upload_sts	data_sts barang_sts create_at
//  data_sts (status 1 = not in draf , 2= on draft , 3 = terfaktur)

//  -tb_detail_do
//  id  id_pre_do tgl_inputer	kd_faktur	kd_rute	kd_customer	kd_barang	qty	satuan	no_lot	tgl_exp	upload_sts	data_sts barang_sts create_at
 
//  -tb_do
//  kd_do nolambung regional driver tgl_pengiriman status
//  status 1 = belum di check oleh admin checker , 2 = telah di chek

//  saya menggunakan database phpmyadmin dan menggunakan framework codeigniter 3