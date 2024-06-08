<?php
session_start();

// Eğer kullanıcı giriş yapmamışsa veya rolü admin değilse, login sayfasına yönlendir
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$database = "stokapp";

$conn = new mysqli($servername, $username, $password, $database);

// Veritabanı bağlantı hatası kontrolü
if ($conn->connect_error) {
    die("Veritabanına bağlanırken hata oluştu: " . $conn->connect_error);
}

// Deleteinecek ürünün id'sini al
$urunId = $_GET['id'];

// Ürünü silme sorgusu
$sql = "DELETE FROM products WHERE id='$urunId'";

if ($conn->query($sql) === TRUE) {
    header("Location: yonetici_stoklar.php");
} else {
    header("Location: yonetici_stoklar.php"); 
}

$conn->close();
?>
