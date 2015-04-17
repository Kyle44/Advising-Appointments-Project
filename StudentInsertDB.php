// File: StudentInsertDB.php
// Author: Kyle Fritz
// Date Created: 4/17/2015
// Last Modified: 4/17/2015
// Description: This page inserts information into Student_Info2 and Advising_Appointments2.  This will only come up if the last
// page was 'StudentAreYouSure.php' and was created.

<?php
session_start();
include('Proj2Head.html');
include('../CommonMethods.php');
// Make sure we're coming from the right page
if($_SESSION['lastPage'] != "StudentAreYouSure.php"){
	echo "Something went wrong!<br>";
}

else{

	$debug = false;
	$COMMON = new Common($debug);
	// Insert all student data into Student_Info2
	$sql = "INSERT INTO `Student_Info2` (`studentId`, `fName`, `lName`, `major`, `studentEmail`) VALUES ($_SESSION['studentId'], $_SESSION['fName'], $_SESSION['lName'], $_SESSION['major'], $_SESSION['studentEmail']) ON DUPLICATE KEY UPDATE `studentId` = `studentId`;
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);


	// Insert info into Advising_Appointments2
	$sql = "INSERT INTO `Advising_Appointments2` (`studentId`, `advisorId`, `dateTime`) VALUES ($_SESSION['studentId'], $_SESSION['advisorId'], $_SESSION['StudentCreateAptDateTime'])";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);


	
}

header('Location: index.php');


// Make last page equal this page.
$_SESSION['lastPage'] = "StudentInsertDB.php";
include('Proj2Tail.html');

?>
