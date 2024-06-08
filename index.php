<?php
// Session'ı başlat
session_start();

// Tüm session değişkenlerini sil
session_unset();

// Session'u sonlandır
session_destroy();

// Kullanıcıyı redirect to login page
header("Location: login.php");
exit();
?>
