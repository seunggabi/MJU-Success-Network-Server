<?php
	require_once "../config.php";
	require_once "config.php";
	
	forbidden($_POST, 4);

	insertLog($token, $g_id, $content);

	if($g_id) {
		$users = getUserList($g_id);
		$group = getGroup($g_id);
		foreach($users as $user) {
			$data = array(
				'message' => $content
				, 'type' => "chatting"
				, 'name' => "[".$group['g_name']."]".$u_name
				, 'g_id' => $g_id
				, 'g_name' => $group['g_name']
				, 'u_alarm' => $user['u_alarm']
				, 'j_alarm' => $user['j_alarm']
			);
			sendMessage($user['token'], $data);
		}
	}
?>