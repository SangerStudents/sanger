<?php
session_start();
include("../includes/header.php");
include("dblayer3.php"); //include my db layer --JR

//debugging
if ($_GET['verbose']) { //to enable debugging messages, add ?verbose=TRUE to the URL, after search.php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}
?>

<h1>This script will completely erase and rebuild the database.<br/> 
Make sure to back it up first!</h1>
<p> </p> 
<form action="<?php $PHP_SELF ?>" method="post" id="search1">  
<input type="submit" name="submit" value="Search!"/>
</form> 


<?php 
if ($_POST['submit']) { 
      echo '<p>Here we go. Fingers crossed.</p>'; 
      echo '<p>Now emptying database.</p>'; 
      $tables = ['test_cat', 'categories', 'journals', '']; 
	foreach $tables as $table { 
 		 //make a query that truncates each 
	} 
      $emptyQuery = "TRUNCATE TABLE" 
      
} 

include("../includes/footer.php");
?>
