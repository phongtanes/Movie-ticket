<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// เช็ค Role
if ($_SESSION['user_role'] === 'Admin') {
    echo "<h1>Welcome, Admin</h1>";
    echo "<a href='admin.php'>Admin Panel</a>";
} else {
    echo "<h1>Welcome, User</h1>";
    echo "<a href='user.php'>User Dashboard</a>";
}
?>
<a href="logout.php">Logout</a>
