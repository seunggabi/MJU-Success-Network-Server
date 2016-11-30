<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>FCM Push Notification</title>
		<style type="text/css">
			*, *:before, *:after {box-sizing: border-box;}
			body {background-color: #FFFF00;}
			.messageWrapper {width:500px; margin:0 auto;}
			ul li {width:500px; margin:0 auto;}
			#submitButton {width:90px; padding:5px; border:2px solid #ccc; -webkit-border-radius: 5px; border-radius: 5px; font: 14px/1.4 "Helvetica Neue", Helvetica, Arial, sans-serif;}
		</style>
	</head>
	<body>
		<div class="messageWrapper">
			<h2>Push Message</h2>
			<form action="/fcm/process.php" method="post">
				<textarea name="message" rows="4" cols="50" placeholder="메세지를 입력하세요"></textarea><br>
				<input type="submit" name="submit" value="Send" id="submitButton">
			</form>
		</div>
	</body>
</html>