<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: ValidateAdvisorSignin.php
File Description: Validation for student signin
*/

session_start();
include('../CommonMethods.php');

//returns in sql format
function minusTwoBusinessDays($day){
	//echo"Day input: $day<br>";
	$day = date('Y-m-d 23:59:59',strtotime($day));
	//echo "$day<br>";
	$dayMinusTwoDOW = date('l',strtotime('-2 days', strtotime($day)));
	//echo "$dayMinusTwoDOW";
	$dayMinusTwo = date('Y-m-d H:i:s', strtotime('-2 days', strtotime($day)));
	//echo "$dayMinusTwo<br>";
	//Day minus 2 is called from a business day that returns another business day
	if($dayMinusTwoDOW == 'Monday' ||$dayMinusTwoDOW == 'Tuesday' ||$dayMinusTwoDOW == 'Wednesday'){
		return($dayMinusTwo);
	}
	//day minus 2 is Sunday (called from a Tuesday)
	else if($dayMinusTwoDOW == 'Sunday' || $dayMinusTwoDOW == 'Saturday'){
		return(date('Y-m-d H:i:s', strtotime('-4 days',strtotime($day))));
	}
	//day minus 2 is Friday (called from a Sunday)
	else if($dayMinusTwoDOW == 'Friday'){
		return (date('Y-m-d H:i:s', strtotime('-3 days', strtotime($day))));
	}
	//day minus 2 is Thursday (called from a saturday)
	else{
		return ($dayMinusTwo);
	}
}
//Testing minusTwoBusinessDays
/*
$day = minusTwoBusinessDays(date('l', strtotime("Monday")));
echo date('l',strtotime($day));
$day = minusTwoBusinessDays(date('l', strtotime("Tuesday")));
echo date('l',strtotime($day));
$day = minusTwoBusinessDays(date('l', strtotime("Wednesday")));
echo date('l',strtotime($day));
$day = minusTwoBusinessDays(date('l', strtotime("Thursday")));
echo date('l',strtotime($day));
$day = minusTwoBusinessDays(date('l', strtotime("Friday")));
echo date('l',strtotime($day));
$day = minusTwoBusinessDays(date('l', strtotime("Saturday")));
echo date('l',strtotime($day));
$day = minusTwoBusinessDays(date('l', strtotime("Sunday")));
echo date('l',strtotime($day));

//this has to be the ceiling of 2 days

*/


if($_SESSION['lastPage'] == 'StudentSignin.php'){
	//add signin info to session
	$_SESSION['fName'] = trim($_POST['fName']);
	$_SESSION['lName'] = trim($_POST['lName']);
	$_SESSION['major'] = trim($_POST['major']);
	$_SESSION['studentEmail'] = trim($_POST['studentEmail']);
	$_SESSION['studentId'] = trim($_POST['studentId']);

	//$studentId = $_SESSION['studentId'];


///////////////////////////////Signin error checking///////////////////////
	$_SESSION['signinError'] = false;
	//error checking for blank fields
	if(strlen($_SESSION['fName'])==0 || strlen($_SESSION['lName'])==0
					||strlen($_SESSION['studentId'])==0
					||strlen($_SESSION['studentEmail']) == 0){
   		$_SESSION['signinError'] = true;
	}

	//error checking for capital letters in f/lname
	elseif(!ctype_upper(substr($_SESSION['fName'], 0, 1)) || 
		!ctype_upper(substr($_SESSION['lName'], 0, 1))){
    		$_SESSION['signinError'] = true;
	}

	//error checking for valid email format:
	//using regexes.Note: not checking .com,etc
	$emailPattern = "/[^@]+@[^@]+/";
	if (!preg_match($emailPattern, $_SESSION['studentEmail'])){
		$_SESSION['signinError'] = true;
	}

	//error checking for valid id format: 7 chars, first 2 capital, last 5 numeric
	elseif(strlen($_SESSION['studentId'])!=7 || !ctype_upper(substr($_SESSION['studentId'],0,2))
										|| !is_numeric(substr($_SESSION['studentId'],2,5))){
		$_SESSION['signinError'] = true;
	}

}//end if last page...

