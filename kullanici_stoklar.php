<?php
session_start();

// If the user is not logged in or is not an admin, redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    header("Location: login.php");
    exit();
}

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

// Productsi veritabanından alın
$sql = "SELECT * FROM products";
$result = $conn->query($sql);

// Products listesi
$products = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Stocks</title>
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
        <h2 class="mt-4 mb-4">Stocks</h2>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Product Name</th>
              <th scope="col">Category</th>
              <th scope="col">Amount</th>
              <th scope="col">Price</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product): ?>
              <tr>
                <th scope="row"><?php echo $product['id']; ?></th>
                <td><?php echo $product['name']; ?></td>
                <td><?php echo $product['category']; ?></td>
                <td><?php echo $product['quantity']; ?></td>
                <td><?php echo $product['price']; ?> TL</td>
              </tr>
            <?php endforeach; ?>
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
