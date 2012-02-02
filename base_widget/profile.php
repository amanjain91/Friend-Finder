<?php
	include "common_functions.php";

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
		
		while(i < sizeof($friends)
		{
			if ($friendID == $friends[i])
			{
				$found = TRUE;
				break;
			}
			
			i++;
		}
		
		
		if($found)
		{		
			$query = "SELECT first_name, last_name, img_url , phone_num FROM user_table WHERE user_id = "$friend_ID";";
		
			$info = getDBResultsArray($query);
		
			echo json_encode($info);
		}
		
		else
		{
			echo "Sorry, not in Friend List.";
		}
	}
?>