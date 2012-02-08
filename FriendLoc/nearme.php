<?php
	require_once 'validate.php';
	require_once 'common_functions.php';
	
	// Obtains the list of friends sorted by distance.
	function getCloseFriends($lat, $long)
	{
		//Translation array from id to locations. 
		//Basically converting format as required for homepage.
		$location_id_to_name = array();
		
		//Getting all the checked in friends.
		$all_check_ins = getFriendCheckIns(getUserId());
		if(sizeof($all_check_ins) == 0)
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
			die();
		}
		
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

		// Run query
		$query = "SELECT * FROM location_table WHERE location_id IN ($qry_str);";
		$result = getDBResultsArray($query);
		
		foreach($result as $row) 
		{
			$b_lat = $row["latitude"];
			$b_long = $row["longitude"];
			$dist = sqrt(pow($b_lat - $lat, 2) + pow($b_long-$long, 2));
			$loc_dist[$row["building_name"]] = $dist;
			$location_id_to_name[$row["location_id"]] = $row["building_name"];
		}
		
		//Sorting loc_dist according to the cvalue.
		asort($loc_dist);
		
		//Deleting distance and putting it as an array
		foreach($loc_dist as $key=>$value)
		{
			$loc_dist[$key] = array();
		}
		
		//Getting the data again to restore the pointer.
		foreach($all_check_ins as $row)
		{
			$my_user_id = $row["user_id"];
			$user_data = getDBResultRecord("SELECT * FROM `user_table` WHERE user_id='$my_user_id'");
			array_push(
				$loc_dist[$location_id_to_name[$row["loc_id"]]], 	
				array(
					"fname"		=>	$user_data["first_name"], 
					"lname" 	=> 	$user_data["last_name"], 
					"status" 	=>	$row["status"], 
					"time"  	=>	$row["time"],
					"img_url"	=>	$user_data["img_url"],
					"nphone"	=>	$user_data["phone_num"]
				)
			);
			//FORMAT <a href="tel:1-408-555-5555">Person Name</a>
			//<a href='tel:123-555-1212'>Person name</a>
		}
		
		echo json_encode($loc_dist);
	}

	// Returns a list of the X closest locations.
	function getCloseLocations($lat, $long) 
	{
		if(!(is_numeric($lat) and is_numeric($long)))
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
			die();
		}
		
		$limit = 10;
		$query = 
			"SELECT location_id AS id, building_name AS location 
			FROM `location_table` 
			ORDER BY SQRT(POW((longitude - $long), 2) + POW((latitude - $lat), 2)) ASC
			LIMIT $limit;";
		
		$res = getDBResultsArray($query);
		
		if(sizeof($res) == 0)
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
			die();
		}
	
		echo json_encode($res);
	}	
?>