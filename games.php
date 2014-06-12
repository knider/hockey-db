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
	<title>CS 275 Project: NHL Database: All Games</title>
	<link type="text/css" rel="stylesheet" href="db.css" media="all" />
	
</head>
<body>


<div id="teamsTable" class="statsTable">
	<h2>All Games:</h2>
		
	
		<table id="gamesTable">
			<tr>
				<td><strong>Game Date</strong></td>
				<td><strong>Home Team</strong></td>
				<td><strong>Away Team</strong></td>
				<td><strong>Home Shots</strong></td>
				<td><strong>Away Shots</strong></td>
				<td><strong>Home Penalty Min</strong></td>
				<td><strong>Away Penalty Min</strong></td>
				<td><strong>Game Status</strong></td>
			</tr>
			<tr>
				<?php
					if(!($stmt = $mysqli->prepare("select * from GAMES ORDER BY GAMES.gameID ASC"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($gameID, $gameDate, $homeTeamID, $awayTeamID, $gameStatus, $homeShots, $awayShots, $homePenaltyMin, $awayPenaltyMin)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo "<tr><td>" . $gameDate . "</td><td>" . $homeTeamID . "</td><td>" . $awayTeamID . "</td><td>" . $homeShots . "</td><td>" . $awayShots . "</td><td>" . $homePenaltyMin . "</td><td>" . $awayPenaltyMin . "</td><td>" . $gameStatus . "</td></tr>";
					}
					$stmt->close();
				?>
		
				
			</tr>
		</table>
	</div> <!-- /gamestable -->
</body>
</html>