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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

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
                    <li><a href="#" data-bs-toggle="modal" data-bs-target="#authModal">เข้าสู่ระบบ / สมัครสมาชิก</a></li>
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
    <!-- Modal -->
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authModalLabel">เข้าสู่ระบบ / สมัครสมาชิก</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <ul class="nav nav-tabs" id="authTabs">
                    <li class="nav-item">
                        <a class="nav-link active" id="login-tab" data-bs-toggle="tab" href="#loginForm">เข้าสู่ระบบ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#registerForm">สมัครสมาชิก</a>
                    </li>
                </ul>
                <div class="tab-content mt-3">
                    <!-- Login Form -->
<div class="tab-pane fade show active" id="loginForm">
    <form method="POST" action="login.php">
        <div class="mb-3">
            <input type="email" class="form-control" id="loginEmail" name="email" placeholder="อีเมล" required>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" id="loginPassword" name="password" placeholder="รหัสผ่าน" required>
        </div>
        <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
    </form>
</div>

<!-- Register Form -->
<div class="tab-pane fade" id="registerForm">
    <form method="POST" action="register.php">
        <div class="mb-3">
            <input type="email" class="form-control" id="regEmail" name="email" placeholder="อีเมล" required>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" id="regPassword" name="password" placeholder="รหัสผ่าน" required>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" id="regName" name="name" placeholder="ชื่อ" required>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" id="regLineToken" name="line_token" placeholder="Line Token" required>
        </div>
        <button type="submit" class="btn btn-success">สมัครสมาชิก</button>
    </form>
</div>


                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("registerForm").addEventListener("submit", function (event) {
        event.preventDefault(); // ป้องกันการโหลดหน้าใหม่

        let formData = new FormData(this);

        fetch("register.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())  // แปลง JSON Response
        .then(data => {
            alert(data.message);
            if (data.status === "success") {
                location.reload(); // รีเฟรชหน้าเพื่ออัปเดตเมนูเป็นล็อกอิน
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("เกิดข้อผิดพลาด กรุณาลองใหม่");
        });
    });
});
</script>

</body>
</html>
