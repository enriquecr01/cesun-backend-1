<?php
require_once('../classes/user.php');
require_once('../classes/exceptions/recordnotfoundexception.php');

//allow access
header('Access-Control-Allow-Access: *');
//allow methods
header('Access-Control-Allow-Methods: GET, POST');

$response = array();


if ($_SERVER["REQUEST_METHOD"] == 'GET') {
	if (isset($_GET['id'])) {
		try {
			$user = new User($_GET['id']);
			$response = array('status' => 0, 'result' => $user->toArray());
		} catch (RecordNotFoundException $ex) {
			$response = array('status' => 1, 'result' => $ex->get_message());
		}
	} else {
		$response = User::getAllUsersArray();
		$response = array('status' => 0, 'result' => $response);
	}
}


if ($_SERVER["REQUEST_METHOD"] == 'POST') {
	//check parameters
	if (
		isset($_POST['name']) &&
		isset($_POST['username']) &&
		isset($_POST['password']) &&
		isset($_POST['role'])
	) {

		//create building object
		$user = new User();
		//assign values
		$user->setName($_POST['name']);
		$user->setUsername($_POST['username']);
		$user->setPassword($_POST['password']);
		$user->setRole($_POST['role']);
		//add
		if ($user->add())
			$response = array('status' => 0, 'result' => 'User added succesfully');
		else
			$response = array('status' => 2, 'result' => 'Could not add user');
	} else
		$response = array(
			'status' => 1,
			'result' => 'Missing Parameters'
		);
}

echo json_encode($response);