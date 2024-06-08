<?php
session_start();

// Eğer kullanıcı giriş yapmamışsa, login sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Formdan gelen verileri al
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Eğer yeni şifreler eşleşmiyorsa, hata mesajı göster
    if ($new_password !== $confirm_password) {
        $_SESSION['error_message'] = "Yeni şifreler eşleşmiyor.";
        header("Location: sifre_yenile.php");
        exit();
    }

    // Veritabanından kullanıcının mevcut şifresini al
    $user_id = $_SESSION['user_id'];
    $db_password = ""; // Veritabanından şifreyi almak için gerekli kod buraya gelecek

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

    // Kullanıcının mevcut şifresini sorgula
    $sql = "SELECT password FROM users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($db_password);
    $stmt->fetch();

    
    // Eğer eski şifre doğru değilse, hata mesajı göster
    if (md5($old_password) !== $db_password) {
        $_SESSION['error_message'] = "Eski şifreniz yanlış.";
        echo ($_SESSION['error_message']);
        header("Location: sifre_yenile.php");
        exit();
    }
   
    // Veritabanı bağlantısını oluştur
    $conn = new mysqli($servername, $username, $password, $database);

    // Bağlantıyı kontrol et
    if ($conn->connect_error) {
        die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
    }

    // Kullanıcının yeni şifresini hashleyip tuzla
    $new_password_hashed = md5($new_password);

    // Kullanıcının şifresini güncelle
    $sql = "UPDATE users SET password = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_password_hashed, $user_id);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Şifreniz başarıyla güncellendi.";
    } else {
        $_SESSION['error_message'] = "Şifre güncellenirken bir hata oluştu.";
    }

    $stmt->close();
    $conn->close();

    $_SESSION['success_message'] = "Şifreniz başarıyla güncellendi.";
    header("Location: login.php"); // Şifre güncellendikten sonra yönlendirilecek sayfa
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h2 class="mt-4 mb-4">Reset Password</h2>
                <form action="sifre_yenile.php" method="POST">
                    <div class="form-group">
                        <label for="old_password">Old Password</label>
                        <input type="password" class="form-control" id="old_password" name="old_password" required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">New Password (Again)</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Reset Password</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
