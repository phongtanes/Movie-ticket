<?php 
session_start();
include 'db.php';

$movie_id = isset($_GET['movie_id']) ? (int)$_GET['movie_id'] : 0;

$sql = "SELECT shows.id, shows.show_date, shows.show_time, 
               movies.title AS movie_title, movies.poster, movies.duration,
               theaters.name AS theater_name
        FROM shows
        JOIN movies ON shows.movie_id = movies.id
        JOIN theaters ON shows.theater_id = theaters.id
        WHERE movies.id = ?
        ORDER BY shows.show_date, shows.show_time";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $movie_id);
$stmt->execute();
$result = $stmt->get_result();

$movies = [];
while ($row = $result->fetch_assoc()) { 
    $movieTitle = $row['movie_title'];

    if (!isset($movies[$movieTitle])) {
        $movies[$movieTitle] = [
            'poster' => $row['poster'],
            'duration' => $row['duration'],
            'shows' => []
        ];
    }

    $movies[$movieTitle]['shows'][] = [
        'id' => $row['id'],
        'theater' => $row['theater_name'],
        'date' => $row['show_date'],
        'time' => $row['show_time']
    ];
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รอบฉาย</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include "nav.php"; ?>

<section class="showtimes">
    <div class="showtime-container">
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $title => $movie): ?>
                <div class="movie-box">
                    <img src="<?= htmlspecialchars($movie['poster']); ?>" alt="<?= htmlspecialchars($title); ?>" class="movie-poster">
                    <div class="movie-details">
                        <h3><?= htmlspecialchars($title); ?></h3>
                        <p>ความยาว: <?= htmlspecialchars($movie['duration']); ?> นาที</p>
                    </div>
                </div>
                <h4>รอบฉายภาพยนตร์</h4>
                <div class="showtimes-row">
                    <?php foreach ($movie['shows'] as $show): ?>
                        <a href="booking.php?show_id=<?= $show['id']; ?>" class="showtime-box">
                            <?= htmlspecialchars($show['theater']); ?> | <?= htmlspecialchars($show['time']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>ไม่มีรอบฉายสำหรับภาพยนตร์นี้</p>
        <?php endif; ?>
    </div>
</section>

<footer>
    <?php include "footer.php"; ?>
</footer>

</body>
</html>