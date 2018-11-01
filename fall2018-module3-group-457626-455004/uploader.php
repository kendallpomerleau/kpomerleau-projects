<?php
    session_start();
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    $username = $_SESSION['username'];
    //validate username
    if( !preg_match('/^[\w_\-]+$/', $username) ){
        echo "Invalid username.";
        ?>
        <button onclick="history.go(-1);">Back </button>
        <?php
	    exit;
    }

    require 'database.php';

    $article_name=$_POST['article_name'];
    $article_link=$_POST['article_url'];
    $article_blurb=$_POST['blurb'];

    //insert articles into articles table
    $stmt = $mysqli->prepare("INSERT into articles(article_name, article_link, blurb) values (?, ?, ?)");
    if(!$stmt){
	    printf("Query Prep Failed: %s\n", $mysqli->error);
	    exit;
    }

    $stmt->bind_param('sss', $article_name, $article_link, $article_blurb);
    $stmt->execute();
    $stmt->close();

    //GET ARTICLE ID
    $stmt3 = $mysqli->prepare("SELECT id from articles where article_name = ?");
    if(!$stmt3){
	    printf("Query Prep Failed: %s\n", $mysqli->error);
	    exit;
    }
    $stmt3->bind_param('s', $article_name);
    $stmt3->execute();
    $stmt3->bind_result($article_id_from_query);

    $article_id=0;

    while($stmt3->fetch()){
        $article_id = $article_id_from_query;
    }
    $stmt3->close();

    //upload articles into users table
    $stmt2 = $mysqli->prepare("INSERT into $username values (?, ?, ?, ?, ?)");
    if(!$stmt2){
	    printf("Query Prep Failed: %s\n", $mysqli->error);
	    exit;
    }

    $yes = 'y';
    $no = 'n';

    $stmt2->bind_param('sssss', $article_id, $yes, $no, $no, $no);
    $stmt2->execute();
    $stmt2->close();

    //upload articles into likes table
    $stmt4 = $mysqli->prepare("INSERT into likes(article_name, likes) values (?, ?)");
    if(!$stmt4){
	    printf("Query Prep Failed: %s\n", $mysqli->error);
	    exit;
    }

    $zero = 0;
    $stmt4->bind_param('ss', $article_name, $zero);
    $stmt4->execute();
    $stmt4->close();

    header("Location: uploadsuccess.html");
    exit;
?>
