<link rel="stylesheet" href="styles.css">
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

    <!-- Include Modal from external file -->
    <?php include 'auth_modal.php'; ?>