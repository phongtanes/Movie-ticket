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
    if (!isset($_POST['name'], $_POST['email'], $_POST['password'], $_POST['line_token'])) {
        echo json_encode(["status" => "error", "message" => "กรุณากรอกข้อมูลให้ครบ"]);
        exit();
    }

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $line_token = trim($_POST['line_token']);
    $role = "User";

    if (empty($name) || empty($email) || empty($password) || empty($line_token)) {
        echo json_encode(["status" => "error", "message" => "กรุณากรอกข้อมูลให้ครบ"]);
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // ตรวจสอบอีเมลซ้ำ
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["status" => "error", "message" => "อีเมลนี้ถูกใช้งานแล้ว"]);
    } else {
        // เพิ่มข้อมูลลงฐานข้อมูล
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, line_token, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $hashed_password, $line_token, $role);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "สมัครสมาชิกสำเร็จ"]);
        } else {
            echo json_encode(["status" => "error", "message" => "เกิดข้อผิดพลาดในการสมัครสมาชิก"]);
        }
    }
}
?>
