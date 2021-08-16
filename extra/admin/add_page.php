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
	<title>Add New Page : <?php echo(getTitle()) ?></title>
	<link rel="stylesheet" type="text/css" href="style/css/froala_editor.min.css" />
	<link rel="stylesheet" type="text/css" href="style/css/froala_style.min.css" />
	<?php
	$error2 = "";
	function addPage($title,$permalink,$content,$description,$keywords,$status,$showIn,$displayOrder)
	{
		mysql_query("INSERT INTO pages(`permalink`,`keywords`,`status`,`showIn`,`displayOrder`) VALUES('$permalink','$keywords','$status','$showIn','$displayOrder')") or die(mysql_error());
		$query=mysql_query("SELECT * FROM `languages`");
		$id=mysql_insert_id();
		while($fetch=mysql_fetch_array($query))
		{
			$language=$fetch['languageName'];
			mysql_query("UPDATE `pages` SET `$language`='$title' WHERE `id`='$id'");
			mysql_query("INSERT INTO `pagesLanguage` (`id`,`language`,`content`,`description`) VALUES('$id','$language','$content','$description')") or die (mysql_error());
		}
	}
	
	if(isset($_POST['submit']))
	{
		$title = xssClean(mres(strip_tags(trim($_POST["title"]))));
		if($title=="")
		{
			$error1 .= "Enter Page Title!";
		}
		else if(pageNameAlreadyExist($title,$id))
		{
			$error1='Page Already Exists';
		}
		$content =mres(trim($_POST["content"]));
		$content=ucfirst(mysql_real_escape_string((strip_tags(trim($content), '<strong><ul>
					<br /><br><em><table><tbody><th><tr><td><img><p><ol><li><h1><h2><h3><h4><h5><a><blockquote><pre><hr>'))));
		if($content=="")
			$error2='Enter Page Contents';
		$description=xssClean(mres(trim(strip_tags($_POST['description']))));
		$keywords=xssClean(mres(trim($_POST['keywords'])));
		$permalink=xssClean(mres(trim($_POST["permalink"])));
		if($permalink!="")
			$permalink = genPagePermalink($permalink);
		else
			$permalink = genPagePermalink($title);

		$result = mysql_query("SELECT COUNT(displayOrder) FROM `pages`");
		$row = mysql_fetch_array($result);
		$total = $row[0];
		$displayOrder=$total+1;		
		$status = xssClean(mres(trim($_POST["status"])));
		$showIn=xssClean(mres(trim($_POST['showIn'])));
		if($error1=="" && $error2=="")
		{
			addPage($title,$permalink,$content,$description,$keywords,$status,$showIn,$displayOrder);
			?>
			<script>
			$(function() {
				$("input[name='title']").val("");
				$("input[name='permalink']").val("");
				document.getElementById('description').value = "";
				document.getElementById('contents').value = "";
				document.getElementById('keyword').value = "";
			});
			</script>
			<?php
		}
	}
	?>
	<script type="text/javascript">
	$( document ).ready(function() 
	{
		$( "#addpage" ).hide();
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
				<h2><i class="fa fa-plus-square-o color"></i> Add Page </h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error1=="" && $error2==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Page Added Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Add New Page</h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" action="./add_page.php" method="post">
								<div class="form-group">
									<label class="col-lg-2 control-label">Page Name</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="title" placeholder="Page Name" value="<?php echo($title) ?>" maxlength="30" required />
										<?php
										if(isset($_POST['title']) && $error1!="")
											echo('<span class="label label-danger">' . $error1 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Permalink</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="permalink" placeholder="Optional" value="<?php echo($permalink) ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Content</label>
									<div class="col-lg-10">
										<textarea class="form-control" id="contents" rows="5" cols="5" name="content"><?php echo $content?></textarea>
										<?php
										if(isset($_POST['content']) && $error2!="")
											echo('<span class="label label-danger">' . $error2 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Meta Description</label>
									<div class="col-lg-10">
										<textarea class="form-control" maxlength="160" id="description" rows="5" cols="5" name="description" placeholder="add description"><?php echo $description ?></textarea>     
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Meta Keywords</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="5" cols="5" id="keyword" name="keywords"><?php echo $keywords ?></textarea>     
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Status</label>
									<?php if(isset($_POST['submit'])){ ?>
									<div class="col-lg-10">
											<div class="radio">
												<label>
													<input type="radio" name="status" value="1" <?php echo ($status==1 ? 'checked' : '')?>>
													Published
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="status" value="0" <?php echo ($status==0 ? 'checked' : '')?>>
													Saved
												</label>
											</div>
									</div>
									<?php } else { ?>
									<div class="col-lg-10">
											<div class="radio">
												<label>
													<input type="radio" name="status" value="1" checked>
													Published
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="status" value="0">
													Saved
												</label>
											</div>
									</div>
									<?php } ?>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Placement</label>
									<div class="col-lg-10">
											<div class="radio">
												<label>
													<input type="radio" name="showIn" value="0" <?php echo ($showIn==0 ? 'checked' : '')?>>
													Footer
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="showIn" value="1" <?php echo ($showIn==1 ? 'checked' : '')?>>
													Header
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="showIn" value="2" <?php echo ($showIn==2 ? 'checked' : '')?>>
													Both
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