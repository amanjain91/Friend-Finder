<?php
	include 'db_helper.php';
	include 'common_functions.php'
	function getCloseFriends($lat, $long){ 
		$all_check_ins = getFriendCheckIns(getUserId());
		$list_of_locations = new SplObjectStorage();
		while ($row = $all_check_ins) {
			if(!$list_of_locations->contains($row["loc_id"])){
				$list_of_locations->attach($row["loc_id"]);
			}
		}
		$count = $list_of_locations->count();
		$i = 0;
		$list_of_locations->rewind;
		
		while($i < $count){
			$probe = list_of_locations->current();
			
		}
		
		

		$friend = array();
		$friend[0] = array(
			"id" => "0",
			"prism" => "npapin3",
			"name" => "Nicolas Papin",
			"location" => "CULC",
			"img" => "img/thumbnail.jpg",
			"status" => "Working on MAS Project!"
		);
		echo json_encode($friend);
	}
?>