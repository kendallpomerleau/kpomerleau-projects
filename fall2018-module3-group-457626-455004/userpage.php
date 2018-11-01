<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Profile Page</title>
    <link rel="stylesheet" type="text/css" href="mystyle.css">
</head>
<body>
    <?php
        session_start();

        $username = $_SESSION['username'];
        $user_id = $_SESSION['user_id'];
        //$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
        //validate username
        if( !preg_match('/^[\w_\-]+$/', $username) ){
            echo "Invalid username.";
            ?>
            <button onclick="history.go(-1);">Back </button>
            <?php
	    exit;
        }

    ?>
    <h1 class="uph"> Welcome <?php echo $username ?> </h1>
    <h2 class="uph2"> My Articles </h2>

    <!-- DISPLAY USER'S UPLOADED ARTICLES AND BLURBS-->
    <?php
        require 'database.php';

        $stmt = $mysqli->prepare("SELECT ar.article_name, ar.article_link, ar.blurb FROM articles ar join $username un on (ar.id=un.article_id) where uploaded='y'");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->execute();
        $result = $stmt->get_result();

        //used https://stackoverflow.com/questions/21258620/check-if-the-query-results-empty-row-mysqli
        //to find how to find if result set is empty
        if ($result->num_rows === 0){
            echo "You have not yet uploaded any articles.";
        }
        else {
            //used https://stackoverflow.com/questions/18916966/add-php-variable-inside-echo-statement-as-href-link-address
            //to find how to use href in php 
            echo "<ul>\n";
            while($row = $result->fetch_assoc()){
                //used https://stackoverflow.com/questions/36150491/how-to-open-link-in-new-tab-in-php
                //to find how to open link in new page
                printf("\t<li>%s</li>\n",
                    '<a href="'.$row["article_link"].'"target="_blank">'.$row["article_name"].'</a>'
                );
                printf("\n\t%s\n",$row["blurb"]);
            }
            echo "</ul>\n";
        }
        $stmt->close();
    ?>
    <div id="bt1">
        <form name="input" action="inputdeletefile.php" method="POST"> <!--Delete Comment-->
            <input type="submit" value="Delete an Article"/>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        </form>
    </div>
    <div id="bt3">
        <form name="input" action="inputblurbedit.php" method="POST"> <!--Edit Comment-->
            <input type="submit" value="Edit a Blurb"/> <br>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        </form>
    </div>

    <h2 class="uph2"> <br>My Comments </h2>



    <!-- DISPLAY USER'S COMMENTED ARTICLES-->
    <?php
        require 'database.php';

        //find articles that have been commented on by that user
        $stmt = $mysqli->prepare("SELECT ar.article_name, ar.article_link FROM articles ar join $username un on (ar.id=un.article_id) where commented='y'");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->execute();
        $result = $stmt->get_result();

        //used https://stackoverflow.com/questions/21258620/check-if-the-query-results-empty-row-mysqli
        //to find how to find if result set is empty
        if ($result->num_rows === 0){ //if no comments yet
            echo "You have not yet commented on any articles.";
        }
        else { //if the user has commented
            //used https://stackoverflow.com/questions/18916966/add-php-variable-inside-echo-statement-as-href-link-address
            //to find how to use href in php 
            echo "<ul>\n";
            while($row = $result->fetch_assoc()){
                //used https://stackoverflow.com/questions/36150491/how-to-open-link-in-new-tab-in-php
                //to find how to open link in new page
                printf("\t<li>%s</li>\n",
                    '<a href="'.$row["article_link"].'"target="_blank">'.$row["article_name"].'</a>'
                );
                printf("\n%s",'My Comments:');
                $stmt2 = $mysqli->prepare("SELECT comment FROM comments cc join articles aa on (aa.id=cc.article_id) where aa.article_name=? and cc.user_id=?");
                $stmt2->bind_param('ss',$row["article_name"],$user_id);
                $stmt2->execute();
                $result2 = $stmt2->get_result();
                echo "<ol>\n";
                while($row = $result2->fetch_assoc()){
                    //used https://stackoverflow.com/questions/36150491/how-to-open-link-in-new-tab-in-php
                    //to find how to open link in new page
                    printf("\t<li>%s</li>\n",
                        htmlspecialchars($row['comment'])
                    );    
                }
                echo "</ol>\n";
                $stmt2->close();
            }
            echo "</ul>\n";
        }
        $stmt->close();
    ?>
    <div id="bt1">
        <form name="input" action="inputdeletecomment.php" method="POST"> <!--Delete Comment-->
            <input type="submit" value="Delete a Comment"/>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        </form>
    </div>
    <div id="bt3">
        <form name="input" action="inputeditcomment.php" method="POST"> <!--Edit Comment-->
            <input type="submit" value="Edit a Comment"/> <br>
            <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
        </form>
    </div>

    <form name="input" action="uploader.php" method="POST"> <!--Upload Article-->
        <br><h2 class="uph2"> Upload an article </h2> 
        URL:
        <input type="url" name="article_url"/> <br>
        Article name:
        <input type="text" name="article_name"/><br>
        Please write a short blurb (under 500 characters) about this article:
        <input type="text" name="blurb"/><br>
        <input type="submit" value="Upload"/>
        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
    </form>
    <form name="input" action="news.php">
        <input type="submit" value="Back to News"/>
    </form>
</body>
</html>