<?php
// encrypt.php

// Encryption fonksiyonu
function encryptPassword($password) {
    return md5($password);
}

// Şifreyi çözen fonksiyon
function decryptPassword($original_password, $md5_password) {
    return md5($original_password) === $md5_password;
}

?>
