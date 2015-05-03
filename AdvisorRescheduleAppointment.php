<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: AdvisorDeleteAppointment.php
File Description: Advisor is displayed times to change the student's 
appointmnet to, and theyselect the time
Last page:AdvisorOptions.php
*/

session_start();
include('Proj2Head.html');
include('../CommonMethods.php');

//instantiate common
$debug = false;
$COMMON = new Common($debug);

//advisor's info
$advisorReschedule = $_SESSION['advisorReschedule'];
$advisorId = $_SESSION['advisorId'];
//echo "$advisorId<br>";
$advisorName = $_SESSION['advisors'][$advisorId];
$advisorRescheduleName = $_SESSION['advisors'][$advisorReschedule];
$advisorfName = $_SESSION['fName'];
//var_dump($_SESSION['studentsAdvisor']);
//echo "$advisorId<br>";



//student's info
$studentId = $_SESSION['rescheduleID'];
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


echo "<div class='form-div'>";
//defining date range the student can select from
//today's day of week
$todayDOW = date(l);


//Now, the advisor should have power to
//Override the student's time constraint 
//echo "$todayDOW";
if($todayDOW != 'Saturday' && $todayDOW != 'Sunday'){
	$startDate = strtotime(date('Y-m-d H:i:s'));
}
else{
	$startDate = strtotime('Monday');
}
	$endDate = strtotime("+9 days", $startDate);

//echo "$startDate";

$sqlFormatStartDate = date("Y-m-d H:i:s",$startDate);
$sqlFormatEndDate = date("Y-m-d H:i:s",$endDate);
//echo "Testing start and end date: $sqlFormatStartDate"."$sqlFormatEndDate<br>";

//get upcoming date and upcoming advisor
//there should only be one appointment
$sql = "SELECT * FROM Advising_Appointments2 WHERE `studentId` = '$studentId'";
$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
while($row = mysql_fetch_assoc($rs)){
	if($row['dateTime'] > $now){
		$upcomingDateTime = $row['dateTime'];
		$upcomingAdvisorId = $row['advisorId'];
		//echo "$upcomingAdvisorId<br>";
		$upcomingAdvisorName = $_SESSION['advisors'][$upcomingAdvisorId];
		//echo"$upcomingAdvisorName<br>";
		$_SESSION['upcomingDateTime'] = $upcomingDateTime;
		$_SESSION['upcomingAdvisorId'] = $upcomingAdvisorId;
	}
	//echo "hi<br>";
}




//get available apts between start/end time with 
if($advisorReschedule == 'GROUPAP'){
	$sql = "SELECT * FROM Advising_Availability2 
			WHERE `advisorId` = '$advisorReschedule' 
			AND `dateTime` BETWEEN '$sqlFormatStartDate' AND '$sqlFormatEndDate'
			AND (`major` = '$major'
				OR `major` = 'All Majors')";
}
else{
$sql = "SELECT * FROM Advising_Availability2 
		WHERE `advisorId` = '$advisorReschedule' 
		AND `dateTime` BETWEEN '$sqlFormatStartDate' AND '$sqlFormatEndDate'";	
}

$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);

//push into array (all of the appointments in Advising_Avialability w/i that
//time range)
$allPotentialAppointmentArray = array();
while($row = mysql_fetch_assoc($rs)){
	array_push($allPotentialAppointmentArray, $row);
}

//numSlots in Advising_Availability2 has number of slots in an appt
//avavailbleAppointmentArray holds times that are available to sign up for, eg,
//number of taken slots does not exceed the number of slots in the appoinmtnet time
$availableAppointmentArray = array();
foreach($allPotentialAppointmentArray as $row){
	$sqlFormatTime = $row["dateTime"];
	$numSlots = $row["numSlots"];
	$advisorId = $row["advisorId"];
	$sql = "SELECT COUNT(*) as totalno 
				FROM `Advising_Appointments2` 
				WHERE `dateTime`='$sqlFormatTime'
 				AND `advisorId` = '$advisorId'";

  	$rs = $COMMON->executeQuery($sql,$SERVER["SCRIPT_NAME"]);
  
 	while($data=mysql_fetch_array($rs)){
   		$numTakenSlots = $data['totalno'];
  	}
	if($numSlots >$numTakenSlots){
		array_push($availableAppointmentArray, $sqlFormatTime);
	}
}

