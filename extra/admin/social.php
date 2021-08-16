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
	function updateSocial($facebook,$twitter,$google,$pinterest,$status)
	{	 
		mysql_query("UPDATE `socialProfiles` SET `facebook`='$facebook',`twitter`='$twitter',`google`='$google',`pinterest`='$pinterest',`status`='$status'");
	}			
	include 'common/header.php';
	$error1 = "";
	$error2 = "";
	$error3 = "";
	$error4 = "";
	$error5 = "";
	if(isset($_POST['submit']))
	{
		if($_POST["facebook"]!="")
		{
			$facebook = xssClean(mres(trim($_POST["facebook"])));
			if(!validFacebookUrl($_POST["facebook"]))
				$error1 .="Invalid Facebook Page ID/Name";
		}
		if($_POST["twitter"]!="")
		{
			$twitter = xssClean(mres(trim($_POST["twitter"])));
			if(!validTwitterUsername($_POST["twitter"]))
				$error2 .="Invalid Twitter Username";
				
		}
		if($_POST["google"]!="")
		{
			$google = xssClean(mres(trim($_POST["google"])));
			if(!validGoogleUrl($google))
				$error3 .="Invalid Google+ Page ID";
		}
		if($_POST["pinterest"]!="")
		{
			$pinterest= xssClean(mres(trim($_POST["pinterest"])));
			if(!validPinterestUrl($pinterest))
				$error5 .="Invalid pinterest Page Name";
		}
		$socialProfileStatus=xssClean(mres(trim($_POST['socialProfileStatus'])));
		if($socialProfileStatus=='on')
			$socialProfileStatus=1;
		else
			$socialProfileStatus=0;
		if($error1=="" && $error2=="" && $error3=="" && $error4=="" && $error5=="")
		{
			updateSocial($facebook,$twitter,$google,$pinterest,$socialProfileStatus);
		}
	}
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Social Profiles : <?php echo(getTitle()) ?></title>
	<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
	<script type="text/javascript">
		$(document).ready(function() 
		{
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
				<h2><i class="fa fa-group color"></i> Social Profiles </h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error1=="" && $error2=="" && $error3=="" && $error4=="" && $error5==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Social Profiles Updated Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="awidget" style="padding: 15px 35px">
						<div class="row">
						<form class="" role="form" action="social.php" method="post">
							<?php $socialProfile=getSocialProfilesData(); ?>
							
							<legend>
								<h3>&nbsp;<i class="fa fa-facebook color"></i>&nbsp;&nbsp;Facebook Page Name</h3>
							</legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">http://www.facebook.com/</label>
								<div class="col-lg-5">
									<input type="text" class="form-control" name="facebook" value="<?php echo $socialProfile['facebook'];?>"  placeholder=" i.e 'Facebook123'" />
									<?php
									if(isset($_POST['facebook']) && $error1!="")
										echo('<span class="label label-danger">' . $error1 . '</span>');
									?>
								</div>
							</div>
							<legend><h3>&nbsp;<i class="fa fa-twitter color"></i>&nbsp;&nbsp;Twitter Page Name</h3></legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">http://www.twitter.com/</label>
								<div class="col-lg-5">
									<input type="text" class="form-control" name="twitter" value="<?php echo $socialProfile['twitter'];?>" placeholder=" i.e 'Twitter123'"/>
									<?php
									if(isset($_POST['submit']) && $error2!="")
										echo('<span class="label label-danger">' . $error2 . '</span>');
									?>
								</div>
							</div>
							<legend>
								<h3>&nbsp;<i class="fa fa-google-plus color"></i>&nbsp;&nbsp;Google+ Page Name</h3>
							</legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">http://plus.google.com/ </label>
								<div class="col-lg-5">
									<input type="text" class="form-control" name="google" value="<?php 	echo $socialProfile['google'];?>" placeholder=" i.e 'google123'"/>
									<?php
									if(isset($_POST['submit']) && $error3!="")
										echo('<span class="label label-danger">' . $error3 . '</span>');
									?>
								</div>
							</div>
							<div class="clearfix"></div><hr />
							<legend>
								<h3>&nbsp;
									<i class="fa fa-pinterest color"></i>&nbsp;&nbsp;Pinterest Page Name
								</h3>
							</legend>
							<div class="form-group">
								<label class="col-lg-3 control-label">http://www.pinterest.com/ </label>
								<div class="col-lg-5">
									<input type="text" class="form-control" name="pinterest" value="<?php echo $socialProfile['pinterest']; ?>" placeholder=" i.e 'Pinterest123'"/>
									<?php
									if(isset($_POST['submit']) && $error5!="")
										echo('<span class="label label-danger">' . $error5 . '</span>');
									?>
								</div>
							</div>
							<div class="clearfix"></div><hr />
							<div class="form-group">
								<div class="col-lg-12">
									<div class="form-group">
									<label class="col-lg-3 control-label"> Social Profile </label>
										<div style="" class="make-switch switches" data-on="primary" data-off="info" >
											<input type="checkbox" name="socialProfileStatus" <?php echo (($socialProfile['status']) ? 'checked' : '')?>>
										</div>
									</div>
									<hr>
									<label class="control-label"> </label>
									<button type="submit" class="btn btn-success" name="submit"><i class="fa fa-pencil-square-o"></i> Update</button>
								</div>
							</div>
							</div>
						</form>
						<button class="notify-without-image" id="settings"></button>
					</div><!-- Awidget -->
				</div><!-- col-md-12 -->
			</div><!-- row -->
		</div><!-- mainy -->
		<div class="clearfix"></div> 
	</div><!-- container -->
<script src="style/switch/build/js/bootstrap-switch.min.js"></script> 
<script src="style/switch/docs/index.js"></script>
	<?php include 'common/footer.php'; 
}
?>
