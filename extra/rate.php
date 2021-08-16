<?php
error_reporting(0);
include 'config/config.php';
include 'common/functions.php';
if($_POST['page']=='product')
{
	$productIdByPermalink= $_POST["id"];
	$rating = $_POST["rating"];
	$ip = $_SERVER["REMOTE_ADDR"];
	$q = mysql_query("SELECT * FROM `ratings` WHERE `id`='$productIdByPermalink' AND `ip`='$ip'");
	$n = mysql_num_rows($q);
	$r = mysql_fetch_assoc($q);
	if($n > 0)
	{
		if($rating!=$r["rating"])
		{
			mysql_query("UPDATE `ratings` SET `rating`='$rating' WHERE `id`='$productIdByPermalink' AND `ip`='$ip'") or die(mysql_error());
		}
	} 
	else 
	{
		mysql_query("INSERT INTO `ratings` (`id`,`rating`,`ip`) VALUES('$productIdByPermalink', '$rating','$ip')");
	}
include("current-rating.php");
}
if($_POST['page']=='article')
{
	$id= $_POST["id"];
	$rating = $_POST["rating"];
	$ip = $_SERVER["REMOTE_ADDR"];
	$q = mysql_query("SELECT * FROM `articleRatings` WHERE `id`='$id' AND `ip`='$ip'");
	$n = mysql_num_rows($q);
	$r = mysql_fetch_assoc($q);
	if($n > 0)
	{
		if($rating!=$r["rating"])
		{
			mysql_query("UPDATE `articleRatings` SET `rating`='$rating' WHERE `id`='$id' AND `ip`='$ip'");
		}
	} 
	else 
	{
		mysql_query("INSERT INTO `articleRatings` (`id`,`rating`,`ip`) VALUES('$id', '$rating','$ip')");
	}
include("article-rating.php");
}
?>