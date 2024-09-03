<?php
session_start();
include 'Connection.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = $_POST['username_or_email'];
    $password = $_POST['password'];

    // Prepare the statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM data_regis WHERE username_regis = ? OR email_regis = ?");
    $stmt->bind_param("ss", $username_or_email, $username_or_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // Verify password
        // If password is hashed, use password_verify
        if (password_verify($password, $row['password_regis'])) {
            // Set session variables
            $_SESSION["username"] = $row['username_regis'];
            // Redirect to homepage or any desired page
            header("Location: Halaman_Utama.php");
            exit();
        } else {
            $login_error = "Invalid password";
        }
    } else {
        $login_error = "Invalid username or email";
    }

    $stmt->close();
    $conn->close();
}
?>