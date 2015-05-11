<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: AdvisorCreateAppointment.php
File Description: In this file, a student chooses a radio button for the day they want to sign up
for, and selects a time from a select box on that same line. Times only show up
within 2 business days to a week after that if the current week is enabled, or 
next monday through the following monday if current week is disabled. If day has 
no availabilities, the student cannot select it. 
*/

session_start();
include ('Proj2Head.html');
include('CommonMethods.php');



$debug = false;
$COMMON = new Common($debug);

$advisorId = $_SESSION['advisorId'];
$advisorName = $_SESSION['advisors'][$advisorId];
$demo = $_SESSION['demo'];
$schedule = array();
$avaliableSchedule = array();
$appointedSchedule = array();

$sql = "SELECT * FROM Advising_Availability2 where `advisorId` = '$advisorId' OR `advisorId` = 'GROUPAP'";
$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
while($row = mysql_fetch_assoc($rs)){
	array_push($avaliableSchedule, $row['dateTime']);
}

$sql = "SELECT * FROM `Advising_Appointments2` where `advisorId` = '$advisorId' OR `advisorId` = 'GROUPAP'";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
while($row = mysql_fetch_assoc($rs)){
	array_push($appointedSchedule, $row['dateTime']);
}

echo "
<link rel='stylesheet' type='text/css' href='http://userpages.umbc.edu/~t22/CMSC331/Project2/css/AdvisorCancelAppointmentStylesheet.css'/>
<div class='form-div'>
<div class='form-title'> Advisor Cancel Appointments</div>
<form action='AdvisorCancelAppointmentTimesDB.php' name='advisorAppointmentCancelTimes' method='post'>";



if ($demo == False)
{
	$startDate = date('Y-m-d 08:00:00', strtotime('+1 week'));
	$date = $startDate;
	$endDate = date('Y-m-d H:i:s', strtotime('+1 week', strtotime($date)));
	$endOfAppointments = True;
	while ($endOfAppointments)
	{
		if (date('l', strtotime($date)) != "Saturday" &&
		date('l', strtotime($date)) != "Sunday" &&
		date('H', strtotime($date)) >= "08" && 
		date('H', strtotime($date)) <= "16" && 
		date('H:i', strtotime($date)) != "16:30")
		{
			$schedule[$date] = date('F j, Y, g:i a', strtotime($date));
		}
	$date = date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($date)));
		if ($date == $endDate)
		{
			$endOfAppointments = False;
		}
	}
}
else
{
	$startDate = date('2015-03-02 08:00:00');
	$date = $startDate;
	$endDate = date('2015-05-1 16:00:00');
	$endOfAppointments = True;
	while ($endOfAppointments)
	{
		if (date('l', strtotime($date)) != "Saturday" &&
		date('l', strtotime($date)) != "Sunday" &&
		date('H', strtotime($date)) >= "08" && 
		date('H', strtotime($date)) <= "16" && 
		date('H:i', strtotime($date)) != "16:30")
		{
			$schedule[$date] = date('F j, Y, g:i a', strtotime($date));
		}
	$date = date('Y-m-d H:i:s', strtotime('+30 minutes', strtotime($date)));
		if ($date == $endDate)
		{
			$endOfAppointments = False;
		}
	}
}
$_SESSION['schedule'] = $schedule;

