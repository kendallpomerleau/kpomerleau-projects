<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>
<body>
    <h2>
        Choose a valid username and password.
    </h2>
    <?php
        session_start();
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

    ?>
    <form name="input" action="encrypt.php" method="POST"> 
        Username: <input type="text" name="username"/><br>
        Password: <input type="password" name="password"/><br><br>
        <input type="hidden" name="token" value="<?= $_SESSION['token']?>" />
        <input type="submit" value="Submit"/>
    </form>
</body>
</html>
