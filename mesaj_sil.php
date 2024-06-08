<?php
session_start();

// Eğer kullanıcı giriş yapmamışsa, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database bağlantısı
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

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // URL'den mesaj ID'sini al
    $message_id = $_GET['id'];

    // Mesajı silme sorgusu
    $sql = "DELETE FROM messages WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $message_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Mesaj başarıyla silindi.";
    } else {
        $_SESSION['error_message'] = "Mesaj silinirken bir hata oluştu.";
    }

    $stmt->close();
}

// Bağlantıyı kapat
$conn->close();

// Deleteme işleminden sonra mesajların listelendiği sayfaya yönlendir
header("Location: mesajlar.php");
exit();
?>
