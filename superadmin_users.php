<?php 
session_start();
include "includes/db_conn.php";
include "includes/functions.php";

// SECURITY: Only Allow Super Admin (Role ID 4)
if (!isset($_SESSION['id']) || $_SESSION['role_id'] != 4) { 
    header("Location: index.php"); 
    exit(); 
}

// --- 1. LOGIC: ADD NEW USER ---
if (isset($_POST['add_user'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $pass = $_POST['password']; // In a real app, use password_hash()!
    $role = $_POST['role_id'];

    // THE FIX: Full SQL command (No "..." placeholders)
    $sql = "INSERT INTO users (name, email, password, role_id) 
            VALUES ('$name', '$email', '$pass', '$role')";
    
    if (mysqli_query($conn, $sql)) {
        // Log the action
        log_activity($conn, $_SESSION['id'], 'CREATE_USER', "Added new user: $name");
        header("Location: superadmin_users.php"); // Refresh page
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// --- 2. LOGIC: DELETE USER ---
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    
    if (mysqli_query($conn, "DELETE FROM users WHERE user_id=$id")) {
        // Log the action
        log_activity($conn, $_SESSION['id'], 'DELETE_USER', "Deleted User ID: $id");
        header("Location: superadmin_users.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// --- 3. LOGIC: UPDATE ROLE (Promote/Demote) ---
if (isset($_POST['update_role'])) {
    $id = $_POST['user_id'];
    $new_role = $_POST['new_role_id'];
    
    if (mysqli_query($conn, "UPDATE users SET role_id=$new_role WHERE user_id=$id")) {
        // Log the action
        log_activity($conn, $_SESSION['id'], 'PROMOTE_DEMOTE', "Updated User ID $id to Role $new_role");
        header("Location: superadmin_users.php");
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Users</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <h2>Manage Officers & Roles</h2>
        <a href="home_superadmin.php">Back to Dashboard</a>
        <hr>

        <div class="form-box">
            <h3>Add New Officer</h3>
            <form method="post">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="e.g. John Doe" required>
                
                <label>Email</label>
                <input type="email" name="email" placeholder="e.g. john@mail.com" required>
                
                <label>Password</label>
                <input type="text" name="password" placeholder="Set Password" required>
                
                <label>Role</label>
                <select name="role_id">
                    <option value="1">Officer</option>
                    <option value="2">Manager</option>
                    <option value="3">Admin</option>
                </select>
                
                <button type="submit" name="add_user">Add User</button>
            </form>
        </div>

        <h3>Current Users</h3>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role ID</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Get all users except myself (Super Admin)
                    $my_id = $_SESSION['id'];
                    $sql = "SELECT * FROM users WHERE user_id != $my_id ORDER BY role_id DESC";
                    $result = mysqli_query($conn, $sql);
                    
                    while ($row = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        
                        <td>
                            <form method="post" style="margin:0;">
                                <input type="hidden" name="user_id" value="<?php echo $row['user_id']; ?>">
                                <select name="new_role_id" onchange="this.form.submit()" style="margin-bottom:0; padding:5px;">
                                    <option value="<?php echo $row['role_id']; ?>"><?php echo $row['role_id']; ?> (Current)</option>
                                    <option value="1">1 - Officer</option>
                                    <option value="2">2 - Manager</option>
                                    <option value="3">3 - Admin</option>
                                    <option value="4">4 - Super Admin</option>
                                </select>
                                <input type="hidden" name="update_role" value="1">
                            </form>
                        </td>

                        <td>
                            <a href="superadmin_users.php?delete_id=<?php echo $row['user_id']; ?>" 
                               onclick="return confirm('Are you sure you want to delete this user?');" 
                               style="color:red; font-weight:bold;">
                               Delete
                            </a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>