<?php

$host="localhost";
$database="sanger";
$connection = @mysql_connect($host, "sanger_user", "sangert3st3r!")
	or die ("couldn't connect to the server - please report this problem to <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");
$db = @mysql_select_db($database, $connection)
	or die ("couldn't select database - please report this problem to <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");

$display_number = 40;
	

if(!isset($_GET[num_pages])) {
	$query="select distinct filename, title, date, type from documents, doctypes where documents.doctype=doctypes.id order by title";


	$result = mysql_query($query)
	or die ("An error has occured.  Please contact the <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");
	$num_results = @mysql_num_rows ($result);
	
	if($num_results > $display_number) {
		$num_pages = ceil($num_results/$display_number);
	}
	else if($num_results > 0) {
		$num_pages = 1;
	}
	else {
		echo "<h3 class=\"center\">No records currently exist in the database.</h3>";
	}
	$start=0;
}
else {
	$start = $_GET[start];
	$num_pages=$_GET[num_pages];
}

$query2 = "select distinct filename, title, date, type from documents, doctypes where documents.doctype=doctypes.id order by title LIMIT $start, $display_number";

$result2 = mysql_query($query2)
or die ("An error has occured.  Please contact the <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");

include("../includes/header.php");
			echo "<h2>The database currently contains the following titles:</h2>\n";
if($num_pages>1) {
echo "<div>";
	if($start ==0) {
		$current_page = 1;	
	}
	else {
		$current_page = ($start/$display_number) + 1;
	}
	
	if($start!=0) {
		echo '<a href="'.$PHP_SELF.'?start='.($start - $display_number) . '&num_pages='.$num_pages.'">Previous</a>';
	}
	

	for($i=1;$i<=$num_pages;$i++) {
		$next_start=$start+$display_number;
		if($i!=$current_page) {
			echo '<a href="'.$PHP_SELF.'?start='.(($display_number*($i-1))).'&num_pages='.$num_pages.'">'.$i.'</a> ';	
		}
		else {
			echo $i.' ';
		}
	}
	
	if($current_page!=$num_pages) {
		echo '<a href="'.$PHP_SELF.'?start='.($start+$display_number).'&num_pages='.$num_pages.'">Next</a>';
	}

	echo "</div>";

}
		
	

			echo "<OL>";
			$tempDate = "";
			$resultCounter = $start;
			while ($row = mysql_fetch_array($result2))
			{	
				$resultCounter++;
				extract($row);
				//				$tempDate = strtotime($date);
				if($resultCounter != $start+1) {
					echo "<br />&nbsp;\n";
				}
				echo "<LI VALUE=\"$resultCounter\"><b><a href='show.php?sangerDoc=$filename'>$title</a></b></LI>\n";
				echo "<span class=\"resultSubLine\">";
				echo $date;
				//				echo date ("F d, Y",$tempDate);
				echo " &nbsp;($type)";
				echo "</span>\n";
			}
		
			echo "</OL>";
			
if($num_pages>1) {
echo "<div>";
	if($start ==0) {
		$current_page = 1;	
	}
	else {
		$current_page = ($start/$display_number) + 1;
	}
	
	if($start!=0) {
		echo '<a href="'.$PHP_SELF.'?start='.($start - $display_number) . '&num_pages='.$num_pages.'">Previous</a>';
	}
	

	for($i=1;$i<=$num_pages;$i++) {
		$next_start=$start+$display_number;
		if($i!=$current_page) {
			echo '<a href="'.$PHP_SELF.'?start='.(($display_number*($i-1))).'&num_pages='.$num_pages.'">'.$i.'</a> ';	
		}
		else {
			echo $i.' ';
		}
	}
	
	if($current_page!=$num_pages) {
		echo '<a href="'.$PHP_SELF.'?start='.($start+$display_number).'&num_pages='.$num_pages.'">Next</a>';
	}

	echo "</div>";

}
include("../includes/footer.php");
?>
