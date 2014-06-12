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
	<title>CS 275 Project: NHL Database: Awards</title>
	<link type="text/css" rel="stylesheet" href="db.css" media="all" />
	
</head>
<body>


<div id="awardsTable" class="statsTable">
	<h2>Award Winner(s) for: 
		<?php
			if(!($stmt = $mysqli->prepare("select trophyName from AWARDS where trophyID = ?"))){
				echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
			}
			
			if(!($stmt->bind_param("i",$_POST['trophyName']))){
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
			}
			
			if(!$stmt->execute()){
				echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			if(!$stmt->bind_result($trophyName)){
				echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
			}
			while($stmt->fetch()){
			 echo " " . $trophyName;
			}
			$stmt->close();
		?>
	</h2>
		<table id="awardwinners" style="width: auto">
			<tr>
				
				<?php
					if(!($stmt = $mysqli->prepare("SELECT firstName, lastName FROM PLAYERS INNER JOIN AWARDS_PLAYERS ON PLAYERS.playerID = AWARDS_PLAYERS.playerID INNER JOIN AWARDS ON AWARDS.trophyID = AWARDS_PLAYERS.trophyID WHERE AWARDS.trophyID = ?"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->bind_param("i",$_POST['trophyName']))){
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!$stmt->execute()){
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					if(!$stmt->bind_result($firstName,$lastName)){
						echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					while($stmt->fetch()){
			 			echo "<td>" . $firstName . " " . $lastName. "</td>";
					}
					$stmt->close();
					
				?>
				
			</tr>
		</table>
	</div> <!-- /playertable -->
</body>
</html>