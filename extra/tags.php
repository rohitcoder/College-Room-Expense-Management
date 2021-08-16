<?php 
error_reporting(0);
if(!isset($_SESSION))
session_start();
include 'include/header.php';
$cache = phpFastCache();
$Tagname=xssClean(mres(trim($this->tagName)));
$SortbY=$this->SortbY;
if(is_numeric($this->Page))
$Page=xssClean(mres(trim($this->Page)));
$adsdata=getAdsData();
$language=$_SESSION['lanGuaGe'];
if(isset($Tagname) && trim($Tagname)!="")
{	
	if(isset($SortbY) && $SortbY=="hprice") 
	{
		$_SESSION['SortbY']='originalPrice';
		$_SESSION['SortordeR']='DESC';
	}
	else if($SortbY=="lprice") 
	{
		$_SESSION['SortbY']='originalPrice';
		$_SESSION['SortordeR']='ASC';
	}
	else if($SortbY=="hotest") 
	{
		$_SESSION['SortbY']='clicks';
		$_SESSION['SortordeR']='DESC';
	}
	else if($SortbY=="newest")
	{
		$_SESSION['SortbY']='id';
		$_SESSION['SortordeR']='DESC';
	}
	if(!isset($_SESSION['SortbY']))
		$_SESSION['SortbY']='id';
	if(!isset($_SESSION['SortordeR']))
		$_SESSION['SortordeR']='DESC';
		$RssType='tags';
	$webdata=getWebDate();
?>
	<title><?php echo($lang_array['products_tagged_with'].trim(str_replace('-',' ',$Tagname)).(trim($Page) ? '-Page '.trim($Page):'')) ?></title>
	<meta name="description" content="<?php echo strip_tags($webdata['description'])?>" />
	<meta name="keywords" content="<?php echo ($webdata['metaTags'])?>" />
	<meta property="og:title" content="<?php echo $webdata['title']; ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo rootpath().'/tags/'.trim($Tagname)?>" />
	<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
	<meta property="og:description" content="<?php echo strip_tags($webdata['description'])?>" /> 
	<meta property="og:site_name" content="<?php echo ($webdata['websiteName'])?>" />
<?php
include("include/header_under.php");
	$permalink = trim($Tagname);
	$catid = catPermalinkToId($permalink);
	$page=1;
	$limit=productPerPage();
	$next=2;
	$prev=1;
	$getTag=str_replace('-',' ',trim($Tagname));
	$data=mysql_query("SELECT * FROM `products` WHERE `status`='1' AND `tags` LIKE '%".$getTag."%'");
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
		<h1>
		<?php echo $lang_array['products_tagged_with'] ?>
		<small><?php echo $getTag?></small>
		</h1>
		<div class="pCat pull-right">
			<button class="btn btn-default btn-sm"><?php echo $lang_array['sort_by']?> <i class="fa fa-caret-down"></i></button>
			<ul class="nav nav-pills navbar-right">
				<div class="btn-group">
					<a href="<?php echo rootpath().'/tags/'.$permalink.'/newest'.(trim($Page) ? '/'.trim($Page):'') ?>" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['SortbY']=="id" ? 'active' : "") ?>"><i class="fa fa-star"></i> <?php echo $lang_array['newest'] ; ?></a>
					
					<a href="<?php echo rootpath().'/tags/'.$permalink.'/hotest'.(trim($Page) ? '/'.trim($Page):'') ?>" id="list" class="btn btn-default btn-sm <?php echo ($_SESSION['SortbY']=="clicks" ? 'active' : "") ?>"><i class="fa fa-fire"></i> <?php echo $lang_array['hotest']; ?></a>
					
					<a href="<?php echo rootpath().'/tags/'.$permalink.'/hprice'.(trim($Page) ? '/'.trim($Page):'') ?>" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['SortbY']=="originalPrice" && $_SESSION['SortordeR']=="DESC" ? 'active' : "") ?>"><i class="fa fa-arrow-up"></i> <?php echo $lang_array['high_price'] ; ?></a>

					<a href="<?php echo rootpath().'/tags/'.$permalink.'/lprice'.(trim($Page) ? '/'.trim($Page):'') ?>" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['SortbY']=="originalPrice" && $_SESSION['SortordeR']=="ASC" ? 'active' : "") ?>"><i class="fa fa-arrow-down"></i> <?php echo $lang_array['low_price'] ; ?></a>
				</div>
			</ul> 
		</div>
	</section>
	<div class="clearfix"></div></br>
	<section class="content">
	<div class="row">  
		<?php
		$start_result = ($page-1)*$limit;
		if(enableTagsCache())
		{
			$tagsCacheExpireTime = tagsCacheExpireTime();
			$sOrder=$_SESSION['SortordeR'];
			$sortBy=$_SESSION['SortbY'];
			$var = $permalink."tags_".$sortBy.$sOrder.$page.$language;
			$data = $cache->get($var);
			if($data==null)
			{
				$data = array();
				$match = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`status`='1' AND p.`id`=pl.`id` AND pl.`language`='$language' AND p.`tags` LIKE '%".$getTag."%' ORDER BY p.".$_SESSION['SortbY'].' '.$_SESSION['SortordeR']." LIMIT ".$start_result.",".$limit;
				$qry = mysql_query($match);
				while($row = mysql_fetch_array($qry))
				{
					$data[] = $row;
				}
				$cache->set($var,$data,$tagsCacheExpireTime);
			}
		}
		else
		{
				$data = array();
				$match = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`status`='1' AND p.`id`=pl.`id` AND pl.`language`='$language' AND p.`tags` LIKE '%".$getTag."%' ORDER BY p.".$_SESSION['SortbY'].' '.$_SESSION['SortordeR']." LIMIT ".$start_result.",".$limit;
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
					<img  class="loading" src="<?php echo rootpath()  . '/images/productImages/' . $row["image"]?>" alt="<?php echo $title?>" />
				</a>
				</div>
				<div class="caption">
					<h4>
						<a href="<?php echo rootpath()  . '/'.productCategoryAndSubcategory($row["permalink"]).'/'.$row["permalink"]?>.html"> <?php echo $title?></a>
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
				<p class="old-price"> <strike><?php echo ($show ? $crName .' '.$originalPrice : $originalPrice.''.$crName)?></strike></p>
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
							<button type="button" class="btn btn-buy btn-sm buy_now" id=<?php echo $pid?>><?php echo $lang_array["buy_it_now"]?></button>
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
		} else 
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
			<li>
			<a href="<?php echo rootpath().'/tags/'.trim($Tagname)?>"><?php echo $lang_array['first']?></a>
			</li>
			<?php if($page >1) { ?>
			<li>
				<a href="<?php echo rootpath().'/tags/'.trim($Tagname).'/'.($prev)?>">&laquo;</a>
			</li>
			<?php }
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
					echo('<li class="active"><a href="'.rootpath().'/tags/'.trim($Tagname).'/' . $i . '">' . $i . '</a></li>');
				else
					echo('<li><a href="'.rootpath().'/tags/'.trim($Tagname).'/' . $i . '">' . $i . '</a></li>');
			}
			if($page!=$last) { ?>
			<li><a href="<?php echo rootpath().'/tags/'.trim($Tagname).'/'.($next) ?>">&raquo;</a></li>
			<?php } ?>
			<li><a href="<?php echo rootpath().'/tags/'.trim($Tagname).'/'.($last) ?>"><?php echo $lang_array['last']?></a></li>
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