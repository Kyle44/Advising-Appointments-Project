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


function errorCheckNameInDB($name){
	
	//checking if the name is in Student_Info2
	//instantiating common to execute queries
	$debug = false;
	$COMMON = new Common($debug);
	$sql = "SELECT * FROM `Student_Info2` WHERE `lName` = '$name'";
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
function checkStudentAppointments($studentId){
	$debug = false;
	$COMMON = new Common($debug);

	$advisors = $_SESSION['advisors'];
	$_SESSION['studentHasPastAppointment'] = false;
	$_SESSION['studentHasUpcomingAppointment'] = false;
	$_SESSION['currentWeekEnabled'] = true;
	$_SESSION['upcomingWithinDay'] = false;

	$now = date("Y-m-d H:i:s");


	//check for past/upcoming appointments for the student
	$latestIndTime;
	$sql = "SELECT * FROM Advising_Appointments2 WHERE `studentId` = '$studentId'";
	$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
	
	while($row = mysql_fetch_assoc($rs)){
		if($row['advisorId'] != 'GROUPAP'){
				$latestIndTime = $row['dateTime'];
				$thisMonday = date("Y-m-d H:i:s",strtotime("monday this week",strtotime($latestIndTime)));
				//$thisSaturday = date("Y-m-d H:i:s",strtotime("saturday"));
				if($thisMonday < $latestIndTime && $latestIndTime < $now){
					$_SESSION['currentWeekEnabled'] = false;
				}
			}
		if($row['dateTime'] < $now){
			$_SESSION['studentHasPastAppointment'] = true;
			//if they have a past appointment, they're not allowed to do group
			$_SESSION['groupEnabled'] = false;
		}
		else{
			$_SESSION['studentHasUpcomingAppointment'] = true;
			if($row['dateTime'] < date('Y-m-d H:i:s',strtotime('+1 days', strtotime($now)))){
				$_SESSION['upcomingWithinDay'] = true;
			} 
		}
				 
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

if($advisorsDecision == 'rescheduleAppointment'){
	$_SESSION['advisorReschedule'] = $advisorReschedule;
}

else if($advisorsDecision == 'scheduleAppointment'){
	$_SESSION['advisorSchedule'] = $advisorSchedule;
}


//text fields
$searchStudentID = trim($_POST['text_searchStudentID']);
$cancelID = trim($_POST['text_cancelID']);
$rescheduleID = trim($_POST['text_rescheduleID']);
$scheduleNewfName = trim($_POST['text_scheduleNewfName']);
$scheduleNewlName = trim($_POST['text_scheduleNewlName']);
$scheduleNewMajor = trim($_POST['sel_scheduleNewMajor']);
$scheduleNewEmail=  trim($_POST['text_scheduleNewEmail']);
$scheduleNewID = trim($_POST['text_scheduleNewID']);
$scheduleExistingID = trim($_POST['text_scheduleExistingID']);

$_SESSION['searchStudentID'] = $searchStudentID;
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
	$today = date("Y-m-d 00:00:00");
	$_SESSION['viewAptsDate'] = $today;
	header('Location: AdvisorViewApts.php');
}

elseif($advisorsDecision == 'searchStudentID'){
	
	if(errorCheckIDFormat($searchStudentID) && errorCheckName($searchStudentID)){
		$_SESSION['showAdvisorOptionsMessage'] = true;
		$_SESSION['advisorOptionsMessage'] = 
			'Error: The ID or last name being searched is blank
			 or is in an invalid format';
		header('Location: AdvisorOptions.php');	
	}
	elseif(errorCheckIDinDB($searchStudentID) && errorCheckNameInDB($searchStudentID)){
		$_SESSION['showAdvisorOptionsMessage'] = true;
		$_SESSION['advisorOptionsMessage'] = 
			'The student with last name or ID '.$searchStudentID.' was not found.';
		header('Location: AdvisorOptions.php');	
	}
	else{
		header('Location: AdvisorSearchText.php');
	}
	//check if in DB in next page

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
		checkStudentAppointments($cancelID);
		if(!$_SESSION['studentHasUpcomingAppointment']){
			$_SESSION['showAdvisorOptionsMessage'] = true;
			$_SESSION['advisorOptionsMessage'] = 
			'Error: The student has no upcoming appointments to cancel at this time.';
			header('Location: AdvisorOptions.php');
		}
		else{
			header('Location: AdvisorCancelAppointment.php');
		}
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
		checkStudentAppointments($rescheduleID);
		if(!$_SESSION['studentHasUpcomingAppointment']){
			$_SESSION['showAdvisorOptionsMessage'] = true;
			$_SESSION['advisorOptionsMessage'] = 
			'Error: The student has no upcoming appointments to change at this time.';
			header('Location: AdvisorOptions.php');
		}
		else{
			header('Location: AdvisorRescheduleAppointment.php');
		}
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
			'Error: The ID being used to schedule an appointment
			 with an existing student is not in the database.';
			header('Location: AdvisorOptions.php');
		}
		else{
			checkStudentAppointments($scheduleExistingID);
			header('Location: AdvisorScheduleAppointment.php');
			if($_SESSION['studentHasUpcomingAppointment']){
				$_SESSION['showAdvisorOptionsMessage'] = true;
				$_SESSION['advisorOptionsMessage'] = 
				"Error: The existing student with this ID already has an upcoming appointment, 
				so you cannot create another one. However, you can reschedule or
				cancel this student's appointment.<br><br>";
				header('Location: AdvisorOptions.php');

			}
			else{
				header('Location: AdvisorScheduleAppointment.php');
			}
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
			checkStudentAppointments($scheduleNewID);
			header('Location:AdvisorScheduleAppointment.php');
			
		}
	}
}

?>
