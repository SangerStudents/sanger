<?php
session_start();
include("../includes/header.php");

//debugging
error_reporting(E_ALL);
ini_set('display_errors', '1');

$host="localhost";
$database="sanger";
$connection = @mysql_connect($host, "sanger_user", "sangert3st3r!")
	or die ("couldn't connect to the server - please report this problem to <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");
$db = @mysql_select_db($database, $connection)
	or die ("couldn't select database - please report this problem to <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");

//function redo_alphabetize($titlesArray) { 
	//this function accepts an mysql result array of $id, $title from the database journals, 
	//puts the words "the" and "a" at the ends of journal titles, 
	//then resorts the array according to the journals' second words
 //     $newTitlesArray = array(); //container for transformed titles 
  //    while ($row3a = mysql_fetch_array($titlesArray) { 
//	      extract($row3a);
//		$splitTitle = explode(' ',$title); 
//		if ($splitTitle[0] == "The") { 
//		        unset($splitTitle[0]); //remove "The" 	
//			$title = join($splitTitle).", The"; //put it at the end
//		} 
//		if ($splitTitle[0] == "A") { 
//		        unset($splitTitle[0]); //remove "A" 
//			$title = join($splitTitle).", A"; //put it at the end 
//		}
//		$newTitlesArray.= compact(id, title);  
//		echo "$id, $title<br/>";
 //    } 
  //   return $newTitlesArray; 
//} 

