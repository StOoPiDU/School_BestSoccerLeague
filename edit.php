<?php
/*
 * Final Project - Best Soccer League
 * Name: Cedric Pereira
 * Date: November 11, 2022
 * Description: Editing a player
 */
    require_once('authenticate.php');
    require_once('connect.php');
    $failed = false;
     
    if ($_POST) 
    {
        // UPDATE player if names, position, nationality, team_id, and player_id are present in POST.
        if ($_POST['command'] == "Update" && !empty($_POST['first_name']) && !empty($_POST['last_name']) 
            && !empty($_POST['team_id']) && filter_input(INPUT_POST, 'player_id', FILTER_VALIDATE_INT))
        {
            // Sanitize user input to escape HTML entities and filter out dangerous characters.
            $player_id = filter_input(INPUT_POST, 'player_id', FILTER_SANITIZE_NUMBER_INT);
            $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $position = filter_input(INPUT_POST, 'position', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $nationality = filter_input(INPUT_POST, 'nationality', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $team_id = filter_input(INPUT_POST, 'team_id', FILTER_SANITIZE_NUMBER_INT);
            
            // Build the parameterized SQL query and bind to the above sanitized values.
            $query = "UPDATE player SET first_name = :first_name, last_name = :last_name, 
                                        position = :position, nationality = :nationality,
                                        team_id = :team_id WHERE player_id = :player_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':player_id', $player_id, PDO::PARAM_INT);
            $statement->bindValue(':first_name', $first_name);        
            $statement->bindValue(':last_name', $last_name);
            $statement->bindValue(':position', $position);        
            $statement->bindValue(':nationality', $nationality);
            $statement->bindValue(':team_id', $team_id);
            
            // Execute the INSERT.
            $statement->execute();
            
            // Redirect after edit.
            header("Location: view.php?player_id={$player_id}");
            exit;
        }
        elseif ($_POST['command'] == "Delete" && filter_input(INPUT_POST, 'player_id', FILTER_VALIDATE_INT))
        {
            // This isn't working overall, but I also think this is wrong. \/
            //$post_is_active = filter_input(INPUT_POST, 'post_is_active', FILTER_SANITIZE_NUMBER_INT);

            $player_id = filter_input(INPUT_POST, 'player_id', FILTER_SANITIZE_NUMBER_INT);

            $query = "UPDATE player SET post_is_active = 0 WHERE player_id = :player_id";
            $statement = $db->prepare($query);
            $statement->bindValue(':player_id', $player_id, PDO::PARAM_INT);
            $statement->execute(); 
            header("Location: index.php");
            exit;
        }
    } elseif (isset($_GET['player_id'])) {
        // Sanitize the player_id. Like above but this time from INPUT_GET.
        $player_id = filter_input(INPUT_GET, 'player_id', FILTER_SANITIZE_NUMBER_INT);
        
        // Build the parametrized SQL query using the filtered player_id.
        $query = "SELECT * FROM player WHERE player_id = :player_id";
        $statement = $db->prepare($query);
        $statement->bindValue(':player_id', $player_id, PDO::PARAM_INT);
        
        // Execute the SELECT and fetch the single row returned.
        $statement->execute();
        $value = $statement->fetch();
        //deb($value,1);
    }
    else { $failed = false; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Best Soccer League - Edit Player</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <div id="wrapper">

        <form method ="post" action="search.php">
            <select name="category" class="form-control" id="sele">
                <option value="nationality" selected="selected">Nationality</option>
                <option value="goals">Goals</option>
                <option value="saves">Saves</option>
            </select>
            <input type="text" name="searchResult" placeholder="Search">
            <button type="submit">Search</button>
        </form>

        <div id="header">
            <h1><a href="index.php">Best Soccer League - Edit Player</a></h1>
        </div> 

        <ul id="menu">
            <li><a href="view_all.php" class='active'>List Of All Players</a></li>
            <li><a href="create.php">Add A New Player</a></li>
        </ul> 
        <div id="all_blogs">
            <form method="post" action="">
                <fieldset>
                    <legend>Edit Player</legend>
                    <input type="hidden" name="player_id" value="<?= $value['player_id'] ?>">
                    <p>
                        <label for="first_name">First Name</label>
                        <input id="first_name" name="first_name" value="<?= $value['first_name'] ?>">
                    </p>
                    <p>
                        <label for="last_name">Last Name</label>
                        <input id="last_name" name="last_name" value="<?= $value['last_name'] ?>">
                    </p>
                    <p>
                        <label for="position">Position</label>
                        <input id="position" name="position" value="<?= $value['position'] ?>">
                    </p>
                    <p>
                        <label for="nationality">Nationality</label>
                        <input id="nationality" name="nationality" value="<?= $value['nationality'] ?>">
                    </p>
                    <p>
                        <label for="team_id">Team</label>
                        <input id="team_id" name="team_id" value="<?= $value['team_id'] ?>">
                    </p>
                    <input type="submit" name="command" value="Update" />
                    <input type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this post?')" />
                </fieldset>
            </form>
        </div>
        <?php if($failed): ?>
            <h1>You botched it.</h1>
        <?php endif ?>
        <div id="footer"> Copywrong 2022 - No Rights Reserved</div>
    </div>
</body>
</html>