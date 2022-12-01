<?php
/*
 * Final Project - Best Soccer League
 * Name: Cedric Pereira
 * Date: November 18, 2022
 * Description: Login page for Final Project
 */
require_once('connect.php');

if(empty($_POST['username']) || empty($_POST['password']) || empty($_POST['confirmedpassword']))
{
    $nomatch = "Empty fields";
}
else
{
    if(($_POST['password']) == ($_POST['confirmedpassword'])){
        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO user (username, password) value (:username, :password)";
        $statement = $db->prepare($query);
        $statement->bindValue(':username',$username);
        $statement->bindValue(':password',$password);
        $statement->execute();

        header("Location:login.php");
    }
    else
    {
        $nomatch = "Passwords do not match.";

        echo $nomatch;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Best Soccer League - Sign Up</title>
    <link rel="stylesheet" type="text/css" href="Eventually = bootstrap.css"/>
</head>
<body>
    <form action='login.php' method="POST">
        <ul>
            <li>
                <label>Username:</label>
                <input type="text" name="username" placeholder="Enter Username">
            </li>
            <li>
                <label>Password:</label>
                <input type="password" name="password">
            </li>
            <li>
                <label>Confirm Password:</label>
                <input type="password" name="confirmedpassword">
            </li>
            <li>
                <button>Sign Up</button>
            </li>
        </ul>
    </form>
    <a href="index.php">Home</a> <a href="login.php">Login</a>
</body>
</html>