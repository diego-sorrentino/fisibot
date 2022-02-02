<?php
$Telegram = array(
	'channel' => array(
		'name'	=> '@' . getenv('CHANNEL_NAME'),
	),
	'bot'	=> array (
		'name'	=> getenv('BOT_NAME'),
		'api'	=> 'https://api.telegram.org/bot',
		'token'	=> getenv('BOT_TOKEN')
	),
);


?>
