<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: AdvisorCancelAppointment.php
File Description: In this file, an advisor sees the appointment the student is signed up for (it is only possible for them to be signed up for
one), and deletes it. 
*/

session_start();
include ('Proj2Head.html');
include('../CommonMethods.php');

//instantiate common
$debug = false;
$COMMON = new Common($debug);

//student's info

$studentId = $_SESSION['cancelID'];

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



$dateAndTime = date('Y-m-d H:i:s');
//$fName = $_SESSION['fName'];
//$studentId = $_SESSION['studentId'];

echo "<div class='form-div'>";



// Select the ENTIRE ROW for appointments from the database Advising_Appointments2 where the student's Id occurs
$sql = "SELECT * FROM `Advising_Appointments2` WHERE `studentId` = '$studentId'";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

//there can only be one upcoming appointment

while($row = mysql_fetch_assoc($rs)){
	//echo "aaa <br>";
	if($row['dateTime'] > $dateAndTime){
		$upcomingAptRow = $row;
		//var_dump($row);
	}
}

$advRow = array();
//var_dump($upcomingAptRow);
$advisorCancelId = $upcomingAptRow['advisorId'];
//echo "<br>$advisorCancelId<br>";
//echo"$advisorId<br>";
$sql = "SELECT * FROM `Advisor_Info2` WHERE `employeeId` = '$advisorCancelId'";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
// advrow is all of the info for this advisor
$advRow = mysql_fetch_assoc($rs);
$sqlFormatTime = $upcomingAptRow['dateTime'];
$_SESSION['advisorDeleteTime'] = $sqlFormatTime;
$userFormatDate = date('l, m/d/Y', strtotime($sqlFormatTime));
$userFormatTime = date('g:i a', strtotime($sqlFormatTime));
$advisorCancelName = $advRow['fName']." ".$advRow['lName'];

//OUTPUT
if($advisorCancelName == 'Group Advising'){
	echo"$studentfName $studentlName is currently scheduled for a group advising appointment "; 
}
else{
	echo"$studentfName $studentlName is currently scheduled for an individual advising appointment with 
		$advisorCancelName ";
}
echo"on $userFormatDate at $userFormatTime. You can contact $studentfName $studentlName at $studentEmail.<br><br>";
echo"Would you would like to cancel this appointment?<br><br>";

//check for reasons the advisor might not want to create an appointment
if($_SESSION['upcomingWithinDay']){
	echo "Please consider the following information about this student before cancelling this appointment:<br><br>";
	echo "The appointment with this student is within 24 hours.";
}
?>
<form action = 'AdvisorDeleteDB.php'>
	<div class="button"><input type = 'submit' value='Cancel Appointment'></div>
</form>

<form action='AdvisorOptions.php'>
	<div class="button"><input type = 'submit' value='Go Back'></div>
</form>

</div>

<?php
$_SESSION['lastPage'] = 'StudentDeleteAppointment.php';
include('Proj2Tail.html');
?>
