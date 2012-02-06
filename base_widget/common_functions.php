<?php
	require_once 'db_helper.php';
	
	// Returns the prism id of the current user.
	function getPrismId()
	{
		global $_USER;
		return $_USER['uid'];
	}
	
	// Returns true if the user that is logged is in the system, false otherwise.
	function isCurrentUserValid()
	{
		$prism = getPrismId();
		$query = "SELECT user_id FROM user_table WHERE prism_id='$prism'";
		$results = getDBResultRecord($query);
		
		if(sizeof($results) == 0)
		{
			return false;
		}
		
		return true;
	}
	
	// Used to obtain the database user id of the currently logged in user.
	function getUserId()
	{
		$prism_id = getPrismId();
		$row = getDBResultRecord("
			SELECT user_id 
			FROM user_table 
			WHERE prism_id='$prism_id';
		");
		
		if(sizeof($row) == 0)
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
			die();
		}
		
		$user_id = $row['user_id'];
		return $user_id;
	}
	
	// Obtains a list of the specified user's friends.
	function getFriends($user_id) 
	{
		$arr = getDBResultsArray("SELECT user_b FROM friend_map WHERE status=1 AND user_a='$user_id' UNION SELECT user_a FROM friend_map WHERE status=1 AND user_b='$user_id'");
		$ret = array();
		
		foreach($arr as $row) 
		{
			foreach($row as $key=>$value) 
			{
				array_push($ret, $value);
			}
		}

		return $ret;
	}
	
	// Obtains a listing of all of the checkins for the friends of the specified user.
	function getFriendCheckIns($user_id)
	{
		return getDBResultsArray(
		"	
			SELECT * 
			FROM check_in
			WHERE user_id
			IN (
				SELECT user_a
				FROM friend_map
				WHERE user_b = '$user_id'
				AND STATUS =1
				UNION SELECT user_b
				FROM friend_map
				WHERE user_a = '$user_id'
				AND STATUS =1
			)
		");
	}
?>