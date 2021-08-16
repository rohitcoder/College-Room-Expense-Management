<?php 
error_reporting(0);
if(!isset($_SESSION))
session_start();
include 'include/header.php';
if($_SESSION['21d72e65f75d499adb5d2b9f17fcf352'] != 'security')
{
	header('Location: ' .rootpath());
	exit();
}
if(is_numeric($this->Page))
$Page=xssClean(mres(trim($this->Page)));
$uid=$_SESSION['store_uid'];
$language=$_SESSION['lanGuaGe'];
$RssType='recent';
$webdata=getWebDate();
?>
	<title><?php echo($_SESSION['store_username'].' '.$lang_array['favourite_products']) ?></title>
	<meta name="description" content="<?php echo strip_tags($webdata['description'])?>" />
	<meta name="keywords" content="<?php echo ($webdata['metaTags'])?>" />
	<meta property="og:title" content="<?php echo $webdata['title']; ?>" />
	<meta property="og:type" content="article" />
	<meta property="og:url" content="<?php echo rootpath().'/favourite/'?>" />
	<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
	<meta property="og:description" content="<?php echo strip_tags($webdata['description'])?>" /> 
	<meta property="og:site_name" content="<?php echo ($webdata['websiteName'])?>" />
<?php
include("include/header_under.php");
	$page=1;
	$limit=productPerPage();
	$next=2;
	$prev=1;
	$data=mysql_query("SELECT p.*,f.* FROM `products` p,`favourite` f WHERE p.`status`='1' AND f.`uid`='$uid' AND p.`id`=f.`pid`");
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
	<div class="clearfix"></div></br>
	<section class="content">
	<div class="row">  
		<?php
		$start_result = ($page-1)*$limit;
		$data = array();
		$match = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl,`favourite` f WHERE p.`status`='1' AND p.`id`=pl.`id` AND p.`id`=f.`pid` AND pl.`language`='$language' AND f.`uid`='$uid' LIMIT ".$start_result.",".$limit;
		$qry = mysql_query($match);
		while($row = mysql_fetch_array($qry))
		{
			$data[] = $row;  
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
				<div class="col-xs-6 col-sm-4 col-md-4 col-lg-3 markASdel">
				<div class="thumbnail">
				<div class="favPrd active" id="<?php echo $pid?>"></div>
				<?php
				$sql = "SELECT * FROM `currencySettings` ";
				$qury = mysql_query($sql);
				while($rowp = mysql_fetch_array($qury))
				{     
					$crName = $rowp['crName'];                     
					$priceDollor= $rowp['priceDollor'];                          
					$show= $rowp['showPlace'];
					$originalPrice=$originalPrice * $priceDollor;
					$salePrice=$salePrice * $priceDollor;
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
			<a href="<?php echo rootpath().'/favourite/'?>"><?php echo $lang_array['first']?></a>
			</li>
			<?php if($page >1) { ?>
			<li>
				<a href="<?php echo rootpath().'/favourite/'.($prev)?>">&laquo;</a>
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
					echo('<li class="active"><a href="'.rootpath().'/favourite/' . $i . '">' . $i . '</a></li>');
				else
					echo('<li><a href="'.rootpath().'/favourite/' . $i . '">' . $i . '</a></li>');
			}
			if($page!=$last) { ?>
			<li><a href="<?php echo rootpath().'/favourite/'.($next) ?>">&raquo;</a></li>
			<?php } ?>
			<li><a href="<?php echo rootpath().'/favourite/'.($last) ?>"><?php echo $lang_array['last']?></a></li>
		</ul>
		<?php 
	} 
	?>
	</section>
	<?php
include 'include/footer.php';
?>