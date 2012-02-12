<?php
	// Used as an interface for obtaining data from GTPlaces.
	
	// Used for obtaining building data for a specified building.
	function getBuildingData($id)
	{
		$bldgs = getAllBuildingData();
		
		if(!$bldgs)
			return false;
			
		for($bldgs as $bldg)
		{
			if($bldg['b_id'] == $id)
			{
				return $bldg;
			}
		}
		
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
	
	// Used for obtaining nearby friend data.
	function getNearbyBuildingData($lat, $long, $limit = 0)
	{	
		$comp_func = function($a, $b) use ($lat, $long)
		{
			$a_dist = sqrt(pow($a['latitude'] - $lat, 2) + pow($a['longitude'] - $long, 2));
			$b_dist = sqrt(pow($b['latitude'] - $lat, 2) + pow($b['longitude'] - $long, 2));
			
			if($a_dist == $b_dist)
			{
				return 0;
			}
			else if($a_dist < $b_dist)
			{
				return -1;
			}
			else
			{
				return 1;
			}
		};
		
		$bldgs = getAllBuildingData();
		
		if(!$bldgs)
			return false;
			
		usort($bldgs, $comp_func);
		
		$ret = $bldgs;
		
		if($limit > 0)
			$ret = array_slice($bldgs, 0, $limit, True);
			
		return $ret;
	}
?>