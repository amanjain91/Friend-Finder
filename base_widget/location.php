<?php
	include_once 'db_helper.php';
	include_once 'common_functions.php';
	function getLocationName($loc_id) {	
		//get building name using loc_id
		$query = "SELECT building_name FROM location_table WHERE location_id=$loc_id";
		$name = getDBResultRecord($query);



	}

	function updateStatus($status, $loc_id) {
		//get userid and date		
		$user = getUserId();
		$date = date("ymdGis");

		//check if userid already exists
		$query = "SELECT COUNT(*) FROM check_in WHERE user_id = $user";
		$count = getDBResultRecord($query);
	
		if ($count == 0) //addd new entry
		{
			$query = "INSERT INTO check_in (user_id, loc_id, status, time) VALUES ($user, $loc_id, $status, $date)";
			$exec = getDBesultRecord($query);
		}

		if (count == 1) //update entry
		{
			$query = "UPDATE check_in SET loc_id = $loc_id, status = $status, time = $date WHERE user_id = $user";
			$exec = getDBesultRecord($query);
		}

		else
		{
			echo "error";
		}
	}

?>
	
