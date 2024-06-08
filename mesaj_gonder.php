<?php
session_start();

// If the user is not logged in or is not an admin, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sender_id = $_SESSION['user_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];

    // Database bağlantısı için gerekli bilgiler
    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "stokapp";

    // Create database connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }

    // Mesajı veritabanına ekle
    $sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iis", $sender_id, $receiver_id, $message);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Mesaj başarıyla gönderildi.";
    } else {
        $_SESSION['error_message'] = "Mesaj gönderilirken bir hata oluştu.";
    }

    $stmt->close();
    $conn->close();

    header("Location: mesajlar.php");
    exit();
}
?>
