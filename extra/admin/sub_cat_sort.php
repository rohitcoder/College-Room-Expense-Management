<?php
error_reporting(0);
include('../config/config.php');
if(isset($_POST['parent']))
{
	$parent = mysql_real_escape_string($_POST['parent']);//Some clean up :)
	$name = mysql_real_escape_string($_POST['name']);//Some clean up :)
	mysql_query("UPDATE `categories` SET `parentId`=" . $parent . " WHERE `english`='" . $name . "'") or die(mysql_error());
}
?>