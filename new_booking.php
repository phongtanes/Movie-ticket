<?php 
session_start();
include 'db.php';

$show_id = isset($_GET['show_id']) ? (int)$_GET['show_id'] : 0;

$sql = "SELECT shows.id, shows.show_time, movies.title AS movie_title, movies.poster, movies.duration,
               theaters.name AS theater_name
        FROM shows
        JOIN movies ON shows.movie_id = movies.id
        JOIN theaters ON shows.theater_id = theaters.id
        WHERE shows.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $show_id);
$stmt->execute();
$result = $stmt->get_result();
$show = $result->fetch_assoc();
$stmt->close();

if (!$show) {
    die("ไม่พบรอบฉายที่เลือก");
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จองตั๋ว</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include "nav.php"; ?>

<section class="booking">
    <h2>เลือกที่นั่ง</h2>
    <div class="screen">SCREEN</div>
    <div class="seating-chart">
        <?php for ($row = 'A'; $row <= 'L'; $row++): ?>
            <div class="seat-row">
                <?php for ($col = 1; $col <= 10; $col++): ?>
                    <button class="seat" data-seat="<?= $row . $col; ?>"></button>
                <?php endfor; ?>
            </div>
        <?php endfor; ?>
    </div>
    <form action="confirm_booking.php" method="POST">
        <input type="hidden" name="show_id" value="<?= $show['id']; ?>">
        <input type="hidden" name="selected_seats" id="selected_seats">
        <button type="submit" class="btn btn-primary">ยืนยันการจอง</button>
    </form>
</section>

<aside class="summary">
    <h3>สรุปการจอง</h3>
    <img src="<?= htmlspecialchars($show['poster']); ?>" alt="<?= htmlspecialchars($show['movie_title']); ?>">
    <p><strong><?= htmlspecialchars($show['movie_title']); ?></strong></p>
    <p>⏳ ระยะเวลา: <?= htmlspecialchars($show['duration']); ?> นาที</p>
    <p>โรง: <?= htmlspecialchars($show['theater_name']); ?></p>
    <p>เวลา: <?= htmlspecialchars($show['show_time']); ?></p>
</aside>

<footer>
    <?php include "footer.php"; ?>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        let selectedSeats = [];
        document.querySelectorAll('.seat').forEach(seat => {
            seat.addEventListener('click', function() {
                this.classList.toggle('selected');
                let seatNumber = this.getAttribute('data-seat');
                if (selectedSeats.includes(seatNumber)) {
                    selectedSeats = selectedSeats.filter(s => s !== seatNumber);
                } else {
                    selectedSeats.push(seatNumber);
                }
                document.getElementById('selected_seats').value = selectedSeats.join(',');
            });
        });
    });
</script>

</body>
</html>
