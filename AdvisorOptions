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
$fName = $_SESSION['fName'];
$advisorId = $_SESSION['advisorId'];
$advisors = $_SESSION['advisors'];
$viewEnabled = $_SESSION['viewEnabled'];
//var_dump($_SESSION['advisors']);
?>

<!--Output-->
<div class="form-div">
<div class="form-title">Please choose an option:<br></div>
<div class="form">
<form action = 'AdvisorOptionHeaders.php' method = 'post' name = 'selectOption'>
<input type = 'radio' name = 'rb_option' value = 'selectAppointment' checked> Select Appointment<br>
<input type = 'radio' name = 'rb_option' value = 'viewAppointment'> View Appointment
<?php
echo "<select name='sel_advisor'>";
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
echo "</select>";
?>
<br>
<input type = 'radio' name = 'rb_option' value = 'cancelAppointment'
<?php if(!$viewEnabled){
echo 'disabled';
}
?>
> Cancel Created Appointment<br>
<input type = 'radio' name = 'rb_option' value = 'changeAppointment'
<?php if(!$viewEnabled){
echo 'disabled';
}
?>
> Change Created Appointment<br>

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
