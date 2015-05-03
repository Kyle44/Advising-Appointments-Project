<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: AdvisorChangeDB.php
Description: This page inserts information into Student_Info2 and Advising_Appointments2.
*/

session_start();
include('../CommonMethods.php');

//info to delete
$upcomingAdvisorId = $_SESSION['upcomingAdvisorId'];
$upcomingDateTime = $_SESSION['upcomingDateTime'];
$studentId = $_SESSION['rescheduleID'];

//info to insert
$advisorReschedule = $_SESSION['advisorReschedule'];//advisor id
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
			VALUES ('$studentId', '$advisorReschedule', '$sqlChangeDateTime')";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

$_SESSION['showAdvisorOptionsMessage'] = true;
$_SESSION['advisorOptionsMessage'] = "You have successfully changed an appointment.";
// Make last page equal this page.
$_SESSION['lastPage'] = "AdvisorChangeDB.php";

//header should come after setting last page, or else it won't reach that part of the code
header('Location: AdvisorOptions.php');
?>
