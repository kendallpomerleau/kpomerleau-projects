<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Delete Comment</title>
</head>
<body>
<?php
session_start();
if(!hash_equals($_SESSION['token'], $_POST['token'])){
    die("Request forgery detected");
}
$username = $_SESSION['username'];
//$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
$user_id = $_SESSION['user_id'];
$article_to_be_edited = $_POST['article_name'];
$_SESSION['article_name'] = $article_to_be_edited;
//validate username
if( !preg_match('/^[\w_\-]+$/', $username) ){
    echo "Invalid username.";
    ?>
    <button onclick="history.go(-1);">Back </button>
    <?php
exit;
}

require 'database.php';

//CHECK AND REDIRECT IF ARTICLE DOES NOT EXIST
$stmt4 = $mysqli->prepare("SELECT article_name from articles where article_name=?");
if(!$stmt4){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt4->bind_param('s',$article_to_be_edited);
$stmt4->execute();
$result4 = $stmt4->get_result();
if ($result4->num_rows === 0){ //if no comments
    echo "This articles has not been uploaded.";
    ?>
    <form name="input" action="userpage.php">
        <input type="submit" value="Back to Profile"/>
    </form> 
    <?php
    exit;
}

//GET ARTICLE ID
$stmt3 = $mysqli->prepare("SELECT id from articles where article_name = ?");
if(!$stmt3){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt3->bind_param('s', $article_to_be_edited);
$stmt3->execute();
$stmt3->bind_result($article_id_from_query);

$article_id=0;

while($stmt3->fetch()){
    $article_id = $article_id_from_query;
}
$stmt3->close();



//getting articles that specific user has commented on
$stmt = $mysqli->prepare("SELECT ar.article_name FROM articles ar join $username un on (ar.id=un.article_id) where commented='y'");
if(!$stmt){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$stmt->execute();
$result = $stmt->get_result();
$user_commented_on_file = false;

if ($result->num_rows === 0){ //if no comments
    header("Location: nocomment.html");
    exit;    
}
else { //if comments have been made on that article
    while($row = $result->fetch_assoc()){
        if ($article_to_be_edited == $row["article_name"]){
            $user_commented_on_file=true;
        }
    }
}
$stmt->close();


if ($user_commented_on_file){
    $stmt2 = $mysqli->prepare("SELECT comment_id, comment from comments where user_id=? and article_id=?");
    if(!$stmt2){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $stmt2->bind_param('ss',$user_id,$article_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    ?>
    <h2> Choose which Comment you want to delete by its Comment ID. </h2> <?php
    while($row2 = $result2->fetch_assoc()){
        $comment_id=$row2['comment_id'];
        $comment_text=$row2['comment'];
        ?>
        <div>
            <ul>
                <li> Comment ID: <?php echo $comment_id ?> <br/>
                    Comment: <?php echo $comment_text ?> </li>
            </ul>
        </div>
        <?php
    }
    ?>
    <form action="deletecomment.php" method="POST">
        <input type="text" name="comment_id"/>
        <input type="submit" name="Submit"/>
        <input type="hidden" name="article_id" value="<?= $article_id ?>"/>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>

<?php
}

?>
</body>
</html>
