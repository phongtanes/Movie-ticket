<?php
session_start();
include 'db.php';

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือยัง
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // ถ้ายังไม่ได้ล็อกอินให้ไปหน้า login
    exit();
}

$user_id = $_SESSION['user_id'];

// ดึงข้อมูลผู้ใช้จากฐานข้อมูล
$stmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($name, $email, $role);
    $stmt->fetch();
    // เก็บข้อมูล role ใน session เพื่อใช้งานในส่วนอื่น
    $_SESSION['role'] = $role;
} else {
    echo "ไม่พบข้อมูลผู้ใช้";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ของคุณ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- ใช้ Navbar จากไฟล์ nav.php -->
    <?php include 'nav.php'; ?>

    <section class="profile">
        <h2>โปรไฟล์ของคุณ</h2>
        <p><strong>ชื่อ:</strong> <?= htmlspecialchars($name); ?></p>
        <p><strong>อีเมล:</strong> <?= htmlspecialchars($email); ?></p>
        <p><strong>สถานะผู้ใช้:</strong> <?= htmlspecialchars($role); ?></p>

        <!-- ตรวจสอบว่าเป็น Admin หรือไม่ -->
        <?php if ($_SESSION['role'] === 'Admin'): ?>
            <a href="admin.php" class="btn btn-success">จัดการภาพยนตร์</a><br>
            <a href="cinema_admin.php" class="btn btn-success">จัดการโรงภาพยนตร์</a><br>
            <a href="cinema.php" class="btn btn-success">รอบฉายทั้งหมด</a><br>
        <?php endif; ?>

    </section>

    <footer>
        <?php include "footer.php"; ?>
    </footer>

</body>
</html>
