<html>
	<head>
<!--
Name: Nathaniel Baylon
Date:03/29/2015
Class: CMSC331
Project:Project 2

File: Proj2Head.html
File Description: This file contains the head that will be included throughout the
				  project. The head has the CSS selectors for styling, and the UMBC 
				  logo appears as the first element displayed on each page.
-->

<title>Advising Scheduling</title>
<link rel="icon" type="image/png" href="http://sites.umbc.edu/wp-content/themes/umbc/assets/images/icon.png" />
<link rel="stylesheet" type="text/css" href="css/stylesheet.css"/>
</head>
<body>
<div class="one-third-header"></div>
<div class="two-third-header">
<img class="logo" src="picture/Logo.png" alt="UMBC Advising">
</div>
<div class="three-third-header"></div>
<div class="background">
<div class="one-third-background"></div>
<div class="two-third-background"></div>
<div class="three-third-background"></div>
</div>
<!--rest of php/html code below, /body and /html in Proj2Tail.html-->


<!--output-->
<div class="form-div">
<div class="form-title">Student Signin<br></div>
<form action='ValidateStudentSignin.php' method='post' name='login'>
<div class="form">
	First Name: 	<input type='text' name='fName' value=''><br>
</div>
<div class="form">  	
	Last Name:  	<input type='text' name='lName' value=''><br>
</div>
<div class="form">
	Major:	 		<font color='white'>OO..</font><select name='major'>
	 				<option value='Undecided'>Undecided</option>
  					<option value='Computer Science' >Computer Science</option>
  					<option value='Computer Engineering' >Computer Engineering</option>
  					<option value='Mechanical Engineering' >Mechanical Engineering</option>
  					<option value='Chemical Engineering' >Chemical Engineering</option>
  					</select><br>
</div>
<div class="form">	
	Student Email:	<input type='text' name='studentEmail' value='' ><br>
</div>
<div class="form">  	
	Student ID:	<input type='text' name='studentId' value='' ><br>
</div>

<!--signin button-->
<div class="button"><input type='submit' value='Sign In'></div>
</form>

<!--back button-->
<form action='index.php' name='goBack'>
<div class="button"><input type='submit' value='Go Back'></div>
</form>
</div>
<!--
Name: Nathaniel Baylon
Date:03/21/2015
Class: CMSC331
Project:Advisor Time Selection
File: Proj2Tail.html
-->

</body>
</html>
