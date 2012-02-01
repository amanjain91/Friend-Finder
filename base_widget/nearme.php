<?php

	include 'db_helper.php';
	include 'common_functions.php';
	function getCloseFriends($lat, $long){ 
		//Getting all the checked in friends.
		$all_check_ins = getFriendCheckIns(getUserId());
		$list_of_locations = array();
		//Getting all the building ids.
		foreach($all_check_ins as $row) 
		{
			array_push($list_of_locations, $row["loc_id"]);
		}
		//Removing all the duplicate building ids
		// so as to find the distances.
		array_unique($list_of_locations);
		//Array for mapping each building id to its distance. 
		//Will be reused later

		$loc_dist = array();
		$qry_str = "'" . implode("','", $list_of_locations) . "'";

		$query = "SELECT * FROM location_table WHERE location_id IN ($qry_str);";
		$result = getDBResultsArray($query);

		foreach($result as $row)
		{			
			$b_lat = $row["latitude"];
			$b_long = $row["longitude"];
			$dist = sqrt(pow($b_lat - $lat, 2) + pow($b_long-$long, 2));
			$loc_dist[$row["location_id"]] = $dist;
		}
		
		//Sorting loc_dist according to the cvalue.
		asort($loc_dist);
		//Deleting distance and putting it as an array
		foreach($loc_dist as $key=>$value){
			$loc_dist[$key] = array();
		}
		//Getting the data again to restore the pointer.
		foreach($all_check_ins as $row){
			array_push($loc_dist[$row["loc_id"]], array("id" => $row["user_id"]));
		}

		// PERHAPS CONVERT TO OTHER ENCODING HERE
		
		echo json_encode($loc_dist);
	}

	function getCloseLocations($lat, $long) {
		$locations = array();
		$locations[0] = array(
			"id" => "0",
			"location" => "CULC"
		);
		echo json_encode($locations);
	}	
?>