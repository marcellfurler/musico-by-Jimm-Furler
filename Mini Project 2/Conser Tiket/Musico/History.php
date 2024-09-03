<?php
session_start();

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Anda belum login. Silakan login terlebih dahulu.'); window.location = 'Halaman_Login.php';</script>";
    exit();
}

include 'Connection.php';

$username = $_SESSION['username'];

// Query untuk mengambil riwayat pembelian tiket konser berdasarkan username pengguna
$query = "SELECT pt.id_pembelian, pt.nama_pemesanan, pt.nomor_hp, pt.email_pemesanan, pt.jumlah, pt.tanggal_pembelian, dp.id_detail_pemesanan, dp.nama_orang, dp.nik_orang
FROM pembelian_tiket_konser pt
JOIN detail_pemesanan dp ON pt.id_pembelian = dp.id_pembelian
WHERE pt.nama_pemesanan = ?
ORDER BY pt.tanggal_pembelian DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$purchases = [];
while ($row = $result->fetch_assoc()) {
    $purchases[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musico - History</title>
    <link rel="icon" type="image" href="gambar/Logo2.png">
    <link rel="stylesheet" href="Halaman.css?v=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anta&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Galindo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow&family=Chonburi&family=Frank+Ruhl+Libre&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Malar&family=Single+Day&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <div class="navigasiHeader">
        <!-- Logo -->
        <a href="Halaman_Utama.php"><h1>Musico</h1></a>
        <div class="navigasiNav">
            <a href="Halaman Utama.php">Home</a>
            <a href="AboutUs.html">About Us</a>
            
            <?php if (isset($_SESSION['username'])): ?>
                <a href="logout.php">Logout</a>
                <a>Selamat Datang, <?php echo $_SESSION['username']; ?> !</a>
            <?php else: ?>
                <a href="Halaman_Login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Content -->
    <div class="kontenHistory">
        <h2>Riwayat Pembelian Tiket Konser</h2>

        <?php if (empty($purchases)): ?>
            <p>Tidak ada riwayat pembelian tiket konser.</p>
        <?php else: ?>
            <table border="1">
                <tr>
                    <th>ID Pembelian</th>
                    <th>Nama Pemesan</th>
                    <th>Nomor HP</th>
                    <th>Email</th>
                    <th>Jumlah Tiket</th>
                    <th>Nama Orang</th>
                    <th>NIK Orang</th>
                    <th>Aksi</th>
                </tr>
                <?php foreach ($purchases as $purchase): ?>
                    <tr>
                        <td><?php echo $purchase['id_pembelian']; ?></td>
                        <td><?php echo $purchase['nama_pemesanan']; ?></td>
                        <td><?php echo $purchase['nomor_hp']; ?></td>
                        <td><?php echo $purchase['email_pemesanan']; ?></td>
                        <td><?php echo $purchase['jumlah']; ?></td>
                        <td><?php echo $purchase['nama_orang']; ?></td>
                        <td><?php echo $purchase['nik_orang']; ?></td>
                        <td>
                            <a href="Pembayaran.php?id_pembelian=<?php echo $purchase['id_pembelian']; ?>">Edit</a>
                            <a href="HapusPembelian.php?id_pembelian=<?php echo $purchase['id_pembelian']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus pembelian ini?');">Batal</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

    <div class="footeren">
        <footer>
            <p>Pemilik Mini Project</p>
            <p>&copy;Marcell Jureinwi Manuhutu - 71220855</p>
            <p>&copy;Rizky Charles Christiaan - 71220902</p>
            <p>&copy;Rifaldy Exclesia Tamauka - 71220922</p>
        </footer>
    </div>
</body>
</html>
