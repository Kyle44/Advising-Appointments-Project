<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: AdvisorOptionHeaders.php
File Description: This file directs the student to the correct page,depending on their choice.
*/

session_start();
$advisorsDecision = $_POST['rb_option'];
$advisorView = $_POST['sel_advisor'];
$_SESSION['advisorDecision'] = $advisorsDecision;

if($advisorsDecision == 'selectAppointment'){

	header('Location: AdvisorCreateAppointment.php');
}

elseif($advisorsDecision == 'viewAppointment'){
	//from the advisor selection dropdown
	$_SESSION['advisorView'] = $advisorView;
	header('Location: AdvisorViewApts.php');
}

elseif($advisorsDecision == 'cancelAppointment'){
	header('Location: AdvisorCancelAppointment.php');
}

elseif($advisorsDecision == 'changeAppointment'){
	header('Location: AdvisorChangeAppointment.php');
}

?>
