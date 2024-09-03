<?php
session_start();

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check username and password (example)
    if ($_POST['username'] == "example_user" && $_POST['password'] == "example_password") {
        // Set session variables
        $_SESSION["username"] = $_POST['username'];
        // Redirect to homepage or any desired page
        header("Location: Halaman_Login.php");
        exit();
    } else {
        $login_error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Slide Navbar</title>
    <link rel="stylesheet" type="text/css" href="Halaman_Login.css?v=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
    <link rel="icon" type="image" href="gambar/Logo2.png">
    <link href="https://fonts.googleapis.com/css2?family=Anta&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Galindo&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Mukta+Malar:wght@200;300;400;500;600;700;800&family=Single+Day&display=swap" rel="stylesheet">
</head>

<script type="text/javascript">
    // Menampilkan pesan alert jika ada
    var message = "<?php echo $message; ?>";
    if (message) {
        alert(message);
    }
</script>
<body>
    <div class="main">    
        <div class="halaman_registrasi">
            <form method="post" action="Halaman_Regis_conn.php">
                <h1 class="regis">Registrasi</h1>
                <input type="text" name="username" placeholder="Username" required="">
                <input type="email" name="email" placeholder="Email" required="">
                <input type="password" name="password" placeholder="Password" required="">
                <input type="hidden" name="action" value="login">
                <input class="button" type="submit" value="Registrasi" name="btn-regis">
            
            </form>
            <a href="Halaman_Login.php" class="button"><button class="button">Back</button></a>
            
            <!-- <p>*Kamu akan tertinggal di website Musico selama Login</p> -->
        </div>
    </div>
    
</body>
</html>
