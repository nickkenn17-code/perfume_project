<?php 
session_start();
include "includes/db_conn.php";
if ($_SESSION['role_id'] != 1) { header("Location: index.php"); exit(); }
$myid = $_SESSION['id'];
?>
<!DOCTYPE html>
<html>
<head><title>My Shifts</title><link rel="stylesheet" href="css/style.css">
    <link rel="manifest" href="manifest.json">
</head>
<body>
    <div class="container">
        <h1>Welcome, <?php echo $_SESSION['name']; ?></h1> <a href="logout.php">Logout</a>
        <h3>Your Schedule</h3>
        <table>
            <tr><th>Date</th><th>Venue</th><th>Time</th><th>Notes</th></tr>
            <?php
            $res = mysqli_query($conn, "SELECT s.*, v.venue_name FROM schedules s JOIN venues v ON s.venue_id=v.venue_id WHERE s.user_id=$myid");
            while($r=mysqli_fetch_assoc($res)){ 
                echo "<tr><td>".$r['shift_date']."</td><td>".$r['venue_name']."</td><td>".$r['start_time']."-".$r['end_time']."</td><td>".$r['notes']."</td></tr>"; 
            }
            ?>
        </table>
    </div>
    <script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('sw.js')
        .then(() => console.log('Service Worker Registered'))
        .catch(err => console.log('Service Worker Failed:', err));
    }
    </script>
</body>
</html>