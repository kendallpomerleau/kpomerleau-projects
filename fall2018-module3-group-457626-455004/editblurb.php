<?php
    session_start();
    require 'database.php';
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $blurbedit = $_POST['blurbedit'];
    //GET ARTICLE NAME FROM PREVIOUS FORM THEY ENTERED
    $article_edit_session =  $_SESSION['article_name'];

    if( !preg_match('/^[\w_\-]+$/', $username) ){
        echo "Invalid username.";
        ?>
        <button onclick="history.go(-1);">Back </button>
        <?php
    exit;
    }

    


    // INSERT NEW BLURB INTO BLURB AREA WHERE ARTICLE NAME IS SAME FROM BEFORE
    $stmt = $mysqli->prepare("UPDATE articles set blurb=? where article_name=?");
    if(!$stmt){
        printf("Query Prep Failed hi: %s\n", $mysqli->error);
        exit;
    }
    $stmt->bind_param('ss', $blurbedit, $article_edit_session);
    $stmt->execute();
    $stmt->close();
    
    //SEND TO BACK TO PROFILE
        header("Location: userpage.php");
        exit;



?>