<?php foreach ($historydo_all as $do) : ?>
    <tr>
        <td><?= $do->kd_faktur ?></td>
        <td><?= $do->tgl_transaksi ?></td>
        <td><?= $do->nm_kios ?></td>
        <td><?= $do->rute ?></td>
        <td><?= $do->nama_barang ?></td>
        <td><?= $do->exp_date ?></td>
        <td><?= $do->qty ?></td>
        <td><?= $do->qty_box ?></td>
        <td><?= $do->qty_pcs ?></td>
        <td><?= $do->no_lot ?></td>
    </tr>
<?php endforeach; ?>