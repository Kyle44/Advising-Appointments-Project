<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: AdvisorOptionHeaders.php
File Description: This file directs the student to the correct page,depending on their choice.
*/

session_start();
include('CommonMethods.php');

$_SESSION['showAdvisorOptionsMessage'] = false;//hide message

//error checking functions, these return true if there is an error

function errorCheckIDFormat($ID){
//error checking for valid id format: 7 chars, first 2 capital, last 5 numeric
	if(strlen($ID)!=7 || !ctype_upper(substr($ID,0,2))
									|| !is_numeric(substr($ID,2,5))){
		return true;
	}
	else{
		return false;
	}
}

function errorCheckIDinDB($ID){
	
	//checking if the id is in Student_Info2
	//instantiating common to execute queries
	$debug = false;
	$COMMON = new Common($debug);
	//hold names of advisors for this page
	$students = array();
	$sql = "SELECT * FROM `Student_Info2` WHERE `studentId` = '$ID'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	if(mysql_num_rows($rs) == 0){	
		return true;
	}
	else{
		return false;
	}
}

function errorCheckName($name){
	if(strlen($name)==0 || !ctype_upper(substr($name, 0, 1))){
		return true;
	}
	else{
		return false;
	}
}

function errorCheckEmail($email){
	$emailPattern = "/[^@]+@[^@]+/";
	if (strlen($email)==0 || !preg_match($emailPattern, $email)){
		return true;
	}
	else{
		return false;
	}
}

function errorCheckNewAndExisting(){
	
	if(
		//if at least one of the new student fields is populated AND the existing ID is populated
		((strlen($_SESSION['scheduleNewfName'])>0 ||
		strlen($_SESSION['scheduleNewlName'])>0 ||
		strlen($_SESSION['scheduleNewEmail'])>0 ||
		strlen($_SESSION['scheduleNewID']))>0 &&
		strlen($_SESSION['scheduleExistingID'])>0)
		||
		//or if all fields are blank
		(strlen($_SESSION['scheduleNewfName'])==0&&
		strlen($_SESSION['scheduleNewlName'])==0&&
		strlen($_SESSION['scheduleNewID'])==0&&
		strlen($_SESSION['scheduleNewEmail'])==0&&
		strlen($_SESSION['scheduleExistingID'])==0)
	  )
	{
		return true;
	}
	else{
		return false;
	}

}

//grabbing variables from post
//radio buttons
$advisorsDecision = $_POST['rb_option'];
$_SESSION['advisorDecision'] = $advisorsDecision;

//select boxes
$advisorView = $_POST['sel_advisorView'];
$advisorReschedule = $_POST['sel_advisorReschedule'];
$advisorSchedule = $_POST['sel_advisorSchedule'];
$_SESSION['advisorView'] = $advisorView;
if($advisorsDecision == 'rescheduleToGroup'){
	$_SESSION['advisorReschedule'] = 'GROUPAP';
}
else if($advisorsDecision == 'rescheduleToIndividual'){
	$_SESSION['advisorReschedule'] = $advisorReschedule;
}

if($advisorsDecision == 'scheduleGroup'){
	$_SESSION['advisorSchedule'] = 'GROUPAP';
}
else if($advisorsDecision == 'scheduleIndividual'){
	$_SESSION['advisorSchedule'] = $advisorSchedule;
}


//text fields
$searchStudentID = trim($_POST['text_searchStudentID']);
$searchStudentlName = trim($_POST['text_searchStudentlName']);
$cancelID = trim($_POST['text_cancelID']);
$rescheduleID = trim($_POST['text_rescheduleID']);
$scheduleNewfName = trim($_POST['text_scheduleNewfName']);
$scheduleNewlName = trim($_POST['text_scheduleNewlName']);
$scheduleNewMajor = trim($_POST['sel_scheduleNewMajor']);
$scheduleNewEmail=  trim($_POST['text_scheduleNewEmail']);
$scheduleNewID = trim($_POST['text_scheduleNewID']);
$scheduleExistingID = trim($_POST['text_scheduleExistingID']);

$_SESSION['searchStudentID'] = $searchStudentID;
$_SESSION['searchStudentlName'] = $searchStudentlName;
$_SESSION['cancelID'] = $cancelID;
$_SESSION['rescheduleID'] = $rescheduleID;
$_SESSION['scheduleNewfName'] = $scheduleNewfName;
$_SESSION['scheduleNewlName'] = $scheduleNewlName;
$_SESSION['scheduleNewMajor'] = $scheduleNewMajor;
$_SESSION['scheduleNewEmail'] = $scheduleNewEmail;
$_SESSION['scheduleNewID'] = $scheduleNewID;
$_SESSION['scheduleExistingID'] = $scheduleExistingID;



//headers

if($advisorsDecision == 'selectAppointment'){
	header('Location: AdvisorCreateAppointment.php');
}

elseif($advisorsDecision == 'viewAppointment'){
	
	header('Location: AdvisorViewApts.php');
}

elseif($advisorsDecision == 'searchStudentID'){
	
	if(errorCheckIDFormat($searchStudentID)){
		$_SESSION['showAdvisorOptionsMessage'] = true;
		$_SESSION['advisorOptionsMessage'] = 
			'Error: The ID being searched is blank or in an invalid format.';
		header('Location: AdvisorOptions.php');	
	}
	else{
		header'Location: AdvisorSearchID.php');
	}
	//check if in DB in next page

}

