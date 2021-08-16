<?php
error_reporting(0);
defined("APP") or die();
if(!isset($_SESSION))
session_start();
include("include/header.php");
$cache = phpFastCache();
$sortBy=$this->sortBy;
$adsdata=getAdsData();
$language=$_SESSION['lanGuaGe'];
if(isset($sortBy) && trim($sortBy=="hprice")) 
	{
		$_SESSION['SORtBy']='originalPrice';
		$_SESSION['sortOrder']='DESC';
	}
	else if($sortBy=="lprice") 
	{
		$_SESSION['SORtBy']='originalPrice';
		$_SESSION['sortOrder']='ASC';
	}
	else if($sortBy=="hotest") 
	{
		$_SESSION['SORtBy']='clicks';
		$_SESSION['sortOrder']='DESC';
	}
	else if($sortBy=="newest")
	{
		$_SESSION['SORtBy']='id';
		$_SESSION['sortOrder']='DESC';
	}
	if($_SESSION['SORtBy']=='clicks')
		$RssType='top';
	else if($_SESSION['SORtBy']=='originalPrice' || $_SESSION['SORtBy']=='id')
		$RssType='recent';	
	if(!isset($_SESSION['SORtBy']))
		$_SESSION['SORtBy']='id';
	if(!isset($_SESSION['sortOrder']))
		$_SESSION['sortOrder']='DESC';
$webdata=getWebDate();
?>

	<title><?php echo(getTitle()) ?></title>
	<meta name="description" content="<?php echo strip_tags($webdata['description'])?>" />
	<meta name="keywords" content="<?php echo ($webdata['metaTags'])?>" />
	<meta property="og:title" content="<?php echo $webdata['title']; ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo rootpath().'/home'?>" />
	<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
	<meta property="og:description" content="<?php echo strip_tags($webdata['description'])?>" /> 
	<meta property="og:site_name" content="<?php echo ($webdata['websiteName'])?>" />
<?php
include("include/header_under.php");
$featuredCount=mysql_num_rows(mysql_query("SELECT * FROM products WHERE featured='1' AND `status`='1'"));
?>
<section class="theme-card mtop50" style="padding: 0;">
<?php
if($featuredCount > 0){
?>
	<div class="content">
		<section class="content-header"><!--Page Header-->
			<h1><?php echo $lang_array['featured']?> <small><?php echo $lang_array['products']?></small></h1>
		</section>
	</div>
	<section>
	<div id="myCarousel" class="carousel slide" data-ride="carousel">
	  <!-- Wrapper for slides -->
	  <div class="col-xs-12">
		  <div class="carousel-inner">
		  <?php
			$i=0;
			$query=mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND p.`featured`='1' AND p.`status`='1' AND pl.`language`='$language' ORDER BY p.`id` ASC LIMIT ".featuredProductsLimit());
			while($row=mysql_fetch_array($query))
			{ 
				$title=$row['title'];
				$summary=truncateshortDescription($row['summary']);
			?>
			<div class="item">
			 <a href="<?php echo rootpath().'/'.productCategoryAndSubcategory($row['permalink']).'/'.$row["permalink"]?>.html"><img src="<?php echo rootpath() ?>/images/productImages/_<?php echo $row["image"] ?>" class="img-responsive" alt="<?php echo (strlen($title) > 60 ? substr($title,0,60).'...' : $title)?>"></a>
			   <div class="carousel-caption">
				<h4><a href="<?php echo rootpath().'/'.productCategoryAndSubcategory($row['permalink']).'/'.$row["permalink"]?>.html"><?php echo (strlen($title) > 60 ? substr($title,0,60).'...' : $title)?></a></h4>
				<p><?php echo $summary?></p>
			  </div>
			</div><!-- End Item -->
			<?php
			$i++;
			} ?>
		  </div>
	  </div><!-- End Carousel Inner -->
	<ul class="list-group visible-lg visible-md col-md-4 col-xs-12">
	<div class="lgi-scroll">
		<?php
		$j=0;
		$query=mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND p.`featured`='1' AND p.`status`='1' AND pl.`language`='$language' ORDER BY p.`id` ASC LIMIT ".featuredProductsLimit());
		while($row=mysql_fetch_array($query))
		{ 
			$title=$row['title'];
			$summary=truncateshortDescription($row['summary']);
		?>
		<li class="media list-group-item active" data-target="#myCarousel" data-slide-to="<?php echo $j?>">
			<a class="pull-left" href="#">
			  <img class="img-responsive lgi-thumb" src="<?php echo rootpath() ?>/images/productImages/<?php echo $row["image"] ?>" alt="<?php echo (strlen($title) > 10 ? substr($title,0,15).'...' : $title)?>">
			</a>
			<div class="media-body">
			  <h4 class="media-heading"><?php echo (strlen($title) > 10 ? substr($title,0,15).'...' : $title)?></h4>
			  <p class="by-author"><?php echo $summary?></p>
			</div>
		</li>
		<?php
		$j++;
		} ?>

	</ul>
	<div class="clearfix"></div>
	<br>
	<ul class="visible-xs visible-sm col-md-4 col-xs-12">
	<div class="lgi-scroll">
		<?php
		$j=0;
		$query=mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND p.`featured`='1' AND p.`status`='1' AND pl.`language`='$language' ORDER BY p.`id` ASC LIMIT ".featuredProductsLimit());
		while($row=mysql_fetch_array($query))
		{ ?>
		<li class="media list-group-item active" data-target="#myCarousel" data-slide-to="<?php echo $j?>">
			<a class="pull-left" href="#">
			  <img class="img-responsive lgi-thumb" src="<?php echo rootpath() ?>/images/productImages/<?php echo $row["image"] ?>" alt="<?php echo (strlen($row['title']) > 10 ? substr($row['title'],0,15).'...' : $row['title'])?>">
			</a>
			<div class="media-body">
			  <h4 class="media-heading"><?php echo (strlen($row['title']) > 10 ? substr($row['title'],0,15).'...' : $row['title'])?></h4>
			  <p class="by-author"><?php echo truncateshortDescription($row['summary'])?></p>
			</div>
		</li>
		<?php
		$j++;
		} ?>

	</ul>
	</div>

	  <!-- Controls -->
	  <div class="carousel-controls hidden">
		  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
			<span class="fa fa-chevron-left"></span>
		  </a>
		  <a class="right carousel-control" href="#myCarousel" data-slide="next">
			<span class="fa fa-chevron-right"></span>
		  </a>
	  </div>

	<?php
	} 
