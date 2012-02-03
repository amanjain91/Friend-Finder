<?php

function getGTPlacesData() {
	$locs = json_decode(file_get_contents("http://m.cip.gatech.edu/api/gtplaces/buildings"), true);
	$i = 0;
	foreach($locs as $rows){
		$i++;
		$b_id = $rows["building_id"];
		$lat = $rows["latitude"];
		$lon = $rows["longitude"];
		$name = $rows["name"];
		$img = $rows["image_url"];
		$sql  = "INSERT INTO location_table (
					location_id, 
					building_id, 
					longitude, 
					latitude, 
					building_name, 
					img_url
				)
				VALUES (
					$i, 
					$b_id, 
					$lon, 
					$lat,
					$name, 
					$img
				)";
		//I don't know what function from db helper to call.
		// FIXME for Nick.
	}
	return $locs;
}

?>