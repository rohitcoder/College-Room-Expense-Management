<?php
error_reporting(0);
if(!isset($_SESSION)){
session_start();
}
header('Content-Type: text/html; charset=utf-8');
mysql_query("SET NAMES 'utf8'");
libxml_use_internal_errors(true);

function gen_cover($filename,$height,$width)
{
	$options = array('jpegQuality' => 100);
	$thumb = PhpThumbFactory::create(dirname(__FILE__) . "/productimage/" . $filename,$options);
	$thumb->adaptiveResize($width, $height);
	$thumb->save(dirname(__FILE__) . "/images/productImages/images/" . $filename);
	return true;
}

function innerHTML(DOMNode $node)
{
  $doc = new DOMDocument();
  foreach ($node->childNodes as $child) {
    $doc->appendChild($doc->importNode($child, true));
  }
  return $doc->saveHTML();
}

function cat_has_childs($cid)
{
	$match_sub = "select id from categories where id=" . $cid;
	$qry_sub = mysql_query($match_sub);
	$num_rows_sub = mysql_num_rows($qry_sub); 
	if ($num_rows_sub > 0)
	return true;
	else
	return false;
}

function string_limit_words($string, $word_limit)
{
	$words = explode(' ', $string);
	return implode(' ', array_slice($words, 0, $word_limit));
}

function genPermalink($title)
{
	$permalink =  strtolower($title);
	if(!mb_check_encoding($permalink,"UTF-8"))
	{
		$permalink=preg_replace('/[^a-z0-9]/i',' ', $permalink);
		$permalink=trim(preg_replace("/[[:blank:]]+/"," ",$permalink));
		$permalink=strtolower(str_replace(" ","-",$permalink));
	}
	else
	{
		$permalink=trim($title);
		$permalink=str_replace(" ","-",$permalink);
	}
	$permalink = clean_permalink($permalink);
	$count = 1;
	$temppermalink = $permalink;
	while(isValidProduct($permalink))
	{
	$permalink = $temppermalink . '-' . $count;
	$count++;
	}
	return $permalink;
}

function genPagePermalink($title)
{
	$permalink =  strtolower($title);
	if(!mb_check_encoding($permalink,"UTF-8"))
	{
		$permalink=preg_replace('/[^a-z0-9]/i',' ', $permalink);
		$permalink=trim(preg_replace("/[[:blank:]]+/"," ",$permalink));
		$permalink=strtolower(str_replace(" ","-",$permalink));
	}
	else
	{
		$permalink=trim($title);
		$permalink=str_replace(" ","-",$permalink);
	}
	$permalink = clean_permalink($permalink);
	$count = 1;
	$temppermalink = $permalink;
	while(isValidPage($permalink))
	{
	$permalink = $temppermalink . '-' . $count;
	$count++;
	}
	return $permalink;
}

function genCategoryPermalink($title)
{
	$permalink =  strtolower($title);
	if(!mb_check_encoding($permalink,"UTF-8"))
	{
		$permalink=preg_replace('/[^a-z0-9]/i',' ', $permalink);
		$permalink=trim(preg_replace("/[[:blank:]]+/"," ",$permalink));
		$permalink=strtolower(str_replace(" ","-",$permalink));
	}
	else
	{
		$permalink=trim($title);
		$permalink=str_replace(" ","-",$permalink);
	}
	$permalink = clean_permalink($permalink);
	$count = 1;
	$temppermalink = $permalink;
	while(isValidCategory($permalink))
	{
	$permalink = $temppermalink . '-' . $count;
	$count++;
	}
	return $permalink;
}

function genArticlePermalink($title)
{
	$permalink =  strtolower($title);
	if(!mb_check_encoding($permalink,"UTF-8"))
	{
		$permalink=preg_replace('/[^a-z0-9]/i',' ', $permalink);
		$permalink=trim(preg_replace("/[[:blank:]]+/"," ",$permalink));
		$permalink=strtolower(str_replace(" ","-",$permalink));
	}
	else
	{
		$permalink=trim($title);
		$permalink=str_replace(" ","-",$permalink);
	}
	$permalink = clean_permalink($permalink);
	$count = 1;
	$temppermalink = $permalink;
	while(isValidArticle($permalink))
	{
	$permalink = $temppermalink . '-' . $count;
	$count++;
	}
	return $permalink;
}

function genArticleCategoriesPermalink($title)
{
	$permalink =  strtolower($title);
	if(!mb_check_encoding($permalink,"UTF-8"))
	{
		$permalink=preg_replace('/[^a-z0-9]/i',' ', $permalink);
		$permalink=trim(preg_replace("/[[:blank:]]+/"," ",$permalink));
		$permalink=strtolower(str_replace(" ","-",$permalink));
	}
	else
	{
		$permalink=trim($title);
		$permalink=str_replace(" ","-",$permalink);
	}
	$permalink = clean_permalink($permalink);
	$count = 1;
	$temppermalink = $permalink;
	while(isValidArticleCategory($permalink))
	{
	$permalink = $temppermalink . '-' . $count;
	$count++;
	}
	return $permalink;
}

function updateSettings($websiteName,$title,$description,$keywords,$rootpath,$logo,$favicon,$urlStructure,$httpsStatus)
{
	if($logo==""){
	$qry=mysql_query("SELECT `frontPageLogo` FROM `settings`");
	$fetch=mysql_fetch_array($qry);
	$logo=$fetch['frontPageLogo'];
	}
	if($favicon==""){
	$qry1=mysql_query("SELECT `favicon` FROM `settings`");
	$fetch1=mysql_fetch_array($qry1);
	$favicon=$fetch1['favicon'];
	}
	$update_query = "UPDATE `settings` SET `websiteName`='".mysql_real_escape_string($websiteName)."',`title`='". mysql_real_escape_string($title)."',`description`='".mysql_real_escape_string($description)."',`metaTags`='".mysql_real_escape_string($keywords) ."',`rootpath`='".mysql_real_escape_string($rootpath)."',`frontPageLogo`='" . $logo."',`favicon`='" . $favicon."',`urlStructure`='$urlStructure',`httpsStatus`='$httpsStatus'";
	mysql_query($update_query);
}

function updateRssSettings($enable,$limit,$description,$recent,$category,$top,$tags)
{
	mysql_query("UPDATE rssSettings SET enable='$enable',limitRss='".mysql_real_escape_string($limit)."',descLength='" . mysql_real_escape_string($description) ."',`recentRssEnable`='$recent',catRssEnable='$category',topRssEnable='$top',tagRssEnable='$tags'");
}

function rssEnable()
{
	$qry=mysql_query("SELECT `enable` FROM `rssSettings`");
	$array= mysql_fetch_array($qry);
	return $array['enable'];
}

function rssCategoryEnable()
{
	$qry=mysql_query("SELECT `catRssEnable` FROM `rssSettings`");
	$array= mysql_fetch_array($qry);
	return $array['catRssEnable'];
}

function rssTopEnable()
{
	$qry=mysql_query("SELECT `topRssEnable` FROM `rssSettings`");
	$array= mysql_fetch_array($qry);
	return $array['topRssEnable'];
}

function rssTagsEnable()
{
	$qry=mysql_query("SELECT `tagRssEnable` FROM `rssSettings`");
	$array= mysql_fetch_array($qry);
	return $array['tagRssEnable'];
}

function rssRecentEnable()
{
	$qry=mysql_query("SELECT `recentRssEnable` FROM `rssSettings`");
	$array= mysql_fetch_array($qry);
	return $array['recentRssEnable'];
}

function rssDescription()
{
	$qry=mysql_query("SELECT `descLength` FROM `rssSettings`");
	$array= mysql_fetch_array($qry);
	return $array['descLength'];
}

function rssLimit()
{
	$qry=mysql_query("SELECT `limitRss` FROM `rssSettings`");
	$array= mysql_fetch_array($qry);
	return $array['limitRss'];
}

function productPerPage()
{
	$show= "SELECT perPageProduct FROM mediaSettings";
	$qry=mysql_query($show);
	$array= mysql_fetch_array($qry);
	return $array['perPageProduct'];
}

function frontPageLogo()
{
	$qry=mysql_query("SELECT `frontPageLogo` FROM `settings`");
	$array= mysql_fetch_array($qry);
	return $array['frontPageLogo'];
}

function favicon()
{
	$qry=mysql_query("SELECT `favicon` FROM `settings`");
	$array= mysql_fetch_array($qry);
	return $array['favicon'];
}

function validFaviconExtension($ext) 
{
	$allowedExts = array(
		"ico",
		"png"
	);
	if (!in_array($ext, $allowedExts)) 
	{
		return false;
	}
	return true;
}

function validLogoExtension($ext) 
{
	$allowedExts = array(
		"gif",
		"jpeg",
		"jpg",
		"png"
	);
	if (!in_array($ext, $allowedExts)) 
	{
		return false;
	}
	return true;
}

function validExtension($ext) 
{
	$allowedExts = array(
		"gif",
		"jpeg",
		"jpg",
		"png"
	);
	if (!in_array($ext, $allowedExts)) 
	{
		return false;
	}
	return true;
}

function validFacebookUrl($field)
{
    if(!preg_match('/^[a-z\d.]{5,}$/i', $field))
	{
        return false;
    }
    return true;
}

function validTwitterUsername($field){
    if(!preg_match('/^[A-Za-z0-9_]+$/', $field)){
        return false;
    }
    return true;
}

function validGoogleUrl($field){
   if(!preg_match('/^[A-Za-z0-9+]+$/', $field)){
        return false;
    }
    return true;
}

function validLinkedinUrl($field){
   if(!preg_match('/(?:[^\.]+\.)?(linkedin)+\.[a-z]{2,3}$/i', $field))
   {
        return false;
    }

    return true;
}

