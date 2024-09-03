<?php
session_start();

// Periksa apakah pengguna sudah login atau belum
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Anda belum login. Silakan login terlebih dahulu.'); window.location = 'Halaman_Login.php';</script>";
    exit(); // Hentikan eksekusi script jika pengguna belum login
}

$id_rincian_tiket = $_GET['id_rincian_tiket'];

include "Connection.php";

$konser = array();
$tiket = array();

$stmt = $conn->prepare("SELECT * FROM daftar_konser WHERE id_rincian_tiket = ?");
$stmt->bind_param("i", $id_rincian_tiket);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $konser = $result->fetch_assoc();
} else {
    echo "Konser tidak ditemukan";
    exit();
}

$stmt = $conn->prepare("SELECT * FROM tiket WHERE id_rincian_tiket = ?");
$stmt->bind_param("i", $id_rincian_tiket);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $tiket = $result->fetch_assoc();
} else {
    echo "Konser tidak ditemukan";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && !isset($_POST['confirm'])) {
    // Pertama kali submit, tampilkan konfirmasi
    $jumlahPemesanan = $_POST['jumlahPemesanan'];
    $namaPemesan = $_POST['nama'];
    $nikPemesan = $_POST['nik'];
    $nomorHpPemesan = $_POST['nomorhp'];
    $emailPemesan = $_POST['email'];
    // $tiket = $_GET['harga'];
    $totalHarga = $jumlahPemesanan * $tiket['harga'];
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) && isset($_POST['confirm'])) {
    // Form konfirmasi disubmit, simpan data ke database
    $jumlahPemesanan = $_POST['jumlahPemesanan'];
    $namaPemesan = $_POST['nama'];
    $nikPemesan = $_POST['nik'];
    $nomorHpPemesan = $_POST['nomorhp'];
    $emailPemesan = $_POST['email'];
    $tanggalPembelian = date("Y-m-d");
    $tiket = $_GET['harga'];

    $id_rincian_tiket = $_GET['id_rincian_tiket'];
    $potong_urutan = substr($id_rincian_tiket, 11, 5);
    $id_tiket = $potong_urutan;

    $totalHarga = $jumlahPemesanan * $tiket;

    // Insert into pembelian_tiket_konser
    $sql = "INSERT INTO pembelian_tiket_konser (nama_pemesanan, nomor_hp, email_pemesanan, jumlah, tanggal_pembelian, id_tiket) 
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssiis", $namaPemesan[0], $nomorHpPemesan, $emailPemesan, $jumlahPemesanan, $tanggalPembelian, $id_tiket);

    if ($stmt->execute()) {
        $id_pembelian = $stmt->insert_id; // Get the last inserted id

        // Insert into detail_pemesanan
        $sql_detail = "INSERT INTO detail_pemesanan (id_pembelian, nama_pemesanan, nama_orang, nik_orang) VALUES (?, ?, ?, ?)";
        $stmt_detail = $conn->prepare($sql_detail);

        for ($i = 0; $i < $jumlahPemesanan; $i++) {
            $stmt_detail->bind_param("isss", $id_pembelian, $namaPemesan[0], $namaPemesan[$i], $nikPemesan[$i]);
            $stmt_detail->execute();
        }

        echo "<script>confirm('Pemesanan berhasil! Total pembelian Anda adalah Rp.<?php return $jumlahPemesasnan*$tiket;?>'); window.location = 'DetailKonten.php?id_rincian_tiket=$id_rincian_tiket';</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musico</title>
    <link rel="icon" type="image" href="gambar/Logo2.png">
    <link rel="stylesheet" href="Pembayaran.css?v=1.0">
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
            <a href="DetailKonten.php?id_rincian_tiket=<?php echo $id_rincian_tiket; ?>"><img src="gambar/Back.png" alt=""></a>
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

    <!-- Konten pembayaran -->
    <div class="breadCrumb">
        <a href="Halaman Utama.php">Home /</a>
        <a href="DetailKonten.php?id_rincian_tiket=<?php echo $id_rincian_tiket; ?>"><?php echo $konser['nama_konser']; ?> /</a>
        <a href="Pembayaran.php?id_rincian_tiket=<?php echo $id_rincian_tiket; ?>">Pembayaran: <?php echo $konser['nama_konser']; ?></a>
    </div>

    <div class="berandaAtas">
        <h1>Pembayaran Tiket <?php echo $konser['nama_konser']; ?></h1>
        <img src="<?php echo $konser['posterBesar']; ?>" alt="Poster dari <?php echo $konser['nama_konser']; ?>">
    </div>

    <!-- Form biodata dan konten lainnya -->
    <div class="biodata">
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['confirm'])): ?>
            <form action="Pembayaran.php?id_rincian_tiket=<?php echo $id_rincian_tiket; ?>" method="post" class="biodataPembeli">
                <input type="hidden" name="jumlahPemesanan" value="<?php echo $jumlahPemesanan; ?>">
                <?php foreach ($namaPemesan as $index => $nama): ?>
                    <input type="hidden" name="nama[]" value="<?php echo $nama; ?>">
                    <input type="hidden" name="nik[]" value="<?php echo $nikPemesan[$index]; ?>">
                <?php endforeach; ?>
                <input type="hidden" name="nomorhp" value="<?php echo $nomorHpPemesan; ?>">
                <input type="hidden" name="email" value="<?php echo $emailPemesan; ?>">
                <input type="hidden" name="confirm" value="1">
                <h2>Konfirmasi Pembelian</h2>
                <ul>
                    <?php foreach ($namaPemesan as $index => $nama): ?>
                        <li>Nama: <?php echo $nama; ?>, NIK: <?php echo $nikPemesan[$index]; ?></li>
                    <?php endforeach; ?>
                </ul>
                <p>Nomor HP: <?php echo $nomorHpPemesan; ?></p>
                <p>Email: <?php echo $emailPemesan; ?></p>
                <p>Jumlah Pemesanan: <?php echo $jumlahPemesanan; ?></p>
                <p>Total Harga: Rp<?php echo $jumlahPemesanan*$tiket['harga']; ?></p>
                <p>Waktu konfirmasi: <span id="timer">30</span> detik</p>
                <button type="submit" name="submit" class="sumbit">Konfirmasi</button>
                <button type="button" onclick="window.history.back();" class="sumbit">Edit</button>
            </form>
            <script>
                let timerElement = document.getElementById('timer');
                let timeLeft = 30;

                const countdown = setInterval(() => {
                    if (timeLeft <= 0) {
                        clearInterval(countdown);
                        alert('Waktu konfirmasi habis, silakan ulangi pemesanan.');
                        window.location = 'Pembayaran.php?id_rincian_tiket=<?php echo $id_rincian_tiket; ?>?id_tiket<? echo $id_tiket?>';
                    } else {
                        timerElement.innerText = timeLeft;
                        timeLeft -= 1;
                    }
                }, 1000);
            </script>
        <?php else: ?>
            <form action="Pembayaran.php?id_rincian_tiket=<?php echo $id_rincian_tiket; ?>" method="post" class="biodataPembeli" onsubmit="return validateForm()">
                
                <br>

                <label for="nama">Nama Pemesan:</label><br>
                <input type="text" id="nama" name="nama" placeholder="Nama" required>
                <br><br>

                

                <label for="nomorhp">Nomor Ponsel Pemesan: </label><br>
                <input type="tel" id="nomorhp" name="nomorhp" placeholder="Contoh: 08**" required>
                <br><br>

                <label for="email">Email Pemesan: </label> <br>
                <input type="email" placeholder="email@example.com" name="email" id="email" required>
                <br><br>

                

                <label for="jumlahPemesanan">Masukan jumlah Pesanan</label><br>
                <div class="number-input">
                    <input type="number" name="jumlahPemesanan" id="number" placeholder="Ayo Beli" required oninput="generateFields()">
                    <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepUp(); generateFields();" class="add">+</button>
                    <button type="button" onclick="this.parentNode.querySelector('input[type=number]').stepDown(); generateFields();" class="minus">-</button>
                </div>
                <br>
                
                <div id="nameFields"></div>
                <br>
            

                <button type="reset" onclick="return confirm('Apakah Anda yakin ingin mereset inputan Anda?')" class="reset">Reset</button>
                <button type="submit" class="sumbit" name="submit">Submit</button>

            </form>
        <?php endif; ?>
    </div>
    <!-- Footer -->
    <div class="footeren">
        <footer>
            <p>Pemilik Mini Project</p>
            <p>&copy;Marcell Jureinwi Manuhutu - 71220855</p>
            <p>&copy;Rizky Charles Christiaan - 71220902</p>
            <p>&copy;Rifaldy Exclesia Tamauka - 71220922</p>
            <p>Universitas Kristen Duta Wacana</p>
        </footer>
    </div>

    <script>
    function generateFields() {
        var number = document.getElementById("number").value;
        var nameFields = document.getElementById("nameFields");
        nameFields.innerHTML = "";
        
        <?php if(!empty($namaPemesan)): ?>
            var namaPemesan = <?php echo json_encode($namaPemesan); ?>;
            var nikPemesan = <?php echo json_encode($nikPemesan); ?>;
        <?php endif; ?>


        for (var i = 0; i < number; i++) {
            var labelName = document.createElement("label");
            labelName.setAttribute("for", "nama" + i);
            labelName.innerHTML = "Nama Pemesan " + (i + 1) + ":";
            nameFields.appendChild(labelName);

            var inputName = document.createElement("input");
            inputName.type = "text";
            inputName.id = "nama" + i;
            inputName.name = "nama[]";
            inputName.placeholder = "Nama " + (i + 1);
            inputName.required = true;
            nameFields.appendChild(inputName);

            var labelNIK = document.createElement("label");
            labelNIK.setAttribute("for", "nik" + i);
            labelNIK.innerHTML = "NIK Pemesan " + (i + 1) + ":";
            nameFields.appendChild(labelNIK);

            var inputNIK = document.createElement("input");
            inputNIK.type = "text";
            inputNIK.id = "nik" + i;
            inputNIK.name = "nik[]";
            inputNIK.placeholder = "NIK " + (i + 1);
            inputNIK.required = true;
            nameFields.appendChild(inputNIK);

            nameFields.appendChild(document.createElement("br"));
            nameFields.appendChild(document.createElement("br"));

            // console.log("10101010101010101")
        }
    }

    function validateForm() {
        var number = document.getElementById("number").value;
        if (number < 1) {
            alert("Jumlah pemesanan harus lebih dari 0");
            return false;
        }

        var nameFields = document.getElementsByName("nama[]");
        var nikFields = document.getElementsByName("nik[]");
        var nameSet = new Set();
        var nikSet = new Set();

        for (var i = 0; i < nameFields.length; i++) {
            if (nameSet.has(nameFields[i].value) || nikSet.has(nikFields[i].value)) {
                alert("Nama tiap pesanan tidak boleh sama");
                return false;
            }
            nameSet.add(nameFields[i].value);
            nikSet.add(nikFields[i].value);
        }

        return true;
    }
    </script>

</body>
</html>


