<?php
	//CRU(D) for profile in our database.
	include_once 'db_helper.php'
	include_once 'common_functions.php'
	
	/**Creates a profile using the given parameters for the logged in user*/
	function create_profile($fname, $lname, $p_num, $eadd){
		$p_id = get_prism_id();
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
			)
		";
		getDBResultAffected($sql);
		return;
	}
	
	/** Updating the profile of the user. **/
	function update_profile($prism_id, $fname, $lname, $phone, $mail){
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
				WHERE 	user_id='$u_id'
				AND		prism_id='$prism_id'
		";
		getDBResultsArray($sql);
		return;
	}
	
	/*Reads the profile for the given prism id.*/
	function read_profile($p_id){
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
