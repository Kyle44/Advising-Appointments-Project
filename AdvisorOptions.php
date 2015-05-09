<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: AdvisorOptions.php
File Description: This file shows the options for advising that the student can choose from.
The student can choose to create an appointment, view an upcoming appointment, change an 
appointment, or delete an appointment.
*/

session_start();
include('Proj2Head.html');

//variables to be used as default values
$advisorId = $_SESSION['advisorId'];
$advisors = $_SESSION['advisors'];
//$advisorView = $_SESSION['advisorView'];
$searchStudentID = $_SESSION['searchStudentID'];
$searchStudentlName = $_SESSION['searchStudentlName'];
$cancelID = $_SESSION['cancelID'];
$advisorReschedule = $_SESSION['advisorReschedule'];
$rescheduleID = $_SESSION['rescheduleID'];
$scheduleNewfName = $_SESSION['scheduleNewfName'];
$scheduleNewlName = $_SESSION['scheduleNewlName'];
$scheduleNewEmail = $_SESSION['scheduleNewEmail'];
$scheduleNewMajor = $_SESSION['scheduleNewMajor'];
$scheduleNewID = $_SESSION['scheduleNewID'];
$scheduleExistingID = $_SESSION['scheduleExistingID'];



//var_dump($_SESSION['advisors']);
?>

<!--Output-->
<div class="form-div">
<div class="form-title">Please choose an option:<br></div>
<div class="form">
<form action = 'AdvisorOptionHeaders.php' method = 'post' name = 'selectOption'>

<!--view Schedule-->
<input type = 'radio' name = 'rb_option' value = 'viewAppointment'
	<?php if($_SESSION['advisorDecision'] == 'viewAppointment'){echo 'checked';}?>
> View Schedule
<?php
/*
echo "<select name='sel_advisorView'>";
foreach($advisors as $advisorsId=>$advisorName){
	//if($advisorName != 'Group Advising'){
		echo"<option value = '$advisorsId'";
	 	if($advisorId == $advisorsId){
			echo "selected";
		}
		echo ">";
	//}
	echo "$advisorName</option>";
}
echo "</select><br><br>";
*/
?>



<!--select appointment times-->
<input type = 'radio' name = 'rb_option' value = 'selectAppointment' 
	<?php
	if($_SESSION['advisorDecision'] == 'selectAppointment' ||
	!isset($_SESSION['studentChoice'])){
		echo 'checked';
	}
	?>
> Select Appointment Times<br>


<!--cancelAppointmentTimes-->
<input type = 'radio' name = 'rb_option' value = 'cancelAppointmentTimes' 
	<?php
	if($_SESSION['advisorDecision'] == 'cancelAppointmentTimes'){
		echo 'checked';
	}
	?>
> Cancel Appointment Times<br><br>

<!--Find student-->
<input type = 'radio' name = 'rb_option' value = 'searchStudentID'
	<?php if($_SESSION['advisorDecision'] == 'searchStudentID'){echo 'checked';}?>
> Search For Student:
<input type = 'text' name = 'text_searchStudentID'<?php echo "value='$searchStudentID'"; ?> 
											placeholder = 'Last Name or Student ID'><br><br>

<!--Cancel appointment-->
<input type = 'radio' name = 'rb_option' value = 'cancelAppointment'
	<?php if($_SESSION['advisorDecision'] == 'cancelAppointment'){echo 'checked';}?>
>Cancel Appointment:<input type = 'text' name = 'text_cancelID'<?php echo "value='$cancelID'"; ?>
					placeholder = 'Student ID'><br><br>

<!--Reschedule Appointment-->
<input type = 'radio' name = 'rb_option' value = 'rescheduleAppointment'
	<?php if($_SESSION['advisorDecision'] == 'rescheduleAppointment'){echo 'checked';}?>
> Reschedule Appointment:<input type = 'text' name = 'text_rescheduleID'<?php echo "value='$rescheduleID'"; ?>
					placeholder = 'Student ID'><br>
<?php
echo "With Advisor:<select name='sel_advisorReschedule'>";
foreach($advisors as $advisorsId=>$advisorName){
	//if($advisorName != 'Group Advising'){
		echo"<option value = '$advisorsId'";
		if($advisorId == $advisorsId){
			echo "selected";
		}
		echo '>';
	//}
	echo "$advisorName</option>";
}
echo "</select><br><br>";
?>


<!--Schedule Appointment-->

<input type = 'radio' name = 'rb_option' value = 'scheduleAppointment'
	<?php if($_SESSION['advisorDecision'] == 'scheduleAppointment'){echo 'checked';}?>
> Schedule appointment for new or existing student<br>
<?php
echo "With Advisor:<select name='sel_advisorSchedule'>";
foreach($advisors as $advisorsId=>$advisorName){
	//if($advisorName != 'Group Advising'){
		echo"<option value = '$advisorsId'";
		if($advisorId == $advisorsId){
			echo "selected";
		}
		echo">";
	//}
	echo "$advisorName</option>";
}
echo "</select><br><br>";
?>


New Student's Information:<br>
Student First Name:	 <input type='text' name='text_scheduleNewfName'<?php echo "value='$scheduleNewfName'"; ?>><br>
Student Last Name: 	<input type='text' name='text_scheduleNewlName'<?php echo "value='$scheduleNewlName'"; ?>><br>
Student Major: 		<select name='sel_scheduleNewMajor'>
	 						<option value='Undecided'<?php if($scheduleNewMajor == 'Undecided'){echo"selected";} ?>>Undecided</option>
  							<option value='Computer Science'<?php if($scheduleNewMajor == 'Computer Science'){echo"selected";} ?>>Computer Science</option>
  							<option value='Computer Engineering'<?php if($scheduleNewMajor == 'Computer Engineering'){echo"selected";} ?>>Computer Engineering</option>
  							<option value='Mechanical Engineering'<?php if($scheduleNewMajor == 'Mechanical Engineering'){echo"selected";} ?>>Mechanical Engineering</option>
  							<option value='Chemical Engineering' <?php if($scheduleNewMajor == 'Chemical Engineering'){echo"selected";} ?>>Chemical Engineering</option>
  						</select><br>
Student Email:		<input type='text' name='text_scheduleNewEmail'<?php echo "value='$scheduleNewEmail'"; ?>><br>
Student ID:			<input type='text' name='text_scheduleNewID'<?php echo "value='$scheduleNewID'"; ?>><br><br>

Existing Student's Information:<br>
Student ID:			<input type='text' name='text_scheduleExistingID'<?php echo "value='$scheduleExistingID'"; ?>><br><br>


<!--THE MESSAGE, if there is one...Needs some styling, but I'm lazy  (:@) -->

<?php
if($_SESSION['showAdvisorOptionsMessage']){
	$message = $_SESSION['advisorOptionsMessage'];
	echo "$message <br><br>";
}
?>


<!--submit button-->
<div class="button"><input type = 'submit' value = 'Next'></div>
</form>

<!--go back button-->
<form action = 'index.php' name = 'goback'>
<div class="button"><input type='submit' value = 'Sign Out'></div>
</form>
</div>
</div>

<?php
$_SESSION['lastPage'] = "AdvisorOptions.php";
include('Proj2Tail.html');
?>
