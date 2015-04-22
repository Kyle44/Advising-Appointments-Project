<?php

/*
Name: Nathaniel Baylon, Tommy Tran, Kyle Fritz
Date: 03/29/2015
Class: CMSC331
Project: Project 2
File: index.php
File Description: This is the welcome page, where the user can choose either to start an
advisor session, or a student session 
*/

session_start();
session_unset();
include('Proj2Head.html');
?>

<!--links-->

<div class="one-third-nav"></div>
<div class="two-third-nav">
<div class="index-advisor-nav">
<div class="nav-div"><a class="nav" href="AdvisorSignin.php">Advisor</a></div>
</div>
<div class="index-student-nav">
<div class="nav-div"><a class="nav" href="StudentSignin.php">Student</a></div>
</div>
</div>
<div class="three-third-nav"></div>
<div class="intro">

<img class="mascot" src="picture/TrueGrit.jpg" alt="UMBC Mascot" align="right">

<div class="description"> Introduction </div>

This project is an Advisor/Student website that will allow Advisors as well as Students create 
their schedules with ease. Working on this project allowed us to work first hand with other students
and allowed us to know where our weaknesses and strengths lay when working in a team of developers.
Oh and by the way Nat, your code is really good. Like really really good. I can tell you put alot of time
and effort into learning these languages and it shows. You're doing a good job! Like really really good. I can tell you put alot of time
and effort into learning these languages and it shows. You're doing a good job! Like really really good. I can tell you put alot of time
and effort into learning these languages and it shows. You're doing a good job! Like really really good. I can tell you put alot of time
and effort into learning these languages and it shows. You're doing a good job!

</div>

<div class="content">
<div class="description"> Project Description </div>
This portion will give a brief explanation of our project and how its used. It will allow the user to not
be confused and to have an expectation going in when using this. This portion will give a brief explanation of our project and how its used. It will allow the user to not
be confused and to have an expectation going in when using this. This portion will give a brief explanation of our project and how its used. It will allow the user to not
be confused and to have an expectation going in when using this. This portion will give a brief explanation of our project and how its used. It will allow the user to not
be confused and to have an expectation going in when using this.
</div>


<?php
$_SESSION['lastPage'] = "index.php";
include('Proj2Tail.html');
?>
