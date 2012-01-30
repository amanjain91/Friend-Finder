<?php
	include 'db_helper.php';
	include 'common_functions.php'
	function getCloseFriends($lat, $long){ 
		//Getting all the checked in friends.
		$all_check_ins = getFriendCheckIns(getUserId());
		$list_of_locations = new array();
		//Getting all the building ids.
		while ($row = $all_check_ins) {
			$list_of_locations->push($row["loc_id"]);
		}
		//Removing all the duplicate building ids
		// so as to find the distances.
		array_remove_duplicates($list_of_locations);
		//Array for mapping each building id to its distance. 
		//Will be reused later
		loc_dist = new array();
		foreach($list_of_locations as $value){
		//getting longitude and latitude to calculate diatance and putting distance in loc_dist
			$query = "SELECT * FROM location_table WHERE location_id='$value'";
			$result = mysql_fetch_assoc(mysql_query($query));
			$row = $result;
			$b_lat = $row["latitude"];
			$b_long = $row["longitude"];
			$dist = sqrt(pow($b_lat - $lat, 2) + pow($b_long-$long, 2));
			$loc_dist[$value] = $dist;
		}
		//Sorting loc_dist according to the cvalue.
		asort($loc_dist);
		//Deleting distance and putting it as an array
		for($loc_dist as $key=>$value){
			$loc_dist[$key] = new array();
		}
		//Getting the data again to restore the pointer.
		$all_check_ins = getFriendCheckIns(getUserId());
		while($row=all_check_ins){
			$loc_dist[$row["loc_id"]][]=$row["user_id"];
		}
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