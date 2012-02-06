<?php
	require_once "common_functions.php";
	
	if(!isCurrentUserValid())
	{
		$GLOBALS["_PLATFORM"]->sandboxHeader('HTTP/1.1 403 Forbidden');
		die();
	}
?>