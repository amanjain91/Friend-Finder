<?php
	include 'db_credentials.php';
	
	// Initialize the connection.
	$connection = mysql_connect(
		$db_host,
		$db_username,
		$db_password
	);
	
	// If the connection failed, error out.
	if(!$connection)
	{
		die("Error connecting to the database.<br/><br/>" . 		
		mysql_error());
	}
	
	// Attempt to select the appropriate database.
	$db_select = mysql_select_db($db_database);
	if(!$db_select){die("Error with db select.<br/><br/>".mysql_error());}
	
	// Used for getting the results of a query as an associated array.
	function getDBResultsArray($dbQuery)
	{
		$dbResults=mysql_query($dbQuery);
		if(!$dbResults)
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader("HTTP/1.1 500 Internal Server Error");
			die();
		}
		
		// Test for results.
		$resultsArray = array();
		if(mysql_num_rows($dbResults) > 0)
		{
			while($row = mysql_fetch_assoc($dbResults))
			{
				$resultsArray[] = $row;
			}
		}
		else // If we do not have any results, return an empty array.
		{
			return array();
		}
		
		return $resultsArray;
	}
	
	// Used for obtaining a single record from the database.
	function getDBResultRecord($dbQuery)
	{
		$dbResults=mysql_query($dbQuery);
	
		if(!$dbResults)
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader("HTTP/1.1 500 Internal Server Error");
			die();
		}
	
		if(mysql_num_rows($dbResults) > 1)
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 500 Internal Server Error');
			die();
		}
		else if(mysql_num_rows($dbResults) == 0) // If nothing is found, return nothing
		{
			return array();
		}
		
		return mysql_fetch_assoc($dbResults);
	}
	
	// Used for database updates.
	function getDBResultAffected($dbQuery)
	{
		$dbResults=mysql_query($dbQuery);
		if($dbResults)
		{
			return array('rowsAffected'=>mysql_affected_rows());
		}
		else
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 500 Internal Server Error');
			die(mysql_error());
		}
	}
	
	// Used for database inserts.
	function getDBResultInserted($dbQuery,$id)
	{
		$dbResults=mysql_query($dbQuery);
		if($dbResults)
		{
			return array($id=>mysql_insert_id());
		}
		else
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 500 Internal Server Error');
			die(mysql_error());
		}
	}
?>
