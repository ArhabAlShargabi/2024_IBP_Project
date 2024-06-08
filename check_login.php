<?php
// Database bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$database = "stokapp";

$conn = new mysqli($servername, $username, $password, $database);

// Database bağlantı hatası kontrolü
if ($conn->connect_error) {
    die("Databasena bağlanırken hata oluştu: " . $conn->connect_error);
}

// Formdan gelen verileri alma ve MD5 ile şifreleme
$email = $_POST['email'];
$password = md5($_POST['password']);

// Databasenda kullanıcıyı sorgulama
$sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

// Kullanıcı varsa giriş yap
if ($result->num_rows > 0) {
    session_start();
    $row = $result->fetch_assoc();
    $_SESSION['user_id'] = $row['id'];
    $_SESSION['name'] = $row['name'];
    $_SESSION['user_email'] = $row['email'];
    $_SESSION['user_role'] = $row['role'];
    if($row['role'] == "admin"){
       header("Location: yonetici_anasayfa.php"); // Giriş yapıldıktan sonra yönlendirilecek sayfa
    }
    else if($row['role'] == "user"){
        header("Location: kullanici_anasayfa.php"); // Giriş yapıldıktan sonra yönlendirilecek sayfa
    }
    exit();
} else {
    echo "Geçersiz e-mail veya şifre";
}

$conn->close();
?>
