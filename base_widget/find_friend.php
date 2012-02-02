<?php
	include_once 'db_helper.php';

	/**
	 * Returns a list of users searched by input
	 */
	function findFriends()
	{	
		$arr = explode(" ", $name);
		
		if (sizeof($arr) == 0)
		{
			echo "Please Enter a Name";
		}
		
		else if (sizeof($arr) == 1)
		{
			$query = "SELECT first_name, last_name, img_url FROM user_table WHERE first_name=$arr[0] OR last_name=$arr[0];";
			
			$friends = getDBResultsArray($query);
			echo json_encode($friends);
		}
		
		else
		{
			$query = "SELECT first_name, last_name, img_url FROM user_table WHERE first_name=$arr[0] AND last_name=$arr[1];";
			
			$friends = getDBResultsArray($query);
			echo json_encode($friends);
		}
		
		
	}
?>