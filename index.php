<?php
    session_start();
    if(isset($_SESSION['user_id'])) {
        header("Location: customer_dashboard.php");
        return;
    }
?>
<!-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div>
        <form action="login.php" method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Log-in">
        </form>
    </div>
    <br>
    <div>
        <form action="sign_up.php" method="POST">
            <input type="text" name="firstname" placeholder="Firstname" required>
            <input type="text" name="lastname" placeholder="Lastname" required>
            <input type="text" name="user_address" placeholder="Address" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="user_password" placeholder="Password" required>
            <input type="submit" name="signup" value="Sign-Up">
        </form>
    </div>
</body>
</html> -->





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="imgs/washing-machine_icon-icons.com_60734.ico">
</head>
<body>
      <div class="container">
        <div class="box form-box">
            <header>User Login</header>
            <form action="login.php" method="POST">

                <div class="field input">
                    <input type="email" name="email" id="email" placeholder="Email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <input type="password" name="password" id="password" placeholder="Password" autocomplete="off" required>
                </div>

                <div class="field">
                    
                    <input type="submit" class="btn" name="submit" value="Login" required>
                </div>
                <div class="links">
                    Don't have account? <a href="register.php">Sign Up Now</a><br>
                </div>
            </form>
        </div>
      </div>
</body>
</html>