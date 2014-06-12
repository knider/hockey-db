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
	<title>CS 275 Project: NHL Database: Team Standings</title>
	<link type="text/css" rel="stylesheet" href="db.css" media="all" />
	
</head>
<body>


<div id="teamStandingsTable" class="statsTable" >
	<h2>Team Standings</h2>
		<table id="teamStandings">
			<tr>
				<td><strong>Team Name</strong></td>
				<td><strong>Games Played</strong></td>
				<td><strong>Points</strong></td>
				<td><strong>Wins</strong></td>
				<td><strong>Reg Losses</strong></td>
				<td><strong>OT/SO Losses</strong></td>
				<td><strong>Home Record</strong></td>
				<td><strong>Away Record</strong></td>
			</tr>
			<tr>
		<?php
			# Declare post variables
			$selectTeam = $_POST['teamStandingsName'];
			
			# show all results if no team is selected
			if(empty($selectTeam))
			{
				# select standings data from STANDINGS view
				if(!($stmt = $mysqli->prepare("select team1, 
						count(gameID) as gamesPlayed, 
						sum(case when team1Goals > team2Goals then 2
							when team1Goals < team2Goals and gameStatus in ('OT', 'SO') then 1 else 0 end) as points,
						sum(case when team1Goals > team2Goals then 1 else 0 end) as wins,
						sum(case when team1Goals < team2Goals and gameStatus in ('RT') then 1 else 0 end) as regLosses,
						sum(case when team1Goals <= team2Goals and gameStatus in ('OT', 'SO') then 1 else 0 end) as otsoLosses,
						sum(case when team1Goals > team2Goals and team1Status = 'Home' then 1 else 0 end) as homewins,
						sum(case when team1Goals < team2Goals and gameStatus in ('RT') and team1Status = 'Home' then 1 else 0 end) as homeRegLosses,
						sum(case when team1Goals <= team2Goals and gameStatus in ('OT', 'SO') and team1Status = 'Home' then 1 else 0 end) as homeOtsoLosses,
						sum(case when team1Goals > team2Goals and team1Status = 'Away' then 1 else 0 end) as awaywins,
						sum(case when team1Goals < team2Goals and gameStatus in ('RT') and team1Status = 'Away' then 1 else 0 end) as awayRegLosses,
						sum(case when team1Goals <= team2Goals and gameStatus in ('OT', 'SO') and team1Status = 'Away' then 1 else 0 end) as awayOtsoLosses
					from STANDINGS
					group by team1
					order by sum(case when team1Goals > team2Goals then 2
							when team1Goals < team2Goals and gameStatus in ('OT', 'SO') then 1 else 0 end) desc"))) 
				{
					echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				if(!($stmt->execute())) 
				{
					echo "Execute failed ". $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				
				if(!($stmt->bind_result($teamID, $gamesPlayed, $points, $wins, $regLosses, $otsoLosses, $homeWins, $homeRegLosses, $homeOtsoLosses, $awayWins, $awayRegLosses, $awayOtsoLosses)))
				{
					echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				while($stmt->fetch())
				{
					echo '<tr><td>' . $teamID .  '</td><td>' . $gamesPlayed . '</td><td>' . $points . '</td><td>' . $wins . '</td><td>' .  $regLosses . '</td><td>' . $otsoLosses . '</td><td>' .  
					$homeWins . '-' . $homeRegLosses . '-' . $homeOtsoLosses . '</td><td>' . $awayWins . '-' . $awayRegLosses . '-' . $awayOtsoLosses . '</td></tr>';
				}
			}
			# otherwise, just show results for selected team
			else
			{
				# select standings data from STANDINGS view
				if(!($stmt = $mysqli->prepare("select team1, 
						count(gameID) as gamesPlayed, 
						sum(case when team1Goals > team2Goals then 2
							when team1Goals <= team2Goals and gameStatus in ('OT', 'SO') then 1 else 0 end) as points,
						sum(case when team1Goals > team2Goals then 1 else 0 end) as wins,
						sum(case when team1Goals < team2Goals and gameStatus in ('RT') then 1 else 0 end) as regLosses,
						sum(case when team1Goals < team2Goals and gameStatus in ('OT', 'SO') then 1 else 0 end) as otsoLosses,
						sum(case when team1Goals > team2Goals and team1Status = 'Home' then 1 else 0 end) as homewins,
						sum(case when team1Goals < team2Goals and gameStatus in ('RT') and team1Status = 'Home' then 1 else 0 end) as homeRegLosses,
						sum(case when team1Goals < team2Goals and gameStatus in ('OT', 'SO') and team1Status = 'Home' then 1 else 0 end) as homeOtsoLosses,
						sum(case when team1Goals > team2Goals and team1Status = 'Away' then 1 else 0 end) as awaywins,
						sum(case when team1Goals < team2Goals and gameStatus in ('RT') and team1Status = 'Away' then 1 else 0 end) as awayRegLosses,
						sum(case when team1Goals < team2Goals and gameStatus in ('OT', 'SO') and team1Status = 'Away' then 1 else 0 end) as awayOtsoLosses
					from STANDINGS
					where team1 = '$selectTeam'
					group by team1
					order by sum(case when team1Goals > team2Goals then 2
							when team1Goals < team2Goals and gameStatus in ('OT', 'SO') then 1 else 0 end) desc"))) 
				{
					echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				if(!($stmt->execute())) 
				{
					echo "Execute failed ". $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				
				if(!($stmt->bind_result($teamID, $gamesPlayed, $points, $wins, $regLosses, $otsoLosses, $homeWins, $homeRegLosses, $homeOtsoLosses, $awayWins, $awayRegLosses, $awayOtsoLosses)))
				{
					echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				while($stmt->fetch())
				{
					echo '<tr><td>' . $teamID .  '</td><td>' . $gamesPlayed . '</td><td>' . $points . '</td><td>' . $wins . '</td><td>' .  $regLosses . '</td><td>' . $otsoLosses . '</td><td>' .  
					$homeWins . '-' . $homeRegLosses . '-' . $homeOtsoLosses . '</td><td>' . $awayWins . '-' . $awayRegLosses . '-' . $awayOtsoLosses . '</td></tr>';
				}
			}
			$stmt->close();
		?>
	</h2>
	</div>
</body>
</html>