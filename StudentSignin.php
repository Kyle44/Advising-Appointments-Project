<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: StudentSignin.php
File Description: In this page, the student gives all their necessary information (signs in) 
*/

session_start();
$_SESSION['showSudentOptionsMessage'] = false;

include('Proj2Head.html');
//info format user enters is checked in ValidateStudentSiginin.php
if($_SESSION['lastPage'] == 'ValidateStudentSignin.php'){
	$signinError=true;
	$fName = $_SESSION['fName'];
	$lName = $_SESSION['lName'];
	$major = $_SESSION['major'];
	$studentId = $_SESSION['studentId'];
	$studentEmail = $_SESSION['studentEmail'];
	$returningStudentId = $_SESSION['returningStudentId'];
}
else{
	$signinError = false;//
}


?>

<!--output-->
<div class="form-div">
<div class="form-title">Student Signin<br></div>

New Student
<form action='ValidateStudentSignin.php' method='post' name='login'>
<div class="form">
	First Name: 	<input type='text' name='fName' <?php echo "value='$fName'"?>><br>
</div>
<div class="form">  	
	Last Name:  	<input type='text' name='lName' <?php echo "value='$lName'"?>><br>
</div>
<div class="form">
	Major:	 		<font color='white'>OO..</font><select name='major'>
	 				<option value='Undecided'>Undecided</option>
  					<option value='Computer Science' <?php if($major == 'Computer Science'){echo"selected";} ?>>Computer Science</option>
  					<option value='Computer Engineering' <?php if($major == 'Computer Engineering'){echo"selected";} ?>>Computer Engineering</option>
  					<option value='Mechanical Engineering' <?php if($major == 'Mechanical Engineering'){echo"selected";} ?>>Mechanical Engineering</option>
  					<option value='Chemical Engineering' <?php if($major == 'Chemical Engineering'){echo"selected";}?>>Chemical Engineering</option>
  					</select><br>
</div>
<div class="form">	
	Student Email:	<input type='text' name='studentEmail' <?php echo "value='$studentEmail'"?> ><br>
</div>
<div class="form">  	
	Student ID:	<input type='text' name='studentId' <?php echo "value='$studentId'"?> ><br>
</div>


<br><br>
Returning Student
<div class="form">  	
	Student ID:	<input type='text' name='returningStudentId' <?php echo "value='$returningStudentId'"?> ><br>
</div>
<br><br>
<?php

if($_SESSION['showStudentSigninErrorMessage']){
		//error message in red font
   		echo $_SESSION['studentSigninErrorMessage']."<br><br>";	
}
?>

<!--signin button-->
<div class="button"><input type='submit' value='Sign In'></div>
</form>


<!--back button-->
<form action='index.php' name='goBack'>
<div class="button"><input type='submit' value='Go Back'></div>
</form>
</div>
<?php
$_SESSION['lastPage'] = "StudentSignin.php";
include('Proj2Tail.html');
?>
