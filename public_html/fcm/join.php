<?php
	require_once "../config.php";
	require_once "config.php";
	
	if($_GET['mode'] == "changeAlarm") {
		forbidden($_POST, 2);
		echo changeJoinAlarm($token, $g_id);
	}
	else if($_GET['mode'] == "changeStatus") {
		forbidden($_POST, 3);
		echo changeJoinStatus($token, $g_id, $u_id);
	}

	closeDB();
?>