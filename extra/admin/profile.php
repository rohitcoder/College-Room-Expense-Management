<?php  
error_reporting(0);
if(!isset($_SESSION)) 
session_start();
if(!isset($_SESSION['type']) || !isset($_SESSION['admin_eap']) || !isset($_SESSION['id']) || !isset($_SESSION['username']))
{
header("location: index.php");
}
include 'common/header.php';
$sid=$_SESSION['id'];
$error3 = "";
$error1 = "";
$error2 = "";
function userUpdate($sid,$name,$newpassword,$email)
{
	mysql_query("UPDATE `user` SET `username`='".$name."',`password`='".$newpassword."',`email`='".$email."' WHERE `id`='".$sid."'")or die(mysql_error());
}
if(isset($_POST['submit']))
{
	$name = xssClean(mres(trim(strip_tags($_POST["username"]))));
	$qry = mysql_query("SELECT `username` FROM `user` WHERE `id`!='$sid'");
	while($fetch= mysql_fetch_array($qry))
	{
		foreach($fetch as $dbuser)
		{
			if($name==$dbuser)
			{
			$error2 .= "User Name Already Exist !";
			}
		}	
	}
	if(!validUsername($name))
	{
		$error2 .= "User Name Can only contain Letter a-Z and numbers 0-9";
	}
	$email = xssClean(mres(trim($_POST["email"])));
	$eqry = mysql_query("SELECT `email` FROM `user` WHERE `id`!='".$sid."'");
	while($efetch= mysql_fetch_array($eqry))
	{
		foreach($efetch as $dbemail)
		{
			if($email==$dbemail)
			{
				$error4 .= "Email Already Exist. Try another !";
			}
		}
	}

	if($email=="" || !checkEmail($email) )
	{
		$error3 .= "Please enter Valid Email";
	}
		
	$oldpassword = xssClean(mres(trim($_POST["oldpassword"])));
					
	if($oldpassword!="")
	{
		$oldpassword =MD5($_POST["oldpassword"]);
		$qury = mysql_query("SELECT * FROM `user` WHERE id='$sid'");
		$row= mysql_fetch_array($qury);
		if($oldpassword==$row['password'])
		{					
			$oldpassword=$row['password'];
		}
		else
		{
			$error1 .= "Please Enter Correct Password";
		}
	}
	$newpassword=$_POST["newpassword"]; 
					
	if($_POST["newpassword"]=="")
	{
		$qury = mysql_query("SELECT `password` FROM `user` WHERE `id`='$sid'");
		while($row= mysql_fetch_array($qury))
		{
			$newpassword=$row['password'];				
		}
	}
	else
	{
		$newpassword=md5($_POST["newpassword"]);
	}
	 
	if($error1=="" && $error2=="" && $error3=="" && $error4=="")
	{
		userUpdate($sid,$name,$newpassword,$email);
	}
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>My Profile : <?php echo(getTitle()) ?></title>
</head>
<body>
<?php include 'common/navbar_admin.php'; ?>
<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h2><i class="fa fa-user"></i> My Profile</h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error1=="" && $error2=="" && $error3=="" && $error4==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Profile Updated Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"> Update Profile</h3>
				</div>
				<div class="panel-body">
					<div class="profile">
						<form class="form-horizontal" role="form" action="profile.php" method="post">
							<?php
							$qry = mysql_query("SELECT * FROM `user` WHERE `id`='$sid'");
							while($row= mysql_fetch_array($qry))
							{
								$id=$row['id'];
								$uname=$row['username'];
								$uemail=$row['email'];
							}
							?>
							<div class="form-group">
								<label class="col-lg-2 control-label">User Name</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" name="username" value="<?php echo $uname;  ?>" placeholder="User Name" pattern=".{5,30}" title="5 to 30 characters" required />
									<?php
									if(isset($_POST['submit']) && $error2!="")
										echo('<span class="label label-danger">' . $error2 . '</span>');
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Email</label>
								<div class="col-lg-10">
									<input type="text" class="form-control" name="email" value="<?php echo $uemail ; ?>"  placeholder="User Email" required />
									<?php
									if(isset($_POST['submit']) && $error3!="")
										echo('<span class="label label-danger">' . $error3 . '</span>');
									if(isset($_POST['submit']) && $error4!="")
										echo('<span class="label label-danger">' . $error4 . '</span>');
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">Old Password</label>
								<div class="col-lg-10">
									<input type="password" class="form-control" name="oldpassword"  placeholder="Enter Old Password" required/>
									<?php
									if(isset($_POST['submit']) && $error1!="")
										echo('<span class="label label-danger">' . $error1 . '</span>');
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-2 control-label">New Password</label>
								<div class="col-lg-10">
									<input type="password" class="form-control" name="newpassword"  placeholder="Enter New Password" />
									<small>(Optional)</small>
								</div>
							</div>
							<div class="form-group">
								<div class="col-lg-6 col-lg-offset-2">
									<button type="submit" name="submit" class="btn btn-success"><i class="fa fa-pencil-square-o"></i> Update</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
			<div class="clearfix"></div> 
		</div>
	</div>
</div>
<?php include 'common/footer.php'; ?>