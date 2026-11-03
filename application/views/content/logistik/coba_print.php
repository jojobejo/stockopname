<style>
    @page {
        size: 9.5in 11in;
        margin: 0;
    }

    @media print {
        body {
            font-family: "Courier New", monospace;
            font-size: 12pt;
            line-height: 1.2;
            margin: 0;
            white-space: pre;
        }
    }
</style>

<pre>
<?php foreach ($dostatus as $d) : ?>
FAKTUR DELIVERY ORDER
RUTE : <?= $d->regional ?>


<?php foreach ($doprintsts as $print) :
        $tonase = ($print->total_tonase_faktur / 1000);
        $tgl_kirim = $this->input->get('tgl_kirim');
        $driver = $this->input->get('driver');
        $plat = $this->input->get('plat');
?>
Tanggal Kirim : <?= $tgl_kirim ?>

Driver        : <?= $driver ?>

No Lambung    : <?= $plat ?> 

Total Customer: <?= $print->totalfaktur ?> 
Total Barang  : <?= $print->total_barang ?> 
Tonase        : <?= $print->total_tonase_faktur ?> Kg || <?= $tonase ?> Ton
<?php endforeach; ?>


┌──────────────────────┬─────┬─────────────┬────────────┬───┬────────────────────────────┬──────────────────┬─────────┬─────────┐
│ Nama Kios            │Rute │ Kode Faktur │ Tgl Input  │No │ Nama Barang                │ No Lot           │  Besar  │  Kecil  │
├──────────────────────┼─────┼─────────────┼────────────┼───┼────────────────────────────┼──────────────────┼─────────┼─────────┤
<?php
    $rowspan_count = [];
    foreach ($data_list as $row) {
        if (!isset($rowspan_count[$row->kd_faktur])) {
            $rowspan_count[$row->kd_faktur] = 0;
        }
        $rowspan_count[$row->kd_faktur]++;
    }

    $printed_faktur = [];
    $norut_counter = 1;

    foreach ($data_list as $row) :
        $show_faktur_info = !in_array($row->kd_faktur, $printed_faktur);
        if ($show_faktur_info) {
            // separator antar faktur
            if (!empty($printed_faktur)) {
                echo "├──────────────────────┼─────┼─────────────┼────────────┼───┼────────────────────────────┼──────────────────┼─────────┼─────────┤\n";
            }
            $printed_faktur[] = $row->kd_faktur;
            $norut_counter = 1;
        }

        if ($show_faktur_info) {
            $nama_kios   = str_pad(substr($row->nama_kios, 0, 20), 20);
            $rute        = str_pad(substr($row->kd_rute, 0, 5), 5);
            $faktur      = str_pad(substr($row->kd_faktur, 0, 13), 13);
            $tgl         = str_pad(substr($row->tgl_transaksi, 0, 10), 10);
        } else {
            $nama_kios   = str_pad("", 20);
            $rute        = str_pad("", 5);
            $faktur      = str_pad("", 13);
            $tgl         = str_pad("", 10);
        }

        $no         = str_pad($norut_counter++, 3, " ", STR_PAD_LEFT);
        $barang     = str_pad(substr($row->nm_barang, 0, 26), 26);
        $lot        = str_pad(substr($row->no_lot . " - " . $row->tgl_exp, 0, 18), 18);
        $qty_box    = str_pad($row->qty_box, 7, " ", STR_PAD_LEFT);
        $qty_pcs    = str_pad($row->qty_pcs, 7, " ", STR_PAD_LEFT);

       echo "│ " . $nama_kios . " │" . $rute . "│ " . $faktur . " │ " . $tgl .
     " │" . $no . " │ " . $barang . " │ " . $lot . " │ " . $qty_box .
     " │ " . $qty_pcs . " │\n";


    endforeach;
?>
└──────────────────────┴─────┴─────────────┴────────────┴───┴────────────────────────────┴──────────────────┴─────────┴─────────┘

<?php endforeach; ?>
</pre>