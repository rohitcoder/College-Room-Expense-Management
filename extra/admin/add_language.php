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
$session_id=$_SESSION['id'];
if($_SESSION['type'])
{			
include 'common/header.php';
$lang_array=include '../language/language.php';
if(isset($_POST['submit']))
{
	if($_POST['rtlStatus']=="on") 
	$rtlStatus =1;
	else
	$rtlStatus =0;
	
	if($_POST['status']=="on") 
	$status =1;
	else
	$status =0;

	$array = array();
	foreach(array_combine($_POST['langArray1'], $_POST['langArray2']) as $replaceBy => $originalWords) {
		$array[$originalWords]=mysql_real_escape_string($replaceBy);
	}
	$encode=json_encode($array);
	$languageName=strtolower(xssClean(mres(trim($_POST['languageName']))));
	if(!languageAlreadyExist($languageName,$id)){
	if(getLanguageCode($languageName)){
	if($languageName!='language.php'){
	file_put_contents("../language/".$languageName.".php",$encode); 
	$languageFile = $languageName.".php";
	
	$fetchId = mysql_fetch_array(mysql_query("SELECT MAX(id) AS `id` FROM `languages`"));				
	$id = $fetchId["id"];				
	$displayOrder=$id+1;
	
	 mysql_query("ALTER TABLE `categories` ADD COLUMN `$languageName` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `english`");
	mysql_query("ALTER TABLE `pages` ADD COLUMN `$languageName` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `english`");
	mysql_query("ALTER TABLE `articleCategories` ADD COLUMN `$languageName` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `english`");
	mysql_query("ALTER TABLE `articles` ADD COLUMN `$languageName` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `english`");
	mysql_query("ALTER TABLE `links` ADD COLUMN `$languageName` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL AFTER `english`");
	
	mysql_query("INSERT INTO `languages`(`languageName`, `languagefile`, `status`,`displayOrder`,`rtlStatus`) VALUES ('$languageName','$languageFile','$status','$displayOrder','$rtlStatus')");
	
	$query=mysql_query("SELECT * FROM `categories`");
	
	while($fetch=mysql_fetch_array($query)){
		mysql_query("UPDATE `categories` SET `$languageName`='".$fetch['english']."' WHERE `id`='".$fetch['id']."'");
	}
	$query=mysql_query("SELECT * FROM `pages`");
	while($fetch=mysql_fetch_array($query)){
		mysql_query("UPDATE `pages` SET `$languageName`='".$fetch['english']."' WHERE `id`='".$fetch['id']."'");
	}
	$query=mysql_query("SELECT * FROM `articleCategories`");
	while($fetch=mysql_fetch_array($query)){
		mysql_query("UPDATE `articleCategories` SET `$languageName`='".$fetch['english']."' WHERE `cid`='".$fetch['cid']."'");
	}
	$query=mysql_query("SELECT * FROM `articles`");
	while($fetch=mysql_fetch_array($query)){
		mysql_query("UPDATE `articles` SET `$languageName`='".$fetch['english']."' WHERE `id`='".$fetch['id']."'");
	}
	$query=mysql_query("SELECT * FROM `links`");
	while($fetch=mysql_fetch_array($query)){
		mysql_query("UPDATE `links` SET `$languageName`='".$fetch['english']."' WHERE `id`='".$fetch['id']."'");
	}
	$query=mysql_query("SELECT * FROM `articlesLanguage` WHERE `language`='english'");
	while($fetch=mysql_fetch_array($query)){
		mysql_query("INSERT INTO `articlesLanguage` (`id`,`language`,`description`,`summary`) VALUES('".$fetch['id']."','$languageName','".mysql_real_escape_string($fetch['description'])."','".mysql_real_escape_string($fetch['summary'])."')");
	}
	$query=mysql_query("SELECT * FROM `pagesLanguage` WHERE `language`='english'");
	while($fetch=mysql_fetch_array($query)){
		mysql_query("INSERT INTO `pagesLanguage` (`id`,`language`,`content`,`description`) VALUES('".$fetch['id']."','$languageName','".mysql_real_escape_string($fetch['content'])."','".mysql_real_escape_string($fetch['description'])."')") or die(mysql_error());
	}
	$query=mysql_query("SELECT * FROM `productsLanguage` WHERE `language`='english'");
	while($fetch=mysql_fetch_array($query)){
		mysql_query("INSERT INTO `productsLanguage` (`id`,`language`,`title`,`description`,`summary`) VALUES('".$fetch['id']."','$languageName','".mysql_real_escape_string($fetch['title'])."','".mysql_real_escape_string($fetch['description'])."','".mysql_real_escape_string($fetch['summary'])."')");
	}
	}
	} else {
		$error='Invalid Language Name';
	}
	} else {
		$error='Language Already Exists';
	}
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
<title>Add Language : <?php echo(getTitle()) ?></title>
</head>
<body>
<?php include 'common/navbar_admin.php'; ?>
<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h2><i class="fa fa-language color"></i> Language Settings</h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Language Added Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="awidget" style="padding-top:20px;">
						<form class="form-horizontal" id="imageform" role="form" action="add_language.php" method="post" enctype="multipart/form-data">
								<div class="form-group">
									<label class="col-lg-3 control-label">Language Name</label>
									<div class="col-lg-8">
										<input type="text" class="form-control" name="languageName"  placeholder="Enter Language Name" value="<?php echo $languageName?>" required/>
										<?php
											if(isset($_POST['submit']) && $error!="")
											echo('<span class="label label-danger">' . $error . '</span>');
										?>
									</div>
								</div>
							<?php
							if(isset($_POST['submit']))
							{
								foreach(array_combine($_POST['langArray1'], $_POST['langArray2']) as $originalWords => $replaceBy)
								{
									?>
									<div class="form-group">
										<label class="col-lg-3 control-label"><?php echo $lang_array[$replaceBy]?></label>
										<div class="col-lg-8">
											<input type="text" class="form-control" name="langArray1[]"  value="<?php echo $originalWords?>" required/>
											<input type="hidden" class="form-control" name="langArray2[]"  value="<?php echo $replaceBy?>"/>
										</div>
									</div>
										
									<?php 
								}
							}
							else 
							{
								foreach($lang_array as $replaceBy=>$originalWords)
								{
									?>
									<div class="form-group">
										<label class="col-lg-3 control-label"><?php echo $originalWords?></label>
										<div class="col-lg-8">
											<input type="text" class="form-control" name="langArray1[]" placeholder="<?php echo $originalWords?>"  required/>
											<input type="hidden" class="form-control" name="langArray2[]"  value="<?php echo $replaceBy?>"/>
										</div>
									</div>
										
									<?php 
								} 
							} ?>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="margin-left: 44px;"> RTL Status </label>
								<div class="make-switch switches" data-on="primary" data-off="info" > 
									<input type="checkbox" name="rtlStatus" <?php echo ($rtlStatus ? 'checked' : '')?>>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" style="margin-left: 44px;"> Status </label>
								<div class="make-switch switches" data-on="primary" data-off="info" > 
										<input type="checkbox" name="status" <?php echo ($status ? 'checked' : '')?>>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<div class="col-lg-offset-3 col-lg-9">
									<button type="submit" class="btn btn-success" name="submit"><i class="fa fa-plus"></i> Add</button>
								</div>
							</div>
						</form>
					</div><!-- Awidget -->
				</div><!-- col-md-12 -->
			</div><!-- row -->
		</div><!-- mainy -->
		<div class="clearfix"></div> 
	</div><!-- container -->
</div>
<script src="style/switch/build/js/bootstrap-switch.min.js"></script> 
<script src="style/switch/docs/index.js"></script>
<?php include 'common/footer.php';
}
?>