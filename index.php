<?php
    session_start();
?>
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
            <input type="text" name="lastname" placeholder="Lastname" required>
            <input type="text" name="firstname" placeholder="Firstname" required>
            <input type="text" name="user_address" placeholder="Address" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="user_password" placeholder="Password" required>
            <input type="submit" name="signup" value="Sign-Up">
        </form>
    </div>
    <?php
        if(isset($_SESSION['username']) && isset($_SESSION['password'])) {
            // echo 'username: '.$_SESSION['username'].'<br>';
            // echo 'password: '.$_SESSION['password'].'<br>';
            foreach($_SESSION as $key => $value) {
                echo $key.' : '.$value.'<br>';
            }
        }
    ?>
</body>
</html>