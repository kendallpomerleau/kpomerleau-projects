<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comment Success</title>
</head>
<body>
    <?php
        session_start();
    ?>
    <p>Commented Successfully!</p>
    <form name="input" action="news.php">
        <input type="submit" value="Back to News"/>
    </form>
</body>
</html>