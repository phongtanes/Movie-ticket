<?php
session_start();
session_destroy();

// ใช้ HTTP_REFERER เพื่อกลับไปยังหน้าปัจจุบัน
$redirect_url = $_SERVER['HTTP_REFERER'] ?? 'index.php'; // ถ้าไม่มี referer ให้กลับไปที่ index.php
header("Location: $redirect_url");
exit();
?>
