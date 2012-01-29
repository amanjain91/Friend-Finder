<?php
	include 'db_helper.php';
	
	global $_USER;
	$prismid = $_USER['uid'];
	$curr_user_row = getDBResultRecord("SELECT user_id FROM user_table WHERE prism_id = '$prismid'");
	$user_id = $curr_user_row['user_id'];
	//echo($prismid);
	
	function getCloseFriends($location)
	{ 		
		$friends = array();
		$friends[0] = array(
			"id" => "0",
			"prism" => "npapin3",
			"name" => "Nicolas Papin",
			"location" => "CULC",
			"img" => "img/thumbnail.jpg",
			"status" => "Working on MAS Project!"
		);
		
		echo json_encode($friends);
	}
	
	function getCloseLocations($location)
	{
		$locations = array();
		$locations[0] = array(
			"id" => "0",
			"location" => "CULC"
		);
		
		echo json_encode($locations);
	}
?>
