<?php
	require_once 'validate.php';
	require_once 'common_functions.php';
	
	function getNearbyTagsLoc($loc_id) 
	{
		$loc = getBuildingData($loc_id);
		
		if(!$loc)
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
			die();
		}
		
		getNearbyTags($loc['latitude'], $loc['longitude']);
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
		$locRes = getNearbyBuildingData($lat, $long, $limit);
		
		if(!$locRes)
		{
			$retArray['nearby'] = array();
			echo json_encode($retArray);
			return;
		}
	
		$locations = array();
		foreach($locRes as $value)
		{
			array_push($locations, $value['b_id']);
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
	
