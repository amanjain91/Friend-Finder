<?php
	//CRU(D) for profile in our database.
	require_once 'common_functions.php'
	
	/**Creates a profile using the given parameters for the logged in user*/
	function createProfile($fname, $lname, $p_num, $eadd)
	{
		$p_id = getPrismId();
		$sql ="
			INSERT INTO user_table (
				prism_id,
				first_name,
				last_name,
				phone_num,
				email_add
			)
			VALUES (
				'$p_id',
				'$fname',
				'$lname',
				'$pnum',
				'$eadd'
			);
		";
		
		$res = getDBResultInserted($sql, 0);
		
		if(sizeof($res) == 0)
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 500 Internal Server Error');
	}
	
	/** Updating the profile of the user. **/
	function updateProfile($fname, $lname, $phone, $mail)
	{
		//Get the user id
		$u_id = getUserId();
		//Put his data directly into the database because getUserId makes
		//sure he has a valid user_id for the given prism id.
		//TODO
		$sql = "
				UPDATE 	user_table
				SET 	first_name='$fname', 
						last_name='$lname',
						phone_num = '$phone',
						email_add = '$mail',
				WHERE 	user_id='$u_id';
		";
		
		getDBResultAffected($sql);
	}
	
	/*Reads the profile for the given prism id.*/
	function getProfile()
	{
		$u_id = getUserId();
		
		$sql  = "
				SELECT	prism_id,
						first_name,
						last_name,
						phone_num,
						email_add,
						img_url
				FROM	user_table
				WHERE	user_id='$u_id'
		";
		$result = getDBResultsArray($sql);
		echo json_encode(
			{
				"prismid"	=>		$result["prism_id"], 
				"fname"		=> 		$result["first_name"],
				"lname"		=>		$result["last_name"],
				"pnum"		=>		$result["phone_num"],
				"email"		=>		$result["email_add"],
				"img_url"	=>		$result["prism_id"]
			}
		);
	}
?>
