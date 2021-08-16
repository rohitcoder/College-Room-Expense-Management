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
$session_id=$_SESSION['id'];
if($_SESSION['type'])
{
	include 'common/header.php';
	$error = "";
	function updateCacheSettings($recentCache,$recentExpTime,$categoryCache,$categoryExpTime,$tagsCache,$tagsExpTime,$relatedCache,$relatedExpTime,$productCache,$productExpTime,$articleCache,$articleExpTime)
	{
		$update_query = "UPDATE `cacheSettings` SET `recentCache`='$recentCache',`recentExpTime`='$recentExpTime',`categoryCache`='$categoryCache',`categoryExpTime`='$categoryExpTime', `tagsCache`='$tagsCache',`tagsExpTime`='$tagsExpTime',`relatedCache`='$relatedCache',`relatedExpTime`='$relatedExpTime',`productCache`='$productCache',`productExpTime`='$productExpTime',`articleCache`='$articleCache',`articleExpTime`='$articleExpTime'";
		mysql_query($update_query)or die(mysql_error());
	}
						
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
	{
	$recentExpTime =xssClean(mres(trim($_POST["recentExpTime"])));
	$categoryExpTime =xssClean(mres(trim($_POST["categoryExpTime"])));
	$tagsExpTime =xssClean(mres(trim($_POST["tagsExpTime"])));
	$relatedExpTime =xssClean(mres(trim($_POST["relatedExpTime"])));
	$productExpTime=xssClean(mres(trim($_POST['productExpTime'])));
	$articleCache=xssClean(mres(trim($_POST['articleCache'])));
	$articleExpTime=xssClean(mres(trim($_POST['articleExpTime'])));
	
	
	$recentCache = xssClean(mres(trim($_POST["recentCache"])));
	if($recentCache=='on')
		$recentCache=1;
	else
		$recentCache=0;
	$categoryCache = xssClean(mres(trim($_POST["categoryCache"])));
	if($categoryCache=='on')
		$categoryCache=1;
	else
		$categoryCache=0;
	
	$tagsCache = xssClean(mres(trim($_POST["tagsCache"])));
	if($tagsCache=='on')
		$tagsCache=1;
	else
		$tagsCache=0;
	
	$relatedCache = xssClean(mres(trim($_POST["relatedCache"])));
	if($relatedCache=='on')
		$relatedCache=1;
	else
		$relatedCache=0;
	
	$productCache=xssClean(mres(trim($_POST['productCache'])));
	if($productCache=='on')
		$productCache=1;
	else
		$productCache=0;
	
	$articleCache=xssClean(mres(trim($_POST['articleCache'])));
	if($articleCache=='on')
		$articleCache=1;
	else
		$articleCache=0;
	
	if($recentExpTime=="")
		$error1="Feild must not be empty";
	else if(!is_numeric($recentExpTime))
		$error1="Enter integer only";
	
	if($categoryExpTime=="")
		$error2="Feild must not be empty";
	else if(!is_numeric($categoryExpTime))
		$error2="Enter integer only";
	
	if($tagsExpTime=="")
		$error3="Feild must not be empty";
	else if(!is_numeric($tagsExpTime))
		$error3="Enter integer only";
	
	if($relatedExpTime=="")
		$error4="Feild must not be empty";
	else if(!is_numeric($relatedExpTime))
		$error4="Enter integer only";
	
	if($productExpTime=="")
		$error5="Feild must not be empty";
	else if(!is_numeric($productExpTime))
		$error5="Enter integer only";
	
	if($articleExpTime=="")
		$error6="Feild must not be empty";
	else if(!is_numeric($articleExpTime))
		$error6="Enter integer only";
	
	
	if($error1=="" && $error2=="" && $error3=="" && $error4=="" && $error5=="" && $error6=="")
	{
		updateCacheSettings($recentCache,$recentExpTime,$categoryCache,$categoryExpTime,$tagsCache,$tagsExpTime,$relatedCache,$relatedExpTime,$productCache,$productExpTime,$articleCache,$articleExpTime);
	}	
}

$qry = mysql_query("SELECT * FROM cacheSettings ");
while($fetch=mysql_fetch_array($qry))
{  
	$recentCache = $fetch["recentCache"];
	$recentExpTime = $fetch["recentExpTime"];
	$categoryCache = $fetch["categoryCache"];
	$categoryExpTime = $fetch["categoryExpTime"];
	$tagsCache = $fetch["tagsCache"];
	$tagsExpTime = $fetch["tagsExpTime"];
	$relatedCache = $fetch["relatedCache"];
	$relatedExpTime = $fetch["relatedExpTime"];
	$productCache = $fetch["productCache"];
	$productExpTime = $fetch["productExpTime"];
	$languageCache=$fetch["languageCache"];
	$languageExpTime=$fetch["languageExpTime"];
	$articleCache=$fetch["articleCache"];
	$articleExpTime=$fetch["articleExpTime"];
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
<title>Cache Settings : <?php echo(getTitle()) ?></title>
<script type="text/javascript">
$( document ).ready(function() {
	$( "#settings" ).hide();
});
</script>
</head>
<body>
<?php include 'common/navbar_admin.php'; ?>
<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h2><i class=" fa fa-barcode color"></i> Cache Settings</h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error1=="" && $error2=="" && $error3=="" && $error4=="" && $error5=="" && $error6==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Cache Settings Updated Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="awidget" style="padding: 15px;">
						<form class="" id="imageform" role="form" action="cache.php" method="post" enctype="multipart/form-data">
						
							<div class="form-group">
								<label class="col-lg-3 control-label"> Recent Cache Enable </label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
										<?php
										if ($recentCache) 
										{
											?> 
											<input type="checkbox" name="recentCache" checked>
											<?php
										} 
										else 
										{
											?>
											<input type="checkbox" name="recentCache">
											<?php
										}
										?>
									</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"> Recent Cache Expire Time </label>
								<div class="col-lg-8">
									<input type="text" placeholder="Enter Time in Seconds..." class="form-control" name="recentExpTime" value="<?php echo $recentExpTime;?>" />
									<?php
									if(isset($_POST) && $error1!="")
										echo('<span class="label label-danger">' . $error1 . '</span><br>');
									?>
									<small>(Enter Time In Seconds)</small>
								</div> 
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"> Category Cache Enable </label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
										<?php
										if ($categoryCache) 
										{
											?> 
											<input type="checkbox" name="categoryCache" checked>
											<?php
										} 
										else 
										{
											?>
											<input type="checkbox" name="categoryCache">
											<?php
										}
										?>
									</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label">Category Cache Expire Time</label>
								<div class="col-lg-8">
									<input type="text" placeholder="Enter Time in Seconds..." class="form-control" name="categoryExpTime" value="<?php echo $categoryExpTime;?>" />
									<?php
									if(isset($_POST) && $error2!="")
										echo('<span class="label label-danger">' . $error2 . '</span><br>');
									?>
									<small>(Enter Time In Seconds)</small>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"> Tags Cache Enable </label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
										<?php
										if ($tagsCache) 
										{
											?> 
											<input type="checkbox" name="tagsCache" checked>
											<?php
										} 
										else 
										{
											?>
											<input type="checkbox" name="tagsCache">
											<?php
										}
										?>
									</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"> Tags Cache Expire Time</label>
								<div class="col-lg-8">
									<input type="text" placeholder="Enter Time in Seconds..." class="form-control" name="tagsExpTime" value="<?php echo $tagsExpTime;?>" />
									<?php
									if(isset($_POST) && $error3!="")
										echo('<span class="label label-danger">' . $error3 . '</span><br>');
									?>
									<small>(Enter Time In Seconds)</small>
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"> Related Cache Enable </label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
										<?php
										if ($relatedCache) 
										{
											?> 
											<input type="checkbox" name="relatedCache" checked>
											<?php
										} 
										else 
										{
											?>
											<input type="checkbox" name="relatedCache">
											<?php
										}
										?>
									</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"> Related Cache Expire Time </label>
								<div class="col-lg-8">
									<input type="text" class="form-control" placeholder="Enter Time in Seconds..." name="relatedExpTime" value="<?php echo $relatedExpTime;?>" />
									<?php
									if(isset($_POST) && $error4!="")
										echo('<span class="label label-danger">' . $error4 . '</span><br>');
									?>
									<small>(Enter Time In Seconds)</small>
								</div> 
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"> Product Cache Enable </label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
										<?php
										if ($productCache) 
										{
											?> 
											<input type="checkbox" name="productCache" checked>
											<?php
										} 
										else 
										{
											?>
											<input type="checkbox" name="productCache">
											<?php
										}
										?>
									</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"> Product Cache Expire Time </label>
								<div class="col-lg-8">
									<input type="text" class="form-control" placeholder="Enter Time in Seconds..." name="productExpTime" value="<?php echo $productExpTime;?>" />
									<?php
									if(isset($_POST) && $error5!="")
										echo('<span class="label label-danger">' . $error5 . '</span><br>');
									?>
									<small>(Enter Time In Seconds)</small>
								</div> 
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"> Article Cache Enable </label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
										<?php
										if ($articleCache) 
										{
											?> 
											<input type="checkbox" name="articleCache" checked>
											<?php
										} 
										else 
										{
											?>
											<input type="checkbox" name="articleCache">
											<?php
										}
										?>
									</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label"> Article Cache Expire Time </label>
								<div class="col-lg-8">
									<input type="text" class="form-control" placeholder="Enter Time in Seconds..." name="articleExpTime" value="<?php echo $articleExpTime;?>" />
									<?php
									if(isset($_POST) && $error6!="")
										echo('<span class="label label-danger">' . $error6 . '</span><br>');
									?>
									<small>(Enter Time In Seconds)</small>
								</div> 
							</div>
							<hr/>
							<div class="form-group">
								<div class="col-lg-offset-3 col-lg-9">
									<button type="submit" class="btn btn-success" name="submit"><i class="fa fa-pencil-square-o"></i> Update</button>
								</div>
							</div>
						</form>
					</div><!-- Awidget -->
				</div><!-- col-md-12 -->
			</div><!-- row -->
		</div><!-- mainy -->
		<div class="clearfix"></div> 
	</div><!-- container -->
</div>
<script src="style/switch/build/js/bootstrap-switch.min.js"></script> 
<script src="style/switch/docs/index.js"></script>
<?php include 'common/footer.php';
}
?>