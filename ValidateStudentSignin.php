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

//return true if not in
function errorCheckIDinDB($ID){
	
	//checking if the id is in Student_Info2
	//instantiating common to execute queries
	$debug = false;
	$COMMON = new Common($debug);
	$sql = "SELECT * FROM `Student_Info2` WHERE `studentId` = '$ID'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	if(mysql_num_rows($rs) == 0){	
		//echo "ID $ID don't exists<br>";
		return true;
		
	}
	else{
		//echo "ID $ID exist<br>";
		return false;
	}
}

if($_SESSION['lastPage'] == 'StudentSignin.php'){

	$_SESSION['lastPage'] = 'ValidateStudentSignin.php';
	//add signin info to session
	$_SESSION['fName'] = trim($_POST['fName']);
	$_SESSION['lName'] = trim($_POST['lName']);
	$_SESSION['major'] = trim($_POST['major']);
	$_SESSION['studentEmail'] = trim($_POST['studentEmail']);
	$_SESSION['studentId'] = trim($_POST['studentId']);
	$_SESSION['returningStudentId'] =trim($_POST['returningStudentId']);

	//$studentId = $_SESSION['studentId'];


///////////////////////////////Signin error checking///////////////////////
	$signinError = false;
	//error checking for blank fields

	if	(
	(strlen($_SESSION['returningStudentId']) > 0 &&
		
		(strlen($_SESSION['fName'])>0||
		strlen($_SESSION['lName'])>0||
		strlen($_SESSION['studentEmail']) >0||
		strlen($_SESSION['studentId']) > 0)) ||

	(strlen($_SESSION['returningStudentId']) == 0 &&
		strlen($_SESSION['fName']) == 0 &&
		strlen($_SESSION['lName']) == 0 &&
		strlen($_SESSION['studentEmail']) == 0 &&
		strlen($_SESSION['studentId']) == 0)
		)
	{
		$_SESSION['showStudentSigninErrorMessage'] = true;
		$_SESSION['studentSigninErrorMessage'] = 
		"Please enter either your information (for returning students) or
		your student ID (for returning students), but not none or both.";
		$signinError = true;	
		header('Location: StudentSignin.php');

	}
	elseif(strlen($_SESSION['returningStudentId']) >0){
		if(strlen($_SESSION['returningStudentId'])!=7 || !ctype_upper(substr($_SESSION['returningStudentId'],0,2))
										|| !is_numeric(substr($_SESSION['returningStudentId'],2,5))){
			$_SESSION['showStudentSigninErrorMessage'] = true;
			$_SESSION['studentSigninErrorMessage'] = 
			"Please make sure your student ID is in the format: AB12345.";
			$signinError = true;
			header('Location: StudentSignin.php');
		}	
		elseif(errorCheckIDinDB($_SESSION['returningStudentId'])){
			$_SESSION['showStudentSigninErrorMessage'] = true;
			$_SESSION['studentSigninErrorMessage'] = 
			"Error: the ID entered is not in the database. New Student?";
			$signinError = true;	
			header('Location: StudentSignin.php');
		}
		
	}
	//error checking for capital letters in f/lname
	elseif(!ctype_upper(substr($_SESSION['fName'], 0, 1)) || 
		!ctype_upper(substr($_SESSION['lName'], 0, 1))){
    	$_SESSION['showStudentSigninErrorMessage'] = true;
		$_SESSION['studentSigninErrorMessage'] = 
		"Please capitalize the first letter in your first/last name.";
		$signinError = true;
		header('Location: StudentSignin.php');
	}
	else{
		//error checking for valid email format:
		//using regexes.Note: not checking .com,etc
		$emailPattern = "/[^@]+@[^@]+/";
		if (!preg_match($emailPattern, $_SESSION['studentEmail'])){
			$_SESSION['showStudentSigninErrorMessage'] = true;
			$_SESSION['studentSigninErrorMessage'] = 
			"Please make sure your email is in a valid format.";
			$signinError = true;
			header('Location: StudentSignin.php');
		}
		//error checking for valid id format: 7 chars, first 2 capital, last 5 numeric
		elseif(strlen($_SESSION['studentId'])!=7 || !ctype_upper(substr($_SESSION['studentId'],0,2))
										|| !is_numeric(substr($_SESSION['studentId'],2,5))){
			$_SESSION['showStudentSigninErrorMessage'] = true;
			$_SESSION['studentSigninErrorMessage'] = 
			"Please make sure your student ID is in the format: AB12345.";
			$signinError = true;
			header('Location: StudentSignin.php');
		}
		elseif(!errorCheckIDinDB($_SESSION['studentId'])){
			$_SESSION['showStudentSigninErrorMessage'] = true;
			$_SESSION['studentSigninErrorMessage'] = 
			"Error: The ID entered is already in the database.
			Please enter it into the returning student section instead.";	
			$signinError = true;
			header('Location: StudentSignin.php');
		}
		
	}


}//end if last page...

//after this point, successful login


//now, process signin info to determine which options the student can choose////
if(!$signinError){
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


	//get rest of student's info if returning student
	if(strlen($_SESSION['returningStudentId']) > 0){

		$_SESSION['studentId'] = $_SESSION['returningStudentId'];
		$studentId = $_SESSION['studentId'];
		$sql = "SELECT * FROM Student_Info2 
		WHERE `studentId` = '$studentId'";
		$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
		$row = mysql_fetch_assoc($rs);
		//pull student's info from row	
		$_SESSION['fName'] = $row['fName'];
		$_SESSION['lName'] = $row['lName'];
		$_SESSION['major'] = $row['major'];
		$_SESSION['studentEmail'] = $row['studentEmail'];
		
	}


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
			$groupEndMinusDay = date('Y-m-d H:i:s', strtotime('-1 days',strtotime($groupEndTime)));
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
		$indEndMinusDay = date("Y-m-d H:i:s", strtotime('-1 days', strtotime($latestIndAptEndTime)));
		//echo"$latestIndAptEndTime<br>";
		
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
	
	$_SESSION['lastPage'] = 'ValidateStudentSignin.php';
	
	header('Location: StudentOptions.php');	
}


?>
