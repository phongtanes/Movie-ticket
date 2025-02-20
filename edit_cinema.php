<?php
session_start();
include 'db.php';

// ตรวจสอบว่าเป็น Admin หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php"); // หากไม่ใช่ Admin ให้กลับไปหน้าแรก
    exit();
}

// ตรวจสอบว่ามี ID โรงภาพยนตร์ที่ต้องการแก้ไขหรือไม่
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: cinema_admin.php");
    exit();
}

$cinema_id = intval($_GET['id']);

// ดึงข้อมูลโรงภาพยนตร์จากฐานข้อมูล
$sql = "SELECT id, name, location FROM theaters WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cinema_id);
$stmt->execute();
$result = $stmt->get_result();
$cinema = $result->fetch_assoc();

if (!$cinema) {
    echo "ไม่พบข้อมูลโรงภาพยนตร์";
    exit();
}

// อัปเดตข้อมูลโรงภาพยนตร์
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_cinema'])) {
    $name = htmlspecialchars($_POST['name']);
    $location = htmlspecialchars($_POST['location']);

    $update_sql = "UPDATE theaters SET name = ?, location = ? WHERE id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssi", $name, $location, $cinema_id);
    
    if ($update_stmt->execute()) {
        echo "อัปเดตข้อมูลสำเร็จ!";
        header("Location: cinema_admin.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาดในการอัปเดตข้อมูล!";
    }
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขโรงภาพยนตร์</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include "nav.php"; ?>

<section class="edit-cinema">
    <h2>แก้ไขโรงภาพยนตร์</h2>
    <form action="" method="POST">
        <div class="mb-3">
            <label>ชื่อโรงภาพยนตร์</label>
            <input type="text" name="name" value="<?= htmlspecialchars($cinema['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label>สถานที่</label>
            <input type="text" name="location" value="<?= htmlspecialchars($cinema['location']); ?>" required>
        </div>
        <button type="submit" name="update_cinema" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
    </form>
</section>

<footer>
    <?php include "footer.php"; ?>
</footer>

</body>
</html>