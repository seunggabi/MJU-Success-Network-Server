<?php
	require_once "../config.php";
	require_once "config.php";
	
	if($_GET['mode'] == "add") {
		forbidden($_POST, 9);
		echo insertSchedule($token, $g_id, $s_name, $s_content, $s_datetime, $s_gps_logitude, $s_gps_latitude, $s_gps_location, $s_gps_name);
	}
	
	closeDB();
?>