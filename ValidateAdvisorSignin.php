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
include('CommonMethods.php');

//add signin info to session
$_SESSION['fName'] = trim($_POST['fName']);
$_SESSION['lName'] = trim($_POST['lName']);
$_SESSION['advisorPhoneNumber'] = trim($_POST['advisorPhoneNumber']);
$_SESSION['advisorEmail'] = trim($_POST['advisorEmail']);
$_SESSION['advisorId'] = trim($_POST['advisorId']);

$advisorId = $_SESSION['advisorId'];

///////////////////////////////Signin error checking///////////////////////
$_SESSION['signinError'] = false;
//error checking for blank fields
if(strlen($_SESSION['fName'])==0 || strlen($_SESSION['lName'])==0
				||strlen($_SESSION['advisorId'])==0
				||strlen($_SESSION['advisorEmail']) ==0){
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
if (!preg_match($emailPattern, $_SESSION['advisorEmail'])){
	$_SESSION['signinError'] = true;
}

//error checking for valid id format: 7 chars, first 2 capital, last 5 numeric
elseif(strlen($_SESSION['advisorId'])!=7 || !ctype_upper(substr($_SESSION['advisorId'],0,2))
										|| !is_numeric(substr($_SESSION['advisorId'],2,5))){
$_SESSION['signinError'] = true;
}

//go back to signin if error was found
if($_SESSION['signinError'] == true){
 	header('Location: AdvisorSignin.php');
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
	$_SESSION['advisors'] = $advisors;	
	$_SESSION['viewEnabled'] = false;

	
	//student can view created apt if at least one instance of their id is in Advisng_Appointments2
	$sql = "SELECT * FROM Advising_Availability2 WHERE `advisorId` = '$advisorId'";
	$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
	$row = mysql_fetch_assoc($rs);
	if(!empty($row)){
		$_SESSION['viewEnabled'] = true;
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

		if($_SESSION['currentWeekEnabled']){
			echo "current week is enabled<br>";
		}
		else{
			echo "current week is diabled<br>";
		}

	**********************/
	header('Location: AdvisorOptions.php');	
}//end else


?>
