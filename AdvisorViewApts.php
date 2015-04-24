<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Last Modified: 4/24/15
Class: CMSC331
Project: Project 2
File: AdvisorViewApts.php
File Description: This page will only come up if the last page was AdvisorOptions.php 
and "View Created Appointment" was selected.
*/

session_start();
include('Proj2Head.html');
include('CommonMethods.php');

$fName = $_SESSION['fName'];

// Make sure we're coming from the right page
if($_SESSION['lastPage'] != "AdvisorOptions.php"){
	echo "Something went wrong!<br>";
}

else{
	// $employeeId becomes whatever the Session variable of employeeId holds
	$advisorId = $_SESSION['advisorView'];

	// $debug to true would print out the query whenever one was executed, false wouldn't
	$debug = false;
	$COMMON = new Common($debug);
	// Select the ENTIRE ROW for appointments from the database Advising_Appointments2 where the advisor's Id occurs
	$sql = "SELECT * FROM `Advising_Appointments2` WHERE `advisorId` = '$advisorId'";
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
				// Push the date and time onto $pastApts array
				array_push($pastApts, $row['dateTime']);
				// Each row here is sent to $pastRowArray
				array_push($pastRowArray, $row);
			} // end if statement
			
			else{
				// Push the date and time onto $upcomingApts array
				array_push($upcomingApts, $row['dateTime']);
				// Upcoming array info
				array_push($upcomingRowArray, $row);	
			} // end else statement
	
	} // end of while loop
	
		

	$upcomingAptsLen = count($upcomingApts);
	//echo"$upcomingAptsLen <br>";
	
	
	// Output for times of Past/Upcoming Appointments
	
	
	// All data for students in upcoming appointments
	$upcomingStudentInfoArray = array();

	
	foreach($upcomingRowArray as $element){
		$studentId = $element['studentId'];

		$sql = "SELECT * FROM `Student_Info2` WHERE `studentId` = '$studentId'";
		$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
		// row is all of the info for this student
		$row = mysql_fetch_assoc($rs);
		// Every row is pushed onto the studentInfoArray.
		array_push($upcomingStudentInfoArray, $row);
	}


	if($upcomingAptsLen > 0){	 
		?>
		<table border = "3">
		<!--caption defined right after table tag-->
		<caption> Upcoming Appointments </caption>
		<tr>
			<th>Time</th>
			<th>Student</th>
			<th>Email</th>
			<th>Major</th>
			<th>Student Id</th>
		<tr>

		<?php
	

	 
		echo "Upcoming Appointments:<br>";
		for($j = 0; $j < $upcomingAptsLen; $j++){
			$sqlFormatTime = $upcomingApts[$j];
			$userFormatTime = date('l, m/d/Y, g:i A', strtotime($sqlFormatTime));
			echo $userFormatTime;
			
			$studentfName = $upcomingStudentInfoArray[$j]['fName'];
			$studentlName = $upcomingStudentInfoArray[$j]['lName'];
			$studentEmail = $upcomingStudentInfoArray[$j]['studentEmail'];
			$studentMajor = $upcomingStudentInfoArray[$j]['major'];
			$studentId = $upcomingStudentInfoArray[$j]['studentId'];



			echo "<tr>";	
				echo "<td>$userFormatTime</td>";
				echo "<td>$studentfName $studentlName</td>";
				echo "<td>$studentEmail</td>";
				echo "<td>$studentMajor</td>";
				echo "<td>$studentId</td>";
			echo"</tr>";
		} // end for loop
		echo"</table>";
	}
	echo "<br>";
	
} // End of big else statement

?>


	<!--  THE BUTTON "Go Back"
	<!-- action to go to AdvisorOptions.php.  Name means AdvisorViewApts.php (this page) to AdvisorOptions.php  -->
	<form action='AdvisorOptions.php' name='AVAtoAOptions'>
	<!--Go Back button-->
	<input type='submit' value='Go Back'>
	<!-- End of form  -->
	</form>


<?php
	// Make last page equal this page.
  	$_SESSION['lastPage'] = "AdvisorViewApts.php";
	include('Proj2Tail.html');
?>