//get a list of all possible days, to display. If no times are available in that day, 
//var_dump($availableAppointmentArray);
//counter is a looping variable that holds a date
//outputArray holds dates and times that will be displayed, with the day as the key
	$outputArray = array();
	$counter = $sqlFormatStartDate;
	//echo "sqlFormatStartDate: $sqlFormatStartDate";
	
	//go up to, but do not include end date (end date is beginning of day after last day 
	//that will show up
	while($counter != $sqlFormatEndDate){
		//userFormatDate: date that will be seen
		$userFormatDate = date("l, m/d/Y",strtotime($counter));
		if(date('l', strtotime($userFormatDate))!= "Saturday" && 
			date('l', strtotime($userFormatDate))!= "Sunday"){
			$outputArray[$userFormatDate] = array('No available times');
		}
		//increment counter
		$counter = date("Y-m-d H:i:s", strtotime("+1 days", strtotime($counter)));
	}
	//echo "output Array: ";
	//var_dump($outputArray);

	//sort datetimes. 2 is SORT_STRING
	sort($availableAppointmentArray, 2);

	//fill output array with options that the user will see
	//now is in sql format
	foreach($availableAppointmentArray as $availableDateTime)
	{
		//echo "$availableDateTime<br>";
		$availableDate = date("l, m/d/Y", strtotime($availableDateTime));
		$availableStartTime = date("g:i a", strtotime($availableDateTime));
		array_push($outputArray[$availableDate], $availableStartTime);
	} 

	//var_dump($outputArray);

	$today = date('l, m/d/Y');
	$userUpcomingDate = date('l, m/d/y', strtotime($upcomingDateTime));
	$userUpcomingTime = date('g:i a', strtotime($upcomingDateTime));

	if($upcomingAdvisorId == 'GROUPAP'){
		echo "$studentfName $studentlName is currently scheduled for a group advising appointment on";
	}
	else{
		echo "$studentfName $studentlName is currently scheduled for an individual advising appointment 
			with $upcomingAdvisorName on ";
	}
	echo "$userUpcomingDate at $userUpcomingTime.<br><br>";
	echo "Please select an available time to change the appointment to for ";
	if($advisorReschedule == 'GROUPAP'){
		echo "a group advising appointment.<br><br>";
	}
	else{
		echo "an individual advising appointment with $advisorRescheduleName.<br><br>";
	}

	echo "Today is $today.<br><br>";
	
	//first day is selected by default
	$previousButton = False;
	echo "<form action = 'AdvisorChangeDB.php' method = 'post'>";
	

	foreach($outputArray as $userDay=>$times){
		//at least one real time in that day
		if(sizeof($times) > 1){
			$intDay = strtotime($userDay);
	
			//radio button
			echo "<input type = 'radio' name = 'date' value = '$intDay'";
			if(!$previousButton){echo' checked';} 
				echo ">$userDay: ";
			$previousButton = true;

			//select boxes are stored by day in post
			echo "<select name = '$intDay'>";
			foreach($times as $startTime){
				//display as: "g:i-g:i a" for user
				if($startTime != 'No available times'){
					$endTime = date("g:i a", strtotime("+30 minutes", strtotime($startTime)));
					$availableTime = $startTime."-".$endTime;
					$intTime = strtotime($startTime);
					echo "<option value = '$intTime'>$availableTime</option>";
				}
			}	
			echo "</select><br>";
		}//end if
	
		else{
			
			//No available appointments
			//echo "$userDay: $times[0]";//this is how it previously was
			echo"<input type='radio' name = 'date' value = '$userDay' disabled>";
			echo"$userDay:";
			echo"<select name = '$intDay' disabled>";
			echo"<option value = '$times[0]'>$times[0]</option>";
			echo"</select><br>";
					
		}//end else
	}//end foreach	
	echo"<br>";

//check for reasons the advisor might not want to create an appointment
if($_SESSION['upcomingWithinDay'] ||
	!$_SESSION['currentWeekEnabled'] ||
	($_SESSION['studentHasPastAppointment'] && $advisorReschedule == 'GROUPAP')){
	echo "Please consider the following information about this student before rescheduling this appointment:<br><br>";
	
	if($_SESSION['upcomingWithinDay']){
		echo "The appointment to be changed is within 24 hours.<br><br>";
	}
	if(!$_SESSION['currentWeekEnabled']){
		echo "This student has another individual appointment this week.<br><br>";
	}
	if($_SESSION['studentHasPastAppointment'] && $advisorReschedule == 'GROUPAP'){
		echo "This student has already an appointment already semester, so a group appointment might not be the best option for them.<br><br>";
	}
}

//do not include next button if no appointments
$disableNext=false;
if(empty($availableAppointmentArray)){	
	$disableNext = true;
	if( $advisorReschedule == 'GROUPAP'){
		echo "There are no available Group Advising appointments at this time. 
			Please select more appointment times, or choose a different advising option.<br>";
	}
	else{
		echo "$advisorRescheduleName has no available appointments at this time.
			Please choose a different advising option.<br>";
	}
}


?>
<div class="button"><input type='submit' value = 'Change Appointment'<?php if($disableNext){echo "hidden";}?> ></div>
	</form>
<?php

?>
<form action='AdvisorOptions.php'>
<div class="button"><input type='submit' value='Go Back'></div>
</form>
</div>

<?php
$_SESSION['lastPage'] = 'AdvisorRescheduleAppointment.php';
include('Proj2Tail.html');
?>
