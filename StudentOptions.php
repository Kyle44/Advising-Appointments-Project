<?php
session_start();

/*
Name: Nathaniel Baylon
Date:03/21/2015
Class: CMSC331
Project:Advisor Time Selection
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

//var_dump($_SESSION['advisors']);
?>

<!--Output-->
Choose an option:<br>
<form action = 'StudentOptionHeaders.php' method = 'post' name = 'selectOption'>
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
elseif($_SESSION['studentChoice'] == 'createIndividualAppointment'){
echo ' checked';
}


?> >Create Individual Appointment<br>
Advisor:
<?php
echo "<select name='sel_advisor'";
if(!$indEnabled){
	echo 'disabled';
}
echo '>';
foreach($advisors as $advisorId=>$advisorName){
	if($advisorName != 'Group Advising'){
		echo"<option value = '$advisorId'";
	 	if($_SESSION['selectedAdvisor'] == $advisorId){
			echo "selected";
		}
		echo ">";
	}
	echo "$advisorName</option>";
}
echo "</select><br>";
?>
<br>


<input type = 'radio' name = 'rb_option' value = 'viewAppointment'
<?php if(!$_SESSION['viewEnabled']){
echo "disabled";
}
elseif($_SESSION['studentChoice'] == 'viewAppointment' || empty($_SESSION['studentChoice'])){
echo " checked";
}
?> >View Created Appointment<br>



<input type = 'radio' name = 'rb_option' value = 'cancelAppointment'
<?php if(!$hasUpcomingAppointment){
echo "disabled";
}
elseif($_SESSION['studentChoice'] == 'cancelAppointment'){
echo "checked";
}
 ?> >Cancel Created Appointment<br>



<input type = 'radio' name = 'rb_option' value = 'changeAppointment'
<?php if(!$hasExistingAppointment){
echo "disabled";
} 
elseif($_SESSION['studentChoice'] == 'changeAppointment'){
echo "checked";
}
?> >Change Created Appointment<br>



<input type = 'submit' value = 'Next'>
</form>

<form action = 'index.php' name = 'goback'>
<input type='submit' value = 'Sign Out'>
</form>

<?php
$_SESSION['lastPage'] = "StudentOptions.php";
include('Proj2Tail.html');
?>
