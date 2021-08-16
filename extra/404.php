<?php 
if($found!=1)
include "include/header.php";
include "include/header_under.php";
$adsdata=getAdsData();
?>
<section class="theme-card mtop50">
<title><?php echo $lang_array['page_not_found']?></title>
<section class="content-header">
	<h1>
		<?php echo $lang_array['404_error_page']?>
		<small><?php echo $lang_array['page_cant_be_found']?></small>
	</h1>
	<ol class="breadcrumb">
		<li><a href="<?php echo rootpath()?>"><i class="fa fa-home"></i> <?php echo $lang_array['home']?></a></li>
		<li class="active"><?php echo $lang_array['404_error']?></li>
	</ol>
</section>
<div class="ad-tray-728">
		<?php
		if($adsdata['largeRect1Status'])
		{
			echo $adsdata['largeRect1'];
		}
		?>
	</div>
<section class="content">
	<div class="error-page">
		<h2 class="headline text-info"> <?php echo $lang_array['404']?></h2>
		<div class="error-content">
			<h3><i class="fa fa-warning text-yellow"></i> <?php echo $lang_array['Oops_page_not_found']?></h3>
			<p>
				<?php echo $lang_array['404_link']?> <a href="<?php echo rootpath()?>"><?php echo $lang_array['return_to_homepage']?></a>
			</p>
		</div>
	</div>
	<br />
	<div class="ad-tray-728">
		<?php
		if($adsdata['largeRect2Status'])
		{
			echo $adsdata['largeRect2'];
		}
		?>
	</div>
</section>
</section>
<?php include("include/footer.php") ?>