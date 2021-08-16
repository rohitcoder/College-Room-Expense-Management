<?php
error_reporting(0);
include 'config/config.php';
include 'common/functions.php';
function getCategoryName($id,$language)
{
	$qry = mysql_query("SELECT * FROM `categories` WHERE `id`='$id'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	{
	$row = mysql_fetch_array($qry);
	return ($row[$language]);
	}
}
$dataType=xssClean(mres(trim($this->Type)));
$data=xssClean(mres(trim($this->data)));
$language=xssClean(mres(trim($this->language)));
$json = file_get_contents('language/'.$language.'.php');
$lang_array=json_decode($json, true);
if(!rssEnable())
header("Location: " . rootpath());
header("Content-type: text/xml");
echo '<?xml version="1.0" encoding="UTF-8" ?> 
<feed xmlns:atom="http://www.w3.org/2005/Atom" xml:lang="en" xml:base="'.rootpath().'" xmlns="http://www.w3.org/2005/Atom">
<atom:link href="http://feeds.cadenhead.org/workbench" rel="self" type="application/rss+xml" />';
if(isset($dataType) && trim($dataType)=='category') 
{
	if(isset($data) && isValidCategory(trim($data)) && rssCategoryEnable())
	{
		$permalink = trim($data);
		$catid = catPermalinkToId($permalink);
		if(isParentCategory($permalink)) 
		{
			$query ="SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.id IN(SELECT pid FROM procat WHERE cid IN(SELECT id FROM categories WHERE parentId='".$catid."') OR cid='$catid')  AND pl.`language`='$language' AND p.`id`=pl.`id` AND p.`status`='1' LIMIT ".rssLimit();
		}
		else 
		{
			$query ="SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.id IN(SELECT pid FROM procat WHERE cid='$catid') AND pl.`language`='$language' AND p.`id`=pl.`id` AND p.`status`='1' LIMIT ".rssLimit();
		}
		echo "<title>" . ucwords(getCategoryName($catid,$language)) . " ".$lang_array['rss_feed']."</title>";
	}
}
else if(trim($dataType)=='top' && rssTopEnable())
{
	$query ="SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND  pl.`language`='$language' AND p.`status`='1' ORDER BY p.`clicks` DESC LIMIT " . rssLimit();
	echo "<title>".$lang_array['top_products_rss_feeds']."</title>";
}
else if(trim($dataType)=='tags' && rssTagsEnable())
{	
	$tagName=str_replace("-"," ",trim($data));
	$query ="SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND  pl.`language`='$language' AND p.`tags` LIKE '%$tagName%' AND p.`status`='1' ORDER BY p.`id` DESC LIMIT " . rssLimit();
	echo "<title>".$lang_array['tags_rss_feeds']."</title>";
}
else if(rssRecentEnable())
{
	$query ="SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND  pl.`language`='$language' AND p.`status`='1' ORDER BY p.`id` DESC LIMIT ".rssLimit();
	echo "<title>".$lang_array['recently_added_products_rss_feeds']."</title>";
}
$result = mysql_query($query) or die(mysql_error()); 
if(!$result)
{
	echo "error in your query";
} 
echo "<id>tag:" . getdomain(rootpath()) . "," . date("Y-m-d") . ":Item/" . rand(100,1000) . "</id>
<updated>" . date("l, F d, Y,H:i:s", strtotime(date('Y-m-d H:i:s'))) . "</updated>";  
$author = getAdminUsername();
while($array= mysql_fetch_array($result))
{
	$id   = $array['id'];         
	$title = htmlspecialchars($array['title']); 
	$link  = $array['permalink'];
	$updatedDate=$array['updatedDate'];
	$image = "<div><a href='" . rootpath() . '/'.productCategoryAndSubcategory($link).'/' . $link . ".html'><img src=".rootpath() ."/images/productImages/_".$array['image']."></a></div><br />";
	$summary = htmlspecialchars(substr(preg_replace('/\n+|\t+|\s+/', ' ', $image . strip_tags(stripslashes($array['summary']))) ,0,rssDescription()) . "&nbsp;&nbsp;");
	$pub = $array['date'];
	echo "\n
	<entry> 
		<title>$title</title>
		<link rel='alternate' type='text/html' href='" . rootpath() . '/'.productCategoryAndSubcategory($link).'/' . $link . ".html'/>
		<content type='html'>" .  $summary . "  </content>
		<id>tag:" . getdomain(rootpath()) . "," . date("Y-m-d") . ":Item/$id</id>";
		if($updatedDate!='0000-00-00')
		echo "<updated>".date("l, F d, Y,H:i:s", strtotime($updatedDate))."</updated>";
		else
		echo "<updated>".$updatedDate."</updated>";
		echo "<author>
			<name>$author</name>
		</author>
	</entry>\n";
}
echo "</feed>";
?>