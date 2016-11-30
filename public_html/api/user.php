<?php
	require_once "../config.php";
	require_once "config.php";

	if($_GET['mode']== "get") {
		forbidden($_POST, 1);

		$user = getUser($token);
		//Server에서 JSONArray로 사용, return 결과 1row 따라서 배열처리
		echo "[".json_encode($user)."]";
	}

	closeDB();
?>