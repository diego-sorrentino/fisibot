<?php

/* Command List for @BotFather
start - Info sul bot
gruppiregionali - Gruppi e referenti regionali
iscrizionesindacato - Moduli di iscrizione
faq - Frequently Asked Questions
privacy - Visualizza i dati che vengono memorizzati e dove
help - aiuto in linea
*/

require_once 'settings/settings.php';

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

function CmdStart ($Path, $IDChat, $Args){
	error_log(__FUNCTION__);

	$message =<<<__START_MESSAGE__
Benvenuto nel BOT realizzato per FISI, al momento non fa quasi nulla
__START_MESSAGE__;

	ReplyWithMessage($Path, $IDChat, $message);
}

function CmdHelp($Path, $IDChat, $Args){
	error_log(__FUNCTION__);

	$message =<<<__START_MESSAGE__
Con questo BOT puoi cercare i gruppi e i referenti regionali, scaricare la modulistica di iscrizione, leggere le faq, ecc...
__START_MESSAGE__;

	ReplyWithMessage($Path, $IDChat, $message);
}


function CmdToDo($Path, $IDChat, $Args = null){
	error_log(__FUNCTION__);

	$message =<<<__TODO_MESSAGE__
Comando non trovato. Premi / per i comandi attivi
__TODO_MESSAGE__;

	ReplyWithMessage($Path, $IDChat, $message);
}

function CmdInfoPrivacy($Path, $IDChat, $Args){
	error_log(__FUNCTION__);

	$message =<<<__START_MESSAGE__
Questo bot non mantiene alcun dato privato su chi lo utilizza (almeno per adesso)
__START_MESSAGE__;

	ReplyWithMessage($Path, $IDChat, $message);
}

function CmdRegionalGroups($Path, $IDChat, $Args){
	error_log(__FUNCTION__);

	header("Content-Type: application/json");
	
	$data = yaml_parse_file('data/gruppiregionali.yaml');
	$dataArray = $data['referenti']['regione'];
	$nData = count($dataArray);
	
	$nCols = 2;
	$nRows = ceil($nData / $nCols);
	$counter = 0;
	for($i = 0; $i < $nRows; $i++){
		$Res[$i] = array();
		for($j = 0; $j < $nCols; $j++){
			$arrayID = $counter++;
			$dataName = $dataArray[$arrayID]['nome'];
			if(null != $dataName){
				$Res[$i][$j] = array('text' => $dataName, 'callback_data' => 'showregionalgroup-' . $arrayID);
			}
		}
	}

	$keyboard = ['inline_keyboard' => $Res ];

	$message =<<<__MESSAGE__
Scegli la regione
__MESSAGE__;

	$parameters = array(
		'chat_id' => $IDChat, 
		'reply_markup' => $keyboard,
		"text" => $message,
		"method" => "sendMessage",
		'parse_mode' => 'HTML',
	);

	echo json_encode($parameters);
}

function CmdShowRegionalGroupRefers($Path, $IDChat, $Args){
	error_log(__FUNCTION__);
	
	$data = yaml_parse_file('data/gruppiregionali.yaml');
	$regionArray = $data['referenti']['regione'][$Args];
	
	$message =<<<__MESSAGE__
Regione: {$regionArray['nome']}
Canale Telegram: https://t.me/{$regionArray['canale']}
Referenti:

__MESSAGE__;
	foreach($regionArray['referente'] as $id => $name)
		$message .= "- {$name}" . PHP_EOL;

	ReplyWithMessage($Path, $IDChat, $message);
}


