<?php
declare(strict_types=1);

session_start();

if (!empty($_SESSION['admin_id'])) {
    header('Location: dashboard.php');
} else {
    header('Location: login.php');
}
exit;
