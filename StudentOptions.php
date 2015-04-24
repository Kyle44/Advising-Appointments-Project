<?php
session_start();

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: StudentOptions.php
File Description: This file shows the options for advising that the student can choose from.
The student can choose to create an appointment, view an upcoming appointment, change an 
appointment, or delete an appointment.
*/

include('Proj2Head.html');
$fName = $_SESSION['fName'];
$studentId = $_SESSION['studentId'];
$advisors = $_SESSION['advisors'];
$groupEnabled = $_SESSION['groupEnabled'];
$indEnabled = $_SESSION['indEnabled'];
$hasUpcomingAppointment = $_SESSION['studentHasUpcomingAppointment'];
$hasPastAppointment = $_SESSION['studentHasPastAppointment'];
$upcomingWithinDay = $_SESSION['upcomingWithinDay'];
//var_dump($_SESSION['advisors']);
?>

<!--Output-->
<div class="form-div">
<div class="form-title">Please choose an option:<br></div>
<form action = 'StudentOptionHeaders.php' method = 'post' name = 'selectOption'>

<!------------------------------------------------------------------------->
<div class="form">
<input type = 'radio' name = 'rb_option' value = 'createGroupAppointment' 
<?php 
if(!$groupEnabled){
	echo 'disabled';
}

elseif($_SESSION['studentChoice'] == 'createGroupAppointment' || 
		empty($_SESSION['studentChoice'])){
echo 'checked';
}
?> >Create Group Appointment<br>
<input type = 'radio' name = 'rb_option' value = 'createIndividualAppointment' 
<?php if(!$indEnabled){
echo 'disabled';
}

//checked if group disabled
elseif($_SESSION['studentChoice'] == 'createIndividualAppointment'||!$groupEnabled){
echo ' checked';
}
?> >Create Individual Appointment<br>
<!------------------------------------------------------------------------>
Advisor:
<?php
echo "<select name='sel_createAdvisor'";
if(!$indEnabled){
	echo 'disabled';
}
echo '>';

//=> is not a mistake. It's like for each key as its values
foreach($advisors as $advisorId=>$advisorName){
	if($advisorName != 'Group Advising'){
		echo"<option value = '$advisorId'";
	 	if($_SESSION['studentCreateAdvisor'] == $advisorId){
			echo "selected";
		}
		echo ">";
	}
	echo "$advisorName</option>";
}
echo "</select><br><br>";
?>
</div>
<!--------------------------------------------------------------------------->
<div class="form">
<input type = 'radio' name = 'rb_option' value = 'changeToGroupAppointment'
<?php if(!$hasUpcomingAppointment || $hasPastAppointment || $upcomingWithinDay){
echo "disabled";
} 

//cancel and change will always be enabled/disabled at the same time, so 
//only check if this was the student's option (coming from a later page)
elseif($_SESSION['studentChoice'] == 'changeToGroupAppointment'||(!$groupEnabled && !$indEnabled)){
	echo "checked";
}
?> >Change to Group Appointment<br>

<!------------------------------------------------------------------------------->
<input type = 'radio' name = 'rb_option' value = 'changeToIndividualAppointment'
<?php if(!$hasUpcomingAppointment || $upcomingWithinDay){
echo "disabled";
}
//cancel and change will always be enabled/disabled at the same time, so 
elseif($_SESSION['studentChoice'] == 'changeToIndividualAppointment' ||(!$groupEnabled && !$indEnabled && $hasPastAppointmnet)){
echo "checked";
}
?> >Change to Individual Appointment<br>
Advisor:

<!---------------------------------------------------------------------->
<?php
echo "<select name='sel_changeAdvisor'";
if(!$hasUpcomingAppointment|| $upcomingWithinDay){
	echo 'disabled';
}
echo '>';
foreach($advisors as $advisorId=>$advisorName){
	if($advisorName != 'Group Advising'){
		echo"<option value = '$advisorId'";
	 	if($_SESSION['studentsChangeAdvisor'] == $advisorId){
			echo "selected";
		}
		echo ">";
	}
	echo "$advisorName</option>";
}
echo "</select><br><br>";
?>
</div>
<!------------------------------------------------------------------------>
<div class="form">
<input type = 'radio' name = 'rb_option' value = 'viewAppointment'
<?php if(!$_SESSION['viewEnabled']){
echo "disabled";
}

//checked if previous 2 are disabled
elseif($_SESSION['studentChoice'] == 'viewAppointment' ||$hasPastAppointment || $hasUpcomingAppointment){
echo " checked";
}
?> >View Created Appointment<br>


<!----------------------------------------------------------------------------->
<input type = 'radio' name = 'rb_option' value = 'cancelAppointment'
<?php if(!$hasUpcomingAppointment || $upcomingWithinDay){
echo "disabled";
}
//will never be checked by default
elseif($_SESSION['studentChoice'] == 'cancelAppointment'){
echo "checked";
}
 ?> >Cancel Created Appointment<br><br>
</div>
<!------------------------------------------------------------------------------>

<!--student options message-->
<?php
if($_SESSION['showStudentOptionsMessage']){

echo $_SESSION['studentOptionsMessage']."<br>";
}
?>
<!---------------------------->

<!--submit button-->
<div class="button"><input type = 'submit' value = 'Next'></div>
</form>
<!------------------------------------------------------------------------->
<!--go back button-->
<form action = 'index.php' name = 'goback'>
<div class="button"><input type='submit' value = 'Sign Out'></div>
</form>
</div>

<?php
$_SESSION['lastPage'] = "StudentOptions.php";
include('Proj2Tail.html');
?>
