<?php
//error_reporting(0);
if(!isset($_SESSION))
	session_start();
if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf')
$userType=1;
else if($_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87')
$userType=2;
else if($_SESSION['type']=='3d3c3f3cc8a1f15cf46db659eaa2b63d')
$userType=3;
$userId=$_SESSION['id'];
include '../config/config.php';
require "../include/cache/phpfastcache.php";
phpFastCache::setup("storage","auto");
include '../common/functions.php';
include 'common/libs/resizeClass.php';
function resizeImage($imageName,$sourcePath,$destinationPath,$width,$height)
{
	$resizeObj = new resize($sourcePath.'/'.$imageName);

	// *** 2) Resize image (options: exact, portrait, landscape, auto, crop)
	$resizeObj -> resizeImage($width, $height, 'crop');

	// *** 3) Save image
	$resizeObj -> saveImage($destinationPath.'/'.$imageName, 100);
}
if(isset($_POST['csvFile'])){
	$file = stripslashes($_FILES['csv']['tmp_name']);
	 $contents = file_get_contents($file);
	 $contents = str_replace('&','&amp;',$contents);
	 file_put_contents($file,$contents); //rewriting file with new string
    $data = simplexml_load_file($file);
    foreach ($data as $product):
        $title=mysql_real_escape_string(trim($product->title));
		$permalink=genPermalink($title);
        $description=mysql_real_escape_string(trim($product->description));
		$summary=mysql_real_escape_string(strip_tags(trim($product->summary)));
        $productImageUrl=mysql_real_escape_string(trim($product->productImageUrl));
		$affiliateUrl=mysql_real_escape_string(trim($product->affiliateUrl));
        $originalPrice=mysql_real_escape_string(trim($product->originalPrice));
		$salePrice=mysql_real_escape_string(trim($product->salePrice));
		$categoryPermalink=mysql_real_escape_string(trim($product->categoryPermalink));
		if($salePrice=="")
			$saleStatus=0;
		else
			$saleStatus=1;
        $tags=mysql_real_escape_string(trim($product->tags));
		$expiryDate=trim($product->expiryDate);
		if($expiryDate=="")
			$expiryDate='0000-00-00';
		$publishedDate=date("Y-m-d");
        $image1Url=trim($product->image1Url);
		$image2Url=trim($product->image2Url);
        $image3Url=trim($product->image3Url);
		$image4Url=trim($product->image4Url);
        $image5Url=trim($product->image5Url);
		$status=mres(trim($_POST['status']));
		
		$productImageExt = strtolower(pathinfo($productImageUrl, PATHINFO_EXTENSION));
		$image1Ext = strtolower(pathinfo($image1Url, PATHINFO_EXTENSION));
		$image2Ext = strtolower(pathinfo($image2Url, PATHINFO_EXTENSION));
		$image3Ext = strtolower(pathinfo($image3Url, PATHINFO_EXTENSION));
		$image4Ext = strtolower(pathinfo($image4Url, PATHINFO_EXTENSION));
		$image5Ext = strtolower(pathinfo($image5Url, PATHINFO_EXTENSION));
		
		list($productImageWidth, $productImageHeight) = getimagesize($productImageUrl);
		list($image1Width, $image1Height) = getimagesize($image1Url);
		list($image2Width, $image2Height) = getimagesize($image2Url);
		list($image3Width, $image3Height) = getimagesize($image3Url);
		list($image4Width, $image4Height) = getimagesize($image4Url);
		list($image5Width, $image5Height) = getimagesize($image5Url);
		if($title=="")
		{
			$titleError='Title Required';
			$error=true;
		}
		else if($productImageUrl=="")
		{
			$errorMsg='Product Image Url Required';
			$error=true;
		}
		else if(!validExtension($productImageExt))
		{
			echo $errorMsg='Invalid Product Image Extension';
			$error=true;
		}
		else if($productImageWidth <featuredImageWidth() || $productImageHeight <featuredImageHeight())
		{
			$errorMsg='Invalid Product Image Width,Height Minimum Size Is '.featuredImageWidth().' X '.featuredImageHeight();
			$error=true;
		}
		else if($affiliateUrl=="" || !validUrl($affiliateUrl))
		{
			$errorMsg='Empty or invalid affiliate url';
			$error=true;
		}
		else if($originalPrice=="" || !validPrice($originalPrice))
		{
			$errorMsg='Empty or invalid original price';
			$error=true;
		}
		else if($salePrice!="" && !validPrice($salePrice))
		{
			$errorMsg='Invalid sale price';
			$error=true;
		}
		else if($image1Url=="" && $image2Url=="" && $image3Url=="" && $image4Url=="" && $image5Url=="")
		{
			$errorMsg='Paste Atleast One Image Url';
			$error=true;
		}
		else if($image1Url!="" && !validExtension($image1Ext))
		{
			$errorMsg='Invalid Image1 Extension';
			$error=true;
		}
		else if($image2Url!="" && !validExtension($image2Ext))
		{
			$errorMsg='Invalid Image2 Extension';
			$error=true;
		}
		else if($image3Url!="" && !validExtension($image3Ext))
		{
			$errorMsg='Invalid Image3 Extension';
			$error=true;
		}
		else if($image4Url!="" && !validExtension($image4Ext))
		{
			$errorMsg='Invalid Image4 Extension';
			$error=true;
		}
		else if($image5Url!="" && !validExtension($image5Ext))
		{
			$errorMsg='Invalid Image5 Extension';
			$error=true;
		}
		else if($image1Url!="" && ($image1Width < largeThumbnailWidth() || $image1Height < largeThumbnailHeight()))
		{
			$errorMsg='Invalid Image1 Width,Height Minimum Size Is '.largeThumbnailWidth().' X '.largeThumbnailHeight();
			$error=true;
		}
		else if($image2Url!="" && ($image2Width < largeThumbnailWidth() || $image2Height < largeThumbnailHeight()))
		{
			$errorMsg='Invalid Image2 Width,Height Minimum Size Is '.largeThumbnailWidth().' X '.largeThumbnailHeight();
			$error=true;
		}
		else if($image1Ur3!="" && ($image3Width < largeThumbnailWidth() || $image3Height < largeThumbnailHeight()))
		{
			$errorMsg='Invalid Image3 Width,Height Minimum Size Is '.largeThumbnailWidth().' X '.largeThumbnailHeight();
			$error=true;
		}
		else if($image4Url!="" && ($image4Width < largeThumbnailWidth() || $image4Height < largeThumbnailHeight()))
		{
			$errorMsg='Invalid Image4 Width,Height Minimum Size Is '.largeThumbnailWidth().' X '.largeThumbnailHeight();
			$error=true;
		}
		else if($image5Url!="" && ($image5Width < largeThumbnailWidth() || $image5Height < largeThumbnailHeight()))
		{
			$errorMsg='Invalid Image5 Width,Height Minimum Size Is '.largeThumbnailWidth().' X '.largeThumbnailHeight();
			$error=true;
		}
		if(!isValidCategory($categoryPermalink))
		{
			$errorMsg='Invalid Category';
			$error=true;
		}
		if(!$error)
		{
			$productImageName=time().'-img.'.$productImageExt;
			copy($productImageUrl,"../images/productImages/".$productImageName);
			resizeImage($productImageName,"../images/productImages/","../images/productImages/",productImageWidth(),productImageHeight());
			copy($productImageUrl,"../images/productImages/_".$productImageName);
			copy($productImageUrl,"../images/productImages/featuredImagesBackUp/_".$productImageName);
			resizeImage('_'.$productImageName,"../images/productImages/","../images/productImages/",featuredImageWidth(),featuredImageHeight());
			
			//image1 data
			if($image1Url!="")
			{
				$image1Name=time().uniqid().'.'.$image1Ext;
				copy($image1Url,"../images/productImages/thumb3/".$image1Name);
				
				resizeImage($image1Name,"../images/productImages/thumb3/","../images/productImages/thumb1/",smallThumbnailWidth(),smallThumbnailHeight());
				
				resizeImage($image1Name,"../images/productImages/thumb3/","../images/productImages/thumb2/",mediumThumbnailWidth(),mediumThumbnailHeight());
				
				resizeImage($image1Name,"../images/productImages/thumb3/","../images/productImages/thumb3/",largeThumbnailWidth(),largeThumbnailHeight());
			}
			
			//image2 data
			if($image2Url!="")
			{
				$image2Name=time().uniqid().'.'.$image2Ext;
				copy($image2Url,"../images/productImages/thumb3/".$image2Name);
				
				resizeImage($image2Name,"../images/productImages/thumb3/","../images/productImages/thumb1/",smallThumbnailWidth(),smallThumbnailHeight());
				
				resizeImage($image2Name,"../images/productImages/thumb3/","../images/productImages/thumb2/",mediumThumbnailWidth(),mediumThumbnailHeight());
				
				resizeImage($image2Name,"../images/productImages/thumb3/","../images/productImages/thumb3/",largeThumbnailWidth(),largeThumbnailHeight());
			}
			
			//image3 data
			if($image3Url!="")
			{
				$image3Name=time().uniqid().'.'.$image3Ext;
				copy($image3Url,"../images/productImages/thumb3/".$image3Name);
				
				resizeImage($image3Name,"../images/productImages/thumb3/","../images/productImages/thumb1/",smallThumbnailWidth(),smallThumbnailHeight());
				
				resizeImage($image3Name,"../images/productImages/thumb3/","../images/productImages/thumb2/",mediumThumbnailWidth(),mediumThumbnailHeight());
				
				resizeImage($image3Name,"../images/productImages/thumb3/","../images/productImages/thumb3/",largeThumbnailWidth(),largeThumbnailHeight());
			}
			
			//image4 data
			if($image4Url!="")
			{
				$image4Name=time().uniqid().'.'.$image4Ext;
				copy($image4Url,"../images/productImages/thumb3/".$image4Name);
				
				resizeImage($image4Name,"../images/productImages/thumb3/","../images/productImages/thumb1/",smallThumbnailWidth(),smallThumbnailHeight());
				
				resizeImage($image4Name,"../images/productImages/thumb3/","../images/productImages/thumb2/",mediumThumbnailWidth(),mediumThumbnailHeight());
				
				resizeImage($image4Name,"../images/productImages/thumb3/","../images/productImages/thumb3/",largeThumbnailWidth(),largeThumbnailHeight());
			}
			
			//image5 data
			if($image5Url!="")
			{
				$image5Name=time().uniqid().'.'.$image5Ext;
				copy($image5Url,"../images/productImages/thumb3/".$image5Name);
				
				resizeImage($image5Name,"../images/productImages/thumb3/","../images/productImages/thumb1/",smallThumbnailWidth(),smallThumbnailHeight());
				
				resizeImage($image5Name,"../images/productImages/thumb3/","../images/productImages/thumb2/",mediumThumbnailWidth(),mediumThumbnailHeight());
				
				resizeImage($image5Name,"../images/productImages/thumb3/","../images/productImages/thumb3/",largeThumbnailWidth(),largeThumbnailHeight());
			}
			mysql_query("INSERT INTO products(`userType`,`userId`, `permalink`,`image`,`url`,`originalPrice`,`salePrice`,`saleStatus`,`tags`,`status`,`publishedDate`,`expiryDate`,`image1`,`image2`,`image3`,`image4`,`image5`) VALUES ('$userType','$userId','$permalink','$productImageName','$affiliateUrl','$originalPrice','$salePrice','$saleStatus','$tags','$status','$publishedDate','$expiryDate','$image1Name','$image2Name','$image3Name','$image4Name','$image5Name')") or die(mysql_error());
			$pid=mysql_insert_id();
			$cid=catPermalinkToId($categoryPermalink);
			mysql_query("INSERT INTO `procat` (`cid`,`pid`) VALUES('$cid','$pid')");
			foreach(getAllLanguages() as $fetch){
			mysql_query("INSERT INTO `productsLanguage` (`id`,`language`,`title`,`description`,`summary`) VALUES('$pid','".$fetch['languageName']."','$title','$description','$summary')");
			}
		}
		if($error)
		{
			echo '<span class="label label-info"><i class="fa fa-times"></i> '.(strlen($title) > 70 ? substr($title, 0, 70).'...': $title).'</span> <span class="label label-danger"> '.$errorMsg.'</span><br>';
			$errorMsg="";
			$error="";
		}
		else
		{
			if(isParentCategoryById($cid))
			{
			clearRecentCache($cid);
			clearCategorycache($cid);
			}
			else
			{
			$cid=parentIdByChildId($cid);
			clearRecentCache($cid);
			clearCategorycache($cid);
			}
			clearTagsCache($tags);
			echo '<span class="label label-info"><i class="fa fa-check-circle-o"></i> '.(strlen($title) > 70 ? substr($title, 0, 70).'...': $title).'</span> <span class="label label-success">Added successfully</span><br>';
		}
    endforeach;
}
if(isset($_POST['bulkUpload']))
{
	if(trim($_POST['bulkUpload'])!="")
	{
		$status=mres(trim($_POST['status']));
		$data=stripslashes($_POST['bulkUpload']);
		$data = str_replace('&','&amp;',$data);
		$product = new SimpleXMLElement($data.'</data>');
		$title=mysql_real_escape_string(trim($product->title));
		$permalink=genPermalink($title);
		$description=mysql_real_escape_string(trim($product->description));
		$summary=mysql_real_escape_string(strip_tags(trim($product->summary)));
		$productImageUrl=mysql_real_escape_string(trim($product->productImageUrl));
		$affiliateUrl=mysql_real_escape_string(trim($product->affiliateUrl));
		$originalPrice=mysql_real_escape_string(trim($product->originalPrice));
		$salePrice=mysql_real_escape_string(trim($product->salePrice));
		$categoryPermalink=mysql_real_escape_string(trim($product->categoryPermalink));
		if($salePrice=="")
			$saleStatus=0;
		else
			$saleStatus=1;
		$tags=mysql_real_escape_string(trim($product->tags));
		$expiryDate=trim($product->expiryDate);
		if($expiryDate=="")
			$expiryDate='0000-00-00';
		$publishedDate=date("Y-m-d");
		$image1Url=trim($product->image1Url);
		$image2Url=trim($product->image2Url);
		$image3Url=trim($product->image3Url);
		$image4Url=trim($product->image4Url);
		$image5Url=trim($product->image5Url);
		$productImageExt = strtolower(pathinfo($productImageUrl, PATHINFO_EXTENSION));
		$image1Ext = strtolower(pathinfo($image1Url, PATHINFO_EXTENSION));
		$image2Ext = strtolower(pathinfo($image2Url, PATHINFO_EXTENSION));
		$image3Ext = strtolower(pathinfo($image3Url, PATHINFO_EXTENSION));
		$image4Ext = strtolower(pathinfo($image4Url, PATHINFO_EXTENSION));
		$image5Ext = strtolower(pathinfo($image5Url, PATHINFO_EXTENSION));
		
		list($productImageWidth, $productImageHeight) = getimagesize($productImageUrl);
		list($image1Width, $image1Height) = getimagesize($image1Url);
		list($image2Width, $image2Height) = getimagesize($image2Url);
		list($image3Width, $image3Height) = getimagesize($image3Url);
		list($image4Width, $image4Height) = getimagesize($image4Url);
		list($image5Width, $image5Height) = getimagesize($image5Url);
		if($title=="")
		{
			$titleError='Title Required';
			$error=true;
		}
		else if($productImageUrl=="")
		{
			$errorMsg='Product Image Url Required';
			$error=true;
		}
		else if(!validExtension($productImageExt))
		{
			echo $errorMsg='Invalid Product Image Extension';
			$error=true;
		}
		else if($productImageWidth <featuredImageWidth() || $productImageHeight <featuredImageHeight())
		{
			$errorMsg='Invalid Product Image Width,Height Minimum Size Is '.featuredImageWidth().' X '.featuredImageHeight();
			$error=true;
		}
		else if($affiliateUrl=="" || !validUrl($affiliateUrl))
		{
			$errorMsg='Empty or invalid affiliate url';
			$error=true;
		}
		else if($originalPrice=="" || !validPrice($originalPrice))
		{
			$errorMsg='Empty or invalid original price';
			$error=true;
		}
		else if($salePrice!="" && !validPrice($salePrice))
		{
			$errorMsg='Invalid sale price';
			$error=true;
		}
		else if($image1Url=="" && $image2Url=="" && $image3Url=="" && $image4Url=="" && $image5Url=="")
		{
			$errorMsg='Paste Atleast One Image Url';
			$error=true;
		}
		else if($image1Url!="" && !validExtension($image1Ext))
		{
			$errorMsg='Invalid Image1 Extension';
			$error=true;
		}
		else if($image2Url!="" && !validExtension($image2Ext))
		{
			$errorMsg='Invalid Image2 Extension';
			$error=true;
		}
		else if($image3Url!="" && !validExtension($image3Ext))
		{
			$errorMsg='Invalid Image3 Extension';
			$error=true;
		}
		else if($image4Url!="" && !validExtension($image4Ext))
		{
			$errorMsg='Invalid Image4 Extension';
			$error=true;
		}
		else if($image5Url!="" && !validExtension($image5Ext))
		{
			$errorMsg='Invalid Image5 Extension';
			$error=true;
		}
		else if($image1Url!="" && ($image1Width < largeThumbnailWidth() || $image1Height < largeThumbnailHeight()))
		{
			$errorMsg='Invalid Image1 Width,Height Minimum Size Is '.largeThumbnailWidth().' X '.largeThumbnailHeight();
			$error=true;
		}
		else if($image2Url!="" && ($image2Width < largeThumbnailWidth() || $image2Height < largeThumbnailHeight()))
		{
			$errorMsg='Invalid Image2 Width,Height Minimum Size Is '.largeThumbnailWidth().' X '.largeThumbnailHeight();
			$error=true;
		}
		else if($image1Ur3!="" && ($image3Width < largeThumbnailWidth() || $image3Height < largeThumbnailHeight()))
		{
			$errorMsg='Invalid Image3 Width,Height Minimum Size Is '.largeThumbnailWidth().' X '.largeThumbnailHeight();
			$error=true;
		}
		else if($image4Url!="" && ($image4Width < largeThumbnailWidth() || $image4Height < largeThumbnailHeight()))
		{
			$errorMsg='Invalid Image4 Width,Height Minimum Size Is '.largeThumbnailWidth().' X '.largeThumbnailHeight();
			$error=true;
		}
		else if($image5Url!="" && ($image5Width < largeThumbnailWidth() || $image5Height < largeThumbnailHeight()))
		{
			$errorMsg='Invalid Image5 Width,Height Minimum Size Is '.largeThumbnailWidth().' X '.largeThumbnailHeight();
			$error=true;
		}
		if(!isValidCategory($categoryPermalink))
		{
			$errorMsg='Invalid Category';
			$error=true;
		}
		if(!$error)
		{
			$productImageName=time().'-img.'.$productImageExt;
			copy($productImageUrl,"../images/productImages/".$productImageName);
			resizeImage($productImageName,"../images/productImages/","../images/productImages/",productImageWidth(),productImageHeight());
			copy($productImageUrl,"../images/productImages/_".$productImageName);
			copy($productImageUrl,"../images/productImages/featuredImagesBackUp/_".$productImageName);
			resizeImage('_'.$productImageName,"../images/productImages/","../images/productImages/",featuredImageWidth(),featuredImageHeight());
			
			//image1 data
			if($image1Url!="")
			{
				$image1Name=time().uniqid().'.'.$image1Ext;
				copy($image1Url,"../images/productImages/thumb3/".$image1Name);
				
				resizeImage($image1Name,"../images/productImages/thumb3/","../images/productImages/thumb1/",smallThumbnailWidth(),smallThumbnailHeight());
				
				resizeImage($image1Name,"../images/productImages/thumb3/","../images/productImages/thumb2/",mediumThumbnailWidth(),mediumThumbnailHeight());
				
				resizeImage($image1Name,"../images/productImages/thumb3/","../images/productImages/thumb3/",largeThumbnailWidth(),largeThumbnailHeight());
			}
			
			//image2 data
			if($image2Url!="")
			{
				$image2Name=time().uniqid().'.'.$image2Ext;
				copy($image2Url,"../images/productImages/thumb3/".$image2Name);
				
				resizeImage($image2Name,"../images/productImages/thumb3/","../images/productImages/thumb1/",smallThumbnailWidth(),smallThumbnailHeight());
				
				resizeImage($image2Name,"../images/productImages/thumb3/","../images/productImages/thumb2/",mediumThumbnailWidth(),mediumThumbnailHeight());
				
				resizeImage($image2Name,"../images/productImages/thumb3/","../images/productImages/thumb3/",largeThumbnailWidth(),largeThumbnailHeight());
			}
			
			//image3 data
			if($image3Url!="")
			{
				$image3Name=time().uniqid().'.'.$image3Ext;
				copy($image3Url,"../images/productImages/thumb3/".$image3Name);
				
				resizeImage($image3Name,"../images/productImages/thumb3/","../images/productImages/thumb1/",smallThumbnailWidth(),smallThumbnailHeight());
				
				resizeImage($image3Name,"../images/productImages/thumb3/","../images/productImages/thumb2/",mediumThumbnailWidth(),mediumThumbnailHeight());
				
				resizeImage($image3Name,"../images/productImages/thumb3/","../images/productImages/thumb3/",largeThumbnailWidth(),largeThumbnailHeight());
			}
			
			//image4 data
			if($image4Url!="")
			{
				$image4Name=time().uniqid().'.'.$image4Ext;
				copy($image4Url,"../images/productImages/thumb3/".$image4Name);
				
				resizeImage($image4Name,"../images/productImages/thumb3/","../images/productImages/thumb1/",smallThumbnailWidth(),smallThumbnailHeight());
				
				resizeImage($image4Name,"../images/productImages/thumb3/","../images/productImages/thumb2/",mediumThumbnailWidth(),mediumThumbnailHeight());
				
				resizeImage($image4Name,"../images/productImages/thumb3/","../images/productImages/thumb3/",largeThumbnailWidth(),largeThumbnailHeight());
			}
			
			//image5 data
			if($image5Url!="")
			{
				$image5Name=time().uniqid().'.'.$image5Ext;
				copy($image5Url,"../images/productImages/thumb3/".$image5Name);
				
				resizeImage($image5Name,"../images/productImages/thumb3/","../images/productImages/thumb1/",smallThumbnailWidth(),smallThumbnailHeight());
				
				resizeImage($image5Name,"../images/productImages/thumb3/","../images/productImages/thumb2/",mediumThumbnailWidth(),mediumThumbnailHeight());
				
				resizeImage($image5Name,"../images/productImages/thumb3/","../images/productImages/thumb3/",largeThumbnailWidth(),largeThumbnailHeight());
			}
			mysql_query("INSERT INTO products(`userType`,`userId`, `permalink`,`image`,`url`,`originalPrice`,`salePrice`,`saleStatus`,`tags`,`status`,`publishedDate`,`expiryDate`,`image1`,`image2`,`image3`,`image4`,`image5`) VALUES ('$userType','$userId','$permalink','$productImageName','$affiliateUrl','$originalPrice','$salePrice','$saleStatus','$tags','$status','$publishedDate','$expiryDate','$image1Name','$image2Name','$image3Name','$image4Name','$image5Name')") or die(mysql_error());
			$pid=mysql_insert_id();
			$cid=catPermalinkToId($categoryPermalink);
			mysql_query("INSERT INTO `procat` (`cid`,`pid`) VALUES('$cid','$pid')");
			foreach(getAllLanguages() as $fetch){
			mysql_query("INSERT INTO `productsLanguage` (`id`,`language`,`title`,`description`,`summary`) VALUES('$pid','".$fetch['languageName']."','$title','$description','$summary')");
			}
		}
		if($error)
		{
			echo '<span class="label label-info"><i class="fa fa-times"></i> '.(strlen($title) > 70 ? substr($title, 0, 70).'...': $title).'</span> <span class="label label-danger"> '.$errorMsg.'</span><br>';
			$errorMsg="";
			$error="";
		}
		else
		{
			if(isParentCategoryById($cid))
			{
			clearRecentCache($cid);
			clearCategorycache($cid);
			}
			else
			{
			$cid=parentIdByChildId($cid);
			clearRecentCache($cid);
			clearCategorycache($cid);
			}
			clearTagsCache($tags);
			echo '<span class="label label-info"><i class="fa fa-check-circle-o"></i> '.(strlen($title) > 70 ? substr($title, 0, 70).'...': $title).'</span> <span class="label label-success">Added successfully</span><br>';
		}
	}
}
?>