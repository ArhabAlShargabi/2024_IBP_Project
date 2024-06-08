<?php
session_start();

// If the user is not logged in or is not an admin, redirect to login page
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Kullanıcının ID'sini al
$userId = $_GET['id'];

// Database bağlantısı
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

// Kullanıcı bilgilerini veritabanından al
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

// Kullanıcı bilgilerini al
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Kullanıcı bulunamadı.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <h2 class="mt-4 mb-4">Edit User</h2>
        <form action="kullanici_guncelle.php" method="POST">
          <input type="hidden" name="userId" value="<?php echo $user['id']; ?>">
          <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo $user['name']; ?>" required>
          </div>
          <div class="form-group">
            <label for="email">E-Mail:</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $user['email']; ?>" required>
          </div>
          <div class="form-group">
            <label for="role">Role:</label>
            <select class="form-control" id="role" name="role" required>
              <option value="user" <?php echo ($user['role'] === 'user') ? 'selected' : ''; ?>>Standard Kullanıcı</option>
              <option value="admin" <?php echo ($user['role'] === 'admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
