<?php
	require_once "../config.php";
	require_once "config.php";
	
	echo json_encode(getChatting($token, $g_id));
	
	closeDB();
?>