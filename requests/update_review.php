<?php 
session_start();


require "../config.php";
require "../db/index.php";
$db = new Database($HOST,$LOGIN,$PASS,$DBNAME);

//method $db->update_review implements stmt preparation

if($_SESSION['admin']['auth'] && isset($_POST['id']) && isset($_POST['action'])){

	$id = $_POST['id'];
	$action = $_POST['action'];

	if($action === 'publish'){

		$updated = $db->update_review($id, 'allowed', 1);

		if(!$updated)echo json_encode(['success' => false]);
		else echo json_encode(['success' => true]);
		exit;
	}

	if($action === 'delete'){
		$deleted = $db->delete_review($id);

		if(!$deleted)echo json_encode(['success' => false]); 
		else echo json_encode(['success' => true]);
		exit;
	}

	if($action === 'reply' and $_POST['message']){
		
		$msg = $_POST['message'];
		$replied = $db->add_answer($id, $msg);

		if(!$replied)echo json_encode(['success' => false]); 
		else{
			$db->update_review($id, 'answered', 1);
			echo json_encode(['success' => true]);
		} 

		exit;
	}

	if($action === 'correct' and $_POST['message']){
		
		$msg = $_POST['message'];
		$corrected = $db->update_review($id, 'message', $msg);

		if(!$corrected)echo json_encode(['success' => false]); 
		else echo json_encode(['success' => true]);
		exit;
	}



	
	//echo json_encode(['success' => true, 'id'=> $_POST['id'], 'action' => $_POST['action']]);


}





 ?>