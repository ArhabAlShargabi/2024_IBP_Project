<?php
session_start();

// Kullanıcı girişi yapılmadıysa veya yetkisi yoksa login sayfasına yönlendir
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$database = "stokapp";

// Veritabanı bağlantısını oluştur
$conn = new mysqli($servername, $username, $password, $database);

// Bağlantıyı kontrol et
if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}

// GET isteğiyle gelen duyuru ID'sini al
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $duyuruId = $_GET['id'];

    // Duyuruyu sil
    $sql = "DELETE FROM announcements WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $duyuruId);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Duyuru başarıyla silindi.";
    } else {
        $_SESSION['error_message'] = "Duyuru silinirken bir hata oluştu.";
    }

    $stmt->close();
}

$conn->close();

header("Location: yonetici_duyurular.php"); // Duyuru silindikten sonra yönlendirilecek sayfa
exit();
?>
