<?php
	include "common_functions.php";

	/**
	 * Returns a list of all of my friends.
	 */
	function getAllFriends()
	{	
		$friends = getFriends(getUserId());
		
		// POSSIBLY ADD STATEMENT HERE TO GET FULL FRIEND ROWS, NOT JUST ID'S.
		
		echo json_encode($friends);
	}
?>