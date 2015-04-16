<?php
session_start();
/*
Name: Nathaniel Baylon
Date:03/21/2015
Class: CMSC331
Project:Advisor Time Selection
File: StudentCreateAppointmnet.php
File Description: This file allows student to choose a time. I will have to 
                  only let the student choose certain times for each major
*/
include ('Proj2Head.html');
include('../CommonMethods.php');

//get datetime from Advising_Availability for $_SESSION['studentsAdvisor']
//they might not be in order :( so I will have to sort them...


$advisorId = $_SESSION['studentsAdvisor'];
$advisorName = $_SESSION['advisors'][$advisorId];
//var_dump($_SESSION['studentsAdvisor']);
//echo "$advisorId<br>";
$fName = $_SESSION['fName'];

$todayDOW = date(l);//day of week today
if($_SESSION['currentWeekEnabled']){

	//weekdays
	if($todayDOW == "Monday"){
		$startDate = strtotime('Wednesday');
	}
	else if($todayDOW == "Tuesday"){
		$startDate = strtotime('Thursday');
	}
	else if($todayDOW == "Wednesday"){
		$startDate = strtotime('Friday');
	}
	else if($todayDOW == "Thursday"){
		$startDate = strtotime('Monday');
	}
	else if($todayDOW == "Friday" || $todayDOW == "Saturday" || $todayDOW == "Sunday"){
		$startDate = strtotime('Tuesday');
	}
	$endDate = strtotime("+1 week +1 day", $startDate);
}

else{

	$startDate = strtotime("next monday");
	$endDate = strtotime("+1 week +1 day", $startDate);		
}

$sqlFormatStartDate = date("Y-m-d H:i:s",$startDate);
$sqlFormatEndDate = date("Y-m-d H:i:s",$endDate);
//echo "Testing start and end date: $sqlFormatStartDate"."$sqlFormatEndDate<br>";

$debug = false;
$COMMON = new Common($debug);
$sql = "SELECT * FROM Advising_Availability2 where `advisorId` = '$advisorId' 
		AND `dateTime` BETWEEN '$sqlFormatStartDate' AND '$sqlFormatEndDate'";
$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);

$allPotentialAppointmentArray = array();

while($row = mysql_fetch_assoc($rs)){
	array_push($allPotentialAppointmentArray, $row);
//echo "o";
	//has all appointments within that timeframe with that advisor
}

//make sure there are openings, based on whether an appointmnet is taken in
//new addition: numSlots in Advising_Availability2 has number of slots in an appt
//Advising_Appointmnets, looping through each appointment in $allPotentialAppointmentArray

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
//echo "a";
}
//now, I have just times that are available, and will show as options for the student

//get a list of all possible days, to display. If no times are available in that day, 
//it will say so in the output

//var_dump($availableAppointmentArray);

	$outputArray = array();
	$counter = $sqlFormatStartDate;
	//echo "sqlFormatStartDate: $sqlFormatStartDate";
	//in sql format
	while($counter != $sqlFormatEndDate){
		//echo "$counter $sqlFormatEndDate<br>";
		$userFormatDate = date("l, m/d/Y",strtotime($counter));
		if(date('l', strtotime($userFormatDate))!= "Saturday" && 
			date('l', strtotime($userFormatDate))!= "Sunday"){
			$outputArray[$userFormatDate] = array('No available appointment times');
		}
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
	//$availableEndTime = date("g:i a", strtotime("+30 minutes", strtotime($availableDateTime)));
	$availableTime = $availableStartTime;//."-".$availableEndTime;
	//echo "available date: $availableDate availableTime: $availableTime<br>";
	array_push($outputArray[$availableDate], $availableTime);
	} 

	//var_dump($outputArray);

	//OUTPUT
	$today = date('l, m/d/Y');
	echo "$fName, please select an available time for ";
	if($advisorId == 'GROUPAP'){
		echo "a group advising appointment.<br>";
	}
	else{
	
		echo "an individual advising appointment with $advisorName.<br>";
	}
	echo "Today is $today.<br><br>";
	
	$previousButton = False;
	echo "<form action = 'StudentAreYouSure.php' method = 'post'>";
	foreach($outputArray as $userDay=>$times){
		if(sizeof($times) > 1){
			$intDay = strtotime($userDay);
			
			echo "<input type = 'radio' name = 'date' value = '$intDay'";
		if(!$previousButton){echo' checked';} 
			echo ">$userDay: ";
		$previousButton = true;
			echo "<select name = '$intDay'>";
			foreach($times as $userTime){
				$intTime = strtotime($userTime);
				if($userTime != 'No available appointment times'){
					echo "<option value = '$intTime'>$userTime</option>";
				}
			}
			echo "<br>";	
		}//end if
	
		else{
			echo "$userDay: $times[0]";
		}//end else
	echo "</select><br>";
	}//end foreach	


$disableNext=false;
if(empty($availableAppointmentArray)){	
	$disableNext = true;
	if( $advisorName == 'Group Advising'){
		echo "Sorry, there are no available Group Advising appointments at this time. 
			Please try again tomorrow, or choose a different advising option.<br>";
	}
	else{
		echo "Sorry $fName, $advisorName has no available appointments at this time.
			Please try again later, or choose a different advising option.<br>";
	}
}
?>
<input type = 'submit' value = 'Next'<?php if($disableNext){echo "disabled";}?> >
	</form>
<form action='StudentOptions.php'>
	<input type = 'submit' value='Go Back'>
</form>

<?php
$_SESSION['lastPage'] = 'StudentCreateAppointment.php';
include('Proj2Tail.html');
?>
