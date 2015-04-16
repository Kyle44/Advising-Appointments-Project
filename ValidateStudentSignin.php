<?php
session_start();
include('../CommonMethods.php');


//add signin info to session
$_SESSION['fName'] = trim($_POST['fName']);
$_SESSION['lName'] = trim($_POST['lName']);
$_SESSION['major'] = trim($_POST['major']);
$_SESSION['studentEmail'] = trim($_POST['studentEmail']);
$_SESSION['studentId'] = trim($_POST['studentId']);

$studentId = $_SESSION['studentId'];

///////////////////////////////Signin error checking///////////////////////
$_SESSION['signinError'] = false;
//error checking for blank fields
if(strlen($_SESSION['fName'])==0 || strlen($_SESSION['lName'])==0
				||strlen($_SESSION['studentId'])==0
				||strlen($_SESSION['studentEmail']) == 0){
   $_SESSION['signinError'] = true;
}

//error checking for capital letters in f/lname
elseif(!ctype_upper(substr($_SESSION['fName'], 0, 1)) || 
	!ctype_upper(substr($_SESSION['lName'], 0, 1))){
    $_SESSION['signinError'] = true;
}

//error checking for valid email format:
//using regexes.Note: not checking .com,etc
$emailPattern = "/[^@]+@[^@]+/";
if (!preg_match($emailPattern, $_SESSION['studentEmail'])){
	$_SESSION['signinError'] = true;
}

//error checking for valid id format: 7 chars, first 2 capital, last 5 numeric
elseif(strlen($_SESSION['studentId'])!=7 || !ctype_upper(substr($_SESSION['studentId'],0,2))
										|| !is_numeric(substr($_SESSION['studentId'],2,5))){
$_SESSION['signinError'] = true;
}

//go back to signin if error was found
if($_SESSION['signinError'] == true){
 	header('Location: StudentSignin.php');
}
//after this point, successful login


//now, process signin info to determine which options the student can choose////
else{
//I emailed Josh Abrams, and he said the following in response:
/**
Only allow one individual appointment per week, doesn't have to be the same advisor
Earliest: 9:00 for individual
Times can show up: Between Two business days after today and 1 week after today
Latest day in semester to get an appointment for course registration is Apr 30/ Nov 30
Students Typically either do individual or group appointment
	Individual appointment students shouldn't do group later
	Group may do individual after group, in which case, within one week of the individual
		 appointment is fine
*/

	//need to make sure they have an existing apponitment in order to cancel or change
	//to view, a student must have at least one appointment on file, past or present
	//the disabled attribute in html tag will make it unselectable

	//instantiating common to execute queries
	$debug = false;
	$COMMON = new Common($debug);

	//get list of advisors from the db: Advisor_Info
	//key: Id, value: fname lname 
	//	   the ID is what will be stored throughout, but the fname lname will be displayed for the user
	$advisors = array();
	//hold names of advisors for this page
	
	$sql = "SELECT * FROM `Advisor_Info2`";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

	//pushing to advisors array with their id as key
	while($row = mysql_fetch_assoc($rs)){
		$flName = $row['fName']." ".$row['lName'];
		$advisors[$row['employeeId']] = $flName;
	}
	$_SESSION['advisors'] = $advisors;	


	$_SESSION['groupEnabled'] = true;
	$_SESSION['indEnabled'] = true;
	$_SESSION['currentWeekEnabled'] = true;
	$_SESSION['studentHasUpcomingAppointment'] = false;
	$_SESSION['viewEnabled'] = false;

	
	//student can view created apt if at least one instance of their id is in Advisng_Appointments2
	$sql = "SELECT * FROM Advising_Appointments2 WHERE `studentId` = '$studentId'";
	$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
	$row = mysql_fetch_assoc($rs);
	if(!empty($row)){
		$_SESSION['viewEnabled'] = true;
	}	 

	//this moment, now
	$now = date("Y-m-d H:i:s");

	$sql = "SELECT * FROM Advising_Appointments2 WHERE `studentId` = '$studentId' AND `advisorId` = 'GROUPAP'";
	$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
	$row = mysql_fetch_assoc($rs);
	
	//at least one group appointment 
	if(!empty($row)){		
		
		//disable option to sign up for group
		$_SESSION['groupEnabled'] = false;
		$groupEndTime = date("Y-m-d H:i:s",strtotime('+30 minutes',strtotime($row['dateTime'])));
		//checking if now is before the group appointment (there should only be one)

		if($now < $groupEndTime){
			//disable option to sign up for individual
			$_SESSION['indEnabled'] = false;
			$_SESSION['studentHasUpcomingAppointment'] = true;
		}
	}


	//checking if they can sign up for individual
	
	//echo"$studentId<br>";	
	$sql = "SELECT * FROM Advising_Appointments2 WHERE `studentId` = '$studentId' AND `advisorId` != 'GROUPAP'";
	$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);

	$indApps = array();
	
	while($row = mysql_fetch_assoc($rs)){
		array_push($indApps,$row);
	}

	//var_dump($indApps);
	if(!empty($indApps)){
		
		//getting the latest ind apt the student signed up for
		$latestApt = $indApps[0];
		foreach($indApps as $apt){
			if($apt['dateTime'] > $latestApt['dateTime']){
				$latestApt = $apt;
			}
		}
		//now, $latestApt is the latest ind apt the student signed up for
		//end of half-hour session, in sql format
		$latestIndAptEndTime = date("Y-m-d H:i:s", strtotime('+30 minutes', strtotime($latestApt['dateTime'])));
	}

	if($latestIndAptEndTime > $now){
		//don't allow student to sign up for anything if they haven't attended
		//their latest appointment yet
		$_SESSION['indEnabled'] = false;
		$_SESSION['groupEnabled'] = false;
		$_SESSION['studentHasUpcomingAppointment'] = true;
	}


	//don't let student sign up for another ind apt within same week
	//starts off as true, so this is the only way the current week can be disabled
	$thisMonday = date("Y-m-d H:i:s",strtotime("monday this week",strtotime($latestIndAptEndTime)));
	$thisSaturday = date("Y-m-d H:i:s",strtotime("saturday"));
	if($latestIndAptEndTime > $thisMonday && $latestIndAptEndTime < $thisSaturday){
		$_SESSION['currentWeekEnabled'] = false;			
	}
	
	//for testing
	//variables to test: groupEnabled, indEnabled,viewEnabled, currentWeekEnabled
	/**********************
		if($_SESSION['groupEnabled']){
			echo "group is enabled<br>";
		}
		else{
			echo "group is diabled<br>";
		}

		if($_SESSION['indEnabled']){
			echo "ind is enabled<br>";
		}
		else{
			echo "ind is diabled<br>";
		}
		if($_SESSION['viewEnabled']){
			echo "view enabled<br>";
		}
		else{
			echo "view disabled<br>";
		}

		if($_SESSION['currentWeekEnabled']){
			echo "current week is enabled<br>";
		}
		else{
			echo "current week is diabled<br>";
		}

	**********************/
	header('Location: StudentOptions.php');	
}//end else


?>
