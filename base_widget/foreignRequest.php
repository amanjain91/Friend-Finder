<?php

function getGTPlacesData() {
	$locs = json_decode(file_get_contents("http://m.cip.gatech.edu/api/gtplaces/buildings"), true);
	foreach($locs as $rows)
	{
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
					'$b_id', 
					'$lon', 
					'$lat',
					'$name', 
					'$img'
				)";
		
		getDBResultInserted($sql, 0);
	}
	return $locs;
}

?>