function validPinterestUrl($field)
{
  if(!preg_match('/^[A-Za-z0-9_]+$/', $field))
    {
        return false;
    }
    return true;
}

function curPageURL()
{
	 $pageURL = 'http';
	 if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	 $pageURL .= "://";
	 if ($_SERVER["SERVER_PORT"] != "80") {
	  $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	 } else {
	  $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	 }
	 return $pageURL;
}

function getSocialProfilesData()
{
	$qry = mysql_query("SELECT * FROM `socialProfiles`");
	$row = mysql_fetch_array($qry);
	return $row;
}

function incrementClicks()
{
	$sql = mysql_query("SELECT clicks FROM `stats` WHERE `datetime`=CURDATE()");
	$rows = mysql_num_rows($sql);
	if($rows>0)
	{
	$sql_update = mysql_query("UPDATE `stats` SET `clicks`=`clicks`+1 WHERE `datetime`=CURDATE()");
	}
}

function incrementHotPageViews($product_id)
{
	$sql = mysql_query("SELECT views FROM `hotProducts` WHERE `datetime`=CURDATE() AND `productId`='".$product_id."'");
	$rows = mysql_num_rows($sql);
	if($rows>0)
	{
	$sql_update = mysql_query("UPDATE `hotProducts` SET `views`=`views`+1 WHERE `datetime`=CURDATE() AND `product_id`=$product_id");
	}
	else
	{
	$sql_insert= mysql_query("INSERT INTO `hotProducts`(`productId`,`views`, `clicks`, `datetime`) VALUES('$product_id','1','0',CURDATE())");
	}	
}

function getImage($id)
{
	$qry = mysql_query("SELECT `image` FROM `products` WHERE `id`='".$id."'");
	$row = mysql_fetch_array($qry);
	return ($row['image']);
}

function getArticleImage($id)
{
	$qry = mysql_query("SELECT `image` FROM `articles` WHERE `id`='".$id."'");
	$row = mysql_fetch_array($qry);
	return ($row['image']);
}

function parentIdByChildId($id)
{
	$row=mysql_fetch_array(mysql_query("SELECT `parentId` FROM `categories` WHERE `id`='$id'"));
	return $row['parentId'];
}

function getAdsData()
{
	$qryAd = mysql_query("SELECT * FROM `ads`");
	$rowAd = mysql_fetch_array($qryAd);
	return $rowAd;
}
function truncateshortDescription($description) 
{
	$shortDescription=productShortDesc();
	$description=trim(strip_tags(stripslashes($description)));
	if (strlen($description) > $shortDescription)
	{
		$description = substr($description, 0, $shortDescription).'...';
	}
	return $description;
}

function truncatelongDescription($description) 
{
	$shortDescription=productLongDesc();
	$description=trim(strip_tags(stripslashes($description)));
	if (strlen($description) > $shortDescription)
	{
		$description = substr($description, 0, $shortDescription).'...';
	}
	return $description;
}
function truncateArticleShortDescription($description) 
{
	$shortDescription=articleShortDesc();
	$description=trim(strip_tags(stripslashes($description)));
	if (strlen($description) > $shortDescription)
	{
		$description = substr($description, 0, $shortDescription).'...';
	}
	return $description;
}

function truncateArticleLongDescription($description) 
{
	$shortDescription=articleLongDesc();
	$description=trim($description);
	if (strlen($description) > $shortDescription)
	{
		$description = substr($description, 0, $shortDescription).'...';
	}
	return $description;
}
function productShortDesc()
{
	$show= "SELECT shortDescription FROM mediaSettings";
	$qry=mysql_query($show);
	$array= mysql_fetch_array($qry);
	return $array['shortDescription'];
}

function productLongDesc()
{
	$show= "SELECT longDescription FROM mediaSettings";
	$qry=mysql_query($show);
	$array= mysql_fetch_array($qry);
	return $array['longDescription'];
}
function articleShortDesc()
{
	$show= "SELECT `articleShortDescription` FROM `mediaSettings`";
	$qry=mysql_query($show);
	$array= mysql_fetch_array($qry);
	return $array['articleShortDescription'];
}

function articleLongDesc()
{
	$show= "SELECT `articleLongDescription` FROM `mediaSettings`";
	$qry=mysql_query($show);
	$array= mysql_fetch_array($qry);
	return $array['articleLongDescription'];
}

function sencrypt($text)
{
	return strtr(base64_encode($text), '+/=', '-_,');
} 

function sdecrypt($text)
{
	return base64_decode(strtr($text, '-_,', '+/='));
}

function resetPass($email) 
{ 
	$qry_user = mysql_query("SELECT `username` FROM `user` WHERE `email`='$email'");
	$row_user = mysql_fetch_array($qry_user);
	$username = $row_user['username'];
	$password = genPassword();
	sendEmail($email,$email,"Password Received","Your Login Details Updated<br/>Username: " .$username . "<br />Your new password is: " . $password . "<br/>Login Here: " . rootpath().'/admin');
	$qry = mysql_query("UPDATE `user` SET `password`='". md5($password) ."' WHERE `email`='".$email."'");
}
function getdomain($url)
{ 
	$parsed = parse_url($url); 
	return str_replace('www.','', strtolower($parsed['host'])); 
}

function getTitle()
{ 
	$qry = mysql_query("SELECT `title` FROM `settings`");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	{
	$row = mysql_fetch_array($qry);
	return ($row['title']);
	}
	else
	{
	return ("Affiliate Store");
	}
}
function getAdminEmail()
{
	$qry = mysql_query("SELECT `email` FROM `user` WHERE `type`='1'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	{
	$row = mysql_fetch_array($qry);
	return $row['email'];
	}
}

function getAdminUsername()
{
	$qry = mysql_query("SELECT `username` FROM `user` WHERE `type`='1'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	{
	$row = mysql_fetch_array($qry);
	return ($row['username']);
	}
}

function getUsername($id)
{
	$qry = mysql_query("SELECT `username` FROM `user` WHERE `id`='$id'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	{
	$row = mysql_fetch_array($qry);
	return ($row['username']);
	}
}

function getDescription()
{
	$match = "SELECT `description` FROM `settings`"; 
	$qry = mysql_query($match);
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	{
	$row = mysql_fetch_array($qry);
	return ($row['description']);
	}
}

function getTags()
{
	$qry = mysql_query("SELECT `metaTags` FROM `settings`");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	{
	$row = mysql_fetch_array($qry);
	return ($row['metaTags']);
	}
}

function validPrice($val)
{
	if(!preg_match('/^[0-9.]+$/', $val)){
	return false;
	}
	return true;
}

function validUrl($url)
{
	$validation = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_HOST_REQUIRED) && (preg_match("#^http(s)?://[a-z0-9-_.]+\.[a-z]{2,4}#i", $url));
	if($validation)
		return true;
	else
	return false;
}

function sendEmailThroughContact($to, $from, $name, $subject, $body) 
{

	$admin = getAdminUsername();
	
	$mail = new SimpleMail();
   
    $mail->setTo($to, 'Admin');
	
    $mail->setSubject($subject);
	
    $mail ->setFrom('no-reply@fullwebstats.com',$name);
	
	$mail->addMailHeader('Reply-To', $from, $name);
	
    $mail->addGenericHeader('X-Mailer', 'PHP/' . phpversion());
	
    $mail->addGenericHeader('Content-Type', 'text/html; charset="utf-8"');
	
    $mail->setMessage("<html><body><p face='Georgia, Times' color='red'><p>Hello! <b>" . ucwords($admin) . "</b>,</p> <p>" . $body . "</p><br /><br /><p>Sent Via <a href='" . rootpath() . "'>" . getTitle() . "</a></p>");
	
    $mail->setWrap(100);
	  
	$send = $mail->send();
	
}

function sendEmail($from,$to,$subject,$body) 
{
	$mail = new SimpleMail();
    $mail->setTo($to, "");
    $mail->setSubject($subject);
    $mail ->setFrom($from,getTitle());
	$mail->addMailHeader('Reply-To', $from,"");	
    $mail->addGenericHeader('X-Mailer', 'PHP/' . phpversion());
    $mail->addGenericHeader('Content-Type', 'text/html; charset="utf-8"');
    $mail->setMessage($body);
    $mail->setWrap(100);
	$send = $mail->send();
}

function genPassword() 
{
	$chars = "abcdefghijkmnopqrstuvwxyz023456789";
    srand((double)microtime()*1000000);
    $i = 0;
    $pass = '' ;
    while ($i <= 8) {
        $num = rand() % 33;
        $tmp = substr($chars, $num, 1);
        $pass = $pass . $tmp;
        $i++;
    }
    return $pass;
}

function isAlpha($val)
{
	return (bool)preg_match("/^([0-9a-zA-Z ])+$/i", $val);
}

