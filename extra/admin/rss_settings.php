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
	include 'common/header.php';
	$error = "";
	if(isset($_POST['rssLimit']))
	{
		$enable = $_POST["rssEnable"];
		$limit = xssClean(mres(trim($_POST["rssLimit"])));
		$descriptionLength = xssClean(mres(trim($_POST["rssDescription"])));
		if(!is_numeric($descriptionLength))
			$error='Invalid description length';
		$recentEnable = $_POST["rssRecent"];
		$topEnable = $_POST["rssTop"];
		$tagsEnable = $_POST["rssTags"];
		$categoryEnable = $_POST["rssCategory"];
		if($enable=='on')
		$enable=1;
		else
		$enable=0;
		if($recentEnable=='on')
		$recentEnable=1;
		else
		$recentEnable=0;
		if($categoryEnable=='on')
		$categoryEnable=1;
		else
		$categoryEnable=0;
		if($topEnable=='on')
		$topEnable=1;
		else
		$topEnable=0;
		if($tagsEnable=='on')
		$tagsEnable=1;
		else
		$tagsEnable=0;
		if($error=="")
		{
			updateRssSettings($enable,$limit,$descriptionLength,$recentEnable,$categoryEnable,$topEnable,$tagsEnable); 
		}
	}
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>RSS Settings : <?php echo(getTitle()) ?></title>
	<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
	<script type="text/javascript">
	$( document ).ready(function()
	{
		$( "#settings" ).hide();
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
				<h2><i class="fa fa-rss color"></i>RSS Settings  </h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Rss Settings Updated Successfully!
				</div>
				<?php } ?>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<div class="awidget" style="padding: 15px;">
						<form class="" role="form" action="rss_settings.php" method="post">
							<div class="row">
								<div class="form-group">
									<label class="col-lg-2 control-label">RSS Feed</label>
										<div class="make-switch switches" data-on="primary" data-off="info" >
										<?php if(rssEnable()) 
										{ 
											?>
											<input type="checkbox" name="rssEnable" checked>
											<?php 
										} 
										else 
										{ 
											?>
											<input type="checkbox" name="rssEnable">
											<?php 
										} 
										?>
									</div>
								</div>
								<br>
								<div class="form-group">
									<label class="col-lg-2 control-label">RSS Limit</label>
									<div class="col-lg-5">
										<select class="form-control" name="rssLimit">
											<option value="10" <?php if(rssLimit()=='10'){echo"selected";}?> >10</option>
											<option value="15" <?php if(rssLimit()=='15'){echo"selected";}?>>15</option>
											<option value="25" <?php if(rssLimit()=='25'){echo"selected";}?>>25</option>
											<option value="50" <?php if(rssLimit()=='50'){echo"selected";}?>>50</option>
										</select>
									</div>
								</div>
								<br>
								<div class="form-group">
									<label class="col-lg-2 control-label">Description Length</label>
									<div class="col-lg-5">
										<input type="text" class="form-control" name="rssDescription" value="<?php echo rssDescription();?>" required />
										<?php
									if(isset($_POST) && $error!="")
										echo('<span class="label label-danger">' . $error . '</span>');
									?>
									</div>
								</div>
								<br>
								<div class="form-group">
									<label class="col-lg-2 control-label">RSS Recent</label>
									<div class="make-switch switches" data-on="primary" data-off="info">
										<?php 
										if(rssRecentEnable()) 
										{
											?>
											<input type="checkbox" name="rssRecent" checked>
											<?php 
										} 
										else 
										{ 
											?>
													<input type="checkbox" name="rssRecent">
											<?php 
										} 
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">RSS Top</label>
									<div class="make-switch switches" data-on="primary" data-off="info">
										<?php 
										if(rssTopEnable()) 
										{
											?>
											<input type="checkbox" name="rssTop" checked>
											<?php 
										} 
										else 
										{ 
											?>
													<input type="checkbox" name="rssTop">
											<?php 
										} 
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">RSS Category</label>
									<div class="make-switch switches" data-on="primary" data-off="info">
										<?php 
										if(rssCategoryEnable()) 
										{ 
											?>
											<input type="checkbox" name="rssCategory" checked>
											<?php 
										} 
										else 
										{ 
											?>
													<input type="checkbox" name="rssCategory">
											<?php 
										} 
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">RSS Tags</label>
									<div class="make-switch switches" data-on="primary" data-off="info">
										<?php 
										if(rssTagsEnable()) 
										{
											?>
											<input type="checkbox" name="rssTags" checked>
											<?php 
										} 
										else 
										{ 
											?>
													<input type="checkbox" name="rssTags">
											<?php 
										} 
										?>
									</div>
								</div>
								<hr/>
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button type="submit" class="btn btn-md btn-success" name="submit"><i class="fa fa-pencil-square-o"></i> Update</button>
									</div>
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
<?php include 'common/footer.php';?>