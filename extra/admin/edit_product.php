<?php
error_reporting(0);
if(!isset($_SESSION)) 
session_start();
ob_start();
if(!isset($_SESSION['type']) || !isset($_SESSION['admin_eap']) || !isset($_SESSION['id']) || !isset($_SESSION['username']))
{
header("location: index.php");
}	
if(!isset($_SESSION['filenames'])){ 
	$_SESSION['filenames']=array();
}
if(!isset($_SESSION['newfilenames'])){ 
	$_SESSION['newfilenames']=array();
}
if(!isset($_SESSION['removeImgs'])){ 
	$_SESSION['removeImgs']=array();
}
if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf')
$userType=1;
else if($_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87')
$userType=2;
else if($_SESSION['type']=='3d3c3f3cc8a1f15cf46db659eaa2b63d')
$userType=3;
include 'common/header_crop.php';
require_once ('common/libs/phpthumb/ThumbLib.inc.php');
require_once ('common/libs/resize.php');
require_once ('common/libs/watermark.class.php');
if($_SESSION['type']=='3d3c3f3cc8a1f15cf46db659eaa2b63d' && (!isValidPublisherId($_GET['id']) || !productEdit())){
header('Location: products.php');
}
$userId=$_SESSION['id']; 
function isValidPublisherId($id){
$count=mysql_num_rows(mysql_query("SELECT * FROM `products` WHERE `id`='$id' AND `userId`='".$_SESSION['id']."' AND `userType`='3'"));
if($count > 0)
return true;
else
return false;
}
function deletePreviousCategory($id)
{
	$query=mysql_query("SELECT `id` FROM `categories` WHERE `id` IN(SELECT `cid` FROM `procat` WHERE `pid`='$id')");
	while($row=mysql_fetch_array($query))
	{
		if(isParentCategoryById($row['id']))
		{
		clearRecentCache($row['id']);
		clearCategorycache($row['id']);
		}
		else
		{
		$cid=parentIdByChildId($row['id']);
		clearRecentCache($cid);
		clearCategorycache($cid);
		}
	}
	mysql_query("DELETE FROM `procat` WHERE `pid`='$id'")or die(mysql_error());
}
function productUpdate($id,$language,$title,$permalink,$description,$summary,$imagename,$url,$originalPrice,$salePrice,$expiryDate,$saleStatus,$featured,$tags,$status,$publishedDate,$updatedDate,$userId,$img1,$img2,$img3,$img4,$img5)
{
	$cache = phpFastCache();
	
	if($title=="" && $description=="" && $summary==""){
		mysql_query("UPDATE `products` SET `permalink`='$permalink',`image`='$imagename',`url`='$url',`originalPrice`='$originalPrice',`salePrice`='$salePrice',`expiryDate`='$expiryDate',`saleStatus`='$saleStatus',`featured`='$featured',`tags`='$tags',`status`='$status',`publishedDate`='$publishedDate',`updatedDate`='$updatedDate',`approvedBy`='$userId',`image1`='$img1',`image2`='$img2',`image3`='$img3',`image4`='$img4',`image5`='$img5' WHERE `id`='$id' AND `userId`='$userId'");
	} else {
		mysql_query("UPDATE `products` SET `permalink`='$permalink',`image`='$imagename',`url`='$url',`originalPrice`='$originalPrice',`salePrice`='$salePrice',`expiryDate`='$expiryDate',`saleStatus`='$saleStatus',`featured`='$featured',`tags`='$tags',`status`='$status',`publishedDate`='$publishedDate',`updatedDate`='$updatedDate',`approvedBy`='$userId',`image1`='$img1',`image2`='$img2',`image3`='$img3',`image4`='$img4',`image5`='$img5' WHERE `id`='$id' AND `userId`='$userId'");
		mysql_query("UPDATE `productsLanguage` SET `title`='$title',`description`='$description',`summary`='$summary' WHERE `id`='$id' AND `language`='$language'") or die(mysql_error());
	}
	
	unset($_SESSION['image']);		 
	$select=mysql_query("SELECT `id` FROM `products` WHERE `id`='$id'");
	$fetch=mysql_fetch_array($select);
	$id=$fetch['id'];
	foreach( getAllLanguages() as $fetch){
	$language=$fetch['languageName'];
	$cache->delete($id.'_product'.$language);
	$cache->delete($id.'_relatedProduct'.$language);
	}
	foreach ($_POST['category'] as $val) {
	addIntoProcat($id,$val);	
	}
}
$result=mysql_query("SELECT * FROM `currencySettings`");
while($rowp=mysql_fetch_array($result))
{
	$crName =$rowp['crName'];
	$priceDollor=$rowp['priceDollor'];
}
if(isset($_GET["id"]))
	$id = $_GET["id"];
if ($_SESSION['image'] !="" && $_SESSION['image'] !=1 ) {
	$imagename = $_SESSION['image'];
}
else {
	$imagename=getImage($id);
} 
function featuredImageById($featuredImageById){
$row=mysql_fetch_array(mysql_query("SELECT image FROM products WHERE id='$featuredImageById'"));
return $row['image'];
}
$query = mysql_fetch_array(mysql_query("SELECT `title` FROM `productsLanguage` WHERE `id`='$id'"));
?>
<head>
<script src="style/ui/jquery.ui.sortable.js"></script>
<script src="style/ui/jquery.ui.accordion.js"></script>
<link rel="stylesheet" type="text/css" href="style/css/froala_editor.min.css" />
<link rel="stylesheet" type="text/css" href="style/css/froala_style.min.css" />
<!-- CSS -->

<link rel="stylesheet" href="imagePicker/imgPicker/assets/css/imgpicker.css">
<script src="imagePicker/imgPicker/assets/js/jquery.Jcrop.min.js"></script>
<script src="imagePicker/imgPicker/assets/js/jquery.imgpicker.js"></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Edit Product: <?php echo $query['title'];?></title>
	<style>
	ul {
		padding:0px;
		margin: 0px;
	}
	#list li {
		list-style: none;
		font-size: 12px;
	}
	#list li a {
		color: #323A45;
	}
	</style>
	<script type="text/javascript">
		$(function() 
		{
			$("#list ul").sortable
			({
				opacity: 0.8, 
				cursor: 'move',
				placeholder: "sortable-placeholder", 
				forcePlaceholderSize: true,
				update: function() 
				{
					var order = $(this).sortable("serialize") + '&editPageSort=sortMe';
					$.post("processupload.php", order); 															 
				}								  
			});
		});
	</script>
	<script> 
		$(function() {
			var time = function(){return'?'+new Date().getTime()};
			
			// Avatar setup
			$('#avatarModal').imgPicker({
				url: '<?php echo rootPath()?>/admin/imagePicker/imgPicker/server/upload_avatar.php',
				aspectRatio: 1, // Crop aspect ratio
				// Delete callback
				deleteComplete: function() {
					$('#avatar').removeAttr('src');
					this.modal('hide');
				},
				// Crop success callback
				cropSuccess: function(image) {
					console.log(image);
					$('#bothAddEdit').html(' Change Image');
					var imgUrl=image.versions.avatar.url;
					imgUrl=imgUrl.replace("files/", "");
					$('#avatar').attr('src', '<?php echo rootPath()?>/images/productImages/'+imgUrl + time());
					this.modal('hide');
				},
				// Send some custom data to server
				data: {
					key: 'value',
				}
			});

			// Demo only
			$('.navbar-toggle').on('click',function(){$('.navbar-nav').toggleClass('navbar-collapse')});
			$(window).resize(function(e){if($(document).width()>=430)$('.navbar-nav').removeClass('navbar-collapse')});
		}); 
	</script>
