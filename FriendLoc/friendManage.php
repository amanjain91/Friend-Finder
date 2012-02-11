<?php
	require_once "validate.php";
	require_once 'common_functions.php';
	
	/** Send request **/
	function addFriend($id)
	{
		if(!is_numeric($id))
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
			die();
		}
	
		$uid = getUserId();
		
		$exist = getDBResultsArray(
			"SELECT user_a AS sender, user_b AS recip, status
			FROM friend_map
			WHERE (user_a = $id AND user_b = $uid) OR (user_a = $uid AND user_b = $id);"
		);
		
		if(sizeof($exist) > 1)
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 500 Internal Server Error');
			die();
		}
		else if(sizeof($exist) == 1)
		{
			$row = $exist[0];
			
			if($row['status'] == 1 or $row['sender'] == $uid)
			{
				$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 500 Internal Server Error');
				die();
			}
			
			getDBResultAffected(
				"UPDATE friend_map
				SET status = 1
				WHERE user_a = $id
				AND user_b = $uid;"
			);
		}
		else
		{
			getDBResultInserted(
				"INSERT INTO friend_map (
					user_a,
					user_b,
					status
				)
				VALUES (
					'$uid',
					'$id',
					0
				);"
			);
		}
	}
	
	/**Removes the friend from current friend list**/
	function removeFriend($id)
	{
		if(!is_numeric($id))
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
			die();
		}
	
		$uid = getUserId();
		
		getDBResultAffected(
			"DELETE FROM friend_map
			WHERE (user_a = $uid AND user_b = $id)
			OR (user_a = $id AND user_b = $uid);"		
		);
	}
?>