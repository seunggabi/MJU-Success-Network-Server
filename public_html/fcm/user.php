<?php
	require_once "../config.php";
	require_once "config.php";

	if($_GET['mode'] == "add") {
		$data = $_POST;

		insertLog($token, 0, "개인정보수정");
		echo updateUser($token, $data);
	}
	else if($_GET['mode'] == "changeAlarm") {
		$data = $_POST;
		
		forbidden($_POST, 2);
		insertLog($token, 0, "알람[".$data['u_alarm']."]수정");
		echo updateUser($token, $data);
	}
	
	closeDB();
?>