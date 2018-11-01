<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WUSTL Weekly</title>
    <link rel="stylesheet" type="text/css" href="mystyle.css">
    
</head>
<body>
    <h1 class="nh"> News! </h1>
    <hr/>
    
    <?php
        session_start();
        $username=$_SESSION['username'];

        require 'database.php';

        $stmt = $mysqli->prepare("SELECT article_name, article_link FROM articles");
        if(!$stmt){
            printf("Query Prep Failed: %s\n", $mysqli->error);
            exit;
        }
        $stmt->execute();
        $result = $stmt->get_result();

        //display webpages on news site

        //used https://stackoverflow.com/questions/18916966/add-php-variable-inside-echo-statement-as-href-link-address
        //to find how to use href in php 
        while($row = $result->fetch_assoc()){
            //used https://stackoverflow.com/questions/36150491/how-to-open-link-in-new-tab-in-php
            //to find how to open link in new page

            printf("\t%s\n",
                '<a href="'.$row["article_link"].'" target="_blank">'.$row["article_name"].'</a>'
            );  

            //display blurb
            $stmt4 = $mysqli->prepare("SELECT blurb FROM articles where article_name=?");
            if(!$stmt4){
                printf("Query Prep Failed: %s\n", $mysqli->error);
                exit;
            }
            $stmt4->bind_param('s',$row["article_name"]);
            $stmt4->execute();
            $result3 = $stmt4->get_result();
            while($row3 = $result3->fetch_assoc()){
                ?>
                <br/>
                
                <?php
                printf("\t%s\n",
                    htmlspecialchars($row3['blurb'])
                );    
            }
            $stmt4->close();

            //display comments  
            ?>
            
            <?php
            $stmt2 = $mysqli->prepare("SELECT comment FROM comments cc join articles aa on (aa.id=cc.article_id) where aa.article_name=?");
            $stmt2->bind_param('s',$row["article_name"]);
            $stmt2->execute();
            $result2 = $stmt2->get_result();
            if ($result2->num_rows != 0){ //if comments exist
                ?>
                <br/>
                <div class="cmt">
                <?php
                printf("\n%s",'Comments:');
                ?>
                </div> 
                <ol> 
                <?php
                while($row2 = $result2->fetch_assoc()){
                    ?>
                    <li> <?php echo htmlspecialchars($row2['comment']); ?> </li>
                    <?php  
                }
                ?>
                </ol>
                <?php
            }
            $stmt2->close();

            ?>
            <br>
            <?php


            //display four buttons
            if ($username!='guest'){
            echo "Options:";
            ?>
            <div class= "buttons">
                <div class="bt1">
                    <form name="input" action="likes.php" method="POST">
                        <input type="submit" name="like" value="Like"/>
                        <input type="hidden" name="article_name" value="<?= $row['article_name']?>"/>
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                    </form>
                </div>
                <div class="bt2">
                    <form name="input" action="dislike.php" method="POST">
                        <input type="submit" name="dislike" value="Dislike"/>
                        <input type="hidden" name="article_name" value="<?= $row['article_name']?>"/>
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                    </form>
                </div>
                <div class="bt3">
                    <form name="input" action="comment.php" method="POST">
                        <input type="submit" value="Comment"/>
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token'];?>" />
                    </form>
                </div>
                <!-- used https://simplesharebuttons.com/html-share-buttons/  -->
                <!-- to find how to link to email -->
                <!-- used http://www.hyperlinkcode.com/button-links.php -->
                <!-- to find how to make link a button -->
                <div class="bt4">
                    <form>
                        <input type="button" value="Email This Article"
                        onclick="window.location.href='mailto:?Subject=News%20Update!&amp;Body=I%20saw%20this%20and%20thought%20of%20you!%20<?=$row['article_link']?>'"/>
                    </form>
                </div>
                <div class="bt5">
                    <form>
                        <input type="button" value="Share to Facebook"
                        onclick="window.location.href='http://www.facebook.com/sharer.php?u=<?=$row['article_link']?>'"/>
                    </form>
                </div>
            </div>
                <?php
            }

                //display number of likes
                $stmt3 = $mysqli->prepare("SELECT likes FROM likes ll where ll.article_name=?");
                if(!$stmt3){
                    printf("Query Prep Failed: %s\n", $mysqli->error);
                    exit;
                }
                $article_name=$row["article_name"];
                $stmt3->bind_param('s',$article_name);
                $stmt3->execute();
                $stmt3->bind_result($numlikes_from_query);
                $numlikes=0;
                while($stmt3->fetch()){
                    $numlikes = $numlikes_from_query;
                }
                $stmt3->close();

                ?>
                <div>
                    <p>
                        <label> Number of Likes: <?php echo $numlikes ?> </label>
                    </p>
                </div>
            <?php
        }
        $stmt->close();
    if ($username!='guest'){
    ?>

    <form name="input" action="userpage.php" method="GET"> <!--Profile Page-->
        <br>Click to go to your profile page.
        <input type="submit" value="My Profile"/>
    </form>
    <?php
    }
    ?>
    <form name="input" action="logout.php" method="GET"> <!--LOGOUT-->
        <br> <input type="submit" value="Logout"/>
    </form>
</body>
</html>