<?php
session_start();

// If the user is not logged in or is not an admin, redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] == 'admin') {
    header("Location: login.php");
    exit();
}

// Databasendan mevcut duyuruları alın
// Örneğin:
// $duyurular = get_all_announcements_from_database();
// Bu fonksiyon, veritabanından tüm duyuruları almalı

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
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Title</th>
              <th scope="col">Contents</th>
              <th scope="col">Posted by</th>
              <th scope="col">Date</th>
            </tr>
          </thead>
          <tbody>
          <?php


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

            // Announcementsı veritabanından alın
            $sql = "SELECT announcements.*, users.name AS username FROM announcements JOIN users ON announcements.creator_id = users.id ORDER BY announcements.created_at DESC ";
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
  <?php
    // Homepageya dön butonunun linkini belirle
    $return_link = ($_SESSION['user_role'] == 'admin') ? 'yonetici_anasayfa.php' : 'kullanici_anasayfa.php';
?>

<a href="<?php echo $return_link; ?>" class="btn btn-secondary return-button">Home page</a>

</body>
</html>

