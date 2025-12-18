<!DOCTYPE html>
<html>
<head>
    <title>LOGIN</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container" style="display:flex; flex-direction:column; justify-content:center;">
        
        <h1>Perfume App</h1>
        <p style="margin-bottom: 30px;">Schedule & Attendance System</p>

        <form action="login.php" method="post" class="form-box" style="box-shadow:none; border:none; padding:0;">
            <?php if (isset($_GET['error'])) { echo "<p class='error'>".$_GET['error']."</p>"; } ?>
            
            <label>Email Address</label>
            <input type="email" name="email" placeholder="example@mail.com" required>
            
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
            
            <br>
            <button type="submit">LOGIN</button>
        </form>

        <p style="margin-top:20px; font-size:12px; color:#aaa;">© 2025 Perfume Co.</p>
    </div>
</body>
</html>