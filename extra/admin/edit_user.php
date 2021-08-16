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
	if(isset($_GET["id"]))
	{
		$id = xssClean(mres(trim($_GET["id"])));
	}
	$error3 = "";
	$error1 = "";
	$error2 = "";
	function userUpdate($id,$name,$password,$email,$type)
	{
		mysql_query("UPDATE `user` SET `username`='".$name."',`password`='".$password."',`email`='".$email."',`type`='".$type."' WHERE `id`='".$id."'")or die(mysql_error());
	}
	if(isset($_POST['submit']))
	{
		$name = xssClean(mres(trim(strip_tags($_POST["username"]))));
		$qry = mysql_query("SELECT `username` FROM `user` WHERE `id`!='$id'");
		while($fetch= mysql_fetch_array($qry))
		{
			foreach($fetch as $dbuser)
			{
				if($name==$dbuser)
				{
					$error1 .= "User Name Already Exist !";
				}
			}	
		}
		$email = xssClean(mres(trim($_POST["email"])));
		$eqry = mysql_query("SELECT `email` FROM `user` WHERE `id`!='$id'");
		while($efetch= mysql_fetch_array($eqry))
		{
			if($email==$efetch['email'])
			{
				$error2 .= "Email Already Exist. Try another !";
			}
		}
		if($email=="" && !checkEmail($email) )
		{
			$error2 .= "Please Enter Valid Email";
		}
		$type = $_POST["type"];
		if($_POST["oldpassword"]!="" && $_POST["newpassword"]!="")
		{
			$password=xssClean(mres(trim($_POST['oldpassword'])));
			$newpassword=trim($_POST['newpassword']);
			if($password!=$newpassword)
				$error4="Password Does't Match";
			else
				$password=md5($password);
		}
		else if($_POST["oldpassword"]=="" && $_POST["newpassword"]!="")
		{
			$error3='Enter Password To Change Password';
		}
		else if($_POST["oldpassword"]!="" && $_POST["newpassword"]=="")
		{
			$error4='Enter Confirm Password To Change Password';
		}
		if($error1=="" && $error2=="" && $error3=="" && $error4=="")
		{
			userUpdate($id,$name,$password,$email,$type);
		}
	}
	$row= mysql_fetch_array(mysql_query("SELECT * FROM `user` WHERE `id`='".$id."'"));
		$uname=$row['username'];
		$uemail=$row['email'];
		$utype=$row['type'];
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Edit User : <?php echo ucfirst($uname)?></title>
	</head>
	<body>
	<?php include 'common/navbar_admin.php'; ?>
	<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h2><i class="fa fa-pencil-square-o color"></i> Edit User </h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error1=="" && $error2=="" && $error3=="" && $error4==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> User Updated Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"> Edit User ( <?php echo getUsername($id) ?> )</h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" action="edit_user.php?id=<?php echo $id; ?>" method="post">
								<div class="form-group">
									<label class="col-lg-2 control-label">User Name</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="username" value="<?php echo $uname;  ?>" placeholder="User Name" pattern=".{5,30}" title="5 to 30 
										characters" required />
										<?php
										if(isset($_POST['submit']) && $error1!="")
											echo('<span class="label label-danger">' . $error1 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Email</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="email" value="<?php echo $uemail ; ?>"  placeholder="User Email" required />
										<?php
											if(isset($_POST['submit']) && $error2!="")
												echo('<span class="label label-danger">' . $error2 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Type</label>
									<div class="col-lg-10">
									<select name="type" class="form-control" >
										<option value="2" <?php if($utype=='2'){echo"selected";}?> >Moderator</option>
										<option value="3" <?php if($utype=='3'){echo"selected";}?> >Publisher</option>
									</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">New Password</label>
									<div class="col-lg-10">
										<input type="password" class="form-control" name="oldpassword"  placeholder="Enter New Password" value="<?php echo $_POST['oldpassword']?>"/>
										<small>(Optional)</small><br>
										<?php
										if(isset($_POST['submit']) && $error3!="")
											echo('<span class="label label-danger">' . $error3 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Confirm Password</label>
										<div class="col-lg-10">
											<input type="password" class="form-control" name="newpassword"  placeholder="Enter Confirm Password" value="<?php echo $_POST['newpassword']?>" />
											<small>(Optional)</small><br>
											<?php
											if(isset($_POST['submit']) && $error4!="")
												echo('<span class="label label-danger">' . $error4 . '</span>');
											?>
									</div>
								</div>
								<hr />
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button type="submit" name="submit" class="btn btn-success"><i class="fa fa-pencil-square-o"></i> Update</button>
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