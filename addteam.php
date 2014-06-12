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
	<title>CS 275 Project: NHL Database: Add Teams</title>
	<link type="text/css" rel="stylesheet" href="db.css" media="all" />
	
</head>
<body>


<div id="addTeam" class="confirmation">
	<h2> 
		<?php
		
			$teamID = $_POST['teamID'];
			/* Validate teamID is 3 character alphabet input */
			
			if(preg_match('/[^A-Za-z]/', $teamID))
			{
				echo "Team acryonym must contain only letters.";
			}
			else # Only proceed with sql query if validation is a pass
			{
				if(!($stmt = $mysqli->prepare("insert into TEAMS (teamID, teamName, stadiumName, city, state, country) values (upper('$_POST[teamID]'),'$_POST[teamName]','$_POST[stadiumName]','$_POST[city]','$_POST[state]','$_POST[country]')"))) {
					echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
				}
				
				/* Note: nothing to bind since using insert query */
				
				if(!$stmt->execute()){
					echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
				}
				echo "Added new team to the database.";
				$stmt->close();
			}
		?>
	</h2>
	</div> 
</body>
</html>