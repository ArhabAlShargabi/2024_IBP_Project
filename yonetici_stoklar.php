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
        <a href="yeni_urun.php" class="btn btn-primary mb-3" style="position: absolute; top: 20px; right: 20px;">Add New Product</a>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Product Name</th>
              <th scope="col">Category</th>
              <th scope="col">Amount</th>
              <th scope="col">Price</th>
              <th scope="col">Transactions</th>
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
                <td>
                  <a href="duzenle.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-warning">Edit</a>
                  <a href="sil.php?id=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger">Delete</a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
   <!-- Homepageya dön butonu -->
   <a href="yonetici_anasayfa.php" class="btn btn-secondary return-button">Home page</a>
</body>
</html>
