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
	<title>CS 275 Project: NHL Database: Add a New Game</title>
	<link type="text/css" rel="stylesheet" href="db.css" media="all" />
	<script type="text/javascript" src="jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="jquery.validate.js"></script>
	<script type="text/javascript" src="db.js"></script>
</head>
<body>


<div id="addGame" class="confirmation">
	<h2> 
		<?php
			# Declare POST variables
			$hTeam = $_POST['homeTeam'];
			$aTeam = $_POST['awayTeam'];
			$gDate = $_POST['gameDate'];
			$gStatus = $_POST['gameStatus']; 
			$hShots = $_POST['homeShots'];
			$aShots = $_POST['awayShots'];
			$hPenMin = $_POST['homePenaltyMin'];
			$aPenMin = $_POST['awayPenaltyMin'];
			
			# Validate game input is valid
			if(strcmp($hTeam, $aTeam) == 0)
			{
				echo "Please try again.  Home and away teams can not be the same";
				exit; # do not process additional queries if this failed
			}
			# Note - no other input validations need to be done - handled by html input in form
			
			# Only proceed to add game with sql queries if input level validation is passed
			
			# First add the game to the GAMES table
			if(!($stmt = $mysqli->prepare("insert into GAMES (gameDate, homeTeamID, awayTeamID, gameStatus, homeShots, awayShots, homePenaltyMin, awayPenaltyMin) values (?, ?, ?, ?, ?, ?, ?, ?)"))) 
			{
				echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!($stmt->bind_param('ssssiiii', $gDate, $hTeam, $aTeam, $gStatus, $hShots, $aShots, $hPenMin, $aPenMin)))
			{
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!$stmt->execute())
			{
				echo "Execution failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			else
				echo "Added " . $hTeam . " vs " . $aTeam . ", " . $gDate;
			$stmt->close();
			
			# Now, retrieve the newly created gameID 
			# GAMES table has unique key constraint on homeTeamID, awayTeamID and gameDate so no duplicate gameID's will be retrieved
			if(!($stmt = $mysqli->prepare("select gameID from GAMES where gameDate = ? and homeTeamID = ? and awayTeamID = ?")))
			{
				echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!($stmt->bind_param('sss', $gDate, $hTeam, $aTeam)))
			{
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!$stmt->execute())
			{
				echo "Execute failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!$stmt->bind_result($newGameID))
			{
				echo " Execution failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			$stmt->fetch();
			echo " to the database with a Game ID of " . $newGameID . ".<br><br><br>";
			$stmt->close();
		?>
	</h2>
</div>
<div id="addGame" class="query">
	<form method="post" action="addgoal.php" id="addGoal">
		<fieldset id="addQuery" class="queryForm">
			<legend>Add a Goal for this Game</legend>
			<div class="formRow">
				<label for="takenBy">Scored By<span class="required">*</span></label>
				<select name="takenBy" id="takenBy" class="required blockInput"><option value="">Select a player</option>
				<?php
					# Get player ID, first and last names for players who could have played in this game
					# Order results by home team first
					if(!($stmt = $mysqli->prepare("select a.teamID, a.playerID, b.firstName, b.lastName
							from PLAYERS_TEAMS as a
							inner join PLAYERS as b
							on a.playerID = b.playerID
							where a.teamID in ('$hTeam', '$aTeam')
							and a.startDate <= '$gDate' and a.endDate >= '$gDate'
							order by case when teamID = '$hTeam' then 1 else 2 end, b.firstName, b.lastName"))) # show home team players first
					{
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
						exit; # do not process additional queries if this failed
					}
					
					if(!($stmt->execute())) {
						echo "Execute failed ". $stmt->errno . " " . $stmt->error;
						exit; # do not process additional queries if this failed
					}
					
					if(!($stmt->bind_result($teamID, $playerID, $firstName, $lastName))){
						echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
						exit; # do not process additional queries if this failed
					}
					while($stmt->fetch()){
						echo '<option value="'. $teamID . $playerID .  '">' . $teamID . ' - ' . $firstName . ' ' . $lastName . '</option>';
					}
				
				?>
				</select>
			</div>
			<div class="formRow">
				<label for="period">Period<span class="required">*</span></label> <select name="period" id="period" class="required blockInput">
					<option value="">Select a Period</option><option value="1">1st Period</option><option value="2">2nd Period</option><option value="3">3rd Period</option><option value="4">Overtime</option><option value="5">Shootout</option>
				</select>
			</div>
			<div class="formRow">
				<label for="elapsedMin">Minute<span class="required">*</span> <input type="number" value="" maxlength="2" name="elapsedMin" id="elapsedMin" class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="elapsedSec">Second<span class="required">*</span> <input type="number" value="" maxlength="2" name="elapsedSec" id="elapsedSec" class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="teamStr">Team Strength<span class="required">*</span></label> <select name="teamStr" id="teamStr" class="required blockInput">
					<option value="">Select an option</option><option value="EV">Even Strength</option><option value="PP">Power Play</option><option value="SH">Shorthanded</option>
				</select>
			</div>
			

			<fieldset>
			<legend>Assists:</legend>
				<div class="formRow">
					<label for="assist1">First Assist</label><select name="assist1" id="assist1" class="blockInput"><option value="">Select a player</option>
					<?php
						//get player ID, first and last names for players who could have played in this game
						if(!($stmt = $mysqli->prepare("select a.teamID, a.playerID, b.firstName, b.lastName
															from PLAYERS_TEAMS as a
															inner join PLAYERS as b
															on a.playerID = b.playerID
															where a.teamID in ('$hTeam', '$aTeam')
															and a.startDate <= '$gDate' and a.endDate >= '$gDate'
															order by case when teamID = '$hTeam' then 1 else 2 end, b.firstName, b.lastName"))) # show home team players first
						{
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
							exit; # do not process additional queries if this failed
						}
						
						if(!($stmt->execute())) {
							echo "Execute failed ". $stmt->errno . " " . $stmt->error;
							exit; # do not process additional queries if this failed
						}
						
						if(!($stmt->bind_result($teamID, $playerID, $firstName, $lastName))){
							echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
							exit; # do not process additional queries if this failed
						}
						while($stmt->fetch()){
							echo '<option value="'. $teamID . $playerID .  '">' . $teamID . ' - ' . $firstName . ' ' . $lastName . '</option>';
						}
					
					?>
					</select>
				</div>
				<div class="formRow">
					<label for="assist2">Second Assist</label><select name="assist2" id="assist2" class="blockInput"><option value="">Select a player</option>
					<?php
						//get player ID, first and last names for players who could have played in this game
						if(!($stmt = $mysqli->prepare("select a.teamID, a.playerID, b.firstName, b.lastName
															from PLAYERS_TEAMS as a
															inner join PLAYERS as b
															on a.playerID = b.playerID
															where a.teamID in ('$hTeam', '$aTeam')
															and a.startDate <= '$gDate' and a.endDate >= '$gDate'
															order by case when teamID = '$hTeam' then 1 else 2 end, b.firstName, b.lastName"))) # show home team players first
						{
							echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
							exit; # do not process additional queries if this failed
						}
						
						if(!($stmt->execute())) {
							echo "Execute failed ". $stmt->errno . " " . $stmt->error;
							exit; # do not process additional queries if this failed
						}
						
						if(!($stmt->bind_result($teamID, $playerID, $firstName, $lastName))){
							echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
							exit; # do not process additional queries if this failed
						}
						while($stmt->fetch()){
							echo '<option value="'. $teamID . $playerID .  '">' . $teamID . ' - ' . $firstName . ' ' . $lastName . '</option>';
						}
					?>
					</select>
				</div>
			</fieldset>		
		</fieldset>
		<div class="clear" ></div>
		<input type="hidden" name="newGameID" id="newGameID" value="<?php echo $newGameID ?>">
		<input type="hidden" name="homeTeam" id="homeTeam" value="<?php echo $hTeam ?>">
		<input type="hidden" name="awayTeam" id="awayTeam" value="<?php echo $aTeam ?>">
		<input type="hidden" name="gameDate" id="gameDate" value="<?php echo $gDate ?>">
		<br />
		<input type="submit" name="addGoal" id="addGoal" value="Add Goal" />
	</form>
</div>
	<br />
	<a href="index.php">Return to Home</a>
</body>
</html>