function checkEmail($email)
{
	return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

function addIntoProcat($id,$val)
{
	mysql_query("INSERT INTO `procat`(`pid`, `cid`) VALUES ('$id','$val')");			
}

function isValidProduct($permalink)
{
	$count=mysql_num_rows(mysql_query("SELECT * FROM `products` WHERE `permalink`='$permalink'"));
	if($count > 0)
	return true;
	else
	return false;
}
function isUnblockProduct($permalink)
{
	$count=mysql_num_rows(mysql_query("SELECT * FROM `products` WHERE `permalink`='$permalink' AND status='1'"));
	if($count > 0)
	return true;
	else
	return false;
}
function isValidPage($permalink)
{
	$qry = mysql_query("SELECT `english` FROM `pages` WHERE `permalink`='" . $permalink. "'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	return true;
	else
	return false;
}

function isValidArticle($permalink)
{
	$qry = mysql_query("SELECT * FROM `articles` WHERE `permalink`='" . $permalink. "'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	return true;
	else
	return false;
}

function pageNameAlreadyExist($title,$id)
{
	$qry = mysql_query("SELECT * FROM `pages` WHERE `english`='$title' AND `id`!='$id'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	return true;
	else
	return false;
}

function linkNameAlreadyExist($title,$id)
{
	$qry = mysql_query("SELECT * FROM `links` WHERE `english`='$title' AND `id`!='$id'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	return true;
	else
	return false;
}

function isValidArticleCategory($permalink)
{ 
	$qry = mysql_query("SELECT * FROM `articleCategories` WHERE `permalink`='$permalink'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	return true;
	else
	return false;
}

function isValidCategory($permalink)
{ 
	$qry = mysql_query("SELECT * FROM `categories` WHERE `permalink`='$permalink'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	return true;
	else
	return false;
}

function getCategory($id)
{
	$qry = mysql_query("SELECT * FROM `categories` WHERE `id`='$id'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	{
	$row = mysql_fetch_array($qry);
	return ($row[$_SESSION['lanGuaGe']]);
	}
	else
	{
	return "";
	}
}

function deleteProduct($id)
{
	if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf' || $_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87') {
	$cache = phpFastCache();
	$query=mysql_query("SELECT `image`,`image1`,`image2`,`image3`,`image4`,`image5`,`tags` FROM `products` WHERE `id`='$id'");
	$cache->delete($id.'_product');
	$cache->delete($id.'_relatedProduct');
	} else{
	$cache = phpFastCache();
	$query=mysql_query("SELECT `image`,`image1`,`image2`,`image3`,`image4`,`image5`,`tags` FROM `products` WHERE `id`='$id' AND `userId`='".$_SESSION['id']."' AND `userType`='3'");
	$cache->delete($id.'_product');
	$cache->delete($id.'_relatedProduct');
	}
	$row=mysql_fetch_array($query);
	$image=$row['image'];
	$image1=$row['image1'];
	$image2=$row['image2'];
	$image3=$row['image3'];
	$image4=$row['image4'];
	$image5=$row['image5'];
	$tags=$row['tags'];
	$query2=mysql_query("SELECT `cid` FROM `procat` WHERE `pid`='$id'");
	while($fetch=mysql_fetch_array($query2)){
		clearCategorycache($fetch['cid']);
		clearRecentCache($fetch['cid']);
	}
	clearTagsCache($tags);
	unlink('../images/productImages/'.$image);
	unlink('../images/productImages/_'.$image);
	unlink('../images/productImages/'.str_replace('-img','',$image));
	unlink('../images/productImages/featuredImagesBackUp/_'.$image);
	unlink('../images/productImages/thumbnailsBackUp/'.$image1);
	unlink('../images/productImages/thumb1/'.$image1);
	unlink('../images/productImages/thumb2/'.$image1);
	unlink('../images/productImages/thumb3/'.$image1);
	unlink('../images/productImages/thumb1/'.$image2);
	unlink('../images/productImages/thumb2/'.$image2);
	unlink('../images/productImages/thumb3/'.$image2);
	unlink('../images/productImages/thumb1/'.$image3);
	unlink('../images/productImages/thumb2/'.$image3);
	unlink('../images/productImages/thumb3/'.$image3);
	unlink('../images/productImages/thumb1/'.$image4);
	unlink('../images/productImages/thumb2/'.$image4);
	unlink('../images/productImages/thumb3/'.$image4);
	unlink('../images/productImages/thumb1/'.$image5);
	unlink('../images/productImages/thumb2/'.$image5);
	unlink('../images/productImages/thumb3/'.$image5);
	if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf' || $_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87') {
	mysql_query("DELETE FROM `ratings` WHERE `id`='$id'");
	mysql_query("DELETE FROM `products` WHERE `id`='$id'");
	mysql_query("DELETE FROM `productsLanguage` WHERE `id`='$id'");
	mysql_query("DELETE FROM `favourite` WHERE `pid`='$id'");
	} else {
	if(productDelete()) {
	mysql_query("DELETE FROM `ratings` WHERE `id`='$id'");
	mysql_query("DELETE FROM `products` WHERE `id`='$id' AND `userId`='".$_SESSION['id']."' AND `userType`='3'");
	mysql_query("DELETE FROM `favourite` WHERE `pid`='$id'");
	mysql_query("DELETE FROM `productsLanguage` WHERE `id`='$id'");
	}
	}
}

function deleteProcat($delid)
{
	$userId=$_SESSION['id'];
	if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf' || $_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87') {
	mysql_query("DELETE FROM `procat` WHERE `pid`='$delid'");
	} else {
	if(productDelete()) {
	mysql_query("DELETE FROM `procat` WHERE `pid`=(SELECT `id` FROM `products` WHERE `userId`='$userId' AND `id`='$delid')");
	}	
	}
}

function rootCat()
{
$qry = mysql_query("SELECT * FROM `categories` WHERE `id`=1");
$row = mysql_fetch_array($qry);
return $row[$_SESSION['lanGuaGe']];
}

function deleteCategory($id)
{
	clearRecentCache($id);
	clearCategorycache($id);
	mysql_query("DELETE FROM `categories` WHERE `id`='$id'");
	mysql_query("UPDATE `products` SET `cid`=1 WHERE `cid`='$id'");
}

function emailExists($val)
{
	$qry = mysql_query("SELECT `username` FROM `user` WHERE `email` ='$val'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	return true;
	else
	return false;
}

function userNameExists($val)
{
	$qry = mysql_query("SELECT * FROM `user` WHERE `username` ='$val'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	return true;
	else
	return false;
}

function userIdByuserName($userName)
{
	$fetch = mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `username` ='$userName'"));
	return $fetch['id'];
}

function updateSitemapSettings($categories,$pages,$contactForm,$posts,$outputPath,$productsLimit)
{
	mysql_query("UPDATE `sitemaps` SET `categoriesStatus`='$categories',`pagesStatus`='$pages',`contactFormStatus`='$contactForm',`postsStatus`='$posts',`outputPath`='$outputPath',`productsLimit`='$productsLimit',`otherFiles`=''");

}

function genProductsSitemap($startLimit,$limit)
{
	$qry = mysql_query("SELECT * FROM `products` WHERE `status`=1 ORDER BY `id` DESC LIMIT ".$startLimit.','.$limit);
	while($array=mysql_fetch_array($qry))
	{
		$sitemaps .='<url>' . PHP_EOL;
		$sitemaps .="<loc>" . rootpath() . "/".productCategoryAndSubcategory($array['permalink'])."/" . $array['permalink'] . ".html</loc>" . PHP_EOL;
		$sitemaps .="<priority>0.8</priority>" . PHP_EOL;
		$sitemaps .='</url>' . PHP_EOL;
	}
	return $sitemaps;
}
function getOtherFiles()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `sitemaps`"));
	return $fetch['otherFiles'];
}

function productsLimitPerFile()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `sitemaps`"));
	return $fetch['productsLimit'];
}

function genCategoriesSitemap()
{
	$sitemap = ""; 
	$qry = mysql_query("SELECT * FROM `categories` ORDER BY `id` DESC");
	while($array=mysql_fetch_array($qry))
	{
		$sitemap .='<url>' . PHP_EOL;
		$sitemap .="<loc>" . rootpath() . "/category/" . (isParentCategory($array['permalink']) ?  $array['permalink'] : categoryPermalinkByChild($array['permalink']).'/'.$array['permalink']) . "</loc>" . PHP_EOL;
		$sitemap .="<priority>0.9</priority>" . PHP_EOL;
		$sitemap .='</url>' . PHP_EOL;
	}
	return $sitemap;
}

function genPagesSitemap()
{
	$sitemap = ""; 
	$qry = mysql_query("SELECT * FROM `pages` WHERE `status`=1 ORDER BY `id` DESC");
	while($array=mysql_fetch_array($qry))
	{
		$sitemap .='<url>' . PHP_EOL;
		$sitemap .="<loc>" . rootpath() . "/page/" . $array['permalink'] . "</loc>" . PHP_EOL;
		$sitemap .="<priority>0.6</priority>" . PHP_EOL;
		$sitemap .='</url>' . PHP_EOL;
	}
	return $sitemap;
}

function genRootSitemap()
{
	$sitemap = "";
	$sitemap .='<url>' . PHP_EOL;
	$sitemap .="<loc>" . rootpath() . "/</loc>" . PHP_EOL;
	$sitemap .="<priority>1.0</priority>" . PHP_EOL;
	$sitemap .='</url>' . PHP_EOL;
	return $sitemap;
}

function genContactSitemap()
{
	$sitemap = "";
	$sitemap .='<url>' . PHP_EOL;
	$sitemap .="<loc>" . rootpath() . "/contact</loc>" . PHP_EOL;
	$sitemap .="<priority>0.7</priority>" . PHP_EOL;
	$sitemap .='</url>' . PHP_EOL;
	return $sitemap;
}

function sitemapOutputPath()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT `outputPath` FROM `sitemaps`"));
	return $fetch['outputPath'];
}
function sitemapCategoriesStatus()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT `categoriesStatus` FROM `sitemaps`"));
	return $fetch['categoriesStatus'];
}
function sitemapPostsStatus()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT `postsStatus` FROM `sitemaps`"));
	return $fetch['postsStatus'];
}
function sitemapContactFormStatus()
{
$fetch=mysql_fetch_array(mysql_query("SELECT `contactFormStatus` FROM `sitemaps`"));
return $fetch['contactFormStatus'];
}
function sitemapPagesStatus()
{
$fetch=mysql_fetch_array(mysql_query("SELECT `pagesStatus` FROM `sitemaps`"));
return $fetch['pagesStatus'];
}

function productApproved()
{
	$qry = mysql_query("SELECT `productApproved` FROM `publisherRoles`");
	$array=mysql_fetch_array($qry);
	return $array["productApproved"];
}

function productEdit()
{ 
	$qry = mysql_query("SELECT productEdit FROM `publisherRoles`");
	$array=mysql_fetch_array($qry);
	return $array["productEdit"];
}

function productDelete() 
{ 
	$qry = mysql_query("SELECT `productDelete` FROM `publisherRoles`");
	$array=mysql_fetch_array($qry);
	return $array["productDelete"];
}

function articleApproved()
{
	$qry = mysql_query("SELECT `articleApproved` FROM `publisherRoles`");
	$array=mysql_fetch_array($qry);
	return $array["articleApproved"];
}

function articleEdit()
{ 
	$qry = mysql_query("SELECT articleEdit FROM `publisherRoles`");
	$array=mysql_fetch_array($qry);
	return $array["articleEdit"];
}

function articleDelete() 
{ 
	$qry = mysql_query("SELECT `articleDelete` FROM `publisherRoles`");
	$array=mysql_fetch_array($qry);
	return $array["articleDelete"];
}

function incrementHotClicks($productId) 
{
	$sql  = mysql_query("SELECT * FROM `hotProducts` WHERE `productId`='" . $productId ."'");
	$rows = mysql_num_rows($sql);
	if ($rows > 0) 
	{
		$fetchData = mysql_fetch_array($sql);
		$todayDate = $fetchData['date'];
		$weekDate = $fetchData['weekUpdateDate'];
		$monthDate = $fetchData['monthUpdateDate'];
		if(strtotime(firstDayOfWeek(date('Y-m-d')))>strtotime($weekDate)) 
		{
			$weekDate = firstDayOfWeek(date('Y-m-d'));
			mysql_query("UPDATE `hotProducts` SET `weeklyClicks`='0' WHERE `productId`='$productId'");
		}
		if(strtotime(date('Y-m-1'))>strtotime($monthDate)) 
		{
			$monthDate = date('Y-m-1');
			mysql_query("UPDATE `hotProducts` SET `monthlyClicks`='0' WHERE `productId`='$productId'");
		}
		$weekValue = $fetchData['weeklyClicks']+1;
		$monthValue = $fetchData['monthlyClicks']+1;
		$alltimeValue = $fetchData['alltimeClicks']+1;
		$sqlUpdate = mysql_query("UPDATE `hotProducts` SET `alltimeClicks`='$alltimeValue',`monthlyClicks`='$monthValue',`weeklyClicks`='$weekValue',`weekUpdateDate`='$weekDate',`monthUpdateDate`='$monthDate' WHERE `productId`='" . $productId . "'");
		todayClicks($todayDate,$productId);
	} 
	else 
	{
		$sql_insert = mysql_query("INSERT INTO `hotProducts`(`productId`,`todayClicks`,`weeklyClicks`, `monthlyClicks`,`alltimeClicks`,`date`,`weekUpdateDate`,`monthUpdateDate`) VALUES('$productId','1','1','1','1',CURDATE(),'" . firstDayOfWeek(date('Y-m-d')) . "','" . date('Y-m-1') . "')");
	}
}

function todayClicks($get_date,$productId) 
{
	$todayDate=date("Y-m-d");
	if($get_date==$todayDate) 
	{
		mysql_query("UPDATE `hotProducts` SET `todayClicks`=`todayClicks` +1 WHERE `productId`='$productId'");
	} 
	else 
	{
		mysql_query("UPDATE `hotProducts` SET `todayClicks`='1',`date`=CURDATE() WHERE `productId`='$productId'");
	}
}

function firstDayOfWeek($date)
{
    $day = DateTime::createFromFormat('Y-m-d', $date);
    $day->setISODate((int)$day->format('o'), (int)$day->format('W'), 1);
    return $day->format('Y-m-d');
}

function getWeekUpdateDate()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `hotProducts` WHERE `weekUpdateDate` BETWEEN DATE_SUB( CURDATE( ) ,INTERVAL 6 DAY ) AND CURDATE( ) ORDER BY `weekUpdateDate` DESC LIMIT 1"));
	return $fetch['weekUpdateDate'];
}
function getMonthUpdateDate()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `hotProducts` WHERE `monthUpdateDate` >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) AND CURDATE( ) ORDER BY `monthUpdateDate` DESC LIMIT 1"));
	return $fetch['monthUpdateDate'];
}
function formSubmitted()
{
	if($_SERVER['REQUEST_METHOD'] == 'POST')
	return true;
}

