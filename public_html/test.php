<?php
	require_once "config.php";
	
	$token = "dj1StZjCA-U:APA91bGjcYTkq150eACZU_p9GY0tTZTdbdMF9X6C3jM1zv9c3q_5uNCikZPNBrDTJbud2WoVHuXCZ6-mUbf0tlBausW8dTftKytXW5oAqPIrBuVx_TRr8KgXCDaVoQMGzArbMEXFMU37";
	$g_id = 1;
	$u_id = 3;
	echo debug(getScheduleList($token, $g_id));
?>