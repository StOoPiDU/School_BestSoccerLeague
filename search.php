<?php
/*
 * Final Project - Best Soccer League
 * Name: Cedric Pereira
 * Date: November 20, 2022
 * Description: Search page for Final project.
 */
    require_once('connect.php');
    $searchResult ="";

    $selection = $_POST['category'];

    /*// SQL is written as a String.
    $query = "SELECT * FROM player ORDER BY player_id DESC";

    // A PDO::Statement is prepared from the query.
    $statement = $db->prepare($query);

    // Execution on the DB server is delayed until we execute().
    $statement->execute(); */

    if((empty($_POST['searchResult'])))
    {
        //header("location:index.php");
    }
    else
    {
        $searchResult = $_POST['searchResult'];
    }

    $query ='';
    $sql_for_pagination = '';

    if($selection == "nationality")
    {
        $sql_for_pagination = "SELECT COUNT(*) FROM player WHERE nationality LIKE '$searchResult%'";
    }
    elseif($selection == "goals")
    {
        $sql_for_pagination = "SELECT COUNT(*) FROM player_fielder WHERE goals LIKE '$searchResult%'";
    }
    else
    {
        $sql_for_pagination = "SELECT COUNT(*) FROM player_keeper WHERE saves LIKE '$searchResult%'";
    }

    $statement = $db->prepare($sql_for_pagination);
    $statement->execute();

	$page_rows = $db->prepare($sql_for_pagination); 
	$page_rows->execute();
	$row_count = $page_rows->rowCount();
	$row_count = $page_rows->fetch();
	
	//rowssss variable for number count
	$rowsss = $row_count[0];

	//limited to 5 items per page
	$page_rows = 5;

	$last = ceil($rowsss/$page_rows);

	if ($last < 1) 
	{
		$last = 1;
	}

	$pagenum = 1;

	if(isset($_GET['page']))
	{
		$pagenum = preg_replace('#[^0-9]#', '', $_GET['page']);
	}

	if ($pagenum < 1)
	{ 
	    $pagenum = 1; 
	} 
	else if ($pagenum > $last) 
	{ 
	    $pagenum = $last; 
	}

	$limit = 'LIMIT ' .($pagenum - 1) * $page_rows .',' .$page_rows;

	$sql_2 = "SELECT * FROM player ORDER BY last_name";

	$textline1 = "(<b>$rowsss</b>)";
	$textline2 = "Page <b>$pagenum</b> of <b>$last</b>";

	$paginationCtrls = '';

	if($last != 1)
	{
		if ($pagenum > 1) 
		{
	        $previous = $pagenum - 1;
			$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?page='.$previous.'">Previous</a> &nbsp; &nbsp;';
			
			for($i = $pagenum-4; $i < $pagenum; $i++)
			{
				if($i > 0)
				{
			        $paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?page='.$i.'">'.$i.'</a>&nbsp;';
				}
		    }
	    }

	    $paginationCtrls .= ''.$pagenum.' &nbsp;';

	    for($i = $pagenum+1; $i <= $last; $i++)
	    {
			$paginationCtrls .= '<a href="'.$_SERVER['PHP_SELF'].'?page='.$i.'">'.$i.'</a>&nbsp;';
			
			if($i >= $pagenum+4)
			{
				break;
			}
		}

		if ($pagenum != $last) 
		{
	        $next = $pagenum + 1;
	        $paginationCtrls .= '&nbsp; &nbsp;<a href="'.$_SERVER['PHP_SELF'].'?page='.$next.'">Next</a>';
	    }
    }
    
    if($selection == 'nationality'){
        $query = "SELECT * FROM player WHERE nationality LIKE '%$searchResult%'";
        $statement2 = $db->prepare($query);
        $statement2->execute();
    }
    elseif($selection == 'goals'){
        $query = "SELECT * FROM player_fielder WHERE goals LIKE '%$searchResult%'";
        $statement2 = $db->prepare($query);
        $statement2->execute();  
    }
    elseif($selection =='saves')
    {
        $query = "SELECT * FROM player_keeper WHERE saves LIKE '%$searchResult%'";
        $statement2 = $db->prepare($query);
        $statement2->execute();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Best Soccer League - Search</title>
  <link rel="stylesheet" href="css/bootstrap-grid.css">
</head>

<body>
    <nav>
        <ul></ul>
    </nav>

    <div id="wrapper">
    <form method ="post" action="search.php">
    <select name="category" class="form-control" id="sele">
            <option value="nationality" selected="selected">Nationality</option>
            <option value="goals">Goals</option>
            <option value="saves">Saves</option>
    </select>
    <input type="text" name="searchResult" placeholder="Canada, 11, or 1" value="<?=((isset($_POST['searchResult']))?($_POST['searchResult']):(""))?>">
        <button type="submit">Search</button>
    </form>
    </div>
        <a href="create.php">Add a Player</a>
        <a href="index.php">Go Back Home</a>
        <p>Ordered A-Z</p>
        <ul>
        <?php if($selection == 'nationality'): ?>
            <?php while($row = $statement2->fetch()): ?>
                <li><?= $row['first_name']?> <?=$row['last_name']?></li>
                <li><?= $row['nationality'] ?></li> 
                <a href= "view.php?player_id=<?=$row['player_id']?>">View Player</a>
            <?php endwhile ?>
        <?php endif?>

        <?php if($selection == 'goals'): ?>
            <?php while($row = $statement2->fetch()): ?>
                <li><?= $row['player_id'] ?></li>
                <li><?= $row['goals'] ?></li>
                <a href= "view.php?player_id=<?=$row['player_id']?>">View Player</a>
            <?php endwhile ?>
        <?php endif?>

        <?php if($selection == 'saves'): ?>
            <?php while($row = $statement2->fetch()): ?>
                <li><?= $row['player_id'] ?></li>
                <li><?= $row['saves'] ?></li>
                <a href= "view.php?player_id=<?=$row['player_id']?>">View Player</a>
            <?php endwhile ?>
        <?php endif?>
        </ul>
        <div id="pagination">
	  	<p><?php echo $textline2; ?></p>
	  	<div id="pagination_controls"><?php echo $paginationCtrls; ?></div>
  	</div>
    </div>
</body>
</html>
