<?php
include '../config/config.php';
include '../common/functions.php';
require_once ('common/libs/phpthumb/ThumbLib.inc.php');
require_once ('common/libs/resize.php');
require_once ('common/libs/watermark.class.php'); 
if(isset($_POST['regenerateType']) && trim($_POST['regenerateType'])=='articleThumbnails')
{
	$name = trim($_POST['name']);
	$id = trim($_POST['id']);
	regenerateArticleImage($id,$name);
	
}
if(isset($_POST['regenerateType']) && trim($_POST['regenerateType'])=='featuredImage')
{
	$name = trim($_POST['name']);
	$id = trim($_POST['id']);
	regenerateFeaturedImage($id,$name);
	
}
if(isset($_POST['regenerateType']) && trim($_POST['regenerateType'])=='productImages')
{
	$name = trim($_POST['name']);
	$id = trim($_POST['id']);
	regenerateProductImage($id,$name);
	
}
else if(isset($_POST['regenerateType']) && (trim($_POST['regenerateType'])=='smallThumbnails') || (trim($_POST['regenerateType'])=='mediumThumbnails') || (trim($_POST['regenerateType'])=='largeThumbnails'))
{
	if(trim($_POST['regenerateType'])=='smallThumbnails')
	{
		$name = trim($_POST['name']);
		$id = trim($_POST['id']);
		regenerateSmallThumbnail($id,$name);
	}
	
	if(trim($_POST['regenerateType'])=='mediumThumbnails')
	{
		$name = trim($_POST['name']);
		$id = trim($_POST['id']);
			regenerateMediumThumbnail($id,$name);
	}
	if(trim($_POST['regenerateType'])=='largeThumbnails')
	{
		$name = trim($_POST['name']);
		$id = trim($_POST['id']);
		regenerateLargeThumbnail($id,$name);
	}
}
?>