<?php

require_once( 'private.php' ); 

$host="localhost";
$database=PRIVATE_MYSQL_DB;
$connection = @mysql_connect($host, PRIVATE_MYSQL_USER, PRIVATE_MYSQL_PW);
if (!$connection) {
  fwrite ($filepointer, "\n<p>couldn't connect to the mysql server - please report this problem to <a href='mailto:humanities.computing@nyu.edu'> the administrator</a> immediately.</p>\n");
  fclose ($filepointer);
  die();
} 

?>
