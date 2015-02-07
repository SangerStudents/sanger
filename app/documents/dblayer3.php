<?php 

require_once( 'private.php' ); 

$host="localhost";
$database=PRIVATE_MYSQL_DB;
$connection = @mysql_connect($host, PRIVATE_MYSQL_USER, PRIVATE_MYSQL_PW)
		or die ("couldn't connect to the server - please report this problem to <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");
$db = @mysql_select_db($database, $connection)
		or die ("couldn't select database - please report this problem to <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");

?> 
