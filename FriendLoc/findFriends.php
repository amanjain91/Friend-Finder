<?php
	require_once "validate.php";
	require_once 'common_functions.php';

	/**
	 * Returns a list of users searched by input
	 */
	function findFriends($name)
	{	
		$arr = explode(" ", trim(mysql_real_escape_string($name)));
		
		if (sizeof($arr) == 0)
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
			die();
		}
		
		$uid = getUserId();
		
		$cond = implode("|", $arr);
		
		$res = getDBResultsArray(
			"SELECT user_id AS id, first_name, last_name, img_url 
			FROM user_table 
			WHERE user_id <> $uid
			AND (first_name REGEXP '$cond' OR last_name REGEXP '$cond')
			AND user_id NOT IN (
				SELECT user_a
				FROM friend_map
				WHERE user_b = $uid
				UNION SELECT user_b
				FROM friend_map
				WHERE user_a = $uid
			);"
		);
		
		echo json_encode($res);
	}
?>