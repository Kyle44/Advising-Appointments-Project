<?php
/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 5/8/2015
Last Modified: 5/8/15
Class: CMSC331
Project: Project 2
File: AdvisorSearchText.php
File Description: This is the page that will come up only after a search on the AdvisorOptions page was made.
*/
session_start();
include('Proj2Head.html');
include('CommonMethods.php');

$advisorId = $_SESSION['advisorId'];
$advisors = $_SESSION['advisors'];


?>
<div class="form-title">Search<br></div>
<?php


// whatever the session variable for the search was, put it here
$searchStudent = $_SESSION['searchStudentID'];

$debug = false;
$COMMON = new Common($debug);
$sql = "SELECT * FROM `Student_Info2` WHERE `studentId` = '$searchStudentID' OR `lname` = '$searchStudentID'";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

$studentArray = array();

while($row = mysql_fetch_assoc($rs)){
	array_push($studentArray, $row);
}



$today = date('Y-m-d H:i:s');
// Create two arrays, one for past appointments (ones that have already occurred) and one for upcoming appointments.
$pastApts = array();
$upcomingApts = array();
$today = date('Y-m-d H:i:s');

// For loop to get all of the appointments for a student in one place
foreach($studentArray as $element){
	$studentId = $element['studentId'];
	$sql = "SELECT * FROM `Advising_Appointments2` WHERE `studentId` = '$studentId'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	// row is all of the info for this student
	$row = mysql_fetch_assoc($rs);

	foreach($row as $apts){
		// put the rows into the arrays, 
		if($today > $apts['dateTime']){
			array_push($pastApts, $apts);
		}
		else{
			array_push($upcomingApts, $apts);
		}
	} // end for
} // end big for



// Prints all of the appointments for each student
foreach($studentArray as $element){
	echo "Upcoming Appointments: <br>";
	foreach($upcomingApts as $apts){
		if($element['studentId'] == $apts['studentId']){
			echo"$apts['dateTime']<br>";
		} // end if
	} // end for

	echo "Past Appointments: <br>";
	foreach($pastApts as $apts){
		if($element['studentId'] == $apts['studentId']){
			echo"$apts['dateTime']<br>";
		} // end if
	} // end for
} // end big for




<?php
$_SESSION['lastPage'] = "AdvisorSearchText.php";
include('Proj2Tail.html');
?>
