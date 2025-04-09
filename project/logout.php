<?php
session_start();

if (isset($_POST['logout'])) {
    // Destroy the session and redirect to login page
    session_destroy();
    header("Location: dangnhap.php");
    exit();
}
?>
