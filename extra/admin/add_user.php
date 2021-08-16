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
$error1=="";
$error2 = "";
function addUser($name,$password,$email,$type)
{
	$name = mysql_real_escape_string($name);
	$email = mysql_real_escape_string($email);
	$password=MD5($_POST["password"]);
	$sql = "INSERT INTO user (username,password,email,type) VALUES('$name','$password','$email','$type')";
	mysql_query($sql) or die(mysql_error());
}
if(isset($_POST['submit'])&& $_POST['submit']!="")
{
	$name = xssClean(mres(strip_tags(trim($_POST["username"]))));
	$userquery="select username from user";
	$qry = mysql_query($userquery);
	while($fetch= mysql_fetch_array($qry))
	{
		if($name==$fetch['username'])
		{
			$error1 .= "User Name Already Exist !";
		}	
	}
	if(!validUsername($name))
	{
		$error1 .= "User Name Only Contain Letter a-Z and numbers 0-9";
	}		
	$password = MD5($_POST["password"]);
	$email = xssClean(mres(trim($_POST["email"])));
	$eqry = mysql_query("select email from user");
	while($efetch= mysql_fetch_array($eqry))
	{ 
		if($email==$efetch['email'])
		{
			$error2 .= "Email Already Exist. Try another !";
		}
	}
	if(!checkEmail($email) )
	{
		$error2 .= "Please enter Valid Email";
	}
	$type = xssClean(mres(trim($_POST["type"])));
	if($error1=="" && $error2=="")
	{	
		addUser($name,$password,$email,$type);
		?>
		<script>
		$(function() {
			$("input[name='email']").val("");
			$("input[name='username']").val("");
		});
		</script>
		<?php
	}
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Add New User : <?php echo(getTitle()) ?></title>
</head>
<body>
<?php include 'common/navbar_admin.php'; ?>
<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h2><i class="fa fa-plus-square-o"></i> Add User </h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error1=="" && $error2==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> User Added Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">
								 Add New User    
							</h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" action="add_user.php" method="post">
								<div class="form-group">
									<label class="col-lg-2 control-label">User Name</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="username" placeholder="User Name" value="<?php echo $name?>" pattern=".{5,30}" title="5 to 30 characters"  required/>
										<?php
										if(isset($_POST['username']) && $error1!="")
											echo('<span class="label label-danger">' . $error1 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Password</label>
									<div class="col-lg-10">
										<input type="password" class="form-control" name="password" placeholder="Password" required />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Email</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" value="<?php echo $email?>" name="email" placeholder="Email ID" required />
										<?php
										if(isset($_POST['username']) && $error2!="")
											echo('<span class="label label-danger">' . $error2 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Type</label>
									<div class="col-lg-10">
										<select name="type" class="form-control" >
											<option value="2"  >Moderator</option>
											<option value="3"  >Publisher</option>
										</select>
									</div>
								</div>
								<hr />
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-5">
										<button type="submit" class="btn btn-success" name="submit" value="Add"><i class="fa fa-plus-square-o"></i> Add</button>
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
</div>
</div>
<?php include 'common/footer.php'; 
}
?>					