<?php
error_reporting(0);
include '../config/config.php';
require "../include/cache/phpfastcache.php";
phpFastCache::setup("storage","auto");
include '../common/functions.php';
if(isset($_POST['publish']))
{
	$cache = phpFastCache();
	$id = xssClean(mres(trim($_POST["id"])));                                                                
	mysql_query("UPDATE `products` SET `status`='1' WHERE `id`='$id'");
	$query=mysql_query("SELECT `cid` FROM `procat` WHERE `pid`='$id'");
	while($fetch=mysql_fetch_array($query)){
		if(isParentCategoryById($fetch['cid']))
		{
		clearRecentCache($fetch['cid']);
		clearCategorycache($fetch['cid']);
		}
		else
		{
		$cid=parentIdByChildId($fetch['cid']);
		clearRecentCache($cid);
		clearCategorycache($cid);
		}
	}
	foreach( getAllLanguages() as $fetch){
	$language=$fetch['languageName'];
	$cache->delete($id.'_product'.$language);
	$cache->delete($id.'_relatedProduct'.$language);
	}
}
if(isset($_POST['unpublish']))
{
	$cache = phpFastCache();
	$id = xssClean(mres(trim($_POST["id"])));                                                                
	mysql_query("UPDATE `products` SET `status`='0' WHERE `id`='$id'");
	$query=mysql_query("SELECT `cid` FROM `procat` WHERE `pid`='$id'");
	while($fetch=mysql_fetch_array($query)){
		if(isParentCategoryById($fetch['cid']))
		{
		clearRecentCache($fetch['cid']);
		clearCategorycache($fetch['cid']);
		}
		else
		{
		$cid=parentIdByChildId($fetch['cid']);
		clearRecentCache($cid);
		clearCategorycache($cid);
		}
	}
	foreach(getAllLanguages() as $fetchLanguage){
	$language=$fetchLanguage['languageName'];
	$id.'_product'.$language;
	$id.'_relatedProduct'.$language;
	$cache->delete($id.'_product'.$language);
	$cache->delete($id.'_relatedProduct'.$language);
	}
}
if(isset($_POST['delete']))
{
	$cache = phpFastCache();
	$id = xssClean(mres(trim($_POST["id"]))); 
	$query=mysql_query("SELECT `cid` FROM `procat` WHERE `pid`='$id'");
	while($fetch=mysql_fetch_array($query)){
		if(isParentCategoryById($fetch['cid']))
		{
		clearRecentCache($fetch['cid']);
		clearCategorycache($fetch['cid']);
		}
		else
		{
		$cid=parentIdByChildId($fetch['cid']);
		clearRecentCache($cid);
		clearCategorycache($cid);
		}
	}
	foreach( getAllLanguages() as $fetch){
	$language=$fetch['languageName'];
	$cache->delete($id.'_product'.$language);
	$cache->delete($id.'_relatedProduct'.$language);
	}
	deleteProcat($id);
	deleteProduct($id);
}
//Article
if(isset($_POST['articlepublish']))
{
	$cache = phpFastCache();
	$id = xssClean(mres(trim($_POST["id"])));                                                                
	mysql_query("UPDATE `articles` SET `status`='1' WHERE `id`='$id'");
	clearArticlesCache();
	clearArticleCategoriesCache(articleCategoryId($id));
	foreach( getAllLanguages() as $fetch){
	$language=$fetch['languageName'];
	clearArticlesCache();
	clearShowArticlesCache($id);
	clearArticleCategoriesCache(getArticleCategoryId($id));
	}
}
if(isset($_POST['articleunpublish']))
{
	$id = xssClean(mres(trim($_POST["id"])));                                                                
	mysql_query("UPDATE `articles` SET `status`='0' WHERE `id`='$id'");
	clearArticlesCache();
	clearArticleCategoriesCache(articleCategoryId($id));
	foreach( getAllLanguages() as $fetch){
	$language=$fetch['languageName'];
	clearArticlesCache();
	clearShowArticlesCache($id);
	clearArticleCategoriesCache(getArticleCategoryId($id));
	}
}
if(isset($_POST['articledelete']))
{
	$id = xssClean(mres(trim($_POST["id"]))); 
	unlink('../images/articleImages/'.getArticleImage($id));
	$filename=str_replace('-img','',getArticleImage($id));
	unlink('../images/articleImages/'.$filename);
	unlink('../images/articleImages/articleImagesBackUp/'.$filename);
	mysql_query("DELETE  FROM `articles` WHERE `id`='$id'");
	mysql_query("DELETE  FROM `articlesLanguage` WHERE `id`='$id'");
	mysql_query("DELETE  FROM `articleRatings` WHERE `id`='$id'");
	clearArticlesCache();
	clearArticleCategoriesCache(articleCategoryId($id));
	foreach( getAllLanguages() as $fetch){
	$language=$fetch['languageName'];
	clearArticlesCache();
	clearShowArticlesCache($id);
	clearArticleCategoriesCache(getArticleCategoryId($id));
	}
}
?>