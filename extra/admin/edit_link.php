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
	function linkUpdate($id,$title,$url,$language,$status,$showIn,$newTab)
	{
		$title=ucwords($title);
		$content=ucfirst($content);
		if($title==""){
			mysql_query("UPDATE `links` SET `url`='$url',`status`='$status',`showIn`='$showIn',`newTab`='$newTab' WHERE `id`='$id'");
		} else {
			mysql_query("UPDATE `links` SET `$language`='$title',`url`='$url',`status`='$status',`showIn`='$showIn',`newTab`='$newTab' WHERE `id`='$id'");
		}
	}
	if(isset($_GET["id"]))
	{
		$id = $_GET["id"];
	}
	if(isset($_POST['submit']))
	{
		$title = mysql_real_escape_string(strip_tags(trim($_POST['title'])));
		if(linkNameAlreadyExist($title,$id))
		{
			$error='Link Already Exists';
		}
		$url = mres(trim($_POST["url"]));
		if($url !="" && $url !="#" && !validUrl($url))
			$error1='Invalid Url';
		$status=xssClean(mres(trim($_POST['status'])));
		$showIn=xssClean(mres(trim($_POST['showIn'])));
		$newTab=xssClean(mres(trim($_POST['newTab'])));
		$language=xssClean(mres(trim($_POST['languageName'])));
		if($error=="" && $error1=="")  
		{
			linkUpdate($id,$title,$url,$language,$status,$showIn,$newTab);
		}
	}
			if(isset($_GET['language'])){
				$language=mysql_real_escape_string(trim($_GET['language']));
				$row = mysql_fetch_array(mysql_query("SELECT * FROM `links` WHERE `id`='$id'"));
					$title = $row[$language];
			} else {
				$row = mysql_fetch_array(mysql_query("SELECT * FROM `links` WHERE `id`='$id'"));
			}
			$id=$row['id'];	
			$url=$row['url'];
			$status=$row['status'];	
			$showIn=$row['showIn'];
			$newTab=$row['newTab'];
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Edit Link: <?php echo ($language ? $title : $row['english'])?></title>
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
					<h2><i class="fa fa-pencil-square-o color"></i> Edit Link </h2> 
					<hr />
					<?php if(isset($_POST['submit']) && $error=="" && $error1==""){ ?>
						<div class="alert alert-success alert-dismissible" role="alert">
							<button type="button" class="close" data-dismiss="alert" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<strong>Success !</strong> Link Updated Successfully!
						</div>
					<?php } ?>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="panel panel-default">
							<div class="panel-heading">
								<h3 class="panel-title">Edit Link</h3>
							</div>
							<div class="panel-body">
							<form class="form-horizontal" role="form" method="post">
								<div class="form-group">
									<label class="col-lg-2 control-label">Url</label>
									<div class="col-lg-10">
										<input type="text" class="form-control" name="url" placeholder="Url" value="<?php echo($url) ?>" />
										<?php
										if(isset($_POST['url']) && $error1!="")
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
											<label class="col-lg-2 control-label">Link Title</label>
											<div class="col-lg-5">
												<input type="text" class="form-control" name="title" value="<?php echo $title;?>" placeholder="Link Title" required />
											</div>
										</div>
										<?php } ?>
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
								<div class="form-group">
									<label class="col-lg-2 control-label">Open Link In New Tab</label>
									<div class="col-lg-10">
											<div class="radio">
												<label>
													<input type="radio" name="newTab" value="0" <?php echo ($newTab==0 ? 'checked' : '')?>>
													No
												</label>
											</div>
											<div class="radio">
												<label>
													<input type="radio" name="newTab" value="1" <?php echo ($newTab==1 ? 'checked' : '')?>>
													Yes
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
	<?php include 'common/footer.php'; 
}
?>					