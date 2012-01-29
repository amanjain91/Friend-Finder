<?php
	include 'db_helper.php';
	include 'common_functions.php'
	function getCloseFriends($lat, $long){ 
		$all_check_ins = getFriendCheckIns(getUserId());
		$list_of_locations = new array();
		while ($row = $all_check_ins) {
			$list_of_locations->push($row["loc_id"]);
		}
		array_remove_duplicates($list_of_locations);
		loc_dist = new array();
		foreach($list_of_locations as $value){
			$query = "SELECT * FROM location_table WHERE location_id='$value'";
			$result = mysql_fetch_assoc(mysql_query($query));
			$row = $result;
			$b_lat = $row["latitude"];
			$b_long = $row["longitude"];
			$dist = sqrt(pow($b_lat - $lat, 2) + pow($b_long-$long, 2));
			
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

	function getCloseLocations($lat, $long)
	{
		$locations = array();
		$locations[0] = array(
			"id" => "0",
			"location" => "CULC"
		);
		
		echo json_encode($locations);
	}	
?>