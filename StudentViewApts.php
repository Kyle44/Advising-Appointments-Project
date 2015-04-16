// File: StudentViewApts.php
// Author: KyleFritz
// Date Created: 4/11/2015
// Last Modified: 4/15/2015
// Description: This page will only come up if the last page was StudentOptions.php 
//   and "View Created Appointment" was created.

<?php
session_start();

include('Proj2Head.html');
include('../CommonMethods.php');



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
	$sql = "SELECT * FROM `Advising_Appointments2` WHERE `studentId` LIKE '$studentId'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	


	// set the default timezone to use.
	date_default_timezone_set('EST');
	// Not sure if this works, but I believe this will give me the date and time of right now. Changed h:i:s to H:i:s.
	$dateAndTime = date('Y-m-d H:i:s');

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

		$sql = "SELECT * FROM `Advising_Info2` WHERE `employeeId` LIKE '$element['advisorId']'";
		$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
		// row is all of the info for this advisor
		$row = mysql_fetch_assoc($rs);
		// Every row is pushed onto the advisorInfoArray.
		push_array($pastAdvisorInfoArray, $row);
	}

	


	// Count length of both arrays so for loops later can work
	$pastAptsLen = count($pastApts);
	$upcomingAptsLen = count($upcomingApts);
	

	// Output for times of Past/Upcoming Appointments
	echo "Past Appointments: <br>";
	// For loop for $pastApts

	///////////////////////////******************************///////////////
	foreach($pastAdvisorInfoArray as $row)

	for($i = 0; $i < $pastAptsLen; $i++){
		echo $pastApts[$i];
		echo "<br>";
		// 
		echo $pastAdvisorInfoArray[$j];
	} // end for loop
	


	// All data for advisors in upcoming appointments
	$upcomingAdvisorInfoArray = array();

	
	foreach($upcomingRowArray as $element){

		$sql = "SELECT * FROM `Advising_Info2` WHERE `employeeId` LIKE '$element['advisorId']'";
		$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
		// row is all of the info for this advisor
		$row = mysql_fetch_assoc($rs);
		// Every row is pushed onto the advisorInfoArray.
		push_array($upcomingAdvisorInfoArray, $row);
	}




	echo "Upcoming Appointments<br>";
	for($j = 0; $j < $upcomingAptsLen; $j++){
		echo $upcomingApts[$j];
		echo "<br>";
		echo $upcomingAdvisorInfoArray[$j];
	} // end for loop
	
} // End of big else statement

?>



	<!--  THE BUTTONS "Go Back" and "Done".  -->

	<!-- Post method to go to StudentOptions.php.  Name means StudentViewApts.php (this page) to StudentOptions.php  -->
	<form action='StudentOptions.php' method='post' name='SVAtoSOptions'>
	<!-- Go Back button -->
	<input type='Go Back'>
	<!-- End of form  -->
	</form>

	<!-- Post method to go to index.php.  Name means StudentViewApts.php (this page) to index.php  -->
	<form action='index.php' method='post' name='SVAtoINDEX'>
	<!-- Done button  -->
	<input type='Done'>
	<!-- End of form -->
	</form>



<?php
	// Make last page equal this page.
  	$_SESSION['lastPage'] = "StudentViewApts.php";
	include('Proj2Tail.html');
?>
