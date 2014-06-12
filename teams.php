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
	<title>CS 275 Project: NHL Database: All Teams</title>
	<link type="text/css" rel="stylesheet" href="db.css" media="all" />
	
</head>
<body>


<div id="teamsTable" class="statsTable">
	<h2>All Teams:</h2>
		
	
		<table id="teamsTable">
			<tr>
				<td><strong>Team ID</strong></td>
				<td><strong>Team Name</strong></td>
				<td><strong>Stadium Name</strong></td>
				<td><strong>City</strong></td>
				<td><strong>State</strong></td>
				<td><strong>Country</strong></td>
			</tr>
			<tr>
				<?php
					if(!($stmt = $mysqli->prepare("select * from TEAMS"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($teamID, $teamName, $stadiumName, $city, $state, $country)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
					 echo "<tr><td>" . $teamID . "</td>" . "<td>" . $teamName . "</td>" . "<td>" . $stadiumName . "</td>" . "<td>" . $city . "</td>" . "<td>" . $state . "</td>" . "<td>" . $country . "</td></tr>";
					}
					$stmt->close();
				?>
		
				
			</tr>
		</table>
	</div> <!-- /teamtable -->
</body>
</html>