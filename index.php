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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN">

<html>
<head><title>CS 275 Project: NHL Database for 2007/2008 Season</title>
<link type="text/css" rel="stylesheet" href="db.css" media="all" />
<script type="text/javascript" src="jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="jquery.validate.js"></script>
<script type="text/javascript" src="db.js"></script>

</head>

<body>
<h1>CS 275 Project: NHL Database for 2007/2008 Season</h1>
<h1>Kevin Nider &amp; Bental Wong</h1>

<h2><a href="javascript: void(0)" id="addQueryOpenButton" style="display: inline"><img src="addbutton.png" style="display:inline" /> Add Queries</a> <a href="javascript: void(0)" id="addQueryCloseButton" style="display: none;"><img src="minusbutton.png" style="display: inline" /> Add Queries</a></h2>
<div class="query" id="addQuery" style="display:none;">
	
	<form method="post" action="addteam.php" id="addTeam">
		<fieldset id="addQuery" class="queryForm">
			<legend>Add a Team</legend>
			<div class="formRow">
				<label for="teamID">Team ID (3 letter initials)<span class="required">*</span></label> <input type="text" value="" maxlength="3" minlength="3" size="3" id="teamID" name="teamID" title="Team ID must be 3 letters long and contain only letters." class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="teamName">Team Name<span class="required">*</span></label> <input type="text" value="" title="Please complete this field." maxlength = "100" size="25" id="teamName" name="teamName" class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="stadiumName">Stadium Name</label> <input type="text" value="" maxlength = "100" size="25" id="stadiumName" name="stadiumName" class="blockInput" />
			</div>
			<div class="formRow">
				<label for="City">City</label> <input type="text" value="" maxlength = "100" size="25" id="city" name="city" class="blockInput" />
			</div>
			<div class="formRow">
				<label for="state">State</label> <select name="state" id="state" class="blockInput"><option value="">Select a state</option><option value="AL">Alabama</option><option value="AK">Alaska</option><option value="AS">American Samoa</option><option value="AZ">Arizona</option><option value="AR">Arkansas</option>
					<option value="CA">California</option><option value="CO">Colorado</option><option value="CT">Connecticut</option><option value="DE">Delaware</option><option value="DC">D.C.</option><option value="FL">Florida</option>
					<option value="GA">Georgia</option><option value="GU">Guam</option><option value="HI">Hawaii</option><option value="ID">Idaho</option><option value="IL">Illinois</option><option value="IN">Indiana</option><option value="IA">Iowa</option><option value="KS">Kansas</option><option value="KY">Kentucky</option><option value="LA">Louisiana</option>
					<option value="ME">Maine</option><option value="MD">Maryland</option><option value="MA">Massachusetts</option><option value="MI">Michigan</option><option value="MN">Minnesota</option>
					<option value="MS">Mississippi</option><option value="MO">Missouri</option><option value="MT">Montana</option><option value="NE">Nebraska</option><option value="NV">Nevada</option><option value="NH">New Hampshire</option><option value="NJ">New Jersey</option><option value="NM">New Mexico</option><option value="NY">New York</option>
					<option value="NC">North Carolina</option><option value="ND">North Dakota</option><option value="OH">Ohio</option><option value="OK">Oklahoma</option><option value="OR">Oregon</option><option value="PA">Pennsylvania</option>
					<option value="PR">Puerto Rico</option><option value="RI">Rhode Island</option><option value="SC">South Carolina</option><option value="SD">South Dakota</option><option value="TN">Tennessee</option><option value="TX">Texas</option><option value="UT">Utah</option><option value="VT">Vermont</option><option value="VI">Virgin Islands</option>
					<option value="VA">Virginia</option><option value="WA">Washington</option><option value="WV">West Virginia</option><option value="WI">Wisconsin</option><option value="WY">Wyoming</option>
					<option value=""></option><option value="AB">Alberta</option><option value="BC">British Columbia</option><option value="MB">Manitoba</option><option value="NB">New Brunswick</option><option value="NF">Newfoundland</option><option value="NS">Nova Scotia</option><option value="ON">Ontario</option>
					<option value="PI">Prince Edward Island</option><option value="QB">Quebec</option><option value="SK">Saskatchewan</option></select>
			</div>
			<div class="formRow">
				<label for="country">Country</label> <select name="country" id="country" class="blockInput"><option value="">Select a country</option><option value="Canada">Canada</option><option value="United States">United States</option></select>
			</div>
			<div class="clear" ></div>
			<input type="submit" name="addteam" value="Add Team" />
		</fieldset>
		
	</form>


	<form method="post" action="addplayer.php" id="addPlayer">
		<fieldset id="addQuery" class="queryForm">
			<legend>Add a Player</legend>
			<div class="formRow">
				<label for="firstName">First Name<span class="required">*</span></label> <input type="text" value="" title="Please complete this field." maxlength="100" size="25" id="firstName" name="firstName" class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="lastName">Last Name<span class="required">*</span></label> <input type="text" value="" title="Please complete this field." maxlength="100" size="25" id="lastName" name = "lastName" class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="jerseyNum">Jersey Number<span class="required">*</span></label> <input type="number" value="" title="Please enter a number between 1 and 99." size="2" id="jerseyNum" name="jerseyNum" class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="position">Position<span class="required">*</span></label> <select title="Please complete this field." name="position" id="position" class="required blockInput"><option value="">Select a position</option><option value="C">Center</option><option value="L">Left Wing</option><option value="R">Right Wing</option><option value="D">Defense</option><option value="G">Goalie</option></select>
			</div>
			<fieldset>
				<legend>Team #1:</legend>
				<div class="formRow">
					<label for="playerteamName1">Team Name<span class="required">*</span></label> <!--<input type="text" value="" title="Please complete this field." size="25" id="playerteamName" class="required blockInput" />-->
					<select name="playerteamName1" id="playerteamName1" class="required blockInput" title="Please complete this field."><option value="">Select a team</option>
				<?php
				
					if(!($stmt = $mysqli->prepare("SELECT teamID, teamName FROM TEAMS"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->execute())) {
						#echo "Execute failed ". $stmt->errno . " " . $stmt->error;
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					
					if(!($stmt->bind_result($teamID, $teamName))){
						echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					}
					
					
					while($stmt->fetch()){
						echo '<option value="'. $teamID .  '">' . $teamName . '</option>';
					}
				
				?>
				</select>
				</div>
				<div class="formRow">
					<label for="startDate1">Start Date<span class="required">*</span> (YYYY-MM-DD)</label> <input type="date" value="" min="2007-09-29" max="2008-04-06" title="Date must be within 2007/2008 season." id="startDate1" name="startDate1" class="required blockInput" />
				</div>
			</fieldset>
			<fieldset>
				<legend>Team #2 (If player was traded during the season):</legend>
				<div class="formRow">
					<label for="playerteamName2">Team Name</label>
					<select name="playerteamName2" id="playerteamName2"><option value="">Select a team</option>
				<?php
				
					if(!($stmt = $mysqli->prepare("SELECT teamID, teamName FROM TEAMS"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->execute())) {
						#echo "Execute failed ". $stmt->errno . " " . $stmt->error;
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					
					if(!($stmt->bind_result($teamID, $teamName))){
						echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					}
					
					
					while($stmt->fetch()){
						echo '<option value="'. $teamID .  '">' . $teamName . '</option>';
					}
				
				?>
				</select>
				</div>
				<div class="formRow">
					<label for="startDate2">Start Date (YYYY-MM-DD)</label> <input type="date" value="" min="2007-09-29" max="2008-04-06" title="Date must be within 2007/2008 season." id="startDate2" name="startDate2"/>

				</div>
			</fieldset>
			<br />
			<div class="clear" ></div>
			<input type="submit" name="addplayer" value="Add Player" />
		</fieldset>
		
	</form>

	<form method="post" action="addgame.php" id="addGame">
		<fieldset id="addQuery" class="queryForm">
			<legend>Add a Game</legend>
			<div class="formRow">
				<label for="homeTeam">Home Team<span class="required">*</span></label> 
				<select name="homeTeam" id="homeTeam" class="required blockInput" title="Please complete this field."><option value="">Select a team</option>
			<?php
			
				if(!($stmt = $mysqli->prepare("SELECT teamID, teamName FROM TEAMS"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}
				
				if(!($stmt->execute())) {
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				
				if(!($stmt->bind_result($teamID, $teamName))){
					echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
				}
				
				
				while($stmt->fetch()){
					echo '<option value="'. $teamID .  '">' . $teamName . '</option>';
				}
			
			?>
			</select>
			</div>
			<div class="formRow">
				<label for="awayTeam">Away Team<span class="required">*</span></label> 
				<select name="awayTeam" id="awayTeam" class="required blockInput" title="Please complete this field."><option value="">Select a team</option>
			<?php
			
				if(!($stmt = $mysqli->prepare("SELECT teamID, teamName FROM TEAMS"))){
					echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
				}
				
				if(!($stmt->execute())) {
					echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
				}
				
				if(!($stmt->bind_result($teamID, $teamName))){
					echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
				}
				
				
				while($stmt->fetch()){
					echo '<option value="'. $teamID .  '">' . $teamName . '</option>';
				}
			
			?>
			</select>
			</div>
			<div class="formRow">
				<label for="gameDate">Game Date<span class="required">*</span> (YYYY-MM-DD)<input type="date" class="required blockInput" value="" min="2007-09-29" max="2008-04-06" title="Date must be within 2007/2008 season. (2007-09-29 - 2008-04-06)" id="gameDate" name="gameDate"/>
			</div>
			<div class="formRow">
				<label for="gameStatus">Game Status<span class="required">*</span></label> <select name="gameStatus" id="gameStatus" class="required blockInput"><option value="">Select a status</option><option value="RT">Regular time</option><option value="OT">Overtime</option><option value="SO">Shootout</option></select>
			</div>
			<fieldset>
				<legend>Game Stats:</legend>
				<div class="formRow">
					<label for="homeShots">Home Shots<span class="required">*</span></label> <input type="number" value="" title="Please enter a number between 0 and 100." size="3" id="homeShots" name="homeShots" class="required blockInput" />
				</div>
				<div class="formRow">
					<label for="awayShots">Away Shots<span class="required">*</span></label> <input type="number" value="" title="Please enter a number between 0 and 100." size="3" id="awayShots" name="awayShots" class="required blockInput" />
				</div>
				<div class="formRow">
					<label for="homePenaltyMin">Home Penalty Minutes<span class="required">*</span></label> <input type="number" value="" title="Please enter a number between 0 and 200." size="3" id="homePenaltyMin" name="homePenaltyMin" class="required blockInput" />
				</div>
				<div class="formRow">
					<label for="awayPenaltyMin">Away Penalty Minutes<span class="required">*</span></label> <input type="number" value="" title="Please enter a number between 0 and 200." size="3" id="awayPenaltyMin" name="awayPenaltyMin" class="required blockInput" />
				</div>
			</fieldset>
			
			<div class="clear" ></div><br>
			<input type="submit" name="addgame" id="addgame" value="Add Game" />
		</fieldset>
	</form>
	<!--
	<form method="post" action="javascript: void(0)" id="addGoal">
		<fieldset id="addQuery" class="queryForm">
			<legend>Add a Goal</legend>
			<div class="formRow">
				<label for="shotDate">Date<span class="required">*</span> (YYYY-MM-DD)</label> <input type="text" value="" title="Date must be within 2007/2008 season (2007-09-29 - 2008-04-06)." size="25" id="shotDate" class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="shotTeamName">Team Name<span class="required">*</span></label> 
					<select name="shotTeamName" id="shotTeamName" class="required blockInput"><option value="">Select a team</option>
				<?php
				
					if(!($stmt = $mysqli->prepare("SELECT teamID, teamName FROM TEAMS"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->execute())) {
						#echo "Execute failed ". $stmt->errno . " " . $stmt->error;
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					
					if(!($stmt->bind_result($teamID, $teamName))){
						echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					}
					
					
					while($stmt->fetch()){
						echo '<option value="'. $teamID .  '">' . $teamName . '</option>';
					}
				
				?>
				</select>
			
			</div>
			<div class="formRow">
				<label for="takenBy">Taken By<span class="required">*</span></label> 
				<select name="takenBy" id="takenBy" class="required blockInput"><option value="">Select a player</option>
				<?php
					//get player ID, first and last names
					if(!($stmt = $mysqli->prepare("SELECT TEAMS.teamID, PLAYERS.playerID, PLAYERS.firstName, PLAYERS.lastName FROM PLAYERS INNER JOIN PLAYERS_TEAMS ON PLAYERS.playerID = PLAYERS_TEAMS.playerID INNER JOIN TEAMS ON TEAMS.teamID = PLAYERS_TEAMS.teamID"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->execute())) {
						echo "Execute failed ". $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->bind_result($teamID, $playerID, $firstName, $lastName))){
						echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					}
					
					
					while($stmt->fetch()){
						echo '<option value="'. $playerID .  '">' . $teamID . ' - ' . $firstName . ' ' . $lastName . '</option>';
					}
				
				?>
				</select>
			
			
			</div>
			<div class="formRow">
				<label for="period">Period<span class="required">*</span></label> <select name="Period" id="period" class="required blockInput">
					<option value="">Select a Period</option><option value="1">1st Period</option><option value="2">2nd Period</option><option value="3">3rd Period</option><option value="4">Overtime</option>
					<option value="5">Shootout</option></select>
			</div>
			<div class="formRow">
				<label for="elapsedTime">Elapsed Time<span class="required">*</span> (MM:SS)</label> <input type="number" value=""  size="25" id="elapsedTime" class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="teamStr">Team Strength<span class="required">*</span></label> <select name="Team Strength" id="teamStr" class="required blockInput">
					<option value="EV">Even Strength</option><option value="PP">Power Play</option><option value="SH">Shorthanded</option></select>
			</div>
			<div class="clear" ></div>
			<input type="submit" name="addGoal" id="addGoal" value="Add Goal" />
		</fieldset>
	</form>
	-->
	<!--Change later
	<form method="post" action="javascript: void(0)" id="addAssist">
		<fieldset id="addQuery" class="queryForm">
			<legend>Add Assists</legend>
			<div class="formRow">
				<label for="goalID">Goal ID<span class="required">*</span></label> <input type="number" value="" title="Please complete this field." size="25" id="shotID" class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="player1">Player Name<span class="required">*</span></label> <input type="text" value=""  size="25" id="player1" class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="player2">Player Name</label> <input type="text" value=""  size="25" id="player2" class="blockInput" />
			</div>
			<div class="clear" ></div>
			<input type="submit" name="addAssist" id="addAssist" value="Add Assist" />
		</fieldset>
	</form>
	-->
	<form method="post" action="addaward.php" id="addAward">
		<fieldset id="addQuery" class="queryForm">
			<legend>Add an Award</legend>
			<div class="formRow">
				<label for="trophyName">Trophy Name<span class="required">*</span></label> 
				<select name="trophyName"  id="trophyName" class="required blockInput" title="Please complete this field." ><option value="">Select an award</option>
				<?php
				
					if(!($stmt = $mysqli->prepare("SELECT trophyID, trophyName FROM AWARDS"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->execute())) {
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					
					if(!($stmt->bind_result($trophyID, $trophyName))){
						echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					}
					
					
					while($stmt->fetch()){
						echo '<option value="'. $trophyID .  '">' . $trophyName . '</option>';
					}
				
				?>
				</select>
			</div>
			
			<div class="formRow">
				<label for="awardPlayer1">Player Name<span class="required">*</span></label>
				<select id="awardPlayer1" name="awardPlayer1" class="required blockInput" title="Please select a player"><option value="">Select a player</option>
				<?php
					//get player ID, first and last names
					if(!($stmt = $mysqli->prepare("SELECT TEAMS.teamID, PLAYERS.playerID, PLAYERS.firstName, PLAYERS.lastName, PLAYERS.position FROM PLAYERS INNER JOIN PLAYERS_TEAMS ON PLAYERS.playerID = PLAYERS_TEAMS.playerID INNER JOIN TEAMS ON TEAMS.teamID = PLAYERS_TEAMS.teamID"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->execute())) {
						echo "Execute failed ". $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->bind_result($teamID, $playerID, $firstName, $lastName, $position))){
						echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					}
					
					
					while($stmt->fetch()){
						echo '<option value="'. $playerID .  '">' . $teamID . ' - ' . $firstName . ' ' . $lastName . ' (' . $position .  ')</option>';
					}
				
				?>
				</select>
			</div>
			<div class="formRow">
				<label for="awardPlayer2">2nd Player Name</label> 
				<select id="awardPlayer2" name="awardPlayer2" class="blockInput"><option value="">Select a player</option>
				<?php
					//get player ID, first and last names
					if(!($stmt = $mysqli->prepare("SELECT TEAMS.teamID, PLAYERS.playerID, PLAYERS.firstName, PLAYERS.lastName, PLAYERS.position FROM PLAYERS INNER JOIN PLAYERS_TEAMS ON PLAYERS.playerID = PLAYERS_TEAMS.playerID INNER JOIN TEAMS ON TEAMS.teamID = PLAYERS_TEAMS.teamID"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->execute())) {
						echo "Execute failed ". $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->bind_result($teamID, $playerID, $firstName, $lastName, $position))){
						echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					}
					
					
					while($stmt->fetch()){
						echo '<option value="'. $playerID .  '">' . $teamID . ' - ' . $firstName . ' ' . $lastName . ' (' . $position .  ')</option>';
					}
				
				?>
				</select>
			
			</div>
			<div class="clear" ></div>
			<br />
			<input type="submit" name="addAward" id="addAward" value="Add Award" />
		</fieldset>
	</form>

</div> <!-- /addQuery -->
<div class="clear" ></div>
<h2><a href="javascript: void(0)" id="selectQueryOpenButton"><img src="addbutton.png" style="display: inline" /> Select Queries</a> <a href="javascript: void(0)" id="selectQueryCloseButton" style="display: none;"><img src="minusbutton.png" style="display: inline" /> Select Queries</a></h2>
<div class="query" id="selectQuery" style="display: none;">
<div class="queryForm">
	<form method="post" action="teams.php" id="teams">
		<fieldset id="selectQuery" class="queryForm" style="clear: none; width: auto; margin-right:20px;">
			<legend>Show Teams</legend>
			
			<div class="clear" ></div>
			<br>
			<input type="submit" name="teamsSubmit"  value="Show All Teams" />
		</fieldset>
	</form>
	<form method="post" action="players.php" id="players">
		<fieldset id="selectQuery" class="queryForm" style="clear: none; width: auto; margin-right:20px;">
			<legend>Show Players</legend>
			
			<div class="clear" ></div>
			<br>
			<input type="submit" name="playersSubmit"  value="Show All Players" />
		</fieldset>
	</form>
	<form method="post" action="games.php" id="games">
		<fieldset id="selectQuery" class="queryForm" style="clear: none; width: auto; margin-right:20px;">
			<legend>Show Games</legend>
			
			<div class="clear" ></div>
			<br>
			<input type="submit" name="gamesSubmit"  value="Show All Games" />
		</fieldset>
	</form>
	<div class="clear"></div>
</div>
<div class="clear"></div>
<div class="queryForm">
	<form method="post" action="playerstats.php" id="playerStats">
		<fieldset id="selectQuery" class="queryForm">
			<legend>Player Stats</legend>
			<div class="formRow">
				<label for="playerStatsID">Player Name<span class="required">*</span></label>
				<select name="playerStatsID" id="playerStatsID" class="required blockInput"><option value="">Select a player</option>
				<?php
					//get player ID, first and last names, but exclude goalies since no stats available for goalies.
					if(!($stmt = $mysqli->prepare("SELECT TEAMS.teamID, PLAYERS.playerID, PLAYERS.firstName, PLAYERS.lastName FROM PLAYERS INNER JOIN PLAYERS_TEAMS ON PLAYERS.playerID = PLAYERS_TEAMS.playerID INNER JOIN TEAMS ON TEAMS.teamID = PLAYERS_TEAMS.teamID where PLAYERS.position <> 'G'"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->execute())) {
						echo "Execute failed ". $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->bind_result($teamID, $playerID, $firstName, $lastName))){
						echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					}
					
					
					while($stmt->fetch()){
						echo '<option value="'. $playerID .  '">' . $teamID . ' - ' . $firstName . ' ' . $lastName . '</option>';
					}
				
				?>
				</select>
			
			</div>
			<div class="clear" ></div>
			<input type="submit" name="playerstats" id="playerStatsButton" value="Show Player Stats" />
		</fieldset>
	</form>
	<div class="clear"></div>
</div>
<div class="clear"></div>
<div class="queryForm query">
	
		<fieldset id="selectQuery" class="queryForm">
			<legend>Game Stats</legend>
			<form method="post" action="gamestats.php" id="gameStats">
			
			<div class="formRow">
				<label for="gameStatsDate">Game Date<span class="required">*</span> (YYYY-MM-DD)</label> <input onchange="updateFields()" type="Date" value="" min="2007-09-29" max="2008-04-06" title="Please complete this field." size="25" id="gameStatsDate" name="gameStatsDate" class="required blockInput" />
			</div>
			<div class="formRow">
				<label for="gameStatsTeamName">Team Name<span class="required">*</span></label> 
				<select name="gameStatsTeamName" id="gameStatsTeamName" nameclass="gameStatsTeamName" ="required blockInput" onchange="updateFields()"><option value="">Select a team</option>
				<?php
				
					if(!($stmt = $mysqli->prepare("SELECT teamID, teamName FROM TEAMS"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->execute())) {
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					
					if(!($stmt->bind_result($teamID, $teamName))){
						echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					}
					
					
					while($stmt->fetch()){
						echo '<option value="'. $teamID .  '">' . $teamName . '</option>';
					}
				
				?>
				</select>
			</div>
			
			<div class="clear" ></div>
			<input type="submit" name="scoringSummary" id="scoringSummaryButton" value="Show Scoring Summary" style="margin-right:20px; float:left;" />
			</form>
			
			<form method="post" action="teamsummary.php" id="teamSummary">
			<input type="hidden" value=""  id="teamSummaryDate" name="teamSummaryDate" class="required blockInput" />
			<input type="hidden" value=""  id="teamSummaryTeam" name="teamSummaryTeam" class="required blockInput" />
			<input type="submit" name="teamSummary" id="teamSummaryButton" value="Show Team Summary" style="float: left;"/>
			</form>
		</fieldset>
	
	
	<!--<div id="scoringSummaryTable" class="statsTable" style="display: none;">
		<table id="scoringSummary">
			<tr><td>Period</td><td>Team</td><td>Goal By:</td><td>Assisted By:</td><td>Team Strength</td></tr>
			<tr><td>['period']</td><td>['team']</td><td>['goalBy']</td><td>['assistBy']</td><td>['teamStr']</td></tr>
		</table>
	</div> <!-- /scoringSummarytable -->

	<!--<div id="teamSummaryTable" class="statsTable" style="display: none;">
		<table id="teamSummary">
			<tr><td>Goals For</td><td>Goals Against</td><td>Shots For</td><td>Shots Against</td></tr>
			<tr><td>['goalsfor']</td><td>['goalsagainst']</td><td>['shotsfor']</td><td>['shotsagainst']</td></tr>
		</table>
	</div> <!-- /teamSummarytable -->
</div>

<div class="queryForm">
	<form method="post" action="standings.php" id="teamStandings"><!--Change later-->
		<fieldset id="selectQuery" class="queryForm">
			<legend>Team Standings</legend>
			<div class="formRow">
				<label for="teamStandingsName" style="width: 320px;">Team Name (or select no team for all team standings)</label> 
				<select name="teamStandingsName" id="teamStandingsName" class="required blockInput"><option value="">Select a team</option>
				<?php
				
					if(!($stmt = $mysqli->prepare("SELECT teamID, teamName FROM TEAMS"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->execute())) {
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					
					if(!($stmt->bind_result($teamID, $teamName))){
						echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					}
					
					
					while($stmt->fetch()){
						echo '<option value="'. $teamID .  '">' . $teamName . '</option>';
					}
				
				?>
				</select>
			</div>
			<div class="clear" ></div>
			<input type="submit" name="teamStandings" id="teamStandingsButton" value="Show Team Standings" />
		</fieldset>
	</form>
	<!--<div id="teamStandingsTable" class="statsTable" style="display: none;">
		<table id="teamStandings">
			<tr><td>Team Name</td><td>Games Played</td><td>Wins</td><td>Losses</td><td>OT Losses</td><td>Points</td><td>Home Record</td><td>Away Record</td></tr>
			<tr><td>['teamName']</td><td>['gamesPlayed']</td><td>['wins']</td><td>['losses']</td><td>['OTLosses']</td><td>['points']</td><td>['homeRecord']</td><td>['awayRecord']</td></tr>
		</table>
	</div> <!-- /teamStandingsTable -->
</div>
	
	<form method="post" action="awards.php" id="showAward">
		<fieldset id="selectQuery" class="queryForm">
			<legend>Show Awards</legend>
			<div class="formRow">
				<label for="trophyName">Award Name<span class="required">*</span></label>
				<select name="trophyName"  id="trophyName" class="required blockInput" title="Please complete this field." ><option value="">Select an award</option>
				<?php
				
					if(!($stmt = $mysqli->prepare("SELECT trophyID, trophyName FROM AWARDS"))){
						echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
					}
					
					if(!($stmt->execute())) {
						echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
					}
					
					if(!($stmt->bind_result($trophyID, $trophyName))){
						echo "Bind failed: " . $stmt->errno . " " . $stmt->error;
					}
					
					
					while($stmt->fetch()){
						echo '<option value="'. $trophyID .  '">' . $trophyName . '</option>';
					}
				
				?>
				</select>
			
			</div>
			
			
			<div class="clear" ></div>
			<input type="submit" name="showAward" id="showAward" value="Show Awardees" />
		</fieldset>
	</form>
</div> <!-- /select Query -->

</body>
</html>