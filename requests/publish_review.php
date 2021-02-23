
<?php
ini_set('display_errors', 1); 
ini_set('error_reporting', E_ALL);

$HOST = 'localhost';
$LOGIN = 'apc';
$PASS = 'reasonlive';
$DBNAME = 'app';


require "../db/index.php";
$db = new Database($HOST,$LOGIN,$PASS,$DBNAME);



if(isset($_POST['username']) && isset($_POST['phone']) && isset($_POST['email']) 
	&& isset($_POST['title']) && isset($_POST['message'])){
	
	$username = preg_match('/^[a-zа-яA-ZА-Я0-9\-\_]{2,50}$/', $_POST['username']) ? $_POST['username'] : NULL;

	$phone = preg_match('/^\+?[0-9]{7,17}$/', $_POST['phone']) ? $_POST['phone'] : NULL;

	$email = preg_match('/^([a-zA-Z0-9\_\-]{1,50})@([a-z]{2,15})\.([a-z]{2,7})$/', $_POST['email'])
			? $_POST['email'] : NULL;

	$title = preg_match('/^[a-zA-Zа-яА-Я0-9\s]{2,50}$/', $_POST['title']) ? $_POST['title'] : NULL;

	$message = preg_match('/^[a-zA-Zа-яА-Я0-9\s]{1,500}$/', $_POST['message']) ? strip_tags($_POST['message']) : NULL;

	//add_review method implements stmt preparation
	$result = $db->add_review($title, $message, $username, $phone, $email);
	
	//if(!$result) die('SQL ERROR IN PUBLISH_REVIEW.PHP');

	header("Location: /");
}

?>