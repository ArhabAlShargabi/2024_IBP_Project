<?php
session_start();

// Eğer kullanıcı giriş yapmamışsa veya rolü admin değilse, login sayfasına yönlendir
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// POST isteğiyle gönderilen form verilerini al
if ($_SERVER["REQUEST_METHOD"] == "POST") {
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

    // POST verilerini al
    $urunAdi = $_POST['urunAdi'];
    $kategori = $_POST['kategori'];
    $miktar = $_POST['miktar'];
    $fiyat = $_POST['fiyat'];

    // Veritabanına yeni ürünü ekle
    $sql = "INSERT INTO products (name, category, quantity, price) VALUES ('$urunAdi', '$kategori', '$miktar', '$fiyat')";

    if ($conn->query($sql) === TRUE) {
        header("Location: yonetici_stoklar.php"); 

    } else {
        header("Location: yonetici_stoklar.php");
    }

    $conn->close();
}
?>
