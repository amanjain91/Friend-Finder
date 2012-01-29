<?php
	include 'db_helper.php';
	include 'common_functions.php'
	function getCloseFriends($lat, $long){ 
		$all_check_ins = getFriendCheckIns(getUserId());
		$list_of_locations = new array();
		while ($row = $all_check_ins) {
			if(!$list_of_locations->contains($row["loc_id"])){
				$list_of_locations->push($row["loc_id"]);
			}
		}
		foreach($list_of_locations as $value){
		
		}
		$count = $list_of_locations->count();
		$i = 0;
		$list_of_locations->rewind;
		
		while($i < $count){
			$probe = list_of_locations->current();
			
		}
		
		$friends = array();
		$friends[0] = array(
			"id" => "0",
			"prism" => "npapin3",
			"name" => "Nicolas Papin",
			"location" => "CULC",
			"img" => "img/thumbnail.jpg",
			"status" => "Working on MAS Project!"
		);
		echo json_encode($friend);
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