<?php
// MySQL layer
// Revised 2013/3/8 to fix subject index population issue --JR

require_once( 'private.php' ); 

if (!isset($conid)){
function dbconnect (){
$mysql = @mysql_connect("localhost", PRIVATE_MYSQL_USER, PRIVATE_MYSQL_PW)
	or die ("couldn't connect to the server - please report this problem to <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");
mysql_select_db(PRIVATE_MYSQL_DB);
return $mysql;
}

function dbquery ($sql){
GLOBAL $conid;
$result=mysql_query($sql,$conid); 
return $result;
}

function dbfetch ($result){
if ($row=mysql_fetch_array($result)){
return $row;
} else {
return false;
};
}

function dbrows ($result){
$num=mysql_num_rows($result);
return $num;
};

function dbfree ($result){
mysql_free_result($result);
}

function dbclose ($conid){
GLOBAL $conid;
mysql_close($conid);
}
$conid=dbconnect();
};
?>