//go back to signin if error was found
if($_SESSION['signinError'] == true){
	header('Location: StudentSignin.php');
}
//after this point, successful login


//now, process signin info to determine which options the student can choose////
else{
//I emailed Josh Abrams, and he said the following in response:
/**
Only allow one individual appointment per week, doesn't have to be the same advisor
Earliest: 9:00 for individual
Times can show up: Between Two business days after today and 1 week after today
Latest day in semester to get an appointment for course registration is Apr 30/ Nov 30
Students Typically either do individual or group appointment
	Individual appointment students shouldn't do group later
	Group may do individual after group, in which case, within one week of the individual
		 appointment is fine
*/

	//need to make sure they have an existing apponitment in order to cancel or change
	//to view, a student must have at least one appointment on file, past or present
	//the disabled attribute in html tag will make it unselectable

	//instantiating common to execute queries
	$debug = false;
	$COMMON = new Common($debug);

	//get list of advisors from the db: Advisor_Info
	//key: Id, value: fname lname 
	//	   the ID is what will be stored throughout, but the fname lname will be displayed for the user
	$advisors = array();
	//hold names of advisors for this page
	
	$sql = "SELECT * FROM `Advisor_Info2`";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

	//pushing to advisors array with their id as key
	while($row = mysql_fetch_assoc($rs)){
		$flName = $row['fName']." ".$row['lName'];
		$advisors[$row['employeeId']] = $flName;
	}

	$studentId = $_SESSION['studentId'];
	$_SESSION['advisors'] = $advisors;	
	$_SESSION['groupEnabled'] = true;
	$_SESSION['indEnabled'] = true;
	$_SESSION['currentWeekEnabled'] = true;
	$_SESSION['studentHasUpcomingAppointment'] = false;
	$_SESSION['viewEnabled'] = false;
	$_SESSION['studentHasPastAppointment'] = false;
	$_SESSION['upcomingWithinDay'] = false;	

	//this moment, now
	$now = date("Y-m-d H:i:s");

	//student can view created apt if at least one instance of their id is in Advisng_Appointments2
	//Also, checking here what the student chan change their appointment to (if they have one)
	$sql = "SELECT * FROM Advising_Appointments2 WHERE `studentId` = '$studentId'";
	$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
	while($row = mysql_fetch_assoc($rs)){
		//at this point, the student has a past/upcoming appointment
		//so they have something they can view
		$_SESSION['viewEnabled'] = true;
		
		//if they have a past appointment, the student will only be able to change
		//to individual (past true)
		//otherwise, they can change to any type of appointment (past false)
		//echo $row['dateTime']."<br>";
		//echo $now."<br>";
		if($row['dateTime'] < $now){
			$_SESSION['studentHasPastAppointment'] = true;
			//if they have a past appointment, they're not allowed to do group
			$_SESSION['groupEnabled'] = false;
		}
				 
	}
	$sql = "SELECT * FROM Advising_Appointments2 WHERE `studentId` = '$studentId' AND `advisorId` = 'GROUPAP'";
	$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
	$row = mysql_fetch_assoc($rs);
	
	//at least one group appointment 
	if(!empty($row)){		
		
		//disable option to sign up for group
		$_SESSION['groupEnabled'] = false;
		$groupEndTime = date("Y-m-d H:i:s",strtotime('+30 minutes',strtotime($row['dateTime'])));
		//checking if now is before the group appointment (there should only be one)
		//echo"$groupEndTime<br>";
		//echo"$now<br>";
		if($now < $groupEndTime){
			//disable option to sign up for individual
			$_SESSION['indEnabled'] = false;
			//echo"should have upcoming<br>";
			$_SESSION['studentHasUpcomingAppointment'] = true;
			//$_SESSION['upcomingAppointment'] = $groupEndTime;
			//must be 2 days, otherwise, they might try to change their appointment
			//and then realize they can't sign up for any times within 2 days
			$groupEndMinusDay = date('Y-m-d H:i:s', strtotime(minusTwoBusinessDays($groupEndTime)));
			if($groupEndMinusDay < $now){
				$_SESSION['upcomingWithinDay'] = true;
				//echo "$groupEndMinusDay $now<br>";
				//echo "hello<br>";
			}
		}
	}


	//checking if they can sign up for individual
	
	//echo"$studentId<br>";	
	$sql = "SELECT * FROM Advising_Appointments2 WHERE `studentId` = '$studentId' AND `advisorId` != 'GROUPAP'";
	$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);

	$indApps = array();
	
	while($row = mysql_fetch_assoc($rs)){
		array_push($indApps,$row);
	}

	//var_dump($indApps);
	if(!empty($indApps)){
		
		//getting the latest ind apt the student signed up for
		$latestApt = $indApps[0];
		foreach($indApps as $apt){
			if($apt['dateTime'] > $latestApt['dateTime']){
				$latestApt = $apt;
			}
		}
		//now, $latestApt is the latest ind apt the student signed up for
		//end of half-hour session, in sql format
		$latestIndAptEndTime = date("Y-m-d H:i:s", strtotime('+30 minutes', strtotime($latestApt['dateTime'])));
	}

	if($latestIndAptEndTime > $now){
		//don't allow student to sign up for anything if they haven't attended
		//their latest appointment yet
		$_SESSION['indEnabled'] = false;
		$_SESSION['groupEnabled'] = false;
		//$_SESSION['upcomingAppointment'] = $latestIndAptEndTime;
		$_SESSION['studentHasUpcomingAppointment'] = true;
		//echo"$latestIndAptEndTime<br>";
		$indEndMinusDay = date("Y-m-d H:i:s", strtotime(minusTwoBusinessDays($latestIndAptEndTime)));
		//echo"$latestIndAptEndTime<br>";
		//echo"indendminusDay: $indEndMinusDay<br>";
		///////////////to do: HasUpcoming shouldn't be end time,
		///////////////       make some options booleans better 
		//echo "$indEndMinusDay<br>";	
		if($indEndMinusDay < $now){
				//echo "$indEndMinusDay $now<br>";
	
				$_SESSION['upcomingWithinDay'] = true;
			}
	}


	//don't let student sign up for another ind apt within same week
	//starts off as true, so this is the only way the current week can be disabled
	$thisMonday = date("Y-m-d H:i:s",strtotime("monday this week",strtotime($latestIndAptEndTime)));
	$thisSaturday = date("Y-m-d H:i:s",strtotime("saturday"));
	if($latestIndAptEndTime > $thisMonday && $latestIndAptEndTime < $thisSaturday){
		$_SESSION['currentWeekEnabled'] = false;			
	}
	
	//for testing
	//variables to test: groupEnabled, indEnabled,viewEnabled, currentWeekEnabled
	/**********************
		if($_SESSION['groupEnabled']){
			echo "group is enabled<br>";
		}
		else{
			echo "group is diabled<br>";
		}

		if($_SESSION['indEnabled']){
			echo "ind is enabled<br>";
		}
		else{
			echo "ind is diabled<br>";
		}
		if($_SESSION['viewEnabled']){
			echo "view enabled<br>";
		}
		else{
			echo "view disabled<br>";
		}
		if($_SESSION['studentHasUpcomingAppointment']){
			echo "has upcoming<br>";
		}
		else{
			echo "does not have upcoming<br>";
		}
		if($_SESSION['studentHasPastAppointment']){
			echo"does have past appointment<br>";
		}
		else{
			echo"does not have past appointment<br>";
		}
		if($_SESSION['upcomingWithinDay']){
			echo "upcoming appointment within two days<br>";
		}
		else{
			echo "upcoming appointment not within two days<br>";
		}
		if($_SESSION['currentWeekEnabled']){
			echo "current week is enabled<br>";
		}
		else{
			echo "current week is diabled<br>";
		}

	**********************/
	$_SESSION['lastPage'] = 'ValidateStudentSignin.php';
	header('Location: StudentOptions.php');	
}//end else


?>
