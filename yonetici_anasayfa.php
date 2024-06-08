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

// Stok sayısını al
$sql = "SELECT COUNT(*) as stok_sayisi FROM products";
$result = $conn->query($sql);
$stok_sayisi = $result->fetch_assoc()['stok_sayisi'];

// Kullanıcı sayısını al
$sql = "SELECT COUNT(*) as kullanici_sayisi FROM users";
$result = $conn->query($sql);
$kullanici_sayisi = $result->fetch_assoc()['kullanici_sayisi'];

// Mesaj sayısını al
$sql = "SELECT COUNT(*) as mesaj_sayisi FROM messages";
$result = $conn->query($sql);
$mesaj_sayisi = $result->fetch_assoc()['mesaj_sayisi'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <span class="brand-text font-weight-light">Admin Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
              Home page
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="yonetici_kullanicilar.php" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
              Users
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="yonetici_stoklar.php" class="nav-link">
              <i class="nav-icon fas fa-user"></i>
              <p>
              Stocks
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="yonetici_duyurular.php" class="nav-link">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>
              Announcements
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="mesajlar.php" class="nav-link">
              <i class="nav-icon fas fa-envelope"></i>
              <p>
              Messages
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="sifre_yenile.php" class="nav-link">
              <i class="nav-icon fas fa-envelope"></i>
              <p>
              Change Password
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="logout.php" class="nav-link">
              <i class="nav-icon fas fa-envelope"></i>
              <p>
              Log out
              </p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Home page</h1>
          </div>
        </div>
      </div>
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?php echo($stok_sayisi); ?></h3>
                <p>Stocks</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="yonetici_stoklar.php" class="small-box-footer">More information <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?php echo($kullanici_sayisi); ?></h3>
                <p>Users</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="yonetici_kullanicilar.php" class="small-box-footer">More information <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?php echo($mesaj_sayisi); ?></h3>
                <p>Messages</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="mesajlar.php" class="small-box-footer">More information <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="float-right d-none d-sm-inline">
    Admin Panel
    </div>
    <!-- Default to the left -->
    <strong><?php echo($_SESSION['name']) ?></strong>
  </footer>
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>
</body>
</html>
