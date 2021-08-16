<?php
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
	$productIdFromProcat=array();
	$categoryIdFromProcat=array(); 
	$subCategoryIdFromProcat=array();
	$delete=xssClean(mres(trim($_GET['delete'])));
	function hasParentCategories($id)
	{
		$count=mysql_num_rows(mysql_query("SELECT * FROM `categories` WHERE parentId='0' AND `id`!='$id'"));
		return $count;
	}
	if(isset($_GET['delete']) && hasParentCategories($_GET['delete']))
	{
		
		$sql = mysql_query("SELECT `cid` FROM `procat` WHERE `cid`='$delete'");
		while($cid=mysql_fetch_array($sql))
		{
			array_push($categoryIdFromProcat,$cid['cid']);
		}
		if(isParentCategoryById($delete)){
		$sql2 = mysql_query("SELECT `pid` FROM `procat` WHERE `cid` IN (SELECT `id` FROM `categories` WHERE `parentId`='$delete') OR `cid`='$delete'");
		} else {
		$sql2 = mysql_query("SELECT `pid` FROM `procat` WHERE `cid`='$delete'");
		}
		while($pid=mysql_fetch_array($sql2))
		{
			array_push($productIdFromProcat,$pid['pid']);
		}
		$query = mysql_query("SELECT `id` FROM `categories` WHERE `parentId`='$delete'");
		while($fetch3=mysql_fetch_array($query))
		{
			$sql3 = mysql_query("SELECT `cid` FROM `procat` WHERE `cid`=" . $fetch3['id']);
			while($sub_cid=mysql_fetch_array($sql3))
			{
				array_push($subCategoryIdFromProcat,$sub_cid['cid']);
			}
		}
	}
	$cats = "'". implode("', '", $categoryIdFromProcat) ."'";
	$subCats = "'". implode("', '", $subCategoryIdFromProcat) ."'";
	$pids = "'". implode("', '", $productIdFromProcat) ."'";
	function deleteCategoryName($id){
		mysql_query("DELETE FROM `categories` WHERE `id`='$id'");
	}
	function hasChildCategories($id){
		$count=mysql_num_rows(mysql_query("SELECT * FROM `categories` WHERE `parentId`='$id'"));
		if($count >0)
			return true;
		else
			return false;
	}
	function categoryDelete($id)
	{
		if(isParentCategoryById($id))
		{
		clearRecentCache($id);
		clearCategorycache($id);
		}
		else
		{
		$id=parentIdByChildId($id);
		clearRecentCache($id);
		clearCategorycache($id);
		}
		if(isParentCategoryById($id)) {
		$query=mysql_query("SELECT * FROM products WHERE `id` IN(SELECT `pid` FROM `procat` WHERE `cid` IN(SELECT `id` FROM categories WHERE parentId='$id') OR cid='$id')");
		} else {
		$query=mysql_query("SELECT * FROM products WHERE `id` IN(SELECT `pid` FROM `procat` WHERE `cid` ='$id')");
		}
		$countResults=mysql_num_rows($query);
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
		}
		mysql_query("DELETE FROM `procat` WHERE `cid` IN(SELECT id FROM `categories` WHERE parentId='$id')");
		mysql_query("DELETE FROM `categories` WHERE `id`='$id'");
		mysql_query("DELETE FROM `ratings` WHERE `id`='$id'");
	}
	function categoryUpdate($parentId,$categoryAssignTo)
	{
		clearRecentCache($parentId);
		clearCategorycache($parentId);
		if(isParentCategoryById($categoryAssignTo))
		{
		clearRecentCache($categoryAssignTo);
		clearCategorycache($categoryAssignTo);
		}
		else
		{
		$id=parentIdByChildId($categoryAssignTo);
		clearRecentCache($id);
		clearCategorycache($id);
		}
		$sql = mysql_query("SELECT `id` FROM `categories` WHERE `parentId`='$parentId'");
		while($fetch=mysql_fetch_array($sql))
		{
			$subId=$fetch['id'];
			$sub = mysql_query("update `categories` SET `parentId`='$categoryAssignTo'  WHERE `id`='$subId'");
		}
	}
	if(isset($_POST['submit'])) 
	{
		$status=$_POST['status'];
		
		if($status=='yes')
		{
			categoryDelete($delete); 
		}
		else
		{
			$productAssignTo=xssClean(mres(trim($_POST['parent'])));
			if(isParentCategoryById($productAssignTo))
			{
			clearRecentCache($productAssignTo);
			clearCategorycache($productAssignTo);
			}
			else
			{
			$id=parentIdByChildId($productAssignTo);
			clearRecentCache($id);
			clearCategorycache($id);
			}
			mysql_query("UPDATE `procat` SET `cid`='".$productAssignTo."' WHERE `pid` IN ($pids) AND NOT EXISTS (SELECT * FROM `procat` WHERE `cid`='".$productAssignTo."' AND `pid` IN ($pids))");
			deleteCategoryName($delete); 
		}
		if($_POST['sub-cat-pro']=='yes')
		{
			mysql_query("DELETE FROM `procat` WHERE `cid` IN ($subCats)");
			$sql = mysql_query("SELECT `id` FROM `categories` WHERE `parentId`=" . $_GET['delete']);
			while($fetch=mysql_fetch_array($sql))
			{
				$subId=$fetch['id'];
				$sub = mysql_query("DELETE FROM `categories` WHERE `id`='$subId'");
			}  
		}
		if($_POST['sub-cat'])
		{ 
			
			$categoryAssignTo=xssClean(mres(trim($_POST['sub-cat'])));
			categoryUpdate($delete,$categoryAssignTo); 
		}
header('Location: categories.php');
	}
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!-- TemplateBeginEditable name="doctitle" -->			
<title>Delete Category: <?php echo(getTitle()) ?></title>
<script type="text/javascript">
	$( document ).ready(function() {
	$( "#deletecategory" ).hide();
	});
