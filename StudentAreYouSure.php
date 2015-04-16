<?php
session_start();
/*
Name: Nathaniel Baylon
Date:03/21/2015
Class: CMSC331
Project:Advisor Time Selection
File: StudentOptions.php
File Description: This file lets the user check to make sure the time they chose is
the one they really want. Previous page is StudentCreateAppointment.php, next page is
StudentCreateAppointment.php
*/

include('Proj2Head.html');
include('../CommonMethods.php');

$advisorId = $_SESSION['studentsAdvisor'];
$advisorName = $_SESSION['advisors'][$advisorId];
$fName = $_SESSION['fName'];

if($_SESSION['lastPage'] == 'StudentCreateAppointment.php'){
	$intDate = $_POST['date'];
	$intTime = $_POST[$intDate];
	//var_dump($_POST);

	$userFormatDate = date('l, m/d/Y', $intDate);
	$userFormatTime = date('g:i a', $intTime);
	$sqlDateTime = date('Y-m-d', $intDate)." ".date('H:i:s', $intTime);
	

	//Kyle, this is the session variable to insert into the db. 
	//There may be issues with putting $_SESSION['StudentCreateAptDateTime'] in
	//single quotes in the query because of the 'StudentCreateAptDateTime', so 
	//make sure you put it in another variable first. 
	$_SESSION['StudentCreateAptDateTime'] = $sqlDateTime;

	if($advisorId == 'GROUPAP'){
		echo"$fName, create a group advising appointment ";
	}
	else{
		echo"$fName, create an individual advising appointment with $advisorName ";
	}
	echo "on $userFormatDate at $userFormatTime?";
?>
<form action='StudentInsertDB' name = 'insert'>
	<input type = 'submit' value = 'Create Appointment'>
</form>
<form action='StudentCreateAppointment.php' name ='go back'>
	<input type = 'submit' value = 'Go Back'>
</form>


<?php
}
//other else ifs
$_SESSION['lastPage'] = 'StudentAreYouSure.php';
?>
