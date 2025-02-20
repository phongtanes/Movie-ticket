<?php
session_start();
include 'testdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $line_token = trim($_POST['line_token']);
    
    // ตรวจสอบว่าอีเมลมีอยู่แล้วหรือไม่
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "<script>alert('อีเมลนี้ถูกใช้ไปแล้ว!'); window.location.href='indextest.php';</script>";
        exit();
    }
    
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, line_token, role) VALUES (?, ?, ?, ?, 'User')");
    $stmt->bind_param("ssss", $name, $email, $password, $line_token);
    
    if ($stmt->execute()) {
        echo "<script>alert('สมัครสมาชิกสำเร็จ! กรุณาเข้าสู่ระบบ'); window.location.href='logtest.php';</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด กรุณาลองใหม่'); window.location.href='indextest.php';</script>";
    }
}
?>
