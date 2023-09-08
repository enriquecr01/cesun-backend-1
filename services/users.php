<?php
	//allow access
	header('Access-Control-Allow-Access: *');
	//allow methods
	header('Access-Control-Allow-Methods: GET');
	
	//use user class
	require_once('../classes/user.php');
	//check if headers were received

	$response = array();
	
	
	if ($_SERVER["REQUEST_METHOD"] == 'GET') {
		$response = array('status' => 0, 'message' => 'Missing Parameters');
	
		$response = User::getAllUsersArray();
		$response = array( 'result' => $response );

	
	}
	
	echo json_encode($response);

?>