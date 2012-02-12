<?php
	require_once 'validate.php';
	require_once 'common_functions.php';
	
	function checkIn($status, $tags, $loc_id) 
	{
		$locD = getBuildingData($loc_id);
		
		if(!$locD)
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
			die();
		}
	
		// Creates our tag list.
		$tags = trim(mysql_real_escape_string("$tags"));
		
		$tagArrTMP = explode(" ", $tags);
		
		$tagArr = array();
		foreach($tagArrTMP as $value)
		{
			$str = trim($value);
			
			if(strlen($str) > 0)
				array_push($tagArr, $str);
		}
		
		array_unique($tagArr);
	
		$loc = mysql_real_escape_string("$loc_id");
		$sta = mysql_real_escape_string("$status");
	
		//get userid and date		
		$user = getUserId();
		$date = date("ymdGis");

		//get rid of any existing check-ins
		$delQ = 
			"DELETE FROM check_in
			WHERE user_id = '$user'";
		$count = getDBResultAffected($delQ);
		
		// Insert the check in.
		$query = 
			"INSERT INTO check_in (user_id, loc_id, status, time) 
			VALUES ($user, '$loc', '$sta', NOW());";
		$res = getDBResultInserted($query, "id");
		
		// Insert tags
		$id = $res['id'];
		$tagV = "($id,'" . implode("'),($id,'", $tagArr) . "')";
		
		$tagQuery = 
			"INSERT INTO check_in_tag (checkin_id, tag)
			VALUES$tagV;";
			
		getDBResultInserted($tagQuery, "id");
	}
?>