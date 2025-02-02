<?php
error_reporting(0);
if(!isset($_SESSION)) session_start();

if(!isset($_SESSION['upgrade_step']))
$_SESSION['upgrade_step']=1;

include '../../config/config.php';
$row = mysql_fetch_array(mysql_query("SELECT `version` FROM `settings`"));
$version = $row['version'];
?>
<!DOCTYPE html> 
<html lang="en">

	<head>
		<meta charset="utf-8">
		<title>Universal Affiliate Store Upgrade Wizard</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="shortcut icon" href="../../static/images/favicon.png">
		<link href="../style/css/bootstrap.min.css" rel="stylesheet">
		<link href="../style/css/style.css" rel="stylesheet">
		<script src="../style/js/bootstrap.js"></script>
	</head>

	<body>
		<div class="hidden-xs">
			<div class="logo">
				<img src="../style/images/logo.png">
			</div>
			<div class="sub-logo">
				Universal Affiliate Store Upgrade Wizard
			</div>
		</div>
		<div class="visible-xs logo-sm">
			<img src="../style/images/logo-sm.png">
		</div>
		  
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<div class="panel panel-default">
						  <div class="panel-heading">
							<strong>Thank you for Purchasing <a href="http://www.nexthon.com">Nexthon's</a> Product</strong>
							<div class="pull-right">
								<span class="badge badge-warning">Begin</span>
							</div>
						  </div>
						  <div class="panel-body">
							<h1>Universal Affiliate Store Upgrade Wizard</h1>
							<h4>Upgrade Wizard</h4>
							<br />
							<p>Universal Affiliate Store Script is a powerful PHP based script helping you earn affiliate commission through Affiliate links. Three Different types of admin Can be defined with Different Access Levels. Unlimited Categories and Sub Categories can be Added. Script is Fully responsive based on Bootstrap. The script has very user friendly interface, easy to set up and social media Ready <a href="http://nexthon.ticksy.com">Contact Our Support</a>.</p>
							<br>
							<?php
							if($version==1.4)
							{
								?>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
									<div class="alert alert-success">
										<i class="fa fa-check-square"></i> 
										You are Already using Latest Version !
									</div>
									<a href="../../" class="btn btn-success btn-lg" role="button">Go Back</a>
								</div>
								<?php
							}
							else if($version=="")
							{
								?>
								<div class="col-xs-12 col-sm-8 col-md-8 col-lg-6">
									<div class="alert alert-danger">
										<i class="fa fa-check-square"></i> 
											Universal Affiliate Store Is Not Installed.
									</div>
									<a href="../" class="btn btn-success btn-lg" role="button">Install Now</a>
								</div>
								<?php
							}
							else
							{
								?>
								<p>
									<a href="requirements.php?<?php echo(time()); ?>" class="btn btn-info btn-lg" role="button">Upgrade</a>
								</p>
								<?php
							}
							?>
						  </div>
						  <div class="hidden-xs hidden-sm">
							  <center>All Rights Reserved <a href="http://www.nexthon.com">
								Nexthon.com</center>
							  </a>
						  </div>
					</div>
				</div>
			</div>
		</div>
	</body>

</html>