<?php
	header('Content-Type: application/json');
	if(count($_POST) == 0) {
		$_POST = json_decode(file_get_contents("php://input"),true);
	}
	echo json_encode($_POST);
?>