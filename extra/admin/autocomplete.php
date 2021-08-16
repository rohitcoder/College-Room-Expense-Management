<?php
error_reporting(0);
if(!isset($_SESSION))
session_start();
include '../config/config.php';
include '../common/functions.php';
if(isset($_GET['product'])){
$q=xssClean(mres(trim(strip_tags($_GET['product']))));
if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf' || $_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87')
$sql="SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND  pl.`title` LIKE '%$q%' LIMIT 5";
else
$sql="SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND  pl.`title` LIKE '%$q%' AND p.`userType`='3' AND p.`userId`='".$_SESSION['id']."' LIMIT 5";
$result = mysql_query($sql);
$data = array();
if($result)
{
	while($row=mysql_fetch_array($result)) 
	{
		array_push($data,$row['title']);
	}
	echo json_encode($data);
	unset($data);
}
}
if(isset($_GET['article'])){
$q=xssClean(mres(trim(strip_tags($_GET['article']))));
if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf' || $_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87')
$sql="SELECT `english` FROM `articles` WHERE `english` LIKE '%$q%' LIMIT 5";
else
$sql="SELECT `english` FROM `articles` WHERE `english` LIKE '%$q%' AND userId='".$_SESSION['id']."' LIMIT 5";
$result = mysql_query($sql);
$data = array();
if($result) 
{
	while($row=mysql_fetch_array($result)) 
	{
		array_push($data,$row['english']);
	}
	echo json_encode($data);
	unset($data);
}
}
?>