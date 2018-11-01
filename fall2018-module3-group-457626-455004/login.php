<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
<?php
    session_start();
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
?>
    <h1 class="nh">
        Welcome!
    </h1>
    <h2 class="uph2"> 
        Login with your username.
    </h2>
    <div class="login_forms">
        <form name="input" action="checklogin.php" method="POST"> 
                Username: <input type="text" name="username"/><br>
                Password: <input type="password" name="password"/><br><br>
                <input type="hidden" name="token" value="<?= $_SESSION['token']?>" />
                <input type="submit" value="Submit"/>
        </form>
    </div>
    <h2 class="uph2">
        Not yet a user? Click to register below.
    </h2>
    <form name="input" action="register.php" method="POST">
            <input type="hidden" name="token" value="<?= $_SESSION['token'] ?>" />
            <input type="submit" value="Register"/>
    </form>
    <h2 class="uph2">
        Login as a guest.
    </h2>        
    <form name="input" action="checklogin.php" method="POST">
        <input type="submit" value="Go To News Site"/>
        <input type="hidden" name="username" value="guest"/>    
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
    
</body>
</html>