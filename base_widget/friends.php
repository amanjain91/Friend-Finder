<?php
	include "common_functions.php";

	/**
	 * Returns a list of all of my friends.
	 */
	function getAllFriends() {	
		$friend_ids = getFriends(getUserId());
		$qry_str = "'" . implode("','", $friend_ids) . "'";
		$query = "SELECT user_id AS id, first_name, last_name, img_url FROM user_table WHERE user_id IN ($qry_str);";
		$friends = getDBResultsArray($query);
		echo json_encode($friends);
	}
?>