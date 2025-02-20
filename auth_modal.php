<link rel="stylesheet" href="auth.css">
<!-- Modal -->
<div class="modal fade" id="authModal" tabindex="-1" aria-labelledby="authModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="authModalLabel">เข้าสู่ระบบ / สมัครสมาชิก</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Nav tabs for Login/Register -->
                <ul class="nav nav-tabs" id="authTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="login-tab" data-bs-toggle="tab" href="#login" role="tab" aria-controls="login" aria-selected="true">เข้าสู่ระบบ</a>
                    </li>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link" id="register-tab" data-bs-toggle="tab" href="#register" role="tab" aria-controls="register" aria-selected="false">สมัครสมาชิก</a>
                    </li>
                </ul>

                <!-- Tab content -->
                <div class="tab-content mt-2" id="authTabContent">
                    <!-- Login Form -->
                    <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab">
                        <form id="loginForm" action="login.php" method="POST">
                            <div class="mb-3">
                                <input type="email" class="form-control" id="loginEmail" name="email" placeholder="อีเมล" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" id="loginPassword" name="password" placeholder="รหัสผ่าน" required>
                            </div>
                            <button type="button" id="loginBtn" class="btn btn-primary" name="login">เข้าสู่ระบบ</button>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div class="tab-pane fade" id="register" role="tabpanel" aria-labelledby="register-tab">
                        <form id="registerForm" action="register.php" method="POST">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="registerName" name="name" placeholder="ชื่อ-นามสกุล" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" id="registerEmail" name="email" placeholder="อีเมล" required>
                            </div>
                            <div class="mb-3">
                                <input type="password" class="form-control" id="registerPassword" name="password" placeholder="รหัสผ่าน" required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="registerLineToken" name="line_token" placeholder="ไลน์โทเค็น">
                            </div>
                            <button type="button" id="registerBtn" class="btn btn-primary" name="register">สมัครสมาชิก</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript for handling Enter key submission -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    // ฟังก์ชันตรวจจับปุ่ม Enter และคลิกปุ่ม Submit
    function enableEnterSubmit(formId, buttonId) {
        document.getElementById(formId).addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                event.preventDefault(); // ป้องกันการ submit ตามปกติ
                document.getElementById(buttonId).click(); // คลิกปุ่มที่กำหนด
            }
        });
    }

    // ใช้ฟังก์ชันกับฟอร์ม Login และ Register
    enableEnterSubmit("loginForm", "loginBtn");
    enableEnterSubmit("registerForm", "registerBtn");
});
</script>
