<?php
session_start();
include 'db.php';

// ตรวจสอบว่าเป็น Admin หรือไม่
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php"); // หากไม่ใช่ Admin ให้กลับไปหน้าแรก
    exit();
}

// การเพิ่มโรงภาพยนตร์ใหม่
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_cinema'])) {
    $name = htmlspecialchars($_POST['name']);
    $location = htmlspecialchars($_POST['location']);

    // ตรวจสอบว่ามีข้อมูลครบถ้วนหรือไม่
    if (empty($name) || empty($location)) {
        $error_message = "กรุณากรอกข้อมูลให้ครบถ้วน!";
    } else {
        $sql = "INSERT INTO theaters (name, location) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $location);
        if ($stmt->execute()) {
            $success_message = "เพิ่มโรงภาพยนตร์ใหม่สำเร็จ!";
        } else {
            $error_message = "เกิดข้อผิดพลาดในการเพิ่มโรงภาพยนตร์!";
        }
    }
}

// ดึงข้อมูลโรงภาพยนตร์จากฐานข้อมูล
$sql = "SELECT id, name, location FROM theaters";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการโรงภาพยนตร์</title>
    <link rel="stylesheet" href="styles.css">
    
</head>
<body>

<?php include "nav.php"; ?>

<section class="cinemas">
    <h2>จัดการโรงภาพยนตร์</h2>

    <!-- ฟอร์มสำหรับเพิ่มโรงภาพยนตร์ -->
    <h3>เพิ่มโรงภาพยนตร์ใหม่</h3>
    <form action="cinema_admin.php" method="POST">
        <div class="mb-3">
            <input type="text" name="name" placeholder="ชื่อโรงภาพยนตร์" required>
        </div>
        <div class="mb-3">
            <input type="text" name="location" placeholder="สถานที่" required>
        </div>
        <button type="submit" name="add_cinema" class="btn btn-primary">เพิ่มโรงภาพยนตร์</button>
    </form>

    <!-- แสดงข้อความผลลัพธ์ -->
    <?php if (isset($success_message)): ?>
        <div class="alert alert-success"><?= $success_message; ?></div>
    <?php elseif (isset($error_message)): ?>
        <div class="alert alert-danger"><?= $error_message; ?></div>
    <?php endif; ?>

    <!-- แสดงรายการโรงภาพยนตร์ -->
    <h3>รายการโรงภาพยนตร์</h3>
    <div class="cinema-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="cinema-container">
                <div class="cinema">
                    <h4><?= htmlspecialchars($row['name']); ?></h4>
                    <p>สถานที่: <?= htmlspecialchars($row['location']); ?></p>
                </div>
                <!-- ลิงก์สำหรับการแก้ไขและลบข้อมูล -->
                <div class="actions">
                    <a href="edit_cinema.php?id=<?= $row['id']; ?>" class="btn btn-primary">แก้ไข</a>
                    <!-- <a href="delete_cinema.php?id=<?= $row['id']; ?>" onclick="return confirm('คุณต้องการลบโรงภาพยนตร์นี้จริงหรือไม่?');">ลบ</a> -->
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<footer>
    <?php include "footer.php"; ?>
</footer>

</body>
</html>
