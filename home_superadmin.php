<?php 
session_start();
if (!isset($_SESSION['id']) || $_SESSION['role_id'] != 4) { header("Location: index.php"); exit(); }
?>
<!DOCTYPE html>
<html>
<head><title>Super Admin</title><link rel="stylesheet" href="css/style.css"></head>
<body>
    <div class="container" style="text-align:center;">
        <h1>Hello, <?php echo $_SESSION['name']; ?></h1>
        <hr>
        <a href="superadmin_users.php"><button>Manage Users</button></a>
        <a href="superadmin_venues.php"><button style="background:blue;">Manage Venues</button></a>
        <a href="superadmin_logs.php"><button style="background:purple;">Activity Logs</button></a>
        <br><br>
        <a href="logout.php"><button class="btn-danger">Logout</button></a>
    </div>
</body>
</html>