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
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Add New Link : <?php echo(getTitle()) ?></title>
	<?php
	$error2 = "";
	function addLink($title,$url,$showIn,$status,$newTab,$displayOrder)
	{
		mysql_query("INSERT INTO `links` (`url`,`showIn`,`status`,`newTab`,`displayOrder`) VALUES('$url','$showIn','$status','$newTab','$displayOrder')") or die(mysql_error());
		$query=mysql_query("SELECT * FROM `languages`");
		$id=mysql_insert_id();
		while($fetch=mysql_fetch_array($query))
		{
			$language=$fetch['languageName'];
			mysql_query("UPDATE `links` SET `$language`='$title' WHERE `id`='$id'");
		}
	}
	if(isset($_POST['submit']))
	{
		$title = xssClean(mres(strip_tags(trim($_POST["title"]))));
		if($title=="")
		{
			$error1 .= "Enter Link Title!";
		}
		else if(linkNameAlreadyExist($title,$id))
		{
			$error1='Link Already Exists';
		}
		
		$result = mysql_query("SELECT COUNT(displayOrder) FROM `links`");
		$row = mysql_fetch_array($result);
		$total = $row[0];
		$displayOrder=$total+1;
		$url = mres(trim($_POST["url"]));
		if($url !="" && $url !="#" && !validUrl($url))
			$error2='Invalid Url';
		$showIn=xssClean(mres(trim($_POST['showIn'])));
		$status = xssClean(mres(trim($_POST["status"])));
		$newTab=xssClean(mres(trim($_POST['newTab'])));
		if($error1=="" && $error2=="")
		{
			addLink($title,$url,$showIn,$status,$newTab,$displayOrder);
			?>
			<script>
			$(function() {
				$("input[name='title']").val("");
				$("input[name='url']").val("");
			});
			</script>
			<?php
		}
	}
	?>
	</head>
	<body>
	<?php include 'common/navbar_admin.php'; ?>
	<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h2><i class="fa fa-plus-square-o color"></i> Add Link </h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error1=="" && $error2==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Link Added Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Add New Link</h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="post">
								<div class="form-group">
									<label class="col-lg-2 control-label">Link Title</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="title" placeholder="Link Title" value="<?php echo($title) ?>" maxlength="30" required />
										<?php
										if(isset($_POST['title']) && $error1!="")
											echo('<span class="label label-danger">' . $error1 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Url</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="url" placeholder="Url" value="<?php echo($url) ?>" />
										<?php
										if(isset($_POST['url']) && $error2!="")
											echo('<span class="label label-danger">' . $error2 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Status</label>
									<?php if(isset($_POST['submit'])){ ?>
									<div class="col-lg-10">
											<div class="radio">
												<label>
													<input type="radio" name="status" value="1" <?php echo ($status==1 ? 'checked' : '')?>>
													Published
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="status" value="0" <?php echo ($status==0 ? 'checked' : '')?>>
													Saved
												</label>
											</div>
									</div>
									<?php } else { ?>
									<div class="col-lg-10">
											<div class="radio">
												<label>
													<input type="radio" name="status" value="1" checked>
													Published
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="status" value="0">
													Saved
												</label>
											</div>
									</div>
									<?php } ?>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Placement</label>
									<div class="col-lg-10">
											<div class="radio">
												<label>
													<input type="radio" name="showIn" value="0" <?php echo ($showIn==0 ? 'checked' : '')?>>
													Footer
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="showIn" value="1" <?php echo ($showIn==1 ? 'checked' : '')?>>
													Header
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="showIn" value="2" <?php echo ($showIn==2 ? 'checked' : '')?>>
													Both
												</label>
											</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Open Link In New Tab</label>
									<div class="col-lg-10">
										<div class="radio">
											<label>
												<input type="radio" name="newTab" value="0" <?php echo ($newTab==0 ? 'checked' : '')?>>
												No
											</label>
										</div>
										<div class="radio">
											<label>
												<input type="radio" name="newTab" value="1" <?php echo ($newTab==1 ? 'checked' : '')?>>
												Yes
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
	<?php include 'common/footer.php';
}
?>					