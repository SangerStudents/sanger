<?php
// MySQL layer
if (!isset($conid)){
function dbconnect (){
$mysql=mysql_connect("localhost", "sanger_user2", "sanger2t3st3r!") or die("Woe is me! Can't connect to mySQL server!"); 
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