</script>
<script type="text/javascript">
	$( document ).ready(function() 
	{
		$("input[name='status']").change(function(e)
		{
			if($(this).val() == 'yes') 
				$('#parent').attr('disabled', 'disabled');
			else
				$('#parent').removeAttr('disabled');
		});
		$("input[name='sub-cat-pro']").change(function(e){
		if($(this).val() == 'yes') 
			$('#sub-cat').attr('disabled', 'disabled');
		else
			$('#sub-cat').removeAttr('disabled');
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
	<h2><i class="fa fa-desktop color"></i> Delete Category</h2> 
</div>  
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Are You Sure ?</h3>
			</div>
			<div class="panel-body">
				<form class="form-horizontal" role="form" action="category_delete.php?delete=<?php echo $_GET['delete']; ?>" method="post">
					<div class="form-group">
						<div class="col-lg-10">
							<input type="hidden" class="form-control" name="id" value="<?php echo $id;  ?>"  />
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Also Delete It's Products</label>
						<div class="col-lg-8">			  
							<div class="radio"> 
								<label><input type="radio" name="status" value="yes" /> Yes</label><br />	  
								<label> <input type="radio" name="status" value="no" checked /> No </label>
							</div>								
						</div>
					</div>
					<div class="form-group">
						<label class="col-lg-3 control-label">Assign It's Products to</label>
						<div class="col-lg-5">
							<select class="form-control" id="parent" name="parent">
								<?php
								$result=mysql_query("SELECT * FROM `categories` WHERE `id` !='$delete'");
								while($row=mysql_fetch_array($result))
								{
									echo('
									</option><option value="' . $row['id'] . '" />  '.$row['english'].'<br />');
								}
								?>
							</select>
						</div>
					</div> 
					<?php
					if(isset($_GET['delete']))
					{
						if(hasChildCategories($_GET['delete']))
						{
							?>
							<div class="form-group">
								<label class="col-lg-3 control-label">Also Delete It's Sub Categories</label> 
								<div class="col-lg-8">			  
									<div class="radio"> 
										<label><input type="radio" name="sub-cat-pro" value="yes" /> Yes</label><br />	  
										<label> <input type="radio" name="sub-cat-pro" value="no" checked /> No </label>
									</div>								
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-3 control-label">Assign It's Sub Categories to</label>
								<div class="col-lg-5">
									<select class="form-control" id="sub-cat" name="sub-cat">
									<?php 
									$match = "SELECT `id`,`english` FROM `categories` WHERE `id` !='$delete'"; 
									$qry = mysql_query($match);
									$num_rows = mysql_num_rows($qry);
									if ($num_rows > 0) 
									{
										while($rowx = mysql_fetch_array($qry)) 
										{
											echo('<option value="' . $rowx["id"] . '">' . $rowx["english"] . '</option>'); 
										}
									}
									?>
									</select>
								</div>
							</div>
							<?php
						}
					}
					?>
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