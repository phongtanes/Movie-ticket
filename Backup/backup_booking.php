<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกที่นั่ง</title>
    <!-- เพิ่ม Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-5">เลือกที่นั่งและจองตั๋ว</h1>
        <form action="process_booking.php" method="POST">
            <div class="form-group">
                <label for="seat_number">กรอกหมายเลขที่นั่ง:</label>
                <input type="text" id="seat_number" name="seat_number" class="form-control" required>
            </div>
            <input type="hidden" name="show_id" value="<?php echo $_GET['show_id']; ?>">
            <button type="submit" class="btn btn-primary">ยืนยันการจอง</button>
        </form>
    </div>
    <!-- เพิ่ม Bootstrap JS และ jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.6.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
