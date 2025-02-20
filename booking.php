<?php
session_start();
include 'db.php';

if (!isset($_GET['show_id'])) {
    die("ไม่พบรอบฉาย");
}
$show_id = intval($_GET['show_id']);

// ดึงข้อมูลที่นั่งจากฐานข้อมูล
$sql = "SELECT seat_number, status FROM seats WHERE show_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $show_id);
$stmt->execute();
$result = $stmt->get_result();

$seats = [];
while ($row = $result->fetch_assoc()) {
    $seats[$row['seat_number']] = $row['status'];
}

$sql = "SELECT movies.title, movies.poster, theaters.name AS theater_name, shows.show_time 
        FROM shows 
        JOIN movies ON shows.movie_id = movies.id 
        JOIN theaters ON shows.theater_id = theaters.id 
        WHERE shows.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $show_id);
$stmt->execute();
$movie_data = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกที่นั่ง</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .seat { font-size: 24px; cursor: pointer; margin: 5px; }
        .available { color: purple; }
        .booked { color: grey; cursor: not-allowed; pointer-events: none; }
        .selected { color: blue; }
        .seat-layout { display: flex; flex-direction: column-reverse; }
        .summary { position: fixed; right: 20px; top: 100px; width: 300px; border: 1px solid #ccc; padding: 15px; text-align: center; }
        .poster { width: 100%; border-radius: 10px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <?php include 'nav.php'; ?>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <h1 class="text-center">เลือกที่นั่ง</h1>
                <div class="screen text-white text-center" style="width: 100%; height: 35px; background:#6e6e6e;">SCREEN</div>
                <div class="seat-layout text-center mt-3">
    <?php
    $rows = range('A', 'L'); // แถว A ถึง L (จากบนลงล่าง)
    $cols = range(1, 10);
    foreach ($rows as $row) { // แสดงแถวจาก A ถึง L (จากบนลงล่าง)
        echo "<div class='d-flex align-items-center justify-content-center'>";
        echo "<span class='mr-2'>$row</span>";
        foreach ($cols as $col) {
            $seat_id = "$row$col";
            $status = isset($seats[$seat_id]) ? $seats[$seat_id] : 'available';
            $class = ($status == 'booked') ? 'booked' : 'available';
            echo "<i class='fa-solid fa-couch seat $class' data-seat='$seat_id'></i>";
        }
        echo "<span class='ml-2'>$row</span></div>";
    }
    ?>
</div>
            </div>
            <div class="col-md-4">
                <div class="summary">
                    <img src="<?php echo htmlspecialchars($movie_data['poster']); ?>" alt="โปสเตอร์ <?php echo htmlspecialchars($movie_data['title']); ?>" class="poster">
                    <h5><?php echo htmlspecialchars($movie_data['title']); ?></h5>
                    <p>โรง: <?php echo htmlspecialchars($movie_data['theater_name']); ?></p>
                    <p>เวลา: <?php echo date('d/m/Y H:i', strtotime($movie_data['show_time'])); ?></p>
                    <hr>
                    <p id="selected-seats"></p>
                    <p id="total-price"></p>
                    <form action="process_booking.php" method="POST" id="booking-form" style="display: none;">
                        <input type="hidden" name="show_id" value="<?php echo $show_id; ?>">
                        <input type="hidden" name="seat_numbers" id="selected_seats" required>
                        <button type="submit" class="btn btn-primary btn-block">ยืนยันการจอง</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        let selectedSeats = [];
        document.querySelectorAll('.seat.available').forEach(seat => {
            seat.addEventListener('click', function() {
                const seatId = this.dataset.seat;
                if (selectedSeats.includes(seatId)) {
                    selectedSeats = selectedSeats.filter(s => s !== seatId);
                    this.classList.remove('selected');
                } else {
                    selectedSeats.push(seatId);
                    this.classList.add('selected');
                }
                document.getElementById('selected_seats').value = selectedSeats.join(',');
                document.getElementById('selected-seats').innerText = 'ที่นั่ง: ' + selectedSeats.join(', ');
                document.getElementById('total-price').innerText = 'ราคา: ' + (selectedSeats.length * 150) + ' บาท';
                document.getElementById('booking-form').style.display = selectedSeats.length > 0 ? 'block' : 'none';
            });
        });
    });
    </script>
    <footer><?php include 'footer.php'; ?></footer>
</body>
</html>
