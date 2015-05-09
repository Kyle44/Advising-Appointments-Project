<?php
/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 5/8/2015
Last Modified: 5/8/15
Class: CMSC331
Project: Project 2
File: AdvisorSearchText.php
File Description: This is the page that will come up only after a search on the AdvisorOptions page was made.
*/
session_start();
include('Proj2Head.html');
include('CommonMethods.php');

$advisorId = $_SESSION['advisorId'];
$advisors = $_SESSION['advisors'];


?>
<div class="form-title">Search<br></div>
<?php


// whatever the session variable for the search was, put it here
$searchStudent = $_SESSION['searchStudentID'];
//echo"$searchStudent <br>";


$debug = false;
$COMMON = new Common($debug);
$sql = "SELECT * FROM `Student_Info2` WHERE `studentId` = '$searchStudent' OR `lName` = '$searchStudent'";
$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

$studentArray = array();


while($row = mysql_fetch_assoc($rs)){
	array_push($studentArray, $row);
}

// Create two arrays, one for past appointments (ones that have already occurred) and one for upcoming appointments.
$pastApts = array();
$upcomingApts = array();
$today = date('Y-m-d H:i:s');
$apts = array();

// For loop to get all of the appointments for a student in one place
foreach($studentArray as $element){
	$studentId = $element['studentId'];
	$sql = "SELECT * FROM `Advising_Appointments2` WHERE `studentId` = '$studentId'";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);
	// row is all of the info for this student
	while($row = mysql_fetch_assoc($rs)){
		array_push($apts, $row);
	}
} // end for

//print_r($apts);

foreach($apts as $elem){
	// put the rows into the arrays
	if($today > $elem['dateTime']){
		array_push($pastApts, $elem);
		//echo"past $elem[dateTime] <br>";
	}
	else{
		array_push($upcomingApts, $elem);
		//echo"upcoming $elem <br>";
	}
} // end for



// ID array for students
$studentIdArray = array();
foreach($studentArray as $student){
	array_push($studentIdArray, $student[studentId]);
}


// Prints all of the appointments for each student
// array_unique used to find unique first names in the studentArray
foreach(array_unique($studentIdArray) as $element){
	foreach($studentArray as $stud){
		if($element == $stud['studentId']){
			echo $stud['fName']." ".$stud['lName']."<br>";
		}
	}
	echo "Upcoming Appointments: <br>";
	foreach($upcomingApts as $elemApts){
		if($element == $elemApts['studentId']){
			echo $elemApts['dateTime']."<br>";
		} // end if
	} // end for
	echo "<br>";


	echo "Past Appointments: <br>";
	foreach($pastApts as $elemApts){
		if($element == $elemApts['studentId']){
			echo $elemApts['dateTime']."<br>";
		} // end if
	} // end for
	echo "<br>";
} // end big for



$_SESSION['lastPage'] = "AdvisorSearchText.php";
include('Proj2Tail.html');
?>
