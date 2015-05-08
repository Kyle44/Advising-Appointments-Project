<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: ValidateAdvisorSignin.php
File Description: Validation for student signin
*/

session_start();
include('CommonMethods.php');

//add signin info to session
$_SESSION['advisorId'] = trim($_POST['advisorId']);
$advisorId = $_SESSION['advisorId'];

///////////////////////////////Signin error checking///////////////////////
$_SESSION['signinError'] = false;

//error checking for blank fields
if(strlen($_SESSION['advisorId'])==0){
   $_SESSION['signinError'] = true;
}

//error checking for valid id format: 7 chars, first 2 capital, last 5 numeric
elseif(strlen($_SESSION['advisorId'])!=7 || !ctype_upper(substr($_SESSION['advisorId'],0,2))
										|| !is_numeric(substr($_SESSION['advisorId'],2,5))){
$_SESSION['signinError'] = true;
}

//instantiating common to execute queries
$debug = false;
$COMMON = new Common($debug);
//hold names of advisors for this page
$advisors = array();
$sql = "SELECT * FROM `Advisor_Info2`";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

//pushing to advisors array with their id as key
while($row = mysql_fetch_assoc($rs)){
	$flName = $row['fName']." ".$row['lName'];
	$advisors[$row['employeeId']] = $flName;
}

//if the key does not exist, create an error
if (!array_key_exists($advisorId , $advisors))
{
	$_SESSION['signinError'] = true;
}

$_SESSION['advisors'] = $advisors;	

//go back to signin if error was found
if($_SESSION['signinError'] == true)
{
 	header('Location: AdvisorSignin.php');
}
else
{
	header('Location: AdvisorOptions.php');
}
?>
