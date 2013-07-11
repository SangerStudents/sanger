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
<input type="submit" name="submit" value="Rebuild!"/>
</form> 


<?php 
if ($_POST['submit']) { 
      echo '<p>Here we go. Fingers crossed.</p>'; 
      echo '<p>Now emptying database.</p>'; 
      $tables = array('categories', 'correspondence', 'documents', 'documents_category', 'journals', 'materials', 'mentioned_people', 'mentioned_places', 'ricordi_correspondence', 'test_cat'); 
	foreach ($tables as $table) { 
 		 //make a query that truncates each 
	      $emptyQuery = "TRUNCATE TABLE ".$table." ;";  

		if ($_GET['verbose']) { //to enable debugging messages, add ?verbose=TRUE to the URL, after search.php
			echo '<p>Emptying table '.$table.' using query '.$emptyQuery.'.'; 
		}
	      $result=mysql_query($emptyQuery) 
		      or die ("Could not empty database."); 
		if ($_GET['verbose']) { //to enable debugging messages, add ?verbose=TRUE to the URL, after search.php
		}
	} 

      echo '<p>Database now empty. Now attempting to move files.</p>';	
      //following is adapted from http://stackoverflow.com/a/2082164 
	    // Get array of all source files
	    $files = scandir("../../xml_added");
			if ($_GET['verbose']) { //to enable debugging messages, add ?verbose=TRUE to the URL, after search.php
				echo 'Preparing to move files: '.$files; 
			}
	    // Identify directories
	    $source = "../../xml_added/";
	    $destination = "../../xml_queue/";
	    // Cycle through all source files
	    foreach ($files as $file) {
	      if (in_array($file, array(".",".."))) continue;
	      // If we copied this successfully, mark it for deletion
	      if (copy($source.$file, $destination.$file)) {
		$delete[] = $source.$file;
	      }
	    }
	    // Delete all successfully-copied files
	    foreach ($delete as $file) {
	      unlink($file);
	    } 
} 

include("../includes/footer.php");
?>
