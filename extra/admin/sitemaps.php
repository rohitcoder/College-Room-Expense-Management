<?php
error_reporting(0);
if(!isset($_SESSION)) 
session_start();
if(!isset($_SESSION['admin_eap']) && !isset($_SESSION['id']) && !isset($_SESSION['type']) && !isset($_SESSION['username']))
{
header("location: index.php");
}
if($_SESSION['type']!='a3852d52cc0ac36ea335d8cdd952d4cf')
{
header("location: dashboard.php");
}		
else
{			
	include 'common/header.php';
	$error = "";
	if(isset($_POST['gen']))
	{
		$categories = $_POST["categories"];
		if($categories=='on')
			$categories=1;
		else
			$categories=0;
		$pages = $_POST["pages"];
		if($pages=='on')
			$pages=1;
		else
			$pages=0;
		$contactForm = $_POST["contactForm"];
		if($contactForm=='on')
			$contactForm=1;
		else
			$contactForm=0;
		$posts = $_POST["posts"];
		if($posts=='on')
			$posts=1;
		else
			$posts=0;
		$outputPath = xssClean(mres(trim($_POST["outputPath"])));
		
		$productsLimit = xssClean(mres(trim($_POST["productsLimit"])));
		
		if($outputPath=="")
			$error='File Name Must Required';
		$data=getOtherFiles();
		if($error=="")
		updateSitemapSettings($categories,$pages,$contactForm,$posts,$outputPath,$productsLimit);
		
		if(sitemapCategoriesStatus() || sitemapContactFormStatus() || sitemapPagesStatus())
		{
			$sitemaps = "";
			$filename = sitemapOutputPath();
			$sitemaps .= '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
			$sitemaps .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
			$sitemaps .= genRootSitemap();
			if(sitemapCategoriesStatus())
				$sitemaps .= genCategoriesSitemap();
			if(sitemapContactFormStatus())
				$sitemaps .= genContactSitemap();
			if(sitemapPagesStatus())
				$sitemaps .= genPagesSitemap();
			$sitemaps .= '</urlset>';
			$file = fopen("../".$filename,"w+");
			fwrite($file,$sitemaps);
			fclose($file);
		}
		if(sitemapPostsStatus())
		{
			$limit=$productsLimit;
			$data=explode(',',$data);
			$filesCount=count($data);
			for($i=1; $i<=$filesCount; $i++)
			{
				unlink('../'.$data[$i-1]);
			}
			$otherfiles=basename($outputPath,".xml");
			$query = mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE  pl.`language`='english' AND p.`id`=pl.`id`");
			$count=mysql_num_rows($query);
			$total=ceil($count/$limit);
			for($i=1; $i<=$total; $i++)
			{
				$sitemaps = "";
				$filename = sitemapOutputPath();
				$sitemaps .= '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;
				$sitemaps .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
				$sitemaps .= genRootSitemap();
				$start_result = ($i-1)*$limit;
				$filename=$otherfiles.$i.'.xml';

				if(sitemapPostsStatus())
				$sitemaps .= genProductsSitemap($start_result,$limit);
				$sitemaps .= '</urlset>';
				$file = fopen("../".$filename,"w+");
				fwrite($file,$sitemaps);
				fclose($file);
				$files .=$filename.',';
				
			}
			$otherFiles=rtrim($files,',');
		}
		$sql = "UPDATE `sitemaps` SET `lastModified`='" . date('Y-m-d H:i:s') . "',`otherFiles`='$otherFiles'";
		mysql_query($sql);
		$regenerated = true;
		$regen_msg = "Sitemap Generated and Saved in <a href=". rootpath() . "/" . $filename.">" . rootpath() . "/" . $filename."</a>";
	}
	?>  
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
	<title>Sitemap Settings : <?php echo(getTitle()) ?></title>
	</head>
	<body>
	<?php include 'common/navbar_admin.php'; ?>
	<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
		<div class="page-title">
		<h2>
			<i class="fa fa-sitemap color"></i> Sitemap Settings </h2> 
			<hr />
			<?php if(isset($_POST['gen']) && $error==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Sitemap Generated and saved Successfully!
				</div>
			<?php } ?>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="awidget" style="padding: 15px;">
					<form class="form-horizontal" role="form" action="sitemaps.php" method="post">
						<div class="form-group">
								<label class="col-lg-2 control-label"> Categories</label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
									<input type="checkbox" name="categories" <?php echo (sitemapCategoriesStatus() ? 'checked' : '')?>>
								</div>
						</div>
						<div class="form-group">
								<label class="col-lg-2 control-label"> Pages</label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
									<input type="checkbox" name="pages" <?php echo (sitemapPagesStatus() ? 'checked' : '')?>>
								</div>
						</div>
						<div class="form-group">
								<label class="col-lg-2 control-label"> Contact Form</label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
										<input type="checkbox" name="contactForm" <?php echo (sitemapContactFormStatus() ? 'checked' : '')?>>
								</div>
						</div>
						<div class="form-group">
								<label class="col-lg-2 control-label"> Products</label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
									<input type="checkbox" name="posts" <?php echo (sitemapPostsStatus() ? 'checked' : '')?>>
								</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Products Limit Per File</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" name="productsLimit" placeholder="Products Limit Per File" value="<?php echo(productsLimitPerFile()); ?>" required />
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">File Name</label>
							<div class="col-lg-6">
								<input type="text" class="form-control" name="outputPath" placeholder="e.g:abc.xml" value="<?php echo(sitemapOutputPath()); ?>" required />
							</div>
						</div>
						<?php
						if($error!="")
							echo('<span class="label label-danger">' . $error . '</span>');
						?>
						<hr />
						<div class="form-group">
							<div class="col-lg-offset-2 col-lg-10">
								<button class="btn btn-success" type="submit" name="gen">Generate</button>
							</div>
						</div>	
						<div class="form-group">
							<label class="col-lg-2 control-label"></label>
							<div class="col-lg-10">
								<?php
									if((sitemapCategoriesStatus() || sitemapContactFormStatus() || sitemapPagesStatus()) && sitemapOutputPath() && file_exists('../'.sitemapOutputPath()))
									{
										$regen_msg = "Sitemap For".(sitemapCategoriesStatus() ? ' Categories,':' ').(sitemapContactFormStatus() ? ' Contact Form,':' ').(sitemapPagesStatus() ? ' Pages':' ')."Generated and Saved in <a href=". rootpath() . "/" . $data[$i-1]." target='_blank'>" . rootpath() . "/" . sitemapOutputPath()."</a>";
											echo '<span class="label label-success">' . $regen_msg . '</span><br>';
									}
									if(sitemapPostsStatus())
									{
										$data=getOtherFiles();
										$data=explode(',',$data);
										$filesCount=count($data);
										for($i=1; $i<=$filesCount; $i++)
										{
											 if(getOtherFiles() && file_exists('../'.$data[$i-1]))
											 {
												$regen_msg = "Sitemap For Products Generated and Saved in <a href=". rootpath() . "/" . $data[$i-1]." target='_blank'>" . rootpath() . "/" . $data[$i-1]."</a>";
												echo '<span class="label label-success">' . $regen_msg . '</span><br>';
											 }
										}
									}
								?>
							</div>
						</div>
					</form>
				</div><!-- Awidget -->
			</div><!-- col-md-12 -->
		</div><!-- row -->
		</div><!-- mainy -->
		<div class="clearfix"></div> 
	</div><!-- container -->
<script src="style/switch/build/js/bootstrap-switch.min.js"></script> 
<script src="style/switch/docs/index.js"></script>
	<?php include 'common/footer.php'; 
}
?>