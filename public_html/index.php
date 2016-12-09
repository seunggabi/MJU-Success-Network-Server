<?
	require_once "config.php";
	$managers = getManagerList();
	
	$status = null;
	foreach($managers as $manager) {
		if($manager['m_ip'] == $IP) {
			$status = $manager;
			break;
		}
	}
	
	if(!$status) {
		alert("관리자만 공지를 띄울 수 있습니다.");
		forbidden(0, 0);
	}

	if(isset($_POST['content'])) {
		$users = getUserList(null);
		$content = $_POST['content'];

		if ($content == "") {
			$content = "새글이 등록되었습니다.";
		}
		insertNotice($manager['m_id'], $content);
		foreach($users as $user) {
			$data = array(
				'message' => $content
				, 'type' => "notice"
				, 'name' => $manager['m_name']
				, 'j_alarm' => "Y"
				, 'u_alarm' => $user['u_alarm']
			);
			sendMessage($user['token'], $data);
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Notice Maker</title>
		<link rel="stylesheet" href="/common/style.css" />
	</head>
	<body>
		<h1><span class="blue">명</span></b>지대학교 <span class="blue">취</span>업을 준비하는 <span class="blue">사</span>람들</h1>
		<div id="noticeMakerHeader">
			&lt;Notice Maker&gt;
		</div>
		<div id="noticeMakerBody">
			<form method="post">
				<textarea name="content" rows="10" cols="80" placeholder="공지사항을 입력하세요"></textarea><br>
				<input type="submit" name="submit" value="등록하기" id="submitButton">
			</form>
		</div>
	</body>
</html>