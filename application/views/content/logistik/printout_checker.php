<style>
    @media print {
        .table thead th {
            background-color: rgb(0, 0, 0) !important;
            color: #fff !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }

    .footer-highlight {
        background-color: black !important;
        color: white;
        font-weight: bold;
        text-align: center;
    }

    .footer-black {
        background-color: black !important;
        color: white;
        text-align: center;
    }


    .badge-tempo {
        display: inline-block;
        padding: 6px 12px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 6px;
        min-width: 70px;
        text-align: center;
    }

    .badge-cash {
        background-color: #007bff;
        color: #fff;
    }

    .badge-30 {
        background-color: #ffc107;
        color: #000;
    }

    .badge-other {
        background-color: #28a745;
        color: #fff;
    }


    table {
        font-size: 14px;
        white-space: nowrap;
    }

    th,
    td {
        vertical-align: middle;
        text-align: center;
    }

    .table thead th {
        background-color: rgb(0, 0, 0);
        color: #fff !important;
    }

    .header-title {
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        text-transform: uppercase;
    }

    .info-faktur {
        margin-bottom: 20px;
        font-size: 16px;
    }

    .info-faktur div {
        margin-bottom: 5px;
    }

    .wrap-text {
        white-space: normal !important;
        text-align: left;
    }

    .wrap-text-left {
        text-align: left !important;
    }

    td[rowspan] {
        vertical-align: middle !important;
        text-align: center !important;
    }
</style>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper m-5">
        <div class="header-title">LIST BARANG CHECKER</div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>No Lot</th>
                    <th>Tgl Expire</th>
                    <th>Qty</th>
                    <th>Box</th>
                    <th>Pcs</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grouped = [];
                foreach ($data_list as $row) {
                    $key = $row->nama_barang;
                    $grouped[$key][] = $row;
                }

                $no = 1;
                foreach ($grouped as $nama_barang => $items) :
                    $rowspan = count($items);
                    $first = true;
                    foreach ($items as $item) :
                ?>
                        <tr>
                            <?php if ($first) : ?>
                                <td rowspan="<?= $rowspan ?>"><?= $no++ ?></td>
                                <td rowspan="<?= $rowspan ?>"><?= $nama_barang ?></td>
                            <?php endif; ?>
                            <td><?= $item->no_lot ?></td>
                            <td><?= date('j/n/Y', strtotime($item->tgl_exp)) ?></td>
                            <td><?= $item->qty ?></td>
                            <td><?= $item->qty_box ?></td>
                            <td><?= $item->qty_pcs ?></td>
                        </tr>
                <?php
                        $first = false;
                    endforeach;
                endforeach;
                ?>
            </tbody>
        </table>

    </div>
</body>