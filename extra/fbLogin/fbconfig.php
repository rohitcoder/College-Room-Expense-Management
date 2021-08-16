<?php
session_start();

// added in v4.0.0
include '../config/config.php';
include '../common/functions.php';
require_once 'autoload.php';
use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookRequestException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\Entities\AccessToken;
use Facebook\HttpClients\FacebookCurlHttpClient;
use Facebook\HttpClients\FacebookHttpable;
// init app with app id and secret
FacebookSession::setDefaultApplication(appId(),appSecret());
// login helper with redirect_uri
$helper = new FacebookRedirectLoginHelper('http://'.$_SERVER['HTTP_HOST'].dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/fbconfig.php');
try 
{
	$session = $helper->getSessionFromRedirect();
} 
catch( FacebookRequestException $ex ) 
{
  // When Facebook returns an error
} 
catch( Exception $ex )
{
  // When validation fails or other local issues
}
// see if we have a session
if ( isset( $session ) ) 
{
	// graph api request for user data
	$request = new FacebookRequest($session, 'GET', '/me');
	
	$response = $request->execute();
	// get response
	$graphObject = $response->getGraphObject();
	
	$fbid = $graphObject->getProperty('id');              // To Get Facebook ID
	
	$fbfullname = $graphObject->getProperty('name'); // To Get Facebook full name
	
	$femail = $graphObject->getProperty('email');	// To Get Facebook email ID
	
	/* ---- Session Variables -----*/
	
	$_SESSION['IMAGEID'] = $fbid;
	
	$_SESSION['FULLNAME'] = $fbfullname;
	
	$_SESSION['EMAIL'] =  $femail;
	
	$_SESSION['LOGIN_TYPE'] = 'facebook';
	if($femail)
	{
		$rec = mysql_query("SELECT * FROM `socialLogin` WHERE `email`='$femail' AND `loginType`='facebook'");
	}
	else
	{
		$rec = mysql_query("SELECT * FROM `socialLogin` WHERE `username`='$fbfullname' AND `loginType`='facebook'");
	}
	
	if(mysql_num_rows($rec) > 0)
	{
		$row = mysql_fetch_array($rec);
		
		if($row['status'] == '1')
		{
			$_SESSION['store_uid'] = $row['id'];
			
			$_SESSION['store_username'] = $row['username'];
			
			$_SESSION['store_email'] = $row['email'];
			
			$_SESSION['21d72e65f75d499adb5d2b9f17fcf352'] = 'security';
			
			header("Location: ".rootpath().'/favourite');
		}
	}
	else
	{
		if($femail || $fbfullname)
		{
		mysql_query("INSERT INTO `socialLogin` (`username`,`email`,`loginType`,`status`) VALUES('$fbfullname','$femail','facebook','1')") or die(mysql_error());
		if($femail)
			$row = mysql_fetch_array(mysql_query("SELECT * FROM `socialLogin` WHERE `email`='$femail' AND `loginType`='facebook'"));
		else
			$row = mysql_fetch_array(mysql_query("SELECT * FROM `socialLogin` WHERE `username`='$fbfullname' AND `loginType`='facebook'"));
		$_SESSION['store_uid'] = $row['id'];
			
		$_SESSION['store_username'] = $row['username'];
		
		$_SESSION['store_email'] = $row['email'];
		
		$_SESSION['21d72e65f75d499adb5d2b9f17fcf352'] = 'security';
		
		header("Location: ".rootpath().'/favourite');
		}
	}
} 
else 
{
	$loginUrl = $helper->getLoginUrl();	
	
	header("Location: ".$loginUrl);
}
?>