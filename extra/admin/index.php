<?php
error_reporting(0);
if(!isset($_SESSION))
session_start();
error_reporting(0);
if(isset($_SESSION['type']) && isset($_SESSION['admin_eap']) && isset($_SESSION['id']) && isset($_SESSION['username']))
{
header("location: dashboard.php");
exit();
}
include "../config/config.php";     
include "../common/functions.php";
$error = "";
if(isset($_POST["submit"])) 
{
	$username=xssClean(mres($_POST["username"]));
	$password=md5(xssClean(mres(trim($_POST["password"]))));
	if(!onOffAdminCaptcha()) 
	{
		if(isset($_POST["captcha"]) && trim($_POST["captcha"])!="") 
		{ 
			if (trim(strtolower($_POST['captcha'])) != $_SESSION['captcha'])
			{
				$error = 'Invalid Captcha';
			}	
		}
		else
		{
			$error='Captcha Feild Must Not Be Empty';
		}
	}
	if($error == "")
	{
		$qry = mysql_query("SELECT * FROM `user` WHERE `username`='$username' AND `password`='$password'") or die(mysql_error());
		while($row=mysql_fetch_array($qry))
		{
			$id=$row['id'];
			$username=$row['username'];
			$type=$row['type'];
		}
		$num_rows = mysql_num_rows($qry); 
		if ($num_rows > 0)
		{
			$_SESSION['id']= $id;
			$_SESSION['type']= $type;
			$_SESSION['username']= $username;
			if($type==1) //adMin
			{
				$_SESSION['type']= 'a3852d52cc0ac36ea335d8cdd952d4cf';
			}
			else if($type==2) // subADmin
			{
				$_SESSION['type']= 'f2215e916e70ca1152df06d5ce529a87';
			}
			else if($type==3) //modeRator
			{
				$_SESSION['type']= '3d3c3f3cc8a1f15cf46db659eaa2b63d';
			}
			$_SESSION['admin_eap']= 1;
			$_SESSION['image']= 1;
			header("location: dashboard.php");
			exit();
		}
		else
		{
			$error = "Invalid username and password combination";
		}
	}
	unset($_SESSION['captcha']);
}
?>
<head>
	<title>Login: <?php  echo(getTitle()) ?></title>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600italic,600' rel='stylesheet' type='text/css'>
	<link rel="shortcut icon" href="<?php echo(rootpath()); ?>/images/favicon/<?php echo(favicon() . "?" . time()); ?>"/>
	<link href="style/css/bootstrap.min.css" rel="stylesheet">
	<link href="style/css/font-awesome.min.css" rel="stylesheet">		
	<link href="style/css/style.css" rel="stylesheet">
	<style>
body{
font-family:Arial, Helvetica, sans-serif;
font-size:13px;
}
</style>
</head>
<div class="header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="logo text-center">
					<h1><a href="index.php">Admin Panel</a></h1>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="page-content blocky">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="awidget login-reg">
					<div class="awidget-head"></div>
					<div class="awidget-body">
						<div class="page-title text-center">
							<h2>Login</h2>
							<hr />
						</div>
						<br />
						<?php
						if ($error != "") 
						{
							?>
							<div class="alert alert-danger">
								<a class="close" data-dismiss="alert" href="#">×</a><i class="icon-remove-sign"></i> <?php echo $error; ?>
							</div>
							<?php
						}
						?>
						<form class="form-horizontal" role="form" method="POST" action="index.php" accept-charset="UTF-8">
							<div class="form-group">
								<label class="col-lg-2 control-label">Username</label>
								<div class="col-lg-10">
									<input type="text" id="username" class="form-control" name="username" placeholder="Username" />
								</div>
							</div>
							<div class="form-group">
								<label for="inputPassword1" class="col-lg-2 control-label">Password</label>
								<div class="col-lg-10">
									<input type="password" id="password" class="form-control" name="password" placeholder="Password" />
								</div>
							</div>
							<?php if(onOffAdminCaptcha()){ ?>
							<div class="form-group">
								<label for="inputPassword1" class="col-lg-2 visible-lg control-label">Captcha</label>
								<div class="col-sm-6 col-lg-5 captcha-center">
									<img src="captcha/captcha.php" id="captcha" /><br/>
									<div class="a-chng-txt">
									<a href="#" onclick="
										document.getElementById('captcha').src='captcha/captcha.php?'+Math.random();
										document.getElementById('captcha-form').focus();"
										id="change-image">Not readable? Change text.
									</a></div><br/>
								</div>
								<div class="col-sm-6 col-lg-5">
									<input type="text" id="captchaCode" class="form-control captcha-input" name="captcha" placeholder="Enter Above Code" />
								</div>
							</div>
							<?php } ?>
							<hr>
							<div class="form-group"> 
								<div class="col-lg-offset-2 col-lg-10">
									<button type="submit" name="submit" class="btn btn-success">Sign in</button>
									<a href="reset.php" class="btn btn-info">Reset</a>
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
<?php include 'common/footer.php'; ?>		