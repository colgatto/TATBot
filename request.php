<?php
mb_internal_encoding('UTF-8');

function sendBodyRequest($url,$data){
	$data_string = json_encode($data);
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: '.strlen($data_string))
	);
	return curl_exec($ch);
}

function validPostRequest(){
	return isset($_POST['update_id']) &&
		isset($_POST['message_id']) &&
		isset($_POST['date']) &&
		isset($_POST['from_id']) &&
		isset($_POST['from_first_name']) &&
		isset($_POST['from_last_name']) &&
		isset($_POST['from_username']) &&
		isset($_POST['from_language_code']) &&
		isset($_POST['chat_id']) &&
		isset($_POST['chat_first_name']) &&
		isset($_POST['chat_last_name']) &&
		isset($_POST['chat_username']) &&
		isset($_POST['chat_type']) &&
		isset($_POST['text']) &&
		isset($_POST['hook']);
}

function generateData(){
	return [
		'update_id' => $_POST['update_id'],
		'message' => [
			'message_id' => $_POST['message_id'],
			'date' => $_POST['date'],
			'from' => [
				'id' => $_POST['from_id'],
				'is_bot' => isset($_POST['from_is_bot']),
				'first_name' => $_POST['from_first_name'],
				'last_name' => $_POST['from_last_name'],
				'username' => $_POST['from_username'],
				'language_code' => $_POST['from_language_code'],
			],
			'chat' => [
				'id' => $_POST['chat_id'],
				'first_name' => $_POST['chat_first_name'],
				'last_name' => $_POST['chat_last_name'],
				'username' => $_POST['chat_username'],
				'type' => $_POST['chat_type'],
			],
			'text' => $_POST['text'],
		]
	];
}

if(validPostRequest()){
	echo sendBodyRequest($_POST['hook'],generateData());
	die();
}

//header('Content-Tipe: application/json; charset=utf-8');
//echo json_encode($response, true)

?>