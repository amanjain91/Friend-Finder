<?php	
	/**A function to check if the given email is valid or not**/
	function is_valid_email($an_email){
		if(filter_var($email_a, FILTER_VALIDATE_EMAIL)){
			return true;
		}
		return false;
	}
?>