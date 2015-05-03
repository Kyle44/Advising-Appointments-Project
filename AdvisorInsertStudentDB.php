<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: AdvisorInsertStudentDB.php
File Description: Insert Student into db after creating appointment
*/

session_start();
include('../CommonMethods.php');


//instantiating COMMON
$debug = false;
$COMMON = new Common($debug);

//student's info

//if existing student
if(strlen($_SESSION['scheduleExistingID'])!=0){
	$studentId = $_SESSION['scheduleExistingID'];
	$sql = "SELECT * FROM Student_Info2 
		WHERE `studentId` = '$studentId'";
	$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
	$row = mysql_fetch_assoc($rs);
	//pull student's info from row
	
	$studentfName = $row['fName'];
	$studentlName = $row['lName'];
	$studentMajor = $row['Major'];
	$studentEmail = $row['studentEmail'];
	$studentId = $row['studentId'];
}
//new student
else{
	$studentfName = $_SESSION['scheduleNewfName'];
	$studentlName = $_SESSION['scheduleNewlName'];
	$studentMajor = $_SESSION['scheduleNewMajor'];
	$studentEmail = $_SESSION['scheduleNewEmail'];
	$studentId = $_SESSION['scheduleNewID'];
}

$intDate = $_POST['date'];
$intTime = $_POST[$intDate];
$sqlDateTime = date('Y-m-d', $intDate)." ".date('H:i:s', $intTime);
$advisorId = $_SESSION['advisorSchedule'];


//insert all student data into Student_Info2
$sql = "INSERT INTO `Student_Info2` (`studentId`, `fName`, `lName`, `major`, `studentEmail`) 
		VALUES ('$studentId', '$studentfName', '$studentlName', '$studentMajor', '$studentEmail') 
		ON DUPLICATE KEY UPDATE `studentId` = `studentId`";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

$_SESSION['lastPage'] = 'AdvisorInsertStudentDB.php';

//insert info into Advising_Appointments2
$sql = "INSERT INTO `Advising_Appointments2` (`studentId`, `advisorId`, `dateTime`) 
		VALUES ('$studentId', '$advisorId', '$sqlDateTime')";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);


$_SESSION['lastPage'] = 'AdvisorInsertStudentDB.php';

$_SESSION['showAdvisorOptionsMessage'] = true;
$_SESSION['advisorOptionsMessage'] = 
	'You have successfully scheduled an appointment.';		
		
header('Location: AdvisorOptions.php');
?>
