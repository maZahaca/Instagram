<?php

/**
 * A PHP implementation of the Instagram API
 * @package Instagram
 * @author  Aleksandr Zelenin <aleksandr@zelenin.me>
 * @link    https://github.com/zelenin/Instagram
 * @version 0.1.0
 * @license http://opensource.org/licenses/gpl-3.0.html GPL-3.0
 */

namespace Zelenin;

class Instagram
{
	private $client_id;
	private $client_secret;
	private $redirect_uri;
	private $access_token;
	private $curl;
	const OAUTH = 'https://api.instagram.com/oauth';
	const API = 'https://api.instagram.com/v1';
	const OEMBED = 'http://api.instagram.com/oembed';
	const VERSION = '0.1.0';

	public function __construct( $client_id, $client_secret, $redirect_uri, $access_token = null )
	{
		$this->client_id = $client_id;
		$this->client_secret = $client_secret;
		$this->redirect_uri = $redirect_uri;
		$this->access_token = $access_token;
		$this->curl = new Curl;
	}

	public function getToken()
	{
		if ( !isset( $_GET['code'] ) ) {
			$params = array(
				'client_id' => $this->client_id,
				'redirect_uri' => $this->redirect_uri,
				'response_type' => 'code',
				'scope' => 'basic comments relationships likes'
			);
			header( 'Location: ' . self::OAUTH . '/authorize/?' . http_build_query( $params ) );
		} else {
			$params = array(
				'client_id' => $this->client_id,
				'client_secret' => $this->client_secret,
				'grant_type' => 'authorization_code',
				'redirect_uri' => $this->redirect_uri,
				'code' => $_GET['code']
			);
			$response = $this->curl->post( self::OAUTH . '/access_token', $params );
			return json_decode( $response['body'], true );
		}
	}

	private function createSubscription( $callback_url, $params )
	{
		if ( !isset( $_GET['hub_challenge'] ) ) {
			$defaults = array(
				'client_id' => $this->client_id,
				'client_secret' => $this->client_secret,
				'aspect' => 'media',
				'callback_url' => $callback_url
			);
			$params = array_merge( $params, $defaults );
			$response = $this->curl->post( self::API . '/subscriptions/', $params );
			return $response['body'];
		} else {
			echo $_GET['hub_challenge'];
		}
	}

	public function userSubscription( $callback_url )
	{
		$params = array(
			'object' => 'user',
			'verify_token' => 'myVerifyToken'
		);
		return $this->createSubscription( $callback_url, $params );
	}

	public function tagSubscription( $tag, $callback_url )
	{
		$params = array(
			'object' => 'tag',
			'object_id' => $tag
		);
		return $this->createSubscription( $callback_url, $params );
	}

	public function locationSubscription( $id, $callback_url )
	{
		$params = array(
			'object' => 'location',
			'object_id' => $id
		);
		return $this->createSubscription( $callback_url, $params );
	}

	public function geographySubscription( $lat, $lng, $radius, $callback_url )
	{
		$params = array(
			'object' => 'geography',
			'lat' => $lat,
			'lng' => $lng,
			'radius' => $radius
		);
		return $this->createSubscription( $callback_url, $params );
	}

	public function getUpdates()
	{
		return json_decode( file_get_contents( 'php://input' ), true );
	}

	public function listSubscriptions()
	{
		$params = array(
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret
		);
		$response = $this->curl->get( self::API . '/subscriptions', $params );
		return json_decode( $response['body'], true );
	}

	public function deleteSubscription( $object = null, $id = null )
	{
		$params = array(
			'client_id' => $this->client_id,
			'client_secret' => $this->client_secret
		);
		if ( $object ) $params['object'] = $object;
		if ( $id ) $params['id'] = $id;
		$response = $this->curl->delete( self::API . '/subscriptions', $params );
		return $response['body'];
	}

