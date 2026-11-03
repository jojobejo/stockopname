<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {


	function __construct()
    {
        parent::__construct();
		$this->load->model('');
    }

	public function index()
	{
		$this->load->view('welcome_message');
	}
}





// #struktur tabel (tb_pre_do)
// tgl_inputer (tanggal input)
// kd_faktur
// kd_rute (rute driver)
// kd_customer 
// kd_barang
// qty
// satuan (pcs/box)
// no_lot 
// tgl_exp



// saya mau membuat aplikasi deliver order (DO) , saya membutuhkan module atau fitur yang berguna untuk :

// 1. import data csv dari excel kedalam tabel tb_pre_do dengan format header (tgl_inputer	kd_faktur	kd_rute	kd_customer	kd_barang	qty	satuan	no_lot	tgl_exp	upload_sts	data_sts	barang_sts	create_at)
// 2. isi data import yaitu faktur penjualan , data dapat di kelola untuk menjadi draft faktur delivery
// 3. admin do melakukan pengecekan faktur penjualan customer , cek list data faktur dan konfirmasi pada admin sales 
// 4. apabila terdapat perubahan faktur penjualan admin do melakukan import ulang
// 5. import ulang data faktur penjualan , cek by sistem apabila terdapat perubahan data pada faktur tersebut dan berikan tanda keterangan revisi-sales
// 6. data draft yang telah terkonfirmasi lalu melakukan create faktur delivery order
// 7. admin checker melakukan persiapan barang dan pengecekan barang terjual berdasar kan data faktur delivery order yang telah terkonfirmasi 
// 8. cheker telah menyiapkan barang penjualan
// 9. admin do melakukan penentuan driver , tanggal pengiriman , dan print faktur  

// database : 
// - tb_pre_do 
// tgl_inputer	kd_faktur	kd_rute	kd_customer	kd_barang	qty	satuan	no_lot	tgl_exp	upload_sts	data_sts barang_sts create_at
// data_sts (status 1 = not in draf , 2= on draft , 3 = terfaktur)

// -tb_detail_do
// id  id_pre_do tgl_inputer	kd_faktur	kd_rute	kd_customer	kd_barang	qty	satuan	no_lot	tgl_exp	upload_sts	data_sts barang_sts create_at
 
// -tb_do
// kd_do nolambung regional driver tgl_pengiriman status
// status 1 = belum di check oleh admin checker , 2 = telah di chek

// saya menggunakan database phpmyadmin dan menggunakan framework codeigniter 3
