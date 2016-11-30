<?php
	require_once "../config.php";
	require_once "config.php";
	
	if($_GET['mode'] == "add") {
		forbidden($_POST, 4);
		echo insertGroup($token, $g_name, $g_intro, $g_tag);
	}
	else if($_GET['mode'] == "join") {
		forbidden($_POST, 2);
		echo insertJoin($token, $g_id);
	}
	else if($_GET['mode'] == "edit") {
		forbidden($_POST, 6);
		echo updateGroup($token, $g_id, $g_name, $g_intro, $g_tag, $g_hidden);
	}
	else if($_GET['mode'] == "exit") {
		forbidden($_POST, 2);
		echo deleteJoin($token, $g_id);
	}
	else if($_GET['mode'] == "status") {
		forbidden($_POST, 3);
		echo deleteJoin($token, $g_id, $u_id);
	}
	
	closeDB();
?>