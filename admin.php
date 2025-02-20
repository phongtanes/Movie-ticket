<?php
require 'db.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php");
    exit();
}


// เพิ่มภาพยนตร์
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = htmlspecialchars($_POST['title']);  // ป้องกัน XSS
    $description = htmlspecialchars($_POST['description']);  // ป้องกัน XSS
    $release_date = $_POST['release_date'];
    $duration = (int)$_POST['duration'];  // แปลงเป็นตัวเลข
    $poster = filter_var($_POST['poster'], FILTER_SANITIZE_URL);  // ตรวจสอบ URL
    $trailer_url = filter_var($_POST['trailer_url'], FILTER_SANITIZE_URL);  // ตรวจสอบ URL

    // ตรวจสอบว่า poster เป็น URL ที่ถูกต้อง
    if (!filter_var($poster, FILTER_VALIDATE_URL)) {
        echo "URL ของโปสเตอร์ไม่ถูกต้อง";
        exit();
    }
    if (!filter_var($trailer_url, FILTER_VALIDATE_URL)) {
        echo "URL ของวิดีโอไม่ถูกต้อง";
        exit();
    }

    $sql = "INSERT INTO movies (title, description, release_date, duration, poster, trailer_url) 
        VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $title, $description, $release_date, $duration, $poster, $trailer_url);
    $stmt->execute();


    header("Location: admin.php");
    exit();
}

// ดึงข้อมูลภาพยนตร์
$result = $conn->query("SELECT * FROM movies");
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - จัดการภาพยนตร์</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="admin.css">
</head>
<body>
<?php include "nav.php"; ?>
    <h2>เพิ่มภาพยนตร์</h2>
    <form action="admin.php" method="post" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="ชื่อภาพยนตร์" required>
        <textarea name="description" placeholder="รายละเอียด" required></textarea>
        <input type="date" name="release_date" required>
        <input type="number" name="duration" placeholder="ความยาว (นาที)" required>
        <input type="text" name="poster" placeholder="URL รูปภาพโปสเตอร์" required>
        <input type="text" name="trailer_url" placeholder="URL ตัวอย่างหนัง" required>
        <button type="submit">เพิ่ม</button>
    </form>

    <h2>รายการภาพยนตร์</h2>
    <table>
        <tr>
            <th>ชื่อ</th>
            <th>วันที่ฉาย</th>
            <th>ความยาว</th>
            <th>โปสเตอร์</th>
            <th>จัดการ</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['release_date']) ?></td>
            <td><?= htmlspecialchars($row['duration']) ?> นาที</td>
            <td><img src="<?= htmlspecialchars($row['poster']) ?>" width="50"></td>
            <td>
                <a href="edit.php?id=<?= $row['id'] ?>">แก้ไข</a> |
                <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('แน่ใจหรือไม่?');">ลบ</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <div class="button-container">
        <button onclick="window.history.back()">ย้อนกลับ</button>
    </div>
    
    <footer><?php include 'footer.php';?></footer>
</body>
</html>
