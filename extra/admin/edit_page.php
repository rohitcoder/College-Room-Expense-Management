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
	$error2 = "";
	function pageUpdate($id,$title,$permalink,$language,$content,$description,$keywords,$status,$showIn)
	{
		$title=ucwords($title);
		$content=ucfirst($content);
		if($title=="" && $content=="" && $description==""){
			mysql_query("UPDATE `pages` SET `permalink`='$permalink',`keywords`='$keywords',`status`='$status',`showIn`='$showIn' WHERE `id`='$id'");
		} else {
			mysql_query("UPDATE `pages` SET `permalink`='$permalink',`$language`='$title',`keywords`='$keywords',`status`='$status',`showIn`='$showIn' WHERE `id`='$id'");
			mysql_query("UPDATE `pagesLanguage` SET `content`='$content',`description`='$description' WHERE `id`='$id' AND `language`='$language'") or die(mysql_error());
		}
	}
	if(isset($_GET["id"]))
	{
		$id = $_GET["id"];
	}
	if(isset($_POST['submit']))
	{
		$title = mysql_real_escape_string(strip_tags(trim($_POST['title'])));
		if(pageNameAlreadyExist($title,$id))
		{
			$error='Page Already Exists';
		}
		$permalink=xssClean(mres(trim($_POST["permalink"])));
		$oldpermalink=xssClean(mres(trim($_POST["oldpermalink"])));
		if($permalink=="" && $title!="")
			$permalink = genPagePermalink($title);
		else if($permalink!="" && $permalink!=$oldpermalink)
			$permalink = genPagePermalink($permalink);
		else
			$permalink=$oldpermalink;
		$content=ucfirst(mysql_real_escape_string((strip_tags(trim($_POST["content"]), '<strong><ul>
					<br /><br><em><table><tbody><th><tr><td><img><p><ol><li><h1><h2><h3><h4><h5><a><blockquote><pre><hr>'))));
		$description=xssClean(mres(trim(strip_tags($_POST['description']))));
		$keywords=xssClean(mres(trim($_POST['keywords'])));
		$status=xssClean(mres(trim($_POST['status'])));
		$showIn=xssClean(mres(trim($_POST['showIn'])));
		$language=xssClean(mres(trim($_POST['languageName'])));
		if($error=="")  
		{
			pageUpdate($id,$title,$permalink,$language,$content,$description,$keywords,$status,$showIn);
		}
	}
			if(isset($_GET['language'])){
				$language=mysql_real_escape_string(trim($_GET['language']));
				$row = mysql_fetch_array(mysql_query("SELECT p.*,pl.* FROM `pages` p,`pagesLanguage` pl WHERE p.`id`='$id' AND pl.`language`='$language' AND p.`id`=pl.`id`"));
					$title = $row[$language];
			} else {
				$row = mysql_fetch_array(mysql_query("SELECT * FROM `pages` WHERE `id`='$id'"));
			}
			$id=$row['id'];	
			$permalink=$row['permalink'];
			$content=$row['content'];
			$description=$row['description'];
			$keywords=$row['keywords'];
			$status=$row['status'];	
			$showIn=$row['showIn'];
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Edit Page: <?php echo $title?></title>
	<link rel="stylesheet" type="text/css" href="style/css/froala_editor.min.css" />
	<link rel="stylesheet" type="text/css" href="style/css/froala_style.min.css" />
	<script type="text/javascript">
	$( document ).ready(function() 
	{
		$( "#editpage" ).hide();
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
					<h2><i class="fa fa-pencil-square-o color"></i> Edit Page </h2> 
					<hr />
					<?php if(isset($_POST['submit']) && $error==""){ ?>
						<div class="alert alert-success alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<strong>Success !</strong> Page Updated Successfully!
						</div>
					<?php } ?>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Edit Page</h3>
							</div>
							<div class="panel-body">
							<form class="form-horizontal" role="form" method="post" action="edit_page.php?id=<?php echo $id?>">
								<div class="form-group">
									<label class="col-lg-2 control-label">Permalink</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="permalink" placeholder="Optional" value="<?php echo($permalink) ?>" />
										<input type="hidden" class="form-control" name="oldpermalink" placeholder="Optional" value="<?php echo($permalink) ?>" />
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Meta Keywords</label>
									<div class="col-lg-10">
										<textarea class="form-control" rows="10" cols="5" id="keyword" name="keywords"><?php echo $keywords ?></textarea>  
									</div>
								</div>
								<div class="form-group">
								<label class="col-lg-2 control-label">Language</label>
								<div class="col-lg-10">
									<select onchange="self.location='<?php echo basename($_SERVER['PHP_SELF']); ?>?id=<?php echo $_GET['id']; ?>&language='+this.options[this.selectedIndex].value"  class="form-control" name="languageName">
										<?php 
										if(isset($_GET['language']))
										{
											$language=$_GET['language'];
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
										<label class="col-lg-2 control-label">Page Name</label>
										<div class="col-lg-10">
										<input type="text" class="form-control" name="title" value="<?php echo $title;  ?>" placeholder="Page Name" maxlength="70" required />
										<?php
										if(isset($_POST['title']) && $error!="")
										echo('<span class="label label-danger">' . $error . '</span>');
										?>
										</div>
									</div>
									<div class="form-group">
									<label class="col-lg-2 control-label">Content</label>
									<div class="col-lg-10">
											<textarea class="form-control" rows="5" cols="5" id="contents" name="content"><?php echo $content;  ?></textarea>
									</div>
									</div>
									<div class="form-group">
										<label class="col-lg-2 control-label">Meta Description</label>
										<div class="col-lg-10">
											<textarea class="form-control" maxlength="160" rows="5" cols="5" name="description"><?php echo $description;  ?></textarea>
										</div>
									</div>
									<?php
									}?>
									<div class="form-group">
										<label class="col-lg-2 control-label">Status</label>
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
		</div>
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