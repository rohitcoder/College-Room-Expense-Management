<?php
error_reporting(0);
if(!isset($_SESSION))
session_start();
if(!isset($_SESSION['addfilenames']))
{ 
	$_SESSION['addfilenames']=array();
}
if(!isset($_SESSION['type']) || !isset($_SESSION['admin_eap']) || !isset($_SESSION['id']) || !isset($_SESSION['username']))
{
header("location: index.php");
}
if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf')
$userType=1;
else if($_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87')
$userType=2;
else if($_SESSION['type']=='3d3c3f3cc8a1f15cf46db659eaa2b63d')
$userType=3;
$userId=$_SESSION['id'];
include 'common/header_crop.php';
include 'common/navbar_admin.php';
require_once ('common/libs/phpthumb/ThumbLib.inc.php');
require_once ('common/libs/resize.php');
require_once ('common/libs/watermark.class.php'); 
function addNewProduct($userType,$userId,$permalink,$title,$description,$summary,$imagename,$url,$originalPrice,$salePrice,$expiryDate,$saleStatus,$featured,$tags,$status,$submitDate,$publishedDate,$updatedDate,$img1,$img2,$img3,$img4,$img5)	
{
	$cache = phpFastCache();
	mysql_query("INSERT INTO products(`userType`,`userId`,`permalink`,`image`,`url`,`originalPrice`,`salePrice`,`expiryDate`,`saleStatus`,`featured`,`tags`,`status`,`submitDate`,`publishedDate`,`updatedDate`,`image1`,`image2`,`image3`,`image4`,`image5`) values('$userType','$userId','$permalink','$imagename','$url','$originalPrice','$salePrice','$expiryDate','$saleStatus','$featured','$tags','$status','$submitDate','$publishedDate','$updatedDate','$img1','$img2','$img3','$img4','$img5')") or die(mysql_error()); 
	$query=mysql_query("SELECT * FROM `languages`");
	$id=mysql_insert_id();
	while($fetch=mysql_fetch_array($query))
		{
			$language=$fetch['languageName'];
			mysql_query("INSERT INTO `productsLanguage` (`id`,`language`,`title`,`description`,`summary`) VALUES('$id','$language','$title','$description','$summary')") or die (mysql_error());
		}
	$select=mysql_query("SELECT `id` FROM `products` WHERE `permalink`='".$permalink."'");
	$fetch=mysql_fetch_array($select);
	$fetch_id=$fetch['id'];
	foreach( getAllLanguages() as $fetch){
	$language=$fetch['languageName'];
	$cache->delete($fetch_id.'_product'.$language);
	$cache->delete($fetch_id.'_relatedProduct'.$language);
	}
	foreach ($_POST['category'] as $val)
	{
		addIntoProcat($fetch_id,$val);
		if(isParentCategoryById($val))
		{
		clearRecentCache($val);
		clearCategorycache($val);
		}
		else
		{
		$val=parentIdByChildId($val);
		clearRecentCache($val);
		clearCategorycache($val);
		}
	}	
}
$result=mysql_query("SELECT * FROM `currencySettings`");
while($rowp=mysql_fetch_array($result))
{
	$crName =$rowp['crName'];
	$priceDollor=$rowp['priceDollor'];
}	
if(isset($_POST['title']))
	$post=1;
else
	$post=0;
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
<title>Add New Product : <?php echo(getTitle()) ?></title>
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
					var order = $(this).sortable("serialize") + '&addPageSort=sortMe';
					$.post("processupload.php", order); 															 
				}								  
			});
		});
	</script>  
