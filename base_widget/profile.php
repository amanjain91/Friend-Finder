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
			$query = "SELECT first_name, last_name, img_url, phone_num, email_add FROM user_table WHERE user_id = '$friendID';";
		
			$info = getDBResultsArray($query);
		
			echo json_encode($info);
		}
		
		else
		{
			// REPLACE WITH 404 or 503 redirect
			echo "Sorry, not in Friend List.";
		}
	}
?>