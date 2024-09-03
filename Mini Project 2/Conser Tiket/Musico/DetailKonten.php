<?php
session_start();

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Anda belum login. Silakan login terlebih dahulu.'); window.location = 'Halaman_Login.php';</script>";
    exit(); // Hentikan eksekusi script jika pengguna belum login
}

include "Connection.php";

// Inisialisasi variabel untuk menyimpan data konser dan tiket
$konser = array();
$tiket = array();

// Jika parameter ID diberikan, ambil detail konser dan tiket sesuai ID
if (isset($_GET['id_rincian_tiket'])) {
    $concertId = intval($_GET['id_rincian_tiket']);

    // Ambil detail konser
    $stmt = $conn->prepare("SELECT * FROM daftar_konser WHERE id_rincian_tiket = ?");
    $stmt->bind_param("i", $concertId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $konser = $result->fetch_assoc();
    } else {
        echo "Konser tidak ditemukan";
        exit();
    }
    $stmt->close();

    // Ambil detail tiket
    $stmt = $conn->prepare("SELECT * FROM tiket WHERE id_rincian_tiket = ?");
    $stmt->bind_param("i", $concertId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tiket[] = $row;
        }
    } 
    $stmt->close();
} else {
    echo "ID Rincian Tiket tidak diberikan";
    exit();
}

$conn->close();

// Periksa apakah konser sudah lewat
$eventEnded = strtotime($konser['tanggal_konser']) <= strtotime(date('Y-m-d'));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musico</title>
    <link rel="icon" type="image" href="gambar/Logo2.png">
    <link rel="stylesheet" href="DetailKonten.css?v=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anta&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Galindo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow&family=Chonburi&family=Frank+Ruhl+Libre&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Malar&family=Single+Day&display=swap" rel="stylesheet">

</head>
<body>

    <!-- Navigasi -->
    <div class="navigasiHeader">
        <div class="tombolBack">
            <a href="Halaman Utama.php"><img src="gambar/Back.png" alt=""></a>
        </div>
        <a href="Halaman Utama.php"><h1>Musico</h1></a>
        <div class="navigasiNav">
            <a href="Halaman Utama.php">Home</a>
            <a href="History.php">History</a>
            <?php if (isset($_SESSION['username'])): ?>
                <a>Hallo, <?php echo $_SESSION['username']; ?> !</a>
            <?php else: ?>
                <a href="Halaman_Login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="breadCrumb">
        <a href="Halaman Utama.php">Home /</a>
        <a href="DetailKonten.php?id_rincian_tiket=<?php echo $konser['id_rincian_tiket']; ?>">
            <?php echo $konser['nama_konser']; ?>
        </a>
    </div>

    <div class="kontenMain">
        <h2 class="judulKonten"><?php echo $konser['nama_konser']; ?></h2>
        <img src="<?php echo $konser['posterBesar']; ?>" alt="Concert Image">
        
        <div class="detailKonten">
            <p><span>Hari/Tanggal: </span><?php echo date('l, d F Y', strtotime($konser['tanggal_konser'])); ?></p>
            <p><span>Waktu: </span><?php echo $konser['waktu_mulai'] . ' - ' . $konser['waktu_berakhir']; ?> WIB</p>
            <p><span>Lokasi: </span><?php echo $konser['lokasi_konser']; ?></p>
            <p><span>Penyelenggara: </span><?php echo $konser['penyelenggara']; ?></p>
        </div> 
        
        <div class="informasiKonten">
            <div class="deskripsi">
                <h2>Deskripsi</h2>
                <img src="<?php echo $konser['posterStage']; ?>" alt="Concert Stage">
                <p><?php echo nl2br($konser['deskripsi']); ?></p>
            </div>

            <div class="tiket">
                <h2>Tiket</h2>
                <div class="rincianKelasTiket">
                    <?php if (!empty($tiket)): ?>
                        <?php foreach ($tiket as $t): ?>
                            <?php if ($t['stok'] > 0): ?>
                                <div class="kelasTiket">
                                    <div class="rincianTiket">
                                        <h4><?php echo $t['nama_tiket']; ?></h4>
                                        <p><?php echo $t['keterangan']; ?></p>
                                        <p class="hargaTiket">Rp. <?php echo number_format($t['harga'], 0, ',', '.'); ?></p>
                                        <p>Stok: <?php echo $t['stok']; ?></p>
                                        <button class="tersedia"><a href="Pembayaran.php?id_rincian_tiket=<?php echo $concertId; ?>?id_tiket=<?php echo $t['id_tiket']; ?>">Pesan</a></button>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="kelasTiket">
                                    <div class="rincianTiket">
                                        <h4><?php echo $t['nama_tiket']; ?></h4>
                                        <p><?php echo $t['keterangan']; ?></p>
                                        <p class="hargaTiket">Rp. <?php echo number_format($t['harga'], 0, ',', '.'); ?></p>
                                        <p>Stok: <?php echo $t['stok']; ?></p>
                                        <button class="stokHabis" disabled>Stok Habis</button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <center>Tiket tidak tersedia</center>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="footeren">
        <footer>
            <p>Pemilik Mini Project</p>
            <p>&copy;Marcell Jureinwi Manuhutu - 71220855</p>
            <p>&copy;Rizky Charles Christiaan - 71220902</p>
            <p>&copy;Rifaldy Exclesia Tamauka - 71220922</p>
            <p>Universitas Kristen Duta Wacana</p>
        </footer>
    </div>
</body>
</html>