function CmdFAQS($Path, $IDChat, $Args){
	error_log(__FUNCTION__);

	header("Content-Type: application/json");
	
	$data = yaml_parse_file('data/faq.yaml');
	$dataArray = $data['faqs']['comparto'];
	$nData = count($dataArray);
	
	$nCols = 2;
	$nRows = ceil($nData / $nCols);
	$counter = 0;
	for($i = 0; $i < $nRows; $i++){
		$Res[$i] = array();
		for($j = 0; $j < $nCols; $j++){
			$arrayID = $counter++;
			$dataName = $dataArray[$arrayID]['nome'];
			if(null != $dataName){
				$Res[$i][$j] = array('text' => $dataName, 'callback_data' => 'showfaq-' . $arrayID);
			}
		}
	}

	$keyboard = ['inline_keyboard' => $Res ];

	$message =<<<__MESSAGE__
Scegli il comparto
__MESSAGE__;

	$parameters = array(
		'chat_id' => $IDChat, 
		'reply_markup' => $keyboard,
		"text" => $message,
		"method" => "sendMessage",
		'parse_mode' => 'HTML',
	);

	echo json_encode($parameters);
}

function CmdShowFaq($Path, $IDChat, $Args){
	error_log(__FUNCTION__);

	$data = yaml_parse_file('data/faq.yaml');
	$dataArray = $data['faqs']['comparto'][$Args];
	
	foreach($dataArray['faq'] as $id => $data)
		$message[] =<<<__DATA__
Faq n. {$data['numero']}
Domanda: {$data['domanda']}
Risposta: {$data['risposta']}

__DATA__;

	ReplyWithMessage($Path, $IDChat, implode(PHP_EOL, $message));
}

function CmdShowSubscribeModule($Path, $IDChat, $Args){
	error_log(__FUNCTION__);
	
	$data = yaml_parse_file('data/moduli.yaml');
	$dataArray = $data['moduli']['comparto'][$Args];
//TODO: Return message with PDF in attach	
	$message =<<<__MESSAGE__
Filename: {$dataArray['filename']}
__MESSAGE__;

	ReplyWithMessage($Path, $IDChat, $message);
}

function CmdSubscribe($Path, $IDChat, $Args){
	error_log(__FUNCTION__);

	header("Content-Type: application/json");
	
	$data = yaml_parse_file('data/moduli.yaml');
	$dataArray = $data['moduli']['comparto'];
	$nData = count($dataArray);
	
	$nCols = 2;
	$nRows = ceil($nData / $nCols);
	$counter = 0;
	for($i = 0; $i < $nRows; $i++){
		$Res[$i] = array();
		for($j = 0; $j < $nCols; $j++){
			$arrayID = $counter++;
			$dataName = $dataArray[$arrayID]['nome'];
			if(null != $dataName){
				$Res[$i][$j] = array('text' => $dataName, 'callback_data' => 'showmodule-' . $arrayID);
			}
		}
	}

	$keyboard = ['inline_keyboard' => $Res ];

	$message =<<<__MESSAGE__
Scegli il modulo
__MESSAGE__;

	$parameters = array(
		'chat_id' => $IDChat, 
		'reply_markup' => $keyboard,
		"text" => $message,
		"method" => "sendMessage",
		'parse_mode' => 'HTML',
	);

	echo json_encode($parameters);
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
//	$IDChat = $JSONRequest['message']["contact"]["user_id"];
//	$phoneNumber = $JSONRequest['message']["contact"]["phone_number"];
//
//	CmdSaveTelegramPhoneNumber($Path, $IDChat, $phoneNumber);
	
}
elseif(array_key_exists('callback_query', $JSONRequest)){
	$AvailableCallbacks = array(
		'showregionalgroup'	=> 'CmdShowRegionalGroupRefers',
		'showfaq'		=> 'CmdShowFaq',
		'showmodule'		=> 'CmdShowSubscribeModule'
	);

	$IDChat = $JSONRequest['callback_query']["message"]["chat"]["id"];
	$method = $JSONRequest['callback_query']['data'];
	list($command, $value) = preg_split('/-/', $method);
	
	$callbackFound = false;
	foreach($AvailableCallbacks as $Cmd => $FuncName){
		if(preg_match('@' . $Cmd  . '@', $command)){
			$callbackFound = true;
			$FuncName($Path, $IDChat, $value);
		}
	}
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
		'gruppiregionali'	=> 'CmdRegionalGroups',
		'iscrizionesindacato'	=> 'CmdSubscribe',
		'faq'			=> 'CmdFAQS',
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
