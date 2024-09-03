<?php
session_start();
session_destroy();
header("Location: Halaman_Login.php");
?>