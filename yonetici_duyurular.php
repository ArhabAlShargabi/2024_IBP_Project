<?php
session_start();

// Eğer kullanıcı giriş yapmamışsa veya rolü admin değilse, login sayfasına yönlendir
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Veritabanından mevcut duyuruları alın
// Örneğin:
// $duyurular = get_all_announcements_from_database();
// Bu fonksiyon, veritabanından tüm duyuruları almalı

// Örnek duyurular
$duyurular = array(
    array('id' => 1, 'baslik' => 'Duyuru Başlığı 1', 'icerik' => 'Duyuru İçeriği 1', 'tarih' => '2024-05-23'),
    array('id' => 2, 'baslik' => 'Duyuru Başlığı 2', 'icerik' => 'Duyuru İçeriği 2', 'tarih' => '2024-05-22'),
    // Diğer duyurular
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Announcements</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Sağ alt köşede yapışık kalacak şekilde butonu ayarla */
    .return-button {
      position: fixed;
      bottom: 20px;
      right: 20px;
      z-index: 99;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h2 class="mt-4 mb-4">Announcements</h2>
        <a href="duyuru_ekle.php" class="btn btn-primary mb-3">Add New Announcement</a>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Title</th>
              <th scope="col">Contents</th>
              <th scope="col">Posted by</th>
              <th scope="col">Date</th>
              <th scope="col">Transactions</th>
            </tr>
          </thead>
          <tbody>
          <?php


            // Veritabanı bağlantısı için gerekli bilgiler
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

            // Announcementsı veritabanından alın
            $sql = "SELECT announcements.*, users.name AS username FROM announcements JOIN users ON announcements.creator_id = users.id  ORDER BY announcements.created_at DESC ";
            $result = $conn->query($sql);

            // Announcementsı listeleme
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<th scope='row'>" . $row['id'] . "</th>";
                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['content'] . "</td>";
                    echo "<td>" . $row['username'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td>";
                    echo "<a href='duyuru_duzenle.php?id=" . $row['id'] . "' class='btn btn-sm btn-warning'>Edit</a>";
                    echo "<a href='duyuru_sil.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "Hiç duyuru bulunamadı.";
            }
            $conn->close();
            ?>

          </tbody>
        </table>
      </div>
    </div>
  </div>
   <!-- Homepageya dön butonu -->
   <a href="yonetici_anasayfa.php" class="btn btn-secondary return-button">Home page</a>
</body>
</html>

