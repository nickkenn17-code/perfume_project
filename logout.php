<?php 
session_start();
include "includes/db_conn.php";
include "includes/functions.php";

// Check if user is logged in before logging them out
if (isset($_SESSION['id'])) {
    // 1. Log the action
    log_activity($conn, $_SESSION['id'], 'LOGOUT', 'User logged out of the system');
}

// 2. Destroy Session
session_unset();
session_destroy();

// 3. Redirect
header("Location: index.php");
exit();
?>