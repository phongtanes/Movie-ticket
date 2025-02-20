<?php
require 'db.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM movies WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$movie = $result->fetch_assoc();

if (!$movie) {
    echo "<h2>ไม่พบข้อมูลภาพยนตร์</h2>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($movie['title']); ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include "nav.php"; ?>
    <div class="movie-detail">
        <img src="<?= htmlspecialchars($movie['poster']); ?>" alt="<?= htmlspecialchars($movie['title']); ?>">
        <div class="details">
            <h2><?= htmlspecialchars($movie['title']); ?></h2>
            <p><strong>วันที่ฉาย:</strong> <?= htmlspecialchars($movie['release_date']); ?></p>
            <p><strong>ความยาว:</strong> <?= htmlspecialchars($movie['duration']); ?> นาที</p>
            <p><?= htmlspecialchars($movie['description']); ?></p>
            <a href="showtimes.php?movie_id=<?= $movie['id']; ?>" class="book-now">ดูรอบฉาย</a>
        </div>
    </div>

    <!-- เพิ่มตัวอย่างหนัง -->
    <?php
if (!empty($movie['trailer_url'])):
    // แปลง URL จาก https://www.youtube.com/watch?v=VIDEO_ID เป็น https://www.youtube.com/embed/VIDEO_ID
    $trailer_url = $movie['trailer_url'];
    if (strpos($trailer_url, 'youtube.com') !== false) {
        $trailer_url = preg_replace("/(?:https?:\/\/(?:www\.)?youtube\.com\/(?:watch\?v=|embed\/))([a-zA-Z0-9_-]+)/", "https://www.youtube.com/embed/$1", $trailer_url);
    }
?>
    <div class="trailer">
        <h3>ตัวอย่างหนัง</h3>
        <iframe width="560" height="315" src="<?= htmlspecialchars($trailer_url); ?>" frameborder="0" allowfullscreen></iframe>
    </div>
<?php endif; ?>

<footer>
    <?php include "footer.php"; ?>
</footer>

</body>
</html>