if(!$_POST[submit1] && !$_POST[submit2] && !isset($_GET[num_pages])) { //this is the input page 
	session_unset();
	$query2a="SELECT `id` FROM `categories`";
	$result2a= mysql_query($query2a)
		or die ("could not execute query # 2a");
	$query3a="SELECT `id`, `title` FROM `journals` order by `title`";
	$result3a= mysql_query($query3a)
		or die ("could not execute query # 3a");

//	$result3a = redo_alphabetize($result3a);  	


        echo "<br />                                                                                                                                                                                        
                <div style=\"font-size: 16px; font-weight: bold\">Search the Web Edition</div>                                                                                                              
(<a href=\"list.php\">list all records</a>)<br />  		<br />
		<h3>Basic Search</h3> 
		<form action=\"$PHP_SELF\" method=\"post\" id=\"search1\">
			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td class=\"searchLabelCell\">
						&nbsp; 
					</td>
					<td>
						<input name=\"body1\" type=\"text\" id=\"body1\" /> 
					</td>
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
					<td>
						<i>(search every part of the documents)</i> 
					</td>
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr>
					<td class=\"searchLabelCell\">
						&nbsp; 
					</td>
					<td>
						<input type=\"submit\" name=\"submit1\" value=\"Search!\" /> 
					</td>
				</tr>
			</table>
		</form>
		<hr />
		<h3>Advanced Search</h3> 
		<form action=\"$PHP_SELF\" method=\"post\" id=\"search2\">
			<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
				<tr>
					<td class=\"searchLabelCell\">
						<b>title: &nbsp;</b> 
					</td>
					<td>
						<input name=\"title\" type=\"text\" id=\"title\" /> 
					</td>
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
					<td>
						<i>(search through all of the document titles)</i> 
					</td>
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr>
					<td class=\"searchLabelCell\">
						<b>full text: &nbsp;</b> 
					</td>
					<td>
						<input name=\"body2\" type=\"text\" id=\"body2\" /> 
					</td>
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
					<td>
						<i>(search through the body of the documents)</i> 
					</td>
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr>
					<td class=\"searchLabelCell\">
						<b>date: &nbsp;</b> 
					</td>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr>
					<td class=\"searchLabelCell\">
						<i>on/from</i>: &nbsp; 
					</td>
					<td>
						<select name=\"month1\" id=\"month1\"> 
							<option value=\"\">
								&nbsp; 
							</option>
							<option value=\"01\">
								January 
							</option>
							<option value=\"02\">
								February 
							</option>
							<option value=\"03\">
								March 
							</option>
							<option value=\"04\">
								April 
							</option>
							<option value=\"05\">
								May 
							</option>
							<option value=\"06\">
								June 
							</option>
							<option value=\"07\">
								July 
							</option>
							<option value=\"08\">
								August 
							</option>
							<option value=\"09\">
								September 
							</option>
							<option value=\"10\">
								October 
							</option>
							<option value=\"11\">
								November 
							</option>
							<option value=\"12\">
								December 
							</option>
						</select> 
						<select name=\"day1\" id=\"day1\"> 
							<option value=\"\">
								&nbsp; 
							</option>";
	for($i=1;$i<=31;$i++) {
		echo "<option value=\"$i\">
		$i 
		</option>\n";
	}
	echo "						</select> <select name=\"year1\" id=\"year1\"> 
							<option value=\"\">
								&nbsp; 
							</option>";
	for($i=1911;$i<=1960;$i++) {
		echo "<option value=\"$i\">
		$i 
		</option>\n";
	}
	echo "						</select> 
					</td>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr>
					<td class=\"searchLabelCell\">
						<i>through</i>: &nbsp; 
					</td>
					<td>
						<select name=\"month2\" id=\"month2\"> 
							<option value=\"\">
								&nbsp; 
							</option>
							<option value=\"01\">
								January 
							</option>
							<option value=\"02\">
								February 
							</option>
							<option value=\"03\">
								March 
							</option>
							<option value=\"04\">
								April 
							</option>
							<option value=\"05\">
								May 
							</option>
							<option value=\"06\">
								June 
							</option>
							<option value=\"07\">
								July 
							</option>
							<option value=\"08\">
								August 
							</option>
							<option value=\"09\">
								September 
							</option>
							<option value=\"10\">
								October 
							</option>
							<option value=\"11\">
								November 
							</option>
							<option value=\"12\">
								December 
							</option>
						</select> 

						<select name=\"day2\" id=\"day2\"> 
							<option value=\"\">
								&nbsp; 
							</option>";
	for($i=1;$i<=31;$i++) {
		echo "<option value=\"$i\">
		$i 
		</option>\n";
	}
	echo "
						</select> <select name=\"year2\" id=\"year2\"> 
							<option value=\"\">
								&nbsp; 
							</option>";
	for($i=1911;$i<=1960;$i++) {
		echo "<option value=\"$i\">
		$i 
		</option>\n";
	}
	echo "						</select> 
					</td>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
					<td>
						<i>(search on one date or from a range of dates)</i> 
					</td>
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr>
					<td class=\"searchLabelCell\">
						<b>document type: &nbsp;</b> 
					</td>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr>
					<td class=\"searchLabelCell\">
						<i>1</i>: &nbsp; 
					</td>
					<td>
						<select name=\"doctype2\" style=\"width: 200px;\"> 
							<option value=\"\">
								&nbsp; 
							</option>
							<option value=\"article\">
								article 
							</option>
							<option value=\"document\">
								document 
							</option>
							<option value=\"interview\">
								interview 
							</option>
							<option value=\"review\">
								review 
							</option>
							<option value=\"speech\">
								speech 
							</option>
							<option value=\"statement\">
								statement 
							</option>
							<option value=\"testimony\">
								testimony 
							</option>
						</select> 
					</td>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr>
					<td class=\"searchLabelCell\">
						<i>2</i>: &nbsp; 
					</td>
					<td>
						<select name=\"doctype1\" style=\"width: 200px;\"> 
							<option value=\"\">
								&nbsp; 
							</option>
							<option value=\"Autograph\">
								Autograph 
							</option>
							<option value=\"Autograph draft\">
								Autograph draft 
							</option>
							<option value=\"Published\">
								Published 
							</option>
							<option value=\"Typed\">
								Typed 
							</option>
							<option value=\"Typed draft\">
								Typed draft 
							</option>
						</select> 
					</td>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
					<td>
						<i>(find documents of particular types)</i> 
					</td>
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr>
					<td class=\"searchLabelCell\">
						<b>journal title: &nbsp;</b> 
					</td>
					<td>
						<select name=\"journal\" style=\"width: 400px;\"> 
							<option value=''></option>\n
";
				  
	 while ($row3a = mysql_fetch_array($result3a)) //this is the part that populates the journal titles list

	{
		extract($row3a);
		//insert way to move "the" and "a" to ends of titles and resort
		echo "							<option value='$id'>$title</option>\n";

	}
	
	echo "
						</select>
					</td>
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
					<td>
						<i>(find documents from a particular journal)</i> 
					</td>
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr>
					<td class=\"searchLabelCell\">
						<b>subject index: &nbsp;</b> 
					</td>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr>
					<td class=\"searchLabelCell\">
						&nbsp; 
					</td>
					<td>";
		include("ultra.php");			
echo "					</td>
					</tr>
				<tr>
						<td>
							&nbsp; 
						</td>
						<td>
							<i>(search within a specific subject index)</i> 
						</td>
				</tr>
				<tr>
						<td>
							&nbsp;
						</td>
				</tr>
				<tr>
						<td class=\"searchLabelCell\">
							&nbsp;
						</td>
						<td>
							<input type=\"submit\" name=\"submit2\" value=\"Search!\" />
							<input type=\"reset\" name=\"reset\" value=\"Reset\" /> 
						</td>
				</tr>
			</table>
		</form>
			";
}
else {

	$display_number = 40;
	echo "<h2>Search Results:</h2>\n";

	if($_POST[submit1] || $_POST[submit2] || $_GET) { //this is the output page
		$_SESSION['search_values'] = "<div>You searched using the following values:\n<ul>";
	
		$query="";
		
		/* Query database */
		
		if($_POST[submit1]) {
		  $body = trim($_POST[body1]);
		}
		else {
		  $body = trim($_POST[body2]);
		  $title = trim($_POST[title]);	
		  $doctype1 = trim($_POST[doctype1]);
		  $doctype2 = trim($_POST[doctype2]);
		  $doctype_test = trim($doctype1 . " " . $doctype2);
		  $journal = trim($_POST[journal]);
		  $category = $_POST[category];
		  $year1 = $_POST[year1];
		  $month1 = $_POST[month1];
		  $day1 = $_POST[day1];
		  $year2 = $_POST[year2];
		  $month2 = $_POST[month2];
		  $day2 = $_POST[day2];
		}		
		
		//Query the doctype database
		
	
		if($doctype1 || $doctype2)
		{
			$_SESSION['search_values'].="<li>document type=\"$doctype_test\"</li>"; 
			/* Get the IDs of all of the doctypes that include the indicated keyword(s) */
			$query_doctype = "SELECT id FROM `doctypes` WHERE type LIKE '%$doctype_test%'";
			$result_doctype = mysql_query($query_doctype);
			$error = mysql_error();
			echo $error;
			$num_rows = @mysql_num_rows($result_doctype);
			/* If any doctype IDs are returned, put them all into a set string bounded by () */
			if($num_rows) { 
				/* This var will help us deal with commas between doctypes */
				$addedFirstType = 0;
				$doctype_set = "(";
				while($row = mysql_fetch_array($result_doctype)) {
					extract($row);
					/* The first added doctype doesn't need a comma at all */
					if($addedFirstType == 0) {
						$doctype_set .= $id;
						$addedFirstType = 1;
					}
					/* All subsequently added doctypes should be preceeded by commas */	
					else {
						$doctype_set .= ", " . $id;
					}
				}
				/* Close it up! */
				$doctype_set .= ")";
			}
		}
	
		$query .= "select distinct filename, title, date, type from documents, doctypes";
		if(sizeof($category)>0) {
			for($i=0;$i<sizeof($category);$i++) {
				$query .= ", documents_category AS docs_cat".$i;
			}
		}
	
		$query.=" ";
	
		/* If the user specified a title, then include that in the query */	
		if($title) {
			$_SESSION['search_values'].="<li>title=\"$title\"</li>"; 
	
			$query .= "where title like \"%$title%\" ";
		}
		if($body) {
			$_SESSION['search_values'].="<li>full text=\"$body\"</li>"; 
	
			if($title) {
				$query .= "and ";
			}
			else {
				$query .= "where ";
			}
			$query .= "MATCH (title, body) AGAINST ('$body' IN BOOLEAN MODE) ";
		}
		/* If the user specified a doctype, then include that in the query */
		if($doctype_set) {
			/* If a title preceeded the doctype set, then add "and" to the query */
			if($title || $body) {
				$query .= "and ";
			}
			/* If doctype is first in the query, then we need to start it off with 
			"where" */
			else {
				$query .= "where ";
			}
			/* Finally, add the doctype to the query string */
			$query .= "doctype in $doctype_set ";
		}
		/* If the user specified a journal, then include that in the query */
		if($journal) {
			$journNameQuery = "SELECT title AS journ_title FROM journals WHERE id=$journal";
			$result=mysql_query($journNameQuery)
			or die("Journal title query failed.");
			$row=mysql_fetch_array($result);
			extract($row);
	
			$_SESSION['search_values'].="<li>journal title=\"$journ_title\"</li>"; 
	
			/* If a title preceeded the doctype set, then add "and" to the query */
			if($title || $body || $doctype_set) {
				$query .= "and ";
			}
			/* If doctype is first in the query, then we need to start it off with 
			"where" */
			else {
				$query .= "where ";
			}
			/* Finally, add the doctype to the query string */
			$query .= "journal=$journal ";
		}
		/* If the user entered a year in either date field, then we must
		consider dates */
		if($year1 || $year2) {
			if($year1) {
				/* Zero any values left blank.  If month blank, then date
				must be zeroed too. */
				if(!$month1) {
					$month1 = "00";
					$day1 = "00";
				}
				if(!$day1) {
					$day1 = "00";
				}
				$_SESSION['search_values'].="<li>date (from)=\"$year1-$month1-$day1\"</li>"; 
			}
			if($year2) {
				/* Zero any values left blank.  If month blank, then date
				must be zeroed too. */
				if(!$month2) {
					$month2 = "00";
					$day2 = "00";
				}
				if(!$day2) {
					$day2 = "00";
				}
				$_SESSION['search_values'].="<li>date (to)=\"$year2-$month2-$day2\"</li>"; 
			}
			/* If there isn't a second year, then we do a regular date-matching
			search */
			if(!$year2) {
	
				$date1 = $year1 . "-" . $month1 . "-" . $day1;
				if($doctype_set || $title || $body || $journal) {
					$query .= "and ";
				}
				else {
					$query .= "where ";
				}
				if($month1 == "00") {
					$query .= "date like \"%$year1%\" ";
				}
				else {
					$query .= "date=\"$date1\" ";
				}
			}
			else if($year2 && (!$year1)) {
				if($month2 == "00") {
					$year2++;
				}
				if($day2 == "00") {
					$month2++;
					if($month2 == "13") {
						$year2++;
						$month2 = "01";
					}
				}
				$date1 = "1900-00-00";
				$date2 = $year2 . "-" . $month2 . "-" . $day2;
				if($doctype_set || $title || $body || $journal) {
					$query .= "and ";
				}
				else {
					$query .= "where ";
				}
		
				$query .= "date BETWEEN \"$date1\" and \"$date2\" ";
			}
			else {
				if($month2 == "00") {
					$year2++;
				}
				if($day2 == "00") {
					$month2++;
					if($month2 == "13") {
						$year2++;
						$month2 = "01";
					}
				}
				$date1 = $year1 . "-" . $month1 . "-" . $day1;
				$date2 = $year2 . "-" . $month2 . "-" . $day2;
				if($doctype_set || $title || $body || $journal) {
					$query .= "and ";
				}
				else {
					$query .= "where ";
				}
		
				$query .= "date BETWEEN \"$date1\" and \"$date2\" ";
	
			
			}
		}
		if(sizeof($category)>0) {
			if($date1 || $doctype_set || $title || $body || $journal) {
				$query .= "and ";
			}
			else {
				$query .= "where ";
			}
			for($i=0;$i<sizeof($category);$i++) {
				$catNameQuery = "SELECT name AS cat_name FROM test_cat WHERE id=$category[$i]";
				$result=mysql_query($catNameQuery)
				or die("Category name query failed.");
				$row=mysql_fetch_array($result);
				extract($row);
				$_SESSION['search_values'].="<li>subject index ".($i+1)."=\"$cat_name\"</li>"; 
				if($i>0) {
					$query .= "and ";
				}
				$query .= "docs_cat".$i.".cat_id=\"$category[$i]\" and docs_cat".$i.".doc_id=documents.id ";
			}
		}
		$query .= " and documents.doctype=doctypes.id ";
		
		// Handle Basic Search
		// will check body, type, title & categories
		if ($_POST['submit1']) {
			// Pull Search string apart
			$sSearch = isset($_POST['body1']) ? $_POST['body1'] : 'XXXXXXXX XXXXXXXX';
			$arSearch = split(" ", $sSearch);
			$query = "select distinct z.filename, z.title, z.date, z.type from (select y.*, c.name from (select x.*, dc.cat_id from (select d.id as doc_id, d.body, d.filename, d.title, d.date, dt.type from documents d left join doctypes dt on d.doctype = dt.id) x left join documents_category dc on dc.doc_id = x.doc_id) y left join test_cat c on y.cat_id = c.id) z where 1=1 ";
			for ($i=0; $i < sizeof($arSearch); $i++) {
				$query = $query."AND (z.body like '%".addslashes($arSearch[$i])."%' OR z.title like '%".addslashes($arSearch[$i])."%' OR z.type like '%".addslashes($arSearch[$i])."%' OR z.name like '%".addslashes($arSearch[$i])."%') ";
			} //end loop through search terms
			
		}
	
		$query .= "order by date";
		$_SESSION['current_query']=$query;
		$_SESSION['search_values'].="</ul></div>";
	}
	else {
		$query = $_SESSION['current_query'];
	}
	if(!$title && !$doctype1 && !$doctype2 && !$date1 && !$date2 && !$category && !$body && !$journal && !$_SESSION['current_query']) {
		echo "<div class=\"center\">Please <a href=\"search.php\">go back</a> and enter at least one criterion by which to search.</div>";
	}
	else {
	
		if(!isset($_GET[num_pages])) {
	
			//$query = "select * from 'categories'";
			print 'here is the query: '.$query;
			$result = mysql_query($query) //this is the actual query

			or die ("Query failed.  Please contact <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");
			$num_results = @mysql_num_rows ($result);

			if($num_results>$display_number) {
				$num_pages=ceil($num_results/$display_number);
			}
			else if($num_results>0) {
				$num_pages=1;
			}
			else {
				echo "<div class=\"center\">Your query yielded no results.  Please <a href=\"search.php\">try again</a>.</div>";
			}
			$start=0;
		}
		else {
			$start = $_GET[start];
			$num_pages=$_GET[num_pages];
		}
		$query2=$query." LIMIT $start, $display_number";
		$result2 = mysql_query($query2)
		or die("Limited query failed.  Please contact <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.");
		echo $_SESSION['search_values'];
		if($num_pages>1) {
			echo "<div>";	
			
			if($start==0) {
				$current_page=1;
			}
			else {
				$current_page=($start/$display_number) + 1;
			}
			if ($start !=0) {
				echo '<a href="'.$PHP_SELF.'?start=' . ($start - $display_number) . '&num_pages=' . $num_pages . '">Previous</a> ';
			}
		
			
			//Make all the numbered pages.
		
			for ($i = 1; $i <= $num_pages; $i++) {
				$next_start = $start + $display_number;
				if ($i != $current_page) { 
					// Don't link the current page
					echo '<a href="'.$PHP_SELF.'?start=' . (($display_number * ($i - 1))) . '&num_pages=' . $num_pages . '">' . $i . '</a> ';
				} else {
					echo $i . ' ';
				}
		
			}
			if ($current_page !=$num_pages) {
				echo '<a href="'.$PHP_SELF.'?start=' . ($start + $display_number) . '&num_pages=' . $num_pages . '">Next</a>';	
				}
		
			echo "</div>";
		}	

		echo "<OL>";
		$tempDate = "";
		$resultCounter = $start;
		while ($row = mysql_fetch_array($result2)) //here is where the results actually start displaying
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
			
			if($start==0) {
				$current_page=1;
			}
			else {
				$current_page=($start/$display_number) + 1;
			}
			if ($start !=0) {
				echo '<a href="'.$PHP_SELF.'?start=' . ($start - $display_number) . '&num_pages=' . $num_pages . '">Previous</a> ';
			}
		
			
			//Make all the numbered pages.
		
			for ($i = 1; $i <= $num_pages; $i++) {
				$next_start = $start + $display_number;
				if ($i != $current_page) { 
					// Don't link the current page
					echo '<a href="'.$PHP_SELF.'?start=' . (($display_number * ($i - 1))) . '&num_pages=' . $num_pages . '">' . $i . '</a> ';
				} else {
					echo $i . ' ';
				}
		
			}
			if ($current_page !=$num_pages) {
				echo '<a href="'.$PHP_SELF.'?start=' . ($start + $display_number) . '&num_pages=' . $num_pages . '">Next</a>';	
				}
		
			echo "</div>";
		}	

	}

}
include("../includes/footer.php");
?>
