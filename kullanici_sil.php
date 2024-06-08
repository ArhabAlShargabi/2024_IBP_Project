<?php
session_start();

// If the user is not logged in or is not an admin, redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    // Delete user
    $userId = $_GET['id'];

    // Delete user's messages
    $sqlDeleteMessages = "DELETE FROM messages WHERE sender_id = ? OR receiver_id = ?";
    $stmtDeleteMessages = $conn->prepare($sqlDeleteMessages);
    $stmtDeleteMessages->bind_param("ii", $userId,$userId);

    if ($stmtDeleteMessages->execute()) {
        // Messages başarıyla silindi
    } else {
        // Messages silinirken bir hata oluştu
    }

    $stmtDeleteMessages->close();


    // Delete user's announcements
    $sqlDeleteMessages = "DELETE FROM announcements WHERE creator_id = ?";
    $stmtDeleteMessages = $conn->prepare($sqlDeleteMessages);
    $stmtDeleteMessages->bind_param("i", $userId);

    if ($stmtDeleteMessages->execute()) {
        // duyurularını başarıyla silindi
    } else {
        // duyurularını silinirken bir hata oluştu
    }

    $stmtDeleteMessages->close();

    // Delete user
    $sqlDeleteUser = "DELETE FROM users WHERE id = ?";
    $stmtDeleteUser = $conn->prepare($sqlDeleteUser);
    $stmtDeleteUser->bind_param("i", $userId);

    if ($stmtDeleteUser->execute()) {
        $_SESSION['success_message'] = "Kullanıcı başarıyla silindi.";
    } else {
        $_SESSION['error_message'] = "Kullanıcı silinirken bir hata oluştu.";
    }

    $stmtDeleteUser->close();
}

$conn->close();

header("Location: yonetici_kullanicilar.php"); // Kullanıcı silindikten sonra yönlendirilecek sayfa
exit();
?>
