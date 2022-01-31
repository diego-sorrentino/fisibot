<?php

/* Command List for @BotFather
start - Info sul bot
privacy - Visualizza i dati che vengono memorizzati e dove
help - aiuto in linea
*/

require_once 'settings/settings.php';

function CmdToDo($Path, $IDChat, $Args = null){
	error_log(__FUNCTION__);
	$dataTODO= [
		'chat_id' => $IDChat,
		'text'	=> 'Comando non trovato. Premi / per i comandi attivi'

	];

	file_get_contents($Path . "/sendMessage?" . http_build_query($dataTODO));
}

function ReplyWithMessage($Path, $IDChat, $Message){
	header("Content-Type: application/json");

	$parameters = array(
		'chat_id' => $IDChat, 
		"text" => $Message,
		"method" => "sendMessage",
		'parse_mode' => 'HTML',
	);
	
	echo json_encode($parameters);

}


function CmdInfoPrivacy($Path, $IDChat, $Args){
	$message =<<<__START_MESSAGE__
Questo bot non mantiene alcun dato privato su chi lo utilizza (almeno per adesso)
__START_MESSAGE__;

	ReplyWithMessage($Path, $IDChat, $message);
}


$JSONRequest = json_decode(file_get_contents("php://input"), TRUE);

if(DEBUG)
	error_log(print_r($JSONRequest, true));

if(!$JSONRequest){ 
	error_log('json non valido');
	exit;
}
//else{

$Path = $Telegram['bot']['api'] . $Telegram['bot']['token'];


if(array_key_exists('message', $JSONRequest) && array_key_exists('contact', $JSONRequest['message'])){
	$IDChat = $JSONRequest['message']["contact"]["user_id"];
	$phoneNumber = $JSONRequest['message']["contact"]["phone_number"];

	CmdSaveTelegramPhoneNumber($Path, $IDChat, $phoneNumber);
	
}
elseif(array_key_exists('callback_query', $JSONRequest)){
//	$IDChat = $JSONRequest['callback_query']["message"]["chat"]["id"];
//	$method = $JSONRequest['callback_query']['data'];
//        if(preg_match('/showusrbyaggr-/', $method))
//                CmdShowUsrsByAggrPresence($Path, $IDChat, preg_replace('/showusrbyaggr-/', '', $method));
//
//        if(preg_match('/showusrdata-/', $method)){
//		$IDUsr = preg_replace('/showusrdata-/', '', $method);
//                CmdShowUsrsData($Path, $IDChat, $IDUsr);
//	}

}
else{
	$kindRequest = null;
	$availableRequest = array('message', 'edited_message');
	foreach($availableRequest as $request)
		if(array_key_exists($request, $JSONRequest))
			$kindRequest = $request;

	$IDChat = $JSONRequest[$kindRequest]["chat"]["id"];
	$Sender = $JSONRequest[$kindRequest]['from']['id'];

	if(preg_match('/ /', $JSONRequest[$kindRequest]["text"])){
		$Line = preg_split('/ /', $JSONRequest[$kindRequest]["text"]);
		$Command = $Line[0];
		$Args = array_slice($Line, 1);
	}
	else{
		$Args = null;
		$Command = $JSONRequest[$kindRequest]["text"];
	}
	$AvailableCommands = array(
		'start'			=> 'CmdStart',
		'privacy'		=> 'CmdInfoPrivacy',
		'help'			=> 'CmdHelp',

	);

	$cmdFound = false;
	foreach($AvailableCommands as $Cmd => $FuncName){
		if(preg_match('@/' . $Cmd  . '@', $Command)){
			$cmdFound = true;
			$FuncName($Path, $IDChat, $Args);
		}
	}
	
	if(!$cmdFound)
		CmdToDo($Path, $IDChat);

}
?>
