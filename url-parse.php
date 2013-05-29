<?php

include("../includes/header.php");

//report all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

$mysql = @mysql_connect("localhost", "sanger_user", "sangert3st3r!")
or die ("Can't connect to mySQL for some reason.");
mysql_select_db("sanger"); 

$result=mysql_query("SELECT type FROM doctypes where id=1"); 
if (!$result) { echo "No result. Something went wrong!"; }
else { echo "Drum roll please: ".$result ; } 

?>
