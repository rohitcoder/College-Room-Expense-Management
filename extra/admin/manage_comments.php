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
function updateCommentSettings($productCommentStatus,$articleCommentStatus, $disqusUserName,$commentsActive,$fbCommentsLimit)
{
    mysql_query("UPDATE commentSettings SET `productCommentStatus`='$productCommentStatus',`articleCommentStatus`='$articleCommentStatus',disqusUserName='$disqusUserName',`commentsActive`='$commentsActive',`fbCommentsLimit`='$fbCommentsLimit'");
}
if (isset($_POST['submit'])) 
{
    $productCommentStatus = trim($_POST["productCommentStatus"]);
	$articleCommentStatus = trim($_POST["articleCommentStatus"]);
    $disqusUserName = xssClean(mres(trim($_POST["disqusUserName"])));
    if ($productCommentStatus == "on")
        $productCommentStatus = 1;
    else
        $productCommentStatus = 0;
	
	if ($articleCommentStatus == "on")
        $articleCommentStatus = 1;
    else
        $articleCommentStatus = 0;
	$commentsActive=xssClean(mres($_POST['commentsActive']));
	$fbCommentsLimit=xssClean(mres($_POST['fbCommentsLimit']));
	updateCommentSettings($productCommentStatus,$articleCommentStatus, $disqusUserName,$commentsActive,$fbCommentsLimit);
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
<title>Comments Settings : <?php echo (getTitle()); ?></title>
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
				<h2><a href="./manage_comments.php"><i class="fa fa-comment color"></i></a> Comment Settings </h2>
				<hr />
				<?php if(isset($_POST['submit'])){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Comments Settings Updated Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="awidget" style="padding: 15px;">
						<form class="form-horizontal" role="form" action="manage_comments.php" method="post">
							<div class="form-group">
								<label class="col-sm-3 control-label"> Product's Comments Status </label>
								<?php $comment=mysql_fetch_array(mysql_query("SELECT * FROM commentSettings"));?>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
									<?php
									if ($comment['productCommentStatus']) 
									{
										?> 
										<input type="checkbox" name="productCommentStatus" checked>
										<?php
									} 
									else 
									{
										?>
										<input type="checkbox" name="productCommentStatus" >
										<?php
									}
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"> Article's Comments Status </label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
									<?php
									if ($comment['articleCommentStatus']) 
									{
										?> 
										<input type="checkbox" name="articleCommentStatus" checked>
										<?php
									} 
									else 
									{
										?>
										<input type="checkbox" name="articleCommentStatus" >
										<?php
									}
									?>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label"> Comments In Use </label>
									<div style="margin-right: 8px">
											<input type="radio" class="radioCheck" name="commentsActive" value="1" <?php echo ($comment['commentsActive'] ? 'checked' : '')?>>
											Disqus
									</div>
									<div style="margin-right: 8px">
											<input type="radio" class="radioCheck" name="commentsActive" value="0" <?php echo (!$comment['commentsActive'] ? 'checked' : '')?>>
											Facebook
									</div>
							</div>
							<?php if($comment['commentsActive']) { ?>
							<div id="disqus">
								<div class="form-group">
									<label class="col-sm-3 control-label"> Disqus UserName </label>
									<div class="col-sm-4">
									<input type="text" class="form-control" name="disqusUserName" value="<?php echo $comment['disqusUserName']?>" required >
									</div>
								</div>
								<div class="form-group">
								<label class="col-sm-3 control-label"></label>
									<div class="col-sm-6">
										Get Your Disqus Username From Here <a href="https://disqus.com/admin/signup/?utm_source=New-Site">Add Disqus to your site</a> 
									</div>
								</div>
								<hr />
							</div>
							<div id="facebook" style="display:none">
								<div class="form-group">
									<label class="col-sm-3 control-label"> Facebook Comments Limit </label>
									<div class="col-sm-4">
									<input type="text" class="form-control" name="fbCommentsLimit" value="<?php echo $comment['fbCommentsLimit']?>" required >
									</div>
								</div>
								<hr />
							</div>
							<?php } else { ?>
							<div id="facebook">
								<div class="form-group">
									<label class="col-sm-3 control-label"> Facebook Comments Limit </label>
									<div class="col-sm-4">
									<input type="text" class="form-control" name="fbCommentsLimit" value="<?php echo $comment['fbCommentsLimit']?>" required >
									</div>
								</div>
								<hr />
							</div>
							<div id="disqus" style="display:none">
								<div class="form-group">
									<label class="col-sm-3 control-label"> Disqus UserName </label>
									<div class="col-sm-4">
									<input type="text" class="form-control" name="disqusUserName" value="<?php echo $comment['disqusUserName']?>" required >
									</div>
								</div>
								<div class="form-group">
								<label class="col-sm-3 control-label"></label>
									<div class="col-sm-6">
										Get Your Disqus Username From Here <a href="https://disqus.com/admin/signup/?utm_source=New-Site">Add Disqus to your site</a> 
									</div>
								</div>
								<hr />
							</div>
							<?php } ?>
							<div class="form-group">
								<div class="col-xs-12"> 
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