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
	$error = "";
	function categoryUpdate($parent,$id,$permalink,$languageName,$name,$description,$keywords,$status,$limit)
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
		if($name==""){
			mysql_query("UPDATE `categories` SET `parentId` ='$parent',`id`='$id', `permalink`='$permalink',`description`='$description',`keywords`='$keywords',`status`='$status',`limit`='$limit' WHERE `id`='$id'");
		} else {
			mysql_query("UPDATE `categories` SET `parentId` ='$parent',`id`='$id', `permalink`='$permalink',`$languageName`='$name',`description`='$description',`keywords`='$keywords',`status`='$status',`limit`='$limit' WHERE `id`='$id'");
		}
	}
	if(isset($_GET["id"]))
	{
		$id = xssClean(mres(trim($_GET["id"])));
	}
	if(isset($_POST['submit']))
	{
		$id = xssClean(mres(trim($_POST["id"])));
		
		$name = xssClean(mres(trim(ucwords($_POST["name"]))));
		
		$parent = xssClean(mres(trim($_POST["parent"])));
		
		$limit=xssClean(mres(trim($_POST['limit'])));
		
		$permalink=xssClean(mres(trim($_POST["permalink"])));
		
		$oldPermalink=xssClean(mres(trim($_POST["oldPermalink"])));
		
		if(isValidCategory($permalink))
		$error ="Category Name already exist<br />";
		
		$description=xssClean(mres(trim(strip_tags($_POST['description']))));
				
		if($description=="")
		{
			$error0='enter description here';
		}
		
		$keywords=xssClean(mres(trim($_POST['keywords'])));
		
		$status=$_POST['status'];
		
		if($parent==0){
		if($status=='on')
			$status=1;
		else
			$status=0;
		if($limit=="")
			$error0='Enter Limit';
		else if(!is_numeric($limit))
			$error1='Enter Valid Limit';
		} else {
			$status=1;
			$limit=0;
		}
		if($permalink=="" && $name!="")
		{
			$permalink = genCategoryPermalink($name);
		}
		else if($permalink!="" && $permalink!=$oldPermalink)
		{
			$permalink = genCategoryPermalink($permalink);	
		}
		else
		{
			$permalink=$oldPermalink;
		}
		$languageName=xssClean(mres(trim($_POST['languageName'])));
		if($name=="")
			$error="";
		if($error=="" && $error0=="" && $error1=="")
		{
			categoryUpdate($parent,$id,$permalink,$languageName,$name,$description,$keywords,$status,$limit);
		}
	}
	$languageName=xssClean(mres(trim($_GET['language'])));
	$id=xssClean(mres(trim($_GET['id'])));
	$row = mysql_fetch_array(mysql_query("SELECT * FROM `categories` WHERE `id` ='$id'"));
		$id=$row['id'];
		$parent = $row['parentId'];
		$permalink=$row['permalink'];
		$name = $row[$languageName];
		$description=$row['description'];
		$keywords=$row['keywords'];
		$status=$row['status'];
		$limit=$row['limit'];
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Edit Category: <?php echo ($languageName ? $row[$languageName] : $row['english'])?></title>
	<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
	<script type="text/javascript">
	$( document ).ready(function() 
	{
		$( "#editcategory" ).hide();
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
			<h2><i class="fa fa-pencil-square-o color"></i> Edit Category </h2> 
			<hr />
			<?php if(isset($_POST['submit']) &&  $error=="" && $error0=="" && $error1==""){ ?>
			<div class="alert alert-success alert-dismissible" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<strong>Success !</strong> Category Updated Successfully!
			</div>
			<?php } ?>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="panel panel-default">
					<div class="panel-heading">
					   <h3 class="panel-title">Edit Category</h3>
					 </div>
					<div class="panel-body">
						<form class="form-horizontal" role="form" action="edit_category.php?id=<?php echo $id ; ?>" method="post">
										<div class="form-group">
											<div class="col-lg-10">
												<input type="hidden" class="form-control" name="id" value="<?php echo $id;?>"  />
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-2 control-label">Permalink</label>
											<div class="col-lg-5">
												<input type="text" class="form-control" name="permalink" value="<?php echo $permalink ;?>"  placeholder="Leave Empty To Auto Generate"  />
											</div>
										</div>
										<input type="hidden" name="oldPermalink" value="<?php echo $permalink?>">
										<div class="form-group">
											<label class="col-lg-2 control-label">Description</label>
											<div class="col-lg-5">
												<textarea class="form-control" rows="5" cols="5" id="description" name="description"><?php echo $description ?></textarea>
												<?php
												if(isset($_POST['submit']) && $error0!="")
													echo('<span class="label label-danger">' . $error0 . '</span>');
												?>										
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-2 control-label">Keywords</label>
											<div class="col-lg-5">
												<textarea class="form-control" rows="5" cols="5" id="keyword" name="keywords"><?php echo $keywords ?></textarea>     
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-2 control-label">Parent Category</label>
											<div class="col-lg-5">
												<select class="form-control selectCategory" name="parent">
													<?php 
													$qry = mysql_query("SELECT `id`,`english` FROM `categories` WHERE `parentId`=0 AND id!='$id'");
													$num_rows = mysql_num_rows($qry);
													if ($num_rows > 0) 
													{
														echo('<option value="0">None</option>');
														while($rowx = mysql_fetch_array($qry)) 
														{
														if($parent==$rowx["id"])
															echo('<option value="' . $rowx["id"] . '" selected>' . $rowx["english"] . '</option>');
														else
															echo('<option value="' . $rowx["id"] . '">' . $rowx["english"] . '</option>'); 
														}
													}
													else
													{
														echo('<option value="0">None</option>');
													}
													?>
												</select>
											</div>
										</div> 
										<div class="form-group limit" <?php echo (!isParentCategory(xssClean(mres(trim($_GET["id"])))) ? 'style="display:none"' : '')?>>
											<label class="col-lg-2 control-label">Limit</label>
											<div class="col-lg-5">
												<input type="text" class="form-control" name="limit" value="<?php echo $limit?>" placeholder="Enter Product Limit" />
												<?php
													if(isset($_POST['submit']) && $error1!="")
													echo('<span class="label label-danger">' . $error1 . '</span>');
												?>
											</div>
										</div>
										<div class="form-group">
											<label class="col-lg-2 control-label">Select Language</label>
											<div class="col-lg-5">
											<select class="form-control" name="languageName" onchange="self.location='<?php echo basename($_SERVER['PHP_SELF']); ?>?id=<?php echo $_GET['id']; ?>&language='+this.options[this.selectedIndex].value">
											<?php
											if(isset($_GET['language'])) {
												$language=xssClean(mres(trim($_GET['language'])));
												$query=mysql_query("SELECT * FROM `languages` WHERE `languageName`!='$language' AND `status`='1' ORDER BY `displayOrder`");
												?><option value="<?php echo $language?>"><?php echo ucfirst($language)?></option><?php
												while($fetch=mysql_fetch_array($query))
												{
												?><option value="<?php echo $fetch['languageName']?>"><?php echo ucfirst($fetch['languageName'])?></option><?php
												}
											} else {
												?><option>Select Language To Translate Category Name</option><?php
												$query=mysql_query("SELECT * FROM `languages` WHERE `status`='1' ORDER BY `displayOrder`");
												while($fetch=mysql_fetch_array($query))
												{
												?><option value="<?php echo $fetch['languageName']?>"><?php echo ucfirst($fetch['languageName'])?></option><?php
												}
											}
											?>
											</select>
											</div>
										</div>
										<?php if(isset($_GET['language']) && trim($_GET['id'])!="") { ?>
										<div class="form-group">
											<label class="col-lg-2 control-label">Category Name</label>
											<div class="col-lg-5">
												<input type="text" class="form-control" name="name" value="<?php echo $name;?>" placeholder="Category Name" required />
											</div>
										</div>
										<?php } ?>
										<div class="form-group checkBox" <?php echo (!isParentCategory(xssClean(mres(trim($_GET["id"])))) ? 'style="display:none"' : '')?>>
											<label class="col-lg-2 control-label">Featured</label>
												<input type="checkbox" name="status" <?php echo ($status==1 ? 'checked' : '')?> />
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
		<div class="clearfix"></div>  
	</div>
<script src="style/switch/build/js/bootstrap-switch.min.js"></script> 
<script src="style/switch/docs/index.js"></script>
<script type="text/javascript">
$( document ).ready(function() 
{
	var value=$(".selectCategory").val();
	if(value==0){
		$('.limit').show();
		$('.checkBox').show();
	}
});
</script>
 <script>
$(".selectCategory").change(function(){
	var value=$(".selectCategory").val();
	if(value==0){
		$('.limit').show();
		$('.checkBox').show();
	}
	else{
		$('.limit').hide();
		$('.checkBox').hide();
	}
});
 </script>
<link rel="stylesheet" type="text/css" href="style/css/TagsInput.css" />
<script src="style/js/TagsInput.js"></script>
<script type="text/javascript">
function onAddTag(tag) 
{
	alert("Added a tag: " + tag);
}
function onRemoveTag(tag) 
{
	alert("Removed a tag: " + tag);
}
function onChangeTag(input,tag) 
{
	alert("Changed a tag: " + tag);
}
$(function() {
	$('#keyword').tagsInput({width:'auto'});
});
</script>
	<?php include 'common/footer.php'; 
}
?>					