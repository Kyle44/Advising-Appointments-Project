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


<div class="intro">

<img class="mascot" src="picture/TrueGrit.jpg" alt="UMBC Mascot" align="right">

<div class="description"> Introduction </div>

This project is an Advisor/Student website that will allow Advisors as well as Students to create 
their schedules with ease. Working on this project allowed us to work first hand with other students
and allowed us to know where our weaknesses and strengths lay when working in a team of developers.


</div>

<div class="content">
<div class="description"> Project Description </div>
At the bottom of the screen, you can sign in as either an advisor or a student.  When you sign in to your account as an advisor, you can choose to create as many
appointments for students as possible, view who has chosen to come to your appointments, cancel any appointments where you have something else come up, reschedule 
appointments for students with any advisor, schedule an appointment for a new or existing student, and search for any student to see their past and upcoming
appointments.  When you sign in as a student, you can choose any available appointments, but you can only have one scheduled appointment at a time.  You have the
ability to change or cancel your upcoming appointment, as well as view your past and upcoming appointments.
</div>


<form action='AdvisorSignin.php' name='advisor'>
<input type='submit' value ='Advisor Sign-In'>
</form>
<form action='StudentSignin.php' name='student'>
<input type='submit' value ='Student Sign-In'>
</form>

<?php
$_SESSION['lastPage'] = "index.php";
include('Proj2Tail.html');
?>