echo "<div class='monday'>
<div class='DOW' style='width: 14%';>Monday</div>
<div class='DOW-form-div'>";
foreach ($schedule as $key => $value)
{	
	$keySpace = str_replace(" ","", $key);
	$valueSpace = str_replace(" ","", $value);
	$keyIndividual = $keySpace.'Individual';
	$keyGroup = $keySpace.'Group';
	$valueMajor = $valueSpace.'Major';
	$valueHide = $valueSpace.'';
	
	if (date('l', strtotime($key)) == "Monday" && in_array($key, $appointedSchedule))
	{
		
		echo "<div class='space'>
		<b>$value</b> Cancel Appointment
		<input type='checkbox' id='$key' name='$key' value='$value'>
		<b><font color='#ff0000'>Student assigned to time slot</font></b>
		</div>";
		// if there is a student signed up
	}
	elseif (date('l', strtotime($key)) == "Monday" && in_array($key, $avaliableSchedule))
	{
		
		echo "<div class='space'>
		<b>$value</b> Cancel Appointment
		<input type='checkbox' id='$key' name='$key' value='$value'>
		</div>";
		// if there is a student signed up
	}
	else
	{
		// Nothing
	}
}
echo "</div>
</div>";
echo "<div class='tuesday'>
<div class='DOW' style='width: 14%';>Tuesday</div>
<div class='DOW-form-div'>";
foreach ($schedule as $key => $value)
{	
	$keySpace = str_replace(" ","", $key);
	$valueSpace = str_replace(" ","", $value);
	$keyIndividual = $keySpace.'Individual';
	$keyGroup = $keySpace.'Group';
	$valueMajor = $valueSpace.'Major';
	$valueHide = $valueSpace.'';
	
	
	if (date('l', strtotime($key)) == "Tuesday" && in_array($key, $appointedSchedule))
	{
		
		echo "<div class='space'>
		<b>$value</b> Cancel Appointment
		<input type='checkbox' id='$key' name='$key' value='$value'>
		<b><font color='#ff0000'>Student assigned to time slot</font></b>
		</div>";
		// if there is a student signed up
	}
	elseif (date('l', strtotime($key)) == "Tuesday" && in_array($key, $avaliableSchedule))
	{
		
		echo "<div class='space'>
		<b>$value</b> Cancel Appointment
		<input type='checkbox' id='$key' name='$key' value='$value'>
		</div>";
		// if there is a student signed up
	}
	else
	{
		// Nothing
	}
}
echo "</div>
</div>";
echo "<div class='wednesday'>
<div class='DOW' style='width: 14%';>Wednesday</div>
<div class='DOW-form-div'>";
foreach ($schedule as $key => $value)
{	
	$keySpace = str_replace(" ","", $key);
	$valueSpace = str_replace(" ","", $value);
	$keyIndividual = $keySpace.'Individual';
	$keyGroup = $keySpace.'Group';
	$valueMajor = $valueSpace.'Major';
	$valueHide = $valueSpace.'';
	
	
	if (date('l', strtotime($key)) == "Wednesday" && in_array($key, $appointedSchedule))
	{
		
		echo "<div class='space'>
		<b>$value</b> Cancel Appointment
		<input type='checkbox' id='$key' name='$key' value='$value'>
		<b><font color='#ff0000'>Student assigned to time slot</font></b>
		</div>";
		// if there is a student signed up
	}
	elseif (date('l', strtotime($key)) == "Wednesday" && in_array($key, $avaliableSchedule))
	{
		
		echo "<div class='space'>
		<b>$value</b> Cancel Appointment
		<input type='checkbox' id='$key' name='$key' value='$value'>
		</div>";
		// if there is a student signed up
	}
	else
	{
		// Nothing
	}
}
echo "</div>
</div>";
echo "<div class='DOW-form-div'>
<div class='DOW'>Thursday</div>";
foreach ($schedule as $key => $value)
{	
	$keySpace = str_replace(" ","", $key);
	$valueSpace = str_replace(" ","", $value);
	$keyIndividual = $keySpace.'Individual';
	$keyGroup = $keySpace.'Group';
	$valueMajor = $valueSpace.'Major';
	$valueHide = $valueSpace.'';
	
	
	if (date('l', strtotime($key)) == "Thursday" && in_array($key, $appointedSchedule))
	{
		
		echo "<div class='space'>
		<b>$value</b> Cancel Appointment
		<input type='checkbox' id='$key' name='$key' value='$value'>
		<b><font color='#ff0000'>Student assigned to time slot</font></b>
		</div>";
		// if there is a student signed up
	}
	elseif (date('l', strtotime($key)) == "Thursday" && in_array($key, $avaliableSchedule))
	{
		
		echo "<div class='space'>
		<b>$value</b> Cancel Appointment
		<input type='checkbox' id='$key' name='$key' value='$value'>
		</div>";
		// if there is a student signed up
	}
	else
	{
		// Nothing
	}
}
echo "</div>
</div>";
echo "<div class='friday'>
<div class='DOW' style='width: 14%';>Friday</div>
<div class='DOW-form-div'>";
foreach ($schedule as $key => $value)
{	
	$keySpace = str_replace(" ","", $key);
	$valueSpace = str_replace(" ","", $value);
	$keyIndividual = $keySpace.'Individual';
	$keyGroup = $keySpace.'Group';
	$valueMajor = $valueSpace.'Major';
	$valueHide = $valueSpace.'';
	
	
	if (date('l', strtotime($key)) == "Friday" && in_array($key, $appointedSchedule))
	{
		
		echo "<div class='space'>
		<b>$value</b> Cancel Appointment
		<input type='checkbox' id='$key' name='$key' value='$value'>
		<b><font color='#ff0000'>Student assigned to time slot</font></b>
		</div>";
		// if there is a student signed up
	}
	elseif (date('l', strtotime($key)) == "Friday" && in_array($key, $avaliableSchedule))
	{
		
		echo "<div class='space'>
		<b>$value</b> Cancel Appointment
		<input type='checkbox' id='$key' name='$key' value='$value'>
		</div>";
		// if there is a student signed up
	}
	else
	{
		// Nothing
	}
}
echo "</div>
</div>";
?>

<!--submit button-->
<div class="button"><input type = 'submit' value = 'Next'></div>
</form>

<!--go back button-->
<form action = 'AdvisorOptions.php' name = 'goback'>
<div class="button"><input type='submit' value = 'Back'></div>
</form>
</div>
<?php
$_SESSION['lastPage'] = 'AdvisorCancelAppointmentTimes.php';
include('Proj2Tail.html');
?>
