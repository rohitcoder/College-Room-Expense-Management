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
$lang_array = include '../language/language.php';
									
if(isset($_POST['submit']))
{
	$id=trim($_GET['id']);
	$previousLanguage=$_POST['previousLanguage'];
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
	if($previousLanguage!=$languageName){
		mysql_query("ALTER TABLE `categories` CHANGE $previousLanguage $languageName varchar(100)  CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		mysql_query("ALTER TABLE `pages` CHANGE $previousLanguage $languageName varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		mysql_query("ALTER TABLE `articleCategories` CHANGE $previousLanguage $languageName varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		mysql_query("ALTER TABLE `articles` CHANGE $previousLanguage $languageName varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		mysql_query("ALTER TABLE `links` CHANGE $previousLanguage $languageName varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci");
		unlink('../language/'.$previousLanguage.'.php');
		
		mysql_query("UPDATE `pagesLanguage` SET `language`='$languageName' WHERE `language`='$previousLanguage'");
		mysql_query("UPDATE `articlesLanguage` SET `language`='$languageName' WHERE `language`='$previousLanguage'");
		mysql_query("UPDATE `productsLanguage` SET `language`='$languageName' WHERE `language`='$previousLanguage'");
		
	}
	file_put_contents("../language/".$languageName.".php",$encode);	
	$languageFile = $languageName.".php";
	mysql_query("UPDATE `languages` SET `languageName`='$languageName', `languagefile`='$languageFile',`rtlStatus`='$rtlStatus',`status`='$status' WHERE `id`='$id'") or die(mysql_error());
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
<title>Edit Language : <?php echo(getTitle()) ?></title>
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
					<strong>Success !</strong> Language Updated Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="awidget" style="padding: 15px;">
						<form class="" id="imageform" role="form" method="post" enctype="multipart/form-data">
							<?php
							if(isset($_GET['id']))
							{
								$id = trim($_GET['id']);
								$sql = mysql_fetch_array(mysql_query("SELECT * FROM `languages` WHERE `id` = '$id'"));					
									
									$json = file_get_contents('../language/'.$sql['languageFile']);
									
									$data=json_decode($json, true);
								
							}
							?>	
									<div class="form-group">
									<div class="row">
										<label class="col-lg-3 control-label">Language Name</label>
										<div class="col-lg-8">
											<input type="text" class="form-control" name="languageName"  placeholder="Enter Language Name" value="<?php echo $sql['languageName']?>" required <?php echo (englishLanguage($id) ? 'readonly':'')?>/>
											<?php
												if(isset($_POST['submit']) && $error!="")
												echo('<span class="label label-danger">' . $error . '</span>');
											?>
											<input type="hidden" name="previousLanguage" value="<?php echo $sql['languageName']?>">
										</div>
									</div>
								</div>
							<?php
								foreach($data as $replaceBy => $originalWords)
								{
									?>
									
									<div class="form-group">
									<div class="row">
										<label class="col-lg-3 control-label"><?php echo $lang_array[$replaceBy]?></label>
										<div class="col-lg-8">
											<input type="text" class="form-control" name="langArray1[]"  value="<?php echo $originalWords?>" required />
										</div>
									</div>
									</div>
									
										
									<?php 
								} 
								foreach($lang_array as $replaceBy => $originalWords)
								{ ?>
								<input type="hidden" class="form-control" name="langArray2[]" value="<?php echo $replaceBy?>"/><?php 
								} 
							?>
							
							<div class="form-group" style="margin-left: 46px;">
							<div class="row">
								<label class="col-sm-3 control-label" style="margin-right: 9px;"> RTL Status </label>
								<div class="make-switch switches" data-on="primary" data-off="info" > 
										<input type="checkbox" name="rtlStatus" <?php echo ($sql['rtlStatus'] ? 'checked' : '')?>>
								</div>
							</div>
							</div>
							
							<div class="form-group" style="margin-left: 46px;">
							<div class="row">
								<label class="col-sm-3 control-label" style="margin-right: 9px;"> Block/Unblock </label>
								<div class="make-switch switches" data-on="primary" data-off="info" > 
									<input type="checkbox" name="status" <?php echo ($sql['status'] ? 'checked' : '')?>>
								</div>
							</div>
							</div>
							<hr/>
							<div class="row">
							<div class="form-group">
								<div class="col-lg-offset-3 col-lg-9">
									<button type="submit" class="btn btn-success" name="submit"><i class="fa fa-pencil-square-o"></i> Update</button>
								</div>
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