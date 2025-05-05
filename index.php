<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] == 'admin') {
        header('Location: pages/admin/dashboard.php');
    } elseif ($_SESSION['role'] == 'librarian') {
        header('Location: pages/librarian/dashboard.php');
    } else {
        header('Location: pages/student/dashboard.php');
    }
} else {
    header('Location: login.php');
}
exit;
