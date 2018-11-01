<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Edit Failure</title>
</head>
<body>
    <?php
        session_start();
        if(!hash_equals($_SESSION['token'], $_POST['token'])){
            die("Request forgery detected");
        }
        
    ?>
    <p>Unable to edit.</p>
    <form name="input" action="userpage.php">
        <input type="submit" value="Back to Profile"/>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
</body>
</html>