function validUsername($username) 
{
	return (bool) preg_match("/^([0-9a-zA-Z ])+$/i", $username);
}

function onOffAdminCaptcha()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT `adminCaptcha` FROM `captchaSettings`"));
	return $fetch['adminCaptcha'];
}

function productCategoryById($id)
{
	$arr = array();
	$query=mysql_query("SELECT `cid` FROM `procat` WHERE `pid`='$id'");
	while($fetch=mysql_fetch_array($query)) {
		array_push($arr, $fetch['cid']);
	}
	return $arr;
}

function isParentCategory($permalink)
{
	$count=mysql_num_rows(mysql_query("SELECT * FROM `categories` WHERE `permalink`='$permalink' AND `parentId`=0"));
	if($count > 0)
	return true;
	else
	return false;
}

function relatedProducts()
{
	$row=mysql_fetch_array(mysql_query("SELECT `relatedProducts` FROM `mediaSettings`"));
	if($row['relatedProducts'])
	return true;
	else
	return false;	
}

function relatedProductsLimit()
{
	$row=mysql_fetch_array(mysql_query("SELECT `relatedProductsLimit` FROM `mediaSettings`"));
	return $row['relatedProductsLimit'];	
}

function httpsStatus() 
{
	$array = mysql_fetch_array(mysql_query("SELECT `httpsStatus` FROM `settings`"));
	return $array['httpsStatus'];
}

