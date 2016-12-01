<?php
	require_once "../config.php";
	require_once "config.php";
	
	echo json_encode(getAttendList($token, $s_id));
	
	closeDB();
?>