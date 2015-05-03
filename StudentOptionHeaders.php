<?php
session_start();

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: StudentInsertDB.php
File Description: This file directs the student to the correct page,depending on their choice.
*/

$studentsChoice = $_POST['rb_option'];
$createAdvisor = $_POST['sel_createAdvisor'];
$changeAdvisor = $_POST['sel_changeAdvisor'];
$_SESSION['studentChoice'] = $studentsChoice;
$_SESSION['showStudentOptionsMessage'] = false;

if($studentsChoice == 'createGroupAppointment'){
	$_SESSION['studentsCreateAdvisor'] = 'GROUPAP';
	if($_SESSION['studentHasUpcomingAppointment']){
		$_SESSION['showStudentOptionsMessage'] = true;
		$_SESSION['studentOptionsMessage'] = 
		"You cannot create a new appointment because you already have an upcoming appointment.";
		header('Location: StudentOptions.php');
	}
	else if($_SESSION['studentHasPastAppointment']){
		$_SESSION['showStudentOptionsMessage'] = true;
		$_SESSION['studentOptionsMessage'] = 
		"If you have had past appointments, you are only allowed to create individual appointments.";
		header('Location: StudentOptions.php');
	}
	else{
		//no errors
		header('Location: StudentCreateAppointment.php');
	}
}

elseif($studentsChoice == 'createIndividualAppointment'){
	//from the advisor selection dropdown
	$_SESSION['studentsCreateAdvisor'] = $createAdvisor;
	if($_SESSION['studentHasUpcomingAppointment']){
		$_SESSION['showStudentOptionsMessage'] = true;
		$_SESSION['studentOptionsMessage'] = 
		"You cannot create a new appointment because you already have an upcoming appointment.";
		header('Location: StudentOptions.php');
	}
	else{
		header('Location: StudentCreateAppointment.php');
	}
}
elseif($studentsChoice == 'changeToGroupAppointment'){
	$_SESSION['studentsChangeAdvisor'] = 'GROUPAP';
	if(!$_SESSION['studentHasUpcomingAppointment']){
		$_SESSION['showStudentOptionsMessage'] = true;
		$_SESSION['studentOptionsMessage'] = 
		"You have no upcoming appointment to change.";
		header('Location: StudentOptions.php');
	}
	else if($_SESSION['studentHasPastAppointment']){
		$_SESSION['showStudentOptionsMessage'] = true;
		$_SESSION['studentOptionsMessage'] = 
		"If you have had a past appointment, you can only change to an individual appointment.";
		header('Location: StudentOptions.php');
	}
	else if($_SESSION['upcomingWithinDay']){
		$_SESSION['showStudentOptionsMessage'] = true;
		$_SESSION['studentOptionsMessage'] = 
		"You cannot change your appointment because it is 
		 24 hours.";
		header('Location: StudentOptions.php');
	}
	else{
		header('Location: StudentChangeAppointment.php');
	}
}
elseif($studentsChoice == 'changeToIndividualAppointment'){
	//from the advisor selection dropdown
	$_SESSION['studentsChangeAdvisor'] = $changeAdvisor;
	if(!$_SESSION['studentHasUpcomingAppointment']){
		$_SESSION['showStudentOptionsMessage'] = true;
		$_SESSION['studentOptionsMessage'] = 
		"You have no upcoming appointment to change.";
		header('Location: StudentOptions.php');
	}
	else if($_SESSION['upcomingWithinDay']){
		$_SESSION['showStudentOptionsMessage'] = true;
		$_SESSION['studentOptionsMessage'] = 
		"You cannot change your appointment because it is 
		 within 24 hours.";
		header('Location: StudentOptions.php');
	}
	else{
		header('Location: StudentChangeAppointment.php');
	}
}

elseif($studentsChoice == 'viewAppointment'){
	if(!$_SESSION['studentHasUpcomingAppointment'] && !$_SESSION['studentHasPastAppointment']){
		$_SESSION['showStudentOptionsMessage'] = true;
		$_SESSION['studentOptionsMessage'] = 
		"You have no appointments to view at this time.";
		header('Location: StudentOptions.php');
	}
	else{
		header('Location: StudentViewApts.php');
	}
}

elseif($studentsChoice == 'cancelAppointment'){
	if(!$_SESSION['studentHasUpcomingAppointment']){
		$_SESSION['showStudentOptionsMessage'] = true;
		$_SESSION['studentOptionsMessage'] = 
		"You have no upcoming appointments to cancel at this time.";
		header('Location: StudentOptions.php');
	}
	else if($_SESSION['upcomingWithinDay']){
		$_SESSION['showStudentOptionsMessage'] = true;
		$_SESSION['studentOptionsMessage'] = 
		"You cannot cancel your appointment because it is 
		 wihin 24 hours.";
		header('Location: StudentOptions.php');
	}
	else{
		header('Location: StudentDeleteAppointment.php');
	}
}
?>
