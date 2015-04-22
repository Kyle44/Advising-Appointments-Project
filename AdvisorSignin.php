<?php
session_start();

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: AdvisorSignin.php
File Description: In this page, the student gives all their necessary information (signs in) 
*/

include('Proj2Head.html');
if($_SESSION['signinError'] == true){
	$signinError=true;
	$fName = $_SESSION['fName'];
	$lName = $_SESSION['lName'];
	$advisorPhoneNumber = $_SESSION['advisorPhoneNumber'];
	$advisorId = $_SESSION['advisorId'];
	$advisorEmail = $_SESSION['advisorEmail'];
}
else{
	$signinError = false;//
}

?>

<!--output-->
<div class="form-div">
<div class="form-title"> Advisor Signin<br></div>
<form action='ValidateAdvisorSignin.php' method='post' name='login'>
	<div class="form">
	First Name: 	
					<input type='text' name='fName' <?php echo "value='$fName'"?>><br>
	</div>
	<div class="form">
	Last Name:  	
					<input type='text' name='lName' <?php echo "value='$lName'"?>><br>
	</div>
	<div class="form">
  	Phone Number:	
					<input type='text' name='advisorPhoneNumber' <?php echo "value='$advisorPhoneNumber'"?>><br>
	</div>
	<div class="form">
	Advisor Email:	
					<input type='text' name='advisorEmail' <?php echo "value='$advisorEmail'"?>><br>
	</div>
	<div class="form">
  	Advisor ID:		
					<input type='text' name='advisorId' <?php echo "value='$advisorId'"?>><br>
	</div>

<?php

if($signinError){
		//error message in red font
   		echo "<p><font color='#ff0000' > Please make sure all fields are filled, the first letter of<br> 
										first/last name is capitalized, the email has valid format,and the <br> 
										id format is two capital letters followed by five numbers.</p>";	
}
?>

<!--signin button-->
<div class="button"><input type='submit' value='Sign In'></div>
</form>

<!--back button-->
<form action='index.php' name='goBack'>
<div class="button"><input type='submit' value ='Go Back'></div>
</form>
</div>

<?php
$_SESSION['lastPage'] = "AdvisorSignin.php";
include('Proj2Tail.html');
?>
