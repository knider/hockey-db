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
	<title>CS 275 Project: NHL Database: Game Stats - Team Summary</title>
	<link type="text/css" rel="stylesheet" href="db.css" media="all" />
	
</head>
<body>


	
<div id="teamSummaryTable" class="statsTable">
	<h2>Team Summary for:
		<?php
		
			# Declare POST variables
			$statTeam = $_POST['teamSummaryTeam'];
			$statDate = $_POST['teamSummaryDate'];
			
			# First get game ID
			if(!($stmt = $mysqli->prepare("select gameID from GAMES where gameDate = '$statDate' and (homeTeamID = '$statTeam' or awayTeamID = '$statTeam')"))) 
			{
				echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!($stmt->execute())) 
			{
				echo "Execute failed ". $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			
			if(!($stmt->bind_result($gameID))){
				echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			$stmt->fetch();
			
			# Check if the team played on the specified date
			if(empty($gameID))
			{
				echo "Try again. " . $statTeam . " did not play on " . $statDate;
				exit; # do not process additional queries if this failed					
			}
			$stmt->close();
			
			# Now get some basic game details
			if(!($stmt = $mysqli->prepare("select homeTeamID, awayTeamID, 
					case when gameStatus = 'RT' then 'Regulation Win' when gameStatus = 'OT' then 'Overtime Win' else 'Shootout Win' end as gameType, 
					case when (homeGoals1 + homeGoals2 + homeGoals3 + homeGoals4 + homeGoals5) > 
					(awayGoals1 + awayGoals2 + awayGoals3 + awayGoals4 + awayGoals5) then homeTeamID else awayTeamID end as winTeamID
			from GAMEVIEW where gameID = $gameID"))) 
			{
				echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!$stmt->execute())
			{
				echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				exit; # do not process additional queries if this failed					
			}
			if(!$stmt->bind_result($hTeam, $aTeam, $gStatus, $winTeam))
			{
				echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				exit; # do not process additional queries if this failed					
			}
			$stmt->fetch();
			echo $statDate . ": " . $aTeam  . " at " . $hTeam . " - " . $gStatus . " by " . $winTeam;
		
			$stmt->close();

		?>
		</h2>
		<table id="teamSummary" style="width:50%;">
			<tr><td></td>
				<td><strong>Home</strong></td>
				<td><strong>Away</strong></td>
			</tr>
			<tr>
				<?php
					
					# now get the goals and assist information for the selected game
					if(!($stmt = $mysqli->prepare("select homeTeamID, awayTeamID, 
							homeGoals1 + homeGoals2 + homeGoals3 + homeGoals4 + homeGoals5 as homeGoals,
							awayGoals1 + awayGoals2 + awayGoals3 + awayGoals4 + awayGoals5 as awayGoals,
							homeShots, awayShots, homePenaltyMin, awayPenaltyMin
							from GAMEVIEW
							where gameID = ?")))
					{
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						exit; # do not process additional queries if this failed					
					}
					
					if(!($stmt->bind_param('i',$gameID)))
					{
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						exit; # do not process additional queries if this failed					
					}
					
					if(!$stmt->execute())
					{
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						exit; # do not process additional queries if this failed					
					}
					if(!$stmt->bind_result($hTeam, $aTeam, $hGoals, $aGoals, $hShots, $aShots, $hPen, $aPen))
					{
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						exit; # do not process additional queries if this failed					
					}
					$stmt->fetch();
					echo "<tr><td></td><td>" . $hTeam . "</td><td>" . $aTeam . "</td></tr>";
					echo "<tr><td><strong>Goals</strong></td><td>" . $hGoals . "</td><td>" . $aGoals . "</td></tr>";
					echo "<tr><td><strong>Shots</strong></td><td>" . $hShots . "</td><td>" . $aShots . "</td></tr>";
					echo "<tr><td><strong>Penalty Minutes</strong></td><td>" . $hPen . "</td><td>" . $aPen . "</td></tr>";
					
					
					$stmt->close();

					
				?>
				</tr>
			
		</table>
	</div> <!-- /summarytable -->
</body>
</html>