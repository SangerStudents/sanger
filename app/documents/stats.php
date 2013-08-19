<?php

//script for getting document statistics 

session_start();
include("../includes/header.php");
include("dblayer3.php"); //include my db layer --JR

//debugging
if ($_GET['verbose']) { //to enable debugging messages, add ?verbose=TRUE to the URL, after search.php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}
?>

<h1>Statistics</h1>
<p> </p> 

<?php 
$placesQuery = "SELECT * FROM mentioned_places"; 



include("../includes/footer.php"); ?>
