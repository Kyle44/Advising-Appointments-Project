<?php
/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Last Modified: 5/1/15
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
	// Select the ENTIRE ROW for appointments from the database Advising_Appointments2 where the advisor's Id occurs or where Group Appointments occur
	$sql = "SELECT * FROM `Advising_Appointments2` WHERE `advisorId` = '$advisorId' OR `advisorId` = 'GROUPAP'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	
	// Not sure if this works, but I believe this will give me the date and time of right now. Changed h:i:s to H:i:s.
	$dateAndTime = date('Y-m-d H:i:s');
	$today = date('l, m/d/Y, g:i A');
	
	// Create two arrays, one for past appointments (ones that have already occurred) and one for upcoming appointments.
	$pastApts = array();
	$upcomingApts = array();
	// Used later
	$pastRowArray = array();
	$upcomingRowArray = array();
	// Keep track of whether individual or group appointment
	$advisorNameArray = array();

	
	// changed from mysql_fetch_row($rs) to mysql_fetch_assoc($rs).  Not sure how to use row['dateTime'] for every element.
	$count = 0;
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
				array_push($advisorNameArray, $row['advisorId']);
				$count++;
			} // end else statement
	
	} // end of while loop
	
	$advisorNameArrayLen = count($advisorNameArray);
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
	$today = time();
	// starts today at 8am
	$startDate = strtotime("8am", $today);
	$endDate = strtotime("+14 days", $today);
	$startDateTime = date('g:i', $startDate);
	
	while($startDate < $endDate){
	
	$dow = date("l", $startDate);
	$userFormatDateTime = date('l, m/d/Y, g:i A', $startDate);
	$userFormatDate = date('l, m/d/Y', $startDate);
	$userFormatTime = date('g:i A', $startDate);
	if($dow != "Saturday" && $dow != "Sunday"){
		// create the table 
		?>
		<table border = "3">
		<!--caption defined right after table tag-->
		<!--easy way to get the caption (or any html tag) to include a php variable below:-->
		<?php 
		echo"<caption>$userFormatDate</caption>";
		?>
		<tr>
			<th>Time</th>
			<th>Student</th>
			<th>Email</th>
			<th>Major</th>
			<th>Student Id</th>
		<tr>
		

		<?php	 
		// for loop for times of the day
		for($i = 0; $i < 15; $i++){
			if($startDateTime == "1:30"){
				echo "<tr>";	
					echo "<td>$userFormatTime</td>";
					echo "<td>Lunch Break</td>";
					echo "<td></td>";
					echo "<td></td>";
					echo "<td></td>";
				echo"</tr>";
				$startDate = strtotime("+1 hour", $startDate);
				// update values
				$startDateTime = date('g:i', $startDate);
				$userFormatDateTime = date('l, m/d/Y, g:i A', $startDate);
				$userFormatDate = date('l, m/d/Y', $startDate);
				$userFormatTime = date('g:i A', $startDate);
				// go to 2:30 group appointment now
				continue;
			} // end if
		// check every appointment for every time
		for($j = -1; $j < $upcomingAptsLen; $j++){
	
			$sqlFormatTime = $upcomingApts[$j];
			// appointment date and time
			$userFormatAptDateTime = date('l, m/d/Y, g:i A', strtotime($sqlFormatTime));
			// If the time is the same as the appointment, put it in the table
		if($userFormatDateTime == $userFormatAptDateTime && $advisorNameArray[$j] != 'GROUPAP'){
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
			// if found, then break out of this for loop
			break;
		} // end if

		elseif($userFormatDateTime == $userFormatAptDateTime){
			$studentMajor = $upcomingStudentInfoArray[$j]['major'];
			echo "<tr>";	
				echo "<td>$userFormatTime</td>";
				echo "<td>Group Appointment</td>";
				echo "<td>$studentMajor</td>";
				echo "<td></td>";
				echo "<td></td>";
			echo"</tr>";
			// break for loop if this occurs
			break;	
		} // end elseif
		
		// if none were found, put in blanks
		elseif($j == $upcomingAptsLen - 1){
			echo "<tr>";
				echo "<td>$userFormatTime</td>";
				echo "<td></td>";
				echo "<td></td>";
				echo "<td></td>";
				echo "<td></td>";
			echo"</tr>";
		} // end elseif
		} // end for loop


		// increase by 30 minutes
		$startDate = strtotime("+30 minutes", $startDate);
		// update values
		$startDateTime = date('g:i', $startDate);
		$userFormatDateTime = date('l, m/d/Y, g:i A', $startDate);
		$userFormatDate = date('l, m/d/Y', $startDate);
		$userFormatTime = date('g:i A', $startDate);
		} // for loop for the times of the day
		
					
	} // end if
	// next day
	
	$startDate = strtotime("8am", $startDate);
	$startDate = strtotime("+1 day", $startDate);
	// update values
	$startDateTime = date('g:i', $startDate);
	$userFormatDateTime = date('l, m/d/Y, g:i A', $startDate);
	echo"</table>";
	echo "<br>";
	} // end while $startDate < $endDate
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
