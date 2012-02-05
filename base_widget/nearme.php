<?php
	require_once 'common_functions.php';
	
	function getCloseFriends($lat, $long){
		//Translation array from id to locations. 
		//Basically converting format as required for homepage.
		$location_id_to_name = array();
		//Getting all the checked in friends.
		$all_check_ins = getFriendCheckIns(getUserId());
		$list_of_locations = array();
		//Getting all the building ids.
		foreach($all_check_ins as $row) {
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
		
		foreach($result as $row) {
			$b_lat = $row["latitude"];
			$b_long = $row["longitude"];
			$dist = sqrt(pow($b_lat - $lat, 2) + pow($b_long-$long, 2));
			$loc_dist[$row["building_name"]] = $dist;
			$location_id_to_name[$row["location_id"]] = $row["building_name"];
		}
		
		//Sorting loc_dist according to the cvalue.
		asort($loc_dist);
		//Deleting distance and putting it as an array
		foreach($loc_dist as $key=>$value){
			$loc_dist[$key] = array();
		}
		//Getting the data again to restore the pointer.
		foreach($all_check_ins as $row){
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

function getCloseLocations($lat, $long) {
		$locations = array();
		$query = "SELECT * FROM location_table";
		$result = getDBResultsArray($query);
		$i=0;
		foreach ($result as $row)
		{
			$cur_lat = $row["latitude"];
			$cur_long = $row["longitude"];
			$dist = sqrt(pow($cur_lat - $lat, 2) + pow($cur_long-$long, 2));
			$locations[$i]['id'] = $row['location_id'];
			$locations[$i]['location'] = $row['building_name'];
			$locations[$i++]['dist'] = $dist;
			
		}
		
		//old school - bubble sort by dist
		$temp = array();
		$num_buildings = count($locations);
		for ($i=0; $i<$num_buildings; $i++) {
			for ($j=0; $j<$num_buildings-1; $j++) {
				if ($locations[$j]['dist']>$locations[$j+1]['dist']) {
					$temp[0]['id'] = $locations[$j]['id'];
					$temp[0]['location'] = $locations[$j]['location'];
					$temp[0]['dist'] = $locations[$j]['dist'];

					$locations[$j]['id'] = $locations[$j+1]['id'];
					$locations[$j]['location'] = $locations[$j+1]['location'];
					$locations[$j]['dist'] = $locations[$j+1]['dist'];

					$locations[$j+1]['id'] = $temp[0]['id'];
					$locations[$j+1]['location'] = $temp[0]['location'];
					$locations[$j+1]['dist'] = $temp[0]['dist'];

				}
			}
		}
		$toret = array();
		//return top 5
		for ($i = 0; $i<5; $i++) {
			$toret[$i]['id'] = $locations[$i]['id'];
			$toret[$i]['location'] = $locations[$i]['location'];			
		}
		
		echo json_encode($toret);
	}	
?>