<?php
session_start();
include("../includes/header.php");

include("dblayer3.php"); //include my db layer --JR

//this is the beginning

//header('Cache-Control: max-age=900'); //will this make the 'document expired' problem go away? 

function move_the($title) { 
	// this function moves "the" or "a" to the ends of titles. 
	// they've already been sorted this way in the mysql command. 
		$splitTitle = explode(' ',$title); // split by spaces
		$articles = array("A", "An", "The"); 
		if (in_array($splitTitle[0],$articles)) { // check to see if the first word is in our list of articles
			$the_or_a=$splitTitle[0]; 
			unset($splitTitle[0]); // take off "The" 
			$title = join(' ',$splitTitle).", ".strtolower($the_or_a); //now add it to the end of the title
		} 
		return $title; 
	} 

//debugging
if ($_GET['verbose']) { //to enable debugging messages, add ?verbose=TRUE to the URL, after search.php
	error_reporting(E_ALL);
	ini_set('display_errors', '1');
}

if(!$_POST['submit1'] && !$_POST['submit2'] && !isset($_GET['num_pages']) && !isset($_GET['journal']) && !isset($_GET['subject'])) { //this is the input page 
	session_unset();
	$query2a="SELECT `id` FROM `categories`";
	$result2a= mysql_query($query2a)
		or die ("could not execute query # 2a");
	$query3a="SELECT `id`, `title` FROM `journals` order by trim(leading 'The ' from trim(leading 'A ' from `title`))"; //reorders
	$result3a= mysql_query($query3a)
		or die ("could not execute query # 3a");

	//get mentioned places list from database
	$query4a="SELECT DISTINCT `name` from `mentioned_places` ORDER BY `name`"; 
	$result4a= mysql_query($query4a);  // won't die if there are no mentioned places yet

	//get mentioned people
	$query5a="SELECT DISTINCT `name` from `mentioned_people` ORDER BY `name`"; 
	$result5a= mysql_query($query5a); 

	//get mentioned organizations
	$query6a="SELECT DISTINCT `name` from `mentioned_organizations` ORDER BY TRIM(LEADING 'The ' from TRIM(LEADING 'A ' from TRIM(LEADING 'An ' FROM `name`)))"; 
	$result6a= mysql_query($query6a); 

	//get mentioned titles
	$query7a="SELECT DISTINCT `name` from `mentioned_titles` ORDER BY TRIM(LEADING 'The ' from TRIM(LEADING 'A ' from TRIM(LEADING 'An ' FROM `name`)))"; 
	$result7a= mysql_query($query7a); 

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
		extract($row3a); //break apart the array into variables 
		$title=move_the($title); 
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
					<td> 
						<h3>Mentions:</h3> 
					</td> 
				</tr> 
				<tr> <!-- mentioned places -JR --> 
					<td class=\"searchLabelCell\"> 
						<b>place: &nbsp;</b> 
					</td> 
					<td> 
						<select name=\"mentionedPlace\" style=\"width: 400px;\"> 
							<option value=''></option>\n"; 
	while ($row4a = mysql_fetch_array($result4a)) { 
		extract($row4a); 
		echo "                                  <option value='$name'>$name</option> "; 
	} 
	echo " 
						</select> 
					</td> 
				</tr>  
				<tr> 
					<td></td> 
					<td> 
						<i>(find documents that mention a particular place)</i>
					</td> 
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr> <!-- mentioned person -JR --> 
					<td class=\"searchLabelCell\"> 
						<b>person: &nbsp;</b> 
					</td> 
					<td> 
						<select name=\"mentionedPerson\" style=\"width: 400px;\"> 
							<option value=''></option>\n"; 
	while ($row5a = mysql_fetch_array($result5a)) { 
		extract($row5a); 
		echo "                                  <option value='$name'>$name</option> "; 
	} 
	echo " 
						</select> 
					</td> 
				</tr>  
				<tr> 
					<td></td> 
					<td> 
						<i>(find documents that mention a particular place)</i>
					</td> 
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr> <!-- mentioned organization -JR --> 
					<td class=\"searchLabelCell\"> 
						<b>organization: &nbsp;</b> 
					</td> 
					<td> 
						<select name=\"mentionedOrganization\" style=\"width: 400px;\"> 
							<option value=''></option>\n"; 
	while ($row6a = mysql_fetch_array($result6a)) { 
		extract($row6a); 
		$name=move_the($name); 
		echo "                                  <option value='$name'>$name</option> "; 
	} 
	echo " 
						</select> 
					</td> 
				</tr>  
				<tr> 
					<td></td> 
					<td> 
						<i>(find documents that mention a particular organization)</i>
					</td> 
				</tr>
				<tr>
					<td>
						&nbsp; 
					</td>
				</tr>
				<tr> <!-- mentioned title -JR --> 
					<td class=\"searchLabelCell\"> 
						<b>title: &nbsp;</b> 
					</td> 
					<td> 
						<select name=\"mentionedTitle\" style=\"width: 400px;\"> 
							<option value=''></option>\n"; 
	while ($row7a = mysql_fetch_array($result7a)) { 
		extract($row7a); 
		$name=move_the($name); 
		echo "                                  <option value='$name'>$name</option> "; 
	} 
	echo " 
						</select> 
					</td> 
				</tr>  
				<tr> 
					<td></td> 
					<td> 
						<i>(find documents that mention the title of a work)</i>
					</td> 
				</tr>
				<tr>
					<td>
						&nbsp; 
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

	if(isset($_POST['submit1']) || isset($_POST['submit2']) || isset($_GET['subject']) || isset($_GET['journal'])) { //this is the output page
		$_SESSION['search_values'] = "<div>You searched using the following values:\n<ul>";
	
		$query="";
		
		/* Query database */
		
		if(isset($_POST['submit1'])) {
		  $body = trim($_POST[body1]);
		}
		else {
		  $body = trim($_POST['body2']);
		  $title = trim($_POST['title']);	
		  $doctype1 = trim($_POST['doctype1']);
		  $doctype2 = trim($_POST['doctype2']);
		  $doctype_test = trim($doctype1 . " " . $doctype2);
		  $category = $_POST['category'];
		  $year1 = $_POST['year1'];
		  $month1 = $_POST['month1'];
		  $day1 = $_POST['day1'];
		  $year2 = $_POST['year2'];
		  $month2 = $_POST['month2'];
		  $day2 = $_POST['day2'];
		  $mentionedPlace = $_POST['mentionedPlace']; 
		  $mentionedPerson = $_POST['mentionedPerson']; 
		  $mentionedOrganization = $_POST['mentionedOrganization']; 
		  $mentionedTitle = $_POST['mentionedTitle']; 
		}		
		//find parent subjects if they exist
		/* 
		 * Accepts as params a subject ID
		 * returns parent subject ID
		 */ 
		function look_up_parent_subjects($subjectID) { 
			$subjectParentLookupQuery = "SELECT parent_id FROM test_cat WHERE id=$subjectID"; 
			if ($_GET['verbose']) {  // debugging
				echo "<p>Subject parent lookup query is: $subjectParentLookupQuery</p>"; 
			} 
			$subjectParentLookupResult = mysql_query($subjectParentLookupQuery) or die ("Couldn't look up subject parent(s)."); 
			$resultArray=mysql_fetch_array($subjectParentLookupResult); 
			$parentID=$resultArray[0]; //FIXME there must be an easier way to do this
			return $parentID; 
		} 
		if (isset($category)) { 
			$category=$category[0]; // all we have is one value from POST, anyway. 
			$categories[]=$category; // initialize new array
			while (look_up_parent_subjects($category)!=0) { 
				$category=look_up_parent_subjects($category); 
				$categories[]=$category; //add parent subject to array 
			} 
			$category=array_reverse($categories); // reassign this variable
		} 

		function look_up_journalID($journalTitle) { 
			//this should take the raw journal title, i.e. "Birth Control Review" and look up the ID in the database
			$journalLookupQuery="SELECT id FROM journals WHERE title=$journalTitle;"; //look up journal ID using subject name
			if ($_GET['verbose']) { //debugging. FIXME refactor this to concatenate to a global variable? 
				echo "<p>Trying to look up journal ID now.</p>"; 
				echo "<p>Here is the raw mySQL query for looking up the journal ID in the database: $journalLookupQuery</p>"; //debugging
				$error = mysql_error();
				if ($error) { 
					echo "<p>Here is the mySQL error: </p> 
					<p>$error</p>"; 
				} 
			} 
			$journalLookupResult=mysql_query($journalLookupQuery) or die("<p>Couldn't find journal name in database.</p>");  
			$myResult=mysql_fetch_array($journalLookupResult); 
			$journalID=$myResult[0]; 
			return $journalID; 
		}
		if($_GET['journal']!=NULL) { // get the journal name from the URL, useful for clickable journal links within documents
			if ($_GET['verbose']) {  // debugging
				echo "You've specified a journal title via URL."; 
			}
			$journal = look_up_JournalID($_GET['journal']); 
		} else {  //otherwise use the one specified in the form
		  $journal = trim($_POST['journal']);
			if ($_GET['verbose']) { 
				echo "Using the value for journal specified in the form."; 
				print $journal; 
			}

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
		function look_up_subjectID($subjectParamName, $parentSubjectID=0) { 
			//this function accepts the URL parameter name for the raw subject, and looks up its raw subject heading in the database to get its ID. 
			$subject=$_GET[$subjectParamName]; 
			$subject=addslashes(strtr($subject,"^",'"')); // change all the ^ back into escaped quotes as per #23 
			$subjectLookupQuery="SELECT id AS subjectID FROM test_cat WHERE parent_id=$parentSubjectID and name=\"$subject\";"; //look up subject ID using subject name
			if ($_GET['verbose']) { //debugging
			} 
			$subjectLookupResult=mysql_query($subjectLookupQuery) or die("<p>Couldn't find subject name in database.</p>");  
			$myResult=mysql_fetch_array($subjectLookupResult); 
			$subjectID=$myResult[0]; 
			return $subjectID; 
		} 	
		//check for URL subject search parameters and plug them in 
		// aaaah so much repeition. FIXME: refactor this
		if($_GET['subject']) { 
			$category=array(); //empty out category first. URL params override POST
			$subjectID=look_up_subjectID('subject'); 
			$category[]=$subjectID; //add or push to array
			if($_GET['subject2']) { 
				$parentSubjectID=$subjectID; //assign previous $subject to new variable parentSubject
				$subjectID=look_up_subjectID('subject2', $parentSubjectID); 
				$category[]=$subjectID; //add or push to array
			} 
			if($_GET['subject3']) { 
				$parentSubjectID=$subjectID; //this should now be the ID from subject2
				$subjectID=look_up_subjectID('subject3', $parentSubjectID); 
				$category[]=$subjectID; //add or push to array
			}
			if($_GET['subject4']) { 
				$parentSubjectID=$subjectID; //this should now be the ID from subject3
				$subjectID=look_up_subjectID('subject4', $parentSubjectID); 
				$category[]=$subjectID; //add or push to array
			}
		}

		$query .= "SELECT DISTINCT filename, title, date, type FROM documents AS d, doctypes AS t"; 
		if(sizeof($category)>0) {
			for($i=0;$i<sizeof($category);$i++) {
				$query .= ", documents_category AS docs_cat".$i;
			}
		}
		if($mentionedPlace) { 
			$query .= ", mentioned_places AS mpl"; 
		} 
		if($mentionedPerson) { 
			$query .= ", mentioned_people AS mppl"; 
		} 
		if($mentionedOrganization) { 
			$query .= ", mentioned_organizations AS morg"; 
		} 
		if($mentionedTitle) { 
			$query .= ", mentioned_titles AS mtitle"; 
		} 
	
		$query.=" ";
	
		/* If the user specified a title, then include that in the query */	
		if($title) {
			$_SESSION['search_values'].="<li>title=\"$title\"</li>"; 
	
			$query .= "WHERE TITLE LIKE \"%$title%\" ";
		}
		if($body) {
			$_SESSION['search_values'].="<li>full text=\"$body\"</li>"; 
	
			if($title) {
				$query .= "AND ";
			}
			else {
				$query .= "WHERE ";
			}
			$query .= "MATCH (title, body) AGAINST ('$body' IN BOOLEAN MODE) ";
		}
		/* If the user specified a doctype, then include that in the query */
		if($doctype_set) {
			/* If a title preceeded the doctype set, then add "and" to the query */
			if($title || $body) {
				$query .= "AND ";
			}
			/* If doctype is first in the query, then we need to start it off with 
			"where" */
			else {
				$query .= "WHERE ";
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
				$query .= "WHERE ";
			}
			/* Finally, add the doctype to the query string */
			$query .= "journal=$journal ";
		}
		/* If the user specified a mentioned place, then include that in the query -JR */ 
		if($mentionedPlace) { 
			$_SESSION['search_values'].="<li>mentioned place=\"$mentionedPlace\"</li>"; 

			/* If a title preceeded the doctype set, then add "and" to the query */
			if($title || $body || $doctype_set) {
				$query .= "AND ";
			}
			/* If doctype is first in the query, then we need to start it off with 
			"where" */
			else {
				$query .= "WHERE ";
			}
			/* Finally, add the doctype to the query string */
			$query .= "mpl.name =\"$mentionedPlace\"  "; 
		} 
		/* If the user specified a mentioned person, then include that in the query -JR */ 
		if($mentionedPerson) { 
			$_SESSION['search_values'].="<li>mentioned person=\"$mentionedPerson\"</li>"; 

			/* If a title preceeded the doctype set, then add "and" to the query */
			if($title || $body || $doctype_set || $mentionedPlace) {
				$query .= "and ";
			}
			/* If doctype is first in the query, then we need to start it off with 
			"where" */
			else {
				$query .= "WHERE ";
			}
			/* Finally, add the doctype to the query string */
			$query .= "mppl.name =\"$mentionedPerson\"  "; 
		} 
		if($mentionedOrganization) { 
			$_SESSION['search_values'].="<li>mentioned organization=\"$mentionedOrganization\"</li>"; 

			/* If a title preceeded the doctype set, then add "and" to the query */
			if($title || $body || $doctype_set || $mentionedPerson || $mentionedPlace) {
				$query .= "AND ";
			}
			/* If doctype is first in the query, then we need to start it off with 
			"where" */
			else {
				$query .= "WHERE ";
			}
			/* Finally, add the doctype to the query string */
			$query .= "morg.name =\"$mentionedOrganization\"  "; 
		} 
		if($mentionedTitle) { 
			$_SESSION['search_values'].="<li>mentioned title=\"$mentionedTitle\"</li>"; 

			/* If a title preceeded the doctype set, then add "and" to the query */
			if($title || $body || $doctype_set || $mentionedPerson || $mentionedPlace || $mentionedOrganization) {
				$query .= "AND ";
			}
			/* If doctype is first in the query, then we need to start it off with 
			"where" */
			else {
				$query .= "WHERE ";
			}
			/* Finally, add the doctype to the query string */
			$query .= "mtitle.name =\"$mentionedTitle\"  "; 
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
					$query .= "WHERE ";
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
					$query .= "AND ";
				}
				else {
					$query .= "WHERE ";
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
					$query .= "AND ";
				}
				else {
					$query .= "WHERE ";
				}
		
				$query .= "date BETWEEN \"$date1\" and \"$date2\" ";
	
			
			}
		}
		 
		if(sizeof($category)>0) {
			if($date1 || $doctype_set || $title || $body || $journal || $mentionedPerson || $mentionedPlace || $mentionedOrganization || $mentionedTitle) {
				$query .= "AND ";
			}
			else {
				$query .= "WHERE ";
			}

			if ($_GET['verbose']) { //debugging
			}

			for($i=0;$i<sizeof($category);$i++) {
				$categoryi = $category[$i]; 
				$catNameQuery = "SELECT name AS cat_name FROM test_cat WHERE id=$categoryi";
				if ($_GET['verbose']) { //debugging
					echo "<p>Category name query is: $catNameQuery</p>"; 
				} 
				$result=mysql_query($catNameQuery)
				or die("<p>Category name query failed.</p>");
				$row=mysql_fetch_array($result);
				extract($row);
				$_SESSION['search_values'].="<li>subject index ".($i+1)."=\"$cat_name\"</li>"; 
				if($i>0) {
					$query .= "and ";
				}
				$query .= "docs_cat".$i.".cat_id=\"$categoryi\" AND docs_cat".$i.".doc_id = d.id ";
			}
		}

		//Handle subject search from URL parameters
		//so much code going to waste
//		if($_GET['subject']) { 
//			$subject = $_GET['subject']; 
//			$subjectLookupQuery="SELECT id AS subjectID FROM test_cat WHERE parent_id=0 and name=$subject;"; //look up subject ID using subject name
//			$subjectLookupResult=mysql_query($subjectLookupQuery) or die("<p>Couldn't find subject name in database.</p>");  
//			$myResult=mysql_fetch_array($subjectLookupResult); 
//			$subjectID=$myResult[0]; 
//// aaaah! factor this into a loop like the code above!
//			if($_GET['subject2']) { 
//				$subject2 = $_GET['subject2']; 
//				//make sure the second subject is in the database
//				$subject2LookupQuery="SELECT id AS subjectID FROM test_cat WHERE parent_id=$subjectID and name=$subject2;"; //look up subject ID using subject name
//				$subject2LookupResult=mysql_query($subject2LookupQuery) or die("<p>Couldn't find subject 2 name in database.</p>");  
//				$myResult=mysql_fetch_array($subject2LookupResult); 
//				$subject2ID=$myResult[0]; 
//				
//				//now get the ID of the second subject and return associated documents
//				$_SESSION['search_values'].="<li>subject index ".($i+1)."=\"$cat_name\"</li>"; 
//			} 
//			$catNameQuery = "SELECT name AS cat_name FROM test_cat WHERE id=$subjectID";
//			$result=mysql_query($catNameQuery)
//				or die("Subject name URL parameter query failed.");
//			$row=mysql_fetch_array($result);
//			extract($row);
//			$query .= ", documents_category AS docs_cat0 where docs_cat0.cat_id=\"$subjectID\" and docs_cat0.doc_id=documents.id "; //I don't know why this works but it does
//		} 
		$query .= " and d.doctype = t.id "; 
		if($mentionedPlace) { 
			$query .= " AND d.filename = mpl.in_document "; 
		} 
		if($mentionedPerson) { 
			$query .= " AND d.filename = mppl.in_document "; 
		} 
		if($mentionedOrganization) { 
			$query .= " AND d.filename = morg.in_document "; 
		} 
		if($mentionedTitle) { 
			$query .= " AND d.filename = mtitle.in_document "; 
		} 
		
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
	
		$query .= "ORDER BY DATE ";
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
	
		if(!isset($_GET['num_pages'])) {
	
			//$query = "select * from 'categories'";
			if ($_GET['verbose']) { 
				echo "<p>Here is the raw mySQL query: </p>
				      <p>$query</p>"; //debugging
				$error = mysql_error();
					echo "<p>Here is the mySQL error: </p> 
					<p>$error</p>"; 
			}
			$result = mysql_query($query) //this is the actual query

			or die ("<p>Query failed.  Please contact <a href='mailto:humanities.computing@nyu.edu'>the administrator</a> immediately.</p>");
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
		while ($row = mysql_fetch_array($result2)) //this is where the results actually start displaying 
		{	
			$resultCounter++;
			extract($row);
			//				$tempDate = strtotime($date);
			if($resultCounter != $start+1) {
				echo "<br />&nbsp;\n";
			}
			echo "<LI VALUE=\"$resultCounter\"><b><a href='show.php?sangerDoc=$filename'>$title</a></b></LI>\n";
			echo "<span class=\"resultSubLine\">";
			if ($date=="0000-00-00") { 
				echo "n.d."; 
			} else {  
				echo $date;
			} 
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
