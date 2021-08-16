<?php
error_reporting(0);
include '../config/config.php';
if (!isset($_SESSION)) 
session_start();
$inactive = 30;
if (isset($_SESSION["timeout"]))
{
	$sessionTTL = time() - $_SESSION["timeout"];
	if ($sessionTTL > $inactive)
	{
		unset($_SESSION['processed']);
		unset($_SESSION["timeout"]);
		session_destroy();
		session_start();
	}
}
$_SESSION["timeout"] = time();
if (isset($_SESSION['admin_eap']))
{
header("location:dashboard.php");
}
include "../common/simpleMail.php";
include 'common/header.php';
$error = "";
if ((isset($_POST['email']) && trim($_POST['email']) != "") || (isset($_POST['username']) && trim($_POST['username']) != ""))
{
	$email = "";
	$username = "";
		$email = xssClean(mres(trim($_POST["email"])));
		$username = xssClean(mres(trim($_POST["username"])));
		if($email !="") {
			if (!checkEmail($email))
			{
				$error.= "Invalid Email Address";
			}
			if (checkEmail($email) && !emailExists($email))
			{
				$error.= "Email Doesn't Exists";
			}
		}
		else if($username!=""){
			if(userNameExists($username)){
			$qry = mysql_fetch_array(mysql_query("SELECT `email` FROM `user` WHERE `username`='$username'"));
			$email=$row['email'];
			}
			else
			{
			$error.= "Username doesn't exists";
			}
		}
	if(emailExists($email) && $error=="")
	{
		sendEmail($email,$email,"Password reset request",$username . " Please click on the link below to reset your password<br/><a href=". rootpath() . "/admin/reset.php?rid=" . sencrypt($email).">". rootpath() . "/admin/reset.php?rid=" . sencrypt($email)."</a>");
	}
}
else
{
	$error = "Enter a valid email or username to reset password";
}
?>
<title>Reset Password: <?php echo (getTitle());?></title>
</head>
<body>
<div class="header">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="logo text-center">
					<h1>
						<a href="index.php">
							<?php echo (getTitle()); ?>
						</a>
					</h1>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="page-content blocky">
	<div class="container" style="min-height:550px">
		<div class="row">
			<div class="col-md-12">
				<div class="awidget login-reg">
					<div class="awidget-head"></div>
					<div class="awidget-body">
						<div class="page-title text-center">
							<h2>Reset Password</h2>
							<hr />
						</div>
						<?php
						if (isset($_GET['rid']) && $_GET['rid'] != "")
						{
							$rid=xssClean(mres(trim($_GET['rid'])));
							$dec_email = sdecrypt($rid);
							if (emailExists($dec_email))
							{
								if (!isset($_SESSION['processed']))
								{
									resetPass($dec_email);
									$_SESSION['processed'] = true;
								}
								?>
								<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#">×</a><i class="icon-check-sign"></i>&nbsp;New password generated and emailed to you !</div>
								<?php
							}
							else
							{
								?>
								<div class="alert alert-danger">
								<a class="close" data-dismiss="alert" href="#">×</a><i class="icon-remove-sign"></i>&nbsp;Invalid Request or Timed Out. </div>
								<?php
							}
						}
						if ((isset($_POST['email']) || isset($_POST['username'])) && $error == "")
						{
							?>
							<div class="alert alert-success"><a class="close" data-dismiss="alert" href="#">×</a><i class="icon-info-sign"></i>&nbsp;Password reset instructions sent to your email address.</div>
							<?php
						}
						else if ((isset($_POST['email']) || isset($_POST['username'])) && $error != "")
						{
							echo ('<div class="alert alert-danger"><a class="close" data-dismiss="alert" href="#">×</a><i class="icon-remove-sign"></i>&nbsp;' . $error . ' !</div>');
						}
						?>
						<form class="form-horizontal" role="form" method="POST" action="reset.php" accept-charset="UTF-8">
							<div class="form-group">
								<label class="col-lg-2 control-label">Email</label>
								<div class="col-lg-8">
									<input type="text" id="username" class="form-control" name="email" placeholder="Email" >
								</div>
							</div>
							<div align="center">
								<legend>OR</legend>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Username</label>
								<div class="col-lg-8">
									<input type="text" id="username" class="form-control" name="username" placeholder="Username" >
								</div>
							</div>
							<hr />
							<div class="form-group">
								<div class="col-lg-offset-2 col-lg-10">
									<button type="submit" name="submit" class="btn btn-success">Reset</button>
									<a href="./index.php" class="btn btn-info">Back</a>
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