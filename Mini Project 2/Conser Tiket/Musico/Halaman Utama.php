<?php
session_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musico</title>
    <link rel="icon" type="image" href="gambar/Logo2.png">
    <link rel="stylesheet" href="Halaman.css?v=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anta&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Galindo&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Barlow&family=Chonburi&family=Frank+Ruhl+Libre&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Malar&family=Single+Day&display=swap" rel="stylesheet">

    <script>
    document.addEventListener('DOMContentLoaded', () => {   
        const searchForm = document.querySelector('.pencarianForm');
        const searchInput = document.querySelector('.formPencarian');
        const concertBoxes = document.querySelectorAll('.buttonKontenBoxes');

        document.querySelector('.submitButton').addEventListener('click', (e) => {
            e.preventDefault();
            const searchTerm = searchInput.value.toLowerCase();
            filterConcerts(searchTerm);
        });

        function filterConcerts(searchTerm) {
            const concerts = Array.from(concertBoxes);

            concerts.forEach(concert => {
                const artistName = concert.querySelector('h3').textContent.toLowerCase();
                const location = concert.querySelector('p:nth-child(4)').textContent.toLowerCase();
                const date = concert.querySelector('p:nth-child(3)').textContent.toLowerCase();

                if (artistName.includes(searchTerm) || location.includes(searchTerm) || date.includes(searchTerm)) {
                    concert.style.display = '';
                } else {
                    concert.style.display = 'none';
                }
            });
        }
    });
</script>
</head>

<body>
    <!-- Navigasi -->
    <div class="navigasiHeader">
        <!-- Logo -->
        <a href="Halaman Utama.php"><h1>Musico</h1></a>
        <div class="navigasiNav">
            <a href="Halaman Utama.php">Home</a>
            <a href="History.php">History</a>

            
            <?php if (isset($_SESSION['username'])): ?>
                <a href="logout.php">Logout</a>
                <a>Selamat Datang, <?php echo $_SESSION['username']; ?> !</a>
            <?php else: ?>
                <a href="Halaman_9.php">Login</a>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['username'])): ?>
        <?php endif; ?>

        <div class="pencarian">
            <form action="index.php" method="get" class="pencarianForm">
                <input class="formPencarian" type="text" placeholder="Cari berdasarkan nama artis" name="searchTerm">
                <input type="submit" class="submitButton" value="Search">
                
            </form>
        </div>
    </div>


    <div class="gambarAtas">
        <div class="gambarAtasHeader">
            <img src="gambar/backgrnd1.jpg" alt="Conser At The Netherland using Musico">
        </div>
    </div>

    <!-- Konten -->
    <div class="kontenKonser">
    <?php
    include 'Connection.php';

    $query = "SELECT DISTINCT jenis_konser FROM daftar_konser";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $concertType = $row['jenis_konser'];

            $concertQuery = "SELECT * FROM daftar_konser WHERE jenis_konser = '$concertType'";
            $concertResult = $conn->query($concertQuery);

            if ($concertResult->num_rows > 0) {
                echo "<div class='$concertType'>";
                echo "<h2>\"Konser $concertType di Indonesia\"</h2>";
                echo "<div class='kontenBox'>";

                while ($concertRow = $concertResult->fetch_assoc()) {
                    $today = date("Y-m-d");
                    $concertDate = $concertRow['tanggal_konser'];
                    $status = (strtotime($concertDate) < strtotime($today)) ? "Sudah tidak Tersedia" : "Tersedia";

                    echo "<button type='button' class='buttonKontenBoxes'>
                            <a href='http://localhost/Mini_Project2/Mini%20Project%202/Conser%20Tiket/Musico/DetailKonten.php?id_rincian_tiket=".$concertRow["id_rincian_tiket"]."'>
                                <img src='" . $concertRow['poster_kecil'] . "' alt='Poster'>
                                <h3>" . $concertRow['nama_konser'] . "</h3>
                                <p>Tanggal: " . $concertDate . "</p>
                                <p>Lokasi: " . $concertRow['lokasi_konser'] . "</p>
                                <p>Harga: Rp. " . number_format($concertRow['harga_tiket'], 0, ',', '.') . "</p>
                                <p>Status: " . $status . "</p>
                            </a>
                        </button>";
                }

                echo "</div>";
                echo "</div>";
            } else {
                echo "No concerts available for $concertType.";
            }
        }
    } else {
        echo "No concert types found.";
    }
    ?>
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