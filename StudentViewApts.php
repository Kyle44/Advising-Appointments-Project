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


	// changed from mysql_fetch_row($rs) to mysql_fetch_assoc($rs).  Not sure how to use row['dateTime'] for every element.
	while($row = mysql_fetch_assoc($rs)){

		foreach ($row as $element){

			// Not sure how to do this exactly, but what I want is: if dateTime variable is before now, then it is a past appointment.
			//   I believe the dateTime variable is now in $element, but I don't know if I can compare it with $dateAndTime
			if($element < $dateAndTime){
				// Trying to push the date and time onto $pastApts array
				array_push($pastApts, $element);
			} // end if statement
			
			else{
				// Trying to push the date and time onto $upcomingApts array
				array_push($upcomingApts, $element);
			} // end else statement
		} // end for loop
	} // end of while loop
	

	// Count length of both arrays so for loops later can work
	$pastAptsLen = count($pastApts);
	$upcomingAptsLen = count($upcomingApts);

	// Output for times of Past/Upcoming Appointments
	echo "Past Appointments: <br>";
	// For loop for $pastApts
	for($i = 0; $i < $pastAptsLen; $x++){
		echo $pastApts[$x];
		echo "<br>";
	} // end for loop
	
	echo "Upcoming Appointments<br>";
	for($j = 0; $j < $upcomingAptsLen; $x++){
		echo $upcomingApts[$x];
		echo "<br>";
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
