<?php
	require_once 'common_functions.php';

	function testValidUser()
	{
		$valid_user = isCurrentUserValid();
		
		if(!$valid_user)
		{
			$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 403 Forbidden');
		}
		echo
		{
			echo json_encode(array("valid" => $valid_user));
		}
	}
?>