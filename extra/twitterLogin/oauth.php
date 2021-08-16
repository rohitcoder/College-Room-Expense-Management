<?php
session_start();
require_once('twitteroauth/twitteroauth.php');

include "../config/config.php";

include "../common/functions.php";

$CONSUMER_KEY=consumerKey();

$CONSUMER_SECRET=consumerSecret();

$OAUTH_CALLBACK=rootpath().'/twitterLogin/oauth.php';

if(isset($_GET['oauth_token']))
{
	$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $_SESSION['request_token'], $_SESSION['request_token_secret']);
	
	$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);
	
	if($access_token)
	{
		$connection = new TwitterOAuth($CONSUMER_KEY, $CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);
		
		$params =array();
		
		$params['include_entities']='false';
		
		$content = $connection->get('account/verify_credentials',$params);

		if($content && isset($content->screen_name) && isset($content->name))
		{	
			$_SESSION['IMAGEID']=$content->profile_image_url;
			
			$_SESSION['FULLNAME']=$content->screen_name;
			
			$_SESSION['LOGIN_TYPE'] = 'twitter';
			
			$username = $_SESSION['FULLNAME'];
			
			$rec = mysql_query("SELECT * FROM `socialLogin` WHERE `username`='$username' AND `loginType`='twitter'");
	
			if(mysql_num_rows($rec) > 0)
			{
				$row = mysql_fetch_array($rec);
				
				if($row['status'] == '1')
				{
					$_SESSION['store_uid'] = $row['id'];
			
					$_SESSION['store_username'] = $row['username'];
					
					$_SESSION['21d72e65f75d499adb5d2b9f17fcf352'] = 'security';
					
					header("Location: ".rootpath().'/favourite');
				}
			}
			else
			{
				mysql_query("INSERT INTO `socialLogin` (`username`,`loginType`,`status`) VALUES('$username','twitter','1')") or die(mysql_error());
				$row = mysql_fetch_array(mysql_query("SELECT * FROM `socialLogin` WHERE `username`='$username' AND `loginType`='twitter'"));
				$_SESSION['store_uid'] = $row['id'];
			
				$_SESSION['store_username'] = $row['username'];
				
				$_SESSION['21d72e65f75d499adb5d2b9f17fcf352'] = 'security';
				
				header("Location: ".rootpath().'/favourite');
			}
		}
		else
		{
			echo "<h4> Login Error </h4>";
		}
	}
	else
	{

		echo "<h4> Login Error </h4>";
	}
}
?>