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

echo '<pre>';


//subscriptions
//
//print_r( $instagram->userSubscription( $callback_url ) );
//
//print_r( $instagram->tagSubscription( 'nofilter', $callback_url ) );
//
//print_r( $instagram->locationSubscription( 1257285, $callback_url ) );
//
//print_r( $instagram->geographySubscription( '35.657872', '139.70232', '1000', $callback_url ) );
//
//print_r( $instagram->listSubscriptions() );
//
//print_r( $instagram->deleteSubscription( 'all' ) );
//
//
//users
//
//print_r( $instagram->getUser( 1574083 ) );
//
//print_r( $instagram->getSelf() );
//
//print_r( $instagram->getUserMedia( 3 ) );
//
//print_r( $instagram->getSelfLiked() );
//
// print_r( $instagram->getUserSearch( 'jack' ) );
//
//
//relationships
//
//print_r( $instagram->getUserFollows( 3 ) );
//
//print_r( $instagram->getUserFollowed( 3 ) );
//
//print_r( $instagram->getUserRequestsFollows() );
//
//print_r( $instagram->getUserRelationship( 1574083 ) );
//
//print_r( $instagram->setUserFollow( 1574083 ) );
//
//print_r( $instagram->setUserUnfollow( 1574083 ) );
//
//print_r( $instagram->setUserBlock( 1574083 ) );
//
//print_r( $instagram->setUserUnblock( 1574083 ) );
//
//print_r( $instagram->setUserApprove( 1574083 ) );
//
//print_r( $instagram->setUserDeny( 1574083 ) );
//
//
//media
//
//print_r( $instagram->getMedia( 3 ) );
//
//print_r( $instagram->getMediaSearch( '48.858844', '2.294351' ) );
//
//print_r( $instagram->getMediaPopular() );
//
//
//comments
//
//print_r( $instagram->getComments( 555 ) );
//
//print_r( $instagram->postComments( 555, 'This is my comment' ) );
//
//print_r( $instagram->deleteComments( 555, 50000 ) );
//
//
//likes
//
//print_r( $instagram->getLikes( 555 ) );
//
//print_r( $instagram->postLikes( 555 ) );
//
//print_r( $instagram->deleteLikes( 555 ) );
//
//
//tags
//
//print_r( $instagram->getTag( 'nofilter' ) );
//
//print_r( $instagram->getTagMedia( 'snow' ) );
//
//print_r( $instagram->getTagSearch( 'snowy' ) );
//
//
//locations
//
//print_r( $instagram->getLocation( 1 ) );
//
//print_r( $instagram->getLocationsMedia( 1 ) );
//
//print_r( $instagram->getLocationsSearch( '48.858844', '2.294351' ) );
//
//
//geographies
//
//print_r( $instagram->getGeography( 2844585 ) );
//
//
//embedding
//
//print_r( $instagram->oembed( 'http://instagr.am/p/BUG/' ) );
//
//print_r( $instagram->oembedRedirect( 'http://instagr.am/p/BUG/' ) );