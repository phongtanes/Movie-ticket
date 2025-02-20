<?php
session_start();
include 'testdb.php';
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Booking</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container mt-5 text-center">
        <h1>Movie Booking System</h1>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>ยินดีต้อนรับ, <?php echo $_SESSION['user_name']; ?>!</p>
            <a href="logout.php" class="btn btn-danger">ออกจากระบบ</a>
        <?php else: ?>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#loginModal">เข้าสู่ระบบ/สมัครสมาชิก</button>
        <?php endif; ?>
    </div>

    <!-- Modal Login -->
    <div class="modal fade" id="loginModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เข้าสู่ระบบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="logintest.php" method="POST">
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="อีเมล" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน" required>
                        </div>
                        <button type="submit" class="btn btn-primary">เข้าสู่ระบบ</button>
                    </form>
                    <p class="mt-3">ยังไม่มีบัญชี? <a href="#" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">สมัครสมาชิก</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Register -->
    <div class="modal fade" id="registerModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">สมัครสมาชิก</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="regtest.php" method="POST">
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="ชื่อ" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="อีเมล" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password" class="form-control" placeholder="รหัสผ่าน" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" name="line_token" class="form-control" placeholder="ไลน์โทเคน" required>
                        </div>
                        <button type="submit" class="btn btn-success">สมัครสมาชิก</button>
                    </form>
                    <p class="mt-3">มีบัญชีแล้ว? <a href="#" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">เข้าสู่ระบบ</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
