<?php
session_start();

/*
Name: Nathaniel Baylon
Date:03/29/2015
Class: CMSC331
Project:Advisor Time Selection
File: StudentSignin.php
File Description: In this page, the student gives all their necessary information (signs in) 
*/
include('Proj2Head.html');


//info format user enters is checked in StudentOptions.php
//if Student goes back from here, signin error will be unset
if($_SESSION['signinError'] == true){
	$signinError=true;
	$fName = $_SESSION['fName'];
	$lName = $_SESSION['lName'];
	$major = $_SESSION['major'];
	$studentId = $_SESSION['studentId'];
	$studentEmail = $_SESSION['studentEmail'];
}
else{
	$signinError = false;//
}


?>

<!--output-->
Student Signin<br>
<form action='ValidateStudentSignin.php' method='post' name='login'>

	First Name: 	<input type='text' name='fName' <?php echo "value='$fName'"?>><br>
  	Last Name:  	<input type='text' name='lName' <?php echo "value='$lName'"?>><br>
  	Major:	 		<font color='white'>OO..</font><select name='major'>
	 				<option value='Undecided'>Undecided</option>
  					<option value='Computer Science' <?php if($major == 'Computer Science'){echo"selected";} ?>>Computer Science</option>
  					<option value='Computer Engineering' <?php if($major == 'Computer Engineering'){echo"selected";} ?>>Computer Engineering</option>
  					<option value='Mechanical Engineering' <?php if($major == 'Mechanical Engineering'){echo"selected";} ?>>Mechanical Engineering</option>
  					<option value='Chemical Engineering' <?php if($major == 'Chemical Engineering'){echo"selected";}?>>Chemical Engineering</option>
  					</select><br>
	Student Email:	<input type='text' name='studentEmail' <?php echo "value='$studentEmail'"?> ><br>
  	Student ID:	<input type='text' name='studentId' <?php echo "value='$studentId'"?> ><br>

<?php

if($signinError){
		//error message in red font
   		echo "<p><font color='#ff0000' > Please make sure all fields are filled, the first letter of<br> 
										first/last name is capitalized, the email has valid format,and the <br> 
										id format is two capital letters followed by five numbers.</p>";	
}
?>

<!--signin button-->
<input type='submit' value='Sign In'>
</form>

<!--back button-->
<form action='index.php' name='goBack'>
<input type='submit' value = 'Go Back'>
</form>

<?php
$_SESSION['lastPage'] = "StudentSignin.php";
include('Proj2Tail.html');
?>
