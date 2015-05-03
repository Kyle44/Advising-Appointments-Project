<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: StudentChangeDB.php
Description: This page inserts information into Student_Info2 and Advising_Appointments2.  This will only come up if the last
page was 'StudentAreYouSure.php' and was created.
*/

session_start();
include('../CommonMethods.php');

//info to delete
$upcomingAdvisorId = $_SESSION['upcomingAdvisorId'];
$upcomingDateTime = $_SESSION['upcomingDateTime'];
$studentId = $_SESSION['studentId'];

//info to insert
$changeAdvisorId = $_SESSION['studentsChangeAdvisor'];//advisor id
$intDate = $_POST['date'];
$intTime = $_POST[$intDate];
$sqlChangeDateTime = date('Y-m-d', $intDate)." ".date('H:i:s', $intTime);

$debug = false;
	$COMMON = new Common($debug);

//delete info
$sql = "DELETE FROM `Advising_Appointments2` 
		WHERE `studentId` = '$studentId' 
		AND `dateTime` = '$upcomingDateTime'";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

//insert info
$sql = "INSERT INTO `Advising_Appointments2` (`studentId`, `advisorId`, `dateTime`) 
			VALUES ('$studentId', '$changeAdvisorId', '$sqlChangeDateTime')";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

$_SESSION['showStudentOptionsMessage'] = true;
$_SESSION['studentOptionsMessage'] = "You have successfully changed your appointment.";
// Make last page equal this page.
$_SESSION['lastPage'] = "StudentChangeDB.php";

//header should come after setting last page, or else it won't reach that part of the code
header('Location: ValidateStudentSignin.php');
?>
