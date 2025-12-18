<?php 
session_start();
include "includes/db_conn.php";
if ($_SESSION['role_id'] != 4) { header("Location: index.php"); exit(); }

if (isset($_POST['add_venue'])) {
    // ... (variables) ...
    if (mysqli_query($conn, "INSERT INTO venues ...")) {
        // [LOGGING ADDED HERE]
        log_activity($conn, $_SESSION['id'], 'CREATE_VENUE', "Added venue: $name");
    }
    header("Location: superadmin_venues.php");
}

if (isset($_GET['del'])) {
    $id = $_GET['del'];
    mysqli_query($conn, "DELETE FROM venues WHERE venue_id=$id");
    
    // [LOGGING ADDED HERE]
    log_activity($conn, $_SESSION['id'], 'DELETE_VENUE', "Deleted Venue ID: $id");
    
    header("Location: superadmin_venues.php");
}
?>
<!DOCTYPE html>
<html>
<head><title>Venues</title><link rel="stylesheet" href="css/style.css"></head>
<body>
    <div class="container">
        <h2>Manage Venues</h2> <a href="home_superadmin.php">Back</a>
        <div class="form-box">
            <form method="post">
                <input type="text" name="venue_name" placeholder="Venue Name" required>
                <input type="text" name="location" placeholder="Address" required>
                <button type="submit" name="add_venue">Add Venue</button>
            </form>
        </div>
        <table>
            <tr><th>Venue</th><th>Location</th><th>Action</th></tr>
            <?php
            $res = mysqli_query($conn, "SELECT * FROM venues");
            while ($r = mysqli_fetch_assoc($res)) {
                echo "<tr><td>".$r['venue_name']."</td><td>".$r['location_address']."</td><td>Delete</td></tr>";
            }
            ?>
        </table>
    </div>
</body>
</html>