<?php
if (isset($_POST['submit'])) {
	if($imagename=="") {
		$error =" Add an Image";
	}
	$title = mysql_real_escape_string(trim($_POST["title"]));
	$title=xssClean(mres(trim(ucwords($title))));
	if($title=="")
		$error6='Enter Product Title';
	$permalink = mres(trim($_POST["permalink"]));
	$oldpermalink = mres(trim($_POST["oldpermalink"]));
	if(trim($permalink)=="" && $title!="")
		$permalink=genPermalink($title);
	else if($permalink!="" && $permalink!=$oldpermalink)
		$permalink = genPermalink($permalink);
	else
		$permalink=$oldpermalink;
	$description = mysql_real_escape_string((strip_tags(trim($_POST["description"]), '<strong><ul>
					<br /><br><table><tbody><th><tr><td><em><p><img><ol><li><h1><h2><h3><h4><h5><a><blockquote><pre><hr>')));
	$description=mres(trim(ucfirst($description)));
	$summary=mysql_real_escape_string(strip_tags($_POST['summary']));
	if($description=="")
		$error7='Enter Product Description';
	$url=mres(trim($_POST['url']));

	if(trim($url!="") && !validUrl(trim($url))) {
		$error1 ="Invalid Domain ";
	} 
	if(strlen($summary) < 30)
		$error8='summary minimum length is 30 chars';
	$originalPrice =xssClean(mres(trim($_POST['originalPrice'])));
	if($originalPrice !="" && !validPrice($originalPrice )) {
		$error3 ="Price Is Not In Valid Format<br />";
	}
	$salePrice = xssClean(mres(trim($_POST['salePrice'])));
	if($salePrice !="" && !validPrice($salePrice )) {
		$error5 ="Price Is Not In Valid Format<br />";
	}
	$expiryDate=xssClean(mres(trim($_POST['expiryDate'])));
	
	$saleStatus=xssClean(mres(trim($_POST['saleStatus'])));
	if($saleStatus=='on' && $salePrice!="")
		$saleStatus=1;
	else
		$saleStatus=0;
	
	$featured=xssClean(mres(trim($_POST['featured'])));
	if($featured=='on')
		$featured=1;
	else
		$featured=0;
	$tags=xssClean(mres(trim($_POST['tags'])));
	$status=xssClean(mres(trim($_POST['status'])));
	if($_POST["status"]==1) {
		$publishedDate=date("Y-m-d");
	}
	else {
		$publishedDate="0000-00-00";
	}
	$updatedDate=date("Y-m-d");
	if($_POST['category']=="") {
		$error2="Select Atleast One Category";
	}
	if($_POST['totalImages']==0){
		 $error4="Select Atleast One Image";
	}
	if($title=="" && $description=="" && $summary==""){
		$error6="";
		$error7="";
		$error8="";
	}
	$language=xssClean(mres(trim($_POST['languageName'])));
	if($error=="" && $error1=="" && $error2=="" && $error3=="" && $error4=="" && $error5=="" && $error6=="" && $error7=="" && $error8=="") 
	{
		foreach($_SESSION['removeImgs'] as $updateImgs) {
			unlink('../images/productImages/thumbnailsBackUp/'.$updateImgs);
			unlink('../images/productImages/thumb1/'.$updateImgs);
			unlink('../images/productImages/thumb2/'.$updateImgs);
			unlink('../images/productImages/thumb3/'.$updateImgs);
		}
		deletePreviousCategory($id);
		$featuredImageById=featuredImageById($id);
		foreach($_SESSION['removeImgs'] as $remove){
			if (($key = array_search($remove, $_SESSION['filenames'])) !== false) {
			unset($_SESSION['filenames'][$key]);
		}
		}
		$_SESSION['filenames']=array_values($_SESSION['filenames']);
		productUpdate($id,$language,$title,$permalink,$description,$summary,$imagename,$url,$originalPrice,$salePrice,$expiryDate,$saleStatus,$featured,$tags,$status,$publishedDate,$updatedDate,$userId,$_SESSION['filenames'][0],$_SESSION['filenames'][1],$_SESSION['filenames'][2],$_SESSION['filenames'][3],$_SESSION['filenames'][4]);
		resizeImg($imagename);
		if($featuredImageById!=$imagename) {
			unlink('../images/productImages/'.str_replace('-img','',$featuredImageById));
			unlink('../images/productImages/featuredImagesBackUp/_'.$featuredImageById);
			unlink('../images/productImages/'.$featuredImageById);
			unlink('../images/productImages/_'.$featuredImageById);
		}
		unset($_SESSION['removeImgs']);
		unset($_SESSION['filenames']);
		unset($_SESSION['newfilenames']);
		unset($_SESSION['image']);
		clearTagsCache($tags);
	}
}
else
{
	unset($_SESSION['removeImgs']);
	unset($_SESSION['filenames']);
	$_SESSION['filenames']=array();
	?>
	<script>
		$( document ).ready(function() 
		{
			$.post("processupload.php",{ featuredImage:'<?php echo $_SESSION['image']?>'},function(ajaxresult){
			});
		});
	</script>
	<?php
	unset($_SESSION['image']);
}
?>
</head>
<body>
<?php include 'common/navbar_admin.php'; ?>
<div id="page-content blocky">
	<div class="container" style="padding-top:50px">
		<div class="side-cont">
			<?php include 'common/sidebar.php'; ?>
		</div>
		<div class="mainy make-post">
			<div class="page-title">
				<h2><i class="fa fa-pencil-square-o color"></i> Edit Product</h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error=="" && $error1=="" && $error2=="" && $error3=="" && $error4=="" && $error6=="" && $error7=="" && $error8==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Product Updated Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">   
				<div class="col-md-9">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"> Edit Product </h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="post">
								<?php
								if(!isset($_SESSION['filenames'])) 
								$_SESSION['filenames']=array();
							
									if(isset($_GET['language']))
									{
										$language=trim($_GET['language']);
										$row = mysql_fetch_array(mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`='$id' AND pl.`id`='$id' AND p.`userId`='$userId' AND pl.`language`='$language'"));
										$title = $row["title"];
										$description = $row["description"];
										$summary= $row["summary"];
									} else 
									{
										$row= mysql_fetch_array(mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`='$id' AND pl.`id`='$id' AND p.`userId`='$userId'"));
									}
									$permalink=$row['permalink'];
									$image=$row['image'];
									$image1=$row['image1'];
									$image2=$row['image2'];
									$image3=$row['image3'];
									$image4=$row['image4'];
									$image5=$row['image5'];
									$url=$row['url'];
									$price_db=$row['originalPrice'];
									$price=$price_db * $priceDollor;
									$originalPrice=$price;
									$price_db=$row['salePrice'];
									$price=$price_db * $priceDollor;
									$salePrice=$price;
									$tags=$row['tags'];
									$status=$row['status'];	
									$saleStatus=$row['saleStatus'];
									$featured=$row['featured'];
									$expiryDate=$row['expiryDate'];
								if($image1!="" && file_exists('../images/productImages/thumb1/'.$image1)) 
								{
									if(!in_array($image1,$_SESSION['filenames']))
										array_push($_SESSION['filenames'],$image1);
								}
								if($image2!="" && file_exists('../images/productImages/thumb1/'.$image2)) 
								{
									if(!in_array($image2,$_SESSION['filenames']))
										array_push($_SESSION['filenames'],$image2);
								}
								if($image3!="" && file_exists('../images/productImages/thumb1/'.$image3)) 
								{
									if(!in_array($image3,$_SESSION['filenames']))
										array_push($_SESSION['filenames'],$image3);
								}
								if($image4!="" && file_exists('../images/productImages/thumb1/'.$image4)) 
								{
									if(!in_array($image4,$_SESSION['filenames']))
										array_push($_SESSION['filenames'],$image4);
								}
								if($image5!="" && file_exists('../images/productImages/thumb1/'.$image5)) 
								{
									if(!in_array($image5,$_SESSION['filenames']))
										array_push($_SESSION['filenames'],$image5);
								}
								?>   
								<div class="form-group">
								<label class="col-lg-3 control-label" id="collg3">Permalink</label><br />
								<div class="col-lg-9" id="collg9">
									<input type="text" class="form-control" name="permalink" placeholder="Optional" value="<?php echo $permalink ; ?>" />
									<input type="hidden" class="form-control" name="oldpermalink" placeholder="Optional" value="<?php echo $permalink ; ?>" />
								</div>
								</div>
								<div class="form-group">
								<label class="col-lg-3 control-label" id="collg3">Language</label>
									<div class="col-lg-9" id="collg9">
									<select onchange="self.location='<?php echo basename($_SERVER['PHP_SELF']); ?>?id=<?php echo $_GET['id']; ?>&language='+this.options[this.selectedIndex].value"  class="form-control" name="languageName">
										<?php 
										if(isset($_GET['language']))
										{
											$language=$_GET['language'];
											$mysql = mysql_query("SELECT * FROM `languages` WHERE languageName!='$language'");
											?>
											<option value="<?php echo $language?>"><?php echo ucfirst($language)?></option>
											<?php
											while($row = mysql_fetch_array($mysql))
											{
												?>
												<option value="<?php echo $row['languageName']; ?>"><?php echo ucfirst($row['languageName']); ?></option>
												<?php
											}
										}
										else
										{
										?><option>Select Language</option><?php
											$mysql = mysql_query("SELECT * FROM `languages`");
											while($row = mysql_fetch_array($mysql))
											{
												?>
												<option value="<?php echo $row['languageName']; ?>"><?php echo ucfirst($row['languageName']); ?></option>
												<?php
											}
										}
										?>
									</select>
									</div>
								</div>
								<?php
								if(isset($_GET['language']))
								{
								?>
								<div class="form-group">
									<label class="col-lg-3 control-label" id="collg3">Product Title</label>
									<div class="col-lg-9" id="collg9">
										<input type="text" class="form-control" name="title" value="<?php echo $title;?>" placeholder="Enter Title" required />
										<?php
										if(isset($_POST['submit']) && $error6!="")
											echo('<span class="label label-danger">' . $error6 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label" id="collg3" >Description</label>
									<div class="col-lg-9" id="collg9">
										<textarea class="form-control" id="description" rows="7" name="description" placeholder="Description Of Product" ><?php echo $description ;?></textarea>
										<?php
										if(isset($_POST['submit']) && $error7!="")
											echo('<span class="label label-danger">' . $error7 . '</span>');
										?>
									</div>
								</div> 
								<div class="form-group">
									<label class="col-lg-3 control-label" id="collg3" >Summary</label><br />
									<div class="col-lg-9" id="collg9">
										<textarea class="form-control" rows="7" id="summary" name="summary" placeholder="Summary Of Product" ><?php echo $summary ; ?></textarea>
										<?php
										if($error8!="") echo('<span class="label label-danger">' . $error8 . '</span>');
										?>
									</div>
								</div>
								<?php } ?>
								<div class="form-group">
									<label class="col-lg-3 control-label" id="collg3">Affiliate URL</label>
									<div class="col-lg-9" id="collg9">
										<input type="text" class="form-control" placeholder="Enter Url" name="url" value="<?php echo $url;?>" />
										<?php
										if(isset($_POST['submit']) && $error1!="")
											echo('<span class="label label-danger">' . $error1 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label" id="collg3"> Original Price (<?php echo $crName ; ?>)</label>
									<div class="col-lg-8" id="collg9">
										<input type="text" class="form-control" name="originalPrice" value="<?php echo $originalPrice; ?>" placeholder="Digits Only" required/>
										<?php
										if(isset($_POST['submit']) && $error3!="")
											echo('<span class="label label-danger">' . $error3 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label" id="collg3"> Sale Price (<?php echo $crName ; ?>)</label>
									<div class="col-lg-8" id="collg9">
										<input type="text" class="form-control" name="salePrice" value="<?php echo $salePrice; ?>" placeholder="Digits Only" required/>
										<?php
										if(isset($_POST['submit']) && $error5!="")
											echo('<span class="label label-danger">' . $error5 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label" id="collg3">Expiry Date</label><br />
									<div class="col-lg-9" id="collg9">
										<input type="date" class="form-control" name="expiryDate" value="<?php echo $expiryDate?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label" id="collg3">Show Sale Price?</label><br />
									<div class="col-lg-9" id="collg9">
										<input type="checkbox" name="saleStatus" <?php echo ($saleStatus ? 'checked' : '')?>/>
										<br />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-3 control-label" id="collg3">Featured?</label><br />
									<div class="col-lg-9" id="collg9">
										<input type="checkbox" name="featured" <?php echo ($featured ? 'checked' : '')?>/>
									</div>
								</div>
								<div class="col-xs-12" style="margin-bottom: 10px;">
									<div id="imageloader" style="display:none;"><img src="<?php echo rootPath()?>/images/fbPreloader.gif" style="height:20px;"></div>
									<?php 
									$count=0;
									$filename1 = '../images/productImages/thumb1/'.$image1;
									$filename2 = '../images/productImages/thumb1/'.$image2;
									$filename3 = '../images/productImages/thumb1/'.$image3;
									$filename4 = '../images/productImages/thumb1/'.$image4;
									$filename5 = '../images/productImages/thumb1/'.$image5;
									if($image1 !="" && file_exists($filename1)) {
										$count=$count + 1;
									}
									if($image2 !="" && file_exists($filename2)) {
										$count=$count + 1;
									}
									if($image3 !="" && file_exists($filename3)) {
										$count=$count + 1;
									}
									if($image4 !="" && file_exists($filename4)) {
										$count=$count + 1;
									}
									if($image5 !="" && file_exists($filename5)) {
										$count=$count + 1;
									}
									if(isset($_POST['submit'])){
									foreach($_SESSION['newfilenames'] as $newfilenames) {
										if(file_exists('../images/productImages/thumb1/'.$newfilenames)) {
										$count=$count + 1;
										}
									}
									}
									?>
								<input type="hidden" id="imgFile" name="totalImages" value="<?php echo $count;?>">
								<a class="btn btn-default" id="choose_file" style="margin-bottom: 15px;">Select Image's</a>
								<input type="file" name="myfile[]" id="myfile" accept="image/jpg,image/png,image/jpeg,image/gif,image/pjpeg" multiple="true"style="display:none" /><small style="position: relative; top: -8px;">(Min 1 , Max 5 Images) (png,jpg,jpeg,gif) (Min Size <?php echo largeThumbnailWidth().'X'.largeThumbnailHeight()?>)</small>
								<div id="list">
								<ul id="result" class="row">
									<?php
									if($image1 !="" && file_exists($filename1)) 
									{
										?>
										<li id="arrayorder_<?php echo $image1 ?>" class="col-sm-2 col-xs-12">
											<div class="thumbnail thumb">
												<a id="<?php echo $image1?>" class="remove-image" onclick="total_img()"><i class="fa fa-remove"></i></a><img src="../images/productImages/thumb1/<?php echo $image1?>" class="img-responsive">
											</div>
										</li>
										<?php
									}
									if($image2 !="" && file_exists($filename2)) 
									{
										?>
										<li id="arrayorder_<?php echo $image2 ?>" class="col-sm-2 col-xs-12">
											<div class="thumbnail thumb">
												<a id="<?php echo $image2?>" class="remove-image" onclick="total_img()"><i class="fa fa-remove"></i></a><img src="../images/productImages/thumb1/<?php echo $image2?>" class="img-responsive">
											</div>
										</li>
										<?php
									}
									if($image3 !="" && file_exists($filename3)) 
									{
										?>
										<li id="arrayorder_<?php echo $image3 ?>" class="col-sm-2 col-xs-12">
											<div class="thumbnail thumb">
												<a id="<?php echo $image3?>" class="remove-image" onclick="total_img()"><i class="fa fa-remove"></i></a><img src="../images/productImages/thumb1/<?php echo $image3?>" class="img-responsive">
											</div>
										</li>
										<?php
									}
									if($image4 !="" && file_exists($filename4)) 
									{
										?>
										<li id="arrayorder_<?php echo $image4 ?>" class="col-sm-2 col-xs-12">
											<div class="thumbnail thumb">
												<a id="<?php echo $image4?>" class="remove-image" onclick="total_img()"><i class="fa fa-remove"></i></a><img src="../images/productImages/thumb1/<?php echo $image4?>" class="img-responsive">
											</div>
										</li>
										<?php
									}
									if($image5 !="" && file_exists($filename5)) 
									{
										?>
										<li id="arrayorder_<?php echo $image5 ?>" class="col-sm-2 col-xs-12">
											<div class="thumbnail thumb">
												<a id="<?php echo $image5?>" class="remove-image" onclick="total_img()"><i class="fa fa-remove"></i></a><img src="../images/productImages/thumb1/<?php echo $image5?>" class="img-responsive">
											</div>
										</li>
										<?php
									}
									if(isset($_POST['submit'])){
									foreach($_SESSION['newfilenames'] as $newfilenames) {
										if(file_exists('../images/productImages/thumb1/'.$newfilenames)) {
										?>
											<li id="arrayorder_<?php echo $newfilenames ?>" class="col-sm-2 col-xs-12">
												<div class="thumbnail thumb">
													<a id="remove" class="remove-image" onclick="total_img()"><i class="fa fa-remove"></i><img src="../images/productImages/thumb1/<?php echo $newfilenames?>" class="img-responsive"></a>
												</div>
											</li>
										<?php
										}
									}
									}
									?>
									</ul>
									</div>
									<?php
									if(isset($_POST['submit']) && $error4!="")
										echo('<span class="label label-danger">' . $error4 . '</span>');
									?>
								</div>
								<div class="form-group">
								<label class="col-lg-3" >Action</label>
								<div class="col-lg-9" style="margin-left: 5%;">	
									<?php if($_SESSION['type']=='3d3c3f3cc8a1f15cf46db659eaa2b63d'){ 
									if(productApproved()){
									?>
									<div class="radio">
									<label>
										<input type="radio" name="status" value="1" <?php echo ($status==1 ? 'checked' : '')?> />Publish
									</label>
									<br />
									<label>
										<input type="radio" name="status" value="0" <?php echo ($status==0 ? 'checked' : '')?> />Pending
									</label>
									</div>	 
									<?php } else { ?>
									<div class="radio">
									<label>
										<input type="radio" name="status" value="0" checked />Pending
									</label>
									</div>
									<?php } 
									}  
									if($_SESSION['type']!='3d3c3f3cc8a1f15cf46db659eaa2b63d'){
									?>
									<div class="radio">
									<label>
										<input type="radio" name="status" value="1" <?php echo ($status==1 ? 'checked' : '')?> />Publish
									</label>
									<br />
									<label>
										<input type="radio" name="status" value="0" <?php echo ($status==0 ? 'checked' : '')?> />Pending
									</label>
									</div>	 
									<?php
									}
									?>
								</div> 
							</div>
								<hr/>
								<div class="form-group">
									<div class="col-lg-10">
										<button type="submit" id="btn_save" class="btn btn-success" name="submit"><i class="fa fa-pencil-square-o"></i> Update</button>
									</div>
								</div>
					</div>
				</div>
					</div>
				<div class="col-md-3" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Categories </h3>
						</div>
						<div class="panel-body" style="height:330px;overflow:auto;">
						<?php
						if(isset($_POST['submit']) && $error2!="")
							echo('<span class="label label-danger">' . $error2 . '</span>');
						$result=mysql_query("SELECT * FROM `categories` WHERE `parentId`=0");
						while($row=mysql_fetch_array($result))
						{
							$sub_qury="select * from categories where parentId='".$row["id"]."'";
							$sub_result=mysql_query($sub_qury);
							$countParentId=mysql_num_rows($sub_result);
							if(in_array($row['id'], productCategoryById(trim($_GET["id"])))) {
							echo('
							<label><input type="checkbox" name="category[]" value="' . $row['id'] . '" checked /> '.$row['english'].'</label><br />');
							} else {
							echo('
							<label><input type="checkbox" name="category[]" value="' . $row['id'] . '" /> '.$row['english'].'</label><br />');
							}
							while($sub_row=mysql_fetch_array($sub_result))
							{
								if(in_array($sub_row['id'], productCategoryById(trim($_GET["id"])))) {
								echo('&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="category[]" value="' . $sub_row['id'] . '" checked/> &raquo;  '.$sub_row['english'].'<br />');
								} else {
								echo('&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="category[]" value="' . $sub_row['id'] . '" /> &raquo;  '.$sub_row['english'].'<br />');	
								}
							}
						}
						?>
					</div>
					</div>
				</div>
				<div class="col-md-3" >
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Featured Image</h3>
						</div>
						<?php
						if(isset($_POST['submit']) && $error!="")
							echo('<span class="label label-danger">' . $error . '</span>');
						?>   
						<div class="panel-body" >
							<div class = "coverbox">
								<?php if(isset($_POST['submit'])) { ?>
								<p><img src="<?php echo '../images/productImages/' . $imagename ; ?>" class="avatar" id="avatar" style="width:100%; height:120px;"></p>
								<?php } else { ?>
								<p><img src="<?php echo '../images/productImages/' . $image; ?>" class="avatar" id="avatar" style="width:100%; height:120px;"></p>	
								<?php } ?>
								<div class="coverhover">
								<button type="button" id="bothAddEdit" class="btn btn-info edit-avatar" data-ip-modal="#avatarModal" title="Edit avatar"> Change Image</button>
								</div>
							</div>
							<!-- Avatar Modal -->
							<div class="ip-modal" id="avatarModal">
								<div class="ip-modal-dialog">
									<div class="ip-modal-content">
										<div class="ip-modal-header">
											<a class="ip-close" title="Close">&times;</a>
											<h4 class="ip-modal-title">Change Image</h4>
										</div>
										<div class="ip-modal-body">
											<button class="btn btn-primary ip-upload">Upload <input type="file" name="file" class="ip-file">
											</button>
											<button type="button" class="btn btn-danger ip-delete" style="display:inline-block">Delete</button>
											<div class="alert ip-alert"></div>
											<div class="ip-info">To crop this image, drag a region below and then click "Save Image"</div>
											<div class="ip-preview"></div>
											<div class="ip-rotate">
												<button type="button" class="btn btn-default ip-rotate-ccw" title="Rotate counter-clockwise"><i class="icon-ccw"></i></button>
												<button type="button" class="btn btn-default ip-rotate-cw" title="Rotate clockwise"><i class="icon-cw"></i></button>
											</div>
											<div class="ip-progress">
												<div class="text">Uploading</div>
												<div class="progress progress-striped active"><div class="progress-bar"></div></div>
											</div>
										</div>
										<div class="ip-modal-footer">
											<div class="ip-actions">
												<button type="button" class="btn btn-success ip-save">Save Image</button>
												<button type="button" class="btn btn-primary ip-capture">Capture</button>
												<button type="button" class="btn btn-default ip-cancel">Cancel</button>
											</div>
											<button type="button" class="btn btn-default ip-close">Close</button>
										</div>
									</div>
								</div>
							</div>
							<!-- end Modal -->
						</div>
					</div>
				</div> 
				<div class="col-md-3">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Tags</h3>
						</div>
						<div class="panel-body" >
							<input id="tags_1" class="tags" type="text"  name="tags" value="<?php echo $tags ;?>" ><br />
						</div>
					</div>
				</div>
			</div>
			</form>
		</div>
		<div class="clearfix"></div>
	</div>
</div>					
<?php include 'common/footer.php'; ?>	
<link rel="stylesheet" type="text/css" href="style/css/TagsInput.css" />
<script src="style/js/TagsInput.js"></script>
<script src="style/js/froala_editor.min.js"></script>
<script>
	$(function(){
		$('#description').editable({inlineMode: false, alwaysBlank: true})
	});
</script>
<script type="text/javascript">

function onAddTag(tag) {
	alert("Added a tag: " + tag);
}
function onRemoveTag(tag) {
	alert("Removed a tag: " + tag);
}

function onChangeTag(input,tag) {
	alert("Changed a tag: " + tag);
}

$(function() 
{
	$('#tags_1').tagsInput({width:'auto'});
});

$(document).ready(function()
{
	$("#choose_file").bind("click", (function () {
		$("#myfile").trigger("click");
	}));
	$('#myfile').change(function() {
		var data = new FormData();
		var files = $('#myfile')[0].files;
		var fileLength=files.length;
		var imgFile=$('#imgFile').val();
		imgFile=Number(imgFile) + fileLength;
		$('#imgFile').val(imgFile);
		if(imgFile >5)
		{
			var count=$('#result').find('img').length;
			alert('select atmost 5 images');
			$('#imgFile').val(count);
			$('#imageloader').hide();
		}
		else
		{
			for (var i = 0; i < files.length; i++) 
			{
				if(files[i].type.match('image/jpeg') || files[i].type.match('image/png') || files[i].type.match('image/gif') || files[i].type.match('image/jpg') || files[i].type.match('image/pjepg')) 
				{
					data.append('myfile',files[i]);
					$('#imageloader').show();
					var requestCount=1;
					$.ajax
					({
						type:'POST',
						url:'processupload.php',
						data:data,
						processData: false,
						contentType: false,
						success: function (response) 
						{
							if(i==requestCount){
								$('#imageloader').hide();
							}
							else{
							$('#imageloader').show();
							requestCount=requestCount+1;
							}
							$('#result').append(response);
						}
					});
				} 
				else 
				{
					$('#imageloader').hide();
					var count=$('#imgFile').val();
					if(count > 1) 
					{
						count=count-1
						$('#imgFile').val(count);
					}
				}
			} 
		}
	});
});
$(".remove-image").click(function () 
{
	$(this).closest('li').remove();
	var fileVal=document.getElementById("myfile");
	var sr=$(this).children('img').attr('src');
	if(typeof(sr) == "undefined"){
		sr=this.id;
	}
	$.post("processupload.php",{ editsr:sr},function(ajaxresult){ });
});
function total_img()
{
	var imgFile=$('#imgFile').val();
	imgFile=Number(imgFile)-1;
	if(imgFile==0) 
	{
		$('#myfile').val('');
		$('#imgFile').val('0');
	} 
	else 
	{
		$('#imgFile').val(imgFile);
	}
}
</script>