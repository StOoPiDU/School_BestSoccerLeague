<?php
/*
 * Final Project - Best Soccer League
 * Name: Cedric Pereira
 * Date: November 27, 2022
 * Description: Adding a comment to a player.
 */
    require_once('authenticate.php'); 
    require_once('connect.php');

    if (isset($_GET['player_id'])) 
    {
        $player_id = $_GET['player_id'];
    }

    $captchaTest = "";
    
    if(isset($_SESSION['code']))
    {
        $captchaTest = $_SESSION['code'];
    }
    if(empty($_POST['captcha']))
    {

    }
    elseif($_POST['captcha'] == $captchaTest)
    {
        if(empty($_POST['title']) || empty($_POST['comment']))
        {
            $error = "empty fields";
        }
        else
        {
            //$player_id = filter_input(INPUT_POST, 'player_id', FILTER_SANITIZE_NUMBER_INT); Okay, this causes it to break.
            $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $comment = filter_input(INPUT_POST,'comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            
            $query = "INSERT INTO comment (player_id, title, comment, name, date) VALUES (:player_id, :title, :comment, :name, now())";
            $statement = $db->prepare($query);

            $statement->bindValue(':player_id', $player_id);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':comment', $comment);
            $statement->bindValue(':name', $name);
            $statement->execute();
            $fetch = $statement->fetch();
            header("Location:view.php?player_id=$player_id");
        }
    }

    // <?php echo $player_id;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Comment</title>
    <link rel="stylesheet" type="text/css" href="bootstrap.css"/>
    <script src="//cdn.ckeditor.com/4.6.0/full/ckeditor.js"></script>
</head>
<body>
    <form action="#" method="POST">
    <ul>
        <li>
            <label>Title</label>
            <input type="text" name="title">
        </li>
        <li>
            <label>Comment</label>
            <textarea id="" name="comment" rows="5" cols="50"></textarea>
            <script>CKEDITOR.replace('ckeditor',{
                enterMode : CKEDITOR.ENTER_BR
            });</script>
        </li>
        <label>Enter Captcha</label>
        <img src="captcha.php" id="cap" />
        <input type="text" name="captcha"/>
        <li>
            <label>Name</label>
            <input type="text" name="name">
        </li>
        <li>
            <button type="submit">Submit Comment</button>
        </li>
    </ul>
    <a href="index.php">Home</a>
</body>
</html>