<?php
/*
 * Assignment 3
 * Name: Cedric Pereira
 * Date: November 17, 2022
 * Description: Logout page for Final Project
 */

session_destroy();
header("location:index.php");
?>