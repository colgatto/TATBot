<?php

	$update = json_decode(file_get_contents('php://input'),true);
	
	$chatId = $update['message']['chat']['id'];
	$fromId = $update['message']['from']['id'];
	$chatType = $update['message']['chat']['type'];
	$fromUsername = $update['message']['from']['username'];
	$text=$update['message']['text'];
	
	echo 'chatId: '.$chatId."\n";
	echo 'fromId: '.$fromId."\n";
	echo 'chatType: '.$chatType."\n";
	echo 'fromUsername: '.$fromUsername."\n";
	echo 'text: '.$text."\n";
	
?>