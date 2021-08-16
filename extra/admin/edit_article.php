<?php
error_reporting(0);
if(!isset($_SESSION)) 
session_start();
include 'common/header.php';
if(!isset($_SESSION['admin_eap']) && !isset($_SESSION['id']) && !isset($_SESSION['type']) && !isset($_SESSION['username']))
{
	header("location: index.php");
}
if($_SESSION['type']=='3d3c3f3cc8a1f15cf46db659eaa2b63d' && !articleEdit())
{
	header("location: dashboard.php");
}	
else
{ 
	$id=xssClean(mres(trim($_GET['id']))); 
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Edit Article : <?php echo(getTitle()) ?></title>
	<link rel="stylesheet" type="text/css" href="style/css/froala_editor.min.css" />
	<link rel="stylesheet" type="text/css" href="style/css/froala_style.min.css" />
	<?php
	function updateArticle($id,$uid,$cid,$language,$title,$permalink,$description,$summary,$image,$status)
	{
		$title = $title;
		$summary = $summary;
		$description=$description;
		if($title=="" && $description=="" && $summry==""){
			mysql_query("UPDATE `articles` SET `permalink`='$permalink',`uid`='$uid',`cid`='$cid',`image`='$image',`status`='$status' WHERE `id`='$id'") or die(mysql_error());
		} else {
			mysql_query("UPDATE `articles` SET `permalink`='$permalink',`$language`='$title',`uid`='$uid',`cid`='$cid',`image`='$image',`status`='$status' WHERE `id`='$id'") or die(mysql_error());
			mysql_query("UPDATE `articlesLanguage` SET `description`='$description',`summary`='$summary' WHERE `id`='$id' AND `language`='$language'") or die(mysql_error());
		}
	}
	
	if(isset($_POST['submit']))
	{
	
		$title = xssClean(mres(strip_tags(trim($_POST["title"]))));
		$permalink=xssClean(mres(strip_tags($_POST['permalink'])));
		$oldpermalink=xssClean(mres(strip_tags($_POST['oldpermalink'])));
		if(trim($permalink)=="" && $title!="")
			$permalink=genArticlePermalink($title);
		else if($permalink!="" && $permalink!=$oldpermalink)
			$permalink=genArticlePermalink($permalink);
		else
			$permalink=$oldpermalink;
		$summary=mres(trim(strip_tags($_POST['summary'])));
		$cid=xssClean(mres(trim($_POST['category'])));
		
		$uid=xssClean(mres(trim($_SESSION['id'])));
		
		$status=xssClean(mres(trim($_POST['status'])));
		
		if ($_SESSION['articleimage'] !="") {
		$imagename = $_SESSION['articleimage'];
		}
		else {
			$imagename=getArticleImage($id);
		}
		if($imagename=="")
		$error1 =" Add an Image";
		$description=mres(trim($_POST['description']));
		$language=xssClean(mres(trim($_POST['languageName'])));
		if($error1=="")
		{
			if($imagename!=getArticleImage($id)){
				$previousImage=getArticleImage($id);
				unlink('../images/articleImages/'.$previousImage);
				unlink('../images/articleImages/_'.$previousImage);
				$previousImage=str_replace('-img','',$previousImage);
				unlink('../images/articleImages/articleImagesBackUp/'.$previousImage);
				unlink('../images/articleImages/'.$previousImage);
				$imageName=str_replace('-img', '',$imagename);
				copy("../images/articleImages/$imageName","../images/articleImages/articleImagesBackUp/$imageName");
				unlink('../images/articleImages/'.$imageName);
				
			}
			clearArticlesCache();
			clearShowArticlesCache($id);
			clearArticleCategoriesCache(getArticleCategoryId($id));
			updateArticle($id,$uid,$cid,$language,$title,$permalink,$description,$summary,$imagename,$status);
			unset($_SESSION['articleimage']);
		}
	}
	?>
	<script> 
		$(function() {
			var time = function(){return'?'+new Date().getTime()};
			
			// Avatar setup
			$('#avatarModal').imgPicker({
				url: '<?php echo rootPath()?>/admin/imagePicker/article/server/upload_avatar.php',
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
					$('#avatar').attr('src', '<?php echo rootPath()?>/images/articleImages/'+imgUrl + time());
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
	<link rel="stylesheet" href="imagePicker/article/assets/css/imgpicker.css">
	<script src="imagePicker/article/assets/js/jquery.Jcrop.min.js"></script>
	<script src="imagePicker/article/assets/js/jquery.imgpicker.js"></script>
	</head>
	<body>
	<?php include 'common/navbar_admin.php'; ?>
	<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h2><i class="fa fa-pencil color"></i> Edit Article </h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error1==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Article Updated Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Edit Article</h3>
						</div>
						<?php
						if(isset($_GET['language'])){
							$language=xssClean(mres(trim($_GET['language'])));
							$fetch = mysql_fetch_array(mysql_query("SELECT a.*,al.* FROM `articles` a,`articlesLanguage` al WHERE a.`id`='$id' AND a.`id`=al.`id` AND al.`language`='$language'"));
								$title = $fetch[$language];
						} else {
							$fetch = mysql_fetch_array(mysql_query("SELECT * FROM `articles` WHERE `id`='$id'"));
						}
						$cid=$fetch['cid'];
						$permalink=$fetch['permalink'];
						$description=$fetch['description'];
						$summary=$fetch['summary'];
						$status=$fetch['status'];
						$image=$fetch['image'];
						?>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="post">
								<div class="col-md-3" style="margin-left: 155px;">
										<?php
										if(isset($_POST['submit']) && $error1!="")
											echo('<span class="label label-danger">' . $error1 . '</span>');
										?>   
												<?php if(isset($_POST['submit'])) { ?>
												<p style="margin-left: -15px;"><img src="<?php echo '../images/articleImages/' . $imagename ; ?>" class="avatar img-responsive" id="avatar"></p>
												<br>
												<?php } else { ?>
												<p style="margin-left: -15px;"><img src="<?php echo '../images/articleImages/' . $image; ?>" class="avatar img-responsive" id="avatar"></p>	
												<br>
												<?php } ?>
												<div class="form-group">
												<button type="button" id="bothAddEdit" class="btn btn-info edit-avatar" data-ip-modal="#avatarModal" title="Edit avatar"> Change Image</button>
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
								<div class="clearfix"></div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Permalink</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="permalink" placeholder="permalink(optional)" value="<?php echo($permalink) ?>" />
										<input type="hidden" class="form-control" name="oldpermalink" placeholder="permalink(optional)" value="<?php echo($permalink) ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Category</label>
									<div class="col-lg-10">
										<select class="form-control" name="category">
										<?php
										if(isset($_POST['cid']))
										{
										$cid=xssClean(mres(trim($_POST['cid'])));
										echo '<option value="'.$cid.'">'.adminArticleCategoryNameById($cid).'</option>';
										}
										else
										{
										echo '<option value="'.$cid.'">'.adminArticleCategoryNameById($cid).'</option>';
										}
										$query=mysql_query("SELECT * FROM `articleCategories` WHERE cid!='$cid'");
										while($fetch=mysql_fetch_array($query))
										{
												echo '<option value="'.$fetch['cid'].'">'.$fetch['english'].'</option>';
										}
										?>
										</select>
									</div>
								</div>
								<div class="form-group">
								<label class="col-lg-2 control-label">Language</label>
								<div class="col-lg-10">
									<select onchange="self.location='<?php echo basename($_SERVER['PHP_SELF']); ?>?id=<?php echo $_GET['id']; ?>&language='+this.options[this.selectedIndex].value"  class="form-control" name="languageName">
										<?php 
										if(isset($_GET['language']))
										{
											$language=xssClean(mres(trim($_GET['language'])));
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
									<label class="col-lg-2 control-label">Title</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="title" placeholder="title" value="<?php echo($title) ?>" required />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Description</label>
									<div class="col-lg-10">
										<textarea class="form-control" id="description" rows="5" cols="5" name="description" placeholder="add description"><?php echo $description ?></textarea>     
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Summary</label>
									<div class="col-lg-10">
										<textarea class="form-control" id="summary" rows="5" cols="5" name="summary" placeholder="add summary"><?php echo $summary ?></textarea>     
									</div>
								</div>
								<?php } ?>
								<div class="form-group">
									<label class="col-lg-2 control-label">Action</label>
									<?php
									if(($_SESSION['type']=='3d3c3f3cc8a1f15cf46db659eaa2b63d' && articleApproved()) || $_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87' || $_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf'){ ?>
									<div class="col-lg-10">
											<div class="radio">
												<label>
													<input type="radio" name="status" value="1" <?php echo ($status==1 ? 'checked' : '')?>>
													Publish
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="status" value="0" <?php echo ($status==0 ? 'checked' : '')?>>
													Pending
												</label>
											</div>
									</div>
									<?php } else { ?>
										<div class="col-lg-10">
											<div class="radio">
												<label>
													<input type="radio" name="status" value="0" checked>
													Pending
												</label>
											</div>
									</div>
									<?php
									} ?>
								</div>
								<hr />
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button type="submit" name="submit" class="btn btn-success" value="Add"><i class="fa fa-pencil"></i> Update</button>
									</div>
								</div>
							</form>			
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>  
	</div>
<script src="style/js/froala_editor.min.js"></script>
<script>
	$(function(){
		$('#description').editable({inlineMode: false, alwaysBlank: true})
	});
</script>
	<?php include 'common/footer.php';
}
?>					