<?php

//enable error reports for debugging
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
//debug_print_backtrace();

/*$filepointer;
*/
/****************
	DOC PROCESSING FUNCTION
****************/
function processFile($Files,$filepointer) {

  $line="<p>********<br /><b>Attempting file ".$Files.".</b><br />";
  echo $line;
  fwrite($filepointer,$line);
  
  /****** ABILITY TO OVERWRITE *************/
  // is file already in the system?
  
    $sDupeCheckQ = "Select id FROM documents WHERE filename ='".addslashes($Files)."'";
    $dupeCheck = @mysql_query($sDupeCheckQ);
  	$dupeCheckHits = @mysql_num_rows($dupeCheck);
  	
  	print $sDupeCheckQ."\n";
  	
  	if ($dupeCheckHits > 0) {
  		//file is already in system - remove it so it can be replaced
  		$row = mysql_fetch_assoc($dupeCheck);
  		$iIDtoDelete = $row['id'];
  		$sQuery = "DELETE FROM documents where id = ".$iIDtoDelete;
  		$resultX = @mysql_query($sQuery);
  		$sQuery = "DELETE FROM documents_category where doc_id = ".$iIDtoDelete;
  		$resultX = @mysql_query($sQuery);
  	}
  	
  
  /* array of error messages for printout on failure */
  $error[] = array();
  
  /* get xsl stylesheet */
  $xsl = new DomDocument();
  $xsl->load("../xslt/prep.xsl");
  
  /* get an xml document from this HARDCODED directory */
  $inputdom=new DomDocument();
  $inputdom->load("../../xml_queue/$Files");

  /* ready to process xml and stylesheet */
  $proc=new XsltProcessor();
  $xsl = $proc->importStylesheet($xsl);
  
  /* throw the "styled" xml into another stream for further parsing */
  $stream=$proc->transformToXML($inputdom);
  
  /* if we've got a stream, then massaaaaggggeee it into the db */
  if ($stream) {
	$xml = simplexml_load_string($stream);

    $type=null;
    $date=null;
    $title=null;
    $body=null;
    $journal=null;
    $mentionedTitle=null; 
    $mentionedPerson=null; 
    $mentionedPlace=null; 
    $mentionedOrganization=null; 
	$indices[]=array();

	$type=$xml['type'];
	$date=$xml->docDate['value'];
	$journal=$xml->journal[0];
	if(strcasecmp($journal,"LCM")==0 || strcasecmp($journal,"MSM")==0 || strcasecmp($journal,"margaret sanger microfilm")==0) {
		$journal=null;
	}
	$body=$xml->docBody;
	$title=$xml->docTitle;
	$mentionedTitle=$xml->mentionedTitle; 
	$mentionedPerson=$xml->mentionedPerson; 
	$mentionedPlace=$xml->mentionedPlace; 
	$mentionedOrganization=$xml->mentionedOrganization; 
	$metadata=array('type'=>$type,'date'=>$date,'title'=>$title,'body'=>$body,'journal'=>$journal);
	//	disabling cleaning of mentions because it seems to be causing problems with the database processing
       //	,'mentionedTitle'=>$mentionedTitle,'mentionedPerson'=>$mentionedPerson,'mentionedPlace'=>$mentionedPlace,'mentionedOrganization'=>$mentionedOrganization); 
 	
	//debugging. Remove this. 
	echo "<br/>metadata is: "; 
	print_r($metadata); 
 	echo "<br/>stream is: ".$stream."<br/>"; 
	echo "<br/>xml is: "; 
	print_r($xml); 
	echo "<br/>mentionedPlace is: "; 
	print_r($mentionedPlace); 
	echo "<br/>mentionedPerson is: "; 
	print_r($mentionedPerson); 
	echo "<br/>mentionedTitle is: "; 
	print_r($mentionedTitle); 
	
    $indexCounter=1;
	foreach($xml->headNote->index AS $xml_index) {
		$currentIndex='index'.$indexCounter;
		$indices[$currentIndex] = array();
		foreach($xml_index->attributes() as $key => $value) {
			$indices[$currentIndex][$key]=$value;
		}		
		$indexCounter++;
	}
	

    /* add slashes to the data */
    //refactoring this
    $filename= addslashes($Files); //this one needs to be done separately
    //$type= addslashes($type);
    //$date= addslashes($date);
    //$title= addslashes($title);
    //$body= addslashes($body);
    //if ($journal) {
    //  $journal= addslashes($journal);
    //}
    //$mentionedOrganization = addslashes($mentionedOrganization); 
    //$mentionedPlace = addslashes($mentionedPlace); 
    //$mentionedTitle = addslashes($mentionedTitle); 

	foreach ($metadata as $key => $value) { 
		echo "<br/>old value:";  //debugging
		print_r($value); //debugging  
		if ($value) { 
			if (is_array($value)) { //if it's an array, it's probably a list of extracted places or names
				foreach ($value as $valuePiece) { 
					$valuePiece = preg_replace( '/\s+/', ' ', $valuePiece ); //cleans up interior whitespace				
				} 
			} else {  
			$value=addslashes($value); 
			$value=ereg_replace("\n", " ", $value);
			$value=trim($value);
			} 
		} 
		echo "<br/>new value:"; //debugging  
		print_r($value); //debugging  
		$metadata[$key]=$value; //store the new value back in the array 
	} 
	extract($metadata); //the array needs to become variables now so it can be accessed below

    
    /* replace newline chars with spaces */			
    //$type= ereg_replace("\n", " ", $type);
    //$date= ereg_replace("\n", " ", $date);
    //$title= ereg_replace("\n", " ", $title);
    //$body= ereg_replace("\n", " ", $body);
    //if($journal) {
    //  $journal= ereg_replace("\n", " ", $journal);
    //}			
    //$mentionedOrganization = ereg_replace("\n", " ", $mentionedOrganization);
    //$mentionedPerson = ereg_replace("\n", " ", $mentionedPerson);
    //$mentionedPlace = ereg_replace("\n", " ", $mentionedPlace);
    //$mentionedTitle = ereg_replace("\n", " ", $mentionedTitle);
  //  
  //  /* trim the elements.  commenting on the obvious, anybody? */
  //  $type= trim($type);
  //  $date= trim($date);
  //  $title= trim($title);
  //  $body= trim($body);
  //  if ($journal) {
  //    $journal= trim($journal);
  //  }			
  //  // Argh! So much typing! -JR
  //  $mentionedOrganization = trim($mentionedPlace);
  //  $mentionedPerson = trim($mentionedPerson);
  //  $mentionedPlace = trim($mentionedPlace);
  //  $mentionedTitle = trim($mentionedTitle);

    /* if date is empty or improperly formatted, add an error */
    if (empty($date)) {
      $error[]="No document date in &gt;docdate value=\"\"&gt;";
    } 
    else if (!eregi("[0-9]{4}-[0-9]{2}-[0-9]{2}", $date)) {
      $error[]= $date . " is not a valid date. It must follow the pattern yyyy-mm-dd.";
    }
    
    /* if the doctype is empty or nonexistent in db (typo?), then add an error */
    if (empty($type)) {
      $error[]="No document type in &lt;doc type=\"\"&gt;";
    } 
    /* or go and find the id of the doctype */
    else {
      $query1 = "Select id AS doctype_id FROM doctypes WHERE type ='$type'";
      $docTypeResult = @mysql_query($query1);
      if(mysql_error()) {
		$line= "Error retrieving doctype.  Contact administrator.";
		fwrite($filepointer,$line);
		echo $line;
		return;
      }
      $hits = @mysql_num_rows($docTypeResult);
      if ($hits == 0) {
		$error[]= "" . $type . " is not a valid document type.";
	
      }
      else if ($hits != 1) {
		$error[]= "" . $type . " matched ". $hits." types in the database.
						<br>This is an error.  Please contact the administrator Immediately.";
      }
    }
    
    /* error if no title */
    if (empty($title)) {
      $error[]= "No document title in &lt;docTitle&gt;";
    }
    
    /* error if no body */
    if (empty($body)) {
      $error[]="No document body in &lt;docBody&gt;";
    }
    
    /* if errors, then tell user to get with the program */
    if (count($error) > 1) {
      $line="<i>Failure</i>: A record will not be created for file " . $filename . " because of the following error(s):</p><ol>\n";
      echo $line;
      fwrite ($filepointer, $line);
      /* first element of array is, for some reason "Array()".
				I think i'm confused about something, but this "trashError"
				logic prevents that from being printed out */
      $trashError = true;
      foreach($error as $value) {
	if(!$trashError) {
	  echo "<li>\n";
	  fwrite($filepointer, "<li>\n");
	  echo $value;
	  fwrite($filepointer, $value);
	  echo "</li>\n";	
	  fwrite($filepointer, "</li>\n");
	}
	else {
	  $trashError = false;
	}
      }
      $line="</ol><p>Please edit the file and try again or, if necessary, contact the administrator.</p>\n";
      echo $line;
      fwrite($filepointer, $line);
      return;
    }
    /* otherwise continue, and insert data into database */
    else {
      /* notify that this is all good, and 
				we're gonna throw it into the db */
      $line="<i>Validated</i>: " .$filename.".<br />Proceeding with further processing and insertion into database . . . \n</p><ol>\n";
      echo $line;
      fwrite($filepointer,$line);
      
      /* get the doctype id for document's database insert */
      $row= mysql_fetch_array($docTypeResult);
      extract($row);

    /* if there is a journal entry, get the id
			or add a new journal entry and id */				
    if ($journal) {
      $journQuery = "Select id AS journal_id from journals where title=\"".$journal."\"";
      $journResult = @mysql_query($journQuery);
      $erra=mysql_error();
      if($erra) {
	$line="<li>On retrieval of journal ID:".$erra.".<br>Please contact the administrator immediately.</li></ol>";
	echo $line;
	fwrite($filepointer,$line);
	return;
      }
      else {
	$num_rows = @mysql_num_rows($journResult);
	/* If there is a journal ID, store it for later use */
	if($num_rows == 1) { 
	  while($row = mysql_fetch_array($journResult))
	    {
	      extract($row);
	      $journID = $journal_id;
	    }
	}
	else if($num_rows != 0) {
	  $line="<li>There are ".$num_rows." journals matching title ".$journal.".<br>Please contact the administrator immediately.</li></ol>";
	  echo $line;
	  fwrite($filepointer,$line);
	  return;
	}
	/* otherwise add it and get the id */
	else {
	  $journAddQuery = "insert into `journals` (`title`) values ('".$journal."')";
	  $journResult = @mysql_query($journAddQuery);
	  /* see if there were any errors on insert */
	  $erra=mysql_error();
	  if($erra) {
	    $line="<li>On journal insert to database: ".$erra.".<br>Please contact the administrator immediately.</li></ol>";
	    fwrite($filepointer,$line);
	    echo $line;
	    return;
	  }
	  else {
	    /* get the most recently inserted journal id */
	    $journID=mysql_insert_id();
	    if($journID==0) {
	      $line="<li>Journal ID 0 returned from insert.<br>Please contact the administrator immediately.</li></ol>";
	      echo $line;
	      fwrite($filepointer,$line);
	      return;
	    }
	  }
	}
      }
      $query2 = "INSERT INTO `documents` (`filename`, `title`, `date`, `doctype`, `body`, `journal`) VALUES ('$filename', '$title', '$date', '$doctype_id', '$body', '$journID')";
	/************************************  AM UPDATE 11/29/05 *******************************/
        /* Concatenate ON DUPLICATE KEY logic */
        $query2 = $query2 . " ON DUPLICATE KEY UPDATE `filename` = '$filename', `title` = '$title', `date` = '$date', `doctype` = '$doctype_id', `body` = '$body'";
        /************************************************************************************/
    }
    /* otherwise journalid = default (null) */
    else {
      $query2 = "INSERT INTO `documents` (`filename`, `title`, `date`, `doctype`, `body`) VALUES ('$filename', '$title', '$date', '$doctype_id', '$body')";
	/************************************  AM UPDATE 11/29/05 *******************************/
        /* Concatenate ON DUPLICATE KEY logic */
        $query2 = $query2 . " ON DUPLICATE KEY UPDATE `filename` = '$filename', `title` = '$title', `date` = '$date', `doctype` = '$doctype_id', `body` = '$body'";
        /************************************************************************************/
    }

      /* Now stuff to handle mentioned people, places, titles, etc! -JR */ 

      /******* Parse People Mentioned in Text *********/
      //make sure the database is there first
      $personTableQuery="CREATE TABLE IF NOT EXISTS `mentioned_people` 
	      (name VARCHAR(100), in_document VARCHAR(20), CONSTRAINT UNIQUE (name, in_document));"; //data structure for mentioned people
      $myResult = @mysql_query($personTableQuery); 
      //standard error stuff
      $erra=mysql_error();
      if($erra) {
	$line="<li>On create table: ".$erra.".<br />Please contact the administrator immediately.</li></ol>";
	echo $line;
	fwrite($filepointer, $line);
	return;					
      }

      $personQueries=""; //create an empty string container
      echo "<br/>FILENAME!!!!!!!!!!: "; 
      print_r($filename); 
      if ($mentionedPerson) { 
	      foreach ($mentionedPerson as $person) { 
		      $personQuery = "INSERT INTO mentioned_people (name, in_document) VALUES ('$person', '$filename')
			      ON DUPLICATE KEY UPDATE name='$person',in_document='$filename'; "; 
		      //FIXME: use document ID instead of filename? 
		      //FIXME: it's still not happy with duplicate entres
		      echo "Person query is: ".$personQuery; 
		      $myInsertResult = @mysql_query($personQuery);
		      /* see if there was an error inserting */
		      $erra=mysql_error();
		      if($erra) {
			      $line="<li>On insert: ".$erra.".<br />Please contact the administrator immediately.</li></ol>";
			      echo $line;
			      fwrite($filepointer, $line);
			      return;					
		      }
	      } 
      } 
//      echo "<br/>Personqueries: "; //debugging
//      print_r($personQueries); //debugging 

      /*********** Parse Places Mentioned in Text ***********/
       //make sure the database is there first
      $placeTableQuery="CREATE TABLE IF NOT EXISTS `mentioned_places` 
	      (name VARCHAR(100), in_document VARCHAR(20), CONSTRAINT UNIQUE (name, in_document));"; //data structure for mentioned people
      $myResult = @mysql_query($placeTableQuery); 
      //standard error stuff
      $erra=mysql_error();
      if($erra) {
	$line="<li>On create place table: ".$erra.".<br />Please contact the administrator immediately.</li></ol>";
	echo $line;
	fwrite($filepointer, $line);
	return;					
      }

      $placeQueries=""; //create an empty string container
      print_r($filename); 
      if ($mentionedPlace) { 
	      foreach ($mentionedPlace as $place) { 
		      $placeQuery = "INSERT INTO mentioned_places (name, in_document) VALUES ('$place', '$filename')
			      ON DUPLICATE KEY UPDATE name='$place',in_document='$filename'; "; 
		      //FIXME: use document ID instead of filename? 
		      //$personQueries.=$personQuery; //adds to string
		      $myInsertResult = @mysql_query($placeQuery);
		      /* see if there was an error inserting */
		      $erra=mysql_error();
		      if($erra) {
			      $line="<li>On insert place: ".$erra.".<br />Please contact the administrator immediately.</li>";
			      $line.="<li>Query was: ".$placeQuery." </li></ol>"; 
			      echo $line;
			      fwrite($filepointer, $line);
			      return;					
		      }
	      } 
      } 
      echo "<br/>Placequeries: "; //debugging
      print_r($placeQueries); //debugging 
      
      
      /* carry out the insert query */
      $result2 = @mysql_query($query2);
      /* see if there was an error inserting */
      $erra=mysql_error();
      if($erra) {
	$line="<li>On insert document: ".$erra.".<br />Please contact the administrator immediately.</li>";
	$line.="<li>Query: ".$query2." </li></ol>"; 
	echo $line;
	fwrite($filepointer, $line);
	return;					
      }
      else {
	/* get the most recently inserted document */
	$docID=mysql_insert_id();
	if($docID==0) {
	  $line="<li>Document ID 0 returned from insert.<br />Please contact the administrator immediately.</li></ol>";
	  echo $line;
	  fwrite($filepointer, $line);
	  return;					
	}
	/* if we got a valid document ID, deal with categories */
	else {

	  /*************************** AM UPDATE 1/17/2006 **********************************/
	  /** Clears out indexing for current document so that it might be re-created ******/

	  $query = "DELETE FROM documents_category where doc_id = ".$docID;
	  $result3=@mysql_query($query);
	  $erra=@mysql_error();
	  if($erra) {
        	$line="<li>On category reset: ".$query.".<br />Please contact the administrator immediately.</li></ol>";
        	echo $line;
        	fwrite($filepointer, $line);
        	return;
      	  }
	  /**********************************************************************************/


	  $indexCounter = 1;
	  $currentIndex = 'index' . $indexCounter;
	  /* Keep looking at indices until we find one that is empty (undefined) */	
	  while(!empty($indices[$currentIndex])) {
	    /* (re)init cat id */
	    $catID = NULL;
	    /* with a new category, start at level 1 (top) */
	    $levelCounter = 1;
	    /* init parent id to 0 (for top level categories (levelCounter = 1) */
	    $parentID = 0;
 	    $iIndexMax = sizeof($indices[$currentIndex]);
	    /* loop through 4 (max) levels of current index . . . */
	    for($levelCounter = 1; $levelCounter<=$iIndexMax; $levelCounter++) {
	      /* if the current level of this index is defined, get it or insert it */
	      if($indices[$currentIndex]['level'.$levelCounter]) {
		/* if the category with the current parent id is in the db,
       		  then get the id of that entry */
		
		    $indices[$currentIndex]['level'.$levelCounter]=addslashes($indices[$currentIndex]['level'.$levelCounter]);
		    $indices[$currentIndex]['level'.$levelCounter]= ereg_replace("\n", " ", $indices[$currentIndex]['level'.$levelCounter]);    
		    $indices[$currentIndex]['level'.$levelCounter]=trim($indices[$currentIndex]['level'.$levelCounter]);
		$query = "SELECT id AS cat_id FROM test_cat WHERE name=\"" . $indices[$currentIndex]['level' . $levelCounter] . "\" AND parent_id=".$parentID;	
		$result = @mysql_query($query);
		$erra=mysql_error();
		if($erra) {
		  $line="<li>On retrieval of category ID:".$erra.".<br />Please contact the administrator immediately.</li></ol>";
		  echo $line;
		  fwrite($filepointer, $line);
		  return;					
		}
		else {
		  $num_rows=@mysql_num_rows($result);
		  /* if only 1 id returned for cat, then get the id */
		  if($num_rows==1) {
		    $row = mysql_fetch_array($result);
		    extract($row);
		    $catID=$cat_id;
		  }
		  /* if many id returned for cat, then there is a problem
										in the db */
		  else if($num_rows!=0) {
		    $line="<li>Returned ".$num_rows." IDs for category ".$indices[$currentIndex]['level' . $levelCounter].
		      "with parent ".$parentID.".<br />Please contact the administrator immediately.</li></ol>";
		    echo $line;
		    fwrite($filepointer,$line);
		    return;
		  }
		  /* if 0 id returned for cat, then add this cat to the db */
		  else {
		    /* insert the cat into the db with its current parentID */
		    $query="INSERT INTO `test_cat` (`parent_id`, `name`) VALUES ('".$parentID."', '".$indices[$currentIndex]['level' . $levelCounter] . "')";
		    $result=@mysql_query($query);
		    $erra=mysql_error();
		    if($erra) {
		      $line="<li>On category insert: ".$erra.".<br />Please contact the administrator immediately (".$query.").</li></ol>";
		      echo $line;
		      fwrite($filepointer, $line);
		      return;					
		    }
		    else {
		      $catID=mysql_insert_id();
		      if($catID==0) {
			$line="<li>Category ID 0 returned for insert.<br />Please contact the administrator immediately.</li></ol>";
			echo $line;
			fwrite($filepointer, $line);
			return;					
		      }

		    }
		  }
		}
		$parentID=$catID;
		/* see if there's already a linking table entry which,
	       	         with this new system, is perfectly possible. */
		$query = "SELECT doc_id FROM documents_category WHERE doc_id=".$docID." AND cat_id=".$catID;
		$result=@mysql_query($query);
		$erra=mysql_error();
		if($erra) {
		  $line="<li>On retrieval of entry in documents_category: ".$erra.".<br />Please contact the administrator immediately.</li></ol>";
		  echo $line;
		  fwrite($filepointer, $line);
		  return;
		}
		else {
		  $num_rows = @mysql_num_rows($result);
		  if($num_rows == 0 ) {
		    /* use the catID to make a linking table entry
		       	     linking new or queried category to document */
		    $query = "INSERT INTO `documents_category` (`doc_id`, `cat_id`) VALUES ('".$docID."', '".$catID."')";
		    $parentID=$catID;
		    $result=@mysql_query($query);
		    $erra=mysql_error();
		    if($erra) {
		      $line="<li>On insertion into documents_category: ".$erra.".<br />Please contact the administrator immediately.</li></ol>";
		      echo $line;
		      fwrite($filepointer, $line);
		      return;					
		    }
		  }
		  else if($num_rows != 1) {
		    $line="<li>Returned ".$num_rows." linking table entries for category ".$indices[$currentIndex]['level' . $levelCounter].
		      "with parent ".$parentID."for document".$docID.".<br />Please contact the administrator immediately.</li></ol>";
		    echo $line;
		    fwrite($filepointer, $line);
		    return;	
		  }
		}
	      }
	      /* if any of the levels are undefined, then we jump out of the loop,
		as there cannot be category with levels 1,3,etc.  must be contiguous. */
	      else {
		break;
	      }
	    }
	    $indices[$currentIndex]=NULL;
	    $currentIndex = "index".++$indexCounter;
	  }
	  /* insertion into db must have been successful
	    by now, so move the file to the "added" dir
     	    from the HARDCODED directory */
          $source = "../../xml_queue/".$Files;
          $destination = "../../xml_added/".$Files;
          $move_file_err = rename($source,$destination);
	  if($move_file_err) {
	    $line="</ol></p><b>Success</b>: processed and moved file " . $Files . "</p>\n";
	    echo $line;
	    fwrite ($filepointer, $line);
	  }
	  else {
	    $line="<li>Attempting to move file: ".$move_file_err.".<br>Please contact the administrator immediately.</li></ol>";
	    echo $line;
	    fwrite($filepointer,$line);
	    return;
	  }
	  
	  
	}
      }
    }
  }
  return;
}

