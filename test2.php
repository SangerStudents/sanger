<?php

//this is a test of parsing URL parameters. 
//this script accepts URL parameters like 
//http://localhost/sanger/app/documents/test2.php?name=Jon&age=not-telling
//and outputs the corresponding values. 
include("../includes/header.php");

//report all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

$mysql = @mysql_connect("localhost", "sanger_user", "sangert3st3r!")
or die ("Can't connect to mySQL for some reason.");
mysql_select_db("sanger"); 

$result=mysql_query("SELECT type FROM doctypes where id=1"); 
if (!$result) { echo "No result. Something went wrong!"; }
else { echo "SQL query result: ".$result.'<br/>'; } 

if ($_GET!=NULL) { 
	print "You passed a parameter, you sly dog!".'<br/>'; 
	print "all parameters: ".join($_GET).'<br/>'; 
	if ($_GET['name']!=NULL) { 
		print "I see you told me your name!".'<br/>'; 
		print "name: ".$_GET['name'].'<br/>'; 
	} 
	if ($_GET['age']!=NULL) { 
		print "I see you told me your age.".'<br/>';  
		print "age: ".$_GET['age'].'<br/>'; 
	} 
} 
else { 
	print "No parameters here. I don't know what you're talking about."; 
} 

//refactor this so that it doesn't need so much repitition. 
//something like this: 

$searchvalues = array("subject"); //only this one for now
foreach ($searchvalues as $param) { 
	if ($_GET!=NULL) { //make sure there is at least one parameter 
		if ($_GET[$param]!=NULL) { //check to make sure the parameter exists
			$query = "SELECT * where subject is ".$_GET[$param]; //this is a dummy mysql query. 
		} 
	} 
} 
?>
