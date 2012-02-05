<?php
	include_once 'db_helper.php';
	function get_prism_id(){
		global $_USER;
		return $_USER['uid'];
	}
	
	function show_profile_page($p_id){
		$query = "SELECT user_id FROM user_table WHERE prism_id='$p_id'";
		$results = getDBResultsArray();
		if(sizeof($results) == 0){
			return true;
		}
		return false;
	}
	
	function getUserId(){
		$prismid = get_prism_id();
		$prismid= getDBResultRecord("
			SELECT user_id 
			FROM user_table 
			WHERE prism_id='$prismid'
		");
		$prismid = $prismid['user_id'];
		return $prismid;
	}
	
	function getFriends($user_id) {
		$arr = getDBResultsArray("SELECT user_b FROM friend_map WHERE status=1 AND user_a='$user_id' UNION SELECT user_a FROM friend_map WHERE status=1 AND user_b='$user_id'");
		$ret = array();
		
		foreach($arr as $row) {
			foreach($row as $key=>$value) {
				array_push($ret, $value);
			}
		}

		return $ret;
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