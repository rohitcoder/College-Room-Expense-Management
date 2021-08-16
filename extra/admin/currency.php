<?php
error_reporting(0);
if(!isset($_SESSION)) 
session_start();
if(!isset($_SESSION['admin_eap']) && !isset($_SESSION['id']) && !isset($_SESSION['type']) && !isset($_SESSION['username'])) {
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
	function updateCurrency($currencySymbol,$priceInDollor,$showBefore) 
	{
		mysql_query("UPDATE `currencySettings` SET `crName`='$currencySymbol',`priceDollor`='$priceInDollor',`showPlace`='$showBefore'") or die(mysql_error());
	}
	if(isset($_POST['submit'])) 
	{
		$currencySymbol = xssClean(mres(trim($_POST["currencySymbol"])));
		$priceInDollor = xssClean(mres(trim($_POST["priceInDollor"])));
		if($priceInDollor!="" && !validPrice($priceInDollor))
		$error ="Invalid Currency";
		$showBefore=xssClean(mres(trim($_POST["showBefore"])));
		if($error=="")
		{
			updateCurrency($currencySymbol,$priceInDollor,$showBefore);
		}
	}
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Currency Setting : <?php echo(getTitle()) ?></title>
	</head>
	<body>
	<div class="page-content blocky">
		<div class="container" style="margin-top:20px;">
			<?php include 'common/sidebar.php'; ?>
			<div class="mainy">
				<div class="page-title">
					<h2><i class="fa fa-money color"></i> Currency Settings</h2> 
					<hr />
				</div>
				<div class="row">
					<div class="col-lg-12"> 
					<div class="awidget" style="padding: 15px 25px">
						<form role="form" action="currency.php" method="post">
						<div class="row">
							<?php
							if(isset($_POST['currencySymbol']) && $error=="")
							{ 
								?>
								<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> <strong>Success !</strong> Currency Settings Updated Successfully!</div>
								<div class="form-group">
									<label class="col-lg-4 control-label">Currency Symbol</label>
									<div class="col-lg-6"><input type="text" class="form-control" name="currencySymbol" value="<?php echo $_POST['currencySymbol']; ?>" placeholder="Enter your currency name/symbol" required /></div>
								</div>
								</br></br>
								<div class="form-group">
									<label class="col-lg-4 control-label">One US Dollar = ??</label>
									<div class="col-lg-6"><input type="text" class="form-control" name="priceInDollor" value="<?php echo $_POST['priceInDollor']; ?>" placeholder="Your currency equal to how many dollars?" required />
									<?php
									if(isset($_POST) && $error!="")
										echo('<span class="label label-danger">' . $error . '</span>');
									?>
									</div>
								</div>
								</br></br>
								<div class="form-group">
									<div class="col-lg-4">
										<label class="control-label">Where to Show Currency Symbol?</label>
									</div>
									
									<div class="col-lg-8">
									<?php if($_POST['showBefore']) 
									{  
										?>
										<div class="radio">
											<label class="control-label"><input type="radio" name="showBefore" value="1" checked>Before Digit's</label>
										</div>
										<div class="radio">
											<label class="control-label"><input type="radio" name="showBefore" value="0">After Digit's</label>         
										</div>
										<?php 
									} 
									else 
									{ 
										?>
										<div class="radio">
											<label class="control-label"><input type="radio" name="showBefore" value="1">Before Digit's</label>
										</div>
										<div class="radio">
											<label class="control-label"><input type="radio" name="showBefore" value="0" checked>After Digit's</label>         
										</div>
										<?php 
									} 
									?> 
								</div> 
								</div> 
								<?php 
							} 
							else 
							{
								$array = mysql_fetch_array(mysql_query("SELECT * FROM `currencySettings`"));   
								$currencySymbol = $array['crName'];
								$priceInDollor = $array['priceDollor'];
								$showBefore = $array['showPlace'];
								?>
								<div class="form-group">
									<label class="col-lg-4 control-label">Currency Symbol</label>
									<div class="col-lg-6"><input type="text" class="form-control" name="currencySymbol" value="<?php echo $currencySymbol;?>" placeholder="Enter your currency name/symbol"  required /></div>
								</div>
								</br></br>
								<div class="form-group">
									<label class="col-lg-4 control-label">One US Dollar = ??</label>
									<div class="col-lg-6"><input type="text" class="form-control" name="priceInDollor" value="<?php echo $priceInDollor;?>" placeholder="Your currency equal to how many dollars?"  required />
									<?php
									if(isset($_POST) && $error!="")
										echo('<span class="label label-danger">' . $error . '</span>');
									?>
									</div>
								</div>
								</br></br>
								<div class="form-group">
									<div class="col-lg-4">
										<label class="control-label">Where to Show Currency Symbol?</label>
									</div>
									
									<div class="col-lg-8">
									<?php 
									if($showBefore) 
									{ 
										?>
										<div class="radio">
											<label class="control-label"><input type="radio" name="showBefore" value="1" checked>Before Digit's</label>
										</div>
										<div class="radio">
											<label class="control-label"><input type="radio" name="showBefore" value="0">After Digit's</label>         
										</div>		
										<?php 
									} 
									else 
									{ 
										?>
										<div class="radio">
											<label class="control-label"><input type="radio" name="showBefore" value="1">Before Digit's</label>
										</div>
										<div class="radio">
											<label class="control-label"><input type="radio" name="showBefore" value="0" checked>After Digit's</label>         
										</div>
										<?php 
									} 
									?>
									</div>
								</div>
								<?php 
							} 
							?>
							<div class="form-group">
								<div class="col-lg-12"><button name="submit" type="submit" class="btn btn-success"><i class="fa fa-pencil-square-o"></i> Update</button></div>
							</div>
							</div>
						</form>
					</div>	
				</div>
			</div>
			<div class="clearfix"></div> 
		</div><!-- container -->
	</div>	
	<?php include 'common/footer.php'; 
}
?>					