<?php
session_start();
include 'db.php';

// ดึงข้อมูลภาพยนตร์จากฐานข้อมูล
$sql = "SELECT id, title, description, release_date, duration, poster FROM movies";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Booking</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .movies h1{
            font-size: 33px;
        }
    </style>
</head>
<body>
        <?php include "nav.php"; ?>
    
    <section class="movies">
    <h1>กำลังฉาย</h1>
    <div class="movie-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="movie-container">
                <div class="movie">
                    <img src="<?= htmlspecialchars($row['poster']); ?>" alt="<?= htmlspecialchars($row['title']); ?>">
                    <a href="movie_detail.php?id=<?= $row['id']; ?>" class="view-more">ดูเพิ่มเติม</a>
                </div>
                <div class="movie-info">
                    <h4><?= htmlspecialchars($row['release_date']); ?></h4>
                    <h3><?= htmlspecialchars($row['title']); ?></h3>
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
