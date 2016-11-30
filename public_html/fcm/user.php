<?php
	require_once "../config.php";
	require_once "config.php";

	if($_GET['mode'] == "add") {
		$data = $_POST;

		insertLog($token, 0, "개인정보수정");
		echo updateUser($token, $data);
	}
	
	closeDB();
?>