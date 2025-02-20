$line_token = $_SESSION['line_token']; // ดึง Line Token ของผู้ใช้
$message = "การจองของคุณสำเร็จ: \nภาพยนตร์: $movie_title\nวันที่: $show_date\nรอบ: $show_time\nที่นั่ง: $seat";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://notify-api.line.me/api/notify");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "message=$message");
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $line_token));
curl_exec($ch);
curl_close($ch);
