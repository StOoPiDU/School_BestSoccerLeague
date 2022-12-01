<?php
/*
 * Final Project - Best Soccer League
 * Name: Cedric Pereira
 * Date: November 11, 2022
 * Description: Page to view all players in the database
 */
    require_once('connect.php');

    if (isset($_GET['team_id']))
    {
        $selectedTeam = $_GET['team_id'];

        $query = "SELECT * FROM player WHERE post_is_active = 1 && team_id = $selectedTeam ORDER BY player_id DESC";
        $statement = $db->prepare($query);
        $statement->execute(); 

        // This could have been accomplished with a join, but I'm incompetent. 
        $query2 = "SELECT * FROM teams WHERE team_id = $selectedTeam";
        $statement2 = $db->prepare($query2);
        $statement2->execute(); 
        $value = $statement2->fetch();
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Best Soccer League - <?= $value['abbreviation'] ?></title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="index.php">Best Soccer League - <?= $value['team_name']?></a></h1>
            <h2>All players for <?=$value['abbreviation']?> - <?=$value['team_name']?>!</h2>
        </div> 

        <form method ="post" action="search.php">
            <select name="category" class="form-control" id="sele">
                <option value="nationality" selected="selected">Nationality</option>
                <option value="goals">Goals</option>
                <option value="saves">Saves</option>
            </select>
            <input type="text" name="searchResult" placeholder="Search">
            <button type="submit">Search</button>
        </form>

        <ul id="menu">
            <li><a href="view_all.php" class='active'>List Of All Players</a></li>
            <li><a href="create.php">Add A New Player</a></li>
        </ul> 

        <div id="all_blogs">
            <?php if($statement->rowCount() != 0): ?>
            <!-- Fetch each table row. -->
                <?php while($row = $statement->fetch()): ?>
                <div id="blog_post">
                    <h2><a href="view.php?player_id=<?= $row['player_id'] ?>"><?= $row['first_name']?> <?=$row['last_name']?></a></h2>
                    <h3><?=$row['position']?></h3>
                    <h3><?=$row['nationality']?></h3>
                    <p><small><a href="edit.php?player_id=<?= $row['player_id'] ?>">Edit Player</a></small></p>
                </div>
                <?php endwhile ?> 
        
            <?php else: ?> 
                <h1> No players found. The league is dead!</h1>
            <?php endif ?> 
        </div>
        <div id="footer"> Copyright YadaYada - Please Give Me 100% Marks :D</div>
    </div>
</body>
</html> 