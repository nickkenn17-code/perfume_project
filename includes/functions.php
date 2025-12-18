<?php
function log_activity($conn, $user_id, $action, $desc) {
    $action = mysqli_real_escape_string($conn, $action);
    $desc = mysqli_real_escape_string($conn, $desc);
    
    $sql = "INSERT INTO activity_log (user_id, activity, description) 
            VALUES ('$user_id', '$action', '$desc')";
    mysqli_query($conn, $sql);
}
?>