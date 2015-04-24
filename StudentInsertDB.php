<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: StudentInsertDB.php
File Description: This page inserts information into Student_Info2 and Advising_Appointments2.  This will only come up if the last
page was 'StudentAreYouSure.php' and was created.
*/

//make sure header comments come after <?php, or else they show up as text on the page

session_start();
include('../CommonMethods.php');
// Make sure we're coming from the right page
if($_SESSION['lastPage'] != "StudentCreateAppointment.php"){
	echo "Something went wrong!<br>";
}

else{
	$studentId = $_SESSION['studentId'];
	$fName = $_SESSION['fName'];
	$lName = $_SESSION['lName'];
	$major = $_SESSION['major'];
	$studentEmail = $_SESSION['studentEmail'];
	

	$intDate = $_POST['date'];
	$intTime = $_POST[$intDate];
	$sqlDateTime = date('Y-m-d', $intDate)." ".date('H:i:s', $intTime);
	$advisorId = $_SESSION['studentsCreateAdvisor'];
	//$dateTime = $_SESSION['studentCreateDateTime'];
	
	$debug = false;
	$COMMON = new Common($debug);
	// Insert all student data into Student_Info2
	//using session variables in the query may cause errors because of the quotes, so I put them into other
	//variables 
	$sql = "INSERT INTO `Student_Info2` (`studentId`, `fName`, `lName`, `major`, `studentEmail`) 
			VALUES ('$studentId', '$fName', '$lName', '$major', '$studentEmail') 
			ON DUPLICATE KEY UPDATE `studentId` = `studentId`";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

	//echo "$dateTime<br>";
	// Insert info into Advising_Appointments2
	//make sure to put the values to be inserted in single quotes
	$sql = "INSERT INTO `Advising_Appointments2` (`studentId`, `advisorId`, `dateTime`) 
			VALUES ('$studentId', '$advisorId', '$sqlDateTime')";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);


	
}

$_SESSION['showStudentOptionsMessage'] = true;
$_SESSION['studentOptionsMessage'] = "You have successfully created an appointment.";
// Make last page equal this page.
$_SESSION['lastPage'] = "StudentInsertDB.php";

//header should come after setting last page, or else it won't reach that part of the code
header('Location: ValidateStudentSignin.php');

?>
