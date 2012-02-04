<?php

include_once 'db_helper.php';

/**
* This PHP function gets the data from GT places, takes out address and puts it in our database.
* run this to update our database.
*/
function getGTPlacesData() {
	$locs = json_decode(file_get_contents("http://m.cip.gatech.edu/api/gtplaces/buildings"), true);
	foreach($locs as $rows)
	{
		//Building Id.
		$b_id = mysql_real_escape_string($rows["b_id"]);
		//Latitude
		$lat = $rows["latitude"];
		//Longitude
		$lon = $rows["longitude"];
		//The building name.
		$name = mysql_real_escape_string($rows["name"]);
		//The image URL of the page.
		$img = mysql_real_escape_string($rows["image_url"]);
		$sql  = "INSERT INTO location_table (building_id, longitude, latitude, building_name, img_url) 
				VALUES ('$b_id', $lon, $lat, '$name', '$img');";
		
		getDBResultInserted($sql, 0);
	}
	return $locs;
}

?>