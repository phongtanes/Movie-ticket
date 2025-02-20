<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ตรวจสอบการเชื่อมต่อฐานข้อมูล
    if (!$conn) {
        die("Database connection failed!");
    }

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $line_token = trim($_POST['line_token']);
    $role = "User"; // ค่า role ถูกกำหนดตายตัว ไม่ต้องรับจากฟอร์ม

    // ตรวจสอบว่าฟิลด์ไม่ว่างเปล่า
    if (empty($name) || empty($email) || empty($password) || empty($line_token)) {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบถ้วน');</script>";
    } else {
        // Hash รหัสผ่าน
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // ตรวจสอบว่ามีชื่อหรืออีเมลซ้ำหรือไม่
        $stmt = $conn->prepare("SELECT id FROM users WHERE name = ? OR email = ?");
        $stmt->bind_param("ss", $name, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo "<script>alert('มีชื่อหรืออีเมลนี้อยู่แล้ว');</script>";
        } else {
            // บันทึกลงฐานข้อมูล
            $sql = "INSERT INTO users (name, email, password, line_token, role) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $name, $email, $hashed_password, $line_token, $role);

            if ($stmt->execute()) {
                echo "<script>alert('สมัครสมาชิกสำเร็จ');</script>";
                header("Location: login.php");
                exit();
            } else {
                echo "<script>alert('ไม่สามารถสมัครสมาชิกได้');</script>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก</title>
    <link rel="stylesheet" href="reg.css">
</head>
<style>
    label, button {
        display: block;
        margin-top: 10px;
    }
</style>
<body>
    <center>
        <form action="" method="post">
            <label for="name">Name</label>
            <input type="text" name="name" required>
            
            <label for="email">Email</label>
            <input type="email" name="email" required>
            
            <label for="password">Password</label>
            <input type="password" name="password" required>
            
            <label for="line_token">Line Token</label>
            <input type="text" name="line_token" required>
            
            <label for="role">Role</label>
            <input type="text" name="role" value="User" readonly>
            
            <button type="submit">Register</button>
        </form>
    </center>
</body>
</html>
