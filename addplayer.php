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
	<title>CS 275 Project: NHL Database: Add Players</title>
	<link type="text/css" rel="stylesheet" href="db.css" media="all" />
	
</head>
<body>


<div id="addPlayer" class="confirmation">
	<h2> 
		<?php
			# Declare POST variables
			$fName = $_POST['firstName'];
			$lName = $_POST['lastName'];
			$jNum = $_POST['jerseyNum'];
			$pos = $_POST['position']; 
			$tName1 = $_POST['playerteamName1'];
			$tDate1 = $_POST['startDate1'];
			$tName2 = $_POST['playerteamName2'];
			$tDate2 = $_POST['startDate2'];
			
			# Validate player input is valid
			if(!preg_match("/^[a-zA-Z -]+$/", $fName . $lName))
			{
				# Note: tried using ereg from PHP textbook but got deprecated warning
				# Note Other validations such as maximum name lengths to match table setup is handled by form
				echo "Please try again. First and last name can only contain letters, spaces and hyphens.";
				exit; # do not process additional queries if this failed

			}
			else if(intval($jNum) == 99) # no need to validate between 1 and 99 since handled by form input restrictions
			{
				echo "Record not added.  No player should be arrogant enough to wear Wayne Gretzky's number.";
				exit; # do not process additional queries if this failed
			}
			else if(!empty($tName2) && empty($tDate2)) # only need to check for 2nd team since required fields for team#1
			{
				echo "Please try again.  Starting date for 2nd team is missing.";
				exit; # do not process additional queries if this failed
			}
			else if(!empty($tName2) && $tDate2 <= $tDate1) 
			{
				echo "Please try again.  Starting date for 2nd team should be after 1st team.";
				exit; # do not process additional queries if this failed
			}
			
			# Only proceed add player with sql queries if input level validation is passed
			
			# First add the new player to the PLAYERS table
			if(!($stmt = $mysqli->prepare("insert into PLAYERS (firstName, lastName, jersey, position) values (?, ?, ?, ?)")))
			# Note, there is no unique constraint on the players table since it is possible for 2 people with the same name with
			# the same jersey to play on 2 different teams.  Remote, but allowable.
			{
				echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!($stmt->bind_param('ssis', $fName, $lName, $jNum, $pos)))
			{
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!$stmt->execute()){
				echo " Execute failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			echo "Added " . $fName . " " . $lName;
			$stmt->close();
			
			# Now, retrieve the newly created playerID 
			# Since duplicate player names allowed, using Select max as precautionary measure (playerID has auto-increment)
			if(!($stmt = $mysqli->prepare("select max(playerID) from PLAYERS where firstName = ? and lastName = ? and jersey = ? and position = ?")))
			{
				echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!($stmt->bind_param('ssis', $fName, $lName, $jNum, $pos)))
			{
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!$stmt->execute())
			{
				echo " Execute failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!$stmt->bind_result($newID))
			{
				echo "Could not retrieve the playerID that was created: "  . $stmt->errno . " " . $stmt->error;
			}
			$stmt->fetch();
			echo " to the database with a Player ID of " . $newID . ".<br>";
			$stmt->close();
			
			# Lastly, insert the teams that this player played on (PLAYERS_TEAMS) using the selected playerID

			# Calculate the ending dates for each player
			if(!empty($tName2)) # situation where there are 2 teams to be added
			{
				# first team end equal to day before player started on second team
				$eDateTime1 = new DateTime($tDate2); # begin by setting equal to team 2 starting date
				$eDateTime1->sub(new DateInterval('P1D')); # subtract 1 day to get team 1 ending date
				$eDate1 = $eDateTime1->format('Y-m-d'); # convert to date and not date/time

				# second team end date equal to end of season
				$eDate2 = '2008-04-06'; 
			}
			else
			{
				$eDate1 = '2008-04-06'; # first team end date equal to end of season
				# no need to set end date for team 2
			}
			
			# Add first team to PLAYERS_TEAMS table
			if(!($stmt = $mysqli->prepare("insert into PLAYERS_TEAMS values (?, ?, ?, ?)"))) 
			{
				echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!($stmt->bind_param('isss', $newID, $tName1, $tDate1, $eDate1)))
			{
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!$stmt->execute())
			{
				echo $tName1 . " Execute failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			echo "   Played for " . $tName1 . " from " . $tDate1 . " to " . $eDate1;
			$stmt->close();
			
			# Add second team to PLAYERS_TEAMS table, if required
			if(!empty($tName2)) # situation where there are 2 teams to be added
			{
				if(!($stmt = $mysqli->prepare("insert into PLAYERS_TEAMS values (?, ?, ?, ?)"))) 
				{
					echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				if(!($stmt->bind_param('isss', $newID, $tName2, $tDate2, $eDate2)))
				{
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				if(!$stmt->execute())
				{
					echo $tName1 . " Execute failed: "  . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				echo "<br>   Played for " . $tName2 . " from " . $tDate2 . " to " . $eDate2;
				$stmt->close();
			}
		?>
	</h2>
	</div>
	<a href="index.php">Return to Home</a>
</body>
</html>