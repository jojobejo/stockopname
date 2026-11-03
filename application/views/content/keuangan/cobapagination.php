<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Data</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h1>Data Stock</h1>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Nama Suplier</th>
                    <th>Nama Barang</th>
                    <th>Gudang</th>
                    <th>QTY</th>
                    <th>QTY_MIN</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($stocks as $stock) : ?>
                    <tr>
                        <td><?= $stock['nama_suplier']; ?></td>
                        <td><?= $stock['nm_barang']; ?></td>
                        <td><?= $stock['gudang']; ?></td>
                        <td><?= $stock['qty']; ?></td>
                        <td><?= $stock['qty_min']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div>
            <?= $pagination; ?>
        </div>
    </div>
</body>

</html>