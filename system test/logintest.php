<?php
session_start();
include 'testdb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // ตรวจสอบว่าอีเมลมีอยู่ในฐานข้อมูลหรือไม่
    $stmt = $conn->prepare("SELECT id, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        // ดึงข้อมูลรหัสผ่านที่เก็บในฐานข้อมูล
        $stmt->bind_result($id, $hashed_password, $role);
        $stmt->fetch();
        
        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $id;
            $_SESSION['user_role'] = $role;
            
            echo "<script>alert('เข้าสู่ระบบสำเร็จ!'); window.location.href='welcome.php';</script>";
        } else {
            echo "<script>alert('รหัสผ่านไม่ถูกต้อง'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('ไม่พบผู้ใช้งานนี้'); window.location.href='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>เข้าสู่ระบบ</h2>
    <form action="login.php" method="POST">
        <label for="email">อีเมล:</label>
        <input type="email" name="email" required><br><br>
        
        <label for="password">รหัสผ่าน:</label>
        <input type="password" name="password" required><br><br>
        
        <button type="submit">เข้าสู่ระบบ</button>
    </form>
</body>
</html>
