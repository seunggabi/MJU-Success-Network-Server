<?php
	require_once "../config.php";
	require_once "config.php";
	
	$search = isset($_GET['search']) ? $_GET['search'] : "";
	
	echo json_encode(getNoticeList($search));
	
	closeDB();
?>