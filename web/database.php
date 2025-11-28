<?php
   $produk = [
    ["kode" => "A001", "nama" => "Indomie Goreng", "harga" => 3500, "stok" => 100],
    ["kode" => "A002", "nama" => "Teh Botol Sosro", "harga" => 4000, "stok" => 50],
    ["kode" => "A003", "nama" => "Susu Ultra Milk", "harga" => 12000, "stok" => 30],
    ["kode" => "A004", "nama" => "Roti Tawar Sari Roti", "harga" => 15000, "stok" => 20],
    ["kode" => "A005", "nama" => "Minyak Goreng Bimoli 1L", "harga" => 18000, "stok" => 15]
    ]; 

    // $produk = json_decode(file_get_contents("database.json"), true);
    

// cari produk berdasarkan kode
function cariProduk($array, $kode){
    foreach($array as $p){
        if($p['kode'] === $kode){
            return $p['nama'];
        }
    }
    return "Kode barang tidak ditemukan";
}

//hitung subTotal / total harga per item
function hitungSubtotal($harga, $jumlah){
    return $harga * $jumlah;
}

//ngitung potongan harga baik untuk potongan harga karena diskon atau potongan harga karena kena pajak
function potonganHarga($harga, $persentase){
    return ($harga * $persentase / 100);
}

//ngitung diskon
function hitungDiskon($total){
    $hargaAkhir = 0;
    if($total >= 100000){
        $pot = potonganHarga($total, 10);
        $hargaAkhir = $total - $pot;
        return [$hargaAkhir, "Diskon (10%) = " . formatRupiah($pot)];
    }else if($total >= 50000 && $total < 100000){
        $pot = potonganHarga($total, 5);
        $hargaAkhir = $total - $pot;
        return [$hargaAkhir, "Diskon (5%) = " . formatRupiah($pot)];
    }else{
        $hargaAkhir = $total;
        return [$hargaAkhir, "Tidak Ada diskon"];
    }
}

//hitung pajak
function hitungPajak($totalBelanjaan, $persen = 11){
    return potonganHarga($totalBelanjaan, $persen);
}

//untuk mengurangi stok
function kurangiStok(&$produk, $kode, $jumlah){
    foreach($produk as &$p){
        if($p['kode'] === $kode && $jumlah <= $p['stok']){
            $p['stok'] -= $jumlah;
        }
    }
    unset($p);
    // file_put_contents("database.json", json_encode($produk, JSON_PRETTY_PRINT));
}

//untuk membuat format rupiah
function formatRupiah($angka){
    return "Rp" . number_format($angka, 0, ',', '.');
}

//buat nampilin Stoktersedia per item
function stokTersedia($produk, $item){
    foreach($produk as $p){
        if($p['kode'] === $item){
            return [$p['stok'], $p['nama']];
        }
    }
}

//buat struk belanja
function buatstrukBelanja($transaksi, &$database, $kasir){ //&$database harus pakai pass by refrence karena di dalamnya ada fungsi kurangiStok() 
// yang perlu pass by refrence ke $produk. Kalau $database tidak pakai pass by refrence(&) maka kurangiStok() 
// paramternya akan nangkap refence ke variabel $database bukan $produk(variabel global untuk data barang), 
// jadi supaya aman $database pakai &$database supaya dia nge refrence langsung ke $produk lalu selanjutnya $database yang ada di kurangiStok($database, $t, $j);
// itu ngerefrence ke $produk secara langsung
    echo "======================================================<br>";
    echo "Ngawi Dish .store<br>";
    echo "======================================================<br>";
    date_default_timezone_set("Asia/Jakarta");
    $waktu = date("l, d m Y - H:i:s");
    echo $waktu . "<br><br><br><br>";

    //buat logicnya
    $total = 0;
    foreach($transaksi as $p => $jumlah){
        if($jumlah > 0){
            foreach($database as $db){
                if($p === $db['kode']){
                    $total += $db['harga'] * $jumlah; //ngitung total seluruh belanjaan *belum kena pajak dan diskon
    
                    $subTotal = hitungSubtotal($db['harga'], $jumlah);
                    $subTotalFormat = formatRupiah($subTotal);
                    $hargaFormat = formatRupiah($db['harga']);
                    
                    echo "{$db['nama']}<br>";
                    echo "{$hargaFormat} x {$jumlah} = {$subTotalFormat}<br><br><br>";

                }
            }
        }
    }
    //ngitung diskon dan pajak
    echo "------------------------------------------------------<br>";
    
    $totalFormat = formatRupiah($total);
    $diskon = hitungDiskon($total); //contoh isinya nanti [51200, "Diskon (5%) = Rp48.640"]
    $diskonHarga = $diskon[0];
    $diskonHargaFormat = formatRupiah($diskon[0]);
    $diskonStatus = $diskon[1];

    echo "Subtotal = {$totalFormat}<br>";
    echo $diskonStatus . "<br>";
    echo "Subtotal setelah diskon = " . $diskonHargaFormat . "<br>";

    $pajak = hitungPajak($diskonHarga);
    $pajakFormat = formatRupiah($pajak);

    echo "PPN (11%) = {$pajakFormat}<br>";
    echo "------------------------------------------------------<br>";

    $totalPembayaranAkhir = formatRupiah($pajak + $diskonHarga);
    echo "TOTAL BAYAR = {$totalPembayaranAkhir}<br>";
    echo "KASIR = {$kasir}<br>";
    echo "======================================================<br>";
    echo "<br><br><br><br>";

    //mengurangi stok
    foreach($transaksi as $t => $j){
        kurangiStok($database, $t, $j); //Ini nguranginnya per item
    }

    //nampilin stok yang tersedia
    echo "Status Stok Setelah Transaksi:<br>";
    foreach($transaksi as $t => $j){ // => $j harus ditulis biar yang masuk ke dalam stokTersedia($database, $t); si $t(A001, A003) itu adalah key. kalau => $j nya ilang yang masuk adalah $j alias si valuenya (2, 3, 5)
        //if($j>0) mencegah array $transaksi yang $j nya 0(gak dibeli) gak ikut
        //masuk logic agar mencegah stokTersedia mencetak item yg tidak perlu
        if($j > 0){
            $stok = stokTersedia($database, $t);
            $stokReady = $stok[0];
            $stokNama = $stok[1];

            echo "- {$stokNama}: {$stokReady} pcs<br>";
        }
        
    }

    //Terima kasih
    echo "======================================================<br>";
    echo "Terima kasih atas kunjungan Anda<br>";
    echo "======================================================<br>";
}


