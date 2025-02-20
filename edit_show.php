<?php
session_start();
include 'db.php';

if (!isset($_GET['id'])) {
    die("ไม่พบข้อมูลรอบฉาย");
}

$show_id = $_GET['id'];

// ดึงข้อมูลรอบฉายที่ต้องการแก้ไข
$sql = "SELECT * FROM shows WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $show_id);
$stmt->execute();
$result = $stmt->get_result();
$show = $result->fetch_assoc();

if (!$show) {
    die("ไม่พบรอบฉายนี้");
}

// ดึงข้อมูลหนังและโรงภาพยนตร์
$movies = $conn->query("SELECT id, title FROM movies");
$theaters = $conn->query("SELECT id, name FROM theaters");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $movie_id = $_POST['movie_id'];
    $theater_id = $_POST['theater_id'];
    $show_date = $_POST['show_date'];
    $show_time = $_POST['show_time'];

    // Validate input
    if (empty($movie_id) || empty($theater_id) || empty($show_date) || empty($show_time)) {
        echo "กรุณากรอกข้อมูลให้ครบถ้วน";
    } else {
        // อัปเดตรอบฉาย
        $update_sql = "UPDATE shows SET 
                        movie_id = ?, 
                        theater_id = ?, 
                        show_date = ?, 
                        show_time = ? 
                      WHERE id = ?";
        
        $stmt_update = $conn->prepare($update_sql);
        $stmt_update->bind_param("iissi", $movie_id, $theater_id, $show_date, $show_time, $show_id);
        
        if ($stmt_update->execute()) {
            echo "อัปเดตสำเร็จ!";
            header("Location: cinema.php"); // กลับไปหน้าหลัก
            exit;
        } else {
            echo "เกิดข้อผิดพลาด: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขรอบฉาย</title>
</head>
<body>

<?php include "nav.php"; ?>

<h2>แก
