<?php
include("../includes/header.php");
$host="localhost";
$database="sanger";
$connection = @mysql_connect($host, "sanger_user", "sangert3st3r!")
	or die ("couldn't connect to the server - please report this problem to <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");
$db = @mysql_select_db($database, $connection)
	or die ("couldn't select database - please report this problem to <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");

?>
    <p class="docTitle">Document Links</p>
	<?php
	$query="select filename, title from documents";
	$result=mysql_query($query)
	or die ("Query \"$query\" failed");
	$num_results = @mysql_num_rows ($result);
	if ($num_results) {
		$resultCounter = 0;
		echo "<OL>";
		while ($row  = mysql_fetch_array($result)) {
			$resultCounter++;
			extract($row);
			echo "<LI VALUE=\"$resultCounter\"><b><a href='show.php?sangerDoc=$filename'>$title</a></b></li>\n";
		}
		echo "</OL>";
	}
/*	$Open = opendir ("../../sanger_xml");
	while ($Files = readdir ($Open)) {
		if (!(eregi("^\.", $Files))) {
			print "<li><a href='show.php?sangerDoc=";
			print "$Files"."'>";
			print "Document $Files"."</a></li>\n";
		}
	}*/
	?>
<!--    </ul>-->
<?php
include("../includes/footer.php");
?>
