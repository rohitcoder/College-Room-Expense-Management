<?php
error_reporting(0);
include '../config/config.php';
include '../common/functions.php';
$limit=5;
if(isset($_POST['week']))
{
	if(isset($_POST['next']))
	{
		$i=$_POST['id'] + 1;
		$startResult=$_POST['next'];
		$msql= "SELECT h.*,p.*,pl.* FROM `hotProducts` h,`products` p,`productsLanguage` pl WHERE h.`productId`=p.`id` AND h.`weekUpdateDate`='".getWeekUpdateDate()."' AND p.`id`=pl.`id` AND pl.language='english' ORDER BY h.`weeklyClicks` DESC LIMIT " . $startResult . "," . $limit;
		$sql = mysql_query($msql);	
	}
	else if(isset($_POST['previous']))
	{
		$i=$_POST['id']-5;
		$startResult=$_POST['previous']-6;
		$msql= "SELECT h.*,p.*,pl.* FROM `hotProducts` h,`products` p,`productsLanguage` pl WHERE h.`productId`=p.`id` AND h.`weekUpdateDate`='".getWeekUpdateDate()."' AND p.`id`=pl.`id` AND pl.language='english' ORDER BY h.`weeklyClicks` DESC LIMIT " . $startResult . "," . $limit;
		$sql = mysql_query($msql);
	}
	else if(isset($_POST['first']))
	{
		$i=1;
		$msql= "SELECT h.*,p.*,pl.* FROM `hotProducts` h,`products` p,`productsLanguage` pl WHERE h.`productId`=p.`id` AND h.`weekUpdateDate`='".getWeekUpdateDate()."' AND p.`id`=pl.`id` AND pl.language='english' ORDER BY h.`weeklyClicks` DESC LIMIT 0," . $limit;
		$sql = mysql_query($msql);
	}
	else if(isset($_POST['last']))
	{
		$i=$_POST['id'];
		$startResult=$_POST['last']-1;
		$msql= "SELECT h.*,p.*,pl.* FROM `hotProducts` h,`products` p,`productsLanguage` pl WHERE h.`productId`=p.`id` AND h.`weekUpdateDate`='".getWeekUpdateDate()."' AND p.`id`=pl.`id` AND pl.language='english' ORDER BY h.`weeklyClicks` DESC LIMIT " . $startResult . "," . $limit;
		$sql = mysql_query($msql);
	}
	while($proResult=mysql_fetch_array($sql))
	{
		if($proResult['title'] !="")
		{
			?>
			<tr class="record" id="<?php echo $i?>">
				<td class="width"><?php echo $i;?></td>
				<td>
					<a href="<?php echo(rootpath() . '/'.productCategoryAndSubcategory($proResult['permalink']).'/' . $proResult['permalink'] . '.html')?>">
						<?php
						if (strlen($proResult['title']) > 37)
						{
							$stringCut = substr($proResult['title'], 0, 37);
							$proResult['title'] = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
						}
						echo $proResult['title'] ?>
					</a>
				</td>
				<td class="width"><?php echo number_format($proResult['weeklyClicks']) ?></td>
			</tr>
			<?php
			$i++;
		}
	}
}
else if(isset($_POST['month']))
{
	if(isset($_POST['next']))
	{
		$i=$_POST['id'] + 1;
		$startResult=$_POST['next'];
		$msql= "SELECT h.*,p.*,pl.* FROM `hotProducts` h,`products` p,`productsLanguage` pl WHERE h.`productId`=p.`id` AND h.`monthUpdateDate`='".getMonthUpdateDate()."' AND p.`id`=pl.`id` AND pl.language='english' ORDER BY h.`monthlyClicks` DESC LIMIT " . $startResult . "," . $limit;
		$sql = mysql_query($msql);	
	}
	else if(isset($_POST['previous']))
	{
		$i=$_POST['id']-5;
		$startResult=$_POST['previous']-6;
		$msql= "SELECT h.*,p.*,pl.* FROM `hotProducts` h,`products` p,`productsLanguage` pl WHERE h.`productId`=p.`id` AND h.`monthUpdateDate`='".getMonthUpdateDate()."' AND p.`id`=pl.`id` AND pl.language='english' ORDER BY h.`monthlyClicks` DESC LIMIT " . $startResult . "," . $limit;
		$sql = mysql_query($msql);
	}
	else if(isset($_POST['first']))
	{
		$i=1;
		$msql= "SELECT h.*,p.*,pl.* FROM `hotProducts` h,`products` p,`productsLanguage` pl WHERE h.`productId`=p.`id` AND h.`monthUpdateDate`='".getMonthUpdateDate()."' AND p.`id`=pl.`id` AND pl.language='english' ORDER BY h.`monthlyClicks` DESC LIMIT 0," . $limit;
		$sql = mysql_query($msql);
	}
	else if(isset($_POST['last']))
	{
		$i=$_POST['id'];
		$startResult=$_POST['last']-1;
		$msql= "SELECT h.*,p.*,pl.* FROM `hotProducts` h,`products` p,`productsLanguage` pl WHERE h.`productId`=p.`id` AND h.`monthUpdateDate`='".getMonthUpdateDate()."' AND p.`id`=pl.`id` AND pl.language='english' ORDER BY h.`monthlyClicks` DESC LIMIT " . $startResult . "," . $limit;
		$sql = mysql_query($msql);
	}
	while($proResult=mysql_fetch_array($sql))
	{
		if($proResult['title'] !="")
		{
			?>
			<tr class="mrecord" id="<?php echo $i?>">
				<td class="width"><?php echo $i;?></td>
				<td>
					<a href="<?php echo(rootpath() . '/'.productCategoryAndSubcategory($proResult['permalink']).'/' . $proResult['permalink'] . '.html')?>">
						<?php
						if (strlen($proResult['title']) > 37)
						{
							$stringCut = substr($proResult['title'], 0, 37);
							$proResult['title'] = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
						}
						echo $proResult['title'] ?>
					</a>
				</td>
				<td class="width"><?php echo number_format($proResult['monthlyClicks']) ?></td>
			</tr>
			<?php
			$i++;
		}
	}
}
else if(isset($_POST['year']))
{
	if(isset($_POST['next']))
	{
		$i=$_POST['id'] + 1;
		$startResult=$_POST['next'];
		$msql= "SELECT h.*,p.*,pl.* FROM `hotProducts` h,`products` p,`productsLanguage` pl WHERE h.`productId`=p.`id` AND p.`id`=pl.`id` AND pl.language='english' ORDER BY h.`alltimeClicks` DESC LIMIT " . $startResult . "," . $limit;
		$sql = mysql_query($msql);	
	}
	else if(isset($_POST['previous']))
	{
		$i=$_POST['id']-5;
		$startResult=$_POST['previous']-6;
		$msql= "SELECT h.*,p.*,pl.* FROM `hotProducts` h,`products` p,`productsLanguage` pl WHERE h.`productId`=p.`id` AND p.`id`=pl.`id` AND pl.language='english' ORDER BY h.`alltimeClicks` DESC LIMIT " . $startResult . "," . $limit;
		$sql = mysql_query($msql);
	}
	else if(isset($_POST['first']))
	{
		$i=1;
		$msql= "SELECT h.*,p.*,pl.* FROM `hotProducts` h,`products` p,`productsLanguage` pl WHERE h.`productId`=p.`id` AND p.`id`=pl.`id` AND pl.language='english' ORDER BY h.`alltimeClicks` DESC LIMIT 0," . $limit;
		$sql = mysql_query($msql);
	}
	else if(isset($_POST['last']))
	{
		$i=$_POST['id'];
		$startResult=$_POST['last']-1;
		$msql= "SELECT h.*,p.*,pl.* FROM `hotProducts` h,`products` p,`productsLanguage` pl WHERE h.`productId`=p.`id` AND p.`id`=pl.`id` AND pl.language='english' ORDER BY h.`alltimeClicks` DESC LIMIT " . $startResult . "," . $limit;
		$sql = mysql_query($msql);
	}
	while($proResult=mysql_fetch_array($sql))
	{
		if($proResult['title'] !="")
		{
			?>
			<tr class="yrecord" id="<?php echo $i?>">
				<td class="width"><?php echo $i;?></td>
				<td>
					<a href="<?php echo(rootpath() . '/'.productCategoryAndSubcategory($proResult['permalink']).'/' . $proResult['permalink'] . '.html')?>">
					<?php
					if (strlen($proResult['title']) > 37)
					{
						$stringCut = substr($proResult['title'], 0, 37);
						$proResult['title'] = substr($stringCut, 0, strrpos($stringCut, ' ')).'...'; 
					}
					echo $proResult['title'] ?>
					</a>
				</td>
				<td class="width"><?php echo number_format($proResult['alltimeClicks']) ?></td>
			</tr>
			<?php
			$i++;
		}
	} 
}
?>