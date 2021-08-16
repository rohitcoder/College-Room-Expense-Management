</head>
    <body>
		<div id="wrap">
        <header class="header">
			<nav class="navbar navbar-fixed-top" role="navigation">
			
			<a href="#" class="toggle-btn navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
				<span class="sr-only"><?php echo $lang_array['toggle_navigation']?></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>
			<a href="<?php echo rootpath()?>/" class="logo">
				<img src="<?php echo rootpath()?>/images/logo/<?php echo frontPageLogo().'?'.time(); ?>">
			</a>
				<!--Top Social Buttons-->
				<div class="navbar-left hidden-sm hidden-xs">
					<?php 
					$socialProfile=getSocialProfilesData();
					?>
					<div class="social-icons">
						<ul>
							<?php
							if($socialProfile['status']) { ?>
							<li class="facebook" style="background-color: #FBFBFB">
								<a href="http://www.facebook.com/<?php echo $socialProfile['facebook'] ?>" target="_blank"><?php echo $lang_array['facebook']?></a>
							</li>
							<li class="twitter" style="background-color: #FBFBFB">
								<a href="http://www.twitter.com/<?php echo $socialProfile['twitter'] ?>" target="_blank"><?php echo $lang_array['twitter']?></a>
							</li>
							<li class="googleplus" style="background-color: #FBFBFB">
								<a href="http://plus.google.com/<?php echo $socialProfile['google'] ?>" target="_blank"><?php echo $lang_array['google_plus']?></a>
							</li>
							<li class="pinterest" style="background-color: #FBFBFB">
								<a href="https://www.pinterest.com/<?php echo $socialProfile['pinterest'] ?>" target="_blank"><?php echo $lang_array['pinterest']?></a>
							</li>
							<?php
							}
							if(rssEnable()) 
							{ 
								?>
								<li class="rss" style="background-color: #FBFBFB">
									<?php
									if($RssType=='top' && rssTopEnable())
									{	?>
										<a href="<?php echo(rootpath()) ?>/rss/top/<?php echo $_SESSION['lanGuaGe']?>" target="_blank"><?php echo $lang_array['rss_feed']?></a>
										<?php
									}
									else if($RssType=='category' && rssCategoryEnable())
									{ 	?>
										<a href="<?php echo(rootpath()) ?>/rss/category/<?php echo (isParentCategory($getCategory) ?  $getCategory : categoryPermalinkByChild($getCategory).'/'.$getCategory)?>/<?php echo $_SESSION['lanGuaGe']?>" target="_blank"><?php echo $lang_array['rss_feed']?></a>
										<?php
									}
									else if($RssType=='tags' && rssTagsEnable())
									{ 	?>
										<a href="<?php echo(rootpath()) ?>/rss/tags/<?php echo trim($Tagname)?>/<?php echo $_SESSION['lanGuaGe']?>" target="_blank"><?php echo $lang_array['rss_feed']?></a>
										<?php
									} 
									else if(rssRecentEnable())
									{ 	
										?>
										<a href="<?php echo(rootpath()) ?>/rss/recent/<?php echo $_SESSION['lanGuaGe']?>" target="_blank"><?php echo $lang_array['rss_feed']?></a>
										<?php
									}
									?>
								</li>
								<?php
							} 
							?>
						</ul>
					</div>
				</div>
				<!--/Top Social Buttons-->
				<div class="navbar-right">
					<span class="pages-menu"><i class="fa fa-bars"></i> &nbsp;<i class="fa fa-caret-down"></i></span>
					<ul class="nav navbar-nav">
						<?php
						$qry=mysql_query("SELECT * FROM `pages` WHERE `status`=1 AND `showIn`='1' OR `showIn`='2' ORDER BY displayOrder");
						while($row=mysql_fetch_array($qry))
						{ ?>
						<li class="dropdown notifications-menu"><a class="selectedPage <?php echo ($Permalink==$row['permalink']) ? 'active' : ''?>" href="<?php echo rootpath().'/page/'.$row['permalink']?>"><?php echo $row[$_SESSION['lanGuaGe']]?></a></li>
						<?php
						}
						$qry=mysql_query("SELECT * FROM `links` WHERE `status`=1 AND `showIn`='1' OR `showIn`='2' ORDER BY `displayOrder`");
						while($row=mysql_fetch_array($qry))
						{ ?>
						<li class="dropdown notifications-menu"><a class="selectedPage <?php echo ($Permalink==$row['permalink']) ? 'active' : ''?>" href="<?php echo $row['url']?>" <?php echo ($row['newTab'] ? 'target="_blank"' : '')?>><?php echo $row[$_SESSION['lanGuaGe']]?></a></li>
						<?php
						}
						$pageName=curPageURL();
						$pageName=strrev($pageName);
						$data=explode('/',$pageName);
						$curPageName=strrev($data[0]);
						if(enableArticles())
						{
						?>
						<li>
							<a class="selectedPage <?php echo ($PageName==articleName()) ? 'active' : ''?>" href="<?php echo rootpath()?>/<?php echo articleName()?>/">
								<i class="fa fa-pencil"></i> <?php echo $lang_array['reviews']?>
							</a>
						</li>
						<?php
						}
						$socialLogin=socialLogin();
						if($socialLogin['allow']!=0)
						{
							if($_SESSION['21d72e65f75d499adb5d2b9f17fcf352']== 'security')
							{
							?>
							<li>
								<a class="selectedPage" href="<?php echo rootpath()?>/favourite">
									<i class="fa fa-heart"></i> <?php echo $lang_array['favourite']?>
								</a>
							</li>
							<li>
								<a class="selectedPage" href="<?php echo rootpath()?>/signout.php">
									<i class="fa fa-ban"></i> <?php echo $lang_array['signout']?>
								</a>
							</li>
							<?php
							}
							else
							{
							?>
							<li>
								<a class="selectedPage" href="#" data-toggle="modal" data-target="#login-modal">
									<i class="fa fa-sign-in"></i> <?php echo $lang_array['signin_signup']?>
								</a>
							</li>
							<?php
							}
						}
						?>
						
					</ul>
				</div>
			</nav>
		</header>
<?php
include "include/modal.php";
include "include/sidebar.php";
?>	   
<div class="clearfix"></div>