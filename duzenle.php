<?php
session_start();

// If the user is not logged in or is not an admin, redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
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

// Ürün ID'sini al
$urunId = $_GET['id'];

// Databasendan ürün bilgilerini al
$sql = "SELECT * FROM products WHERE id = $urunId";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $urunAdi = $row['name'];
    $kategori = $row['category'];
    $miktar = $row['quantity'];
    $fiyat = $row['price'];
} else {
    echo "Ürün bulunamadı";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Product</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <h2 class="mt-4 mb-4">Edit Product</h2>
        <form action="urun_guncelle.php" method="POST">
          <input type="hidden" name="urunId" value="<?php echo $urunId; ?>">
          <div class="form-group">
            <label for="urunAdi">Product Name :</label>
            <input type="text" class="form-control" id="urunAdi" name="urunAdi" value="<?php echo $urunAdi; ?>" required>
          </div>
          <div class="form-group">
            <label for="kategori">Category :</label>
            <input type="text" class="form-control" id="kategori" name="kategori" value="<?php echo $kategori; ?>" required>
          </div>
          <div class="form-group">
            <label for="miktar">Amount :</label>
            <input type="number" class="form-control" id="miktar" name="miktar" value="<?php echo $miktar; ?>" required>
          </div>
          <div class="form-group">
            <label for="fiyat">Price :</label>
            <input type="number" class="form-control" id="fiyat" name="fiyat" value="<?php echo $fiyat; ?>" required>
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
