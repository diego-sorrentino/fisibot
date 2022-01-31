<?php
$Telegram = array(
	'channel' => array(
		'name'	=> '@' . getenv('channel_name'),
	),
	'bot'	=> array (
		'name'	=> getenv('bot_name'),
		'api'	=> 'https://api.telegram.org/bot',
		'token'	=> getenv('bot_token')
	),
);


?>
