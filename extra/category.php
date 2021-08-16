<?php
error_reporting(0);
if(!isset($_SESSION))
session_start();
include 'include/header.php';
$cache = phpFastCache();
$getCategory=mres($this->categoryName);
$SORTby=$this->SORTby;
if(is_numeric($this->Page))
$Page=$this->Page;
$adsdata=getAdsData();
$categoryData=getCategoryData($getCategory);
$language=$_SESSION['lanGuaGe'];
if(isset($getCategory) && trim($getCategory)!="" && isValidCategory($getCategory))
{
	if(isset($SORTby) && $SORTby=="hprice") 
	{
		$_SESSION['SORTby']='originalPrice';
		$_SESSION['SORTorder']='DESC';
	}
	else if($SORTby=="lprice") 
	{
		$_SESSION['SORTby']='originalPrice';
		$_SESSION['SORTorder']='ASC';
	}
	else if($SORTby=="hotest") 
	{
		$_SESSION['SORTby']='clicks';
		$_SESSION['SORTorder']='DESC';
	}
	else if($SORTby=="newest")
	{
		$_SESSION['SORTby']='id';
		$_SESSION['SORTorder']='DESC';
	}
	if(!isset($_SESSION['SORTby']))
		$_SESSION['SORTby']='id';
	if(!isset($_SESSION['SORTorder']))
		$_SESSION['SORTorder']='DESC';
		$RssType='category';
$webdata=getWebDate();
?>
	<title><?php echo ($_SESSION['SORTby']=="clicks" ? $lang_array['hotest_ptoducts_in'] : ($_SESSION['SORTby']=="id" ? $lang_array['latest_products_in'] : ($_SESSION['SORTby']=="originalPrice" && $_SESSION['SORTorder']=="DESC" ? $lang_array['most_expensive_products_in'] : ($_SESSION['SORTby']=="originalPrice" && $_SESSION['SORTorder']=="ASC" ? $lang_array['least_expensive_products_in'] : $lang_array['all_products_in'])))) .(catPermalinkToName($getCategory)).(trim($Page) ? '-'.$lang_array['page'].trim($Page):'') ?></title>
	<meta name="description" content="<?php echo $categoryData['description']?>" />
	<meta name="keywords" content="<?php echo $categoryData['keywords']?>" />
	<meta property="og:title" content="<?php echo $categoryData['english']; ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo rootpath().'/category/'.(isParentCategory($getCategory) ?  $getCategory : categoryPermalinkByChild($getCategory).'/'.$getCategory)?>" />
	<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
	<meta property="og:description" content="<?php echo $categoryData['description']?>" /> 
	<meta property="og:site_name" content="<?php echo $webdata['websiteName']?>" />
<?php
include("include/header_under.php");
	$permalink = trim($getCategory);
	$catid = catPermalinkToId($permalink);
	$page=1;
	$limit=productPerPage();
	$next=2;
	$prev=1;
	if(isParentCategory($permalink)) 
	{
		$data = mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.status='1' AND p.`id` IN(SELECT pid FROM procat WHERE cid IN(SELECT id FROM categories WHERE parentId='".$catid."') OR cid='$catid') AND p.`id`=pl.`id` AND pl.`language`='$language'");
	}
	else 
	{
		$data = mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.status='1' AND p.`id` IN(SELECT pid FROM procat WHERE cid='$catid') AND p.`id`=pl.`id` AND pl.`language`='$language'");
	}
	$rows = mysql_num_rows($data);
	$last = ceil($rows/$limit);
	if(isset($Page) && $Page!='' && ($Page>=1 && $Page<=$last))
	{
		$page=$Page;
		if($page>1)
			$prev=$page-1;
		else
			$prev=$page;
		if($page<$last)
			$next=$page+1;
		else
			$next=$page;
	}
	?>
	<section class="theme-card mtop50">
	<section class="content-header">
		<h1><?php echo catPermalinkToName($permalink).'&nbsp&nbsp';?></h1>
		<small>
		<?php
		echo ($_SESSION['SORTby']=="clicks" ? $lang_array['hotest'] : ($_SESSION['SORTby']=="id" ? $lang_array['newest'] : ($_SESSION['SORTby']=="originalPrice" && $_SESSION['SORTorder']=="DESC" ? $lang_array['most_expensive'] : ($_SESSION['SORTby']=="originalPrice" && $_SESSION['SORTorder']=="ASC" ? $lang_array['least_expensive'] : 'Latest Products')))).' '.$lang_array['products'] ?>
		</small>
		<div class="pCat pull-right">
			<button class="btn btn-default btn-sm"><?php echo $lang_array['sort_by']?> <i class="fa fa-caret-down"></i></button>
			<ul class="nav nav-pills navbar-right">
				<div class="btn-group">
						<a href="<?php echo rootpath().'/category/'.(isParentCategory($permalink) ?  $permalink : categoryPermalinkByChild($permalink).'/'.$permalink).'/newest'.(trim($Page) ? '/'.trim($Page):'') ?>" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['SORTby']=="id" ? 'active' : "") ?>"><i class="fa fa-star"></i> <?php echo $lang_array['newest'] ; ?></a>
					
						<a href="<?php echo rootpath().'/category/'.(isParentCategory($permalink) ?  $permalink : categoryPermalinkByChild($permalink).'/'.$permalink).'/hotest'.(trim($Page) ? '/'.trim($Page):'') ?>" id="list" class="btn btn-default btn-sm <?php echo ($_SESSION['SORTby']=="clicks" ? 'active' : "") ?>"><i class="fa fa-fire"></i> <?php echo $lang_array['hotest']; ?></a>
						
						<a href="<?php echo rootpath().'/category/'.(isParentCategory($permalink) ?  $permalink : categoryPermalinkByChild($permalink).'/'.$permalink).'/hprice'.(trim($Page) ? '/'.trim($Page):'') ?>" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['SORTby']=="originalPrice" && $_SESSION['SORTorder']=="DESC" ? 'active' : "") ?>"><i class="fa fa-arrow-up"></i> <?php echo $lang_array['high_price'] ; ?></a>

						<a href="<?php echo rootpath().'/category/'.(isParentCategory($permalink) ?  $permalink : categoryPermalinkByChild($permalink).'/'.$permalink).'/lprice'.(trim($Page) ? '/'.trim($Page):'') ?>" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['SORTby']=="originalPrice" && $_SESSION['SORTorder']=="ASC" ? 'active' : "") ?>"><i class="fa fa-arrow-down"></i> <?php echo $lang_array['low_price'] ; ?></a>
				</div>
			</ul> 
		</div>
		<div class="clearfix"></div>
		<div class="breadcrumbs_box">
			<ol class="breadcrumb">
				<li><a href="<?php echo rootpath()?>/"><?php echo $lang_array['home'] ; ?></a></li>
				<?php echo CategoryAndSubcategoryUrl($permalink)?>
				
			</ol>
		</div>
	</section><!--/Page Header-->
	<section class=""><!--Page Content-->
	<div class="row">  
		<?php
		$start_result = ($page-1)*$limit;
		if(enableCategoryCache())
		{
			$recentCacheExpireTime = categoryCacheExpireTime();
			$sOrder=$_SESSION['SORTorder'];
			$sortBy=$_SESSION['SORTby'];
			$var = $catid ."category_".$sortBy.$sOrder.$page.$language;
			$data = $cache->get($var);
			if($data==null)
			{
				$data = array();
				if(isParentCategory($permalink)) 
				{
					$match = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.status='1' AND p.`id` IN(SELECT pid FROM procat WHERE cid IN(SELECT id FROM categories WHERE parentId='".$catid."') OR cid='$catid') AND p.`id`=pl.`id` AND pl.`language`='$language' ORDER BY p.".$_SESSION['SORTby'].' '.$_SESSION['SORTorder']." LIMIT ".$start_result.",".$limit;
				}
				else 
				{
					$match = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.status='1' AND p.`id` IN(SELECT pid FROM procat WHERE cid='$catid') AND p.`id`=pl.`id` AND pl.`language`='$language' ORDER BY p.".$_SESSION['SORTby'].' '.$_SESSION['SORTorder']." LIMIT ".$start_result.",".$limit;
				}
				$qry = mysql_query($match);
				while($row = mysql_fetch_array($qry))
				{
					$data[] = $row;  
				}
				$cache->set($var,$data,$recentCacheExpireTime);
			}
		}
		else
		{
				$data = array();
				if(isParentCategory($permalink)) 
				{
					$match = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.status='1' AND p.`id` IN(SELECT pid FROM procat WHERE cid IN(SELECT id FROM categories WHERE parentId='".$catid."') OR cid='$catid') AND p.`id`=pl.`id` AND pl.`language`='$language' ORDER BY p.".$_SESSION['SORTby'].' '.$_SESSION['SORTorder']." LIMIT ".$start_result.",".$limit;
				}
				else 
				{
					$match = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.status='1' AND p.`id` IN(SELECT pid FROM procat WHERE cid='$catid') AND p.`id`=pl.`id` AND pl.`language`='$language' ORDER BY p.".$_SESSION['SORTby'].' '.$_SESSION['SORTorder']." LIMIT ".$start_result.",".$limit;
				}
			$qry = mysql_query($match);
			while($row = mysql_fetch_array($qry))
			{
				$data[] = $row;  
			}
		}
		$i=1;
		if (count($data) > 0) 
		{
			foreach($data as $row)
			{
				$pid=$row["id"] ;
				$originalPrice=$row["originalPrice"] ;
				$salePrice=$row["salePrice"] ;
				$url = $row['url'];
				$views= $row['views'];
				$via=getdomain($url);
				$saleStatus=$row['saleStatus'];
				$title=$row['title'];
				$summary=truncateshortDescription($row['summary']);
				?>
				<div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
				<div class="thumbnail">
				<div class="favPrd <?php echo (isset($_SESSION['store_uid']) && isFavourite($_SESSION['store_uid'],$pid) ? 'active' : '')?>" id="<?php echo $pid?>" <?php echo (!isset($_SESSION['store_uid']) ? 'data-toggle="modal" data-target="#login-modal"' : '')?>></div>
				<?php
				$sql = "SELECT * FROM `currencySettings` ";
				$qury = mysql_query($sql);
				while($rowp = mysql_fetch_array($qury))
				{     
					$crName = $rowp['crName'];                     
					$priceDollor= $rowp['priceDollor'];                          
					$show= $rowp['showPlace'];
					$originalPrice=cleanNum($originalPrice * $priceDollor);
					$salePrice=cleanNum($salePrice * $priceDollor);
					?>
					<div class="price"><p><?php echo ($show ? $crName .' '.$originalPrice : $originalPrice.' '.$crName)?></p></div>
					<?php
				}
				?>
				<div class="img-adj">
				<a href="<?php echo rootpath().'/'.productCategoryAndSubcategory($row["permalink"]).'/'.$row["permalink"]?>.html"> 
					<img  class="loading" src="<?php echo rootpath()  . '/images/productImages/' . $row["image"]?>"  alt="<?php echo $title?>"/>
				</a>
				</div>
				<div class="caption">
					<h4>
						<a href="<?php echo rootpath().'/'.productCategoryAndSubcategory($row["permalink"]).'/'.$row["permalink"]?>.html">  <?php echo $title?></a>
					</h4>
					<p class="hidden-xs" style="word-wrap: break-word;"><?php echo $summary?></p>
				</div>
				<div class="row well">
				<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 pull-left">
					<?php echo productRating($row["id"])?>
				</div>
				<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 pull-right">
				<?php
				if($saleStatus){ ?>
				<p><?php echo ($salePrice ==0 ? '<span class="green-text">Free</span>' : ($show ? $crName .' '.$salePrice : $salePrice.' '.$crName))?></p>
				<p class="old-price"> <strike><?php echo ($show ? $crName .' '.$originalPrice : $originalPrice.' '.$crName)?></strike></p>
				<?php } else {
				?>
				<p><?php echo ($show ? $crName .' '.$originalPrice : $originalPrice.' '.$crName)?></p>
				<?php
				}
				?>
				</div>
				</div>
				<div class="thumb-footer">
					<div class="row">
						<div class="col-xs-7">
							<a class="product" href="<?php echo rootpath().'/go/'.$pid?>" target="_blank">
							<button type="button" class="btn btn-buy btn-sm buy_now" id=<?php echo $pid?>><?php echo$lang_array["buy_it_now"]?></button>
							</a>
						</div>
					   
					    <div class="col-xs-5">
						   <p class="detail pull-right">
							  <?php echo $views .' '.$lang_array['views']?>
						   </p>
					    </div>
					</div> 
				   </div>
				  </div>
				</div>
				<?php
				if($i==4){
				?>
				<div class="clearfix"></div>
				<?php
				if($adsdata['largeRect2Status'])
				{ ?>
					<div class="ad-tray-728 <?php echo ($adsdata['largeRect2StatusResponsive']  == '1' ? '' : 'hidden-xs hidden-sm') ?>">
						<?php echo $adsdata['largeRect2'];?>
					</div>
					<div class="clearfix"></div>
				<?php
				}
				}
				$i++;
			}
		}  else 
		{ ?>
		<div class="panel-body related-products">
			<div class="clearfix"></div>
			<div class="row">
				
				<div class="col-md-12">
				    <div class="row">
					   <div class="col-md-12">
						<div class="error-template">
							<h1><?php echo $lang_array['oop']?></h1>
							<h2><i class="fa fa-times-circle"></i> <?php echo $lang_array['no_product_found']?></h2>   
						</div>
					   </div>
				    </div>
				</div>
			</div>
		</div>
		<?php
		}?>
	</div>
	<?php 
	if($rows > $limit) 
	{ 
	?>
		<ul class="pagination">
			<li><a href="<?php echo rootpath().'/category/'.(isParentCategory($permalink) ?  $getCategory : categoryPermalinkByChild($permalink).'/'.$getCategory)?>"><?php echo $lang_array['first']?></a></li>
			<?php if($page >1) { ?>
			<li><a href="<?php echo rootpath().'/category/'.(isParentCategory($permalink) ?  $getCategory : categoryPermalinkByChild($permalink).'/'.$getCategory).'/'.$prev ?>">&laquo;</a></li>
			<?php } ?>
			<?php
			if($page>1 && $last>5)
			{
				$i=((int)$page/5)*5-1;
				if(!($i+5>$last))
				{
					$temp_last = $i+5;
				}
				else
				{
					$i = $last - 5;
					$temp_last = $last;
				}
			}
			else
			{
				$i=1;
				if(!($i+5>$last))
					$temp_last = 5;
				else
					$temp_last = $last;
			}
			for($i;$i<=$temp_last;$i++)
			{
				if($i==$page)
					echo('<li class="active"><a href="'.rootpath().'/category/'.(isParentCategory($permalink) ?  $getCategory : categoryPermalinkByChild($permalink).'/'.$getCategory).'/' . $i . '">' . $i . '</a></li>');
				else
					echo('<li><a href="'.rootpath().'/category/'.(isParentCategory($permalink) ?  $getCategory : categoryPermalinkByChild($permalink).'/'.$getCategory).'/'. $i . '">' . $i . '</a></li>');
			}
			if($page!=$last) { ?>
			<li><a href="<?php echo rootpath().'/category/'.(isParentCategory($permalink) ?  $getCategory : categoryPermalinkByChild($permalink).'/'.$getCategory).'/'.$next ?>">&raquo;</a></li>
			<?php } ?>
			<li><a href="<?php echo rootpath().'/category/'.(isParentCategory($permalink) ?  $getCategory : categoryPermalinkByChild($permalink).'/'.$getCategory).'/'.$last ?>"><?php echo $lang_array['last']?></a></li>
		</ul>
		<?php 
		} 
		?>
	</section>
	<?php
	if(count($data) > 4 || count($data) < 4) 
		{ ?>
		<div class="clearfix"></div>
		<?php
		if($adsdata['largeRect3Status'])
		{ ?>
			<div class="ad-tray-728 <?php echo ($adsdata['largeRect3StatusResponsive']  == '1' ? '' : 'hidden-xs hidden-sm') ?>">
				<?php echo $adsdata['largeRect3'];?>
			</div>
			<div class="clearfix"></div>
		<?php
		}
		?>
	</section>
	<?php }
	include 'include/footer.php'; 
}
else
{
	$found=1;
	include '404.php';
}
?>