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
if($_SESSION['lastPage'] != "AdvisorOptions.php" && $_SESSION['lastPage'] != "AdvisorViewApts.php"){
	echo "Something went wrong!<br>";
}
else{

	$advisors = $_SESSION['advisors'];
	// $employeeId becomes whatever the Session variable of employeeId holds
	
	if($_SESSION['lastPage'] == 'AdvisorViewApts.php'){
		$advisorId = $_POST['sel_advisorView'];
	}
	else{
		$advisorId = $_SESSION['advisorId'];
	}

	//$advisorId = $_SESSION['advisorView'];
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

	
	$numOccurrences = array();
	$lastRowDateTime = 0;
	// how many times an appointment occurs
	$count = 1;
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
				$time = $row['dateTime'];
				// Push the date and time onto $upcomingApts array
				array_push($upcomingApts, $row['dateTime']);
				// Upcoming array info
				array_push($upcomingRowArray, $row);
				array_push($advisorNameArray, $row['advisorId']);

				// another occurrence
				if($lastRowDateTime == $row['dateTime']){
					// account for the new occurrence
					$count++;
					$numOccurrences[$time] = $count;				
				}
				
				else{
					$numOccurrences[$time] = 1;
					// now this dateTime has only one occurrence
					$count = 1;
										
				}
				$lastRowDateTime = $row['dateTime'];
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

	////////////////////////Nat's additions//////////////////
	$today = time();
	$postedDate = $_POST['sel_startDate'];
	if($_SESSION['lastPage'] == 'AdvisorOptionHeaders.php'){
		
		if(date('l')=='Sunday' || date('l')== 'Saturday'){
			
			// starts today at 8am
			$startDate = strtotime("Monday");
			$startDate = strtotime("8 am", $startDate);
			$startDateTime = date('g:i', $startDate);
		}
		else{
			$startDate = strtotime("8am", $today);
			
		}
	}
	//from this page
	else{
		$startDate = strtotime("8am", strtotime($postedDate));
		$startDateTime = date('g:i', $startDate);
	}

	//the advisor
	echo "Advisor:<select name='sel_advisorView'>";
	foreach($advisors as $advisorsId=>$advisorName){
		if($advisorName != 'Group Advising'){
			echo"<option value = '$advisorsId'";
			if($advisorId == $advisorsId){
				echo "selected";
			}
			echo '>';
		}
		echo "$advisorName</option>";
	}
	echo "</select>";


	//form for choosing a time/////////	
	//this should be in sql format

	///TEMPORARILY SETTING THE SESSION VARS HERE FOR START AND END////

	if(!$_SESSION['demo']){

		$counterDay = '2015-03-02 08:00:00';
		$counterEndDay = '2015-05-01 08:00:00';

	}
	else{
		$counterDay = date('Y-m-d H:i:s', strtotime("-2 weeks", time()));
		$counterEndDay = date('Y-m-d H:i:s', strtotime("+2 weeks", time()));
	}
	
	echo"<form action = 'AdvisorViewApts.php' method='post'>";
	echo"Day:<select name='sel_startDate'>";
	while($counterDay < $counterEndDay){
		$userFormatCounterDay = date("l, m/d/Y", strtotime($counterDay));
		echo"<option value='$counterDay'";
			//if same day in the select box as the start day
			if(date('Y-m-d', strtotime($counterDay)) == date('Y-m-d', $startDate)){
				echo "selected";
			}
		echo">$userFormatCounterDay</option>";
			
		if(date('l', strtotime($counterDay)) == "Friday"){
			$counterDay = date('Y-m-d 08:00:00',strtotime("+3 days", strtotime($counterDay)));
		}
		else{
			$counterDay = date('Y-m-d 08:00:00',strtotime("+1 days", strtotime($counterDay)));
		}
	}
	echo "</select><br>";
	echo "<input type='submit'value='View Schedule'>";
	echo "</form><br>";

	//no longer looping, just viewing a single day
	//while($startDate < $endDate){
	
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
			// comparable date that $numOccurrences array can use
			$comparableDateTime = date('Y-m-d H:i:s', strtotime($sqlFormatTime));
			
			$numAppointments = $numOccurrences[$comparableDateTime];
			//echo"comparable date time = $comparableDateTime <br> num appointments = $numAppointments <br>";
			// appointment date and time
			$userFormatAptDateTime = date('l, m/d/Y, g:i A', strtotime($sqlFormatTime));
		// If the time is the same as the appointment, put it in the table
		if($userFormatDateTime == $userFormatAptDateTime && $numAppointments > 1){
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

		elseif($userFormatDateTime == $userFormatAptDateTime && $advisorNameArray[$j] != 'GROUPAP'){
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
		} // end elseif


		elseif($userFormatDateTime == $userFormatAptDateTime){

			//do some sql in here for advising_availabilities to get the major
			$sqlDateTime = date('Y-m-d H:i:s', $startDate);
			$sql = "SELECT * FROM `Advising_Availability2` WHERE `advisorId` = 'GROUPAP'
					AND `dateTime` = '$sqlDateTime'";
			$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
			$row = mysql_fetch_assoc($rs);
			
			$studentMajor = $row['major'];
	
			//$studentMajor = $upcomingStudentInfoArray[$j]['major'];
			echo "<tr>";	
				echo "<td>$userFormatTime</td>";
				echo "<td>Group Appointment</td>";
				echo "<td></td>";
				echo "<td>$studentMajor</td>";
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
	
	//$startDate = strtotime("8am", $startDate);
	//$startDate = strtotime("+1 day", $startDate);
	// update values
	//$startDateTime = date('g:i', $startDate);
	//$userFormatDateTime = date('l, m/d/Y, g:i A', $startDate);
	echo"</table>";
	echo "<br>";
	//} // end while $startDate < $endDate
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
