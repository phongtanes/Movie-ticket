<?php
include 'db.php';

$show_id = isset($_GET['show_id']) ? (int)$_GET['show_id'] : 0;

$sql = "SELECT seat_number, status FROM seats WHERE show_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $show_id);
$stmt->execute();
$result = $stmt->get_result();

$seats = [];
while ($row = $result->fetch_assoc()) {
    $seats[$row['seat_number']] = $row['status'];
}
$stmt->close();
?>

<div class="seat-layout">
    <?php if (!empty($seats)): ?>
        <div class="screen">📺 SCREEN</div>
        <div class="seat-grid">
            <?php foreach ($seats as $seat => $status): ?>
                <button class="seat <?= $status === 'booked' ? 'booked' : 'available'; ?>" 
                        <?= $status === 'booked' ? 'disabled' : ''; ?>>
                    <?= htmlspecialchars($seat); ?>
                </button>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>ไม่มีข้อมูลที่นั่งสำหรับรอบฉายนี้</p>
    <?php endif; ?>
</div>

<style>
    .seat-layout { text-align: center; margin-top: 20px; }
    .screen { background: #222; color: white; padding: 10px; margin-bottom: 10px; }
    .seat-grid { display: grid; grid-template-columns: repeat(10, 40px); gap: 5px; justify-content: center; }
    .seat { width: 40px; height: 40px; border: none; cursor: pointer; }
    .available { background: green; color: white; }
    .booked { background: red; color: white; cursor: not-allowed; }
</style>
