<?php
	require_once 'common_functions.php';
	
	/** Gets the pending requests for the the currently logged in report. **/
	function get_pending_friends(){
		/** The return value **/
		$my_array = array();
		/** Getting the currently logged in user. **/
		$my_user = getUserId();
		$sql =	"
			SELECT user_a
			FROM friend_map
			WHERE user_b = '$my_user'
			AND direction = '0'
			AND status = '0'
				";
		$results = getDBResultsArray($sql);
		foreach($results as $row){
			array_push($my_array, $row["user_a"]);
		}
		$sql = "
			SELECT user_b
			FROM friend_map
			WHERE user_a = '$my_user'
			AND direction = '1'
			AND status = '0'
				";
		$results = getDBResultsArray($sql);
		foreach($results as $row){
			array_push($my_array, $row["user_b"]);
		}
		echo json_encode($my_array);
	}
	
	/** Send request **/
	function send_request($a_friend_id){
		$le_user = getUserId();
		$sql = "
				INSERT INTO friend_map (
					user_a,
					user_b,
					direction,
					status)
				VALUES(
					'$le_user',
					'$a_friend_id',
					'0',
					'0'
				)
		";
		if(sizeof(getDBResultInserted($sql, 0)) == 0){
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 500 Internal Server Error');
		};
	}
	
	/**Accepts an existing friend request**/
	function accept_friend($friend_id){
		$le_user = getUserId();
		$sql =	"
				UPDATE friend_map
				SET status = 1
				WHERE	user_a = $friend_id
				AND		user_b = $le_user
				AND		direction = 0
				AND 	status = 0
		";
		if(sizeof(getDBResultInserted($sql, 0)) == 0){
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 500 Internal Server Error');
		};
	}
?>