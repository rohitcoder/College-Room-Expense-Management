<?php
error_reporting(0);
defined("APP") or die();
if(!isset($_SESSION))
	session_start();
include 'include/header.php';
$cache = phpFastCache();
$category=xssClean(mres(trim($this->categoryName)));
if(is_numeric($this->Page))
$Page=$this->Page;
$sortBy=xssClean(mres(trim($this->SORTby)));
$sorTORDER=xssClean(mres(trim($this->sorTORDER)));
$categoryData=getArticleCategoryData($category);
$webdata=getWebDate();
$adsdata=getAdsData();
$pagedata=getPageData(trim($Permalink));
$language=$_SESSION['lanGuaGe'];
if(isset($category))
	$_SESSION['category']=$category;
if(!isset($_SESSION['sorTBY']))
{
  $_SESSION['sorTBY']='date';
  $_SESSION['sorTORDER']='desc';
}
if(isset($sortBy) && $sortBy!="")
{
if($sortBy=='title')
	$_SESSION['sorTBY']=$language;
else
   $_SESSION['sorTBY']=$sortBy;
}
if(isset($sorTORDER) && $sorTORDER!="")
{
  $_SESSION['sorTORDER']=$sorTORDER;
}
?>
<title><?php echo $categoryData[$language]?> <?php echo(getTitle()) ?></title>
<meta name="description" content="<?php echo strip_tags($categoryData['description'])?>" />
<meta name="keywords" content="<?php echo ($categoryData['keywords'])?>" />
<meta property="og:title" content="<?php echo $categoryData['name']; ?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo rootpath().'/category/'.$category?>" />
<meta property="og:image" content="<?php echo rootpath().'/images/cover.jpg'?>" />
<meta property="og:description" content="<?php echo strip_tags($categoryData['description'])?>" /> 
<meta property="og:site_name" content="<?php echo ($webdata['websiteName'])?>" />
<?php
$PageName=articleName();
require 'include/header_under.php';
$page=1;//Default page
$limit=articlesPerPage();//Records per page
$next=2;
$prev=1;
$cid=articleCategoryIdByPermalink($category);
$query=mysql_query("SELECT a.*,al.* FROM `articles` a,`articlesLanguage` al WHERE al.`language`='$language' AND a.`status`='1' AND `cid`='$cid' AND a.`id`=al.`id`");
$rows = mysql_num_rows($query);
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
	<div class="row">
		<div class="col-lg-12">
			<ol class="breadcrumb">
				<li><a href="<?php echo rootpath()?>/<?php echo articleName()?>"><i class="fa fa-home"></i> <?php echo $lang_array['home']?></a></li><li> <a href="<?php echo rootpath()?>/<?php echo articleName()?>category/<?php echo $category?>"><?php echo articleCategoryNameByPermalink($category)?></a></li>
			</ol>
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
			<div class="pCat pull-right">
			<button class="btn btn-default btn-sm"><?php echo $lang_array['sort_by']?> <i class="fa fa-caret-down"></i></button>
			<ul class="nav nav-pills navbar-right" style="margin: 0;">
				<div class="btn-group">
					<a href="<?php echo rootpath().'/'.articleName().'category/'.$category.'/'.(trim($_SESSION['sorTORDER']=='asc' ? 'desc' : 'asc')).(trim($Page) ? '/'.trim($Page):'') ?>" id="grid" class="btn btn-default btn-sm">&nbsp;<i class="fa <?php echo (trim($_SESSION['sorTORDER']=='asc' ? 'fa-sort-alpha-desc' : 'fa-sort-alpha-asc'))?>"></i>&nbsp;</a>
					<a href="<?php echo rootpath().'/'.articleName().'category/'.$category.'/date'.(trim($Page) ? '/'.trim($Page):'')?>" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['sorTBY']=="date" ? 'active' : "") ?>"><i class="fa fa-calendar"></i> <?php echo $lang_array['date'] ; ?></a>
				
					<a href="<?php echo rootpath().'/'.articleName().'category/'.$category.'/views'.(trim($Page) ? '/'.trim($Page):'')?>" id="list" class="btn btn-default btn-sm <?php echo ($_SESSION['sorTBY']=="views" ? 'active' : "") ?>"><i class="fa fa-eye"></i> <?php echo $lang_array['views']; ?></a>
					
					<a href="<?php echo rootpath().'/'.articleName().'category/'.$category.'/rating'.(trim($Page) ? '/'.trim($Page):'')?>" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['sorTBY']=="rating" ? 'active' : "") ?>"><i class="fa fa-star-o"></i> <?php echo $lang_array['rating'] ; ?></a>

					<a href="<?php echo rootpath().'/'.articleName().'category/'.$category.'/title'.(trim($Page) ? '/'.trim($Page):'')?>" id="grid" class="btn btn-default btn-sm <?php echo ($_SESSION['sorTBY']=="title" ? 'active' : "") ?>"><i class="fa fa-language"></i> <?php echo $lang_array['a-z'] ; ?></a>
				</div>
			</ul> 
			</div>
			
			<div class="clearfix"></div>
			
			<section class="content review">
			<?php
			$startResult = ($page-1)*$limit;
			$sortBy=$_SESSION['sorTBY'];
			$sortOrder=$_SESSION['sorTORDER'];
			$cid=articleCategoryIdByPermalink($category);
			if(enableArticleCache())
			{
			$articleCacheExpireTime = articleCacheExpireTime();
			$var = $cid."_article_".$sortBy.$sortOrder.$page.$language;
			$data = $cache->get($var);
			if($data==null)
			{
				$data = array();
				$query =mysql_query("SELECT a.*,al.* FROM `articles` a,`articlesLanguage` al WHERE al.`language`='$language' AND a.`status`='1' AND a.`cid`='$cid' AND a.`id`=al.`id` ORDER BY a.`$sortBy` $sortOrder LIMIT ".$startResult.",".$limit);
				while($row = mysql_fetch_array($query))
				{
					$data[] = $row;  
				}
				$cache->set($var,$data,$articleCacheExpireTime);
			}
			}
			else
			{
				$data = array();
				$query =mysql_query("SELECT a.*,al.* FROM `articles` a,`articlesLanguage` al WHERE al.`language`='$language' AND a.`status`='1' AND a.`cid`='$cid' AND a.`id`=al.`id` ORDER BY a.`$sortBy` $sortOrder LIMIT ".$startResult.",".$limit);
				while($row = mysql_fetch_array($query))
				{
					$data[] = $row;  
				}
			}
				
			$num_rows = mysql_num_rows($query);    
			if ($num_rows > 0)
			{
			foreach($data as $fetch)
			{ ?>
				<div class="media">
				  <div class="media-left pull-left">
					<div class="review-img">
						<a href="<?php echo rootpath()?>/<?php echo 'show'.articleName()?>/<?php echo $category?>/<?php echo $fetch['permalink']?>">
						  <img src="<?php echo rootpath()?>/images/articleImages/<?php echo $fetch['image']?>" class="img-responsive">
						</a>
					</div> 
				  </div>
				  <div class="media-body">
					<h2 class="media-heading"><a href="<?php echo rootpath().'/show'.articleName().'/'.articleCategoryPermalinkById($fetch['cid'])?>/<?php echo $fetch['permalink']?>"><?php echo ucfirst($fetch[$language])?></a></h2>
					<?php echo articleRating($fetch['id'])?>
					<div class="clearfix"></div>
					<div class="post-des">
						<span><a href="<?php echo rootpath()?>/<?php echo 'show'.articleName()?>/<?php echo articleName()?>/<?php echo getUsername($fetch['uid'])?>"><?php echo getUsername($fetch['uid'])?></a></span> 
						<span><?php echo $lang_array['on']?>  <i class="fa fa-clock-o"></i> <?php echo $fetch['date']?></span>
						<span> <?php echo $lang_array['in']?>  <i class="fa fa-file"></i> <a href="<?php echo rootpath()?>/<?php echo articleName()?>category/<?php echo articleCategoryPermalinkById($fetch['cid'])?>"><?php echo articleCategoryNameById($fetch['cid']);?></a></span>
					</div>
					<p><?php echo truncateArticleShortDescription($fetch['summary'])?></p>
					<a href="<?php echo rootpath()?>/<?php echo 'show'.articleName()?>/<?php echo $category?>/<?php echo $fetch['permalink']?>"><?php echo $lang_array['read_more'];?></a>
				  </div>
				</div>
			<?php 
			} 
			if($rows > $limit) 
			{ 
			?>
				<ul class="pagination">
					<li>
						<a href="<?php echo rootpath().'/'.articleName().'category/'.$category.'/'?>"><?php echo $lang_array['first']?></a>
					</li>
					<?php if($page >1) { ?>
					<li>
						<a href="<?php echo rootpath().'/'.articleName().'category/'.$category.'/'.($prev)?>">&laquo;</a>
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
							echo('<li class="active"><a href="'.rootpath().'/'.articleName().'category/'.$category.'/' . $i . '">' . $i . '</a></li>');
						else
							echo('<li><a href="'.rootpath().'/'.articleName().'category/'.$category.'/' . $i . '">' . $i . '</a></li>');
					}
					if($page!=$last) { ?>
					<li>
						<a href="<?php echo rootpath().'/'.articleName().'category/'.$category.'/'.($next) ?>">&raquo;</a>
					</li>
					<?php } ?>
					<li>
						<a href="<?php echo rootpath().'/'.articleName().'category/'.$category.'/'.($last) ?>"><?php echo $lang_array['last']?></a>
					</li>
				</ul>
				<?php 
			}
			} 
			else 
			{ ?>
			<div class="panel-body related-products">
				<div class="clearfix"></div>
				<div class="row">
					
					<div class="col-md-12">
						<div class="row">
						   <div class="col-md-12">
							<div class="error-template">
								<h1><?php echo $lang_array['oop']?></h1>
								<h2><i class="fa fa-times-circle"></i> <?php echo $lang_array['no_article_found']?></h2>   
							</div>
						   </div>
						</div>
					</div>
				</div>
			</div>
			<?php
			}?>
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
	</div>
	</section>
</section>
		
	<?php include 'include/footer.php';?>