/******************** AM Update 1/23/2006 ***************************/
/******************** Index cleanup logic **************************/
function remUnusedIndices() {
          $query = "CREATE TABLE `temp` (`id` int(6) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1";
          $result4=@mysql_query($query); 
          $erra=@mysql_error();
          if($erra) {
                $line="<li>On index cleanup step 1: ".$erra.".<br />Please contact the administrator immediately.</li></ol>";
                echo $line;
                fwrite($filepointer, $line);
                return;
          }

          $query = "INSERT INTO temp (Select j.id from (SELECT i.id, h.filecount from test_cat i left join ";
	  $query = $query."(SELECT g.cat_id, count(f.filename) as filecount from documents_category g, ";
	  $query = $query."documents f where g.doc_id = f.id group by g.cat_id) h on i.id = h.cat_id) j ";
	  $query = $query."where j.filecount IS NULL and j.id not in (SELECT d.id from (SELECT c.parent as id, count(c.id) as childCount ";
	  $query = $query."FROM (select a.id, b.id as parent from test_cat a left join test_cat b ";
	  $query = $query."on a.parent_id = b.id) c WHERE c.parent is not null GROUP BY c.parent) d ";
	  $query = $query."where d.ChildCount > 0))";
          $result5=@mysql_query($query); 
          $erra=@mysql_error();
          if($erra) {
                $line="<li>On index cleanup step 2: ".$erra.".<br />Please contact the administrator immediately.</li></ol>";
                echo $line;
                fwrite($filepointer, $line);
                return;
          }

          $query = "DELETE FROM test_cat where id in (SELECT id from temp) ";
          $result6=@mysql_query($query); 
          $erra=@mysql_error();
          if($erra) {
                $line="<li>On index cleanup step 3: ".$erra.".<br />Please contact the administrator immediately.</li></ol>";
                echo $line;
                fwrite($filepointer, $line);
                return;
          }

          $query = "drop table temp";
          $result7=@mysql_query($query); 
          $erra=@mysql_error();
          if($erra) {
                $line="<li>On index cleanup step 4: ".$erra.".<br />Please contact the administrator immediately.</li></ol>";
                echo $line;
                fwrite($filepointer, $line);
                return;
          }	
}
/********************************************************************/


/*****************
	MAIN APPLICATION
*****************/
if($_POST['parse']) {
/* connect to database */
	include("dblayer4.php"); 
//} see 678

$db = @mysql_select_db($database, $connection)
     or die ("couldn't select database - please report this problem to <a href='mailto:humanities.computing@nyu.edu'>the administrator</a>immediately.");

	$filePath=trim($_POST['folderName']);
	$viewFile=trim($_POST['fileName']);

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

	/************* AM 1/23/2006 ***************/
	/** Index cleanup - removes up to four levels of indicies that do not point to anything **/
	remUnusedIndices();
	remUnusedIndices();
	remUnusedIndices();
	remUnusedIndices();
	  
	fclose ($filepointer);
	include("../includes/footer.php");
	
} //something here is causing problems. What is this attached to? 
else {
	$dir_path="../../xml_queue/";
	if($_POST[view]) {
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
	</form>
	</p>
	
<?php
	include("../includes/footer.php");
}

?>
