<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete</title>
</head>
<body>
    <?php
        session_start();
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }
        //$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    ?>
    <form name="input" action="deletearticle.php" method="POST">
        Please enter the name of the article you want to delete. <br>
        Reminder: you can only delete files you have uploaded.
        <input type="text" name="article_name"/> <br>
        <br> <input type="submit" value="Delete Article"/>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
    <button onclick="history.go(-1);">Back </button>
</body>
</html>