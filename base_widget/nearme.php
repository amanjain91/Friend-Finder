<?php
	include 'db_helper.php';
	global $_USER;
	$userid = $_USER['uid'];
	function getCloseFriends($lat, $long){ 
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
?>