	public function getUser( $user_id )
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->get( self::API . '/users/' . $user_id, $params );
		return json_decode( $response['body'], true );
	}

	public function getSelf( $count = null, $min_id = null, $max_id = null )
	{
		$params['access_token'] = $this->access_token;
		if ( $count ) $params['count'] = $count;
		if ( $min_id ) $params['min_id'] = $min_id;
		if ( $max_id ) $params['max_id'] = $max_id;
		$response = $this->curl->get( self::API . '/users/self/feed', $params );
		return json_decode( $response['body'], true );
	}

	public function getUserMedia( $user_id, $count = null, $max_timestamp = null, $min_timestamp = null, $min_id = null, $max_id = null )
	{
		$params['access_token'] = $this->access_token;
		if ( $count ) $params['count'] = $count;
		if ( $max_timestamp ) $params['max_timestamp'] = $max_timestamp;
		if ( $min_timestamp ) $params['min_timestamp'] = $min_timestamp;
		if ( $min_id ) $params['min_id'] = $min_id;
		if ( $max_id ) $params['max_id'] = $max_id;
		$response = $this->curl->get( self::API . '/users/' . $user_id . '/media/recent', $params );
		return json_decode( $response['body'], true );
	}

	public function getSelfLiked( $count = null, $max_like_id = null )
	{
		$params['access_token'] = $this->access_token;
		if ( $count ) $params['count'] = $count;
		if ( $max_like_id ) $params['max_like_id'] = $max_like_id;
		$response = $this->curl->get( self::API . '/users/self/media/liked', $params );
		return json_decode( $response['body'], true );
	}

	public function getUserSearch( $q, $count = null )
	{
		$params = array(
			'access_token' => $this->access_token,
			'q' => $q
		);
		if ( $count ) $params['count'] = $count;
		$response = $this->curl->get( self::API . '/users/search', $params );
		return json_decode( $response['body'], true );
	}

	public function getUserFollows( $user_id )
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->get( self::API . '/users/' . $user_id . '/follows', $params );
		return json_decode( $response['body'], true );
	}

	public function getUserFollowed( $user_id )
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->get( self::API . '/users/' . $user_id . '/followed-by', $params );
		return json_decode( $response['body'], true );
	}

	public function getUserRequestsFollows()
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->get( self::API . '/users/self/requested-by', $params );
		return json_decode( $response['body'], true );
	}

	public function getUserRelationship( $user_id )
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->get( self::API . '/users/' . $user_id . '/relationship', $params );
		return json_decode( $response['body'], true );
	}

	private function setUserRelationship( $user_id, $action )
	{
		$params = array(
			'access_token' => $this->access_token,
			'action' => $action
		);
		$response = $this->curl->post( self::API . '/users/' . $user_id . '/relationship', $params );
		return json_decode( $response['body'], true );
	}

	public function setUserFollow( $user_id )
	{
		return $this->setUserRelationship( $user_id, 'follow' );
	}

	public function setUserUnfollow( $user_id )
	{
		return $this->setUserRelationship( $user_id, 'unfollow' );
	}

	public function setUserBlock( $user_id )
	{
		return $this->setUserRelationship( $user_id, 'block' );
	}

	public function setUserUnblock( $user_id )
	{
		return $this->setUserRelationship( $user_id, 'unblock' );
	}

	public function setUserApprove( $user_id )
	{
		return $this->setUserRelationship( $user_id, 'approve' );
	}

	public function setUserDeny( $user_id )
	{
		return $this->setUserRelationship( $user_id, 'deny' );
	}

	public function getMedia( $media_id )
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->get( self::API . '/media/' . $media_id, $params );
		return json_decode( $response['body'], true );
	}

	public function getMediaSearch( $lat = null, $lng = null, $distance = null, $min_timestamp = null, $max_timestamp = null )
	{
		$params['access_token'] = $this->access_token;
		if ( $lat ) $params['lat'] = $lat;
		if ( $distance ) $params['distance'] = $distance;
		if ( $lng ) $params['lng'] = $lng;
		if ( $min_timestamp ) $params['min_timestamp'] = $min_timestamp;
		if ( $max_timestamp ) $params['max_timestamp'] = $max_timestamp;
		$response = $this->curl->get( self::API . '/media/search/', $params );
		return json_decode( $response['body'], true );
	}

	public function getMediaPopular()
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->get( self::API . '/media/popular', $params );
		return json_decode( $response['body'], true );
	}

	public function getComments( $media_id )
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->get( self::API . '/media/' . $media_id . '/comments', $params );
		return json_decode( $response['body'], true );
	}

	public function postComments( $media_id, $text )
	{
		$params = array(
			'access_token' => $this->access_token,
			'text' => $text
		);
		$response = $this->curl->post( self::API . '/media/' . $media_id . '/comments', $params );
		return json_decode( $response['body'], true );
	}

	public function deleteComments( $media_id, $comment_id )
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->delete( self::API . '/media/' . $media_id . '/comments/' . $comment_id, $params );
		return json_decode( $response['body'], true );
	}

	public function getLikes( $media_id )
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->get( self::API . '/media/' . $media_id . '/likes', $params );
		return json_decode( $response['body'], true );
	}

	public function postLikes( $media_id )
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->post( self::API . '/media/' . $media_id . '/likes', $params );
		return json_decode( $response['body'], true );
	}

	public function deleteLikes( $media_id )
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->delete( self::API . '/media/' . $media_id . '/likes', $params );
		return json_decode( $response['body'], true );
	}

	public function getTag( $tag_name )
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->get( self::API . '/tags/' . $tag_name, $params );
		return json_decode( $response['body'], true );
	}

	public function getTagMedia( $tag_name, $min_tag_id = null, $max_tag_id = null )
	{
		$params['access_token'] = $this->access_token;
		if ( $min_tag_id ) $params['min_tag_id'] = $min_tag_id;
		if ( $max_tag_id ) $params['max_tag_id'] = $max_tag_id;
		$response = $this->curl->get( self::API . '/tags/' . $tag_name . '/media/recent', $params );
		return json_decode( $response['body'], true );
	}

	public function getTagSearch( $q )
	{
		$params = array(
			'access_token' => $this->access_token,
			'q' => $q
		);
		$response = $this->curl->get( self::API . '/tags/search', $params );
		return json_decode( $response['body'], true );
	}

	public function getLocation( $location_id )
	{
		$params['access_token'] = $this->access_token;
		$response = $this->curl->get( self::API . '/locations/' . $location_id, $params );
		return json_decode( $response['body'], true );
	}

	public function getLocationsMedia( $location_id, $min_timestamp = null, $min_id = null, $max_id = null, $max_timestamp = null )
	{
		$params['access_token'] = $this->access_token;
		if ( $min_timestamp ) $params['min_timestamp'] = $min_timestamp;
		if ( $min_id ) $params['min_id'] = $min_id;
		if ( $max_id ) $params['max_id'] = $max_id;
		if ( $max_timestamp ) $params['max_timestamp'] = $max_timestamp;
		$response = $this->curl->get( self::API . '/locations/' . $location_id . '/media/recent', $params );
		return json_decode( $response['body'], true );
	}

	public function getLocationsSearch( $lat = null, $lng = null, $distance = null, $foursquare_v2_id = null, $foursquare_id = null )
	{
		$params['access_token'] = $this->access_token;
		if ( $lat ) $params['lat'] = $lat;
		if ( $distance ) $params['distance'] = $distance;
		if ( $lng ) $params['lng'] = $lng;
		if ( $foursquare_v2_id ) $params['foursquare_v2_id'] = $foursquare_v2_id;
		if ( $foursquare_id ) $params['foursquare_id'] = $foursquare_id;
		$response = $this->curl->get( self::API . '/locations/search', $params );
		return json_decode( $response['body'], true );
	}

	public function getGeography( $geo_id, $count = null, $min_id = null )
	{
		$params['client_id'] = $this->client_id;
		if ( $count ) $params['count'] = $count;
		if ( $min_id ) $params['min_id'] = $min_id;
		$response = $this->curl->get( self::API . '/geographies/' . $geo_id . '/media/recent', $params );
		return json_decode( $response['body'], true );
	}

	public function oembed( $url, $callback = null, $maxheight = null, $maxwidth = null )
	{
		$params['url'] = $url;
		if ( $callback ) $params['callback'] = $callback;
		if ( $maxheight ) $params['maxheight'] = $maxheight;
		if ( $maxwidth ) $params['maxwidth'] = $maxwidth;
		$response = $this->curl->get( self::OEMBED, $params );
		return json_decode( $response['body'], true );
	}

	public function oembedRedirect( $url, $size = 'm' )
	{
		$url = trim( $url, '/' );
		$params['size'] = $size;
		$response = $this->curl->get( $url . '/media/', $params );
		$response = $this->curl->get( $response['info']['redirect_url'] );
		return $response['info']['redirect_url'];
	}
}