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
	<title>Add New Category : <?php echo(getTitle()) ?></title>
	<?php
	$error2 = "";
	function addArticleCategory($name,$permalink,$description,$keywords,$status,$displayOrder)
	{
		$name = mysql_real_escape_string($name);
		$permalink = mysql_real_escape_string($permalink);
		$description = mysql_real_escape_string($description);
		mysql_query("INSERT INTO `articleCategories`(`permalink`,`description`,`keywords`,`status`,`displayOrder`) VALUES('$permalink','$description','$keywords','$status','$displayOrder')") or die(mysql_error());
		$query=mysql_query("SELECT * FROM `languages`");
		$id=mysql_insert_id();
		while($fetch=mysql_fetch_array($query))
		{
			$language=$fetch['languageName'];
			mysql_query("UPDATE `articleCategories` SET `$language`='$name' WHERE `cid`='$id'");
		}
		clearArticlesCache();
		clearArticleCategoriesCache($id);
	}
	
	if(isset($_POST['submit']))
	{
		$name = xssClean(mres(strip_tags(trim($_POST["name"]))));
		if($name=="")
		{
			$error1 = "Enter Category Name!";
		}
		
		$permalink=xssClean(mres(trim($_POST['permalink'])));
		if(trim($permalink)=="")
			$permalink=genArticleCategoriesPermalink($name);
		else
			$permalink=genArticleCategoriesPermalink($permalink);
		
		$description=xssClean(mres(trim(strip_tags($_POST['description']))));
		
		if($description=="")
		{
			$error2 = "Enter Description!";
		}
		
		$keywords=xssClean(mres(trim(strip_tags($_POST['keywords']))));
		
		$status=$_POST['status'];
		
		$result = mysql_query("SELECT COUNT(displayOrder) FROM `articleCategories`");
		$row = mysql_fetch_array($result);
		$total = $row[0];
		$displayOrder=$total+1;
		
		if($error1=="" && $error2=="")
		{
			addArticleCategory($name,$permalink,$description,$keywords,$status,$displayOrder);
			?>
			<script>
			$(function() {
				$("input[name='name']").val("");
				$("input[name='permalink']").val("");
				document.getElementById('description').value = "";
				document.getElementById('keyword').value = "";
			});
			</script>
			<?php
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
				<h2><i class="fa fa-plus-square-o color"></i> Add New Category </h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error1=="" && $error2==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Category Added Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Add New Category</h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" action="./add_articleCategory.php" method="post">
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
								<div class="form-group">
									<label class="col-lg-2 control-label">Permalink</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="permalink" placeholder="permalink (optional)" value="<?php echo($permalink) ?>" maxlength="30" />
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
									<label class="col-lg-2 control-label">Action</label>
									<div class="col-lg-10">
											<div class="radio">
												<label>
													<input type="radio" name="status" value="1" checked>
													Publish
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="status" value="0">
													Pending
												</label>
											</div>
									</div>
								</div>
								<hr />
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button type="submit" name="submit" class="btn btn-success" value="Add"><i class="fa fa-plus-square-o"></i> Add</button>
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