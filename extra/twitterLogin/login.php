<?php
session_start();

require_once('twitteroauth/twitteroauth.php');

include "../config/config.php";

include "../common/functions.php";

$CONSUMER_KEY=consumerKey();

$CONSUMER_SECRET=consumerSecret();
$OAUTH_CALLBACK=rootpath().'/twitterLogin/oauth.php';

$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET);

$request_token = $connection->getRequestToken($OAUTH_CALLBACK); //get Request Token

if($request_token)
{
	$token = $request_token['oauth_token'];
	
	$_SESSION['request_token'] = $token ;
	
	$_SESSION['request_token_secret'] = $request_token['oauth_token_secret'];
	
	switch ($connection->http_code) 
	{
		case 200:
			$url = $connection->getAuthorizeURL($token);
			header('Location: ' . $url); 
			break;
		default:
			echo "Coonection with twitter Failed";
			break;
	}
}
else
{
	echo "Error Receiving Request Token";
}
?>