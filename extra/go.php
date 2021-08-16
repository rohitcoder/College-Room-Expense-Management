<?php
error_reporting(0);
defined("APP") or die();
if(!isset($_SESSION)) session_start();
include 'config/config.php';
include 'common/functions.php';
if(isset($this->id)){
$id = mres(xssClean($this->id));
$sql="SELECT `url` FROM `products` WHERE `id`='$id' ";
$res=mysql_query($sql);
$row=mysql_fetch_assoc($res);
$url=$row['url'];
//product clicks
countClicks($id);
incrementPageViews();
incrementHotClicks($id);
incrementHotPageViews($id);
//product views
countViews($id);
mysql_query("UPDATE `stats` SET `clicks`=`clicks`+1 WHERE `datetime`=CURDATE()");
header("Location:$url");
}
if(isset($_POST['stats']))
{
incrementPageViews();
if(!isset($_SESSION['unique_hit'])){
$_SESSION['unique_hit'] = 1;
incrementUniqueHits();
}
}
if(isset($_POST['productId']))
{
$id=$_POST['productId'];
countViews($id);
incrementHotPageViews($id);
}
if(isset($_POST['removeFav']))
{
	$uid=trim($_POST['uid']);
	$pid=trim($_POST['pid']);
	if($uid!="")
	mysql_query("DELETE FROM `favourite` WHERE `uid`='$uid' AND `pid`='$pid'") or die(mysql_error());
}
if(isset($_POST['addFav']))
{
	$uid=trim($_POST['uid']);
	$pid=trim($_POST['pid']);
	$count=mysql_num_rows(mysql_query("SELECT * FROM `favourite` WHERE `uid`='$uid' AND `pid`='$pid'"));
	if(!$count)
	{
		if($uid!="")
		mysql_query("INSERT INTO `favourite`(`uid`,`pid`) VALUES('$uid','$pid')") or die(mysql_error());
	}
}
?>