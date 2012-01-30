<?php
	include "common_functions.php";

	/**
	 * Returns a list of all of my friends.
	 */
	function getAllFriends()
	{	
		$friends = getFriends(getUserId());
		
		echo json_encode($friends);
	}
?>