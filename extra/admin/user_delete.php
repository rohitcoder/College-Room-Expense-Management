<?php
error_reporting(0);
if(!isset($_SESSION)) 
session_start();
ob_start();
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
	$isDeleted = false;
	function deleteUserWithProduct($uid)
	{
		$query=mysql_query("SELECT * FROM products WHERE `userId`='$uid'");
		while($row=mysql_fetch_array($query)){
			unlink('../images/productImages/'.$row['image']);
			unlink('../images/productImages/_'.$row['image']);
			unlink('../images/productImages/'.str_replace('-img','',$row['image']));
			unlink('../images/productImages/featuredImagesBackUp/_'.$row['image']);
			unlink('../images/productImages/thumbnailsBackUp/'.$row['image1']);
			unlink('../images/productImages/thumb1/'.$row['image1']);	
			unlink('../images/productImages/thumb2/'.$row['image1']);
			unlink('../images/productImages/thumb3/'.$row['image1']);
			unlink('../images/productImages/thumb1/'.$row['image2']);	
			unlink('../images/productImages/thumb2/'.$row['image2']);
			unlink('../images/productImages/thumb3/'.$row['image2']);
			unlink('../images/productImages/thumb1/'.$row['image3']);	
			unlink('../images/productImages/thumb2/'.$row['image3']);
			unlink('../images/productImages/thumb3/'.$row['image3']);
			unlink('../images/productImages/thumb1/'.$row['image4']);	
			unlink('../images/productImages/thumb2/'.$row['image4']);
			unlink('../images/productImages/thumb3/'.$row['image4']);
			unlink('../images/productImages/thumb1/'.$row['image5']);	
			unlink('../images/productImages/thumb2/'.$row['image5']);
			unlink('../images/productImages/thumb3/'.$row['image5']);
			mysql_query("DELETE FROM `products` WHERE `id`=".$row['id']);
			mysql_query("DELETE FROM `procat` WHERE `pid`=".$row['id']);
			mysql_query("DELETE FROM `ratings` WHERE `id`=".$row['id']);
		}
	}
	function deleteUserWithArticle($uid)
	{
		$query=mysql_query("SELECT * FROM articles WHERE `uid`='$uid'");
		while($row=mysql_fetch_array($query)){
			unlink('../images/articleImages/'.$row['image']);
			unlink('../images/articleImages/_'.$row['image']);
			$filename=str_replace('-img','',$row['image']);
			unlink('../images/articleImages/'.$filename);
			unlink('../images/articleImages/articleImagesBackUp/'.$filename);
			mysql_query("DELETE FROM `articleRating` WHERE `id`=".$row['id']);
		}
		mysql_query("DELETE FROM `articles` WHERE `uid`='$uid'");
	}
	if(isset($_POST['submit'])) 
	{   $uid=xssClean(mres(trim($_GET['uid'])));
		$deleteProductsStatus=xssClean(mres(trim($_POST['deleteProducts'])));
		if($deleteProductsStatus=='1')
		{
			deleteUserWithProduct($uid); 
		}
		else
		{
			$productAssignTo=xssClean(mres(trim($_POST['assignProduct'])));
			mysql_query("UPDATE `products` SET `userId`='$productAssignTo' WHERE `userId`='$uid'") or die(mysql_error());
		}
		$deleteArticlesStatus=$_POST['deleteArticles'];
		if($deleteArticlesStatus=='1')
		{
			deleteUserWithArticle($uid); 
		}
		else
		{
			$articleAssignTo=xssClean(mres(trim($_POST['assignArticle'])));
			mysql_query("UPDATE `articles` SET `uid`='$articleAssignTo' WHERE `uid`='$uid'") or die(mysql_error());
		}
		header('Location: '.rootpath().'/admin/users.php');
	}
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- TemplateBeginEditable name="doctitle" -->			
<title>Delete User: <?php echo(getTitle()) ?></title>
<script type="text/javascript">
	$( document ).ready(function() {
	$( "#deletecategory" ).hide();
	});
</script>
<script type="text/javascript">
	$( document ).ready(function() 
	{
		$("input[name='deleteProducts']").change(function(e)
		{
			if($(this).val() == '1') 
				$("#assignProduct").attr('disabled', 'disabled');
			else
				$("#assignProduct").removeAttr('disabled');
		});
		$("input[name='deleteArticles']").change(function(e)
		{
			if($(this).val() == '1') 
				$("#assignArticle").attr('disabled', 'disabled');
			else
				$("#assignArticle").removeAttr('disabled');
		});
	});
</script>
</head>
<body>
<div class="page-content blocky">
<div class="container" style="margin-top:20px;">
<?php include 'common/sidebar.php'; ?>
<div class="mainy">
<div class="page-title">
	<h2><i class="fa fa-user color"></i> Delete User</h2> 
</div>  
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Are You Sure ?</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" role="form" method="post">
					<div class="form-group">
						<label class="col-lg-3 control-label">Also Delete His Products</label> 
						<div class="col-lg-8">			  
							<div class="radio"> 
								<label><input type="radio" name="deleteProducts" value="1" /> Yes</label><br />	  
								<label> <input type="radio" name="deleteProducts" value="0" checked /> No </label>
							</div>								
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Assign His Product's to</label>
						<div class="col-lg-5">
							<select class="form-control" name="assignProduct" id="assignProduct">
							<?php 
							$uid=xssClean(mres(trim($_GET['uid'])));
							$match = "SELECT * FROM `user` WHERE `id` !='$uid'"; 
							$qry = mysql_query($match);
							$num_rows = mysql_num_rows($qry);
							if ($num_rows > 0) 
							{
								while($rowx = mysql_fetch_array($qry)) 
								{
									echo('<option value="' . $rowx["id"] . '">' . $rowx["username"] . '</option>'); 
								}
							}
							?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Also Delete His Articles</label> 
						<div class="col-lg-8">			  
							<div class="radio"> 
								<label><input type="radio" name="deleteArticles" value="1" /> Yes</label><br />	  
								<label> <input type="radio" name="deleteArticles" value="0" checked /> No </label>
							</div>								
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Assign His Article's to</label>
						<div class="col-lg-5">
							<select class="form-control" name="assignArticle" id="assignArticle">
							<?php 
							$uid=xssClean(mres(trim($_GET['uid'])));
							$match = "SELECT * FROM `user` WHERE `id` !='$uid'"; 
							$qry = mysql_query($match);
							$num_rows = mysql_num_rows($qry);
							if ($num_rows > 0) 
							{
								while($rowx = mysql_fetch_array($qry)) 
								{
									echo('<option value="' . $rowx["id"] . '">' . $rowx["username"] . '</option>'); 
								}
							}
							?>
							</select>
						</div>
					</div>
					<hr />
					<div class="form-group">
						<div class="col-lg-offset-3 col-lg-10">
							<button type="submit" class="btn btn-success" name="submit"><i class="fa fa-save"></i> Save</button>
						</div>
					</div>
				</form>	
				<button class="notify-without-image" id="deletecategory"></button>				
			</div>
		</div>
	</div>
</div>		