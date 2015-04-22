<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: StudentViewApts.php
File Description: This page will only come up if the last page was StudentOptions.php 
and "View Created Appointment" was created.
*/

session_start();
include('Proj2Head.html');
include('CommonMethods.php');

$fName = $_SESSION['fName'];

echo "<div class='form-div'>";

// Make sure we're coming from the right page
if($_SESSION['lastPage'] != "StudentOptions.php"){
	echo "Something went wrong!<br>";
}

else{
	// $studentId becomes whatever the Session variable of studentId holds
	$studentId = $_SESSION['studentId'];

	// $debug to true would print out the query whenever one was executed, false wouldn't
	$debug = false;
	$COMMON = new Common($debug);
	// Select the ENTIRE ROW for appointments from the database Advising_Appointments2 where the student's Id occurs
	$sql = "SELECT * FROM `Advising_Appointments2` WHERE `studentId` = '$studentId'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	


	// set the default timezone to use.
	//date_default_timezone_set('EST');
	// Not sure if this works, but I believe this will give me the date and time of right now. Changed h:i:s to H:i:s.
	$dateAndTime = date('Y-m-d H:i:s');
	$today = date('l, m/d/Y, g:i A');
	
	// Create two arrays, one for past appointments (ones that have already occurred) and one for upcoming appointments.
	$pastApts = array();
	$upcomingApts = array();
	// Used later
	$pastRowArray = array();
	$upcomingRowArray = array();

	

	// changed from mysql_fetch_row($rs) to mysql_fetch_assoc($rs).  Not sure how to use row['dateTime'] for every element.
	while($row = mysql_fetch_assoc($rs)){
			// Array of rows
			


			// if row['dateTime'] is before right now, put this in $pastApts array
			if($row['dateTime'] < $dateAndTime){
				// Trying to push the date and time onto $pastApts array
				array_push($pastApts, $row['dateTime']);
				// Each row here is sent to $pastRowArray
				array_push($pastRowArray, $row);
			} // end if statement
			
			else{
				// Trying to push the date and time onto $upcomingApts array
				array_push($upcomingApts, $row['dateTime']);
				// Upcoming array info
				array_push($upcomingRowArray, $row);	
			} // end else statement
	
	} // end of while loop
	


	// All data for advisors in past appointments
	$pastAdvisorInfoArray = array();


	// for loop for past appointment's Advising info. 
	foreach($pastRowArray as $element){
		$advisorId = $element['advisorId'];
		$sql = "SELECT * FROM `Advisor_Info2` WHERE `employeeId` = '$advisorId'";//changed this line!!!
		$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
		// row is all of the info for this advisor
		$row = mysql_fetch_assoc($rs);
		// Every row is pushed onto the advisorInfoArray.
		array_push($pastAdvisorInfoArray, $row);
	}

	


	// Count length of both arrays so for loops later can work
	$pastAptsLen = count($pastApts);
	$upcomingAptsLen = count($upcomingApts);
	
	
	// Output for times of Past/Upcoming Appointments
	
	echo "$fName, here are the appointmnets you have created this semester. Today is $today<br><br>";
	if($pastAptsLen>0){
		echo "Past Appointments: <br>";
	}
	// For loop for $pastApts

	///////////////////////////******************************///////////////
	foreach($pastAdvisorInfoArray as $row)

	for($i = 0; $i < $pastAptsLen; $i++){
		$sqlFormatTime = $pastApts[$i];
		$studentFormatTime = date('l, m/d/Y, g:i A', strtotime($sqlFormatTime));
		echo $studentFormatTime;
		$advisorfName = $pastAdvisorInfoArray[$i]['fName'];
		$advisorlName = $pastAdvisorInfoArray[$i]['lName'];
		$advisorEmail = $pastAdvisorInfoArray[$i]['advisorEmail'];
		$advisorPhoneNumber = $pastAdvisorInfoArray[$i]['advisorPhoneNumber'];
		$advisorRoomNumber = $pastAdvisorInfoArray[$i]['advisorRoomNumber'];
		echo " ".$advisorfName." ".$advisorlName.": ";
		echo "email: $advisorEmail, ";
		echo "phone: $advisorPhoneNumber, ";
		echo "room: $advisorRoomNumber";
		echo "<br>";
	} // end for loop
	echo "<br>";


	// All data for advisors in upcoming appointments
	$upcomingAdvisorInfoArray = array();

	
	foreach($upcomingRowArray as $element){
		$advisorId = $element['advisorId'];

		$sql = "SELECT * FROM `Advisor_Info2` WHERE `employeeId` = '$advisorId'";//////changed this line!!!
		$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
		// row is all of the info for this advisor
		$row = mysql_fetch_assoc($rs);
		// Every row is pushed onto the advisorInfoArray.
		array_push($upcomingAdvisorInfoArray, $row);
	}



	if($upcomingAptsLen > 0){	 
		echo "Upcoming Appointments:<br>";
	}
	for($j = 0; $j < $upcomingAptsLen; $j++){
		$sqlFormatTime = $upcomingApts[$j];
		$userFormatTime = date('l, m/d/Y, g:i A', strtotime($sqlFormatTime));
		echo $userFormatTime;
		
		$advisorfName = $upcomingAdvisorInfoArray[$j]['fName'];
		$advisorlName = $upcomingAdvisorInfoArray[$j]['lName'];
		$advisorEmail = $upcomingAdvisorInfoArray[$j]['advisorEmail'];
		$advisorPhoneNumber = $upcomingAdvisorInfoArray[$j]['advisorPhoneNumber'];
		$advisorRoomNumber = $upcomingAdvisorInfoArray[$j]['advisorRoomNumber'];
		echo " ".$advisorfName." ".$advisorlName.": ";
		echo "email: $advisorEmail, ";
		echo "phone: $advisorPhoneNumber, ";
		echo "room: $advisorRoomNumber";
		echo "<br>";
	} // end for loop
echo "<br>";
	
} // End of big else statement

?>


	<!--  THE BUTTONS "Go Back" and "Done".  -->
	<!-- action to go to StudentOptions.php.  Name means StudentViewApts.php (this page) to StudentOptions.php  -->
	<form action='StudentOptions.php' name='SVAtoSOptions'>
	<!--Go Back button-->
	<div class="button"><input type='submit' value='Go Back'></div>
	<!-- End of form  -->
	</form>

	<!-- action='index.php to go to index.php.
	Name means StudentViewApts.php (this page) to index.php
	 post method is just for sending input data from forms-->
	
	<!--you need a new form if the button goes to a different place-->
	<form action='index.php' name='SVAtoINDEX'>
	<!--Done button-->
	<form action='StudentOptions.php' name='SVAtoSTUDENTOPTIONS'>
	<div class="button"><input type= 'submit' value='Done'></div>
	<!-- End of form -->
	</form>
</div>
<?php
	// Make last page equal this page.
  	$_SESSION['lastPage'] = "StudentViewApts.php";
	include('Proj2Tail.html');
?>
