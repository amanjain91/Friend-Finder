<?php
	require_once "validate.php";
	require_once "common_functions.php";

	/**
	 * Returns a list of all of my friends.
	 */
	function getAllFriends() 
	{
		$ret = array();
		$uid = getUserId();
	
		// Friend IDs
		$friend_ids = getFriends($uid);
		
		// Get the actual friend data
		$qry_str = "'" . implode("','", $friend_ids) . "'";
		$query = "SELECT user_id AS id, first_name, last_name, img_url FROM user_table WHERE user_id IN ($qry_str);";
		$friends = getDBResultsArray($query);
		
		$ret['friends'] = $friends;
		
		// Get sent friend request data
		$sent = getDBResultsArray(
			"SELECT user_id AS id, first_name, last_name, img_url
			FROM user_table
			WHERE user_id IN (
				SELECT user_b
				FROM friend_map
				WHERE user_a = '$uid'
				AND STATUS = 0
			);"
		);
		
		$ret['sent'] = $sent;
		
		// Get received friend request data
		$recv = getDBResultsArray(
			"SELECT user_id AS id, first_name, last_name, img_url
			FROM user_table
			WHERE user_id IN (
				SELECT user_a
				FROM friend_map
				WHERE user_b = '$uid'
				AND STATUS = 0
			);"
		);
		
		$ret['received'] = $recv;
		
		// Return to the caller
		echo json_encode($ret);
	}
	
	/**
	 * Checks if the logged in user and friend selected are friends,
	 * if yes, returns friend info
	 */
	function getInfo($friendID)
	{	
		$user_id = getUserId();
		
		$friends = getFriends($user_id);
		
		$i = 0;
		$found = FALSE;
		
		while($i < sizeof($friends))
		{
			if ($friendID == $friends[$i])
			{
				$found = TRUE;
				break;
			}
			
			$i++;
		}
		
		if($found)
		{
			$frnd = mysql_real_escape_string($friendID);
		
			$query = "SELECT first_name, last_name, img_url, phone_num, email_add FROM user_table WHERE user_id = '$frnd';";
		
			$info = getDBResultRecord($query);
		
			echo json_encode($info);
		}
		else
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 403 Forbidden');
			die();
		}
	}
?>