<?php
session_start();
include('../CommonMethods.php');


function withinCurrentWeek($aptTime){
	//check if greater than this monday, less than this friday
	$today = date("Y-m-d H:i:s");
	$thisMonday = date(strtotime("monday this week"));
	$thisFriday = date(strtotime("friday this week"));
	return($today >= $thisMonday && $today <= $thisFriday);
}

$_SESSION['fName'] = trim($_POST['fName']);
$_SESSION['lName'] = trim($_POST['lName']);
$_SESSION['major'] = trim($_POST['major']);
$_SESSION['studentEmail'] = trim($_POST['studentEmail']);
$_SESSION['studentId'] = trim($_POST['studentId']);

$studentId = $_SESSION['studentId'];
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



if($_SESSION['signinError'] == true){
 	header('Location: StudentSignin.php');
}

else{


//What I need to do, is know if a student is assigned an advisor, or if it's free for all.
//And also, know the policy for multiple apponitments.
//I emailed Josh Abrams, and he said the following in response:
/**
Only allow one individual appointment per week, doesn't have to be the same advisor

Earliest: 8:30 or 9:00 for individual

Two business days prior to appointment, 1.5 week after

Latest day in semester to get an appointment for course registration is Apr 30/ Nov 30

Typically either individual or group appointment
	Individual shouldn't do group later
	Group may do individual afterwards, in which case, within one week of the individual appointment is fine

I will figure out the logic for this after I make the options for creating group/individual work out.
Idea: use the 'BETWEEN' thing?

ex:
SELECT * FROM Orders
WHERE OrderDate BETWEEN #07/04/1996# AND #07/09/1996#

*/

	//need to make sure they have an existing apponitment in order to view, cancel, or change
	//the disabled attribute in html tag will make it unselectable

	$debug = false;
	$COMMON = new Common($debug);

	//get list of advisors from the db: Advisor_Info
	//key: Id, value: fname lname 
	//	   the ID is what will be stored throughout, but the fname lname will be displayed for the user
	$advisors = array();
	//hold names of advisors for this page
	
	$sql = "SELECT * FROM `Advisor_Info2`";
	$rs = $COMMON->executeQuery($sql, $_SERVER["SCRIPT_NAME"]);

	while($row = mysql_fetch_assoc($rs)){
		
		$flName = $row['fName']." ".$row['lName'];
		$advisors[$row['employeeId']] = $flName;
	}
	$_SESSION['advisors'] = $advisors;	

	//var_dump($_SESSION['advisors']);

	
	//prevalidation to make only certain options available for the studetnt to choose on the next page.

	//check if student already has group appt
	$_SESSION['groupEnabled'] = true;
	$_SESSION['indEnabled'] = true;
	$_SESSION['currentWeekEnabled'] = true;
	$_SESSION['studentHasUpcomingAppointment'] = false;
	//this moment, now
	$now = date("Y-m-d H:i:s");

	$sql = "SELECT * FROM Advising_Appointments2 WHERE `studentId` = '$studentId' AND `advisorId` = 'GROUPAP'";

	$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);
	$row = mysql_fetch_assoc($rs);
	//var_dump($row);
	//in alg where check if exists 
	if(!empty($row)){
		
		$_SESSION['groupEnabled'] = false;
		
		$groupEndTime = date("Y-m-d H:i:s",strtotime('+30 minutes',strtotime($row['dateTime'])));
		//checking if now is before the group appointment (there will only be one)
		//echo "$now<br>";
		//echo "$groupEndTime<br>";
		if($now < $groupEndTime){
			$_SESSION['indEnabled'] = false;
			//echo "dude<br>";
			$_SESSION['studentHasUpcomingAppointment'] = true;
		}
	}

///////////test: make future group appointment, expected result is ind will be unavail
	///////success
	//checking if they can sign up for individual
		
	$sql = "SELECT * FROM Advising_Appointments2 WHERE `studentId` = '$studentId' AND `advisorId` != 'GROUPAP'";
	$rs = $COMMON->executeQuery($sql, $_SERVER['SCRIPT_NAME']);

	$indApps = array();
	
	while($row = mysql_fetch_assoc($rs)){
		array_push($indApps,$row);
	}

	//var_dump($indApps);
	if(!empty($indApps)){///////////////////////////////dont do empty
		$latestApt = $indApps[0];
		//echo "$latestApt<br>";
		foreach($indApps as $apt){
			if($apt['dateTime'] > $latestApt['dateTime']){
				$latestApt = $apt;
			}
		}
		//now, $latestApt is the latest ind apt the student signed up for
		$latestIndAptEndTime = date("Y-m-d H:i:s", strtotime('+30 minutes', strtotime($latestApt['dateTime'])));
	}
	//echo "latest: $latestIndAptEndTime<br>";
	//echo "now: $now<br>";

	if($latestIndAptEndTime > $now){
		$_SESSION['indEnabled'] = false;
		//$_SESSION['currentWeekEnabled']
		$_SESSION['groupEnabled'] = false;
		$_SESSION['studentHasUpcomingAppointment'] = true;
	}


	$thisMonday = date("Y-m-d H:i:s",strtotime("last monday",strtotime($latestIndAptEndTime)));
	//////////////////////////here, monday. need this week////////////////////////
	$thisSaturday = date("Y-m-d H:i:s",strtotime("saturday this week"));
	//echo "dis mon $thisMonday<br>";
	//echo "dis fri $thisSaturday<br>";//want the day of friday to be valid
	//echo "latest: $latestIndAptEndTime<br>";
	if($latestIndAptEndTime > $thisMonday && $latestIndAptEndTime < $thisSaturday){
		//need to make this function
		//echo "bruh<br>";
			$_SESSION['currentWeekEnabled'] = false;			
		
	}
	////////////////////////current week is messed up//////////////////////////////

	//variables to test: groupEnabled, indEnabled, currentWeekEnabled
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