//buat struk belanja yang ada stylenya
function buatstrukBelanja_s($transaksi, &$database, $kasir){ //&$database harus pakai pass by refrence karena di dalamnya ada fungsi kurangiStok() 
// yang perlu pass by refrence ke $produk. Kalau $database tidak pakai pass by refrence(&) maka kurangiStok() 
// paramternya akan nangkap refence ke variabel $database bukan $produk(variabel global untuk data barang), 
// jadi supaya aman $database pakai &$database supaya dia nge refrence langsung ke $produk lalu selanjutnya $database yang ada di kurangiStok($database, $t, $j);
// itu ngerefrence ke $produk secara langsung
    echo "<div class='_container-struk'>";

    echo "<div class='_header-struk'>";
    // echo "======================================================<br>";
    echo "<h2>Ngawi Dish .store</h2>";
    // echo "======================================================<br>";
    date_default_timezone_set("Asia/Jakarta");
    $waktu = date("l, d m Y - H:i:s");
    echo "<h3>" . $waktu . "</h3>";
    echo "</div>";

    //buat logicnya
    $total = 0;
    echo "<div class='_items-struk'>";
    echo "<ol>";
    foreach($transaksi as $p => $jumlah){
        if($jumlah > 0){
            foreach($database as $db){
                if($p === $db['kode']){
                    $total += $db['harga'] * $jumlah; //ngitung total seluruh belanjaan *belum kena pajak dan diskon
    
                    $subTotal = hitungSubtotal($db['harga'], $jumlah);
                    $subTotalFormat = formatRupiah($subTotal);
                    $hargaFormat = formatRupiah($db['harga']);
                    
                    echo "<li><h4>{$db['nama']}</h4>";
                    echo "<h4>{$hargaFormat} x {$jumlah} = {$subTotalFormat}</h4></li>";
                    
                    
                }
            }
        }
    }
    echo "</ol>";
    echo "</div>";
    //ngitung diskon dan pajak
    echo "<div class='_payment-struk'>";
    // echo "------------------------------------------------------<br>";
    
    $totalFormat = formatRupiah($total);
    $diskon = hitungDiskon($total); //contoh isinya nanti [51200, "Diskon (5%) = Rp48.640"]
    $diskonHarga = $diskon[0];
    $diskonHargaFormat = formatRupiah($diskon[0]);
    $diskonStatus = $diskon[1];

    echo "<h4>Subtotal = {$totalFormat}</h4>";
    echo "<h4>" . $diskonStatus . "</h4>";
    echo "<h4>Subtotal setelah diskon = " . $diskonHargaFormat . "</h4>";

    $pajak = hitungPajak($diskonHarga);
    $pajakFormat = formatRupiah($pajak);

    echo "<h4>PPN (11%) = {$pajakFormat}</h4>";
    // echo "------------------------------------------------------<br>";

    $totalPembayaranAkhir = formatRupiah($pajak + $diskonHarga);
    echo "<h4><span>TOTAL BAYAR = {$totalPembayaranAkhir}</span></h4>";
    echo "<h4><span>KASIR = {$kasir}</span></h4>";
    // echo "======================================================<br>";
    echo "</div>";
    // echo "<br><br><br><br>";

    //mengurangi stok
    foreach($transaksi as $t => $j){
        kurangiStok($database, $t, $j); //Ini nguranginnya per item
    }

    //nampilin stok yang tersedia
    echo "<div class='_stok-struk'>";
    echo "<h4>Status Stok Setelah Transaksi:</h4>";
    foreach($transaksi as $t => $j){ // => $j harus ditulis biar yang masuk ke dalam stokTersedia($database, $t); si $t(A001, A003) itu adalah key. kalau => $j nya ilang yang masuk adalah $j alias si valuenya (2, 3, 5)
        //if($j>0) mencegah array $transaksi yang $j nya 0(gak dibeli) gak ikut
        //masuk logic agar mencegah stokTersedia mencetak item yg tidak perlu
        if($j > 0){
            $stok = stokTersedia($database, $t);
            $stokReady = $stok[0];
            $stokNama = $stok[1];

            echo "<h4>- {$stokNama}: {$stokReady} pcs</h4>";
        }
        
    }
    echo "</div>";

    //Terima kasih
    echo "<hr>";
    echo "<h3>Terima kasih atas kunjungan Anda</h3>";
    echo "<hr>";

    echo "</div>";
}
?>