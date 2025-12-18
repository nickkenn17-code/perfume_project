<?php 
session_start();
include "includes/db_conn.php";
if ($_SESSION['role_id'] != 2) { header("Location: index.php"); exit(); }

if (isset($_POST['verify'])) {
    $sid = $_POST['sid']; 
    $status = $_POST['status']; 
    $mid = $_SESSION['id'];
    
    // ... (Your existing logic for Actual User ID) ...
    $act_user = ($status == 'Substitute') ? $_POST['sub_id'] : $_POST['orig_uid'];
    if($status == 'Absent') $act_user = "NULL";
    
    $sql = "INSERT INTO attendance (schedule_id, status, actual_user_id, verified_by_manager_id) 
            VALUES ('$sid','$status',$act_user,'$mid')";
            
    if(mysqli_query($conn, $sql)){
        // [LOGGING ADDED HERE]
        log_activity($conn, $_SESSION['id'], 'UPDATE_ATTENDANCE', "Verified Schedule ID: $sid. Status: $status");
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Manager</title><link rel="stylesheet" href="css/style.css"></head>
<body>
    <div class="container">
        <h1>Attendance Checklist</h1> <a href="logout.php">Logout</a>
        <?php
        $res = mysqli_query($conn, "SELECT s.*, u.name, u.user_id as uid, v.venue_name FROM schedules s JOIN users u ON s.user_id=u.user_id JOIN venues v ON s.venue_id=v.venue_id WHERE s.schedule_id NOT IN (SELECT schedule_id FROM attendance)");
        while ($row = mysqli_fetch_assoc($res)) { ?>
            <div class="form-box">
                <strong><?php echo $row['venue_name']; ?></strong> - <?php echo $row['name']; ?> (<?php echo $row['shift_date']; ?>)
                <form method="post">
                    <input type="hidden" name="sid" value="<?php echo $row['schedule_id']; ?>">
                    <input type="hidden" name="orig_uid" value="<?php echo $row['uid']; ?>">
                    <select name="status">
                        <option value="Present">Present</option>
                        <option value="Late">Late</option>
                        <option value="Substitute">Substitute</option>
                        <option value="Absent">Absent</option>
                    </select>
                    <input type="text" name="sub_id" placeholder="Sub User ID (if Substitute)">
                    <button type="submit" name="verify">Verify</button>
                </form>
            </div>
        <?php } ?>
    </div>
</body>
</html>