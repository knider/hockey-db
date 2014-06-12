<?php
	//Report all PHP errors 
	error_reporting(E_ALL);
	ini_set('display_errors', 'On');
	//Connect to database
	$mysqli = new mysqli("oniddb.cws.oregonstate.edu","niderk-db","8qV5RXYryvcPMSf8","niderk-db");
	
	/* check connection */
	if (mysqli_connect_errno()) {
	    echo "Connection error " . $mysqli->connect_errno . " " . $mysqli->connect_error;
	}
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
<head>
	<title>CS 275 Project: NHL Database: All Players</title>
	<link type="text/css" rel="stylesheet" href="db.css" media="all" />
	
</head>
<body>


<div id="playersTable" class="statsTable" style="width:50%;">
	<h2>All Players:</h2>
		
	
		<table id="playerTable">
			<tr>
				<td><strong>Team</strong></td>
				<td><strong>Player Name</strong></td>
				<td><strong>Jersey #</strong></td>
				<td><strong>Position</strong></td>
			</tr>
			
				<?php
					if(!($stmt = $mysqli->prepare("SELECT TEAMS.teamID, P.playerID, P.firstName, P.lastName, P.jersey, P.position 
					FROM PLAYERS P
					INNER JOIN PLAYERS_TEAMS ON P.playerID = PLAYERS_TEAMS.playerID 
					INNER JOIN TEAMS ON TEAMS.teamID = PLAYERS_TEAMS.teamID
					ORDER BY P.playerID ASC"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->execute())) {
						echo "Execute failed ". $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->bind_result($teamID, $playerID, $firstName, $lastName, $jersey, $position))){
						echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					}
					
					
					while($stmt->fetch()){
						echo '<tr><td>' . $teamID .  '</td><td>' . $firstName . ' ' . $lastName . '</td><td>' .  $jersey . '</td><td>' . $position . '</td></tr>';
					}
				
				?>
		
				
			
		</table>
	</div> <!-- /playertable -->
</body>
</html>