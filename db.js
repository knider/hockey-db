
//Functions to open and close add/select queries
$(document).ready(function(){
	$("#addQueryOpenButton").click(function(){
	$("#addQuery").show();
	$("#addQueryCloseButton").show();
	$("#addQueryOpenButton").hide();
	});
	
	$("#addQueryCloseButton").click(function(){
	$("#addQuery").hide();
	$("#addQueryCloseButton").hide();
	$("#addQueryOpenButton").show();
	});
	
	$("#selectQueryOpenButton").click(function(){
	$("#selectQuery").show();
	$("#selectQueryCloseButton").show();
	$("#selectQueryOpenButton").hide();
	});
	
	$("#selectQueryCloseButton").click(function(){
	$("#selectQuery").hide();
	$("#selectQueryCloseButton").hide();
	$("#selectQueryOpenButton").show();
	});
});

//Validation for required fields
jQuery.validator.addClassRules({
  required: {
    required: true,
  }
});

//Turn on validation for all queries except special cases
$(document).ready(function(){
	$("#addTeam").validate()	
	$("#addAssist").validate() 
	$("#addAward").validate() 
	$("#playerStats").validate() 
	$("#gameStats").validate() 
	$("#showAward").validate()
});

//Validation for addPlayer query
//Jersey number must be between 1 and 99
$(document).ready(function(){
	$("#addPlayer").validate({
		rules: {
		    jerseyNum: {
		      range: [1, 99],
		      required: true,
		    }
		},
		//where to place the error text
		errorPlacement: function(error, element) {
			error.insertAfter(element);
		}
	});
	
});

//Validation for addGame query
//Shots must be between 0 and 100
//Penalty Minutes must be between 0 and 200
$(document).ready(function(){
	$("#addGame").validate({
		rules: {
		    homeShots: {
		      range: [0, 100],
		      required: true,
		    },
		    awayShots: {
		      range: [0, 100],
		      required: true,
		    },
		    homePenaltyMin: {
		      range: [0, 200],
		      required: true,
		    },
		    awayPenaltyMin: {
		      range: [0, 200],
		      required: true,
		    }
		},
		//where to place the error text
		errorPlacement: function(error, element) {
			error.insertAfter(element);
		}
	});
	
});

//Validation for addGoal query
//Minutes must be between 0 and 19 
//Seconds must be between 0 and 59
$(document).ready(function(){
	$("#addGoal").validate({
		rules: {
		    elapsedMin: {
		      range: [0, 19],
		      required: true,
		    },
		    elapsedSec: {
		      range: [0, 59],
		      required: true,
		    }
		},
		errorPlacement: function(error, element) {
			error.insertAfter(element);
		}
	});
	
});

//Copy form fields in the Game Stats query from scoring summary to team summary
//This allows 2 buttons to appear to work on one form
function updateFields() {
	var GameDate = document.getElementById("gameStatsDate").value;
	document.getElementById("teamSummaryDate").value = GameDate;
	var GameTeam = document.getElementById("gameStatsTeamName").value;
	document.getElementById("teamSummaryTeam").value = GameTeam;
}