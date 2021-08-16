<?php
error_reporting(0);
defined("APP") or die();
include 'include/header.php';
$cache = phpFastCache();
$category=xssClean(mres(trim($this->Category)));
$Permalink=xssClean(mres(trim($this->Article)));
articleViews($Permalink);
$webdata=getWebDate();
$adsdata=getAdsData();
$commentsData=getCommentsData();
$language=$_SESSION['lanGuaGe'];
$articleData=getArticleData($Permalink,$language);
?>
<title><?php echo $articleData[$language]?></title>
<meta name="description" content="<?php echo strip_tags($articleData['description'])?>" />
<meta name="keywords" content="<?php echo ($webdata['metaTags'])?>" />
<meta property="og:title" content="<?php echo $articleData[$language]; ?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo rootpath().'/'.$category.'/'.$Permalink?>" />
<meta property="og:image" content="<?php echo rootpath().'/images/articleImages/_'.$articleData['image']?>" />
<meta property="og:description" content="<?php echo strip_tags($articleData['description'])?>" /> 
<meta property="og:site_name" content="<?php echo ($webdata['websiteName'])?>" />
<?php
$PageName=articleName();
require 'include/header_under.php';
	?>   
<section class="theme-card mtop50">
	<section class="content-header">
	<div class="row">
		<div class="col-lg-12">
			<?php
			$id=getArticleIdByPermalink($Permalink);
			if(enableArticleCache())
			{
				$var = $id.'_article'.$language;
				$fetch = $cache->get($var);
				$articleCacheExpireTime = articleCacheExpireTime(); 
				if($fetch == null)
				{
						$fetch=mysql_fetch_array(mysql_query("SELECT a.*,al.* FROM `articles` a,`articlesLanguage` al WHERE a.`status`='1' AND a.`permalink`='$Permalink' AND al.`language`='$language' AND a.`id`=al.`id`"));
						$cache->set($var, $fetch, $articleCacheExpireTime);
				}
			}
			else
			{
				$fetch=mysql_fetch_array(mysql_query("SELECT a.*,al.* FROM `articles` a,`articlesLanguage` al WHERE a.`status`='1' AND a.`permalink`='$Permalink' AND al.`language`='$language' AND a.`id`=al.`id`"));
			}
			?>
			<ol class="breadcrumb">
				<li><a href="<?php echo rootpath()?>/<?php echo articleName()?>"><i class="fa fa-home"></i> <?php echo $lang_array['home']?></a></li><li><a href="<?php echo rootpath()?>/<?php echo articleName()?>category/<?php echo $category?>"><?php echo articleCategoryNameByPermalink($category)?></a></li><li> <?php echo $fetch[$language]?></li>
			</ol>
			<section class="content reviewShow">
				<h3><?php echo ucfirst($fetch[$language])?></h3>
				<div class="s-r">
					<?php
					   // star rating 
					   echo '<div class="ajax">';
						include("article-rating.php");
					   echo '</div>';
					?>                                                                
					<span class="s-p-views">
						<?php echo $fetch['views']; ?> <?php echo $lang_array['views'] ; ?>
					</span>
				</div>
				<div class="clearfix"></div>
				<div class="post-des">
					<span><a href="<?php echo rootpath()?>/<?php echo articleName()?>/<?php echo getUsername($fetch['uid'])?>"><?php echo getUsername($fetch['uid'])?></a></span>
					<span><?php echo $lang_array['on']?>  <i class="fa fa-clock-o"></i> <?php echo $fetch['date']?></span>
					<span> <?php echo $lang_array['in']?>  <i class="fa fa-file"></i> <a href="<?php echo rootpath()?>/<?php echo articleName()?>category/<?php echo articleCategoryPermalinkById($fetch['cid'])?>"><?php echo articleCategoryNameById($fetch['cid']);?></a></span>
				</div>
				<br>
				<p><?php echo truncateArticleLongDescription($fetch['description'])?></p>
			</section>
		</div>
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
		<div>
		<div class="clearfix"></div>
				<?php if($commentsData['articleCommentStatus'])
				{
					if($commentsData['commentsActive']==1 && disqusUserName())
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
					else if($commentsData['fbCommentsLimit'])
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
		<div class="clearfix"></div>
	</div>
	</section>
</section>
		
	<?php include 'include/footer.php';?>