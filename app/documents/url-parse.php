<?php

include("../includes/header.php");

//report all errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

include("dblayer3.php"); 

$result=mysql_query("SELECT type FROM doctypes where id=1"); 
if (!$result) { echo "No result. Something went wrong!"; }
else { echo "Drum roll please: ".$result ; } 

?>
