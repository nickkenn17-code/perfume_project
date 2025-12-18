<?php 
// FILE: admin_reports.php
session_start();
include "includes/db_conn.php";
include "includes/functions.php";

// SECURITY: Only Admin (ID 3)
if (!isset($_SESSION['id']) || $_SESSION['role_id'] != 3) { 
    header("Location: index.php"); 
    exit(); 
}

// --- LOGIC: EXPORT TO EXCEL ---
if (isset($_POST['export_xlsx'])) {
    // 1. Tell browser this is an Excel file
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Perfume_Schedule_Report.xls");
    
    // 2. Print Column Headers (Tab separated)
    echo "Date\tVenue\tOfficer\tTime\tStatus\tNotes\n";
    
    // 3. Get Data
    $sql = "SELECT s.shift_date, v.venue_name, u.name, s.start_time, s.end_time, a.status, s.notes 
            FROM schedules s 
            JOIN users u ON s.user_id = u.user_id 
            JOIN venues v ON s.venue_id = v.venue_id 
            LEFT JOIN attendance a ON s.schedule_id = a.schedule_id 
            ORDER BY s.shift_date DESC";
            
    $result = mysqli_query($conn, $sql);
    
    while($row = mysqli_fetch_assoc($result)) {
        // Handle empty status (if attendance not taken yet)
        $status = empty($row['status']) ? "Pending" : $row['status'];
        
        echo $row['shift_date'] . "\t" . 
             $row['venue_name'] . "\t" . 
             $row['name'] . "\t" . 
             $row['start_time'] . "-" . $row['end_time'] . "\t" . 
             $status . "\t" . 
             $row['notes'] . "\n";
    }
    exit(); // Stop script so only the file downloads
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports - Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>System Reports</h2>
        <a href="home_admin.php">Back to Dashboard</a>
        <hr>

        <form method="post" style="margin-bottom: 20px;">
            <button type="submit" name="export_xlsx" style="background: #28a745;">
                📥 Download Excel Report
            </button>
        </form>

        <h3>Report Preview</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Venue</th>
                    <th>Officer</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Same query as export, but for display
                $sql = "SELECT s.shift_date, v.venue_name, u.name, a.status 
                        FROM schedules s 
                        JOIN users u ON s.user_id = u.user_id 
                        JOIN venues v ON s.venue_id = v.venue_id 
                        LEFT JOIN attendance a ON s.schedule_id = a.schedule_id 
                        ORDER BY s.shift_date DESC";
                
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Color code the status
                        $status = empty($row['status']) ? "Pending" : $row['status'];
                        $color = "black";
                        if($status == 'Absent') $color = "red";
                        if($status == 'Present') $color = "green";
                        if($status == 'Substitute') $color = "orange";

                        echo "<tr>";
                        echo "<td>" . $row['shift_date'] . "</td>";
                        echo "<td>" . $row['venue_name'] . "</td>";
                        echo "<td>" . $row['name'] . "</td>";
                        echo "<td style='color:$color; font-weight:bold;'>" . $status . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No data found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>