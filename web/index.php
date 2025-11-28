<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ngawi Dish .store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>
<body class="bg-light">
    <?php
    require "./database.php";

    // $cari = cariProduk($produk, "A005");
    // $subtotal = hitungSubtotal(3500, 5);
    // $pot = potonganHarga(56500, 5);
    // $diskon = hitungDiskon(56500);
    // $pajak = hitungPajak($diskon);
    // $rupiah = formatRupiah(17500);
    
    // var_dump($cari);
    // var_dump($subtotal);
    // var_dump($pot);
    // var_dump($diskon);
    // var_dump($pajak);
    // echo "<br>";
    // echo "<br>";
    // var_dump($produk);
    // kurangiStok($produk, "A005", 12);
    // var_dump($produk);
    // var_dump($rupiah);
    // echo "<br>";
    // echo "<br>";
    // buatstrukBelanja();
    ?>

<div class="container py-4">


    <h1 class="text-center mb-4 fw-bold">Ngawi Dish .store</h1>
    <form action="struk.php" method="post" class="card shadow p-4">
    <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
        <tr>
            <th>Barang</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Beli</th>
        </tr>
        </thead>
        <?php
        foreach($produk as $p){
           

            echo "
            <tr>
                <td>{$p['nama']}</td>
                <td>{$p['harga']}</td>
                <td>{$p['stok']}</td>
                <td><input type='number' name='beli[{$p['kode']}]' min='0' max='{$p['stok']}' value='0' class='form-control'></td>
            </tr>
            ";
        }
        ?>
    </table>
    </div>
    
    <div class="mt-3">
        <input type="text" name="kasir" value="Mas Gatot" placeholder="kasir" class="form-control form-control-lg mb-3">
    </div>

    <button type="submit" class="btn btn-primary btn-lg w-100"> Kirim </button>
    </form>

    <div class="text-center mt-4">
        <a href="cari.php" target="_blank"><button class="btn btn-success btn-lg">Cari Barang</button></a>
    </div>


</div>
</body>
</html>