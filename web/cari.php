<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari | Ngawi Dish .store</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>
<body class="bg-light">
<div class="container py-4">
    <?php
    require "./database.php";
    $cari = $_GET['cari'] ?? '';
    $hasilPencarian = null;
        // var_dump($cari);
        // var_dump($hasilPencarian);
    if($cari !== ''){
        $hasilPencarian = cariProduk($produk, $cari);
    }
    // var_dump($hasilPencarian);
    ?>
    <h4>
        <!-- sengaja aku pisah halaman cari supaya ngga nge refesh halaman utama biar data nya gak ilang -->
    </h4>
    <div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
        <tr>
            <th>Barang</th>
            <th>Harga</th>
            <th>Stok</th>
        </tr>
        </thead>
        <?php
        if($cari === null || $cari === ''){
        foreach($produk as $p){
           

            echo "
            <tr>
                <td>{$p['nama']}</td>
                <td>{$p['harga']}</td>
                <td>{$p['stok']}</td>
            </tr>
            ";
        }
        }else{
            foreach($produk as $p){
           
            if($p['nama'] === $hasilPencarian){
                echo "
            <tr class='table-warning'>
                <td>{$p['nama']}</td>
                <td>{$p['harga']}</td>
                <td>{$p['stok']}</td>
            </tr>
            ";
            }else{
                echo "
            <tr>
                <td>{$p['nama']}</td>
                <td>{$p['harga']}</td>
                <td>{$p['stok']}</td>
            </tr>
            ";
            }
            
        }
        }
        ?>
    </table>
    </div>

    
    <form action="" method="get" class="card shadow p-4">
        <div class="text-center mt-3">
            <input type="text" name="cari" id="" placeholder="Cari dengan kode" class="form-control form-control-lg mb-3">
            <input type="submit" value="Cari" class="btn btn-success btn-lg">
        </div>
    </form>

</div>
</body>
</html>