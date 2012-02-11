<?php
	//CRU(D) for profile in our database.
	require_once 'common_functions.php';
	
	/**Creates a profile using the given parameters for the logged in user*/
	function createProfile($fname, $lname, $p_num, $eadd)
	{
		$fname = mysql_real_escape_string($fname);
		$lname = mysql_real_escape_string($lname);
		$p_num = mysql_real_escape_string($p_num);
		$eadd = mysql_real_escape_string($eadd);
	
		$p_id = getPrismId();
		$sql = "
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
				'$p_num',
				'$eadd'
			);
		";
		
		$res = getDBResultInserted($sql, 0);
		
		if(sizeof($res) == 0)
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 500 Internal Server Error');
	}
	
	/** Updating the profile of the user. **/
	function updateProfile($fname, $lname, $phone, $mail, $img_url)
	{
		$fname = mysql_real_escape_string($fname);
		$lname = mysql_real_escape_string($lname);
		$phone = mysql_real_escape_string($phone);
		$mail = mysql_real_escape_string($mail);
		$img_url = mysql_real_escape_string($img_url);
	
		//Get the user id
		$uid = getUserId();
		//Put his data directly into the database because getUserId makes
		//sure he has a valid user_id for the given prism id.
		//TODO
		$sql = "
				UPDATE 	user_table
				SET 	first_name = '$fname', 
						last_name = '$lname',
						phone_num = '$phone',
						email_add = '$mail',
						img_url = '$img_url'
				WHERE 	user_id = $uid;
		";
		
		getDBResultAffected($sql);
	}
	
	/*Reads the profile for the given prism id.*/
	function getProfile()
	{
		$u_id = getUserId();
		
		$sql  = "
				SELECT	first_name,
						last_name,
						phone_num,
						email_add,
						img_url
				FROM	user_table
				WHERE	user_id='$u_id'
		";
		$result = getDBResultRecord($sql);
		
		echo json_encode($result);
	}
?>
