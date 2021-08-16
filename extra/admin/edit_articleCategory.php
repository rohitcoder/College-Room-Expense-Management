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
	<title>Edit Category : <?php echo(getTitle()) ?></title>
	<?php
	function updateArticleCategory($cid,$language,$name,$permalink,$description,$keywords,$status)
	{
		$name = mysql_real_escape_string($name);
		$description = mysql_real_escape_string($description);
		if($name==""){
		mysql_query("UPDATE `articleCategories` SET `permalink`='$permalink',`description`='$description',`keywords`='$keywords',`status`='$status' WHERE `cid`='$cid'") or die(mysql_error());
		} else {
		mysql_query("UPDATE `articleCategories` SET `permalink`='$permalink',`$language`='$name',`description`='$description',`keywords`='$keywords',`status`='$status' WHERE `cid`='$cid'") or die(mysql_error());
		}
		clearArticlesCache();
		clearArticleCategoriesCache($cid);
	}
	$cid=xssClean(mres(trim($_GET['cid'])));
	
	if(isset($_POST['submit']))
	{
		
		$name = xssClean(mres(ucfirst(strip_tags(trim($_POST["name"])))));
		if(isset($_GET['language']) && $name=="")
		{
			$error1 = "Enter Category Name!";
		}
		$permalink=xssClean(mres(trim($_POST['permalink'])));
		$oldpermalink=xssClean(mres(trim($_POST['oldpermalink'])));
		if(trim($permalink)=="" && $name!="")
			$permalink=genArticleCategoriesPermalink($name);
		else if($permalink!="" && $permalink!=$oldpermalink)
			$permalink=genArticleCategoriesPermalink($permalink);
		else
			$permalink=$oldpermalink;
			
		$description=xssClean(mres(trim(strip_tags($_POST['description']))));
		
		if($description=="")
		{
			$error2 = "Enter Description!";
		}
		
		$keywords=xssClean(mres(trim(strip_tags($_POST['keywords']))));
		
		$language=xssClean(mres(trim($_POST['languageName'])));
		
		$status=xssClean(mres(trim($_POST['status'])));
		
		if($error1=="" && $error2=="")
		{
			clearArticlesCache();
			clearArticleCategoriesCache(articleCategoryId($id));
			clearArticleCategoriesCache($cid);
			updateArticleCategory($cid,$language,$name,$permalink,$description,$keywords,$status);
		}
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
				<h2><i class="fa fa-pencil color"></i> Edit Category </h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error1=="" && $error2==""){ ?>
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
						<?php
						if(isset($_GET['language']))
						$language=xssClean(mres(trim($_GET['language'])));
						$fetch=mysql_fetch_array(mysql_query("SELECT * FROM `articleCategories` WHERE `cid`='$cid'"));
						$cid=$fetch['cid'];
						$name=$fetch[$language];
						$permalink=$fetch['permalink'];
						$description=$fetch['description'];
						$keywords=$fetch['keywords'];
						$status=$fetch['status'];
						?>
						<div class="panel-body">
							<form class="form-horizontal" role="form" method="post">
								<div class="form-group">
									<label class="col-lg-2 control-label">Permalink</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="permalink" placeholder="permalink (optional)" value="<?php echo($permalink) ?>"/>
										<input type="hidden" class="form-control" name="oldpermalink" placeholder="permalink (optional)" value="<?php echo($permalink) ?>"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Description</label>
									<div class="col-lg-10">
										<textarea class="form-control" id="description" rows="5" cols="5" name="description" placeholder="add description"><?php echo $description ?></textarea>     
										<?php
										if(isset($_POST['submit']) && $error2!="")
											echo('<span class="label label-danger">' . $error2 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Keywords</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="5" cols="5" id="keyword" name="keywords"><?php echo $keywords ?></textarea>     
									</div>
								</div>
								<div class="form-group">
								<label class="col-lg-2 control-label">Language</label>
								<div class="col-lg-10">
									<select onchange="self.location='<?php echo basename($_SERVER['PHP_SELF']); ?>?cid=<?php echo $_GET['cid']; ?>&language='+this.options[this.selectedIndex].value"  class="form-control" name="languageName">
										<?php 
										if(isset($_GET['language']))
										{
											$language=xssClean(mres(trim($_GET['language'])));
											$mysql = mysql_query("SELECT * FROM `languages` WHERE languageName!='$language'");
											?>
											<option value="<?php echo $language?>"><?php echo ucfirst($language)?></option>
											<?php
											while($row = mysql_fetch_array($mysql))
											{
												?>
												<option value="<?php echo $row['languageName']; ?>"><?php echo ucfirst($row['languageName']); ?></option>
												<?php
											}
										}
										else
										{
										?><option>Select Language</option><?php
											$mysql = mysql_query("SELECT * FROM `languages`");
											while($row = mysql_fetch_array($mysql))
											{
												?>
												<option value="<?php echo $row['languageName']; ?>"><?php echo ucfirst($row['languageName']); ?></option>
												<?php
											}
										}
										?>
									</select>
									</div>
								</div>
								<?php
								if(isset($_GET['language']))
								{
									?>
								<div class="form-group">
									<label class="col-lg-2 control-label">Name</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="name" placeholder="name" value="<?php echo($name) ?>" required />
										<?php
										if(isset($_POST['submit']) && $error1!="")
											echo('<span class="label label-danger">' . $error1 . '</span>');
										?>
									</div>
								</div>
								<?php } ?>
								<div class="form-group">
									<label class="col-lg-2 control-label">Action</label>
									<div class="col-lg-10">
											<div class="radio">
												<label>
													<input type="radio" name="status" value="1" <?php echo ($status==1 ? 'checked' : '')?>>
													Publish
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="status" value="0" <?php echo ($status==0 ? 'checked' : '')?>>
													Pending
												</label>
											</div>
									</div>
								</div>
								<hr />
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button type="submit" name="submit" class="btn btn-success" value="Add"><i class="fa fa-pencil"></i> Update</button>
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
<link rel="stylesheet" type="text/css" href="style/css/TagsInput.css" />
<script src="style/js/TagsInput.js"></script>
<script src="style/js/froala_editor.min.js"></script>
<script>
	$(function(){
		$('#contents').editable({inlineMode: false, alwaysBlank: true})
	});
</script>
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