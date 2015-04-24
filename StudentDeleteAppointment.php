<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: StudentCreateAppointment.php
File Description: In this file, a student sees the appointment they are signed up for (it is only possible for them to be signed up for
one), and deletes it. 
*/

session_start();
include ('Proj2Head.html');
include('../CommonMethods.php');


$dateAndTime = date('Y-m-d H:i:s');
$fName = $_SESSION['fName'];
$studentId = $_SESSION['studentId'];

echo "<div class='form-div'>";


$debug = false;
$COMMON = new Common($debug);
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
$advisorId = $upcomingAptRow['advisorId'];
//echo"$advisorId<br>";
$sql = "SELECT * FROM `Advisor_Info2` WHERE `employeeId` = '$advisorId'";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
// advrow is all of the info for this advisor
$advRow = mysql_fetch_assoc($rs);
$sqlFormatTime = $upcomingAptRow['dateTime'];
$_SESSION['studentDeleteTime'] = $sqlFormatTime;////////////////////unset this if go back
$userFormatDate = date('l, m/d/Y', strtotime($sqlFormatTime));
$userFormatTime = date('g:i a', strtotime($sqlFormatTime));
$advisorName = $advRow['fName']." ".$advRow['lName'];

//OUTPUT
if($advisorName == 'Group Advising'){
	echo"$fName, you are currently scheduled for a group advising appointment "; 
}
else{
	echo"$fName, you are currently scheduled for an individual advising appointment with 
		$advisorName ";
}
echo"on $userFormatDate at $userFormatTime.<br>";
echo"Would you would like to cancel this appointment?<br><br>";
?>
<form action = 'StudentDeleteDB.php'>
	<div class="button"><input type = 'submit' value='Cancel Appointment'></div>
</form>

<form action='StudentOptions.php'>
	<div class="button"><input type = 'submit' value='Go Back'></div>
</form>

</div>

<?php
$_SESSION['lastPage'] = 'StudentDeleteAppointment.php';
include('Proj2Tail.html');
?>
