<?php
error_reporting(0);
defined("APP") or die();
include 'include/header.php';
$Permalink=xssClean(mres(trim($this->Permalink)));
$language=$_SESSION['lanGuaGe'];
$webdata=getWebDate();
$adsdata=getAdsData();
$pagedata=getPageData(trim($Permalink),$language);
if(isset($Permalink) && trim($Permalink)!="" && isValidPagePermalink($permalink))
{
?>
<title><?php echo($pagedata['english']) ?></title>
<meta name="description" content="<?php echo strip_tags($pagedata['description'])?>" />
<meta name="keywords" content="<?php echo ($pagedata['keywords'])?>" />
<meta property="og:title" content="<?php echo $pagedata[$language]; ?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo rootpath().'/page.php?p='.trim($Permalink)?>" />
<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
<meta property="og:description" content="<?php echo strip_tags($pagedata['description'])?>" />
<?php
require 'include/header_under.php';
	$permalink = trim($Permalink);
	$pid=pageIdByPermalink($permalink);
	$row=mysql_fetch_array(mysql_query("SELECT p.*,pl.* FROM `pages` p,`pagesLanguage` pl WHERE p.`id`='$pid' AND pl.`language`='$language' AND p.`id`=pl.`id`"));
	
	$title=$row[$language];
	$content=$row["content"];
	?>   
<section class="theme-card mtop50">
	<section class="content-header">
	<div class="row">
		<div class="col-xs-12">
			<h1> <?php echo ucfirst($title); ?> </h1>
			<ol class="breadcrumb">
				<li><a href="<?php echo rootpath()?>"><i class="fa fa-home"></i> <?php echo $lang_array['home']?></a></li><li> <?php echo $lang_array['page']?></li>
				<li class="active"><?php echo $title; ?></li>
			</ol>
			<?php
			if($adsdata['largeRect2Status'])
			{ ?>
				<div class="ad-tray-728 <?php echo ($adsdata['largeRect2StatusResponsive']  == '1' ? '' : 'hidden-xs hidden-sm') ?>">
					<?php echo $adsdata['largeRect2'];?>
				</div>
			<?php
			}
			?>
			<section class="content">
				<p> <?php echo $content; ?>	</p>
			</section>
		</div>
		<div class="clearfix"></div>
		<div class="col-xs-12">
			<?php
			if($adsdata['largeRect3Status'])
			{ ?>
				<div class="ad-tray-728 <?php echo ($adsdata['largeRect2StatusResponsive']  == '1' ? '' : 'hidden-xs hidden-sm') ?>">
					<?php echo $adsdata['largeRect3'];?>
				</div>
				<div class="clearfix"></div>
			<?php
			}
			?>
		</div>
	</div>
</section>
</section>
<div class="clearfix"></div>
<?php include 'include/footer.php'; 
}
else
{
	$found=1;
	include '404.php';
}
?>