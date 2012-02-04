<?php
/**
* This PHP function gets the data from GT places, takes out address and puts it in our database.
* run this to update our database.
*/
function getGTPlacesData() {
	$locs = json_decode(file_get_contents("http://m.cip.gatech.edu/api/gtplaces/buildings"), true);
<<<<<<< HEAD
	foreach($locs as $rows)
	{
=======
	$i = 0;
	foreach($locs as $rows){
		//Incrementing location id
		$i++;
		//Building Id.
>>>>>>> cc44f1e0b24119354641ab429f7f9feb8c806129
		$b_id = $rows["building_id"];
		//Latitude
		$lat = $rows["latitude"];
		//Longitude
		$lon = $rows["longitude"];
		//The building name.
		$name = $rows["name"];
		//The image URL of the page.
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