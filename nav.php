<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function isActive($page) {
    return basename($_SERVER['SCRIPT_NAME']) == $page ? 'active' : '';
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="styles.css">
<style>
    nav ul h6{
        margin-right: 10px;
    }
</style>
<header>
    <nav>
        <ul>
            <h6>Movies Fest</h6>
            <li class="<?php echo isActive('index.php'); ?>">
                <a href="index.php">หน้าแรก</a>
            </li>
            <li class="<?php echo isActive('movies.php'); ?>">
                <a href="movies.php">ภาพยนตร์</a>
            </li>
            <li class="<?php echo isActive('contact.php'); ?>">
                <a href="contact.php">ติดต่อ</a>
            </li>

            <?php if (isset($_SESSION['user_id'])): ?>
                <li class="<?php echo isActive('profile.php'); ?>">
                    <a href="profile.php">👤 <?php echo htmlspecialchars($_SESSION['user_name'] ?? 'ผู้ใช้'); ?></a>
                </li>
                <li><a href="logout.php">ออกจากระบบ</a></li>
            <?php else: ?>
                <li><a href="#" data-bs-toggle="modal" data-bs-target="#authModal">เข้าสู่ระบบ / สมัครสมาชิก</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<?php
if (!defined('AUTH_MODAL_INCLUDED')) {
    define('AUTH_MODAL_INCLUDED', true);
    include "auth_modal.php";
}
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var myModal = new bootstrap.Modal(document.getElementById("authModal"));

    // ฟังก์ชันการส่งข้อมูลของฟอร์ม
    function handleFormSubmit(formId, actionUrl) {
        var form = document.getElementById(formId);
        var formData = new FormData(form);

        fetch(actionUrl, {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message); // แจ้งเตือนผลลัพธ์

            if (data.status === "success") {
                location.reload(); // รีโหลดหน้าเว็บเมื่อสำเร็จ
            }
        })
        .catch(error => console.error("Error:", error));
    }

    // การทำงานเมื่อกดปุ่ม "เข้าสู่ระบบ"
    document.getElementById("loginBtn")?.addEventListener("click", function () {
        handleFormSubmit("loginForm", "login.php");
    });

    // การทำงานเมื่อกดปุ่ม "สมัครสมาชิก"
    document.getElementById("registerBtn")?.addEventListener("click", function () {
        handleFormSubmit("registerForm", "register.php");
    });
});
</script>

