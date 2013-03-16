<?php

$config = array(
	'client_id' => 'XXX',
	'client_secret' => 'XXX',
	'redirect_uri' => 'XXX/auth.php'
);

$access_token = 'XXX';
$callback_url = 'XXX/callback.php';

require_once 'vendor/autoload.php';
require_once 'lib/Zelenin/Instagram.php';

$instagram = new \Zelenin\Instagram( $config['client_id'], $config['client_secret'], $config['redirect_uri'], $access_token );

file_put_contents( 'post.txt', json_encode( $instagram->getUpdates() ) );