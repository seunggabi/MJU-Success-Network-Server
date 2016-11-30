<?php
	require_once "../config.php";
	require_once "config.php";
	
	forbidden($_POST, 2);

	echo json_encode(getScheduleList($token, $g_id));
	
	closeDB();
?>