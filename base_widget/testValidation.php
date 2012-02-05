<?php

include 'common_functions.php';

function testValidUser()
{
	$prism = get_prism_id();
	$valid_user = show_profile_page($prism);
	
	if(!$valid_user)
	{
		$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 404 Not Found');
	}
	echo
	{
		echo json_encode(array("valid" => $valid_user));
	}
}

?>