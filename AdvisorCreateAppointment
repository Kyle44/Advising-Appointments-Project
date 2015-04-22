<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: AdvisorCreateAppointment.php
File Description: In this file, a student chooses a radio button for the day they want to sign up
for, and selects a time from a select box on that same line. Times only show up
within 2 business days to a week after that if the current week is enabled, or 
next monday through the following monday if current week is disabled. If day has 
no availabilities, the student cannot select it. 
*/

session_start();
include ('Proj2Head.html');
include('CommonMethods.php');

$advisorId = $_SESSION['advisorId'];
$advisorName = $_SESSION['advisors'][$advisorId];
//var_dump($_SESSION['advisorId']);
?>



<?php
$_SESSION['lastPage'] = 'AdvisorCreateAppointment.php';
include('Proj2Tail.html');
?>
