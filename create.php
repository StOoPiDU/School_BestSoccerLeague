<?php
/*
 * Final Project - Best Soccer League
 * Name: Cedric Pereira
 * Date: November 11, 2022
 * Description: Adding a player for the database
 */
    require_once('authenticate.php'); 
    require_once('connect.php');
    $failed = false;

    if ($_POST && !empty($_POST['first_name']) && !empty($_POST['last_name']) && !empty($_POST['position']) && !empty($_POST['nationality']) && !empty($_POST['team_id'])) 
    {
        //  Sanitize user input to escape HTML entities and filter out dangerous characters.
        $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $position = filter_input(INPUT_POST, 'position', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $nationality = filter_input(INPUT_POST, 'nationality', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $team_id = filter_input(INPUT_POST, 'team_id', FILTER_SANITIZE_NUMBER_INT);
        
        //  Build the parameterized SQL query and bind to the above sanitized value.
        $query = "INSERT INTO player (first_name, last_name, position, nationality, team_id) VALUES (:first_name, :last_name, :position, :nationality, :team_id)";
        $statement = $db->prepare($query);
        
        //  Bind values to the parameters
        $statement->bindValue(':first_name', $first_name);
        $statement->bindValue(':last_name', $last_name);
        $statement->bindValue(':position', $position);
        $statement->bindValue(':nationality', $nationality);
        $statement->bindValue(':team_id', $team_id);
        $statement->execute(); 

        // Returning to main page after completion
        header('Location: index.php');
        exit;
    }
    else if ($_POST && (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['position']) || empty($_POST['nationality']) || empty($_POST['team_id'])))
    { 
        $failed = true; 
    }





    /* THIS CAN BE USED FOR THE NOTES ON VALUES
                    <p>
                        <label for="content">Content</label>
                        <textarea name="content" id="content"></textarea>
                    </p>

        Add a better way to select certain things (drop downs)
    */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Best Soccer League - Add New Player</title>
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
            <h1><a href="index.php">Best Soccer League - Add New Player</a></h1>
        </div> 

        <ul id="menu">
            <li><a href="view_all.php" class='active'>List Of All Players</a></li>
            <li><a href="create.php">Add A New Player</a></li>
        </ul> 
        <div id="all_blogs">
            <form method="post" action="create.php">
                <fieldset>
                    <legend>Add A New Player</legend>
                    <p>
                        <label for="first_name">First Name</label>
                        <input id="first_name" name="first_name">
                    </p>
                    <p>
                        <label for="last_name">Last Name</label>
                        <input id="last_name" name="last_name">
                    </p>
                    <p>
                        <label for="position">Position</label>
                        <input id="position" name="position">
                    </p>
                    <p>
                        <label for="nationality">Nationality</label>
                        <input id="nationality" name="nationality">
                    </p>
                    <p>
                        <label for="team_id">Team</label>
                        <input id="team_id" name="team_id">
                    </p>
                    <input type="hidden" name="player_id">
                    <input type="submit" value="Create">
                </fieldset>
            </form>
            <?php if($failed): ?>
                <h1>You botched it.</h1>
            <?php endif ?>
        </div>
        <div id="footer"> Copyright YadaYada - Please Give Me 100% Marks :D</div>
    </div>
</body>
</html>