<?php
	require_once "../config.php";
	require_once "config.php";
	
	if($_GET['mode'] == "check") {
		forbidden($_POST, 4);
		echo insertAttend($token, $g_id, $s_id, $u_id);
	}
	
	closeDB();
?>