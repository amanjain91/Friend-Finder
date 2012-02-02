<?php

function getGTPlacesData()
{
	$locs = json_decode(file_get_contents("http://m.cip.gatech.edu/api/gtplaces/buildings"), true);

	return $locs;
}

?>