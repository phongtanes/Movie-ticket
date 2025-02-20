<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('กรุณาเข้าสู่ระบบก่อนทำการจอง'); window.history.back();</script>";
    exit;
}

if (!isset($_POST['show_id'], $_POST['seat_numbers'])) {
    echo "<script>alert('ข้อมูลไม่ครบ กรุณาเลือกที่นั่ง'); window.history.back();</script>";
    exit;
}

$show_id = intval($_POST['show_id']);
$user_id = intval($_SESSION['user_id']);
$seat_numbers = explode(",", $_POST['seat_numbers']);

if (empty($seat_numbers)) {
    echo "<script>alert('กรุณาเลือกที่นั่งอย่างน้อย 1 ที่'); window.history.back();</script>";
    exit;
}

// ตรวจสอบว่ารอบฉายมีอยู่จริง
$stmt = $conn->prepare("SELECT id, movie_id, theater_id, show_time FROM shows WHERE id = ?");
$stmt->bind_param("i", $show_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo "<script>alert('ไม่พบรอบฉายที่เลือก'); window.history.back();</script>";
    exit;
}
$show = $result->fetch_assoc();
$stmt->close();

// ตรวจสอบว่าที่นั่งว่างอยู่
$placeholders = implode(",", array_fill(0, count($seat_numbers), "?"));
$params = array_merge([$show_id], $seat_numbers);
$types = "i" . str_repeat("s", count($seat_numbers));

$sql = "SELECT seat_number FROM seats WHERE show_id = ? AND seat_number IN ($placeholders) AND status = 'booked'";
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $booked_seats = [];
    while ($row = $result->fetch_assoc()) {
        $booked_seats[] = $row['seat_number'];
    }
    echo "<script>alert('ที่นั่ง " . implode(", ", $booked_seats) . " ถูกจองไปแล้ว'); window.history.back();</script>";
    exit;
}
$stmt->close();

// บันทึกการจอง
$stmt = $conn->prepare("INSERT INTO bookings (show_id, user_id, seat_number) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $show_id, $user_id, $seat);
foreach ($seat_numbers as $seat) {
    $stmt->execute();
}
$stmt->close();

// อัปเดตสถานะที่นั่ง
$stmt = $conn->prepare("UPDATE seats SET status = 'booked' WHERE show_id = ? AND seat_number = ?");
$stmt->bind_param("is", $show_id, $seat);
foreach ($seat_numbers as $seat) {
    $stmt->execute();
}
$stmt->close();

// ดึง LINE Token
$line_token = $_SESSION['line_token'] ?? '';
if (empty($line_token)) {
    $stmt = $conn->prepare("SELECT line_token FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $line_token = $row['line_token'];
        $_SESSION['line_token'] = $line_token;
    }
    $stmt->close();
}

// ดึงชื่อหนังและโรงภาพยนตร์
$stmt = $conn->prepare("SELECT title FROM movies WHERE id = ?");
$stmt->bind_param("i", $show['movie_id']);
$stmt->execute();
$movie_title = $stmt->get_result()->fetch_assoc()['title'];
$stmt->close();

$stmt = $conn->prepare("SELECT name FROM theaters WHERE id = ?");
$stmt->bind_param("i", $show['theater_id']);
$stmt->execute();
$theater_name = $stmt->get_result()->fetch_assoc()['name'];
$stmt->close();

$stmt = $conn->prepare("SELECT name FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_name = $stmt->get_result()->fetch_assoc()['name'];
$stmt->close();

// ส่งแจ้งเตือน LINE Notify
if (!empty($line_token)) {
    function sendLineNotify($token, $message) {
        $line_api = 'https://notify-api.line.me/api/notify';
        $data = ['message' => $message];
        $headers = ['Content-Type: multipart/form-data', 'Authorization: Bearer ' . $token];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $line_api);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    $message = "🎟 จองตั๋วสำเร็จ!\n\n"
        . "ชื่อผู้จอง: $user_name\n"
        . "หนังที่จอง: $movie_title\n"
        . "รอบ: " . date('Y-m-d H:i', strtotime($show['show_time'])) . "\n"
        . "โรง: $theater_name\n"
        . "ที่นั่ง: " . implode(", ", $seat_numbers);

    sendLineNotify($line_token, $message);
}

$conn->close();

echo "<script>alert('จองตั๋วสำเร็จ! ข้อมูลของตั๋วจะถูกส่งไปยัง LINE ของคุณ'); window.history.back();</script>";
?>
