<?php	
	/**A function to check if the given email is valid or not**/
	function is_valid_email($an_email){
		if(filter_var($email_a, FILTER_VALIDATE_EMAIL)){
			return true;
		}
		return false;
	}
	
	/**checks if this phone number contains 10 digits.**/
	function is_valid_phone_number($a_number){
		$all_letters = str_split($a_number);
		$count_of_valid_numbers = 0;
		for($i = 0; $i < sizeof($all_letters); $i++){
			if(ctype_digit($all_letters[$i])){
				$count_of_valid_numbers++;
			}
		}
		if($count_of_valid_numbers == 10){
			return true;
		}
		return false;
	}
	
	/**Checks if this is a valid last name or first name**/
	function is_valid_name($a_name){
		return ctype_alpha($a_name);
	}
?>