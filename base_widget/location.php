<?php
	include_once 'db_helper.php';
	include_once 'common_functions.php';
	function getLocationName($loc_id) {	
		$query = "SELECT building_name FROM location_table WHERE location_id=$loc_id";
		$name = getDBResultRecord($query);
		//echo json_encode($name);
		$user = getUserId();
		$date = date("ymdGis");
		echo json_encode($date);
		//$query = "INSERT INTO check_in 
	}

	
