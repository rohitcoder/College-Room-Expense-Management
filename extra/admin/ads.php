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
function updateAds($largeRect1, $largeRect1Status, $largeRect2, $largeRect2Status, $largeRect3, $largeRect3Status,$largeRect1StatusResponsive,$largeRect2StatusResponsive,$largeRect3StatusResponsive)
{
    mysql_query("UPDATE `ads` SET `largeRect1`='$largeRect1',`largeRect1Status`='$largeRect1Status',`largeRect2`=' $largeRect2',`largeRect2Status`='$largeRect2Status',`largeRect3`=' $largeRect3',`largeRect3Status`='$largeRect3Status',`largeRect1StatusResponsive`='$largeRect1StatusResponsive',`largeRect2StatusResponsive`='$largeRect2StatusResponsive',`largeRect3StatusResponsive`='$largeRect3StatusResponsive'");
}
if (isset($_POST['submit'])) 
{
    $largeRect1 = mres(trim($_POST["largeRect1"]));
    $largeRect2 = mres(trim($_POST["largeRect2"]));
	$largeRect3 = mres(trim($_POST["largeRect3"]));
	$largeRect1StatusResponsive = mres(trim($_POST["largeRect1StatusResponsive"]));
    $largeRect2StatusResponsive = mres(trim($_POST["largeRect2StatusResponsive"]));
	$largeRect3StatusResponsive = mres(trim($_POST["largeRect3StatusResponsive"]));
    if ($_POST["largeRect1Status"] == "on")
        $largeRect1Status = 1;
    else
        $largeRect1Status = 0;
    if ($_POST["largeRect2Status"] == "on")
        $largeRect2Status = 1;
    else
        $largeRect2Status = 0;
	if ($_POST["largeRect3Status"] == "on")
        $largeRect3Status = 1;
    else
        $largeRect3Status = 0;
	
	if ($_POST["largeRect1StatusResponsive"] == "on")
        $largeRect1StatusResponsive = 1;
    else
        $largeRect1StatusResponsive = 0;
    if ($_POST["largeRect2StatusResponsive"] == "on")
        $largeRect2StatusResponsive = 1;
    else
        $largeRect2StatusResponsive = 0;
	if ($_POST["largeRect3StatusResponsive"] == "on")
        $largeRect3StatusResponsive = 1;
    else
        $largeRect3StatusResponsive = 0;
	updateAds($largeRect1, $largeRect1Status, $largeRect2, $largeRect2Status, $largeRect3, $largeRect3Status,$largeRect1StatusResponsive,$largeRect2StatusResponsive,$largeRect3StatusResponsive);
}
$adsData=getAdsData();
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
<title>Ads Management : <?php echo (getTitle()); ?></title>
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
				<h2><a href="./ads.php"><i class="fa fa-code color"></i></a> Ads Management </h2>
				<hr />
				<?php if(isset($_POST['submit'])){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Ads Settings Updated Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="awidget" style="padding: 15px;">
						<form class="" role="form" action="ads.php" method="post">
						<div class="row">
							<div class="form-group">
								<label class="col-lg-2 control-label">
								Ad 1 - (728X90) After Slider
								<div class="form-group">
									<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
									<?php
									if ($adsData['largeRect1Status']) 
										{
										?>
										<input type="checkbox" name="largeRect1Status" checked> 
										<?php
									} 
									else 
									{
										?>
										<input type="checkbox" name="largeRect1Status">
										<?php
									}
									?>Responsive Ads<?php
									if ($adsData['largeRect1StatusResponsive']) 
									{
										?> 
										<input type="checkbox" name="largeRect1StatusResponsive" checked>
										<?php
									} 
									else 
									{
										?>
										<input type="checkbox" name="largeRect1StatusResponsive" >
										<?php
									}
									?>
									</div>
								</div>
								</label>
								<div class="col-lg-10">
									<textarea class="form-control" rows="8" name="largeRect1"><?php
									echo $adsData['largeRect1'];
									?></textarea>
								</div>
							</div>
							<div class="clearfix"></div><br>
							<div class="form-group">
								<label class="col-lg-2 control-label">
								Ad 2 - (728X90)
								<div class="form-group">
									<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
										<?php
										if ($adsData['largeRect2Status']) 
										{
											?> 
											<input type="checkbox" name="largeRect2Status" checked>
											<?php
										} 
										else 
										{
											?>
											<input type="checkbox" name="largeRect2Status" >
											<?php
										}
										?>Responsive Ads<?php
										if ($adsData['largeRect2StatusResponsive']) 
										{
											?> 
											<input type="checkbox" name="largeRect2StatusResponsive" checked>
											<?php
										} 
										else 
										{
											?>
											<input type="checkbox" name="largeRect2StatusResponsive" >
											<?php
										}
										?>
									</div>
								</div>
								</label>
								<div class="col-lg-10">
									<textarea class="form-control" rows="8" name="largeRect2"><?php echo $adsData['largeRect2']; ?></textarea>
									</div>
							</div>
							<div class="clearfix"></div><br>
							<div class="form-group">
								<label class="col-lg-2 control-label">
								Ad 3 - (728X90)
								<div class="form-group">
									<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
										<?php
										if ($adsData['largeRect3Status']) 
										{
											?> 
											<input type="checkbox" name="largeRect3Status" checked>
											<?php
										} 
										else 
										{
											?>
											<input type="checkbox" name="largeRect3Status" >
											<?php
										}
										?>Responsive Ads<?php
										if ($adsData['largeRect3StatusResponsive']) 
										{
											?> 
											<input type="checkbox" name="largeRect3StatusResponsive" checked>
											<?php
										} 
										else 
										{
											?>
											<input type="checkbox" name="largeRect3StatusResponsive" >
											<?php
										}
										?>
									</div>
								</div>
								</label>
								<div class="col-lg-10">
									<textarea class="form-control" rows="8" name="largeRect3"><?php echo $adsData['largeRect3']; ?></textarea>
									</div>
							</div>
							<div class="clearfix"></div><br>
							<hr />
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-10"> 
									<button type="submit" class="btn btn-success" name="submit"><i class="fa fa-pencil-square-o"></i> Update</button>
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