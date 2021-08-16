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
include "common/header.php";
$error = "";
function updateArticleSettings($name,$status)
{
    mysql_query("UPDATE `articleSettings` SET `name`='$name',`status`='$status'") or die(mysql_error());
}
if (isset($_POST['submit'])) 
{
    $name = xssClean(mres(trim($_POST["name"])));
	if(!preg_match("/^([a-zA-Z])+$/i", $name))
		$error='Invalid Name Only a-zA-Z Allow';
    $status = xssClean(mres(trim($_POST["status"])));
    if ($status == "on")
        $status = 1;
    else
        $status = 0;
	if($error=="")
	{
		updateArticleSettings($name,$status);
	}
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
<title>Article Settings : <?php echo (getTitle()); ?></title>
<script>
var _gaq = _gaq || [];
_gaq.push(['_setAccount', 'UA-43092768-1']);
_gaq.push(['_trackPageview']);
(function () 
{
	var ga = document.createElement('script');
	ga.type = 'text/javascript';
	ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0];
	s.parentNode.insertBefore(ga, s);
})();
</script>
</head>
<body>
<?php include 'common/navbar_admin.php'; ?>
<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h2><a href="./article_setting.php"><i class="fa fa-pencil color"></i></a> Article Settings </h2>
				<hr />
				<?php if(isset($_POST['submit']) && $error==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Article Settings Updated Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="awidget">
					<br><br>
						<form class="form-horizontal" role="form" action="article_setting.php" method="post">
							<div class="form-group">
								<label class="col-sm-3 control-label"> Enabled? </label>
								<?php $fetch=mysql_fetch_array(mysql_query("SELECT * FROM articleSettings"));?>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
									<?php
									if ($fetch['status']) 
									{
										?> 
										<input type="checkbox" name="status" checked>
										<?php
									} 
									else 
									{
										?>
										<input type="checkbox" name="status" >
										<?php
									}
									?>
								</div>
							</div>
								<div class="form-group">
									<label class="col-sm-3 control-label"> Permalink </label>
									<div class="col-sm-4">
									<input type="text" class="form-control" name="name" value="<?php echo $fetch['name']?>" required >
									<?php
									if(isset($_POST['submit']) && $error!="")
										echo('<span class="label label-danger">' . $error . '</span>');
									?>
									</div>
								</div>
								<hr />
							<div class="form-group">
								<div class="col-xs-offset-2 col-xs-8"> 
									<button type="submit" name="submit" class="btn btn-success" ><i class="fa fa-pencil-square-o"></i> Update</button>
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
<script src="style/switch/build/js/bootstrap-switch.min.js"></script> 
<script src="style/switch/docs/index.js"></script>
<script>
$('.radioCheck').click(function() {
   if($("input[name=commentsActive]").is(":checked"))
	   var value=$(this).val();
   if(value==1){
	   $('#facebook').hide();
	   $('#disqus').show();
   }
   else {
	   $('#facebook').show();
	   $('#disqus').hide();
   }
});
</script>
<?php include 'common/footer.php';  ?> 