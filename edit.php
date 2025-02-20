<?php
require 'db.php';

session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// ตรวจสอบว่ามีการส่งค่า id มาหรือไม่
if (!isset($_GET['id'])) {
    die("ไม่พบรหัสภาพยนตร์");
}

$id = $_GET['id'];

// ดึงข้อมูลภาพยนตร์จากฐานข้อมูล
$sql = "SELECT * FROM movies WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if (!$movie) {
    die("ไม่พบภาพยนตร์ที่ต้องการแก้ไข");
}

// อัปเดตข้อมูลภาพยนตร์เมื่อกดบันทึก
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $release_date = $_POST['release_date'];
    $duration = $_POST['duration'];
    $poster = $_POST['poster'];
    $trailer_url = $_POST['trailer_url'];

    // แก้ไข SQL query ให้ตรงกับจำนวน parameter ที่ต้องการ
    $update_sql = "UPDATE movies SET title=?, description=?, release_date=?, duration=?, poster=?, trailer_url=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sssissi", $title, $description, $release_date, $duration, $poster, $trailer_url, $id);
    $stmt->execute();

    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขภาพยนตร์</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>
    <h2>แก้ไขภาพยนตร์</h2>
    <form action="edit.php?id=<?= $id ?>" method="post">
        <input type="text" name="title" value="<?= htmlspecialchars($movie['title']) ?>" required>
        <textarea name="description" required><?= htmlspecialchars($movie['description']) ?></textarea>
        <input type="date" name="release_date" value="<?= $movie['release_date'] ?>" required>
        <input type="number" name="duration" value="<?= $movie['duration'] ?>" required>
        <input type="text" name="poster" value="<?= htmlspecialchars($movie['poster']) ?>" required>
        <input type="text" name="trailer_url" value="<?= htmlspecialchars($movie['trailer_url']) ?>" required>
        <button type="submit">บันทึก</button>
        <a href="admin.php">ยกเลิก</a>
    </form>
</body>
</html>
