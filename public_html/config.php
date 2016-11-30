<?php
	$PATH = $_SERVER['DOCUMENT_ROOT'];
	$IP = $_SERVER['REMOTE_ADDR'];

	$DB_SERVER = "127.0.0.1";
	$DB_ID = "root";
	$DB_PASS = "autoset";
	$DB_NAME = "mju-success-network";
	$API_KEY = "AAAAOan-9AU:APA91bHL6xbSVuiGx8eLZej2dPAaDAYFZlA3cHgG0Of66zvmEjLAsI4yVbsRsYTP9KXlKYLbIVXQmBXWGA3Qn5p67HDmMdh8d-f4fKFE2_RfDrzVaSaG25e-NJrcRn4gp8k0VzxO3911CD176cK7mNSENDbOK29HZQ";

	$mysqli = new mysqli($DB_SERVER, $DB_ID, $DB_PASS, $DB_NAME);

	require_once $PATH."/helper/function.php";
	require_once $PATH."/helper/model.php";

	header('Content-Type: text/html; charset=UTF-8');
?>