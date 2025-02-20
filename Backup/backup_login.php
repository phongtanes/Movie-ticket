<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo "<script>alert('กรุณากรอกอีเมลและรหัสผ่าน');</script>";
    } else {
        $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($id, $name, $hashed_password, $role);
            $stmt->fetch();

            if (password_verify($password, $hashed_password)) {
                // เก็บข้อมูลลง session
                $_SESSION['user_id'] = $id;
                $_SESSION['user_name'] = $name;
                $_SESSION['user_role'] = $role;

                echo "<script>alert('เข้าสู่ระบบสำเร็จ');</script>";
                header("Location: index.php");
                exit();
            } else {
                echo "<script>alert('รหัสผ่านไม่ถูกต้อง');</script>";
            }
        } else {
            echo "<script>alert('ไม่มีอีเมลนี้ในระบบ');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ</title>
    <link rel="stylesheet" href="login.css">
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
            <label for="email">Email</label>
            <input type="email" name="email" required>

            <label for="password">Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </center>
</body>
</html>