function urlStructure() 
{
	$array = mysql_fetch_array(mysql_query("SELECT `urlStructure` FROM `settings`"));
	return $array['urlStructure'];
}
if(!urlStructure() && substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.')
{

	$https = (httpsStatus() && isset($_SERVER['HTTPS'])) ? 'https://':'http://';

	if (substr($_SERVER['HTTP_HOST'], 0, 4) === 'www.') {

		header("HTTP/1.1 301 Moved Permanently");

		header('Location: ' . $https . substr($_SERVER['HTTP_HOST'], 4).$_SERVER['REQUEST_URI']);

		exit();

	}

} 
else if(urlStructure() && (strpos($_SERVER['HTTP_HOST'], 'www.') === false))
{

	$https = (httpsStatus() && isset($_SERVER['HTTPS'])) ? 'https://':'http://';

	if ((strpos($_SERVER['HTTP_HOST'], 'www.') === false)) {
	
		header("HTTP/1.1 301 Moved Permanently");
		
		header('Location: ' . $https . 'www.'.$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
		
		exit();
	
	}

}
if($_SERVER["HTTPS"] != "on" && httpsStatus())
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}
else if($_SERVER["HTTPS"] == "on" && !httpsStatus())
{
	header("Location: http://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}

function httpStatusCode($url)
{
	$handle = curl_init($url);

	$USER_AGENT = $_SERVER['HTTP_USER_AGENT'];

	curl_setopt($handle,  CURLOPT_RETURNTRANSFER, true);

	curl_setopt($handle, CURLOPT_USERAGENT, $USER_AGENT);

	curl_setopt($handle, CURLOPT_TIMEOUT, 5);

	curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);

	curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

	curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);

	curl_setopt($handle,CURLOPT_HEADER,true);
		
	curl_setopt($handle,CURLOPT_NOBODY,true);

	curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

	$response = curl_exec($handle);

	$httpCode = curl_getinfo($handle, CURLINFO_HTTP_CODE);

	curl_close($handle);

	return $httpCode;

}
function rootpath()
{
	$query = mysql_query("SELECT `rootpath` FROM `settings`");
	$fetch = mysql_fetch_array($query);
	if ($fetch['rootpath'] != "")
	{
		$www = (urlStructure()) ? 'www.':'';
		$https = (httpsStatus()) ? 'https://':'http://';
		$rootPath = $https . $www . cleanUrl($fetch['rootpath']);
		
	}
	return $rootPath;
}

function cleanUrl($url) 
{
	$url = preg_replace('#^https?://#', '', $url);
	$url = preg_replace('/^www\./', '', $url);
	return $url;
}

function catPermalinkToId($permalink)
{
	$sql = "SELECT `id` FROM `categories` WHERE `permalink`='" . $permalink . "'";
	$qry = mysql_query($sql);
	$fetch = mysql_fetch_array($qry);
	return $fetch['id'];
}

function catPermalinkToName($permalink)
{
	$sql = "SELECT * FROM `categories` WHERE `permalink`='" . $permalink . "'";
	$qry = mysql_query($sql);
	$fetch = mysql_fetch_array($qry);
	return $fetch[$_SESSION['lanGuaGe']];
}

function catIdByProductId($id)
{
	$row = mysql_fetch_array(mysql_query("SELECT `cid` FROM `procat` WHERE `pid`='$id'"));
	return $row['cid'];
}

function catNameByPermalink($permalink)
{
	$pid=productIdByPermalink($permalink);
	$qry = mysql_query("SELECT * FROM `categories` WHERE `id` IN(SELECT `cid` FROM `procat` WHERE pid='".$pid."')");
	while($fetch = mysql_fetch_array($qry)) {
	if($fetch['parentId']==0)
	{
	$names .='<a href="'.rootpath().'/category/'.$fetch['permalink'].'">'.ucwords($fetch[$_SESSION['lanGuaGe']]).'</a>,';
	}
	else
	{
	$names .='<a href="'.rootpath().'/category/'.categoryPermalinkById($fetch['parentId']).'/'.$fetch['permalink'].'">'.ucwords($fetch[$_SESSION['lanGuaGe']]).'</a>,';	
	}
	}
	return rtrim($names,',');
}

function productIdByPermalink($permalink)
{
	$qry = mysql_query("SELECT `id` FROM `products` WHERE `permalink`='" . $permalink . "'");
	$fetch = mysql_fetch_array($qry);
	return $fetch['id'];
}

function catIdToPermalink($id)
{
	$qry = mysql_query("SELECT `permalink` FROM `categories` WHERE `id`='$id'");
	$fetch = mysql_fetch_array($qry);
	return $fetch['permalink'];
}

function productCategory($permalink)
{
	$qry = mysql_query("SELECT `cid` FROM `products` WHERE `permalink`='" . $permalink . "'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	{
	$row = mysql_fetch_array($qry);
	$match_cat = "SELECT * FROM `categories` WHERE `id`=" . $row['cid']; 
	$qry_cat = mysql_query($match_cat);
	$row_cat = mysql_fetch_array($qry_cat);
	return ($row_cat[$_SESSION['lanGuaGe']]);
	}
	else
	{
	return "";
	}
}
function productCategoryAndSubcategory($permalink)
{
	$productRow = mysql_fetch_array(mysql_query("SELECT `id` FROM `products` WHERE `permalink`='" . $permalink . "'"));
	$proCatRow = mysql_fetch_array(mysql_query("SELECT `cid` FROM `procat` WHERE `pid`='" . $productRow['id'] . "'"));
	$categoriesRow = mysql_fetch_array(mysql_query("SELECT `permalink`,`parentId` FROM `categories` WHERE `id`='" . $proCatRow['cid'] . "'"));
	if($categoriesRow['parentId']==0)
	{
	$parentCategory=$categoriesRow['permalink'];
	return $parentCategory;
	}
	else
	{
	$parentCategoriesRow = mysql_fetch_array(mysql_query("SELECT `permalink` FROM `categories` WHERE `id`='" . $categoriesRow['parentId'] . "' AND parentId='0'"));
	$parentCategory=$parentCategoriesRow['permalink'];
	$childCategory=$categoriesRow['permalink'];
	return $parentCategory.'/'.$childCategory;
	}
}

function CategoryAndSubcategoryUrl($permalink)
{
	$categoriesRow = mysql_fetch_array(mysql_query("SELECT * FROM `categories` WHERE `permalink`='$permalink'"));
	if($categoriesRow['parentId']==0)
	{
		$parentCategoryName=$categoriesRow[$_SESSION['lanGuaGe']];
		$parentCategoryPermalink=$permalink;
		return '<li><a href="'.rootpath().'/category/'.$parentCategoryPermalink.'">'.$parentCategoryName.'</a></li>';
	}
	else
	{
		$parentCategoriesRow = mysql_fetch_array(mysql_query("SELECT * FROM `categories` WHERE `id`='" . $categoriesRow['parentId'] . "' AND parentId='0'"));
		$parentCategoryName=$parentCategoriesRow[$_SESSION['lanGuaGe']];
		$childCategoryName=$categoriesRow[$_SESSION['lanGuaGe']];
		$parentCategoryPermalink=$parentCategoriesRow['permalink'];
		$childCategoryPermalink=$permalink;
		return '<li><a href="'.rootpath().'/category/'.$parentCategoryPermalink.'">'.$parentCategoryName.'</a></li><li><a href="'.rootpath().'/category/'.$parentCategoryPermalink.'/'.$childCategoryPermalink.'">'.$childCategoryName.'</a></li>';
	}
}


function clearTagsCache($tags)
{
	$cache = phpFastCache();
	$alltags= explode("," , $tags);
	foreach($alltags as $tag)
	{
		$tagName = trim($tag);
		$cache->delete($tagName."tags_clicksDESC1");
		$cache->delete($tagName."tags_clicksASC1");
		$cache->delete($tagName."tags_idDESC1");
		$cache->delete($tagName."tags_idASC1");
		$cache->delete($tagName."tags_originalPriceDESC1");
		$cache->delete($tagName."tags_originalPriceASC1");
	}
}
function clearArticlesCache()
{
	foreach( getAllLanguages() as $fetch){
	$language=$fetch['languageName'];
	$cache = phpFastCache();
	$cache->delete("article_titledesc1".$language);
	$cache->delete("article_titleasc1".$language);
	$cache->delete("article_viewsdesc1".$language);
	$cache->delete("article_viewsasc1".$language);
	$cache->delete("article_datedesc1".$language);
	$cache->delete("article_dateasc1".$language);
	$cache->delete("article_ratingdesc1".$language);
	$cache->delete("article_ratingasc1".$language);
	}
}
function clearShowArticlesCache($id)
{
	foreach( getAllLanguages() as $fetch){
	$language=$fetch['languageName'];
	$cache = phpFastCache();
	$cache->delete($id.'_article'.$language);
	}
}
function clearArticleCategoriesCache($cid)
{
	foreach( getAllLanguages() as $fetch){
	$language=$fetch['languageName'];
	$cache = phpFastCache();
	$cache->delete($cid."_article_titledesc1".$language);
	$cache->delete($cid."_article_titleasc1".$language);
	$cache->delete($cid."_article_viewsdesc1".$language);
	$cache->delete($cid."_article_viewsasc1".$language);
	$cache->delete($cid."_article_datedesc1".$language);
	$cache->delete($cid."_article_dateasc1".$language);
	$cache->delete($cid."_article_ratingdesc1".$language);
	$cache->delete($cid."_article_ratingasc1".$language);
	}
}

function clearCategorycache($id)
{
	foreach( getAllLanguages() as $fetch)
	{
		$language=$fetch['languageName'];
		$query=mysql_query("SELECT `id` FROM `categories` WHERE (`parentId`='$id' OR `id`='$id')");
		while($row=mysql_fetch_array($query))
		{
			$cache = phpFastCache();
			$cache->delete($row['id']."category_clicksDESC1".$language);
			$cache->delete($row['id']."category_clicksASC1".$language);
			$cache->delete($row['id']."category_idDESC1".$language);
			$cache->delete($row['id']."category_idASC1".$language);
			$cache->delete($row['id']."category_originalPriceDESC1".$language);
			$cache->delete($row['id']."category_originalPriceASC1".$language);
		}
	}
}
function clearRecentCache($id)
{
	foreach( getAllLanguages() as $fetch)
	{
		$language=$fetch['languageName'];
		$query=mysql_query("SELECT `id` FROM `categories` WHERE (`parentId`='$id' OR `id`='$id')");
		while($row=mysql_fetch_array($query))
		{
			$cache = phpFastCache();
			$cache->delete($row['id']."home_clicksDESC".$language);
			$cache->delete($row['id']."home_clicksASC".$language);
			$cache->delete($row['id']."home_idDESC".$language);
			$cache->delete($row['id']."home_idASC".$language);
			$cache->delete($row['id']."home_originalPriceDESC".$language);
			$cache->delete($row['id']."home_originalPriceASC".$language);
		}
	}
}
function featuredImageWidth() 
{
	$match = "SELECT `featuredImageWidth` FROM `mediaSettings`"; 
	$qry = mysql_query($match);
	$array=mysql_fetch_array($qry);
	return $array["featuredImageWidth"];
}
function featuredImageHeight() 
{
	$match = "SELECT `featuredImageHeight` FROM `mediaSettings`"; 
	$qry = mysql_query($match);
	$array=mysql_fetch_array($qry);
	return $array["featuredImageHeight"];
}

function articleImageWidth() 
{
	$match = "SELECT `articleThumbnailWidth` FROM `mediaSettings`"; 
	$qry = mysql_query($match);
	$array=mysql_fetch_array($qry);
	return $array["articleThumbnailWidth"];
}
function articleImageHeight() 
{
	$match = "SELECT `articleThumbnailHeight` FROM `mediaSettings`"; 
	$qry = mysql_query($match);
	$array=mysql_fetch_array($qry);
	return $array["articleThumbnailHeight"];
}

function productImageWidth() 
{
	$match = "SELECT `productImageWidth` FROM `mediaSettings`"; 
	$qry = mysql_query($match);
	$array=mysql_fetch_array($qry);
	return $array["productImageWidth"];
}
function productImageHeight() 
{
	$match = "SELECT `productImageHeight` FROM `mediaSettings`"; 
	$qry = mysql_query($match);
	$array=mysql_fetch_array($qry);
	return $array["productImageHeight"];
}

function smallThumbnailWidth() 
{
	$match = "SELECT `smallThumbnailWidth` FROM `mediaSettings`";
	$qry = mysql_query($match);
	$array=mysql_fetch_array($qry);
	return $array["smallThumbnailWidth"];
}

function smallThumbnailHeight() 
{
	$match = "SELECT `smallThumbnailHeight` FROM `mediaSettings`"; 
	$qry = mysql_query($match);
	$array=mysql_fetch_array($qry);
	return $array["smallThumbnailHeight"];
}

function mediumThumbnailWidth() 
{
	$match = "SELECT `mediumThumbnailWidth` FROM `mediaSettings`";
	$qry = mysql_query($match);
	$array=mysql_fetch_array($qry);
	return $array["mediumThumbnailWidth"];
}

function mediumThumbnailHeight() 
{
	$match = "SELECT `mediumThumbnailHeight` FROM `mediaSettings`"; 
	$qry = mysql_query($match);
	$array=mysql_fetch_array($qry);
	return $array["mediumThumbnailHeight"];
}
function largeThumbnailWidth() 
{
	$match = "SELECT `largeThumbnailWidth` FROM `mediaSettings`";
	$qry = mysql_query($match);
	$array=mysql_fetch_array($qry);
	return $array["largeThumbnailWidth"];
}

function largeThumbnailHeight() 
{
	$match = "SELECT `largeThumbnailHeight` FROM `mediaSettings`"; 
	$qry = mysql_query($match);
	$array=mysql_fetch_array($qry);
	return $array["largeThumbnailHeight"];
}

function mres($var) 
{
    if (get_magic_quotes_gpc()) 
	{
        $var = stripslashes(trim($var));	
    }
	return mysql_real_escape_string(trim($var));
}

function xssClean($data) 
{
	return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');	
}

function enableRecentCache()
{
	$row=mysql_fetch_array(mysql_query("SELECT `recentCache` FROM `cacheSettings`"));
	return $row['recentCache'];
}
function enableCategoryCache()
{
	$row=mysql_fetch_array(mysql_query("SELECT `categoryCache` FROM `cacheSettings`"));
	return $row['categoryCache'];
}
function enableTagsCache()
{
	$row=mysql_fetch_array(mysql_query("SELECT `tagsCache` FROM `cacheSettings`"));
	return $row['tagsCache'];
}
function enableRelatedCache()
{
	$row=mysql_fetch_array(mysql_query("SELECT `relatedCache` FROM `cacheSettings`"));
	return $row['relatedCache'];
}
function enableProductCache()
{
	$row=mysql_fetch_array(mysql_query("SELECT `productCache` FROM `cacheSettings`"));
	return $row['productCache'];
}
function enableArticleCache()
{
	$row=mysql_fetch_array(mysql_query("SELECT `articleCache` FROM `cacheSettings`"));
	return $row['articleCache'];
}

function recentCacheExpireTime()
{
	$row=mysql_fetch_array(mysql_query("SELECT `recentExpTime` FROM `cacheSettings`"));
	return $row['recentExpTime'];
}

function categoryCacheExpireTime()
{
	$row=mysql_fetch_array(mysql_query("SELECT `categoryExpTime` FROM `cacheSettings`"));
	return $row['categoryExpTime'];
}

function tagsCacheExpireTime()
{
	$row=mysql_fetch_array(mysql_query("SELECT `tagsExpTime` FROM `cacheSettings`"));
	return $row['tagsExpTime'];
}

function relatedCacheExpireTime()
{
	$row=mysql_fetch_array(mysql_query("SELECT `relatedExpTime` FROM `cacheSettings`"));
	return $row['relatedExpTime'];
}

function productCacheExpireTime()
{
	$row=mysql_fetch_array(mysql_query("SELECT `productExpTime` FROM `cacheSettings`"));
	return $row['productExpTime'];
}
function articleCacheExpireTime()
{
	$row=mysql_fetch_array(mysql_query("SELECT `articleExpTime` FROM `cacheSettings`"));
	return $row['articleExpTime'];
}

function productCategoryAndSubcategoryUrl($permalink)
{
	$productRow = mysql_fetch_array(mysql_query("SELECT `id` FROM `products` WHERE `permalink`='" . $permalink . "'"));
	$proCatRow = mysql_fetch_array(mysql_query("SELECT `cid` FROM `procat` WHERE `pid`='" . $productRow['id'] . "'"));
	$categoriesRow = mysql_fetch_array(mysql_query("SELECT *,`parentId` FROM `categories` WHERE `id`='" . $proCatRow['cid'] . "'"));
	if($categoriesRow['parentId']==0)
	{
		$parentCategoryName=$categoriesRow[$_SESSION['lanGuaGe']];
		$parentCategoryPermalink=$categoriesRow['permalink'];
		return '<li><a href="'.rootpath().'/category/'.$parentCategoryPermalink.'">'.$parentCategoryName.'</a></li>';
	}
	else
	{
		$parentCategoriesRow = mysql_fetch_array(mysql_query("SELECT * FROM `categories` WHERE `id`='" . $categoriesRow['parentId'] . "' AND parentId='0'"));
		$parentCategoryName=$parentCategoriesRow[$_SESSION['lanGuaGe']];
		$childCategoryName=$categoriesRow[$_SESSION['lanGuaGe']];
		$parentCategoryPermalink=$parentCategoriesRow['permalink'];
		$childCategoryPermalink=$categoriesRow['permalink'];
		return '<li><a href="'.rootpath().'/category/'.$parentCategoryPermalink.'">'.$parentCategoryName.'</a></li><li><a href="'.rootpath().'/category/'.$parentCategoryPermalink.'/'.$childCategoryPermalink.'">'.$childCategoryName.'</a></li>';
	}
}

function productsInParentCategory($parentId)
{
	$productIdFromProcat=array();
	$query=mysql_query("SELECT * FROM `procat` WHERE `cid` IN(SELECT `id` FROM `categories` WHERE `parentId`='$parentId') OR cid='$parentId'");
	while($row=mysql_fetch_array($query)){
		array_push($productIdFromProcat,$row['pid']);
	}
	$pid = "'". implode("', '", $productIdFromProcat) ."'";
	$count=mysql_num_rows(mysql_query("SELECT * FROM `products` WHERE `id` IN($pid) AND status='1'"));
	return $count;
}

function productsInChildCatecory($cid)
{
	$productIdFromProcat=array();
	$query=mysql_query("SELECT * FROM `procat` WHERE `cid`='$cid'");
	while($row=mysql_fetch_array($query)){
		array_push($productIdFromProcat,$row['pid']);
	}
	$pid = "'". implode("', '", $productIdFromProcat) ."'";
	$count=mysql_num_rows(mysql_query("SELECT * FROM `products` WHERE `id` IN($pid) AND status='1'"));
	return $count;
}

function parentCategoryName($permalink)
{
	$row=mysql_fetch_array(mysql_query("SELECT * FROM `categories` WHERE `id`=(SELECT `parentId` FROM `categories` WHERE `permalink`='$permalink')"));
	return $row[$_SESSION['lanGuaGe']];
}

function isValidPagePermalink($permalink)
{
	$count=mysql_num_rows(mysql_query("SELECT * FROM `pages` WHERE permalink='$permalink'"));
	if($count > 0)
	return true;
	else
	return false;
}

function validEmail($email) 
{
	return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

function validName($name) 
{
	return (bool) preg_match("/^([a-zA-Z ])+$/i", $name);
}

function onOffContactCaptcha()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT `contactCaptcha` FROM `captchaSettings`"));
	return $fetch['contactCaptcha'];
}

function getCommentsData()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `commentSettings`"));
	return $fetch;
}

