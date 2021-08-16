<?php
error_reporting(0);
if(!isset($_SESSION)) 
session_start();
if(!isset($_SESSION['admin_eap']) && !isset($_SESSION['id']) && !isset($_SESSION['type']) && !isset($_SESSION['username']))
{
header("location: index.php");
}
if($_SESSION['type']!='a3852d52cc0ac36ea335d8cdd952d4cf')
{
header("location: dashboard.php");
}	
else
{			
	include 'common/header.php';
	$error = "";
	$error1=="" ;
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
	{
		$websiteName = xssClean(mres(strip_tags(trim($_POST["websiteName"]))));
		$title = xssClean(mres(strip_tags(trim($_POST["title"]))));
		$description = xssClean(mres(trim(strip_tags($_POST["description"]))));
		$keywords = xssClean(mres(trim($_POST["metaTags"])));
		$rootpath = xssClean(mres(strip_tags(trim($_POST["rootpath"]))));
		$urlStructure=$_POST['urlStructure'];
		$httpsStatus=$_POST['https'];
		if($urlStructure=='on')
			$urlStructure=1;
		else
			$urlStructure=0;
		if($httpsStatus=='on') {
		$code = httpStatusCode("https://" . $rootpath);
		if(!$code) {
		$sslError = true;
		$httpStatus=0;
		} else{
		$httpStatus=1;
		}
		} else {
		$httpStatus=0;
		}
		if (trim($_FILES["logo"]["name"]) != "")
		{
		$base = explode(".", strtolower(basename($_FILES["logo"]["name"])));
		$ext = end($base);
		if (validLogoExtension($ext))
		{
			$logo = "logo.".$ext;
			unlink("../images/logo/".frontPageLogo());
			move_uploaded_file($_FILES["logo"]["tmp_name"], "../images/logo/".$logo);
		}
		else
		{
			$logo = frontPageLogo();
			$error1='Invalid Logo Extension';
		}
	}
	else
	{
		$logo = frontPageLogo();
	}
	if (trim($_FILES["favicon"]["name"]) != "")
	{
		$base = explode(".", strtolower(basename($_FILES["favicon"]["name"])));
		$ext = end($base);
		if (validFaviconExtension($ext))
		{
			$favicon = "favicon." . $ext;
			unlink("../images/favicon/" . favicon());
			move_uploaded_file($_FILES["favicon"]["tmp_name"], "../images/favicon/".$favicon);
		}
		else
		{
			$favicon = favicon();
			$error='Invalid Favicon Extension';
		}
	}
	else
	{
		$favicon = favicon();
	}
	if($error=="" && $error1=="")
		{
			updateSettings($websiteName,$title,$description,$keywords,$rootpath,$logo,$favicon,$urlStructure,$httpsStatus);
		}
	}
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>General Settings : <?php echo(getTitle()) ?></title>
	<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
	<link href="style/css/upload.css" rel="stylesheet">
	<script type="text/javascript" src="style/js/upload.js"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#settings').hide();
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
				<h2>
					<i class="fa fa-cog color"></i> General Settings 
				</h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error=="" && $error1=="" && $error2==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> General Settings Updated Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-xs-10">
					<div class="awidget" style="padding: 15px;">
						<form class="form-horizontal" role="form" action="settings.php" method="post" id="imageform" enctype="multipart/form-data">
							<?php 
							$qry = mysql_query("SELECT * FROM `settings`");
							while($row=mysql_fetch_array($qry))
							{
								$websiteName=$row["websiteName"];
								$title = $row["title"];
								$description = $row["description"];
								$keywords = $row["metaTags"];
								$EmailContact = $row["EmailContact"];
								$rootpath = $row["rootpath"];
								$logo= $row["frontPageLogo"];
								$favi= $row["favicon"];
							}
							?>
							<div class="form-group">
								<label class="col-lg-2 control-label">Name</label>
								<div class="col-lg-8">
									<input type="text" class="form-control" name="websiteName" value="<?php echo $websiteName; ?>" required/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Title</label>
								<div class="col-lg-8">
									<input type="text" class="form-control" name="title" value="<?php echo $title; ?>" required/>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Description</label>
									<div class="col-lg-8">
									<textarea class="form-control" rows="5" name="description" maxlength="180" required><?php echo $description; ?></textarea>
								</div>
							</div> 
							<div class="form-group">
								<label class="col-lg-2 control-label">Keywords</label>
								<div class="col-lg-8">
									<textarea class="form-control" id="keywords" rows="5" name="metaTags"><?php echo $keywords ; ?></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Rootpath</label>
								<div class="col-lg-8">
									<input type="text" class="form-control" name="rootpath" value="<?php echo $rootpath ?>" required/>
								</div>
							</div>
							<div class="form-group last">
								<div class="col-md-9 col-lg-push-2">
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="fileupload-new org">
											<?php
											echo '<img class="thumbnail" src="../images/logo/'.$logo.'?'.time().'">';
											?>
										</div>
										<div class="fileupload-preview fileupload-exists"></div>
											<span class="btn btn-white btn-file btn-info">
											<span class="fileupload-new">
											<i class="fa fa-paper-clip"></i> Change Logo
											</span>
											<span class="fileupload-exists">
											<i class="fa fa-undo"></i> Change Logo
											</span>
											<input type="file" name="logo" class="default" />
											</span>
											<?php
												if(isset($_POST) && $error1!="")
												echo('<span class="label label-danger">' . $error1 . '</span>');
											?>
									</div>
								</div>
							</div>
							<div class="form-group last">
								<div class="col-md-9 col-lg-push-2">
									<div class="fileupload fileupload-new" data-provides="fileupload">
										<div class="fileupload-new  org">
											<?php
											echo '<img class="thumbnail" src="../images/favicon/'.$favi.'?'.time().'">';
											?>
										</div>
										<div class="fileupload-preview fileupload-exists "></div>
											<span class="btn btn-white btn-file btn-info">
											<span class="fileupload-new">
											<i class="fa fa-paper-clip"></i> Change Favicon
											</span>
											<span class="fileupload-exists">
											<i class="fa fa-undo"></i> Change Favicon
											</span>
											<input type="file" name="favicon" class="default" value="<?php echo $favi;?> "/>
											</span>
											<?php
											if(isset($_POST) && $error!="")
											echo('<span class="label label-danger">' . $error . '</span>');
											?>
									</div>
								</div>
							</div>	
							<div class="form-group">
								<label class="col-lg-2 control-label"> WWW</label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
									<input type="checkbox" name="urlStructure" <?php echo (urlStructure() ? 'checked' : '')?>>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label"> HTTPS</label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
									<input type="checkbox" name="https" <?php echo (httpsStatus() ? 'checked' : '')?>>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-10">
									<button class="btn btn-success" name="submit" type="submit" ><i class="fa fa-pencil-square-o"></i> Update</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div> 
	</div>
<script src="style/switch/build/js/bootstrap-switch.min.js"></script> 
<script src="style/switch/docs/index.js"></script>
<link rel="stylesheet" type="text/css" href="style/css/TagsInput.css" />
<script src="style/js/TagsInput.js"></script>
<script type="text/javascript">
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
	$('#keywords').tagsInput({width:'auto'});
});
</script>
	<?php include 'common/footer.php';
}
?>