<script type="text/javascript">
	$( document ).ready(function() 
	{
		$( "#addproduct" ).hide();
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
</head>
<?php
$error="";
$error2="";
$error3="";
$error4="";
$error5="";
if(isset($_POST['submit']))
{
	$title =xssClean(mres(strip_tags(trim($_POST["title"]))));
	$title=ucwords($title);
	if($title=="")
		$error9='Enter Product Title';
	$permalink = xssClean(mres(trim($_POST["permalink"])));
	if($permalink=="")
		$permalink=genPermalink($title);
	else
		$permalink = genPermalink($permalink);
	if($_POST['category']=="")
 		$error4="Select Atleast One Category";
 	$description = mysql_real_escape_string((strip_tags(trim($_POST["description"]), '<strong><ul>
					<br /><br><table><tbody><th><tr><td><em><img><p><ol><li><h1><h2><h3><h4><h5><a><blockquote><pre><hr>')));
	$description=ucfirst($description);
	$summary=mres(trim(strip_tags($_POST['summary'])));
	if(strlen($summary) < 30)
		$error10='summary minimum length is 30 chars';
	if($description=="")
		$error8="Enter Product Description";
	$originalPrice = xssClean(mres(trim($_POST["originalPrice"])));
	if($originalPrice!="" && !validPrice($originalPrice ))
		$error6 =" Price Is Not In Valid Format";
	$salePrice = xssClean(mres(trim($_POST["salePrice"])));
	if($salePrice!="" && !validPrice($salePrice ))
		$error7 =" Price Is Not In Valid Format";
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
	
	$status=$_POST["status"];
	$updatedDate=date("Y-m-d");
	if($_POST["status"]==1)
	   $publishedDate=date("Y-m-d");
	else
	   $publishedDate="0000-00-00";
	$submitDate=date("Y-m-d");
	$url = mres(trim($_POST["url"]));
    if(trim($_POST["url"]!="") && !validUrl($url))
		$error2='Invalid Domain';
	$tags= $_POST["tags"];  
	$imagename = xssClean(mres(trim($_SESSION['image'])));
	if($imagename=="")
		$error=" Add an Image";
	$totalImages=xssClean(mres(trim($_POST['totalImages'])));
	if($totalImages==0){
		 $error5="Select Atleast One Image";
	}
	if(isset($_POST['submit']) && $error=="" && $error2=="" && $error3=="" && $error4=="" && $error5=="" && $error6=="" && $error7=="" && $error8=="" && $error9=="" && $error10=="") 
	{
		$_SESSION['addfilenames']=array_values($_SESSION['addfilenames']);
		addNewProduct($userType,$userId,$permalink,$title,$description,$summary,$imagename,$url,$originalPrice,$salePrice,$expiryDate,$saleStatus,$featured,$tags,$status,$submitDate,$publishedDate,$updatedDate,$_SESSION['addfilenames'][0],$_SESSION['addfilenames'][1],$_SESSION['addfilenames'][2],$_SESSION['addfilenames'][3],$_SESSION['addfilenames'][4]);
		resizeImg($imagename);
		unset($_SESSION['addfilenames']);
		unset($_SESSION['newfilenames']);
		unset($_SESSION['filenames']);
		unset($_SESSION['image']);
		clearTagsCache($tags);
		?>
		<script>
		$(function() {
			$("input[name='title']").val("");
			$("input[name='permalink']").val("");
			$("input[name='originalPrice']").val("");
			$("input[name='salePrice']").val("");
			$("input[name='url']").val("");
			document.getElementById('description').value = "";
			document.getElementById('summary').value = "";
			document.getElementById('tags_1').value = "";
			$('input:checkbox').removeAttr('checked');
		});
		</script>
<?php
	}
} 
else 
{
	?>
	<script>
		$( document ).ready(function() 
		{
			$.post("processupload.php",{ unlinkExtraInAddPage:'unlinkextra'},function(ajaxresult){});
		});
	</script>
	<?php 
} 
?>
<body>
<div id="page-content blocky">
<div class="container" style="padding-top:50px">
	<div class="side-cont">
		<?php include 'common/sidebar.php'; ?>
	</div>
	<div class="mainy make-post">
		<div class="page-title">
			<h2><i class="fa fa-plus-square-o color"></i> Add Product</h2> 
			<hr />
			<?php if(isset($_POST['submit']) && $error=="" && $error2=="" && $error3=="" && $error4=="" && $error5=="" && $error6=="" && $error7=="" && $error8=="" && $error9=="" && $error10==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Product Added Successfully!
				</div>
			<?php } ?>
		</div>
		<div class="row">
			<div class="col-md-9">
				<div class="panel panel-default" id="paneldefbody">
					<div class="panel-heading">
						<h3 class="panel-title">Add New Product</h3>
					</div>
					<div class="panel-body">
			<form class="form-horizontal"  role="form" action="add_product.php" method="post">	 						
						<?php
						if (isset($_POST['submit'])) 
						{
							$imagename = $_SESSION['image']; 
							$title = strip_tags(trim($_POST["title"]));
							$permalink = $_POST["permalink"];
							if(trim($permalink)=="")
								$permalink=genCategoryPermalink($title);
							else
								$permalink = genCategoryPermalink($permalink);
							$description = trim($_POST["description"]);
							$priceByUser = $_POST["price"];		
							$status=$_POST["status"];
							$category=$_POST['category'];
							$url = $_POST["url"];   
							$tags= $_POST["tags"];  
							$saleStatus=$_POST['saleStatus'];
						}
						?>
						<div class="form-group">
							<label class="col-lg-3 control-label" id="collg3" >Product Title</label><br />
							<div class="col-lg-9"  id="collg9">
								<input type="hidden" name="hidden" value="hidden" />
								<input type="text" class="form-control" name="title" placeholder="Enter Title" value="<?php echo $title ; ?>" required />
								<?php
								if($error9!="") echo('<span class="label label-danger">' . $error9 . '</span>');
								?>
							</div>
						</div>						  
						<div class="form-group">
							<label class="col-lg-3 control-label" id="collg3">Permalink</label><br />
							<div class="col-lg-9" id="collg9">
								<input type="text" class="form-control" name="permalink" placeholder="Optional" value="<?php echo $permalink ; ?>" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label" id="collg3" >Description</label><br />
							<div class="col-lg-9" id="collg9">
								<textarea class="form-control" rows="7" id="description" name="description" placeholder="Description Of Product" ><?php echo $description ; ?></textarea>
								<?php
								if($error8!="") echo('<span class="label label-danger">' . $error8 . '</span>');
								?>
							</div>
						</div> 
						<div class="form-group">
							<label class="col-lg-3 control-label" id="collg3" >Summary</label><br />
							<div class="col-lg-9" id="collg9">
								<textarea class="form-control" rows="7" id="summary" name="summary" placeholder="Summary Of Product" ><?php echo $summary ; ?></textarea>
								<?php
								if($error10!="") echo('<span class="label label-danger">' . $error10 . '</span>');
								?>
							</div>
						</div> 
						<div class="form-group">
							<label class="col-lg-3 control-label" id="collg3">Affiliate URL</label><br />
							<div class="col-lg-9" id="collg9">
								<input type="text" class="form-control" name="url" placeholder="Enter Url" value="<?php echo $url ; ?>"  required />
								<?php
								if($error2!="") echo('<span class="label label-danger">' . $error2 . '</span>');
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label" id="collg3"> Original Price (<?php echo $crName ; ?>)</label><br />
							<div class="col-lg-9" id="collg9">
								<input type="text" class="form-control" name="originalPrice" placeholder="Digits Only" value="<?php echo $originalPrice ; ?>"  required/>
								<?php
								if(isset($_POST['submit']) && $error6!="")
								echo('<span class="label label-danger">' . $error6 . '</span>');
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label" id="collg3">Sale Price (<?php echo $crName ; ?>)</label><br />
							<div class="col-lg-9" id="collg9">
								<input type="text" class="form-control" name="salePrice" placeholder="Digits Only" value="<?php echo $salePrice ; ?>"/>
								<?php
								if(isset($_POST['submit']) && $error7!="")
								echo('<span class="label label-danger">' . $error7 . '</span>');
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label" id="collg3">Expiry Date</label><br />
							<div class="col-lg-9" id="collg9">
								<input type="date" class="form-control" placeholder="Y-M-D" name="expiryDate"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-3 control-label" id="collg3">Show Sale Price?</label><br />
							<div class="col-lg-9" id="collg9">
								<input type="checkbox" name="saleStatus" <?php echo ($saleStatus ? 'checked' : '')?>/>
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
								<?php if(isset($_POST['submit'])) 
								{
									$count=0;
									$i=1;
									if($_SESSION['addfilenames'][0] !="" && file_exists('../images/productImages/thumb1/'.$_SESSION['addfilenames'][0])) 
									{
										$count=$count + 1;
									}
									if($_SESSION['addfilenames'][1] !="" && file_exists('../images/productImages/thumb1/'.$_SESSION['addfilenames'][1])) 
									{
										$count=$count + 1;
									}
									if($_SESSION['addfilenames'][2] !="" && file_exists('../images/productImages/thumb1/'.$_SESSION['addfilenames'][2])) 
									{
										$count=$count + 1;
									}
									if($_SESSION['addfilenames'][3] !="" && file_exists('../images/productImages/thumb1/'.$_SESSION['addfilenames'][3]))
									{
										$count=$count + 1;
									}
									if($_SESSION['addfilenames'][4] !="" && file_exists('../images/productImages/thumb1/'.$_SESSION['addfilenames'][4])) 
									{
										$count=$count + 1;
									}
									?>
									<input type="hidden" id="imgFile" name="totalImages" value="<?php echo $count;?>">
									<a class="btn btn-default" id="choose_file">Select Image's</a>
									<input type="file" accept="image/jpg,image/png,image/jpeg,image/gif,image/pjpeg" name="myfile[]" id="myfile" multiple="true" style="display:none" /><small>(Min 1 , Max 5 Images)  (png,jpg,jpeg,gif) (Min Size <?php echo largeThumbnailWidth().'X'.largeThumbnailHeight()?>)</small>
									<div id="list">
										<ul id="result" class="row">
											<?php
											if($_SESSION['addfilenames'][0] !="" && file_exists('../images/productImages/thumb1/'.$_SESSION['addfilenames'][0])) 
											{
												?>
												<li id="arrayorder_<?php echo $_SESSION['addfilenames'][0] ?>" class="col-sm-2 col-xs-12">
													<div class="thumbnail thumb">
														<a id="<?php echo $_SESSION['addfilenames'][0]?>" class="remove-image" onclick="total_img()">
															<i class="fa fa-remove"></i></a>
															<img src="../images/productImages/thumb1/<?php echo $_SESSION['addfilenames'][0]?>"  class="img-responsive">
													</div>
												</li>
												<?php
											}
											if($_SESSION['addfilenames'][1] !="" && file_exists('../images/productImages/thumb1/'.$_SESSION['addfilenames'][1])) 
											{
												?>
												<li id="arrayorder_<?php echo $_SESSION['addfilenames'][1] ?>" class="col-sm-2 col-xs-12">
													<div class="thumbnail thumb">
														<a id="<?php echo $_SESSION['addfilenames'][1]?>" class="remove-image" onclick="total_img()">
															<i class="fa fa-remove"></i></a>
															<img src="../images/productImages/thumb1/<?php echo $_SESSION['addfilenames'][1]?>"  class="img-responsive">
													</div>
												</li>
												<?php
											}
											if($_SESSION['addfilenames'][2] !="" && file_exists('../images/productImages/thumb1/'.$_SESSION['addfilenames'][2])) 
											{
												?>
												<li id="arrayorder_<?php echo $_SESSION['addfilenames'][2] ?>" class="col-sm-2 col-xs-12">
													<div class="thumbnail thumb">
														<a id="<?php echo $_SESSION['addfilenames'][2]?>" class="remove-image" onclick="total_img()">
															<i class="fa fa-remove"></i></a>
															<img src="../images/productImages/thumb1/<?php echo $_SESSION['addfilenames'][2]?>"  class="img-responsive">
													</div>
												</li>
												<?php
											}
											if($_SESSION['addfilenames'][3] !="" && file_exists('../images/productImages/thumb1/'.$_SESSION['addfilenames'][3])) 
											{
												?>
												<li id="arrayorder_<?php echo $_SESSION['addfilenames'][3] ?>" class="col-sm-2 col-xs-12">
													<div class="thumbnail thumb">
														<a id="<?php echo $_SESSION['addfilenames'][3]?>" class="remove-image" onclick="total_img()">
															<i class="fa fa-remove"></i></a>
															<img src="../images/productImages/thumb1/<?php echo $_SESSION['addfilenames'][3]?>"  class="img-responsive">
													</div>
												</li>
												<?php
											}
											if($_SESSION['addfilenames'][4] !="" && file_exists('../images/productImages/thumb1/'.$_SESSION['addfilenames'][4])) 
											{
												?>
												<li id="arrayorder_<?php echo $_SESSION['addfilenames'][4] ?>" class="col-sm-2 col-xs-12">
													<div class="thumbnail thumb">
														<a id="<?php echo $_SESSION['addfilenames'][4]?>" class="remove-image" onclick="total_img()">
															<i class="fa fa-remove"></i></a>
															<img src="../images/productImages/thumb1/<?php echo $_SESSION['addfilenames'][4]?>" class="img-responsive">
													</div>
												</li>
												<?php
											} 
											?>
										</ul>
									</div>
									<?php 
									if(isset($_POST['submit']) && $error5!="")
										echo('<span class="label label-danger">' . $error5 . '</span>');
								} 
								else 
								{
									?>
									<input type="hidden" id="imgFile" name="totalImages" value="0">
									<a class="btn btn-default" id="choose_file">Select Image's</a>
									<input type="file" name="myfile[]" id="myfile" accept="image/jpg,image/png,image/jpeg,image/gif,image/pjpeg" multiple="true" style="display:none" /><small>(Min 1 , Max 5 Images) (png,jpg,jpeg,gif) (Min Size <?php echo largeThumbnailWidth().'X'.largeThumbnailHeight()?>)</small>
									<div id="list">
										<ul id="result" class="row">
										</ul>
									</div>
									<?php 
									if(isset($_POST['submit']) && $error5!="")
									echo('<span class="label label-danger">' . $error5 . '</span>');
								} 
								?>
							</div>
							<div class="form-group">
								<label class="col-lg-3" >Action</label>
								<div class="col-lg-9" style="margin-left: 5%;">	
									<?php if($_SESSION['type']=='3d3c3f3cc8a1f15cf46db659eaa2b63d'){ 
									if(productApproved()){
									if(isset($_POST['submit'])){
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
									else
									{ ?>
									<div class="radio">
									<label>
										<input type="radio" name="status" value="1" checked />Publish
									</label>
									<br />
									<label>
										<input type="radio" name="status" value="0"/>Pending
									</label>
									</div>	
									<?php 
									} 
									} else { ?>
									<div class="radio">
									<label>
										<input type="radio" name="status" value="0" checked />Pending
									</label>
									</div>
									<?php } 
									}  
									if($_SESSION['type']!='3d3c3f3cc8a1f15cf46db659eaa2b63d'){
									if(isset($_POST['submit'])){
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
									else
									{ ?>
									<div class="radio">
									<label>
										<input type="radio" name="status" value="1" checked />Publish
									</label>
									<br />
									<label>
										<input type="radio" name="status" value="0"/>Pending
									</label>
									</div>	
									<?php 
									}
									}
									?>
								</div> 
							</div>
					<hr/>
					<div class="form-group">
						<div class="pull-left">
							<button type="submit" id="btn_save" class="btn btn-success" name="submit"><i class="fa fa-plus-square-o"></i> Add</button>
						</div>
					</div>
					</div> 
				</div> 
			</div>
			<div class="col-md-3" >
				<div class="panel panel-default" >
					<div class="panel-heading">
						<h3 class="panel-title">Categories </h3>
					</div>
					<div class="panel-body" style="height:330px;overflow:auto;">
						<?php
						if(isset($_POST['submit']) && $error4!="")
							echo('<span class="label label-danger">' . $error4 . '</span>');
						$result=mysql_query("SELECT * FROM `categories` WHERE `parentId`=0");
						while($row=mysql_fetch_array($result))
						{
							$sub_qury="select * from categories where parentId='".$row["id"]."'";
							$sub_result=mysql_query($sub_qury);
							$countParentId=mysql_num_rows($sub_result);
							echo('
							<label><input type="checkbox" name="category[]" value="' . $row['id'] . '" /> '.$row['english'].'</label><br />');
							while($sub_row=mysql_fetch_array($sub_result))
							{
								echo('&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" name="category[]" value="' . $sub_row['id'] . '" /> &raquo;  '.$sub_row['english'].'<br />');
							}
						}
						?> 
					</div>
				</div>
			</div> 
			<div class="col-md-3" >
			<?php
			if(isset($_POST['submit']) && $error!="")
				echo('<span class="label label-danger">' . $error . '</span>');
			?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Featured Image</h3>
					</div>
					<div class="panel-body" id="panel-body-id">
						<?php
						$newimg = "";
						if($imagename == "")
							$newimg="";
						else
							$newimg = 'src= productimage/' . $imagename;
						?>
						
						<div class="container"><div class="box">
						
						
						<div class = "coverbox">						 	
							<div class="coverhover pressBtn" > 
								<?php if(isset($_POST['title']) && $_SESSION['image']!="") 
								{
									?>
									<button type="button" class="btn btn-info edit-avatar" data-ip-modal="#avatarModal" id="bothAddEdit"> Remove Image </button>
									<?php 
								} 
								else 
								{ 
									?>
									<button id="bothAddEdit" type="button" class="btn btn-info edit-avatar" data-ip-modal="#avatarModal"> Add Image </button>
									<?php 
								} 
								?>
							</div>
							<?php
							if(isset($_POST['title']) && $_SESSION['image']!="") 
								{
									?>
									<p><img src="<?php echo rootPath()?>/images/productImages/<?php echo $_SESSION['image']?>" class="avatar" id="avatar" style="width:100%; height:120px;"></p>
									<?php 
								} 
								else 
								{ 
									?>
									<p><img src="" class="avatar" id="avatar" style="width:100%; height:120px;"></p>
									<?php 
								} 
							?>
						</div>
						</div></div>
						<!-- Avatar Modal -->
						<div class="ip-modal" id="avatarModal">
							<div class="ip-modal-dialog">
								<div class="ip-modal-content">
									<div class="ip-modal-header">
										<a class="ip-close" title="Close">&times;</a>
										<h4 class="ip-modal-title">Change Image</h4>
									</div>
									<div class="ip-modal-body">
										<div class="btn btn-primary ip-upload">Upload <input type="file" name="file" class="ip-file"></div>
										<button type="button" id="<?php echo $_SESSION['image']?>" class="btn btn-danger ip-delete" <?php echo ((isset($_POST['submit']) && $_SESSION['image']!="" &&  file_exists('../images/productImages/'.$_SESSION['image']))? 'style="display: inline-block;"':'')?>>Delete</button>
										
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
					</div>
				</div>
			</div>    
			<div class="col-md-3">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h3 class="panel-title">Tags</h3>
					</div>
					<div class="panel-body" >
						<input id="tags_1" type="text" class="tags" value="<?php echo $tags ; ?>"  name="tags" />
					</div>
				</div>
			</div>				
			</form>
		</div> <!-- end row -->
		<div class="clearfix"></div>
	</div>
</div>
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
$("#choose_file").bind("click", (function () 
{
	$("#myfile").trigger("click");
}));
function onAddTag(tag) 
{
	alert("Added a tag: " + tag);
}
function onRemoveTag(tag) 
{
	alert("Removed a tag: " + tag);
}
function onChangeTag(input,tag) 
{
	alert("Changed a tag: " + tag);
}
$(function() {
	$('#tags_1').tagsInput({width:'auto'});
});
$(document).ready(function()
{
	$('#myfile').change(function() 
	{
		$('#imageloader').show();
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
					$('#imageloader').show();
					var requestCount=1;
					data.append('myfile',files[i]);
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
	$.post("processupload.php",{ sr:sr},function(ajaxresult){});
});
$(".ip-delete").click(function () 
{
	var id=this.id;
	var newimg='_'+id;
	$.post('<?php echo rootPath()?>/admin/imagePicker/imgPicker/server/upload_avatar.php',{ action: 'delete', data: newimg, file: id},function(ajaxresult){});
	$.post('<?php echo rootPath()?>/admin/imagePicker/imgPicker/server/upload_avatar.php',{ action: 'delete', data: newimg, file: newimg},function(ajaxresult){});
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