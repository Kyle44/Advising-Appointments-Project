<?php
session_start();

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: StudentInsertDB.php
File Description: This file directs the student to the correct page,depending on their choice.
*/

$studentsChoice = $_POST['rb_option'];
$createAdvisor = $_POST['sel_createAdvisor'];
$changeAdvisor = $_POST['sel_changeAdvisor'];
$_SESSION['studentChoice'] = $studentsChoice;
$_SESSION['showStudentOptionsMessage'] = false;

if($studentsChoice == 'createGroupAppointment'){
	$_SESSION['studentsCreateAdvisor'] = 'GROUPAP';
	header('Location: StudentCreateAppointment.php');
}

elseif($studentsChoice == 'createIndividualAppointment'){
	//from the advisor selection dropdown
	$_SESSION['studentsCreateAdvisor'] = $createAdvisor;
	header('Location: StudentCreateAppointment.php');
}

if($studentsChoice == 'changeToGroupAppointment'){
	$_SESSION['studentsChangeAdvisor'] = 'GROUPAP';
	header('Location: StudentChangeAppointment.php');
}

elseif($studentsChoice == 'changeToIndividualAppointment'){
	//from the advisor selection dropdown
	$_SESSION['studentsChangeAdvisor'] = $changeAdvisor;
	header('Location: StudentChangeAppointment.php');
}

elseif($studentsChoice == 'viewAppointment'){
	header('Location: StudentViewApts.php');
}

elseif($studentsChoice == 'cancelAppointment'){
	header('Location: StudentDeleteAppointment.php');
}

elseif($studentsChoice == 'changeAppointment'){
	header('Location: StudentChangeAppointment.php');
}

?>
