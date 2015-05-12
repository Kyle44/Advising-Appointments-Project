<?php
/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 5/8/2015
Last Modified: 5/11/15
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

$pastAptsLen = 0;
$upcomingAptsLen = 0;


foreach($apts as $elem){
	// put the rows into the arrays
	if($today > $elem['dateTime']){
		$elem['dateTime'] = strtotime($elem['dateTime']);
		$elem['dateTime'] = date('m-d-Y @ h:i A', $elem['dateTime']);
		array_push($pastApts, $elem);
		$pastAptsLen++;
	}
	else{
		$elem['dateTime'] = strtotime($elem['dateTime']);
		$elem['dateTime'] = date('m-d-Y h:i A', $elem['dateTime']);
		array_push($upcomingApts, $elem);
		$upcomingAptsLen++;
	}
} // end for



// ID array for students
$studentIdArray = array();
foreach($studentArray as $student){
	array_push($studentIdArray, $student[studentId]);
}


// Prints all of the appointments for each student
// array_unique used to find unique ids in the studentArray
foreach(array_unique($studentIdArray) as $element){
	// create the table 
		?>
		<table border = "3">
		<!--caption defined right after table tag-->
		<!--easy way to get the caption (or any html tag) to include a php variable below:-->
		<?php 
		echo"<caption>$userFormatDate</caption>";
		?>
		<tr>
			<th>Student</th>
			<th>Email</th>
			<th>Major</th>
			<th>Student Id</th>

		<tr>
	<?php
	foreach($studentArray as $stud){
		if($element == $stud['studentId']){
			echo "<tr>";	
				echo "<td> $stud[fName] $stud[lName] </td>";
				echo "<td>$stud[studentEmail]</td>";
				echo "<td>$stud[major]</td>";
				echo "<td>$stud[studentId]</td>";
			echo"</tr>";
		}
	}
	echo"</table>";
	echo "<br>";

$count = 0;
foreach($upcomingApts as $elemApts){
	if($element == $elemApts['studentId']){
		if($count == 0){
			echo "Upcoming Appointments";
			// create the table 
			?>
			<table border = "3">
			<!--caption defined right after table tag-->
			<!--easy way to get the caption (or any html tag) to include a php variable below:-->
			<?php 
			$count = 1;
			?>
			<tr>
				<th>Time</th>
				<th>Advisor</th>
			<tr>
			<?php
		} // end if
		echo "<tr>";	
			echo "<td> $elemApts[dateTime] </td>";
			echo "<td> $elemApts[advisorId] </td>";
		echo"</tr>";

	

	} // end for
	echo"</table>";
	echo "<br>";
} // end if

$count = 0;
foreach($pastApts as $elemApts){
	if($element == $elemApts['studentId']){
		if($count == 0){
			echo "Past Appointments";
			// create the table 
			?>
			<table border = "3">
			<!--caption defined right after table tag-->
			<!--easy way to get the caption (or any html tag) to include a php variable below:-->
			<?php 
			$count = 1;
			?>
			<tr>
				<th>Time</th>
				<th>Advisor</th>
			<tr>
			<?php
		} // end if
		echo "<tr>";	
			echo "<td> $elemApts[dateTime] </td>";
			echo "<td> $elemApts[advisorId] </td>";
		echo"</tr>";
	} // end if
} // end for
echo"</table>";
echo "<br>";
echo "<br>";
} // end big for


?>
<form action='AdvisorOptions.php' name='AVAtoAOptions'>
	<!--Go Back button-->
	<input type='submit' value='Go Back'>
	<!-- End of form  -->
	</form>

<?php

$_SESSION['lastPage'] = "AdvisorSearchText.php";
include('Proj2Tail.html');
?>
