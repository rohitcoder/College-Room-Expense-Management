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
include "common/header.php";
$error = "";
function updateCaptchaSettings($adminCaptcha, $contactCaptcha)
{
    mysql_query("UPDATE captchaSettings SET `adminCaptcha`='$adminCaptcha',contactCaptcha='$contactCaptcha'");
}
if (isset($_POST['submit'])) 
{
    $adminCaptcha = xssClean(mres(trim($_POST["adminCaptcha"])));
    $contactCaptcha = xssClean(mres(trim($_POST["contactCaptcha"])));
    if ($adminCaptcha == "on")
        $adminCaptcha = 1;
    else
        $adminCaptcha = 0;
    if ($contactCaptcha == "on")
        $contactCaptcha = 1;
    else
        $contactCaptcha = 0;
	updateCaptchaSettings($adminCaptcha, $contactCaptcha);
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
<title>Captcha Settings : <?php echo (getTitle()); ?></title>
<script>
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-43092768-1']);
_gaq.push(['_trackPageview']);
(function () 
{
	var ga = document.createElement('script');
	ga.type = 'text/javascript';
	ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(ga, s);
})();
</script>
</head>
<body>
<?php include 'common/navbar_admin.php'; ?>
<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h2><a href="./manage_captcha.php"><i class="fa fa-eye-slash color"></i></a> Captcha Settings </h2>
				<hr />
				<?php if(isset($_POST['submit'])){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Captcha Settings Updated Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="awidget" style="padding: 15px;">
						<form class="" role="form" action="manage_captcha.php" method="post">
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label"> Admin Captcha </label>
								<?php $captcha=mysql_fetch_array(mysql_query("SELECT * FROM captchaSettings"));?>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
									<?php
									if ($captcha['adminCaptcha']) 
									{
										?> 
										<input type="checkbox" name="adminCaptcha" checked>
										<?php
									} 
									else 
									{
										?>
										<input type="checkbox" name="adminCaptcha" >
										<?php
									}
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"> Contact Page Captcha </label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
										<?php
										if ($captcha['contactCaptcha']) 
										{
											?> 
											<input type="checkbox" name="contactCaptcha" checked>
											<?php
										} 
										else 
										{
											?>
											<input type="checkbox" name="contactCaptcha" >
											<?php
										}
										?>
									</div>
							</div>
							<hr />
							<div class="form-group">
								<div class="col-lg-10"> 
									<button type="submit" name="submit" class="btn btn-success" ><i class="fa fa-pencil-square-o"></i> Update</button>
								</div>
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
<script src="style/switch/build/js/bootstrap-switch.min.js"></script> 
<script src="style/switch/docs/index.js"></script>
<?php include 'common/footer.php';  ?> 