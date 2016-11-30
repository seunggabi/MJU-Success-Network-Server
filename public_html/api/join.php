<?php
	require_once "../config.php";
	require_once "config.php";

	if($_GET['mode'] == "get") {
		forbidden($_POST, 2);
		
		if(!isset($search))
			$search = "";

		echo json_encode(getJoinList($token, $search));
	}
	else if($_GET['mode'] == 'check') {
		forbidden($_POST, 2);

		echo "[".json_encode(isJoin($token, $g_id))."]";
	}
	else if($_GET['mode'] == 'getUserList') {
		forbidden($_POST, 2);

		echo json_encode(getJoinUserList($token, $g_id));
	}
	closeDB();
?>