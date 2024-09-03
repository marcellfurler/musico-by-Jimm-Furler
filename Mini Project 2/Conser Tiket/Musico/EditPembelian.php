<?php
session_start();

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Anda belum login. Silakan login terlebih dahulu.'); window.location = 'Halaman_Login.php';</script>";
    exit();
}

include 'Connection.php';

$id_pembelian = $_GET['id_pembelian'];

// Ambil data pembelian berdasarkan id_pembelian
$query = "
SELECT pt.id_pembelian, pt.nama_pemesanan, pt.nomor_hp, pt.email_pemesanan, pt.jumlah, dp.nama_orang, dp.nik_orang
FROM pembelian_tiket_konser pt
JOIN detail_pemesanan dp ON pt.id_pembelian = dp.id_pembelian
WHERE pt.id_pembelian = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_pembelian);
$stmt->execute();
$result = $stmt->get_result();

$pembelian = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_pemesanan = $_POST['nama_pemesanan'];
    $nomor_hp = $_POST['nomor_hp'];
    $email_pemesanan = $_POST['email_pemesanan'];
    $jumlah = $_POST['jumlah'];
    $nama_orang = $_POST['nama_orang'];
    $nik_orang = $_POST['nik_orang'];

    // Update pembelian_tiket_konser
    $update_query = "
    UPDATE pembelian_tiket_konser 
    SET nama_pemesanan = ?, nomor_hp = ?, email_pemesanan = ?, jumlah = ?
    WHERE id_pembelian = ?";
    
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("sssii", $nama_pemesanan, $nomor_hp, $email_pemesanan, $jumlah, $id_pembelian);
    $stmt->execute();

    // Update detail_pemesanan
    $update_detail_query = "
    UPDATE detail_pemesanan 
    SET nama_orang = ?, nik_orang = ?
    WHERE id_pembelian = ?";
    
    $stmt = $conn->prepare($update_detail_query);
    $stmt->bind_param("ssi", $nama_orang, $nik_orang, $id_pembelian);
    $stmt->execute();

    echo "<script>alert('Data berhasil diupdate'); window.location = 'History.php';</script>";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pembelian</title>
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
        <a href="Halaman Utama.php"><h1>Musico</h1></a>
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
    <div class="kontenEdit">
        <h2>Edit Pembelian Tiket Konser</h2>

        <form method="POST">
            <label for="nama_pemesanan">Nama Pemesan:</label>
            <input type="text" id="nama_pemesanan" name="nama_pemesanan" value="<?php echo $pembelian['nama_pemesanan']; ?>" required><br>

            <label for="nomor_hp">Nomor HP:</label>
            <input type="text" id="nomor_hp" name="nomor_hp" value="<?php echo $pembelian['nomor_hp']; ?>" required><br>

            <label for="email_pemesanan">Email:</label>
            <input type="email" id="email_pemesanan" name="email_pemesanan" value="<?php echo $pembelian['email_pemesanan']; ?>" required><br>

            <label for="jumlah">Jumlah Tiket:</label>
            <input type="number" id="jumlah" name="jumlah" value="<?php echo $pembelian['jumlah']; ?>" required><br>

            <label for="nama_orang">Nama Orang:</label>
            <input type="text" id="nama_orang" name="nama_orang" value="<?php echo $pembelian['nama_orang']; ?>" required><br>

            <label for="nik_orang">NIK Orang:</label>
            <input type="text" id="nik_orang" name="nik_orang" value="<?php echo $pembelian['nik_orang']; ?>" required><br>

            <input type="submit" value="Simpan">
        </form>
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
