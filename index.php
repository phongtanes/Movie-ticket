<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db.php';
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

<?php include "nav.php"; ?>


    <section class="movies">                                                                                                                    
        <h2>ภาพยนตร์แนะนำ</h2>
        <div class="movie-list">
            <?php
            $sql = "SELECT id, title, poster, release_date FROM movies";
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0):
                while ($row = $result->fetch_assoc()): ?>
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
                <?php endwhile;
            endif;
            ?>
        </div>
    </section>

    <footer>
        <?php include "footer.php"; ?>
    </footer>
</body>
</html>
