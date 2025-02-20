<?php
session_start();
include 'db.php';

// ดึงรายชื่อโรงภาพยนตร์
$theaters = $conn->query("SELECT id, name, location FROM theaters");

// เพิ่มรอบฉาย
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_show'])) {
    $movie_id = $_POST['movie_id'];
    $theater_id = $_POST['theater_id'];
    $show_date = $_POST['show_date'];
    $show_time = $_POST['show_time'];

    $conn->query("INSERT INTO shows (movie_id, theater_id, show_date, show_time) VALUES ('$movie_id', '$theater_id', '$show_date', '$show_time')");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// แก้ไขรอบฉาย
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_show'])) {
    $show_id = $_POST['show_id'];
    $movie_id = $_POST['movie_id'];
    $theater_id = $_POST['theater_id'];
    $show_date = $_POST['show_date'];
    $show_time = $_POST['show_time'];

    $conn->query("UPDATE shows SET movie_id = '$movie_id', theater_id = '$theater_id', show_date = '$show_date', show_time = '$show_time' WHERE id = $show_id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// ลบรอบฉาย
if (isset($_GET['delete_show'])) {
    $show_id = $_GET['delete_show'];
    $conn->query("DELETE FROM shows WHERE id = $show_id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โรงภาพยนตร์</title>
    <!-- <link rel="stylesheet" href="styles.css"> -->
    <link rel="stylesheet" href="cine.css">
</head>
<body>

<?php include "nav.php"; ?>

<section class="cinemas">
    <h2>โรงภาพยนตร์</h2>
    <!-- แบบฟอร์มเพิ่มรอบฉาย -->
    <form method="POST" action="">
        <h4>เพิ่มรอบฉาย</h4>
        <label>เลือกโรงภาพยนตร์:</label>
        <select name="theater_id" required>
            <?php
            $all_theaters = $conn->query("SELECT id, name FROM theaters");
            while ($theater = $all_theaters->fetch_assoc()):
            ?>
                <option value="<?= $theater['id']; ?>"><?= htmlspecialchars($theater['name']); ?></option>
            <?php endwhile; ?>
        </select>
        <br>
        <label>เลือกภาพยนตร์:</label>
        <select name="movie_id" required>
            <?php
            $movies = $conn->query("SELECT id, title FROM movies");
            while ($movie = $movies->fetch_assoc()):
            ?>
                <option value="<?= $movie['id']; ?>"><?= htmlspecialchars($movie['title']); ?></option>
            <?php endwhile; ?>
        </select>
        <br>
        <label>วันที่:</label>
        <input type="date" name="show_date" required>
        <label>เวลา:</label>
        <input type="time" name="show_time" required>
        <br>
        <button type="submit" name="add_show">เพิ่มรอบ</button>
    </form>

    <div class="cinema-list">
        <?php while ($theater = $theaters->fetch_assoc()): ?>
            <div class="cinema-container">
                <div class="cinema">
                    <h3><?= htmlspecialchars($theater['name']); ?></h3>
                    <p>สถานที่: <?= htmlspecialchars($theater['location']); ?></p>
                </div>

                <!-- ดึงข้อมูลรอบฉายของโรงภาพยนตร์นี้ -->
                <?php
                $theater_id = $theater['id'];
                $shows = $conn->query("SELECT shows.id, shows.show_date, shows.show_time, movies.title AS movie_title, movies.poster, movies.id AS movie_id, shows.theater_id FROM shows JOIN movies ON shows.movie_id = movies.id WHERE shows.theater_id = $theater_id ORDER BY shows.show_date, shows.show_time");
                ?>

                <div class="showtimes">
                    <h4>รอบฉาย</h4>
                    <?php if ($shows->num_rows > 0): ?>
                        <ul>
                            <?php while ($show = $shows->fetch_assoc()): ?>
                                <li>
                                    <img src="<?= htmlspecialchars($show['poster']); ?>" width="50">
                                    <strong><?= htmlspecialchars($show['movie_title']); ?></strong>
                                    <br>วันที่: <?= htmlspecialchars($show['show_date']); ?> เวลา: <?= htmlspecialchars($show['show_time']); ?>
                                    <a href="?delete_show=<?= $show['id']; ?>" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบรอบนี้?');">ลบ</a>
                                    <button onclick="document.getElementById('editForm<?= $show['id']; ?>').style.display='block'">แก้ไข</button>

                                    <!-- ฟอร์มแก้ไขรอบฉาย -->
                                    <div id="editForm<?= $show['id']; ?>" style="display:none; margin-top:10px;">
                                        <form method="POST" action="">
                                            <input type="hidden" name="show_id" value="<?= $show['id']; ?>">
                                            <label>โรงภาพยนตร์:</label>
                                            <select name="theater_id" required>
                                                <?php
                                                $all_theaters = $conn->query("SELECT id, name FROM theaters");
                                                while ($theater = $all_theaters->fetch_assoc()):
                                                    $selected = ($theater['id'] == $show['theater_id']) ? 'selected' : '';
                                                ?>
                                                    <option value="<?= $theater['id']; ?>" <?= $selected; ?>><?= htmlspecialchars($theater['name']); ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                            <br>
                                            <label>ภาพยนตร์:</label>
                                            <select name="movie_id" required>
                                                <?php
                                                $movies = $conn->query("SELECT id, title FROM movies");
                                                while ($movie = $movies->fetch_assoc()):
                                                    $selected = ($movie['id'] == $show['movie_id']) ? 'selected' : '';
                                                ?>
                                                    <option value="<?= $movie['id']; ?>" <?= $selected; ?>><?= htmlspecialchars($movie['title']); ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                            <br>
                                            <label>วันที่:</label>
                                            <input type="date" name="show_date" value="<?= $show['show_date']; ?>" required>
                                            <label>เวลา:</label>
                                            <input type="time" name="show_time" value="<?= $show['show_time']; ?>" required>
                                            <br>
                                            <button type="submit" name="edit_show">บันทึก</button>
                                            <button type="button" onclick="document.getElementById('editForm<?= $show['id']; ?>').style.display='none'">ยกเลิก</button>
                                        </form>
                                    </div>
                                </li>
                            <?php endwhile; ?>
                        </ul>
                    <?php else: ?>
                        <p>ไม่มีรอบฉาย</p>
                    <?php endif; ?>
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
