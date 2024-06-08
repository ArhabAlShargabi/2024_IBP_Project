<?php
session_start();

// Eğer kullanıcı giriş yapmamışsa, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen verileri al
    $userId = $_POST['userId'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $role = $_POST['role'];

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

    // Kullanıcıyı güncelle
    $sql = "UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", $name, $email, $role, $userId);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Kullanıcı başarıyla güncellendi.";
    } else {
        $_SESSION['error_message'] = "Kullanıcı güncellenirken bir hata oluştu.";
    }

    $stmt->close();
    $conn->close();

    header("Location: yonetici_kullanicilar.php"); // Kullanıcı güncellendikten sonra yönlendirilecek sayfa
    exit();
}
?>
