<?php
// submit_order.php
include 'Connection.php'; // Sesuaikan dengan koneksi database Anda

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $concertName = $_POST['concertName'];
    $ticketPackage = $_POST['ticketPackage'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $ticketQuantity = $_POST['ticketQuantity'];

    // Validasi dan filter input
    // ...

    // Mulai transaksi
    mysqli_begin_transaction($conn);

    try {
        // Kurangi stok tiket
        $updateStockQuery = "UPDATE tickets SET stock = stock - ? WHERE concert_name = ? AND ticket_package = ?";
        $stmt = mysqli_prepare($conn, $updateStockQuery);
        mysqli_stmt_bind_param($stmt, 'iss', $ticketQuantity, $concertName, $ticketPackage);
        mysqli_stmt_execute($stmt);

        // Simpan data pemesanan
        $insertOrderQuery = "INSERT INTO orders (concert_name, ticket_package, buyer_name, buyer_email, buyer_phone, quantity) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertOrderQuery);
        mysqli_stmt_bind_param($stmt, 'sssssi', $concertName, $ticketPackage, $name, $email, $phone, $ticketQuantity);
        mysqli_stmt_execute($stmt);

        // Simpan data pemegang tiket
        for ($i = 0; $i < $ticketQuantity; $i++) {
            $holderName = $_POST['holderName' . $i];
            $holderEmail = $_POST['holderEmail' . $i];
            $insertHolderQuery = "INSERT INTO ticket_holders (order_id, holder_name, holder_email) VALUES (LAST_INSERT_ID(), ?, ?)";
            $stmt = mysqli_prepare($conn, $insertHolderQuery);
            mysqli_stmt_bind_param($stmt, 'ss', $holderName, $holderEmail);
            mysqli_stmt_execute($stmt);
        }

        // Commit transaksi
        mysqli_commit($conn);
        echo "Pemesanan berhasil!";
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        mysqli_rollback($conn);
        echo "Pemesanan gagal: " . $e->getMessage();
    }
}
?>