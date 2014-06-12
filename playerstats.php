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
	<title>CS 275 Project: NHL Database: Player Stats</title>
	<link type="text/css" rel="stylesheet" href="db.css" media="all" />
	
</head>
<body>


<div id="playerStatsTable" class="statsTable">
	<h2>Player Stats for: 
		<?php
			if(!($stmt = $mysqli->prepare("select firstName, lastName from PLAYERS where playerID = ?"))){
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			
			if(!($stmt->bind_param("i",$_POST['playerStatsID']))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
			
			if(!$stmt->execute()){
				echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			if(!$stmt->bind_result($firstName,$lastName)){
				echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			while($stmt->fetch()){
			 echo "" . $firstName . " " . $lastName;
			}
			$stmt->close();
		?>
	</h2>
		<table id="playerstats">
			<tr>
				<td><strong>Goals</strong></td>
				<td><strong>Assists</strong></td>
				<td><strong>Points</strong></td>
				<td><strong>Power Play Goals</strong></td>
				<td><strong>Shorthanded Goals</strong></td>
			</tr>
			<tr>
				
				<?php
					if(!($stmt = $mysqli->prepare("select count(goalID) as goals from GOALS where period < 5 and takenBy = ?"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->bind_param("i",$_POST['playerStatsID']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($goals)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo "<td>" . $goals . "</td>";
					}
					$stmt->close();
				
					
					if(!($stmt = $mysqli->prepare("select count(goalID) as assists from ASSISTS where playerID =  ?"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->bind_param("i",$_POST['playerStatsID']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($assists)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo "<td>" . $assists . "</td>";
					}
					$stmt->close();
				
					
					$points = $assists + $goals;
					echo "<td>" . $points . "</td>";
					
					if(!($stmt = $mysqli->prepare("select count(goalID) as powerPlayGoals from GOALS where period < 5 and teamStrength = 'PP' and takenBy = ?"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->bind_param("i",$_POST['playerStatsID']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($powerPlayGoals)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo "<td>" . $powerPlayGoals . "</td>";
					}
					$stmt->close();
					
					
					if(!($stmt = $mysqli->prepare("select count(goalID) as shortHandedGoals from GOALS where period < 5 and teamStrength = 'SH' and takenBy = ?"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->bind_param("i",$_POST['playerStatsID']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($shortHandedGoals)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo "<td>" . $shortHandedGoals . "</td>";
					}
					$stmt->close();
					
				?>
				
			</tr>
		</table>
	</div> <!-- /playertable -->
</body>
</html>