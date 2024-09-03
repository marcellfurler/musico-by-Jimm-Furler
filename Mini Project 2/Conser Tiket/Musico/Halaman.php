<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Musico</title>
    <link rel="icon" type="image" href="gambar/Logo2.png">
    <link rel="stylesheet" href="Halaman.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Anta&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Galindo&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Barlow:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Chonburi&family=Frank+Ruhl+Libre:wght@300..900&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Malar:wght@200;300;400;500;600;700;800&family=Single+Day&display=swap" rel="stylesheet">



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
    
                concerts.sort((a, b) => {
                    const dateA = new Date(a.querySelector('p:nth-child(3)').textContent.split(': ')[1]);
                    const dateB = new Date(b.querySelector('p:nth-child(3)').textContent.split(': ')[1]);
                    return dateB - dateA;
                });
    
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
        <a href="Halaman.html"><h1>Musico</h1></a>


        <div class="navigasiNav">
            <a href="Halaman.html">Home</a>
            <a href="AboutUs.html">About Us</a>
            <a href="Halaman_SignUp.html">Login</a>
        </div>


        
        <!-- <div class="pencarian">
            <form action="#" method="get" class="pencarianForm">
                <input class="formPencarian" type="text" placeholder="Cari berdasarkan nama artis" name="">
                <div class="dropdown">
                    <ul class="dropdownForm">
                        <li><a href="#">Filter</a></li>
                        <ul class="dropdownForms">
                            <li><h3>Filter Pencarian</h3></li>
                            <li>Lokasi <br><input type="text" placeholder="Masukan Lokasi anda"></li>
                            <li>Tanggal <br><input type="date"></li>
                        </ul>
                    </ul>
                </div>
                <button class="submitButton">
                    <a href="#" >Search</a>
                  
                </button>
                
                
            </form>
            

        </div> -->

        <div class="pencarian">
            <form action="#" method="get" class="pencarianForm">
                <input class="formPencarian" type="text" placeholder="Cari berdasarkan nama artis" name="searchTerm">
                <input type="submit" class="submitButton" value="Search">
            </form>
        </div>





        

    </div>

    <div class="gambarAtas">

        <div class="gambarAtasHeader">
            <img src="gambar/backgrnd1.jpg" alt="Conser At The Netherland using Musico">
            <!-- <p class="tulisanWelcome">Welcome To<span>MUSICO</span></p> -->
        </div>

        
    </div>

    <?php
    include 'Connection.php';  // Pastikan file koneksi Anda benar

    // Query untuk mengambil data dari tabel RincianTiketKonser
    $sql = "SELECT nama_konser, poster_kecil, tanggal_konser, lokasi_konser, harga_tiket FROM daftar_konser";
    $query = mysqli_query($conn, $sql);

    // Loop untuk menampilkan data dari hasil query
    while($data = mysqli_fetch_array($query)){
        echo '<div class="kontenKonser">';
        echo '<div class="musik">';
        echo '<div class="kontenBox">';
        echo '<button type="button" class="buttonKontenBoxes">';
        echo '<a href="DetailKonten_Musik.php">';
        echo '<img src="'.$data['poster_kecil'].'" alt="Poster Kecil">';
        echo '<h3>'.$data['nama_konser'].'</h3>';
        echo '<p>Tanggal: '.$data['tanggal_konser'].'</p>';
        echo '<p>Lokasi: '.$data['lokasi_konser'].'</p>';
        echo '<p>Harga: Rp. '.number_format($data['harga_tiket'], 0, ',', '.').'</p>';
        echo '</a>';
        echo '</button>';
        echo '</div>';
        echo '</div>';  // Menutup div musik
        echo '</div>';  // Menutup div kontenKonser
    }
