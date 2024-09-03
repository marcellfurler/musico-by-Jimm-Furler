<?php
session_start();
include 'Connection.php';

if (isset($_POST['btn-login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM data_login WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifying the password
        if ($password == $row['password']) {
            // Password matches, start session
            $_SESSION['username'] = $username;
            header('Location: Halaman Utama.php');
        } else {
            $message = "username atau password anda salah!";
            header("Location: Halaman_Login.php?message=" . urlencode($message));
        }
    } else {
        $message = "username atau password anda salah!";
        header("Location: Halaman_Login.php?message=" . urlencode($message));
    }
    exit();
}
?>
