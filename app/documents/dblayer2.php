<?php
// MySQL layer
// Revised 2013/3/8 to fix subject index population issue --JR

if (!isset($conid)){
function dbconnect (){
//changing this commented line to the first user. Fingers crossed. -JR
$mysql = @mysql_connect("localhost", "sanger_user", "sangert3st3r!")
	or die ("couldn't connect to the server - please report this problem to <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");
mysql_select_db("sanger");
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