?>
	<div class="clearfix"></div>
	<?php
	if($adsdata['largeRect1Status'])
	{ ?>
		<div class="ad-tray-728 <?php echo ($adsdata['largeRect1StatusResponsive']  == '1' ? '' : 'hidden-xs hidden-sm') ?>">
			<?php echo $adsdata['largeRect1'];?>
		</div>
		<div class="clearfix"></div>
	<?php
	}
	?>
	
	<section class="content-header theme-card"><!--Page Header-->
		<h1>
			<?php echo ($_SESSION['SORtBy']=="clicks" ? $lang_array['hotest'] : ($_SESSION['SORtBy']=="id" ? $lang_array['newest'] : ($_SESSION['SORtBy']=="originalPrice" && $_SESSION['sortOrder']=="DESC" ? $lang_array['most_expensive'] : ($_SESSION['SORtBy']=="originalPrice" && $_SESSION['sortOrder']=="ASC" ? $lang_array['least_expensive'] : $lang_array['newest'])))) ?>
			<small><?php echo $lang_array['products']?></small>
		</h1>
		<div class="pCat pull-right">
			<button class="btn btn-default btn-sm"><?php echo $lang_array['sort_by']?> <i class="fa fa-caret-down"></i></button>
			<ul class="nav nav-pills navbar-right">
				<div class="btn-group">
					<a href="<?php echo rootpath()?>/home/newest" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['SORtBy']=="id" ? 'active' : "") ?>"><i class="fa fa-star"></i> <?php echo $lang_array['newest'] ; ?></a>
					
					<a href="<?php echo rootpath()?>/home/hotest" id="list" class="btn btn-default btn-sm <?php echo ($_SESSION['SORtBy']=="clicks" ? 'active' : "") ?>"><i class="fa fa-fire"></i> <?php echo $lang_array['hotest']; ?></a>
					
					<a href="<?php echo rootpath()?>/home/hprice" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['SORtBy']=="originalPrice" && $_SESSION['sortOrder']=="DESC" ? 'active' : "") ?>"><i class="fa fa-arrow-up"></i> <?php echo $lang_array['high_price'] ; ?></a>

					<a href="<?php echo rootpath()?>/home/lprice" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['SORtBy']=="originalPrice" && $_SESSION['sortOrder']=="ASC" ? 'active' : "") ?>"><i class="fa fa-arrow-down"></i> <?php echo $lang_array['low_price'] ; ?></a>
				</div>
			</ul>
		</div>
	</section>
		<?php
		$home=mysql_query("SELECT * FROM `categories` WHERE `parentId`=0 AND `status`=1 ORDER BY `displayOrder`");
		$num_rows = mysql_num_rows($home);  
		$i=1;
		if ($num_rows > 0)
		{
			while($rows = mysql_fetch_array($home))
			{
				$limit=$rows['limit'];
				if(enableRecentCache())
				{
					$recentCacheExpireTime = recentCacheExpireTime();
					$sOrder=$_SESSION['sortOrder'];
					$sortBy=$_SESSION['SORtBy'];
					$var = $rows['id']."home_".$sortBy.$sOrder.$language;
					$data = $cache->get($var);
					if($data==null)
					{
						$data = array();
						$subq=mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND p.`status`='1' AND p.`id` IN(SELECT `pid` FROM `procat` WHERE `cid` IN(SELECT `id` FROM `categories` WHERE `parentId`='".$rows['id']."') OR cid='".$rows['id']."') AND pl.`language`='$language' ORDER BY p.".$_SESSION['SORtBy'].' '.$_SESSION['sortOrder']." LIMIT $limit");
						while($row = mysql_fetch_array($subq))
						{
							$data[] = $row;  
						}
						$cache->set($var,$data,$recentCacheExpireTime);
					}
				}
				else
				{
					$data = array();
					$subq=mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND p.`status`='1' AND p.`id` IN(SELECT `pid` FROM `procat` WHERE `cid` IN(SELECT `id` FROM `categories` WHERE `parentId`='".$rows['id']."') OR cid='".$rows['id']."') AND pl.`language`='$language' ORDER BY p.".$_SESSION['SORtBy'].' '.$_SESSION['sortOrder']." LIMIT $limit");
					while($row = mysql_fetch_array($subq))
					{
						$data[] = $row;  
					}
				}
				if(count($data) > 0)
				{
					?>
					<div class="clearfix"></div>
					<div class="">
						<div class="categorie-heading">
							<div class="page-header">
								<h1>
									<?php echo $rows[$_SESSION['lanGuaGe']] ?>
									<a class="more-prd btn-buy" href="<?php echo rootpath() ?>/category/<?php echo $rows['permalink'] ?> "><?php echo $lang_array['show_more']?></a>
								</h1>
							</div>
						</div>
					<?php
					foreach($data as $row)
					{    
						$pid=$row['id'];
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
						$sql = "SELECT * FROM `currencySettings`";
						$qury = mysql_query($sql);
						while($rowp = mysql_fetch_array($qury))
						{     
							$crName = $rowp['crName'];                     
							$priceDollor= $rowp['priceDollor'];                          
							$show= $rowp['showPlace'];
							$originalPrice=cleanNum($originalPrice * $priceDollor);
							$salePrice=cleanNum($salePrice * $priceDollor);
							?>
							<div class="price"><p><?php echo ($show ? $crName .' '.$originalPrice : $originalPrice.''.$crName)?></p></div>
							<?php
						}
						?>
						<div class="img-adj">
							<a href="<?php echo rootpath().'/'.productCategoryAndSubcategory($row['permalink']).'/'.$row["permalink"]?>.html">
								<img class="loading" id="image-<?php echo $pid?>" src="<?php echo rootpath()  . '/images/productImages/' . $row["image"] ?>"  alt="<?php echo $title?>"/>
							</a>
						</div>
						<div class="caption">
						<h4>
							<a href="<?php echo rootpath().'/'.productCategoryAndSubcategory($row['permalink']).'/'.$row["permalink"]?>.html"> <?php echo $title?></a></h4>
							<p class="hidden-xs"><?php echo $summary?></p>
							
						</div>
						<div class="row well">
						<div class="clearfix"></div>
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
						<p><?php echo ($show ? $crName .' '.$originalPrice : $originalPrice.' '.$crName);?></p>
						
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
									  <?php echo  $views .' '.$lang_array['views']?>
								   </p>
								</div>
							</div>
						</div>
						</div> 		
					</div>
					<?php
					} 
				}
				else
				{
					?>
					<div class="panel-body related-products">
						<div class="clearfix"></div>
						<div class="row">
						   <div class="col-md-12">
							<div class="error-template">
								<h1><?php echo $lang_array['oop']?></h1>
								<h2><i class="fa fa-times-circle"></i> <?php echo $lang_array['no_product_found']?></h2>   
							</div>
						   </div>
						</div>
					</div>
					<?php
				}
				echo '';
				if($i==1){
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
				?>
				<?php
				}
				if($i==$num_rows)
				{
					?>
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
					<div class="panel-body"><br><br></div>
					<?php
				}
				$i++;
			} 
		}
		else
		{
			?>
			<div class="panel-body related-products">
				<div class="clearfix"></div>
				<div class="row">
					<div class="col-md-12">
						<div class="error-template">
							<h1><?php echo $lang_array['oop']?></h1>
							<h2><i class="fa fa-times-circle"></i> <?php echo $lang_array['no_product_found']?></h2>   
						</div>
					</div>
				</div>
			</div>
			<?php
		}
				echo '';
		?>  
  
	</div></div></div>
</section>
</section>
<div class="clearfix"></div>
<?php include("include/footer.php") ?>
               