<?php
session_start();

/*
Name: Nathaniel Baylon
Date:03/21/2015
Class: CMSC331
Project:Advisor Time Selection
File: StudentOptionHeaders.php
File Description: 
This file directs the student to the correct page,depending on their choice.
*/

$studentsDecision = $_POST['rb_option'];
$indAdvisor = $_POST['sel_advisor'];
$_SESSION['studentAction'] = $studentsDecision;

if($studentsDecision == 'createGroupAppointment'){
	$_SESSION['studentsAdvisor'] = 'GROUPAP';
	header('Location: StudentCreateAppointment.php');
}

elseif($studentsDecision == 'createIndividualAppointment'){
	//from the advisor selection dropdown
	$_SESSION['studentsAdvisor'] = $indAdvisor;
	header('Location: StudentCreateAppointment.php');
}

elseif($studentsDecision == 'viewAppointment'){
	header('Location: StudentViewApts.php');//changed from StudentViewAppointments
}

elseif($studentsDecision == 'cancelAppointment'){
	header('Location: StudentCancelAppointment.php');
}

elseif($studentsDecision == 'changeAppointment'){
	header('Location: StudentChangeAppointment.php');
}

?>
