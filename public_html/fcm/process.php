<?php
	require_once "../config.php";
	require_once "config.php";

	$tokens = getUserList();
	
	if ($message == "") {
		$message = "새글이 등록되었습니다.";
	}

	echo sendMessage($tokens, $message);

	closeDB();
?>