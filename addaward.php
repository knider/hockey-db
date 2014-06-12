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
	<title>CS 275 Project: NHL Database: Add Award</title>
	<link type="text/css" rel="stylesheet" href="db.css" media="all" />
	<script type="text/javascript" src="jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="jquery.validate.js"></script>
	<script type="text/javascript" src="db.js"></script>	
</head>
<body>


<div id="addAward" class="confirmation">
	<h2> 
		<?php
			# Declare POST variables
			$trophyID = $_POST['trophyName'];
			$awardP1 = $_POST['awardPlayer1'];
			$awardP2 = $_POST['awardPlayer2'];
			
			# AWARDS_PLAYERS table already has a unique key constraint to prevent duplicate awards being given to a player
			# In reality, you'd probably want to do a validation that prevents additional recipients from receiving it once
			# it's been awarded.  However, for the purposes of ths project, given that this is for the 2007/2008 season, we
			# are not putting that constraint in, in order to demonstate the insert feature.
			
			# Only other validation that is possible with this database is to check for awards given out to specific positions
			# Vezina/Jennings trophy is awarded to goalies and Norris trophy is awarded to defensemen.
			
			# Check Vezina/Jennings winners
			if($trophyID == 14 || $trophyID == 15)
			{
				if(!($stmt = $mysqli->prepare("select count(playerID) from PLAYERS where playerID in (?, ?) and position <> 'G'"))) 
				{
					echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				if(!($stmt->bind_param('ii', $awardP1, $awardP2)))
				{
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				if(!($stmt->execute())) 
				{
					echo "Execute failed ". $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				
				if(!($stmt->bind_result($wrongPos))){
					echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				$stmt->fetch();
				if($wrongPos != 0)
				{
					echo "Try again. The winner of this award must be a goalie.";
					exit; # do not process additional queries if this failed					
				}
			}

			# Check Norris winners
			if($trophyID == 7)
			{
				if(!($stmt = $mysqli->prepare("select count(playerID) from PLAYERS where playerID in (?, ?) and position <> 'D'"))) 
				{
					echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				if(!($stmt->bind_param('ii', $awardP1, $awardP2)))
				{
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				if(!($stmt->execute())) 
				{
					echo "Execute failed ". $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				
				if(!($stmt->bind_result($wrongPos)))
				{
					echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				$stmt->fetch();
				if($wrongPos != 0)
				{
					echo "Try again.  The winner of this award must be a defenseman.";
					exit; # do not process additional queries if this failed					
				}
			}

			# Add the award winners to AWARDS_PLAYERS if they pass validations
			if(!($stmt = $mysqli->prepare("insert into AWARDS_PLAYERS values (?, ?)"))) 
			{
				echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!($stmt->bind_param('ii', $trophyID, $awardP1)))
			{
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!$stmt->execute())
			{
				echo " Execute failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			echo "<br> Trophy ID #" . $trophyID . " was won by Player ID = " . $awardP1;

			# Now add second winner, if there is one
			if(!empty($awardP2))
			{
				if(!($stmt->bind_param('ii', $trophyID, $awardP2)))
				{
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				if(!$stmt->execute()){
				
					echo " Execute failed: "  . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				echo "<br> Trophy ID #" . $trophyID . " was won by Player ID = " . $awardP2;
			
			}
			$stmt->close();
			
		?>
	</h2>
	<br><br>
	<a href="index.php">Return to Home</a>
</body>
</html>