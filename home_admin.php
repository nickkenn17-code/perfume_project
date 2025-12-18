<?php 
session_start();
include "includes/db_conn.php";
include "includes/functions.php"; // Added this just in case you want to log actions

// Security Check
if ($_SESSION['role_id'] != 3) { header("Location: index.php"); exit(); }

// Logic to Add Schedule
if (isset($_POST['add_schedule'])) {
    $uid = $_POST['user_id']; 
    $vid = $_POST['venue_id']; 
    $date = $_POST['date'];
    $start = $_POST['start']; 
    $end = $_POST['end']; 
    $note = $_POST['notes'];
    
    $sql = "INSERT INTO schedules (user_id, venue_id, shift_date, start_time, end_time, notes) 
            VALUES ('$uid','$vid','$date','$start','$end','$note')";
            
    if(mysqli_query($conn, $sql)) {
        // Optional: Log this action
        log_activity($conn, $_SESSION['id'], 'CREATE_SCHEDULE', "Assigned schedule to User ID $uid on $date at Venue ID $vid");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h1>Admin Dashboard</h1>
        <p style="text-align:center;">
            Welcome, <?php echo $_SESSION['name']; ?> | <a href="logout.php">Logout</a>
        </p>
        <hr>

        <a href="admin_reports.php">
            <button style="background: #17a2b8; margin-bottom: 20px;">📊 View & Make Reports</button>
        </a>

        <div class="form-box">
            <h3>Create Schedule</h3>
            <form method="post">
                <label>Select Officer</label>
                <select name="user_id" required>
                    <option value="">-- Choose Officer --</option>
                    <?php 
                    $u = mysqli_query($conn,"SELECT * FROM users WHERE role_id=1"); 
                    while($row=mysqli_fetch_assoc($u)){ 
                        echo "<option value='".$row['user_id']."'>".$row['name']."</option>"; 
                    } 
                    ?>
                </select>

                <label>Select Venue</label>
                <select name="venue_id" required>
                    <option value="">-- Choose Venue --</option>
                    <?php 
                    $v = mysqli_query($conn,"SELECT * FROM venues"); 
                    while($row=mysqli_fetch_assoc($v)){ 
                        echo "<option value='".$row['venue_id']."'>".$row['venue_name']."</option>"; 
                    } 
                    ?>
                </select>

                <label>Date</label>
                <input type="date" name="date" required>
                
                <label>Start Time</label>
                <input type="time" name="start" required>
                
                <label>End Time</label>
                <input type="time" name="end" required>
                
                <label>Notes</label>
                <input type="text" name="notes" placeholder="e.g. Opening Shift">
                
                <button type="submit" name="add_schedule">Assign Shift</button>
            </form>
        </div>

        <h3>Recent Schedules</h3>
        <table>
            <tr>
                <th>Date</th>
                <th>Officer</th>
                <th>Venue</th>
            </tr>
            <?php 
            $res = mysqli_query($conn, "SELECT s.shift_date, u.name, v.venue_name FROM schedules s JOIN users u ON s.user_id=u.user_id JOIN venues v ON s.venue_id=v.venue_id ORDER BY s.shift_date DESC");
            while($r=mysqli_fetch_assoc($res)){ 
                echo "<tr><td>".$r['shift_date']."</td><td>".$r['name']."</td><td>".$r['venue_name']."</td></tr>"; 
            } 
            ?>
        </table>
    </div>
</body>
</html>