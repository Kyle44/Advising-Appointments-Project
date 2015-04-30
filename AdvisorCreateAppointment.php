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
$schedule = array();
$avaliableSchedule = array();

$sql = "SELECT * FROM Advising_Availability2 where `advisorId` = '$advisorId'";
$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
while($row = mysql_fetch_assoc($rs)){
	array_push($avaliableSchedule, $row['dateTime']);
}

echo "<div class='form-div'>
<div class='form-title'> Advisor Create Appointments</div>
<form action='AdvisorInsertDB.php' name='advisorAppointments' method='post'>";


$startDate = date('Y-m-d 09:00:00', strtotime('+2 days'));
$date = $startDate;
$endDate = date('Y-m-d H:i:s', strtotime('+1 week', strtotime($date)));
$endOfAppointments = True;
while ($endOfAppointments)
{
	if (date('l', strtotime($date)) != "Saturday" &&
	date('l', strtotime($date)) != "Sunday" &&
	date('H', strtotime($date)) >= "09" && 
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
$_SESSION['schedule'] = $schedule;

echo "<div class='DOW-form-div'>
<div class='DOW'>Monday</div>
<div class='individual'>";
foreach ($schedule as $key => $value)
{
	if (date('l', strtotime($key)) == "Monday" && date('H', strtotime($key)) < "14" &&
	!in_array($key, $avaliableSchedule))
	{
		echo "<div class='checkbox'>
		<input type='checkbox' name='$key' value='$key'>Individual Appointment $value
		</div>
		<input type='hidden' name='$value' value='1'>";
	}
}

echo "</div>
<div class='group'>";
foreach ($schedule as $key => $value)
{
	if (date('l', strtotime($key)) == "Monday" && date('H', strtotime($key)) >= "14" &&
	!in_array($key, $avaliableSchedule)
	)
	{
		echo "<div class='checkbox'>
		<input type='checkbox' name='$key' value='$key'>Group Appointment $value
		<select name='$value'>
		<option value='1'>1</option>
		<option value='2'>2</option>
		<option value='3'>3</option>
		<option value='4'>4</option>
		<option value='5'>5</option>
		<option value='6'>6</option>
		<option value='7'>7</option>
		<option value='8'>8</option>
		<option value='9'>9</option>
		<option value='10' selected>10</option>
		</select>
		</div>";
	}
}
echo "</div>";
echo "</div>";
echo "<div class='DOW-form-div'>
<div class='DOW'>Tuesday</div>
<div class='individual'>";
foreach ($schedule as $key => $value)
{
	if (date('l', strtotime($key)) == "Tuesday" && date('H', strtotime($key)) < "14" &&
	!in_array($key, $avaliableSchedule))
	{
		echo "<div class='checkbox'>
		<input type='checkbox' name='$key' value='$key'>Individual Appointment $value
		</div>
		<input type='hidden' name='$value' value='1'>";
	}
}

echo "</div>
<div class='group'>";
foreach ($schedule as $key => $value)
{
	if (date('l', strtotime($key)) == "Tuesday" && date('H', strtotime($key)) >= "14" &&
	!in_array($key, $avaliableSchedule))
	{
		echo "<div class='checkbox'>
		<input type='checkbox' name='$key' value='$key'>Group Appointment $value
		<select name='$value'>
		<option value='1'>1</option>
		<option value='2'>2</option>
		<option value='3'>3</option>
		<option value='4'>4</option>
		<option value='5'>5</option>
		<option value='6'>6</option>
		<option value='7'>7</option>
		<option value='8'>8</option>
		<option value='9'>9</option>
		<option value='10' selected>10</option>
		</select>
		</div>";
	}
}
echo "</div>";
echo "</div>";
echo "<div class='DOW-form-div'>
<div class='DOW'>Wednesday</div>
<div class='individual'>";
foreach ($schedule as $key => $value)
{
	if (date('l', strtotime($key)) == "Wednesday" && date('H', strtotime($key)) < "14" &&
	!in_array($key, $avaliableSchedule))
	{
		echo "<div class='checkbox'>
		<input type='checkbox' name='$key' value='$key'>Individual Appointment $value
		</div>
		<input type='hidden' name='$value' value='1'>";
	}
}

echo "</div>
<div class='group'>";
foreach ($schedule as $key => $value)
{
	if (date('l', strtotime($key)) == "Wednesday" && date('H', strtotime($key)) >= "14" &&
	!in_array($key, $avaliableSchedule))
	{
		echo "<div class='checkbox'>
		<input type='checkbox' name='$key' value='$key'>Group Appointment $value
		<select name='$value'>
		<option value='1'>1</option>
		<option value='2'>2</option>
		<option value='3'>3</option>
		<option value='4'>4</option>
		<option value='5'>5</option>
		<option value='6'>6</option>
		<option value='7'>7</option>
		<option value='8'>8</option>
		<option value='9'>9</option>
		<option value='10' selected>10</option>
		</select>
		</div>";
	}
}
echo "</div>";
echo "</div>";
echo "<div class='DOW-form-div'>
<div class='DOW'>Thursday</div>
<div class='individual'>";
foreach ($schedule as $key => $value)
{
	if (date('l', strtotime($key)) == "Thursday" && date('H', strtotime($key)) < "14" &&
	!in_array($key, $avaliableSchedule))
	{
		echo "<div class='checkbox'>
		<input type='checkbox' name='$key' value='$key'>Individual Appointment $value
		</div>
		<input type='hidden' name='$value' value='1'>";
	}
}

echo "</div>
<div class='group'>";
foreach ($schedule as $key => $value)
{
	if (date('l', strtotime($key)) == "Thursday" && date('H', strtotime($key)) >= "14" &&
	!in_array($key, $avaliableSchedule))
	{
		echo "<div class='checkbox'>
		<input type='checkbox' name='$key' value='$key'>Group Appointment $value
		<select name='$value'>
		<option value='1'>1</option>
		<option value='2'>2</option>
		<option value='3'>3</option>
		<option value='4'>4</option>
		<option value='5'>5</option>
		<option value='6'>6</option>
		<option value='7'>7</option>
		<option value='8'>8</option>
		<option value='9'>9</option>
		<option value='10' selected>10</option>
		</select>
		</div>";
	}
}
echo "</div>";
echo "</div>";
echo "<div class='DOW-form-div'>
<div class='DOW'>Friday</div>
<div class='individual'>";
foreach ($schedule as $key => $value)
{
	if (date('l', strtotime($key)) == "Friday" && date('H', strtotime($key)) < "14" &&
	!in_array($key, $avaliableSchedule))
	{
		echo "<div class='checkbox'>
		<input type='checkbox' name='$key' value='$key'>Individual Appointment $value
		</div>
		<input type='hidden' name='$value' value='1'>";
	}
}

echo "</div>
<div class='group'>";
foreach ($schedule as $key => $value)
{
	if (date('l', strtotime($key)) == "Friday" && date('H', strtotime($key)) >= "14" &&
	!in_array($key, $avaliableSchedule))
	{
		echo "<div class='checkbox'>
		<input type='checkbox' name='$key' value='$key'>Group Appointment $value
		<select name='$value'>
		<option value='1'>1</option>
		<option value='2'>2</option>
		<option value='3'>3</option>
		<option value='4'>4</option>
		<option value='5'>5</option>
		<option value='6'>6</option>
		<option value='7'>7</option>
		<option value='8'>8</option>
		<option value='9'>9</option>
		<option value='10' selected>10</option>
		</select>
		</div>";
	}
}
echo "</div>";
echo "</div>";

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
$_SESSION['lastPage'] = 'AdvisorCreateAppointment.php';
include('Proj2Tail.html');
?>
