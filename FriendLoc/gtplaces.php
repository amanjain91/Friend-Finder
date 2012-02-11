<?php
	// Used as an interface for obtaining data from GTPlaces.
	
	// Used for obtaining building data for a specified building.
	function getBuildingData($id)
	{
		$loc = file_get_contents("http://m.cip.gatech.edu/api/gtplaces/buildings/$id");
		
		if($loc)
			return json_decode($loc, true);
		else
			return false;
	}
	
	// Used for obtaining a listing of all building data.
	function getAllBuildingData()
	{
		$locs = file_get_contents("http://m.cip.gatech.edu/api/gtplaces/buildings");
		
		if($locs)
			return json_decode($locs, true);
		else
			return false;
	}
?>