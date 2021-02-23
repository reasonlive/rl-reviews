<?php

session_start([
    'cookie_lifetime' => 86400,
]);

ini_set('display_errors', 1); 
ini_set('error_reporting', E_ALL);

require "../config.php";
require "../db/index.php";
$db = new Database($HOST,$LOGIN,$PASS,$DBNAME);



if(isset($_POST['login']) && isset($_POST['password'])){


	$login = preg_match('/^[a-zA-Z0-9]{1,15}$/', $_POST['login']) ? $_POST['login'] : NULL;
	$password = preg_match('/^[a-zA-Z0-9\-\_]{8,30}$/', $_POST['password']) ? $_POST['password'] : NULL;

	$result = $db->get("SELECT * FROM admins WHERE login = '$login' AND password = '$password' ");
	
	if($result){
		$_SESSION['admin'] = array(
			'auth' => true,
			'login' => $login,
			'id' => $result['id']
		);

		echo json_encode(['success' => true]);

	}
	
	else echo json_encode(['success' => false]);
	exit;
}

?>