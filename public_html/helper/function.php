<?php

	function sendMessage($tokens, $data) {
		global $API_KEY;

		if(count($tokens) == 1) 
			$tokens = array($tokens);
		foreach($tokens as $token) {
			$url = 'https://fcm.googleapis.com/fcm/send';
			$fields = array(
				'registration_ids' => $tokens,
				'data' => $data
			);
			$headers = array(
				'Authorization:key ='.$API_KEY,
				'Content-Type: application/json'
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			$result = curl_exec($ch);           
		}

		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);

		$json = json_decode($result);

		if($json->success)
			return "Success";
		else
			return "Fail";
	}

	function debug($data) {
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}

	function forbidden($args, $count) {
		if(count($args) != $count) {
			echo "잘못된 접근입니다.";
			exit;
		}
	}

	function toggleYN($bool) {
		$bool = $bool == "Y" ? "N" : "Y";
		return $bool;
	}

	function script($expr) {
		echo "<script>";
		echo $expr;
		echo "</script>";
	}

	function alert($str) {
		script("alert('$str');");
	}

	function console($str) {
		script("console.log('$str');");
	}

?>