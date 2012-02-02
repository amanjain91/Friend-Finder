<?php
include 'db_helper.php';
include 'common_functions.php';
function getCloseLocations($lat, $long) {
		$locations = array();
		$query = "SELECT * FROM location_table";
		$result = getDBResultsArray($query);
		$i=0;
		foreach ($result as $row)
		{
			$cur_lat = $row["latitute"];
			$cur_long = $row["longitude"];
			$dist = sqrt(pow($cur_lat - $lat, 2) + pow($cur_long-$long, 2));
			$locations[i]['id'] = $row['location_id'];
			$locations[i]['location'] = $row['building_name'];
			$locations[i++]['dist'] = $dist;
			
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
					$locations[$j+1]['id'] = $temp[0]['id'];
					$locations[$j+1]['id'] = $temp[0]['id'];

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
