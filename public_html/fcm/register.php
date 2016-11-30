<?php
	require_once "../config.php";
	require_once "config.php";
	
	forbidden($_POST, 1);

	echo insertUser($token);
	
	closeDB();
?>