elseif($advisorsDecision == 'searchStudentlName'){
	
	if(errorCheckName($searchStudentlName)){
		$_SESSION['showAdvisorOptionsMessage'] = true;
		$_SESSION['advisorOptionsMessage'] = 
			'Error: The last name being searched is blank or in an invalid format.';
		header('Location: AdvisorOptions.php');
	}
	else{
		header'Location: AdvisorSearchlName.php');
	}
}

elseif($advisorsDecision == 'cancelAppointment'){
	
	if(errorCheckIDFormat($cancelID)){
		$_SESSION['showAdvisorOptionsMessage'] = true;
		$_SESSION['advisorOptionsMessage'] = 
			'Error: The ID being used to cancel an appointment is blank or in an invalid format.';
		header('Location: AdvisorOptions.php');	
	}
	else if(errorCheckIDinDB($cancelID)){
		$_SESSION['showAdvisorOptionsMessage'] = true;
		$_SESSION['advisorOptionsMessage'] = 
			'Error: The ID being used to cancel an appointment is not in the database.';
		header('Location: AdvisorOptions.php');
	}
	else{
		header('Location: AdvisorCheckStudentAppointments.php');
	}

}
elseif($advisorsDecision == 'rescheduleToGroup' || $advisorsDecision == 'rescheduleToIndividual'){
	
	
	if(errorCheckIDFormat($rescheduleID)){
		$_SESSION['showAdvisorOptionsMessage'] = true;
		$_SESSION['advisorOptionsMessage'] = 
			'Error: The ID being used to reschedule an appointmnet is blank or in an invalid format.';	
		header('Location: AdvisorOptions.php');
	}
	else if(errorCheckIDinDB($rescheduleID)){
		$_SESSION['showAdvisorOptionsMessage'] = true;
		$_SESSION['advisorOptionsMessage'] = 
			'Error: The ID being used to reschedule an appointment is not in the database.';
		header('Location: AdvisorOptions.php');
	}
	else{
		header('Location: AdvisorCheckStudentAppointments.php');
	}

}

elseif($advisorsDecision == 'scheduleGroup' || $advisorDecision = 'scheduleIndividual'){
	

	if(errorCheckNewAndExisting()){
		$_SESSION['showAdvisorOptionsMessage'] = true;
		$_SESSION['advisorOptionsMessage'] = 
			'Error: Enter either a new student&#39s information or an existing student&#39s information, but not both or none.';		
		header('Location: AdvisorOptions.php');
	}
	//if using existing student
	else if(!empty($scheduleExistingID)){
		if(errorCheckIDFormat($scheduleExistingID)){
			$_SESSION['showAdvisorOptionsMessage'] = true;
			$_SESSION['advisorOptionsMessage'] = 
				'Error: The ID being used to schedule an appointment
				 with an existing student is in an invalid format.';
			header('Location: AdvisorOptions.php');	
		}
		else if(errorCheckIDinDB($scheduleExistingID)){
			$_SESSION['showAdvisorOptionsMessage'] = true;
			$_SESSION['advisorOptionsMessage'] = 
			'Error: The ID being used to schedule an appointment<br>
			 with an existing student is not in the database.';
			header('Location: AdvisorOptions.php');
		}
		else{
			header('Location: AdvisorCheckStudentAppointments.php');
		}
	}
	//if using new student
	else if(!$scheduleExistingID){

		//check for wrong format or blank fields in new student
		if(errorCheckName($scheduleNewfName)){
			$_SESSION['showAdvisorOptionsMessage'] = true;
			$_SESSION['advisorOptionsMessage'] = 
				'Error: The first name being used to schedule an appointment
				 with a new student is blank or in an invalid format.';
			header('Location: AdvisorOptions.php');
		}
		elseif(errorCheckName($scheduleNewlName)){
			$_SESSION['showAdvisorOptionsMessage'] = true;
			$_SESSION['advisorOptionsMessage'] = 
				'Error: The last name being used to schedule an appointment
				 with a new student is blank or in an invalid format.';
			header('Location: AdvisorOptions.php');	
		}
		elseif(errorCheckEmail($scheduleNewEmail)){
			$_SESSION['showAdvisorOptionsMessage'] = true;
			$_SESSION['advisorOptionsMessage'] = 
				'Error: The email being used to schedule an appointment
				 with a new student is blank or in an invalid format.';
			header('Location: AdvisorOptions.php');
		}
		elseif(errorCheckIDFormat($scheduleNewID)){
			$_SESSION['showAdvisorOptionsMessage'] = true;
			$_SESSION['advisorOptionsMessage'] = 
				'Error: The ID being used to schedule an appointment
				 with a new student is blank or in an invalid format.';
			header('Location: AdvisorOptions.php');
		}
		elseif(!errorCheckIDinDB($scheduleNewID)){
			$_SESSION['showAdvisorOptionsMessage'] = true;
			$_SESSION['advisorOptionsMessage'] = 
				'Error: The ID being used to schedule an appointment with a new
				student is already in the database. Please enter the ID in the 
				existing student section instead.';
			header('Location: AdvisorOptions.php');
		}
		else{
			header('Location: AdvisorCheckStudentAppointments.php');
		}
	}
	
}

?>
