<?php
error_reporting(0);
if(!isset($_SESSION)) 
session_start();
include 'common/header.php';
if(!isset($_SESSION['admin_eap']) && !isset($_SESSION['id']) && !isset($_SESSION['type']) && !isset($_SESSION['username']))
{
	header("location: index.php");
}	
else
{ 
	?>
	<link rel="stylesheet" type="text/css" href="style/css/froala_editor.min.css" />
	<link rel="stylesheet" type="text/css" href="style/css/froala_style.min.css" />
	<link rel="stylesheet" href="imagePicker/article/assets/css/imgpicker.css">
	<script src="imagePicker/article/assets/js/jquery.Jcrop.min.js"></script>
	<script src="imagePicker/article/assets/js/jquery.imgpicker.js"></script>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Add New Article : <?php echo(getTitle()) ?></title>
	<?php
	$error2 = "";
	function addArticle($uid,$cid,$title,$permalink,$description,$summary,$image,$status)
	{
		$title = $title;
		$permalink = $permalink;
		$description = $description;
		$summary = $summary;
		mysql_query("INSERT INTO articles(`uid`,`cid`,`permalink`,`image`,`status`,`date`) VALUES('$uid','$cid','$permalink','$image','$status',CURDATE())") or die(mysql_error());
		$query=mysql_query("SELECT * FROM `languages`");
		$id=mysql_insert_id();
		while($fetch=mysql_fetch_array($query))
		{
			$language=$fetch['languageName'];
			mysql_query("UPDATE articles SET `$language`='$title' WHERE id='$id'");
			mysql_query("INSERT INTO `articlesLanguage` (`id`,`language`,`description`,`summary`) VALUES('$id','$language','$description','$summary')") or die (mysql_error());
		}
	}
	
	if(isset($_POST['submit']))
	{
		$title = xssClean(mres(trim(strip_tags($_POST["title"]))));
		
		if($title=="")
		{
			$error1 = "Enter Article Title!";
		}
		$description=mres(trim($_POST['description']));
		if($description=="")
		{
			$error2 = "Enter Article Description!";
		}
		$summary=mres(trim(strip_tags($_POST['summary'])));
		if($summary=="")
		{
			$error3 = "Enter Article Summary!";
		}
		$cid=xssClean(mres(trim($_POST['cid'])));
		
		$uid=$_SESSION['id'];
		
		$status=xssClean(mres(trim($_POST['status'])));
		$permalink=$_POST['permalink'];
		if(trim($permalink)=="")
			$permalink=genArticlePermalink($title);
		else
			$permalink=genArticlePermalink($permalink);
		
		$image=$_SESSION['articleimage'];
		
		if($image=="")
			$error4='Add Image';
		if($error1=="" && $error2=="" && $error3=="" && $error4=="")
		{
			$cache = phpFastCache();
			addArticle($uid,$cid,$title,$permalink,$description,$summary,$image,$status);
			clearArticlesCache();
			clearArticleCategoriesCache($cid);
			
			clearArticlesCache();
			clearArticleCategoriesCache($cid);
			$imageName=str_replace('-img', '',$_SESSION['articleimage']);
			copy("../images/articleImages/$imageName","../images/articleImages/articleImagesBackUp/$imageName");
			$filename=str_replace('-img','',$imageName);
			unlink("../images/articleImages/" . $filename);
			unset($_SESSION['articleimage']);
			?>
			<script>
			$(function() {
				$("input[name='title']").val("");
				$("input[name='permalink']").val("");
				document.getElementById('description').value = "";
				document.getElementById('summary').value = "";
			});
			</script>
			<?php
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
	<script>
	$(function(){
		$('#description').editable({inlineMode: false, alwaysBlank: true})
	});
</script>
	</head>
	<body>
	<?php include 'common/navbar_admin.php'; ?>
	<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h2><i class="fa fa-plus-square-o color"></i> Add Article </h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error1=="" && $error2=="" && $error3=="" && $error4==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Article Added Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Add New Article</h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" action="./add_article.php" method="post">
							<div class="col-md-3 col-md-offset-2" style="margin-left: 139px; margin-bottom: 15px;">
									<?php
									if(isset($_POST['submit']) && $error4!="")
										echo('<span class="label label-danger">' . $error4 . '</span>');
									?>
										<div id="panel-body-id">
											
											<div class="container">
															 	
													<?php if(isset($_POST['submit']) && $_SESSION['articleimage']!="") 
													{
														?>
														<div class="form-group">
														<button type="button" class="btn btn-info edit-avatar" data-ip-modal="#avatarModal" id="bothAddEdit"> Remove Image </button>
														</div>
														<?php 
													} 
													else 
													{ 
														?>
														<div class="form-group">
														<button id="bothAddEdit" type="button" class="btn btn-info edit-avatar" data-ip-modal="#avatarModal"> Add Image </button>
														</div>
														<?php 
													} 
													?>
												<?php
												if(isset($_POST['submit']) && $_SESSION['articleimage']!="") 
													{
														?>
														<p style="margin-left: -15px;"><img src="<?php echo rootPath()?>/images/articleImages/<?php echo $_SESSION['articleimage']?>" class="avatar img-responsive" id="avatar" style="width:100%; height:120px;"></p>
														<?php 
													} 
													else 
													{ 
														?>
														<p style="margin-left: -15px;"><img src="" class="avatar img-responsive" id="avatar"></p>
														<?php 
													} 
												?>
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
															<div class="btn btn-primary ip-upload">Upload <input type="file" name="file" class="ip-file"></div>
															<button type="button" id="<?php echo $_SESSION['articleimage']?>" class="btn btn-danger ip-delete" <?php echo ((isset($_POST['submit']) && $_SESSION['articleimage']!="" &&  file_exists('../images/articleImages/'.$_SESSION['articleimage']))? 'style="display: inline-block;"':'')?>>Delete</button>
															
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
								<br>
								<div class="clearfix"></div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Category</label>
									<div class="col-lg-10">
										<select class="form-control" name="cid">
										<?php
											$query=mysql_query("SELECT * FROM `articleCategories`");
											while($fetch=mysql_fetch_array($query)){
												echo '<option value="'.$fetch['cid'].'">'.$fetch['english'].'</option>';
											}
										?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Title</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="title" placeholder="title" value="<?php echo($title) ?>" required />
										<?php
										if(isset($_POST['submit']) && $error1!="")
											echo('<span class="label label-danger">' . $error1 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Permalink</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="permalink" placeholder="permalink(optional)" value="<?php echo($permalink) ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Description</label>
									<div class="col-lg-10">
										<textarea class="form-control" id="description" rows="5" cols="5" name="description" placeholder="add description"><?php echo $description ?></textarea>  
										<?php
										if(isset($_POST['submit']) && $error2!="")
											echo('<span class="label label-danger">' . $error2 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Summary</label>
									<div class="col-lg-10">
										<textarea class="form-control" id="summary" rows="5" cols="5" name="summary" placeholder="add summary"><?php echo $summary ?></textarea>     
										<?php
										if(isset($_POST['submit']) && $error3!="")
											echo('<span class="label label-danger">' . $error3 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Action</label>
									<div class="col-lg-10">
											<div class="radio">
												<label>
													<input type="radio" name="status" value="1" checked>
													Publish
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="status" value="0">
													Pending
												</label>
											</div>
									</div>
								</div>
								<hr />
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button type="submit" name="submit" class="btn btn-success" value="Add"><i class="fa fa-plus-square-o"></i> Add</button>
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
$(".ip-delete").click(function () 
{
	var id=this.id;
	var newimg='_'+id;
	$.post('<?php echo rootPath()?>/admin/imagePicker/article/server/upload_avatar.php',{ action: 'delete', data: newimg, file: id},function(ajaxresult){});
	$.post('<?php echo rootPath()?>/admin/imagePicker/article/server/upload_avatar.php',{ action: 'delete', data: newimg, file: newimg},function(ajaxresult){});
});
</script>
	<?php include 'common/footer.php';
}
?>					