<?php
session_start();

// If the user is not logged in or is not an admin, redirect to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Giriş yapan kullanıcının ID'si
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages</title>
  <!-- Bootstrap CSS -->
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .fixed-button {
      position: fixed;
      top: 20px;
      right: 20px;
      z-index: 1000;
    }
    
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
        
        <h2 class="mt-4 mb-4">Messages</h2>
        <button type="button" class="btn btn-primary fixed-button" data-toggle="modal" data-target="#newMessageModal">Yeni Send Message</button>
      </div>
      <div class="col-md-6">
        
        <h3 class="mb-4">Incoming Messages</h3>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Sender</th>
              <th scope="col">Message</th>
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

            // Gelen mesajları veritabanından alın
            $sql = "SELECT messages.*, users.name AS sender_name FROM messages JOIN users ON messages.sender_id = users.id WHERE messages.receiver_id = $user_id  ORDER BY messages.created_at DESC ";
            $result = $conn->query($sql);

            // Gelen mesajları listeleme
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<th scope='row'>" . $row['id'] . "</th>";
                    echo "<td>" . $row['sender_name'] . "</td>";
                    echo "<td>" . $row['message'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Hiç gelen mesaj bulunamadı.</td></tr>";
            }
          ?>
          </tbody>
        </table>
      </div>

      <div class="col-md-6">
        <h3 class="mb-4">Sent Messages</h3>
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Recipient</th>
              <th scope="col">Message</th>
              <th scope="col">Date</th>
              <th scope="col">Transactions</th>
            </tr>
          </thead>
          <tbody>
          <?php
            // Gönderdiğiniz mesajları veritabanından alın
            $sql = "SELECT messages.*, users.name AS receiver_name FROM messages JOIN users ON messages.receiver_id = users.id WHERE messages.sender_id = $user_id  ORDER BY messages.created_at DESC ";
            $result = $conn->query($sql);

            // Gönderilen mesajları listeleme
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<th scope='row'>" . $row['id'] . "</th>";
                    echo "<td>" . $row['receiver_name'] . "</td>";
                    echo "<td>" . $row['message'] . "</td>";
                    echo "<td>" . $row['created_at'] . "</td>";
                    echo "<td>";
                    echo "<a href='mesaj_sil.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Hiç gönderdiğiniz mesaj bulunamadı.</td></tr>";
            }
            $conn->close();
          ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Yeni Mesaj Modal -->
  <div class="modal fade" id="newMessageModal" tabindex="-1" aria-labelledby="newMessageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="newMessageModalLabel">Send New Message</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="newMessageForm" action="mesaj_gonder.php" method="POST">
            <div class="form-group">
              <label for="receiver_id">Recipient</label>
              <select class="form-control" id="receiver_id" name="receiver_id" required>
                <option value="">Select a user</option>
                <?php
                // Create database connection
                $conn = new mysqli($servername, $username, $password, $database);

                // Check connection
                if ($conn->connect_error) {
                    die("Database connection failed: " . $conn->connect_error);
                }

                // Usersı veritabanından alın
                $sql = "SELECT id, name FROM users WHERE id != $user_id";
                $result = $conn->query($sql);

                // Usersı listeleme
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                }

                $conn->close();
                ?>
              </select>
            </div>
            <div class="form-group">
              <label for="message">Message</label>
              <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" form="newMessageForm" class="btn btn-primary">Send</button>
        </div>
      </div>
    </div>
  </div>
  <?php
    // Homepageya dön butonunun linkini belirle
    $return_link = ($_SESSION['user_role'] == 'admin') ? 'yonetici_anasayfa.php' : 'kullanici_anasayfa.php';
    ?>
   <!-- Homepageya dön butonu -->
   <a href="<?php echo $return_link; ?>" class="btn btn-secondary return-button">Home page</a>
  
  <!-- Bootstrap JS ve jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
