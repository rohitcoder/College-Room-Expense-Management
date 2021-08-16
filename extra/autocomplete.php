<?php
error_reporting(0);
include 'config/config.php';
include 'common/functions.php';
if(!isset($_SESSION))
	session_start();
if(!isset($_SESSION['lanGuaGe']))
$language=$_SESSION['lanGuaGe']=getDefaultLanguage();
else
$language=$_SESSION['lanGuaGe'];
$q=xssClean(mres(trim(strip_tags($_GET['query']))));
$result = mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.status='1' AND pl.`language`='$language' AND p.`id`=pl.`id` AND pl.`title` LIKE '%$q%' LIMIT 5");
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
?>