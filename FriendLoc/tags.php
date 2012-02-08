<?php
	require_once 'validate.php';
	require_once 'common_functions.php';
	
	function getNearbyTagsLoc($loc_id) 
	{
		$loc = mysql_real_escape_string("$loc_id");
		$query = "SELECT latitude, longitude FROM location_table WHERE location_id='$loc'";
		$row = getDBResultRecord($query);
		
		if(sizeof($row) < 1)
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
			die();
		}
		
		getNearbyTags($row['latitude'], $row['longitude']);
	}

	function getNearbyTags($lat, $long)
	{
		$retArray = array();
		$user = getUserId();
	
		if(!(is_numeric($lat) and is_numeric($long)))
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
			die();
		}
		
		$limit = 10;
	
		// GET POPULAR FRIEND TAGS
		$friend_checks = getFriendCheckIns($user);
		$fcheckins = array();
		foreach($friend_checks as $value)
		{
			array_push($fcheckins, $value['checkin_id']);
		}
		
		$fquery = implode("','", $fcheckins);
		$fresults = getDBResultsArray(
			"SELECT tag 
			FROM check_in_tag 
			WHERE checkin_id IN ('$fquery') 
			GROUP BY tag 
			ORDER BY COUNT(tag) 
			DESC LIMIT $limit;"
		);
		
		$retArray['friends'] = $fresults;
		
		// GET POPULAR NEARBY TAGS
		$nquery = 
			"SELECT location_id AS id 
			FROM location_table 
			ORDER BY SQRT(POW((longitude - $long), 2) + POW((latitude - $lat), 2)) ASC
			LIMIT $limit;";
		
		$locRes = getDBResultsArray($nquery);
		
		if(sizeof($locRes) == 0)
		{
			$retArray['nearby'] = array();
			echo json_encode($retArray);
			return;
		}
	
		$locations = array();
		foreach($locRes as $value)
		{
			array_push($locations, $value['id']);
		}
	
		$locs = implode("','", $locations);
		$nearCheckinsQuery = 
			"SELECT checkin_id
			FROM check_in
			WHERE loc_id IN ('$locs');";
		$nearCheckinsRes = getDBResultsArray($nearCheckinsQuery);
		
		if(sizeof($nearCheckinsRes) == 0)
		{
			$retArray['nearby'] = array();
			echo json_encode($retArray);
			return;
		}
		
		$nearCheckins = array();
		foreach($nearCheckinsRes as $value)
		{
			array_push($nearCheckins, $value['checkin_id']);
		}	
		
		$nearQuery = implode("','", $nearCheckins);
		$nearRes = getDBResultsArray(
			"SELECT tag 
			FROM check_in_tag 
			WHERE checkin_id IN ('$nearQuery') 
			GROUP BY tag 
			ORDER BY COUNT(tag) 
			DESC LIMIT $limit;"
		);
		
		$retArray['nearby'] = $nearRes;
		
		echo json_encode($retArray);
	}
?>
	
