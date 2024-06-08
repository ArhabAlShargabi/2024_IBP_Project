<?php
session_start();

// If the user is not logged in or is not an admin, redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Form gönderildiğinde
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen verileri al
    $name = $_POST['name'];
    $email = $_POST['email'];
    $passwordUser = $_POST['password'];
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

    // Şifreyi hashle
    $hashed_password = md5($passwordUser);

    // Kullanıcıyı ekle
    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Kullanıcı başarıyla eklendi.";
    } else {
        $_SESSION['error_message'] = "Kullanıcı eklenirken bir hata oluştu.";
    }

    $stmt->close();
    $conn->close();

    header("Location: yonetici_kullanicilar.php"); // Kullanıcı eklendikten sonra yönlendirilecek sayfa
    exit();
}
?>

