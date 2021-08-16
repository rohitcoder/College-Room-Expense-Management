<?php
error_reporting(0);
if(!isset($_SESSION)) 
session_start();
if(!isset($_SESSION['admin_eap']) && !isset($_SESSION['id']) && !isset($_SESSION['type']) && !isset($_SESSION['username']))
{
header("location: index.php");
}
$user_id= $_SESSION['id'];

if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf')
 {
function countAllProducts()
{
	$query = mysql_query("SELECT COUNT(id) AS `total` FROM `products`");
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function countCategory()
{
	$query = mysql_query("SELECT COUNT(id) AS `totalCategory` FROM `categories`");
	$fecth = mysql_fetch_array($query);
	return $fecth['totalCategory'];
}
function totalPagesCount()
{
	$query = mysql_query("SELECT COUNT(id) AS `totalPages` FROM `pages`");
	$fecth = mysql_fetch_array($query);
	return $fecth['totalPages'];
}
function totalLinksCount()
{
	$query = mysql_query("SELECT COUNT(id) AS `totalPages` FROM `links`");
	$fecth = mysql_fetch_array($query);
	return $fecth['totalPages'];
}
function moderatorUsers()
{
	$query = mysql_query("SELECT COUNT(id) AS `moderator` FROM `user` WHERE `type`=2");
	$fecth = mysql_fetch_array($query);
	return $fecth['moderator'];
}
function publisherUsers()
{
	$query = mysql_query("SELECT COUNT(id) AS `publisher` FROM `user` WHERE `type`=3");
	$fecth = mysql_fetch_array($query);
	return $fecth['publisher'];
}
function countUsers()
{
	$query = mysql_query("SELECT COUNT(id) AS `totalUsers` FROM `user` WHERE `type`!='1'");
	$fecth = mysql_fetch_array($query);
	return $fecth['totalUsers'];
}
function totalArticleCategories(){
	$sql = "SELECT COUNT(cid) AS `total` FROM `articleCategories`";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function totalArticles(){
	$sql = "SELECT COUNT(id) AS `total` FROM `articles`";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
} else{
function countAllProducts()
{
	$sql = "SELECT COUNT(id) AS `total` FROM`products` WHERE `userId`='".$_SESSION['id']."'";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
function totalArticles(){
	$sql = "SELECT COUNT(id) AS `total` FROM `articles` WHERE `uid`='".$_SESSION['id']."'";
	$query = mysql_query($sql);
	$fetch = mysql_fetch_array($query);
	return $fetch['total'];
}
}
?>    
				  <div class="sidebar-dropdown"><a href="#">MENU</a></div>
					<div class="sidey">                
						<div class="side-cont">
							<ul class="nav">
                            <!-- Main menu -->
								<?php if(basename($_SERVER['PHP_SELF'])=="dashboard.php"){ ?>								
								<li class="current">
								<?php } else { ?>
									<li class="">
								<?php } ?>
									<a href="dashboard.php"><i class="fa fa-bar-chart"></i> Website Stats</a></li>
								<?php
								if(basename($_SERVER['PHP_SELF'])=="add_product.php" || 										
								basename($_SERVER['PHP_SELF'])=="products.php" ||
								 basename($_SERVER['PHP_SELF'])=="edit_product.php" ||
								 basename($_SERVER['PHP_SELF'])=="bulk.php")
								{ ?> 
									<li class="has_submenu open current"> 
								<?php	} else { ?>
									<li class="has_submenu">
								<?php } ?>
									 <a href="#">
										<i class="fa fa-th"></i> Products
										<span class="caret pull-right"></span>
									 </a>
									 <!-- Sub menu -->
								     <!-- Sub menu -->
                                 <ul>
									 <li ><a href="add_product.php"><i class="fa fa-plus-square-o"></i> Add Product</a></li>
									  <li ><a href="bulk.php"><i class="fa fa-plus-square-o"></i> XML/Bulk Upload</a></li>
									 <li><a href="products.php"><i class="fa fa-th"></i> All Products (<?php echo(countAllProducts()) ?>)</a></li>
                                </ul>
								</li>
								<?php 
								if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf')
								{
								
								if(basename($_SERVER['PHP_SELF'])=="add_category.php" || 										
										basename($_SERVER['PHP_SELF'])=="edit_category.php" ||
										basename($_SERVER['PHP_SELF'])=="category_delete.php" ||
										basename($_SERVER['PHP_SELF'])=="categories.php")
								{ ?> 
									<li class="has_submenu open current"> 
								<?php	} else { ?>
									<li class="has_submenu">
								<?php } ?>
									 <a href="#">
										<i class="fa fa-folder-open"></i> Categories
										<span class="caret pull-right"></span>
									 </a>
									 <!-- Sub menu -->
									 <ul>
										<li><a href="add_category.php"><i class="fa fa-plus-square-o"></i> Add Category</a></li>
										<li><a href="categories.php"><i class="fa fa-folder-open"></i> All Categories (<?php echo(countCategory()) ?>)</a></li>
									</ul>
								</li>
								<?php }
								if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf')
								{
								 if(basename($_SERVER['PHP_SELF'])=="add_page.php" || 
									basename($_SERVER['PHP_SELF'])=="edit_page.php" ||
										basename($_SERVER['PHP_SELF'])=="pages.php")
								{ ?> 
									<li class="has_submenu open current"> 
								<?php	} else { ?>
									<li class="has_submenu">
								<?php } ?>
									<a href="#">
										<i class="fa fa-file"></i> Pages
										<span class="caret pull-right"></span>
									</a>
									<ul>
										<li><a href="add_page.php"><i class="fa fa-plus-square-o"></i> Add Page</a></li>
										<li><a href="pages.php"><i class="fa fa-file"></i> All Pages (<?php echo(totalPagesCount()) ?>)</a></li>
									</ul>
								</li>
								<?php }
								if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf')
								{
								 if(basename($_SERVER['PHP_SELF'])=="add_link.php" || 
									basename($_SERVER['PHP_SELF'])=="edit_link.php" ||
										basename($_SERVER['PHP_SELF'])=="links.php")
								{ ?> 
									<li class="has_submenu open current"> 
								<?php	} else { ?>
									<li class="has_submenu">
								<?php } ?>
									<a href="#">
										<i class="fa fa-code"></i> External Links
										<span class="caret pull-right"></span>
									</a>
									<ul>
										<li><a href="add_link.php"><i class="fa fa-plus-square-o"></i> Add Link</a></li>
										<li><a href="links.php"><i class="fa fa-code"></i> All Link (<?php echo(totalLinksCount()) ?>)</a></li>
									</ul>
								</li>
								<?php }
								if($_SESSION['type']!="")
								{
								 if(basename($_SERVER['PHP_SELF'])=="add_article.php" || 
									basename($_SERVER['PHP_SELF'])=="edit_article.php" ||
										basename($_SERVER['PHP_SELF'])=="articles.php" ||
										basename($_SERVER['PHP_SELF'])=="article_setting.php" || 
									basename($_SERVER['PHP_SELF'])=="add_articleCategory.php" ||
										basename($_SERVER['PHP_SELF'])=="edit_articleCategory.php" ||
										basename($_SERVER['PHP_SELF'])=="article_categories.php")
								{ ?> 
									<li class="has_submenu open current"> 
								<?php	} else { ?>
									<li class="has_submenu">
								<?php } ?>
									<a href="#">
										<i class="fa fa-pencil"></i> Articles
										<span class="caret pull-right"></span>
									</a>
									<ul>
									<?php if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf'){ ?>
									<li><a href="article_setting.php"><i class="fa fa-pencil"></i> Articles Setting </a></li> 
									<?php } ?>
									<li><a href="articles.php"><i class="fa fa-pencil"></i> Articles (<?php echo(totalArticles()) ?>)</a></li>
									<?php if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf'){ ?>
									<li><a href="article_categories.php"><i class="fa fa-folder-open"></i> Categories (<?php echo(totalArticleCategories()) ?>)</a></li>
									<?php } ?>
									</ul>
								</li>
								<?php }
								if(basename($_SERVER['PHP_SELF'])=="profile.php"){ ?>								
								<li class="current">
								<?php } else { ?>
									<li class="">
								<?php } ?>
									<a href="profile.php"><i class="fa fa-user"></i> My Profile</a></li>
									<?php
									if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf')
									{
								 if(basename($_SERVER['PHP_SELF'])=="add_user.php" ||
										basename($_SERVER['PHP_SELF'])=="edit_user.php" ||
										basename($_SERVER['PHP_SELF'])=="publisher_roles.php" ||
										basename($_SERVER['PHP_SELF'])=="users.php" ||
										basename($_SERVER['PHP_SELF'])=="user_delete.php")
								{ ?> 
									<li class="has_submenu open current"> 
								<?php	} else { ?>
									<li class="has_submenu">
								<?php } ?>
								<a href="#">
										<i class="fa fa-group"></i> Users
										<span class="caret pull-right"></span>
									</a>
									
									
									<ul>
								 <li><a href="add_user.php"><i class="fa fa-plus-square-o"></i> Add New</a></li>
							 <li><a href="publisher_roles.php"><i class="fa fa-group"></i> Publisher Roles</a></li>
							  <li><a href="users.php"><i class="fa fa-group"></i> All Users (<?php  echo(countUsers()) ?>)</a></li>
									</ul>
																										
								</li> 
								<?php } ?>
								
								
																
								<?php  if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf')
									{
								 if(basename($_SERVER['PHP_SELF'])=="settings.php" ||
								 
									basename($_SERVER['PHP_SELF'])=="media_settings.php" ||
									basename($_SERVER['PHP_SELF'])=="cache.php" ||
									basename($_SERVER['PHP_SELF'])=="social.php" ||
										basename($_SERVER['PHP_SELF'])=="ads.php" ||
										basename($_SERVER['PHP_SELF'])=="api_settings.php" ||
										basename($_SERVER['PHP_SELF'])=="currency.php" || 
										basename($_SERVER['PHP_SELF'])=="manage_captcha.php" || 
										basename($_SERVER['PHP_SELF'])=="manage_comments.php" ||
										basename($_SERVER['PHP_SELF'])=="publisher_roles.php" ||
										basename($_SERVER['PHP_SELF'])=="language_setting.php" ||
										basename($_SERVER['PHP_SELF'])=="add_language.php" ||
										basename($_SERVER['PHP_SELF'])=="edit_language.php" ||
										basename($_SERVER['PHP_SELF'])=="analytics.php" ||
										basename($_SERVER['PHP_SELF'])=="sitemaps.php" ||
										basename($_SERVER['PHP_SELF'])=="rss_settings.php")
								{  ?> 
									<li class="has_submenu open current"> 
								<?php	} else { ?>
									<li class="has_submenu">
								<?php  } ?> 
									<a href="#">
										<i class="fa fa-cogs"></i> Website Settings
										<span class="caret pull-right"></span>
									</a>
									<ul>
						<li><a href="settings.php"><i class="fa fa-cog"></i> General Settings</a></li>
						<li><a href="media_settings.php"><i class="fa fa-bullhorn"></i> Media Settings</a></li>
						<li><a href="cache.php"><i class="fa fa-barcode"></i> Cache Settings</a></li>
						<li><a href="social.php"><i class="fa fa-group"></i> Social Profiles </a></li>
						<li><a href="ads.php"><i class="fa fa-code"></i> Ads Management </a></li>
						<li><a href="api_settings.php"><i class="fa fa-cogs"></i> Api Settings </a></li>
						<li><a href="currency.php"><i class="fa fa-money"></i> Currency Settings </a></li>
						<li><a href="manage_captcha.php"><i class="fa fa-eye-slash"></i> Captcha Settings </a></li>
						<li><a href="manage_comments.php"><i class="fa fa-comment"></i> Comments Settings </a></li>
						<li><a href="language_setting.php"><i class="fa fa-language"></i> Language Settings </a></li> 
						<li><a href="analytics.php"><i class="fa fa-code"></i> Analytics Settings </a></li> 
						<li><a href="sitemaps.php"><i class="fa fa-sitemap"></i> Sitemaps</a></li>
						<li><a href="rss_settings.php"><i class="fa fa-rss"></i> RSS Settings</a></li>
									</ul>
								</li>
								
								<?php } ?>
							
	
								
                             <li><a href="logout.php"><i class="fa fa-power-off"></i>  Logout</a></li>
							</ul>
						</div>
					</div>