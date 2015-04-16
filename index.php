<?php
session_start();
session_unset();

/*
Name: Nathaniel Baylon
Date:03/29/2015
Class: CMSC331
Project:Project2
File: index.php
File Description: This is the welcome page, where the user can choose either to start an
advisor session, or a student session 
*/
include('Proj2Head.html');
?>

<!--output-->
Welcome<br>

<!--buttons-->

<form action='AdvisorSignin.php' value='Advisor'>
	<input type='submit' value='Advisor'>
</form>

<form action='StudentSignin.php' value='Student'>
	<input type='submit' value='Student'>
</form>



<?php
$_SESSION['lastPage'] = "index.php";
include('Proj2Tail.html');
?>
