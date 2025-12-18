<?php 
session_start(); 
include "includes/db_conn.php";
include "includes/functions.php";

if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$pass'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        
        $_SESSION['email'] = $row['email'];
        $_SESSION['name'] = $row['name'];
        $_SESSION['id'] = $row['user_id'];
        $_SESSION['role_id'] = $row['role_id'];

        log_activity($conn, $row['user_id'], 'LOGIN', 'User logged in');

        if ($row['role_id'] == 4) header("Location: home_superadmin.php");
        else if ($row['role_id'] == 3) header("Location: home_admin.php");
        else if ($row['role_id'] == 2) header("Location: home_manager.php");
        else header("Location: home_officer.php");
        exit();
    } else {
        header("Location: index.php?error=Incorrect Email or Password");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>