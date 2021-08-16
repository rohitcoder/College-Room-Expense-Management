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
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Api Settings : <?php echo(getTitle()) ?></title>
	<?php
	$error2 = "";
	function updateApp($appId,$appSecret,$consumerKey,$consumerSecret,$allow)
	{
		mysql_query("UPDATE `apiSettings` SET `appId`='$appId',`appSecret`='$appSecret',`consumerKey`='$consumerKey',`consumerSecret`='$consumerSecret',`allow`='$allow'") or die(mysql_error());
	}
	if(isset($_POST['submit']))
	{
		$appId=xssClean(mres(trim($_POST['appId'])));
		$appSecret = xssClean(mres(trim($_POST["appSecret"])));
		$consumerKey=xssClean(mres(trim($_POST['consumerKey'])));
		$consumerSecret=xssClean(mres(trim($_POST['consumerSecret'])));
		$allow=xssClean(mres(trim($_POST['allow'])));
		updateApp($appId,$appSecret,$consumerKey,$consumerSecret,$allow);
	}
	?>
	</head>
	<body>
	<?php include 'common/navbar_admin.php'; ?>
	<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h2><i class="fa fa-cogs color"></i> Api Settings </h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error1=="" && $error2==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Settings Updates Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Api Settings</h3>
						</div>
						<?php
						$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `apiSettings`"));
						$appId=$fetch['appId'];
						$appSecret=$fetch['appSecret'];
						$consumerKey=$fetch['consumerKey'];
						$consumerSecret=$fetch['consumerSecret'];
						$allow=$fetch['allow'];
						?>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="post">
								<div class="form-group">
									<label class="col-lg-2 control-label">Facebook App Id</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="appId" placeholder="Facebook App Id" value="<?php echo($appId) ?>" required />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Facebook App Secret</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="appSecret" placeholder="Facebook App Secret" value="<?php echo($appSecret) ?>" required/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Twitter Consumer Key</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="consumerKey" placeholder="Twitter Consumer Key" value="<?php echo($consumerKey) ?>" required />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Twitter Consumer Key Secret</label>
									<div class="col-lg-6">
										<input type="text" class="form-control" name="consumerSecret" placeholder="Twitter Consumer Key Secret" value="<?php echo($consumerSecret) ?>" required/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Allow?</label>
									<div class="col-lg-10">
											<div class="radio">
												<label>
													<input type="radio" name="allow" value="1" <?php echo ($allow==1 ? 'checked' : '')?>>
													Facebook
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="allow" value="2" <?php echo ($allow==2 ? 'checked' : '')?>>
													Twitter
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="allow" value="3" <?php echo ($allow==3 ? 'checked' : '')?>>
													Both
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="allow" value="0" <?php echo ($allow==0 ? 'checked' : '')?>>
													None
												</label>
											</div>
									</div>
								</div>
								<hr />
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button type="submit" name="submit" class="btn btn-success" value="Add"><i class="fa fa-pencil-square-o"></i> Update</button>
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