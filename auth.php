<?php

$config = array(
	'client_id' => 'XXX',
	'client_secret' => 'XXX',
	'redirect_uri' => 'XXX/auth.php'
);

require_once 'vendor/autoload.php';

$instagram = new \Zelenin\Instagram( $config['client_id'], $config['client_secret'], $config['redirect_uri'] );

$token = $instagram->getToken( 'basic comments relationships likes' );

echo $token['access_token'];