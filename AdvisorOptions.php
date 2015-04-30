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
$advisorId = $_SESSION['advisorId'];
$advisors = $_SESSION['advisors'];
//var_dump($_SESSION['advisors']);
?>

<!--Output-->
<div class="form-div">
<div class="form-title">Please choose an option:<br></div>
<div class="form">
<form action = 'AdvisorOptionHeaders.php' method = 'post' name = 'selectOption'>
<input type = 'radio' name = 'rb_option' value = 'selectAppointment' checked> Create Appointments<br>
<input type = 'radio' name = 'rb_option' value = 'viewAppointment'> View Schedules
<?php
echo "<select name='sel_advisor'>";
foreach($advisors as $advisorsId=>$advisorName){
	if($advisorName != 'Group Advising'){
		echo"<option value = '$advisorsId'";
	 	if($advisorId == $advisorsId || $_SESSION['advisorView'] == $advisorsId){
			echo "selected";
		}
		echo ">";
	}
	echo "$advisorName</option>";
}
echo "</select>";
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
