<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Comment</title>
</head>
<body>
<?php
session_start();
if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
}
?>
    <form name="input" action="commentupload.php" method="POST">
        Please enter the name of the article you want to comment on.
        <input type="text" name="article_name"/> <br>
        Please enter a comment for the article you chose.
        <input type="text" name="comment"/>
        <br> <input type="submit" value="Submit Comment"/>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
    <button onclick="history.go(-1);">Back </button>
</body>
</html>