<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Username Exists</title>
</head>
<body>
<?php
    session_start();
    //get username and encrypt password
    require 'database.php';

    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }

    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    //$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

    //CHECK THAT USERNAME DOESN'T YET EXIST
    $stmt4 = $mysqli->prepare("SELECT username from users");
    if(!$stmt4){
	    printf("Query Prep Failed: %s\n", $mysqli->error);
	    exit;
    }
    $stmt4->execute();
    $result4 = $stmt4->get_result();

    while($row4 = $result4->fetch_assoc()){
        if ($username == $row4["username"]){
            echo "This username already exists. Please enter a new username or login with this username.";
            ?>
            <br>
            <form name="input" action="login.php">
                <input type="submit" value="Back to Login"/>   
                 <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
            </form>
            <?php
            exit;
        }
    }

    //INSERT NEW USERNAME
    $stmt = $mysqli->prepare("INSERT into users(username, hashed_password) values (?, ?)");
    if(!$stmt){
	    printf("Query Prep Failed: %s\n", $mysqli->error);
	    exit;
    }

    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $stmt->close();

    $_SESSION['username']=$username;

    //create table for user's profile
    $stmt2 = $mysqli->prepare("CREATE TABLE $username(
        article_id int unsigned not null,
        uploaded enum('y','n') not null,
        commented enum('y','n') not null,
        liked enum('y','n') not null,
        disliked enum('y','n') not null,
        primary key (article_id),
        foreign key (article_id) references articles (id)
        ) engine = InnoDB default character set = utf8 collate = utf8_general_ci;");
    if(!$stmt2){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt2->execute();
    $stmt2->close();

    //get new user id
    $stmt3 = $mysqli->prepare("SELECT id FROM users WHERE username=?");
    $stmt3->bind_param('s', $username);
    $stmt3->execute();
    $stmt3->bind_result($user_id);
    $stmt3->fetch();    
    $_SESSION['user_id'] = $user_id;
    $stmt3->close();

    header("Location: news.php");
    exit;

?>

</body>
</html>
