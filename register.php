





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Register</title>
</head>
<body>
      <div class="container">
        <div class="box form-box">
            <header>Sign Up</header>
            <form action="sign_up.php" method="POST">

                <div class="field input">
                    <input type="text" name="firstname" id="fname" placeholder="Firstname" autocomplete="off" required>
                </div>

                <div class="field input">
                    <input type="text" name="lastname" id="lname" placeholder="Lastname" autocomplete="off" required>
                </div>

                <div class="field input">
                    <input type="email" name="email" id="email" placeholder="Email" autocomplete="off" required>
                </div>

                <div class="field input">
                    <input type="text" name="user_address" id="address" placeholder="Address" autocomplete="off" required>
                </div>
                <div class="field input">
                    <input type="password" name="user_password" id="password" placeholder="Password" autocomplete="off" required>
                </div>

                <div class="field">
                    
                    <input type="submit" class="btn" name="signup" value="Register" required>
                </div>
                <div class="links">
                    Already a member? <a href="index.php">Sign In</a>
                </div>
            </form>
        </div>
        
      </div>
</body>
</html>