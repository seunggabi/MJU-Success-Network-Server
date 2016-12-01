<?php
	require_once "config.php";
	
	$token = "dj1StZjCA-U:APA91bGjcYTkq150eACZU_p9GY0tTZTdbdMF9X6C3jM1zv9c3q_5uNCikZPNBrDTJbud2WoVHuXCZ6-mUbf0tlBausW8dTftKytXW5oAqPIrBuVx_TRr8KgXCDaVoQMGzArbMEXFMU37";
	$s_id = 1;
	$g_id = 1;
	echo debug(deleteSchedule($token, $g_id, $s_id));
?>