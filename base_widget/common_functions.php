<?php
	include_once 'db_helper.php';
	function getUserId(){
		global $_USER;
		$prismid = $_USER['uid'];
		$prismid= getDBResultRecord("SELECT user_id FROM user_table WHERE prism_id='$prismid'");
		$prismid = $prismid['user_id'];
		return $prismid;
	}
	function getFriends($user_id){
		return getDBResultsArray("SELECT user_b FROM friend_map WHERE status=1 AND user_a='$user_id' UNION SELECT user_a FROM friend_map WHERE status=1 AND user_b='$user_id'");
	}
	
	function getFriendCheckIns($user_id){
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