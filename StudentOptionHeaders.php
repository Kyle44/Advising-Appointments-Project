<?php
session_start();

$studentsDecision = $_POST['rb_option'];
$indAdvisor = $_POST['sel_advisor'];
$_SESSION['studentAction'] = $theDecision;

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
	header('Location: StudentViewAppointment.php');
}

elseif($studentsDecision == 'cancelAppointment'){
	header('Location: StudentViewAppointment.php');
}

elseif($studentsDecision == 'changeAppointment'){
	header('Location: StudentChangeAppointment.php');
}

?>
