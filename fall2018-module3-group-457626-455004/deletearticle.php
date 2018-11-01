<?php
    session_start();
    if(!hash_equals($_SESSION['token'], $_POST['token'])){
        die("Request forgery detected");
    }
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $article_to_be_deleted = $_POST['article_name'];

    //$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    //validate username
    if( !preg_match('/^[\w_\-]+$/', $username) ){
        echo "Invalid username.";
        ?>
        <button onclick="history.go(-1);">Back </button>
        <?php
    exit;
    }

    require 'database.php';

    //getting articles that specific user has uploaded
    $stmt = $mysqli->prepare("SELECT ar.article_name FROM articles ar join $username un on (ar.id=un.article_id) where uploaded='y'");
    if(!$stmt){
        printf("Query Prep Failed this query: %s\n", $mysqli->error);
        exit;
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $user_uploaded_file = false;

    if ($result->num_rows === 0){ //if no files have been uploaded
        header("Location: nofilesuploaded.html");
        exit;    
    }
    else {
        while($row = $result->fetch_assoc()){
            if ($article_to_be_deleted == $row["article_name"]){
                $user_uploaded_file=true;
            }
        }
    }
    $stmt->close();

    if ($user_uploaded_file){
        //GET ARTICLE ID OF ARTICLE TO DELETE BASED ON ARTICLE NAME FROM ARTICLES TABLE
        $stmt3 = $mysqli->prepare("SELECT id from articles where article_name = ?");
        if(!$stmt3){
            printf("Query Prep Failed second query: %s\n", $mysqli->error);
            exit;
        }
        $stmt3->bind_param('s', $article_to_be_deleted);
        $stmt3->execute();
        $result = $stmt3->get_result();
        while($row = $result->fetch_assoc()){
            $article_id = $row['id'];
        }
        $stmt3->close();

        //FIND ALL USERS WHO HAVE THAT ARTICLE IN THEIR USER SPECIFIC TABLE AND DELETE IT
        $stmt4=$mysqli->prepare("SELECT username from users");
        if(!$stmt4){
            printf("Query Prep Failed third query: %s\n", $mysqli->error);
            exit;
        }
        $stmt4->execute();
        $result2 = $stmt4->get_result();
        while($row2 = $result2->fetch_assoc()){
            $username2 = $row2['username'];

            $stmt5 = $mysqli->prepare("DELETE from $username2 where article_id = ?");
            if(!$stmt5){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt5->bind_param('s',$article_id);
            $stmt5->execute();
            $stmt5->close();
        }
        $stmt4->close();


        //DELETE ARTICLE FROM DATABASE (COMMENTS, LIKES, ARTICLES)
    
        $stmt6 = $mysqli->prepare("DELETE from comments where article_id=?"); //delete from comments
        if(!$stmt6){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt6->bind_param('s',$article_id);
        $stmt6->execute();
        $stmt6->close();

        $stmt7 = $mysqli->prepare("DELETE from likes where article_name=?"); //delete from likes
        if(!$stmt7){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt7->bind_param('s',$article_to_be_deleted);
        $stmt7->execute();
        $stmt7->close();
        
        
        $stmt2 = $mysqli->prepare("DELETE FROM articles where article_name=?"); //delete from articles
        if(!$stmt2){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt2->bind_param('s',$article_to_be_deleted);
        $stmt2->execute();

        //SEND TO DELETE SUCCESSFUL PAGE
        header("Location: deletesuccessful.php");
        exit;
    }
    else { //if article is not uploaded by that user
        //SEND TO DELETE UNSUCCESSFUL PAGE
        header("Location: deleteunsuccessful.php");
        exit;
    }
?>