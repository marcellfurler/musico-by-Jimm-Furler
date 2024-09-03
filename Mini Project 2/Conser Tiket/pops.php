<?php
	include 'Connection.php';
	if(isset($_POST['btn-login'])){
		$query = "select * from data_login where username = '".$_POST['username']."'";
		$result = mysqli_query($conn, $query);
		$cekData = mysqli_num_rows($result);

		if($cekData < 1){
			$message = "username atau password anda salah!";
			header("Location: Halaman_SignUp.php?message=" . urlencode($message));
			exit();

		}else{
			header('Location: Halaman.html');
		}
	}
?>