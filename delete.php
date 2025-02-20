<?php
require 'db.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $movie_id = (int)$_GET['id'];
    
    // ตรวจสอบว่ามีภาพยนตร์นี้อยู่จริงหรือไม่
    $check_sql = "SELECT id FROM movies WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $movie_id);
    $check_stmt->execute();
    $check_stmt->store_result();
    
    if ($check_stmt->num_rows > 0) {
        // ลบภาพยนตร์ออกจากฐานข้อมูล
        $sql = "DELETE FROM movies WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $movie_id);
        
        if ($stmt->execute()) {
            echo "<script>alert('ลบภาพยนตร์สำเร็จ'); window.location.href='admin.php';</script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการลบ'); window.location.href='admin.php';</script>";
        }
    } else {
        echo "<script>alert('ไม่พบภาพยนตร์ที่ต้องการลบ'); window.location.href='admin.php';</script>";
    }
} else {
    echo "<script>alert('ไม่มีข้อมูลภาพยนตร์ที่ถูกส่งมา'); window.location.href='admin.php';</script>";
}
?>