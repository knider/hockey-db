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
	<title>CS 275 Project: NHL Database: Add Goal</title>
	<link type="text/css" rel="stylesheet" href="db.css" media="all" />
	<script type="text/javascript" src="jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="jquery.validate.js"></script>
	<script type="text/javascript" src="db.js"></script>	
</head>
<body>


<div id="addGoal" class="confirmation">
	<h2> 
		<?php
			# Declare POST variables
			$scorerTeam = substr($_POST['takenBy'], 0, 3); # parse out first 3 letters which is the team ID
			$scorerID = substr($_POST['takenBy'], 3, strlen($_POST['takenBy']) - 3); # parse out remaining characters as playerID
			$gPeriod = $_POST['period'];
			if(intval($_POST['elapsedMin']) < 10) # Convert minutes into '00' format used by mySql
				$gMin = "0" . $_POST['elapsedMin'];
			else
				$gMin = $_POST['elapsedMin'];
			if(intval($_POST['elapsedSec']) < 10) # convert seconds into '00' format used by mySql
				$gSec = "0" . $_POST['elapsedSec'];
			else
				$gSec = $_POST['elapsedSec'];
			$gTime = "00:" . $gMin .":" . $gSec; # concatenate time field to pass into mySql
			$gStr = $_POST['teamStr'];
			$assist1Team = substr($_POST['assist1'], 0, 3); # team ID for assist 1
			$assist1ID = substr($_POST['assist1'], 3, strlen($_POST['assist1']) - 3); # player ID for assist 1
			$assist2Team = substr($_POST['assist2'], 0, 3); # team ID for asssist 2
			$assist2ID = substr($_POST['assist2'], 3, strlen($_POST['assist2']) - 3); # player ID for assist 2
			# variables passed from prior page to track game selection data
			$newGameID = $_POST['newGameID'];
			$hTeam = $_POST['homeTeam'];
			$aTeam = $_POST['awayTeam'];
			$gDate = $_POST['gameDate'];
			
			echo "For Game " . $hTeam . " vs " . $aTeam . ": ";
		
			# Validate goal input is valid
			if(intval($gMin) == 0 && intval($gSec) == 0)
			{
				echo "Try again.  Goals can not be scored at 0 min and 0 sec.";
				exit; # do not process additional queries if this failed
			}
			else if(intval($gPeriod) == 4 && (intval($gMin > 5) || (intval($gMin) == 5 && intval($gSec) > 0))) 
			{
				echo "Try again. The overtime period is only 5 minutes long.";
				exit; # do not process additional queries if this failed
			}
			else if(intval($gPeriod) == 5 && (!empty($assist1ID) || !empty($assist2ID)))
			{
				echo "Try again.  Shootout goals do not have assists.";
				exit; # do not process additional queries if this failed
			}
			else if((!empty($assist1Team) && strcmp($assist1Team, $scorerTeam) != 0) ||
				(!empty($assist2Team) && strcmp($assist2Team, $scorerTeam) != 0))
			{
				echo "Try again.  Goals have to be assisted by players on the same team.";
				exit; # do not process additional queries if this failed
			}
			else if(!empty($assist2ID) && empty($assist1ID))
			{
				echo "Try again.  First assist is required in order to have a second assist.";
				exit; # do not process additional queries if this failed
			}
			else if((!empty($assist1ID) && strcmp($assist1ID, $scorerID) == 0) ||
					(!empty($assist2ID) && strcmp($assist2ID, $scorerID) == 0) ||
					(!empty($assist2ID) && strcmp($assist2ID, $assist1ID) == 0))
			{
				echo "Try again.  Player can not assist on own goal, or get 2 assists on a goal.";
				exit; # do not process additional queries if this failed
			}
			
			# Only proceed add goals and assists with sql queries if input level validation is passed
			
			# First add the goal to the GOALS table
			if(!($stmt = $mysqli->prepare("insert into GOALS (gameID, takenBy, period, elapsedTime, teamStrength) values (?, ?, ?, ?, ?)"))) 
			{
				echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!($stmt->bind_param('iiiss', $newGameID, $scorerID, $gPeriod, $gTime, $gStr)))
			{
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!$stmt->execute()){
				echo " Execute failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			echo "Added goal scored by" . $scorerTeam . ", Player ID: " . $scorerID;
			$stmt->close();
			
			# Now, retrieve the newly created goalID in order to add the assists for the goal
			# Since duplicate player names allowed, using Select max as precautionary measure (playerID has auto-increment)
			if(!($stmt = $mysqli->prepare("select max(goalID) from GOALS where gameID = ? and takenBy = ? and period = ? and elapsedTime = ? and teamStrength = ?")))
			{
				echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!($stmt->bind_param('iiiss', $newGameID, $scorerID, $gPeriod, $gTime, $gStr)))
			{
				echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!$stmt->execute())
			{
				echo " Execute failed: "  . $stmt->errno . " " . $stmt->error;
				exit; # do not process additional queries if this failed
			}
			if(!$stmt->bind_result($newGoalID))
			{
				echo "Could not retrieve the goalID that was created: "  . $stmt->errno . " " . $stmt->error;
			}
			$stmt->fetch();
			echo " to the database with Goal ID: " . $newGoalID . ".<br>";
			$stmt->close();
			
			# Now add the assists to the ASSISTS table
			
			# Add first assist to ASSIST table
			if(!empty($assist1ID)) # goal may be un-assisted, so only add if assist was inputted
			{
				if(!($stmt = $mysqli->prepare("insert into ASSISTS values (?, ?)"))) 
				{
					echo "Prepare failed: " . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				if(!($stmt->bind_param('ii', $newGoalID, $assist1ID)))
				{
					echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				if(!$stmt->execute())
				{
					echo " Execute failed: "  . $stmt->errno . " " . $stmt->error;
					exit; # do not process additional queries if this failed
				}
				echo "<br> Assisted by Player ID: " . $assist1ID;
				
				# Add second assist to ASSIST table (use nested if to re-use the same prepare)
				if(!empty($assist2ID)) # only add if assist was inputted
				{
					if(!($stmt->bind_param('ii', $newGoalID, $assist2ID)))
					{
						echo "Bind failed: "  . $stmt->errno . " " . $stmt->error;
						exit; # do not process additional queries if this failed
					}
					if(!$stmt->execute())
					{
						echo " Execute failed: "  . $stmt->errno . " " . $stmt->error;
						exit; # do not process additional queries if this failed
					}
					echo " and by Player ID: " . $assist2ID;
				}
				$stmt->close();
			}
		?>
	</h2>
</div>
<div id="addGoal" class="query">
	<form method="post" action="addgoal.php" id="addGoal">
		<fieldset id="addQuery" class="queryForm">
			<legend>Add a Goal for this Game</legend>
			<div class="formRow">
				<label for="takenBy">Scored By<span class="required">*</span></label><select name="takenBy" id="takenBy" class="required blockInput">
				<option value="">Select a player</option>
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
				<label for="period">Period<span class="required">*</span></label> <select name="period" id="period" title="A period is required." class="required blockInput">
					<option value="">Select a Period</option><option value="1">1st Period</option><option value="2">2nd Period</option><option value="3">3rd Period</option><option value="4">Overtime</option><option value="5">Shootout</option>
					</select>
			</div>
			<div class="formRow">
				<label for="elapsedMin">Minute<span class="required">*</span></label> <input type="number" value="" name="elapsedMin" id="elapsedMin" title="A minute value is required between 0 and 19" class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="elapsedSec">Second<span class="required">*</span></label> <input type="number" value="" name="elapsedSec" id="elapsedSec" title="A second value is required between 0 and 59" class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="teamStr">Team Strength<span class="required">*</span></label> <select name="teamStr" id="teamStr" title="Team Strength is required." class="required blockInput">
					<option value="">Select an option</option><option value="EV">Even Strength</option><option value="PP">Power Play</option><option value="SH">Shorthanded</option>
				</select>
			</div>
			

			<fieldset>
			<legend>Assisted by:</legend>
				<div class="formRow">
					<label for="assist1">First Assist</label><select name="assist1" id="assist1" class="blockInput">
					<option value="">Select a player</option>
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
					<label for="assist2">Second Assist</label><select name="assist2" id="assist2" class="blockInput">
					<option value="">Select a player</option>
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
		<input type="submit" name="addMoreGoals" id="addMoreGoals" value="Add Goal" />
	</form>
</div>
<br />
<a href="index.php">Return to Home</a>
</body>
</html>