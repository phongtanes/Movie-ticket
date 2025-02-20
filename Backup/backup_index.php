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
    
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="index.php">หน้าแรก</a></li>
                <li><a href="movies.php">ภาพยนตร์</a></li>
                <li><a href="#">โรงภาพยนตร์</a></li>
                <li><a href="#">โปรโมชั่น</a></li>
                <li><a href="#">ติดต่อ</a></li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li><a href="profile.php">👤 <?php echo $_SESSION['user_name']; ?></a></li>
                    <li><a href="logout.php">ออกจากระบบ</a></li>
                <?php else: ?>
                    <li><a href="login.php">เข้าสู่ระบบ</a></li>
                    <li><a href="register.php">สมัครสมาชิก</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>
    
    <section class="movies">
    <h2>ภาพยนตร์แนะนำ</h2>
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
        <p>&copy; 2025 Movie Booking. All Rights Reserved.</p>
    </footer>
</body>
</html>
