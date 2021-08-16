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
	include 'common/navbar_admin.php';
	$error = "";
	function update_publisher($productApproved,$productEdit,$productDelete,$articleApproved,$articleEdit,$articleDelete)
	{
		mysql_query("UPDATE `publisherRoles` SET `productApproved`='$productApproved',`productEdit`='$productEdit',`productDelete`='$productDelete',`articleApproved`='$articleApproved',`articleEdit`='$articleEdit',`articleDelete`='$articleDelete'")or die(mysql_error());
	} 
	if(isset($_POST['submit']))
	{
		$productApproved = xssClean(mres(trim($_POST["productApproved"])));
		if($productApproved=='on')
			$productApproved=1;
		else
			$productApproved=0;
		$productEdit = xssClean(mres(trim($_POST["productEdit"])));
		if($productEdit=='on')
			$productEdit=1;
		else
			$productEdit=0;
		$productDelete = xssClean(mres(trim($_POST["productDelete"])));
		if($productDelete=='on')
			$productDelete=1;
		else
			$productDelete=0;
			$articleApproved = xssClean(mres(trim($_POST["articleApproved"])));
		if($articleApproved=='on')
			$articleApproved=1;
		else
			$articleApproved=0;
		$articleEdit = xssClean(mres(trim($_POST["articleEdit"])));
		if($articleEdit=='on')
			$articleEdit=1;
		else
			$articleEdit=0;
		$articleDelete = xssClean(mres(trim($_POST["articleDelete"])));
		if($articleDelete=='on')
			$articleDelete=1;
		else
			$articleDelete=0;
		if($error=="")
		{
			update_publisher($productApproved,$productEdit,$productDelete,$articleApproved,$articleEdit,$articleDelete);
		}
	}
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
	<title>Publisher Roles : <?php echo(getTitle()) ?></title>
	<script type="text/javascript">
	$(document).ready(function() {
		$('#publisher_roles').hide(); 
	});
	</script>
	</head>
	<body>
	<div class="page-content blocky">
		<div class="container" style="margin-top:20px;">
			<?php include 'common/sidebar.php'; ?>
			<div class="mainy">
				<div class="page-title">
					<h2><i class="fa fa-group color"></i> Publisher Roles </h2> 
					<?php
					if(isset($_POST['submit'])){
					?>
					<div class="alert alert-success alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						<strong>Success!</strong> Publisher Settings Updated Successfully.
					</div>
					<?php 
					} ?>
					<hr />
				</div>
				<div class="row">
					<div class="col-xs-12">
						<div class="awidget" style="padding: 25px 25px 0;">
							<form class="form-group" role="form" action="publisher_roles.php" method="post">
								
									<div class="form-group make-switch switches" data-on="primary" data-off="info">
									<div class="row">
										<label class="col-lg-4 control-label">Can Publisher Publish His Products ?</label>
										<div class="col-lg-7">
										<?php
										if(productApproved()) { ?>
											<input type="checkbox" name="productApproved" checked>
										<?php } else { ?>
											<input type="checkbox" name="productApproved">
										<?php } ?>
										</div>
									</div>
									</div>
									
									<div class="form-group make-switch switches" data-on="primary" data-off="info">
									<div class="row">
										<label class="col-lg-4 control-label">Can Publisher Edit His Products ?</label>
										<div class="col-lg-7">
										<?php
										if(productEdit()){  ?>
												<input type="checkbox" name="productEdit" checked>
										<?php } else { ?>
												<input type="checkbox" name="productEdit">
										<?php } ?> 
										</div>
									</div>
									</div>
									<div class="form-group make-switch switches" data-on="primary" data-off="info">
									<div class="row">
										<label class="col-lg-4 control-label">Can Publisher Delete His Products ?</label>
										<div class="col-lg-7">
										<?php
										if(productDelete()){ ?>
												<input type="checkbox" name="productDelete" checked>
										<?php } else { ?>
												<input type="checkbox" name="productDelete">
										<?php } ?>
										</div>
									</div>
									</div>
									<div class="form-group make-switch switches" data-on="primary" data-off="info">
									<div class="row">
										<label class="col-lg-4 control-label">Can Publisher Publish His Article ?</label>
										<div class="col-lg-7">
										<?php
										if(articleApproved()) { ?>
											<input type="checkbox" name="articleApproved" checked>
										<?php } else { ?>
											<input type="checkbox" name="articleApproved">
										<?php } ?>
										</div>
									</div>
									</div>
									
									<div class="form-group make-switch switches" data-on="primary" data-off="info">
									<div class="row">
										<label class="col-lg-4 control-label">Can Publisher Edit His Article ?</label>
										<div class="col-lg-7">
										<?php
										if(articleEdit()){  ?>
												<input type="checkbox" name="articleEdit" checked>
										<?php } else { ?>
												<input type="checkbox" name="articleEdit">
										<?php } ?> 
										</div>
									</div>
									</div>
									<div class="form-group make-switch switches" data-on="primary" data-off="info">
									<div class="row">
										<label class="col-lg-4 control-label">Can Publisher Delete His Article ?</label>
										<div class="col-lg-7">
										<?php
										if(articleDelete()){ ?>
												<input type="checkbox" name="articleDelete" checked>
										<?php } else { ?>
												<input type="checkbox" name="articleDelete">
										<?php } ?>
										</div>
									</div>
									</div>
								<hr />
								<div class="form-group">
									<div class="row">
									<div class="col-lg-12">
										<button type="submit" class="btn btn-success" name="submit"><i class="fa fa-pencil-square-o"></i> Update</button>
									</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="clearfix"></div> 
		</div>
	</div>
<script src="style/switch/build/js/bootstrap-switch.min.js"></script> 
<script src="style/switch/docs/index.js"></script>
	<?php include 'common/footer.php'; 
}
?>					