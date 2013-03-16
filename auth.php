<?php

$config = array(
	'client_id' => 'XXX',
	'client_secret' => 'XXX',
	'redirect_uri' => 'XXX/auth.php'
);

require_once 'vendor/autoload.php';
require_once 'lib/Zelenin/Instagram.php';

$instagram = new \Zelenin\Instagram( $config['client_id'], $config['client_secret'], $config['redirect_uri'] );

$token = $instagram->getToken();

echo $token['access_token'];