<?php
error_reporting(0);
defined("APP") or die();
if(!isset($_SESSION)) 
	session_start();
include 'include/header.php';
$cache = phpFastCache();
$Product=mres(trim($this->Product));
$Product=substr_replace($Product, "", -5);
$adsdata=getAdsData();
$webdata=getWebDate();
$commentsData=getCommentsData();
$RssType='recent';
$language=$_SESSION['lanGuaGe'];
if(isset($Product) && isValidProduct($Product) && isUnblockProduct($Product))
{  
$productData=getProductData($Product,$language);
?>

	<title><?php echo $productData['title'] ?></title>
	<meta name="description" content="<?php echo strip_tags($productData['description'])?>" />
	<meta name="keywords" content="<?php echo ($productData['tags'])?>" />
	<meta property="og:title" content="<?php echo $productData['title']; ?>" />
	<meta property="og:type" content="product" />
	<meta property="og:url" content="<?php echo rootpath().'/'.productCategoryAndSubcategory(trim($Product)).'/'.trim($Product).'.html'?>" />
	<meta property="og:image" content="<?php echo rootpath().'/images/productImages/_'.$productData['image']?>" />
	<meta property="og:description" content="<?php echo strip_tags($productData['description'])?>" /> 
	<meta property="og:site_name" content="<?php echo ($webdata['websiteName'])?>" />
	
	<link rel="stylesheet" href="<?php echo rootpath()?>/style/css/smooth_products.css" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo rootpath()?>/style/js/smooth_products.js"></script>
<script>
	$(document).ready(function() {
	var productId='<?php echo productIdByPermalink($Product)?>';
	$.ajax
		({
			type:"POST",
			url: "<?php echo rootpath()?>/go",
			data: {'productId':productId}
		});
	});
	</script>
<?php
include("include/header_under.php");
	$prod=trim($Product);
	$productIdByPermalink = productIdByPermalink($prod);
		if(enableProductCache())
		{
			$var = productIdByPermalink($Product)."_product".$language;
			$row = $cache->get($var);
			$productCacheExpireTime = productCacheExpireTime();
			if($row == null)
			{
					$row = mysql_fetch_array(mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`='$productIdByPermalink' AND pl.`language`='$language' AND p.`id`=pl.`id`"));
					$cache->set($var, $row, $productCacheExpireTime);
			}
		}
		else
		{
			$row = mysql_fetch_array(mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`='$productIdByPermalink' AND pl.`language`='$language' AND p.`id`=pl.`id`"));
		}
			$productid= $row['id'];
			$productTitle=$row['title'] ;
			$productPermalink=$row['permalink'] ;
			$productDescription=$row['description'] ;
			$summary=$row['summary'] ;
			$originalPrice=$row['originalPrice'] ;
			$salePrice=$row['salePrice'] ;
			$image= $row['image'];
			$image1= $row['image1'];
			$image2= $row['image2'];
			$image3= $row['image3'];
			$image4= $row['image4'];
			$image5= $row['image5'];
			$date= $row['publishedDate'];
			$views= $row['views'];
			$url = $row['url'];
			$via=getdomain($url);
			$saleStatus=$row['saleStatus'];
			
			$title=$row['title'];
			$summary=truncateshortDescription($row['summary']);
			$description=$row['description'];
			$prodata = mysql_query("SELECT * FROM `procat` WHERE `pid`='$productid'");
			while($fetchpro=mysql_fetch_array($prodata))
			{
				$catid=$fetchpro['cid'];
				$catdata = mysql_query("SELECT * FROM `categories` WHERE `id`='$catid'");
				while($fetchcat=mysql_fetch_array($catdata))
				{
					$catname =$fetchcat['name'];
					$parentId=$fetchcat['parentId'];
					$catpermalink=$fetchcat['permalink'];
					$cat .= '<a href="'.rootpath().'/category/' . $fetchcat['permalink'] . '" title="View Products In ' . $fetchcat['name'] . '">'. $fetchcat['name'] .'</a> ,  '  ;
				} 
			}
	?>
<head>	
</head>  
<section class="theme-card mtop50">
	<section class="content-header">
		<div class="clearfix"></div>    
		<div class="breadcrumbs_box">
			<ol class="breadcrumb">
				<li><a href="<?php echo rootpath()?>/"><?php echo $lang_array['home'] ; ?></a></li>
				<?php echo productCategoryAndSubcategoryUrl($Product)?>
				<li class="active hidden-xs"><?php echo $title; ?></li>
			</ol>
		</div>
	</section>
	<section class="product-page">
		<div class="row mrg40">
			<div class="col-xs-12 visible-xs">
				 <h3 class="product-title"><?php echo $title; ?></h3>
				 <hr>
			</div>
			<div class="col-xs-12 col-sm-4 pull-left">
				<?php
				$filename1 = 'images/productImages/thumb1/'.$image1;
				$filename2 = 'images/productImages/thumb1/'.$image2;
				$filename3 = 'images/productImages/thumb1/'.$image3;
				$filename4 = 'images/productImages/thumb1/'.$image4;
				$filename5 = 'images/productImages/thumb1/'.$image5;
				?>
				<div class="sp-wrap">
				<?php
				if($image1 !="" && file_exists($filename1)) 
				{
				?>
				<a href="<?php echo rootpath()?>/images/productImages/thumb3/<?php echo $image1?>">
				<img src="<?php echo rootpath()?>/images/productImages/thumb2/<?php echo $image1?>" alt="<?php echo $title; ?>"></a>
				<?php
				}
				if($image2 !="" && file_exists($filename2)) 
				{
				?>
				<a href="<?php echo rootpath()?>/images/productImages/thumb3/<?php echo $image2?>">
				<img src="<?php echo rootpath()?>/images/productImages/thumb2/<?php echo $image2?>" alt="<?php echo $title; ?>"></a>
				<?php
				}
				if($image3 !="" && file_exists($filename3)) 
				{
				?>
				<a href="<?php echo rootpath()?>/images/productImages/thumb3/<?php echo $image3?>">
				<img src="<?php echo rootpath()?>/images/productImages/thumb2/<?php echo $image3?>" alt="<?php echo $title; ?>"></a>
				<?php
				}
				if($image4 !="" && file_exists($filename4)) 
				{
				?>
				<a href="<?php echo rootpath()?>/images/productImages/thumb3/<?php echo $image4?>">
				<img src="<?php echo rootpath()?>/images/productImages/thumb2/<?php echo $image4?>" alt="<?php echo $title; ?>"></a>
				<?php
				}
				if($image5 !="" && file_exists($filename5)) 
				{
				?>
				<a href="<?php echo rootpath()?>/images/productImages/thumb3/<?php echo $image5?>">
				<img src="<?php echo rootpath()?>/images/productImages/thumb2/<?php echo $image5?>" alt="<?php echo $title; ?>"></a>
				<?php
				}
				?>
				</div>
				<div class="clearfix"></div>
				<div class="show-product-detail">
					<?php
					// star rating 
					echo '<div class="ajax">';
					include("current-rating.php");
				    echo '</div>';
					?>                                                                
					<span class="s-p-views  pull-right">
						<?php echo $views; ?> <?php echo $lang_array['views'] ; ?>
					</span>
				</div>
				<script type="text/javascript">
					/* wait for images to load */
					$(document).ready(function() {
						$('.sp-wrap').smoothproducts();
					});
				</script>
				<div class="row social-btns socialBTNS">
					<div class="col-xs-12">
						<a target="_blank" class="social-btn fb-btn" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo curPageURL(); ?>">
							<i class="fa fa-facebook-square"></i>
						</a>
						<a target="_blank" class="social-btn twitter-btn" href="http://twitter.com/home?status=<?php echo(ucfirst($productTitle)); ?>%20-%20<?php  echo curPageURL(); ?>">
							<i class="fa fa-twitter"></i>
						</a>
						<a target="_blank" class="social-btn google-btn" href="https://plus.google.com/share?url=<?php echo curPageURL(); ?>">
							<i class="fa fa-google-plus"></i>
						</a>
						<a target="_blank" class="social-btn pin-btn" href="http://pinterest.com/pin/create/button/?url=<?php echo curPageURL(); ?>&amp;media=<?php echo(rootpath().'/images/productImages/_'.$image); ?>&amp;description=<?php echo(strip_tags($productDescription)); ?>">
							<i class="fa fa-pinterest"></i>
						</a>
						<a target="_blank" class="social-btn li-btn" href="http://www.linkedin.com/shareArticle?mini=true&amp;url=<?php	echo curPageURL();?>&amp;title=<?php echo($productTitle); ?>">
							<i class="fa fa-linkedin-square"></i>
						</a>
						<a target="_blank" class="social-btn red-btn" href="https://www.reddit.com/login?dest=http://www.reddit.com/submit?url=<?php echo curPageURL(); ?>/&amp;title=<?php echo($productTitle); ?>">
							<i class="fa fa-reddit"></i>
						</a>
						<a target="_blank" class="social-btn vk-btn" href="https://vk.com/share.php?url=<?php echo curPageURL(); ?>&amp;title=<?php echo($productTitle); ?>&amp;description=<?php echo(strip_tags($productDescription)); ?>&amp;image=https://www.reddit.com/login?dest=http://www.reddit.com/submit?url=<?php echo curPageURL(); ?>&amp;noparse=true">
							<i class="fa fa-vk"></i>
						</a>
						<a target="_blank" class="social-btn tumblr-btn" href="http://digg.com/submit?phase=2&amp;url=<?php echo curPageURL(); ?>">
							<i class="fa fa-tumblr"></i>
						</a>	
					</div>
				</div>


			</div>
			<div class="col-xs-12 col-sm-8 prd-bg pull-right">
				<h3 class="product-title hidden-xs"><?php echo $title; ?></h3>
				<hr class="hidden-xs">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 prd-detail-bg">
						<div class="Category-text col-xs-5"><i class="fa fa-folder-open"></i> <?php echo $lang_array['category'] ; ?>:</div>
						<div class="category-value col-xs-7">
							<?php echo catNameByPermalink($prod) ?>
						</div>
						<div class="clearfix"></div>
						<div class="via-text col-xs-5">
							<i class="fa fa-external-link"></i>  <?php echo $lang_array['via'] ; ?>:
						</div>
						<div class="via-value col-xs-7">
							<a href="<?php echo rootpath()?>/go/<?php echo $productid?>"><?php echo $via ; ?></a>
						</div>
						<div class="clearfix"></div>
						<div class="price-text col-xs-5">
							<i class="fa fa-money"></i> <?php echo $lang_array['price'] ; ?>:
						</div>
						<div class="price-value col-xs-7">
							<?php
							$sql = "SELECT * FROM `currencySettings`";
						    $qury = mysql_query($sql);
							while($rowp = mysql_fetch_array($qury))
							{     
								$crName = $rowp['crName'];                     
								$price_dollor= $rowp['priceDollor'];                          
								$show= $rowp['showPlace'];
								$originalPrice=cleanNum($originalPrice * $price_dollor);
								$salePrice=cleanNum($salePrice * $price_dollor);
								
							}
							if(!$saleStatus){ ?>
							<p><?php echo ($show ? $crName .' '.$originalPrice : $originalPrice.' '.$crName)?></p>
							<?php } ?>
						</div>
						<div class="clearfix"></div>
						<div class="price-text col-xs-5">
						</div>
						<div class="old-price-main col-xs-7">
						<?php
							if($saleStatus){ ?>
							<p class="price-value"> <?php echo ($salePrice ==0 ? '<span class="green-text">Free</span>' : ($show ? $crName .' '.$salePrice : $salePrice.' '.$crName))?></p>
							<strike><p><?php echo ($show ? $crName .' '.$originalPrice : $originalPrice.' '.$crName)?></p></strike>
							<?php }?>
						</div>
						
						<div class="clearfix"></div>
						<div class="col-xs-12 col-sm-7">
							<a href="<?php echo rootpath()?>/go/<?php echo $productid?>" target="_blank"><button type="button" class="btn btn-buy btn-md"><i class="fa fa-shopping-cart"></i> <?php echo $lang_array["buy_it_now"]?></button></a>
						</div>
						<div class="col-xs-12 col-sm-5"></div>
						
						<div class="clearfix"></div>		
					</div>
				</div>
				<div class="clearfix"></div>
				<div class="panel-body">
					<h4><?php echo $lang_array['summary'] ; ?>:</h4>
					<div class="product_des"><?php echo $summary; ?>	</div>			
					<div class="tag-cloud hidden-xs">
						<ul>
							<?php
							$tagsquery= mysql_query("SELECT `tags` FROM `products` WHERE `permalink`='$prod'");
							while($rowtags = mysql_fetch_array($tagsquery))
							{    
								$t= $rowtags['tags'];
								if ($t!="")
								{
									$tags= explode("," , $t);
									// echo $tags[0];
									foreach($tags as $tag)
									{
										$tag = trim($tag);            
										echo('<li><a href="'.rootpath().'/tags/'.str_replace(' ','-',$tag).'"><span></span>'.$tag.'</a></li>');
									}
								}
							}
							?>                                              
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
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
			</div>
			
			<div class="col-xs-12 product_des">
				<?php echo trim($description); ?>
			</div>
			
			<div class="col-xs-12 r-p">
			   <?php if(relatedProducts())
			   {
					if(enableRelatedCache())
						{
							$relatedCacheExpireTime = relatedCacheExpireTime();
							$sOrder=$_SESSION['SORTorder'];
							$sortBy=$_SESSION['SORTby'];
							$var = $productid.'_relatedProduct'.$language;
							$data = $cache->get($var);
							if($data==null)
							{
								$data = array();
								$query=mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE MATCH(p.`tags`,pl.`title`) AGAINST('" . $productTitle . " " .  trim(str_replace(","," ",$t)) . "' IN BOOLEAN MODE) AND p.`id` NOT IN($productid) AND pl.`language`='$language' AND p.`id`=pl.`id`");
									$countTotal=mysql_num_rows($query);
									if($countTotal >= relatedProductsLimit()) 
									{
										$query = mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE MATCH(p.`tags`,pl.`title`) AGAINST('" . $productTitle . " " .  trim(str_replace(","," ",$t)) . "' IN BOOLEAN MODE) AND p.`id` NOT IN($productid) AND pl.`language`='$language' AND p.`id`=pl.`id` LIMIT ".relatedProductsLimit());
									} 
									else
									{
										$newCount=relatedProductsLimit()-$countTotal;
										$query=mysql_query("(SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE MATCH(p.`tags`,pl.`title`) AGAINST('" . $productTitle . " " .  trim(str_replace(","," ",$t)) . "' IN BOOLEAN MODE) AND p.`id` NOT IN($productid) AND pl.`language`='$language' AND p.`id`=pl.`id` LIMIT $countTotal) UNION (SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id` IN(SELECT `pid` FROM `procat` WHERE `cid`='".catIdByProductId($productid)."' AND `pid`!='$productid') AND pl.`language`='$language' AND p.`id`=pl.`id` ORDER BY RAND() LIMIT $newCount)");
									}
							while($row = mysql_fetch_array($query))
							{
								$data[] = $row;  
							}
								$cache->set($var,$data,$relatedCacheExpireTime);
							}
						}
						else
						{
								$data = array();
								$query=mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE MATCH(p.`tags`,pl.`title`) AGAINST('" . $productTitle . " " .  trim(str_replace(","," ",$t)) . "' IN BOOLEAN MODE) AND p.`id` NOT IN($productid) AND pl.`language`='$language' AND p.`id`=pl.`id`");
									$countTotal=mysql_num_rows($query);
									if($countTotal >= relatedProductsLimit()) 
									{
										
										$query = mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE MATCH(p.`tags`,pl.`title`) AGAINST('" . $productTitle . " " .  trim(str_replace(","," ",$t)) . "' IN BOOLEAN MODE) AND p.`id` NOT IN($productid) AND pl.`language`='$language' AND p.`id`=pl.`id` LIMIT ".relatedProductsLimit());
									} 
									else 
									{
										$newCount=relatedProductsLimit()-$countTotal;
										
										$query=mysql_query("(SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE MATCH(p.`tags`,pl.`title`) AGAINST('" . $productTitle . " " .  trim(str_replace(","," ",$t)) . "' IN BOOLEAN MODE) AND p.`id` NOT IN($productid) AND pl.`language`='$language' AND p.`id`=pl.`id` LIMIT $countTotal) UNION (SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id` IN(SELECT `pid` FROM `procat` WHERE `cid`='".catIdByProductId($productid)."' AND `pid`!='$productid') AND pl.`language`='$language' AND p.`id`=pl.`id` ORDER BY RAND() LIMIT $newCount)");
									}
							while($row = mysql_fetch_array($query))
							{
								$data[] = $row;  
							}
						}
					if(count($data) > 0) {
					?>
					<div class="related-products">
					<?php echo $lang_array["you_may_also_like"]?> :
					</div>
					<?php 
					foreach($data as $fetch)
					{
						$title=$fetch['title'];
						$summary=truncateshortDescription($fetch['summary']);
						$sql = "SELECT * FROM `currencySettings` ";
						$qury = mysql_query($sql);
						while($rowp = mysql_fetch_array($qury))
						{     
							$crName = $rowp['crName'];                     
							$priceDollor= $rowp['priceDollor'];                          
							$show= $rowp['showPlace'];
							$originalPrice=cleanNum($fetch['originalPrice'] * $priceDollor);
							$salePrice=cleanNum($fetch['salePrice'] * $priceDollor);
						}
						?>
						<div class="col-xs-6 col-sm-4 col-md-4 col-lg-3">
							<div class="thumbnail">
								<div class="price"><p><?php echo ($show ? $crName .' '.$originalPrice : $originalPrice.' '.$crName)?></p></div>
								<div class="img-adj">
								<a href="<?php echo rootpath().'/'.productCategoryAndSubcategory($fetch["permalink"]).'/'.$fetch["permalink"].'.html'?>">
								<img class="loading" src="<?php echo (rootpath() . "/images/productImages/" . $fetch['image']);?>" alt="<?php echo $title; ?>"></a>
								</div>
								<div class="caption">
									<h4>
										<a href="<?php echo rootpath().'/'.productCategoryAndSubcategory($fetch["permalink"]).'/'.$fetch["permalink"].'.html'?>"><?php echo $title?></a>
									</h4>
									<p class="hidden-xs" style="word-wrap: break-word;">
									<?php echo $summary?>
									</p>
								</div>
								<div class="row well"> 
									<div class="col-lg-7 col-md-7 col-sm-7 col-xs-7 pull-left">
											<?php echo productRating($fetch["id"])?>
										</div>
										<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5 pull-right">
										<?php
										if($fetch['saleStatus']){ ?>
										<p><?php echo ($salePrice ==0 ? '<span class="green-text">Free</span>' : ($show ? $crName .' '.$salePrice : $salePrice.' '.$crName))?></p>
										<p class="old-price"> <strike><?php echo ($show ? $crName .' '.$originalPrice : $originalPrice.''.$crName)?></strike></p>
										<?php } else {
										?>
										<p><?php echo ($show ? $crName .' '.$originalPrice : $originalPrice.' '.$crName)?></p>
										
										<?php
										}?>
									</div>
								</div>
								<div class="thumb-footer">
									<div class="row">
										<div class="col-xs-7">
											<a class="product" href="<?php echo rootpath().'/go/'.$fetch['id']?>" target="_blank">
											<button type="button" class="btn btn-buy btn-sm buy_now" id="<?php echo $fetch['id']?>"><?php echo $lang_array["buy_it_now"]?></button>
											</a>
										</div>
									   
										<div class="col-xs-5">
										   <p class="detail pull-right">
											<?php echo $fetch['views'].' '.$lang_array['views']?>
										   </p>
										</div>
									</div> 
							   </div>
							</div>
						</div>
						<?php 
					}
					}
				} ?>
				<div class="clearfix"></div>
				<?php
				if($adsdata['largeRect3Status'])
				{ ?>
					<div class="ad-tray-728 <?php echo ($adsdata['largeRect2StatusResponsive']  == '1' ? '' : 'hidden-xs hidden-sm') ?>">
						<?php echo $adsdata['largeRect3'];?>
					</div>
					<div class="clearfix"></div>
				<?php
				}
				if($commentsData['productCommentStatus'])
				{
					if($commentsData['commentsActive']==1)
					{ ?>
					<div class="comments col-md-12">
						<div id="disqus_thread"></div>
						<script type="text/javascript">
							var disqus_shortname = '<?php echo disqusUserName()?>';
							(function() {
								var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
								dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
								(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
								
								var s = document.createElement('script'); s.async = true;
								s.type = 'text/javascript';
								s.src = 'http://' + disqus_shortname + '.disqus.com/count.js';
								(document.getElementsByTagName('HEAD')[0] || document.getElementsByTagName('BODY')[0]).appendChild(s);
							})();
						</script>
						<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
						<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
					</div>
					<?php
					}
					else
					{
						?>
						<div id="fb-root"></div>
						<script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
						<fb:comments href="<?php echo curPageURL()?>" num_posts="<?php echo $commentsData['fbCommentsLimit']?>" width="100%"></fb:comments>
						<?php
					}
				} 
				?>
			</div>
			</br>
		</div>	 
	</section>
</section>
	<?php  include 'include/footer.php'; 
}
else
{
	$found=1;
	include '404.php';
}
?>