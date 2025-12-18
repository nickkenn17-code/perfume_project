<?php 
session_start();
include "includes/db_conn.php";

// SECURITY: Only Super Admin (ID 4) allowed
if (!isset($_SESSION['id']) || $_SESSION['role_id'] != 4) { 
    header("Location: index.php"); 
    exit(); 
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Activity Logs</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>System Activity Logs</h2>
        <a href="home_superadmin.php">Back to Dashboard</a>
        <hr>

        <table>
            <thead>
                <tr>
                    <th style="width: 25%;">Time</th>
                    <th style="width: 20%;">User</th>
                    <th style="width: 20%;">Activity</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Join 'activity_log' with 'users' to show the name instead of just ID
                $sql = "SELECT l.*, u.name 
                        FROM activity_log l 
                        JOIN users u ON l.user_id = u.user_id 
                        ORDER BY l.timestamp DESC";
                
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['timestamp'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td><strong>" . $row['activity'] . "</strong></td>";
                        echo "<td>" . $row['description'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No activity recorded yet.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>