function disqusUserName()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT `disqusUserName` FROM `commentSettings`"));
	return $fetch['disqusUserName'];
}

function getWebDate() 
{
	$result=mysql_fetch_array(mysql_query("SELECT * FROM `settings`"));
	return $result;
}
function getPageData($permalink,$language) 
{
	$id=pageIdByPermalink($permalink);
	$result=mysql_fetch_array(mysql_query("SELECT p.*,pl.* FROM `pages` p,`pagesLanguage` pl WHERE p.`id`='$id' AND p.`id`=pl.`id` AND pl.`language`='$language'"));
	return $result;
}
function getProductData($permalink,$language) 
{
	$result=mysql_fetch_array(mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.permalink='$permalink' AND p.`id`=pl.`id` AND pl.`language`='$language'"));
	return $result;
}

function productRating($id)
{
	$q = mysql_query("SELECT * FROM ratings WHERE id='$id'");
	$n = mysql_num_rows($q);
	while($r=mysql_fetch_array($q)){
		$rr = $r["rating"];
		$x += $rr;
	}
	if($n)
		$rating = $x/$n;
	else 
		$rating = 0;
	for($i=1; $i<=floor($rating); $i++){
		$stars .= '<div class="star" id="'.$i.'"></div>';
	}
	?>
	<div class="rating">
	<?php
	for($i=1; $i<=floor($rating); $i++){
	?>
	<div class="star active" id="<?php echo $i?>"></div>
	<?php
	}
	for($i=floor($rating)+1; $i<=5; $i++){
	?>
	<div class="star" id="<?php echo $i?>"></div>
	<?php
	}?>
	</div>
	<?php
}
function articleRating($id)
{
	$q = mysql_query("SELECT * FROM articleRatings WHERE id='$id'");
	$n = mysql_num_rows($q);
	while($r=mysql_fetch_array($q)){
		$rr = $r["rating"];
		$x += $rr;
	}
	if($n)
		$rating = $x/$n;
	else 
		$rating = 0;
	for($i=1; $i<=floor($rating); $i++){
		$stars .= '<div class="star" id="'.$i.'"></div>';
	}
	?>
	<div class="rating">
	<?php
	for($i=1; $i<=floor($rating); $i++){
	?>
	<div class="star active" id="<?php echo $i?>"></div>
	<?php
	}
	for($i=floor($rating)+1; $i<=5; $i++){
	?>
	<div class="star" id="<?php echo $i?>"></div>
	<?php
	}?>
	</div>
	<?php
}
function incrementUniqueHits()
{
	$sql = mysql_query("SELECT uniqueHits FROM `stats` WHERE `datetime`=CURDATE()");
	$rows = mysql_num_rows($sql);
	if($rows>0)
	{
		$sql_update = mysql_query("UPDATE `stats` SET `uniqueHits`=`uniqueHits`+1 WHERE `datetime`=CURDATE()");
	}
	else
	{
		$sql_insert= mysql_query("INSERT INTO `stats`(`pageViews`, `uniqueHits`,`clicks`, `datetime`) VALUES 	('0','1','0',CURDATE())");
	}
}
function countViews($id)
{	
	mysql_query("UPDATE `products` SET `views`=`views`+1 WHERE `id`='$id'");
}	

function countClicks($id)
{
	mysql_query("UPDATE `products` SET `clicks`=`clicks`+1 WHERE `id`='$id'");
}	
function incrementPageViews()
{
	$sql = mysql_query("SELECT pageViews FROM `stats` WHERE `datetime`=CURDATE()");
	$rows = mysql_num_rows($sql);
	if($rows>0)
	{
		$sql_update = mysql_query("UPDATE `stats` SET `pageViews`=`pageViews`+1 WHERE `datetime`=CURDATE()");
	}
	else
	{
		$sql_insert= mysql_query("INSERT INTO `stats`(`pageViews`, `uniqueHits`,`clicks`, `datetime`) VALUES 	('1','0','0',CURDATE())");
	}
}
function resizeImg($filename)
{   
$options = array('jpegQuality' => 100);
if(substr_count($filename, '-img')){
$orgfilename=$filename;
$filename=str_replace('-img','',$filename);
copy('../images/productImages/'.$filename,'../images/productImages/featuredImagesBackUp/_'.$orgfilename);
$thumb = PhpThumbFactory::create("../images/productImages/" . $filename,	$options);
$thumb->adaptiveResize(featuredImageWidth(), featuredImageHeight());
$thumb->save("../images/productImages/_" . $orgfilename);
} else {
copy('../images/productImages/'.$filename,'../images/productImages/featuredImagesBackUp/_'.$orgfilename);
$thumb = PhpThumbFactory::create("../images/productImages/_" . $filename,	$options);
$thumb->adaptiveResize(featuredImageWidth(), featuredImageHeight());
$thumb->save("../images/productImages/_" . $filename);
}  
return true;
}

function regenerateArticleImage($id,$filename)
{   
$options = array('jpegQuality' => 100);
if(substr_count($filename, '-img')){
$orgfilename=$filename;
$filename=str_replace('-img','',$filename);
$thumb = PhpThumbFactory::create("../images/articleImages/articleImagesBackUp/" . $filename,	$options);
$thumb->adaptiveResize(articleImageWidth(), articleImageHeight());
$thumb->save("../images/articleImages/" . $orgfilename);
} 
return true;
}

function regenerateSmallThumbnail($id,$filename)
{   
$options = array('jpegQuality' => 100);
$thumb = PhpThumbFactory::create("../images/productImages/thumbnailsBackUp/" . $filename,	$options);
$thumb->adaptiveResize(smallThumbnailWidth(), smallThumbnailHeight());
$thumb->save("../images/productImages/thumb1/" . $filename);
return true;
}

function regenerateMediumThumbnail($id,$filename)
{   
$options = array('jpegQuality' => 100);
$thumb = PhpThumbFactory::create("../images/productImages/thumbnailsBackUp/" . $filename,	$options);
$thumb->adaptiveResize(mediumThumbnailWidth(), mediumThumbnailHeight());
$thumb->save("../images/productImages/thumb2/" . $filename);
return true;
}

function regeneratelargeThumbnail($id,$filename)
{   
$options = array('jpegQuality' => 100);
$thumb = PhpThumbFactory::create("../images/productImages/thumbnailsBackUp/" . $filename,	$options);
$thumb->adaptiveResize(largeThumbnailWidth(), largeThumbnailHeight());
$thumb->save("../images/productImages/thumb3/" . $filename);
return true;
}

function regenerateFeaturedImage($id,$filename)
{   
$options = array('jpegQuality' => 100);
if(substr_count($filename, '-img')){
$orgfilename=$filename;
$filename=str_replace('-img','',$filename);
$thumb = PhpThumbFactory::create("../images/productImages/featuredImagesBackUp/_" . $orgfilename,	$options);
$thumb->adaptiveResize(featuredImageWidth(), featuredImageHeight());
$thumb->save("../images/productImages/_" . $orgfilename);
} else {
copy('../images/productImages/'.$filename,'../images/productImages/featuredImagesBackUp/_'.$orgfilename);
$thumb = PhpThumbFactory::create("../images/productImages/_" . $filename,	$options);
$thumb->adaptiveResize(featuredImageWidth(), featuredImageHeight());
$thumb->save("../images/productImages/_" . $filename);
}  
return true;
}

function regenerateProductImage($id,$filename)
{   
$options = array('jpegQuality' => 100);
if(substr_count($filename, '-img')){
$orgfilename=$filename;
$filename=str_replace('-img','',$filename);
$thumb = PhpThumbFactory::create("../images/productImages/featuredImagesBackUp/_" . $orgfilename,	$options);
$thumb->adaptiveResize(productImageWidth(), productImageHeight());
$thumb->save("../images/productImages/" . $orgfilename);
} else {
copy('../images/productImages/'.$filename,'../images/productImages/featuredImagesBackUp/_'.$orgfilename);
$thumb = PhpThumbFactory::create("../images/productImages/" . $filename,	$options);
$thumb->adaptiveResize(productImageWidth(), productImageHeight());
$thumb->save("../images/productImages/" . $filename);
}  
return true;
}

function unlinkArticleImg($filename)
{
$filename=str_replace('-img','',$filename);
unlink("../images/articleImages/" . $filename);
unlink('../images/articleImages/articleImagesBackUp/'.$filename);
}

function featuredProductsLimit($id)
{
$query=mysql_fetch_array(mysql_query("SELECT `featuredProductsLimit` FROM `mediaSettings`"));
return $query['featuredProductsLimit'];
}

function getCategoryData($permalink)
{
$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `categories` WHERE `permalink`='$permalink'"));
return $fetch;
}

function getArticleCategoryData($permalink)
{
$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `articleCategories` WHERE `permalink`='$permalink'"));
return $fetch;
}

function unpublishAfterExpireDate($date)
{
	mysql_query("UPDATE `products` SET `status`='0' WHERE `expiryDate`!='0000-00-00' AND `expiryDate`<='$date'");
	mysql_query("UPDATE `expire` SET `expireStatus`='1',`date`='$date'");
}
function alreadyUnpublishAfterExpiry($date)
{
	$fetch=mysql_fetch_array(mysql_query("SELECT `expireStatus` FROM `expire` WHERE `date`='$date'"));
	if($fetch['expireStatus'])
		return true;
	else
		return false;
}
function getDefaultLanguage(){
	$fetch=mysql_fetch_array(mysql_query("SELECT `languageName` FROM `languages` WHERE `status`='1' ORDER BY `displayOrder` LIMIT 1"));
	return $fetch['languageName'];
}

function languageAlreadyExist($languageName,$id){
	$count=mysql_fetch_array(mysql_query("SELECT * FROM `languages` WHERE `languageName`='$languageName' AND `id`!='$id'"));
	if($count > 0)
		return true;
	else
		return false;
}

function getLanguageCode($language){
$language=ucfirst($language);
$languageCode= array(
    'aa' => 'Afar',
    'ab' => 'Abkhaz',
    'ae' => 'Avestan',
    'af' => 'Afrikaans',
    'ak' => 'Akan',
    'am' => 'Amharic',
    'an' => 'Aragonese',
    'ar' => 'Arabic',
    'as' => 'Assamese',
    'av' => 'Avaric',
    'ay' => 'Aymara',
    'az' => 'Azerbaijani',
    'ba' => 'Bashkir',
    'be' => 'Belarusian',
    'bg' => 'Bulgarian',
    'bh' => 'Bihari',
    'bi' => 'Bislama',
    'bm' => 'Bambara',
    'bn' => 'Bengali',
    'bo' => 'Tibetan Standard, Tibetan, Central',
    'br' => 'Breton',
    'bs' => 'Bosnian',
    'ca' => 'Catalan; Valencian',
    'ce' => 'Chechen',
    'ch' => 'Chamorro',
    'co' => 'Corsican',
    'cr' => 'Cree',
    'cs' => 'Czech',
    'cu' => 'Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic',
    'cv' => 'Chuvash',
    'cy' => 'Welsh',
    'da' => 'Danish',
    'de' => 'German',
    'dv' => 'Divehi; Dhivehi; Maldivian;',
    'dz' => 'Dzongkha',
    'ee' => 'Ewe',
    'el' => 'Greek, Modern',
    'en' => 'English',
    'eo' => 'Esperanto',
    'es' => 'Spanish; Castilian',
    'et' => 'Estonian',
    'eu' => 'Basque',
    'fa' => 'Persian',
    'ff' => 'Fula; Fulah; Pulaar; Pular',
    'fi' => 'Finnish',
    'fj' => 'Fijian',
    'fo' => 'Faroese',
    'fr' => 'French',
    'fy' => 'Western Frisian',
    'ga' => 'Irish',
    'gd' => 'Scottish Gaelic; Gaelic',
    'gl' => 'Galician',
    'gn' => 'GuaranÃ­',
    'gu' => 'Gujarati',
    'gv' => 'Manx',
    'ha' => 'Hausa',
    'he' => 'Hebrew (modern)',
    'hi' => 'Hindi',
    'ho' => 'Hiri Motu',
    'hr' => 'Croatian',
    'ht' => 'Haitian; Haitian Creole',
    'hu' => 'Hungarian',
    'hy' => 'Armenian',
    'hz' => 'Herero',
    'ia' => 'Interlingua',
    'id' => 'Indonesian',
    'ie' => 'Interlingue',
    'ig' => 'Igbo',
    'ii' => 'Nuosu',
    'ik' => 'Inupiaq',
    'io' => 'Ido',
    'is' => 'Icelandic',
    'it' => 'Italian',
    'iu' => 'Inuktitut',
    'ja' => 'Japanese (ja)',
    'jv' => 'Javanese (jv)',
    'ka' => 'Georgian',
    'kg' => 'Kongo',
    'ki' => 'Kikuyu, Gikuyu',
    'kj' => 'Kwanyama, Kuanyama',
    'kk' => 'Kazakh',
    'kl' => 'Kalaallisut, Greenlandic',
    'km' => 'Khmer',
    'kn' => 'Kannada',
    'ko' => 'Korean',
    'kr' => 'Kanuri',
    'ks' => 'Kashmiri',
    'ku' => 'Kurdish',
    'kv' => 'Komi',
    'kw' => 'Cornish',
    'ky' => 'Kirghiz, Kyrgyz',
    'la' => 'Latin',
    'lb' => 'Luxembourgish, Letzeburgesch',
    'lg' => 'Luganda',
    'li' => 'Limburgish, Limburgan, Limburger',
    'ln' => 'Lingala',
    'lo' => 'Lao',
    'lt' => 'Lithuanian',
    'lu' => 'Luba-Katanga',
    'lv' => 'Latvian',
    'mg' => 'Malagasy',
    'mh' => 'Marshallese',
    'mi' => 'Maori',
    'mk' => 'Macedonian',
    'ml' => 'Malayalam',
    'mn' => 'Mongolian',
    'mr' => 'Marathi (Mara?hi)',
    'ms' => 'Malay',
    'mt' => 'Maltese',
    'my' => 'Burmese',
    'na' => 'Nauru',
    'nb' => 'Norwegian BokmÃ¥l',
    'nd' => 'North Ndebele',
    'ne' => 'Nepali',
    'ng' => 'Ndonga',
    'nl' => 'Dutch',
    'nn' => 'Norwegian Nynorsk',
    'no' => 'Norwegian',
    'nr' => 'South Ndebele',
    'nv' => 'Navajo, Navaho',
    'ny' => 'Chichewa; Chewa; Nyanja',
    'oc' => 'Occitan',
    'oj' => 'Ojibwe, Ojibwa',
    'om' => 'Oromo',
    'or' => 'Oriya',
    'os' => 'Ossetian, Ossetic',
    'pa' => 'Panjabi, Punjabi',
    'pi' => 'Pali',
    'pl' => 'Polish',
    'ps' => 'Pashto, Pushto',
    'pt' => 'Portuguese',
    'qu' => 'Quechua',
    'rm' => 'Romansh',
    'rn' => 'Kirundi',
    'ro' => 'Romanian, Moldavian, Moldovan',
    'ru' => 'Russian',
    'rw' => 'Kinyarwanda',
    'sa' => 'Sanskrit (Sa?sk?ta)',
    'sc' => 'Sardinian',
    'sd' => 'Sindhi',
    'se' => 'Northern Sami',
    'sg' => 'Sango',
    'si' => 'Sinhala, Sinhalese',
    'sk' => 'Slovak',
    'sl' => 'Slovene',
    'sm' => 'Samoan',
    'sn' => 'Shona',
    'so' => 'Somali',
    'sq' => 'Albanian',
    'sr' => 'Serbian',
    'ss' => 'Swati',
    'st' => 'Southern Sotho',
    'su' => 'Sundanese',
    'sv' => 'Swedish',
    'sw' => 'Swahili',
    'ta' => 'Tamil',
    'te' => 'Telugu',
    'tg' => 'Tajik',
    'th' => 'Thai',
    'ti' => 'Tigrinya',
    'tk' => 'Turkmen',
    'tl' => 'Tagalog',
    'tn' => 'Tswana',
    'to' => 'Tonga (Tonga Islands)',
    'tr' => 'Turkish',
    'ts' => 'Tsonga',
    'tt' => 'Tatar',
    'tw' => 'Twi',
    'ty' => 'Tahitian',
    'ug' => 'Uighur, Uyghur',
    'uk' => 'Ukrainian',
    'ur' => 'Urdu',
    'uz' => 'Uzbek',
    've' => 'Venda',
    'vi' => 'Vietnamese',
    'vo' => 'VolapÃ¼k',
    'wa' => 'Walloon',
    'wo' => 'Wolof',
    'xh' => 'Xhosa',
    'yi' => 'Yiddish',
    'yo' => 'Yoruba',
    'za' => 'Zhuang, Chuang',
    'zh' => 'Chinese',
    'zu' => 'Zulu',
);
   foreach($languageCode as $key => $value) {
    if($value==$language)
		$returnKey=$key;
  }
  return $returnKey;
}
function englishLanguage($id){
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `languages` WHERE `id`='$id'"));
	if($fetch['languageName']=='english')
		return true;
	else
		return false;
}
function articleCategoryNameById($cid)
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `articleCategories` WHERE `cid`='$cid'"));
	return $fetch[$_SESSION['lanGuaGe']];
}
function adminArticleCategoryNameById($cid)
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `articleCategories` WHERE `cid`='$cid'"));
	return $fetch['english'];
}
function articleCategoryNameByPermalink($permalink)
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `articleCategories` WHERE `permalink`='$permalink'"));
	return $fetch[$_SESSION['lanGuaGe']];
}
function articleCategoryPermalinkById($cid)
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `articleCategories` WHERE `cid`='$cid'"));
	return $fetch['permalink'];
}
function articleCategoryIdByPermalink($permalink)
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `articleCategories` WHERE `permalink`='$permalink'"));
	return $fetch['cid'];
}
function getArticleCategoryId($id){
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `articles` WHERE `id`='$id'"));
	return $fetch['cid'];
}
function getArticleIdByPermalink($permalink){
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `articles` WHERE `permalink`='$permalink'"));
	return $fetch['id'];
}
function articleName(){
	$fetch=mysql_fetch_array(mysql_query("SELECT `name` FROM `articleSettings`"));
	return $fetch['name'];
}
function articleViews($permalink){
	mysql_query("UPDATE `articles` SET `views`=`views`+1 WHERE `permalink`='$permalink'");
}
function getArticleData($permalink,$language){
	$fetch=mysql_fetch_array(mysql_query("SELECT a.*,al.* FROM `articles` a,`articlesLanguage` al WHERE a.`permalink`='$permalink' AND a.`id`=al.`id` AND al.`language`='$language'"));
	return $fetch;
}
function articlesPerPage(){
	$fetch=mysql_fetch_array(mysql_query("SELECT `articlesLimit` FROM `mediaSettings`"));
	return $fetch['articlesLimit'];
}
function enableArticles(){
	$fetch=mysql_fetch_array(mysql_query("SELECT `status` FROM `articleSettings`"));
	return $fetch['status'];
}
function articleCategoryId($id){
	$fetch=mysql_fetch_array(mysql_query("SELECT `cid` FROM `articles` WHERE `id`='$id'"));
	return $fetch['cid'];
}
function pageIdByPermalink($permalink){
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `pages` WHERE `permalink`='$permalink'"));
	return $fetch['id'];
}
function getAllLanguages(){
$query = mysql_query("SELECT * FROM languages");
$array = array();
while($row = mysql_fetch_assoc($query)){
  $array[] = $row;
}
return $array;
}
function enableRTL($language){
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `languages` WHERE `languageName`='$language'"));
	return $fetch['rtlStatus'];
}
function analyticsData(){
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `analytics`"));
	return $fetch;
}
function isParentCategoryById($id)
{
	$count=mysql_num_rows(mysql_query("SELECT * FROM `categories` WHERE `id`='$id' AND `parentId`=0"));
	if($count > 0)
	return true;
	else
	return false;
}
function categoryPermalinkById($id)
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `categories` WHERE `id`='$id'"));
	return $fetch['permalink'];
}
function categoryPermalinkByChild($permalink)
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `categories` WHERE `id`=(SELECT `parentId` FROM `categories` WHERE `permalink`='$permalink')"));
	return $fetch['permalink'];
}
function socialLogin()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `apiSettings`"));
	return $fetch;
}
function appId()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `apiSettings`"));
	return $fetch['appId'];
}
function appSecret()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `apiSettings`"));
	return $fetch['appSecret'];
}
function consumerKey()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `apiSettings`"));
	return $fetch['consumerKey'];
}
function consumerSecret()
{
	$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `apiSettings`"));
	return $fetch['consumerSecret'];
}
function isFavourite($uid,$pid)
{
	$count=mysql_num_rows(mysql_query("SELECT * FROM `favourite` WHERE `uid`='$uid' AND `pid`='$pid'"));
	if($count)
		return true;
	else
		return false; 
}
function clean_permalink($permalink)
{
	$to_clean = array(
	"#",
	"%",
	"&",
	"$",
	"*",
	"{",
	"}",
	"(",
	")",
	"@",
	"^",
	"|",
	"/",
	";",
	".",
	",",
	"`",
	"!",
	"\\",
	":",
	"<",
	">",
	"?",
	"/",
	"+",
	'"',
	"'"
	);
	
	$permalink = str_replace(" ", "-", $permalink);
	
	foreach($to_clean as $symbol)
	{
		$permalink = str_replace($symbol, "", $permalink);
	}
	while (strpos($permalink, '--') !== FALSE)
		$permalink = str_replace("--", "-", $permalink);
	
	$permalink = rtrim($permalink, "-");
	
	$permalink = ltrim($permalink, "-");
	
	if ($permalink != "-") 
		return $permalink;
	else 
		return "";
	
}
function cleanNum($num) {
        $num = number_format($num, 2, '.', '');
        return preg_replace('~\.0*$~', '', $num);
}
?>