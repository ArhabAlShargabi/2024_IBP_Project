<?php
session_start();

// If the user is not logged in or is not an admin, redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Form gönderildiğinde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Form verilerini al
    $creator = $_SESSION['user_id'];
    $baslik = $_POST['baslik'];
    $icerik = $_POST['icerik'];

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

    // Databasena yeni duyuruyu ekleme sorgusu
    $sql = "INSERT INTO announcements (title, content,  creator_id) VALUES ('$baslik', '$icerik', '$creator')";

    // Sorguyu çalıştır ve sonucu kontrol et
    if ($conn->query($sql) === TRUE) {
        $success = true;
    } else {
        echo "Duyuru eklenirken bir hata oluştu: " . $conn->error;
        return false;
    }

    // Database bağlantısını kapat
    $conn->close();

    // Ekleme işlemi başarılıysa, kullanıcıyı duyurular sayfasına yönlendir
    if ($success) {
        header("Location: yonetici_duyurular.php");
        exit();
    } 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Announcement</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h2 class="mt-4 mb-4">Add Announcement</h2>
            <?php if (isset($hata)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $hata; ?>
                </div>
            <?php endif; ?>
            <form method="post" action="duyuru_ekle.php">
                <div class="form-group">
                    <label for="baslik">Title:</label>
                    <input type="text" class="form-control" id="baslik" name="baslik" required>
                </div>
                <div class="form-group">
                    <label for="icerik">Contents:</label>
                    <textarea class="form-control" id="icerik" name="icerik" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Add</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