?>




    <!-- Konten -->

    <!-- <div class="kontenKonser">
        <div class="musik">
            <h2>Konser Musik di Indonesia</h2>

            <div class="kontenBox">
                <button type="button" class="buttonKontenBoxes" >
                    <a href="DetailKonten_Musik.html">
                        <img src="gambar/KAJ.jpg" alt="Sia_Poster">
                        <h3 >Anak Jaksel: The Musical!</h3>
                        <p>Tanggal: 09 Maret 2024</p>
                        <p>Lokasi: GKJ, DKI Jakarta</p>
                        <p>Harga: Rp. 125.000 - Rp. 175.000</p>
                    </a>
                </button>

                <button type="button" class="buttonKontenBoxes" >
                    <a href="DetailKonten_Musik.html">
                        <img src="gambar/Bikin Panggung Bogor.jpg" alt="Sia_Poster">
                        <h3>Bikin Panggung Bogor</h3>
                        <p>Tanggal: 25 Februari 2024</p>
                        <p >Lokasi: Mangga Panggung Bogor</p>
                        <p>Harga: Rp. 800.000 - Rp. 1.250.000</p>
                    </a>
                </button>

                <button type="button" class="buttonKontenBoxes" >
                    <a href="DetailKonten_Musik.html">
                        <img src="gambar/Ariana_Musik.jpeg" alt="Sia_Poster">
                        <h3>Positions Ariana</h3>
                        <p>Tanggal: 20 Maret 2024</p>
                        <p>Lokasi: Ambon, Indonesia</p>
                        <p>Harga: Rp. 800.000 - Rp. 1.250.000</p>
                    </a>
                </button>

                <button type="button" class="buttonKontenBoxes" >
                    <a href="DetailKonten_Musik.html">
                        <img src="gambar/Harry_Musik.jpeg" alt="Sia_Poster">
                        <h3>Harry Styles World Tour'88</h3>
                        <p>Tanggal: 21 Januari 2024</p>
                        <p>Lokasi: Manado, Indonesia</p>
                        <p>Harga: Rp. 800.000 - Rp. 1.250.000</p>
                    </a>
                </button>

                <button type="button" class="buttonKontenBoxes" >
                    <a href="DetailKonten_Musik.html">
                        <img src="gambar/Taylor_Musik.jpeg" alt="Sia_Poster">
                        <h3>Taylor Swift The Eras Tour</h3>
                        <p>Tanggal: 12 Januari 2024</p>
                        <p>Lokasi: Papua, Indonesia</p>
                        <p>Harga: Rp. 800.000 - Rp. 1.250.000</p>
                    </a>
                </button>

                <button type="button" class="buttonKontenBoxes" >
                    <a href="DetailKonten_Musik.html">
                        <img src="gambar/EdSheeran_Musik.jpg" alt="Sia_Poster">
                        <h3>+-=/x Tour</h3>
                        <p>Tanggal: 2 Maret 2024</p>
                        <p>Lokasi: GBK Jakarta, Indonesia</p>
                        <p>Harga: Rp. 900.000 - Rp. 5.000.000</p>
                    </a>
                </button>

                

            </div>

        

            
        </div>


        <div class="grupBand">
            <h2>Konser Grup Band di Indonesia</h2>
            <div class="kontenBox">
                <button type="button" class="buttonKontenBoxes" >
                    <a href="DetailKonten_GrupBand.html">
                        <img src="gambar/grupBand1.jpg" alt="Sia_Poster">
                        <h3>The Hold On Tight Tour</h3>
                        <p>Tanggal: 23 Mey 2024</p>
                        <p>Lokasi: Aruba Hall, DKI Jakarta</p>
                        <p>Harga: Rp. 800.000 - Rp. 1.250.000</p>
                    </a>
                </button>

                <button type="button" class="buttonKontenBoxes" >
                    <a href="DetailKonten_Musik.html">
                        <img src="gambar/CP_GrupBand.jpeg" alt="Sia_Poster">
                        <h3>COLDPLAY ZURICH</h3>
                        <p>Tanggal: 21 Maret 2024</p>
                        <p>Lokasi: Jakarta, Indonesia</p>
                        <p>Harga: Rp. 800.000 - Rp. 1.250.000</p>
                    </a>
                </button>

                <button type="button" class="buttonKontenBoxes" >
                    <a href="DetailKonten_Musik.html">
                        <img src="gambar/WL_GrupBand.jpeg" alt="Sia_Poster">
                        <h3>Westlife Coast To Coast</h3>
                        <p>Tanggal: 1 Januari 2024</p>
                        <p>Lokasi: Bali, Indonesia</p>
                        <p>Harga: Rp. 800.000 - Rp. 1.250.000</p>
                    </a>
                </button>

                <button type="button" class="buttonKontenBoxes" >
                    <a href="DetailKonten_Musik.html">
                        <img src="gambar/BTS_GrupBand.jpeg" alt="Sia_Poster">
                        <h3>BTS - The Wings Tour</h3>
                        <p>Tanggal: 15 Februari 2024</p>
                        <p>Lokasi: Pontianak, Indonesia</p>
                        <p>Harga: Rp. 800.000 - Rp. 1.250.000</p>
                    </a>
                </button>

                <button type="button" class="buttonKontenBoxes" >
                    <a href="DetailKonten_Musik.html">
                        <img src="gambar/Billie_GrupBand.jpeg" alt="Sia_Poster">
                        <h3>Billie Eilish World Tour</h3>
                        <p>Tanggal: 12 Februari 2024</p>
                        <p>Lokasi: Surabaya, Indonesia</p>
                        <p>Harga: Rp. 800.000 - Rp. 1.250.000</p>
                    </a>
                </button>

                <button type="button" class="buttonKontenBoxes" >
                    <a href="DetailKonten_Musik.html">
                        <img src="gambar/NCT_GB.png" alt="Sia_Poster">
                        <h3>NCT 127: Neo City - The Unity</h3>
                        <p>Tanggal: 13 Januari 2024</p>
                        <p>Lokasi: Indonesia Arena, Jakarta</p>
                        <p>Harga: Rp. 900.000 - Rp. 5.000.000</p>
                    </a>
                </button>

                

            </div>

        </div> -->



        
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

}