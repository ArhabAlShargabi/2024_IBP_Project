<?php
session_start();

// Eğer kullanıcı giriş yapmamışsa veya rolü admin değilse, login sayfasına yönlendir
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Product</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <h2 class="mt-4 mb-4">Add New Product</h2>
        <form action="urun_ekle.php" method="POST">
          <div class="form-group">
            <label for="urunAdi">Product Name :</label>
            <input type="text" class="form-control" id="urunAdi" name="urunAdi" required>
          </div>
          <div class="form-group">
            <label for="kategori">Category :</label>
            <input type="text" class="form-control" id="kategori" name="kategori" required>
          </div>
          <div class="form-group">
            <label for="miktar">Amount :</label>
            <input type="number" class="form-control" id="miktar" name="miktar" required>
          </div>
          <div class="form-group">
            <label for="fiyat">Price :</label>
            <input type="number" class="form-control" id="fiyat" name="fiyat" required>
          </div>
          <button type="submit" class="btn btn-primary">Add</button>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
