<?php
require "./database.php";

$belanjaan = $_POST['beli'] ?? [];
$kasir = $_POST['kasir'];

if($belanjaan === []){
    header("Location: index.php");
    exit;
}

//memastikan beanr benar ada barang yg dibeli walau 1
$total = 0;
foreach($belanjaan as $p => $jumlah){
    $total += $jumlah;
}
if ($total === 0) {
    echo "<script>
        alert('Silahkan Isi barang minimal 1');
        window.location.href = 'index.php';
    </script>";
    exit;
}



// var_dump($belanjaan);
// echo "database";
// var_dump($produk);



// var_dump($produk);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk | Ngawi Dish .store</title>
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
</head>
<!-- 
masih bagusan pakai css ku yg asli wkwk daripada bootstrao untuk halaman yg ini
-->
<body class="bg-light"> 
    <!-- <div class="container py-4">
        <div class="card shadow mx-auto" style="max-width: 520px;">
            <div class="card-body"> -->

                <?php buatstrukBelanja_s($belanjaan, $produk, $kasir);?>

            <!-- </div>
        </div>
    </div> -->
</body>

</html>