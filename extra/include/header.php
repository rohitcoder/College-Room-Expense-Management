<?php
error_reporting(0);
if(!isset($_SESSION))
	session_start();
include 'config/config.php'; 
include "common/simpleMail.php";
require "include/cache/phpfastcache.php";
phpFastCache::setup("storage","auto");
include 'common/functions.php';
if(!alreadyUnpublishAfterExpiry(date("Y-m-d")))
unpublishAfterExpireDate(date("Y-m-d"));
if(!isset($_SESSION['lanGuaGe'])){
	$_SESSION['lanGuaGe']=getDefaultLanguage();
	$language=$_SESSION['lanGuaGe'];
}
if(isset($_COOKIE['lanGuaGe'])){
$_SESSION['lanGuaGe']=$_COOKIE['lanGuaGe'];
setcookie("lanGuaGe",$_SESSION['lanGuaGe'],time()-1,"");
}
$json = file_get_contents('language/'.$_SESSION['lanGuaGe'].'.php');
$lang_array=json_decode($json, true);
$analytics=analyticsData();
?> 

<html>
    <head>
		<meta charset="UTF-8">
		<!--
		===============================================================
		 _        _______          _________          _______  _       
		( (    /|(  ____ \|\     /|\__   __/|\     /|(  ___  )( (    /|
		|  \  ( || (    \/( \   / )   ) (   | )   ( || (   ) ||  \  ( |
		|   \ | || (__     \ (_) /    | |   | (___) || |   | ||   \ | |
		| (\ \) ||  __)     ) _ (     | |   |  ___  || |   | || (\ \) |
		| | \   || (       / ( ) \    | |   | (   ) || |   | || | \   |
		| )  \  || (____/\( /   \ )   | |   | )   ( || (___) || )  \  |
		|/    )_)(_______/|/     \|   )_(   |/     \|(_______)|/    )_)
                                                               				  
		===============================================================
		Script Name: Affiliate Store
		Author: Nexthon
		Author Website: Nexthon.com
		Profile: http://codecanyon.net/user/Nexthon
		Version: 1.0
		Description: Affiliate Store Script.
		===============================================================
		-->
		<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
		<link rel="shortcut icon" href="<?php echo(rootpath()); ?>/images/favicon/<?php echo(favicon() . "?" . time()); ?>"/>
		<!-- bootstrap 3.0.2 -->
		<link href="<?php echo rootpath()?>/style/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<!-- font Awesome -->
		<link href="<?php echo rootpath()?>/style/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<!-- Theme style -->
		<link href="<?php echo rootpath()?>/style/css/style.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo rootpath()?>/style/css/preload.css" rel="stylesheet" type="text/css" />
		<script src="<?php echo rootpath()?>/style/js/jquery.min.js"></script>
		<script src="<?php echo rootpath()?>/style/js/1.8.3.min.js" type="text/javascript"></script>
		<script src="<?php echo(rootpath()); ?>/style/js/preloader.js" type="text/javascript"></script>
		<?php
		if(enableRTL($_SESSION['lanGuaGe'])){
		?><link href="<?php echo rootpath()?>/style/css/rtl.css" rel="stylesheet" type="text/css" /><?php
		}
		if($analytics['status'])
		{
			echo $analytics['code'];
		}
		?>