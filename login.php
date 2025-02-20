<?php
session_start();
include 'db.php';

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!$conn) {
        echo json_encode(["status" => "error", "message" => "Database connection failed!"]);
        exit();
    }

    // ตรวจสอบค่าที่ส่งมาจากฟอร์ม
    if (!isset($_POST['email'], $_POST['password'])) {
        echo json_encode(["status" => "error", "message" => "กรุณากรอกข้อมูลให้ครบ"]);
        exit();
    }

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        echo json_encode(["status" => "error", "message" => "กรุณากรอกข้อมูลให้ครบ"]);
        exit();
    }

    // ตรวจสอบอีเมลในฐานข้อมูล
    $stmt = $conn->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $user_name, $hashed_password, $role);
        $stmt->fetch();

        // ตรวจสอบรหัสผ่าน
        if (password_verify($password, $hashed_password)) {
            // เก็บข้อมูลผู้ใช้ใน session
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name;
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;

            echo json_encode(["status" => "success", "message" => "เข้าสู่ระบบสำเร็จ!"]);
            exit();
        } else {
            echo json_encode(["status" => "error", "message" => "รหัสผ่านไม่ถูกต้อง!"]);
            exit();
        }
    } else {
        echo json_encode(["status" => "error", "message" => "ไม่พบอีเมลนี้!"]);
        exit();
    }
}
?>
