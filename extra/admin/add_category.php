<?php
error_reporting(0);
if(!isset($_SESSION)) session_start();
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
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Add New Category : <?php echo(getTitle()) ?></title>
	<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
	<script type="text/javascript">
	$( document ).ready(function() 
	{
		$( "#addcategory" ).hide();
	});
	</script>
	</head>
	<body>
	<div class="page-content blocky">
		<div class="container" style="margin-top:20px;">
			<?php 
			include 'common/sidebar.php';
			$error="";
			function getDisplayOrder(){
				$fetch=mysql_fetch_array(mysql_query("SELECT MAX(displayOrder) AS displayOrder FROM `categories`"));
				return $fetch['displayOrder'];
			}
			function addCategory($parent,$permalink,$name,$description,$keywords,$displayOrder,$status,$limit)
			{
				clearRecentCache($parent);
				clearCategorycache($parent);
				mysql_query("INSERT INTO `categories`(`parentId`,`permalink`,`english`,`description`,`keywords`,`displayOrder`,`status`,`limit`) VALUES('$parent','$permalink','$name','$description','$keywords','$displayOrder','$status','$limit')");
				$query=mysql_query("SELECT * FROM `languages` WHERE languageName!='english'");
				$id=mysql_insert_id();
				while($fetch=mysql_fetch_array($query))
				{
					$languageName=$fetch['languageName'];
					mysql_query("UPDATE `categories` SET `$languageName`='$name' WHERE id='$id'");
				}
			}
			if(isset($_POST['submit']))
			{
				$name = xssClean(mres(trim(ucwords($_POST["category"]))));
				
				$parent = xssClean(mres(trim($_POST["parent"])));
				
				$description=xssClean(mres(trim(strip_tags($_POST['description']))));
				
				if($description=="")
				{
					$error0='enter description here';
				}
				
				$keywords=xssClean(mres(trim($_POST['keywords'])));
				
				$limit=xssClean(mres(trim($_POST['limit'])));
				
				$status=$_POST['status'];
				
				if($parent==0){
				if($status=='on')
					$status=1;
				else
					$status=0;
				if($limit=="")
					$error1='Enter Limit';
				else if(!is_numeric($limit))
					$error1='Enter Valid Limit';
				} else{
					$limit=0;
					$status=1;
				}
				if(isValidCategory($name)) 
					$error ="Category Name Already Exist<br />";
					$permalink=mysql_real_escape_string(trim($_POST["permalink"]));
				
				if($permalink=="")
					$permalink=genCategoryPermalink($name);
				else
					$permalink=genCategoryPermalink($permalink);
				
				if(isset($_POST['category']) &&  $error=="" &&  $error0=="" && $error1=="")
				{
					addCategory($parent,$permalink,$name,$description,$keywords,getDisplayOrder(),$status,$limit);
					?>
					<script>
					$(function() {
						$("input[name='category']").val("");
						$("input[name='permalink']").val("");
						document.getElementById('description').value = "";
						document.getElementById('keyword').value = "";
						$("input[name='limit']").val("");
					});
					</script>
					<?php
				} 
			}
			?>
			<div class="mainy">
				<div class="page-title">
					<h2><i class="fa fa-plus-square-o"></i> Add Category</h2> 
					<hr />
						<?php if(isset($_POST['submit'])&&  $error=="" &&  $error0=="" &&  $error1==""){ ?>
						<div class="alert alert-success alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<strong>Success !</strong> Category Added Successfully!
						</div>
						<?php } ?>
				</div>
				<div class="">
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title">Add New Category</h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal" role="form" action="add_category.php" method="post" id="overlay_form">        
								<div class="form-group">
									<label class="col-lg-2 control-label">Category Name</label>
									<div class="col-lg-5">
										<input type="text" class="form-control" name="category" value="<?php echo $name?>" placeholder="Category Name" required/>
										<?php
											if(isset($_POST['submit']) && $error!="")
											echo('<span class="label label-danger">' . $error . '</span>');
										?>
									</div>
								</div>       
								<div class="form-group">
									<label class="col-lg-2 control-label">Permalink</label>
									<div class="col-lg-5">
										<input type="text" class="form-control" name="permalink" placeholder="Leave Empty To Auto Generate"/>
									</div>
								</div>
								<div class="form-group">
									<label class="col-lg-2 control-label">Description</label>
									<div class="col-lg-5">
										<textarea class="form-control" rows="5" cols="5" id="description" name="description" placeholder="Enter Description"><?php echo $description ?></textarea>
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
										$qry = mysql_query("SELECT `id`,`english` FROM `categories` WHERE `parentId`=0");
										$num_rows = mysql_num_rows($qry); 
										if ($num_rows > 0) 
										{
											if(isset($_GET['parent']))
											{	 
												$parent_id=$_GET['parent'];
											}
											else
											echo('<option value=0>None</option>');
											while($rowx = mysql_fetch_array($qry)) 
											{
												if($parent_id==$rowx["id"])
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
								<div class="form-group limit">
									<label class="col-lg-2 control-label">Limit</label>
									<div class="col-lg-5">
										<input type="text" class="form-control" value="<?php echo $limit?>" name="limit" placeholder="Enter Product Limit" />
										<?php
											if(isset($_POST['submit']) && $error1!="")
											echo('<span class="label label-danger">' . $error1 . '</span>');
										?>
									</div>
								</div>
								<div class="form-group checkBox">
									<label class="col-lg-2 control-label">Featured</label>
										<input type="checkbox" class="form-control" name="status" <?php echo ($status==1 ? 'checked' : '')?> />
								</div>
								<hr />
								<div class="form-group">
									<div class="col-lg-offset-2 col-lg-10">
										<button class="btn btn-success" type="submit" name="submit"><i class="fa fa-plus-square-o"></i> Add</button>
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