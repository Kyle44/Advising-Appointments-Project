<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: StudentDeleteDB.php
File Description: This file deletes the student's appointment from the database.
*/

session_start();
include('../CommonMethods.php');
$sqlFormatTime = $_SESSION['studentDeleteTime'];
$studentId = $_SESSION['studentId'];

$debug = false;
$COMMON = new Common($debug);
// Select the ENTIRE ROW for appointments from the database Advising_Appointments2 where the student's Id occurs
$sql = "DELETE FROM `Advising_Appointments2` WHERE `studentId` = '$studentId' AND `dateTime` = '$sqlFormatTime'";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);


$_SESSION['showStudentOptionsMessage'] = true;
$_SESSION['studentOptionsMessage'] = "You have successfully canceled an appointment.";
$_SESSION['lastPage'] = 'StudentDeleteDB.php';

header('Location: ValidateStudentSignin.php');

?>
