<?php
error_reporting(0);
if(!isset($_SESSION))
session_start();
ob_start();
include 'include/header.php';
$cache = phpFastCache();
$Search=mres(trim($this->Search));
if(isValidProduct($Search))
header('Location: '.rootpath().'/'.productCategoryAndSubcategory($Search).'/'.$Search.'.html');
$Search=xssClean(mres($Search));
$SoRTby=$this->SoRTby;
if(is_numeric($this->Page))
$Page=$this->Page;
$adsdata=getAdsData();
$language=$_SESSION['lanGuaGe'];
if(isset($Search) && trim($Search)!="")
{
	if(isset($SoRTby) && $SoRTby=="hprice") 
	{
		$_SESSION['SoRTby']='originalPrice';
		$_SESSION['SorTOrder']='DESC';
	}
	else if($SoRTby=="lprice") 
	{
		$_SESSION['SoRTby']='originalPrice';
		$_SESSION['SorTOrder']='ASC';
	}
	else if($SoRTby=="hotest") 
	{
		$_SESSION['SoRTby']='clicks';
		$_SESSION['SorTOrder']='DESC';
	}
	else if($SoRTby=="newest")
	{
		$_SESSION['SoRTby']='id';
		$_SESSION['SorTOrder']='DESC';
	}
	else if($SoRTby=="relevence")
	{
		$_SESSION['SoRTby']='relevence';
		$_SESSION['SorTOrder']='DESC';
	}
	if(!isset($_SESSION['SoRTby']))
		$_SESSION['SoRTby']='relevence';
	if(!isset($_SESSION['SorTOrder']))
		$_SESSION['SorTOrder']='DESC';
$webdata=getWebDate();
?>
	<title><?php echo('Search Result For '.trim(str_replace('-',' ',$Search)).(trim($Page) ? '-Page '.trim($Page):'')) ?></title>
	<meta name="description" content="<?php echo strip_tags($webdata['description'])?>" />
	<meta name="keywords" content="<?php echo ($webdata['metaTags'])?>" />
	<meta property="og:title" content="<?php echo $webdata['title']; ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo rootpath().'/search/'.trim($Search)?>" />
	<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
	<meta property="og:description" content="<?php echo strip_tags($webdata['description'])?>" /> 
	<meta property="og:site_name" content="<?php echo ($webdata['websiteName'])?>" />
<?php
include("include/header_under.php");
	$str=trim($Search);
	$str=str_replace("-",",",$str);
	$arraydata=explode(",",$str);
	$S = $arraydata;
	$L=1;
	for ($i = count($S); $i>=1; $i--) 
	{
		$a = $b = 0;
		while ($a < count($S)) 
		{
			$current = $S[$a++];
			$subset[] = $current;
			if (count($subset) == $i) 
			{
				$result .= json_encode($subset);
				array_pop($subset);
			}
		}
	}
	$rpAgain=str_replace('","',' ',$result);
	$afterReplace=str_replace('"]["',',',$rpAgain);
	$ltrim=ltrim($afterReplace,'["');
	$rtrim=rtrim($ltrim,'"],');
	$words = explode(',', $rtrim);
	$looplast=count($words)*2-2;
	for ($i = 0; $i < count($words); $i++)
	{
		$tagscount=count($words) + $i;
		$taglast=$tagscount+1;
		$search=$words[$i];
		$search=trim($search);
		$title .="pl.`title` LIKE '%".$search."%' THEN ".$i." WHEN ";
		if($tagscount <=$looplast)
		$tags .= "p.`tags` LIKE '%".$search."%' THEN ".$tagscount." WHEN ";
		else
		$tags .= "p.`tags` LIKE '%".$search."%' THEN ".$tagscount." ELSE ".$taglast." END";
	}
	$string1=str_replace(",","%' OR pl.`title` LIKE '%",trim($str));
	$string1="pl.`title` LIKE '%".$string1."%'";
	$string2=str_replace(",","%' OR p.`tags` LIKE '%",trim($str));
	$string2="p.`tags` LIKE '%".$string2."%'";
	$queryString="SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`status`='1' AND (" . $string1 .  " OR " . $string2 .  ") AND p.`id`=pl.`id` AND pl.`language`='$language'";
	$order=$title.$tags;
	$permalink = trim($Search);
	$page=1;//Default page
	$limit=productPerPage();//Records per page
	$next=2;
	$prev=1;
	$data=mysql_query($queryString);
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
		<h4><?php echo $lang_array['search_result_for']?> <b><?php echo str_replace("-"," ",$Search)?></b></h4>
			<small><?php echo ($_SESSION['SoRTby']=="clicks" ? $lang_array['hotest'] : ($_SESSION['SoRTby']=="id" ? $lang_array['newest'] : ($_SESSION['SoRTby']=="originalPrice" && $_SESSION['SorTOrder']=="DESC" ? $lang_array['most_expensive'] : ($_SESSION['SoRTby']=="originalPrice" && $_SESSION['SorTOrder']=="ASC" ? $lang_array['least_expensive'] : ($_SESSION['SoRTby']=="relevence" ? $lang_array['related']:$lang_array['related']))))).' '.$lang_array['products'];?></small>
			<div class="pCat pull-right">
			<button class="btn btn-default btn-sm"><?php echo $lang_array['sort_by']?> <i class="fa fa-caret-down"></i></button>
			<ul class="nav nav-pills navbar-right">
				<div class="btn-group">
					<a href="<?php echo rootpath().'/search/'.trim($Search).'/relevence'.(trim($Page) ? '/'.trim($Page):'') ?>" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['SoRTby']=="relevence" ? 'active' : "") ?>"><i class="fa fa-search-plus"></i> <?php echo $lang_array['relevence'] ; ?></a>
					
					<a href="<?php echo rootpath().'/search/'.trim($Search).'/newest'.(trim($Page) ? '/'.trim($Page):'') ?>" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['SoRTby']=="id" ? 'active' : "") ?>"><i class="fa fa-star"></i> <?php echo $lang_array['newest'] ; ?></a>
					
					<a href="<?php echo rootpath().'/search/'.trim($Search).'/hotest'.(trim($Page) ? '/'.trim($Page):'') ?>" id="list" class="btn btn-default btn-sm <?php echo ($_SESSION['SoRTby']=="clicks" ? 'active' : "") ?>"><i class="fa fa-fire"></i> <?php echo $lang_array['hotest']; ?></a>
					
					<a href="<?php echo rootpath().'/search/'.trim($Search).'/hprice'.(trim($Page) ? '/'.trim($Page):'') ?>" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['SoRTby']=="originalPrice" && $_SESSION['SorTOrder']=="DESC" ? 'active' : "") ?>"><i class="fa fa-arrow-up"></i> <?php echo $lang_array['high_price'] ; ?></a>

					<a href="<?php echo rootpath().'/search/'.trim($Search).'/lprice'.(trim($Page) ? '/'.trim($Page):'') ?>" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['SoRTby']=="originalPrice" && $_SESSION['SorTOrder']=="ASC" ? 'active' : "") ?>"><i class="fa fa-arrow-down"></i> <?php echo $lang_array['low_price'] ; ?></a>
				</div>
			</ul> 
		</div>
	</section>
	<br><br>
	<section class="content">
		<div class="row">  
			<?php
			$startResult = ($page-1)*$limit;
			if($_SESSION['SoRTby']=='relevence')
			{
				$queryString."ORDER BY CASE WHEN ".$order ." LIMIT ".$startResult.",".$limit;
				$qry =mysql_query($queryString." ORDER BY CASE WHEN ".$order ." LIMIT ".$startResult.",".$limit);
			}
			else 
			{
				$qry =mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`status`='1' AND (".$string1." OR ".$string2.") AND pl.language='$language' AND p.`id`=pl.`id` ORDER BY p.".$_SESSION['SoRTby']." ".$_SESSION['SorTOrder']."  LIMIT ".$startResult.",".$limit);
			}
			$num_rows = mysql_num_rows($qry);    
			$i=1;
			if ($num_rows > 0)
			{
				while($row = mysql_fetch_array($qry))
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
						<div class="price"><p><?php echo ($show ? $crName .' '.$originalPrice : $originalPrice.''.$crName)?></p></div>
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
							<a href="<?php echo rootpath().'/'.productCategoryAndSubcategory($row["permalink"]).'/'.$row["permalink"]?>.html"> <?php echo $title?></a>
						</h4>
						<p class="hidden-xs"><?php echo $summary?></p>
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
					<a href="<?php echo rootpath().'/search/'.trim($Search)?>"><?php echo $lang_array['first']?></a>
				</li>
				<?php if($page >1) { ?>
				<li>
					<a href="<?php echo rootpath().'/search/'.trim($Search).'/'.($prev)?>">&laquo;</a>
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
						echo('<li class="active"><a href="'.rootpath().'/search/'.trim($Search).'/' . $i . '">' . $i . '</a></li>');
					else
						echo('<li><a href="'.rootpath().'/search/'.trim($Search).'/' . $i . '">' . $i . '</a></li>');
				}
				if($page!=$last) { ?>
				<li>
					<a href="<?php echo rootpath().'/search/'.trim($Search).'/'.($next) ?>">&raquo;</a>
				</li>
				<?php } ?>
				<li>
					<a href="<?php echo rootpath().'/search/'.trim($Search).'/'.($last) ?>"><?php echo $lang_array['last']?></a>
				</li>
			</ul>
			<?php 
		} 
		?>
	</section>
	<?php 
	if($num_rows > 4 || $num_rows < 4)
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
	</section>
			<?php
		}
		include 'include/footer.php'; 
}
else
{
	$found=1;
	include '404.php';
}
?>