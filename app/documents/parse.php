<?php
/*$filepointer;*/


if(1==7) {
/* connect to database */
$host="localhost";
$database="sanger";
$connection = @mysql_connect($host, "sanger_user2", "sanger2t3st3r!");
if (!$connection) {
  fwrite ($filepointer, "\n<p>couldn't connect to the mysql server - please report this problem to <a href='mailto:humanities.computing@nyu.edu'> the administrator</a> immediately.</p>\n");
  fclose ($filepointer);
  die();
}

$db = @mysql_select_db($database, $connection)
     or die ("couldn't select database - please report this problem to <a href='mailto:humanities.computing@nyu.edu'>the administrator</a>immediately.");

	$filePath=trim($_POST[folderName]);
	$viewFile=trim($_POST[fileName]);

	include("../includes/header.php");
	echo "		<h2>Document Processing Results</h2> ";
	$currTime=time();
	echo "<br />Read the <a href='log.php#".$currTime."'>log</a> for history or <a href='parse.php'>go back</a> to the parse interface.";	
	$logfile= "log.php";
	$filepointer= fopen ($logfile, "a");
	$header="<a name=\"".$currTime."\"></a><hr />\n<h3>".date("F j, Y, g:i a",$currTime)."</h3>\n";
	echo $header;
	fwrite ($filepointer, $header);
	
	$indexCounter=1;
	
	if(strcmp($viewFile,"A-L-L_F-I-L-E-S")==0) {
		$Open = opendir ($filePath);
		
		while ($Files = readdir ($Open)) {
			if (!(eregi("^\.", $Files))) {
			  processFile($Files,$filepointer);
			}
		}
	}
	else {
		processFile($viewFile,$filepointer);
	}
	  
	fclose ($filepointer);
	include("../includes/footer.php");
	
}
else {
	$dir_path="../../xml_queue/";
	if(1==7) {
		$viewFile=trim($_POST[fileName]);
		if(strcmp($viewFile,"A-L-L_F-I-L-E-S")!=0) {
?>
			<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
					"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
			<html>
			<head>
				<title>The Public Papers of Margaret Sanger: Web Edition</title> 
				<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
				<link href="../documents/styles.css" rel="stylesheet" type="text/css" />
			<script type="text/javascript" src="../documents/global.js"></script> 
			<script type="text/javascript">
			function windopen() {
<?php
				echo "var w=window.open('".$dir_path.$viewFile."','XML');";
?>
			}
			</script>
			</head>
<?php
			echo "<body onLoad=\"windopen();MM_preloadImages('../images/header_r2_c1_f2.gif','../images/header_r2_c2_f2.gif','../images/header_r2_c3_f2.gif','../images/header_r2_c4_f2.gif','../images/header_r2_c5_f2.gif');\">";
?>
			<p style="margin-top: 0px; margin-bottom: 0px;">
				<img id="header_r1_c1" src="../images/header_r1_c1.gif" width="625" height="95" alt="" /><img src="../images/spacer.gif" width="1" height="95" alt="" /> 
			</p>
			<p style="margin-top: 0px; /*\*/margin-top: -3px;/**/">
				<a href="#" onmouseout="MM_swapImgRestore();" onmouseover="MM_swapImage('header_r2_c1','','../images/header_r2_c1_f2.gif',1);"><img id="header_r2_c1" src="../images/header_r2_c1.gif" width="104" height="20" alt="" /></a><a href="#" onmouseout="MM_swapImgRestore();" onmouseover="MM_swapImage('header_r2_c2','','../images/header_r2_c2_f2.gif',1);"><img id="header_r2_c2" src="../images/header_r2_c2.gif" width="104" height="20" alt="" /></a><a href="#" onmouseout="MM_swapImgRestore();" onmouseover="MM_swapImage('header_r2_c3','','../images/header_r2_c3_f2.gif',1);"><img id="header_r2_c3" src="../images/header_r2_c3.gif" width="104" height="20" alt="" /></a><a href="#" onmouseout="MM_swapImgRestore();" onmouseover="MM_swapImage('header_r2_c4','','../images/header_r2_c4_f2.gif',1);"><img id="header_r2_c4" src="../images/header_r2_c4.gif" width="104" height="20" alt="" /></a><a href="#" onmouseout="MM_swapImgRestore();" onmouseover="MM_swapImage('header_r2_c5','','../images/header_r2_c5_f2.gif',1);"><img id="header_r2_c5" src="../images/header_r2_c5.gif" width="104" height="20" alt="" /></a><img id="header_r2_c6" src="../images/header_r2_c6.jpg" width="105" height="20" alt="" /><img src="../images/spacer.gif" width="1" height="20" alt="" /> 
			</p>
			<div class="outBox">
				<div class="inBox">
<?php		
		}
		else {
			include("../includes/header.php");
		}
	}
	else {
		include("../includes/header.php");
	}

echo "
	<h2>Process XML Documents</h2>";
	
	$Open = opendir($dir_path);
	$fileCount = 0;
echo "	<p>The following files are on queue to be processed:
	
	<form action=\"parse2.php\" method=\"post\">
	<select name=\"fileName\" id=\"fileName\" height=\"5\">
	<option value=\"A-L-L_F-I-L-E-S\">ALL FILES</option>";
	

	while ($Files = readdir ($Open)) {
		if (!(eregi("^\.", $Files))) {
			$fileCount++;
			print "<option value=\"".$Files."\">";
			print "$Files"."</option>\n";
		}
	}
	
	echo "</select> ($fileCount total)";
	echo "<input type=\"hidden\" name=\"folderName\" value=\"".$dir_path."\">";
?>
	<br/><br/>What would you like to do?<br /><br/>
	<input type="submit" name="parse" value="Parse Selected">
	<input type="submit" name="view" value="View Selected">
	<br/><br/> Please make sure to turn off the pop-up blocker before  viewing the xml files. <br /><br />
	</form>
	</p>
	<p>You may view a log of previous batch processes <a href="log.php">here</a>.</p>
	
<?php
	include("../includes/footer.php");
}

?>
