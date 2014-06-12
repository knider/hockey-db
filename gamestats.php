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
	<title>CS 275 Project: NHL Database: Game Stats</title>
	<link type="text/css" rel="stylesheet" href="db.css" media="all" />
	
</head>
<body>


<div id="scoringSummaryTable" class="statsTable">
	<h2>Game Scoring Summary:
				<?php
				
					# Declare POST variables
					$statTeam = $_POST['gameStatsTeamName'];
					$statDate = $_POST['gameStatsDate'];
					
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
					echo $statDate . ": " . $aTeam . " at " . $hTeam . " - " . $gStatus . " by " . $winTeam;
				
					$stmt->close();
				?>
	</h2>
		<table id="scoringSummary">
			<tr>
				<td><strong>Period</strong></td>
				<td><strong>Time</strong></td>
				<td><strong>Strength</strong></td>
				<td><strong>Team</strong></td>
				<td><strong>Goal By</strong></td>
				<td><strong>Assisted By</strong></td>
				<td><strong>Assisted By</strong></td>
			</tr>
			
				
				<?php
					# now get the goals and assist information for the selected game
					if(!($stmt = $mysqli->prepare("select go.period, go.elapsedTime, go.teamStrength, pt.teamID, scr.firstName, scr.lastName, astnm.a1First, astnm.a1Last, astnm.a2First, astnm.a2Last
							from GOALS as go 
							inner join PLAYERS as scr on go.takenBy = scr.playerID
							inner join GAMES as gm on go.gameID = gm.gameID
							inner join PLAYERS_TEAMS as pt on go.takenBy = pt.playerID and gm.gameDate >= pt.startDate and gm.gameDate <= pt.endDate
							left outer join
							(select ast.goalID, pl1.firstName as a1First, pl1.lastName as a1Last, pl2.firstName as a2First, pl2.lastName as a2Last
							from 
							(select goalID, min(playerID) as assist1ID, case when max(playerID) = min(playerID) then NULL else max(playerID) end as assist2ID
							from ASSISTS
							group by goalID) ast
							inner join PLAYERS as pl1 on ast.assist1ID = pl1.playerID
							left outer join PLAYERS as pl2 on ast.assist2ID = pl2.playerID) as astnm
							on go.goalID = astnm.goalID
							where go.gameID = ? order by go.period, go.elapsedTime")))
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
					if(!$stmt->bind_result($gperiod, $gTime, $teamStr, $teamID, $scrFirst, $scrLast, $a1First, $a1Last, $a2First, $a2Last))
					{
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
						exit; # do not process additional queries if this failed					
					}
					while($stmt->fetch())
					{
						echo "<tr><td>" . $gperiod . "</td><td>" . $gTime . "</td><td>" . $teamStr . "</td><td>" . $teamID . "</td><td>" . $scrFirst . " " . $scrLast . "</td><td>" . $a1First . " " . $a1Last . "</td><td>" . $a2First . " " . $a2Last . "</td></tr>";
					}
					
					$stmt->close();
				
					
					
				?>
				
			
		</table>
	</div>
</body>
</html>