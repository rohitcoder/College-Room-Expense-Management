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

$session_id=trim($_SESSION['id']);

$found_i=0;	
$found_t=0;

$widthI="";
$heighti="";

$widthT="";
$heightT="";

$error1="";
$error2="";

$ch_i="";
$ch_t="";

if($_SESSION['type'])
{			
	include 'common/header.php';
	$error = "";
	function countCharacters ($str) {
	$letters=$i=0;
	while ($i<strlen($str)) {
	if (preg_match("/[a-zA-Z]/",$str{$i}))
	$letters++;
	++$i;
	}
	return $letters;
	}
	
	function updateProductSettings($featuredProductsLimit,$featuredImageWidth,$featuredImageHeight,$productImageWidth,$productImageHeight,$smallThumbnailWidth,$smallThumbnailHeight,$mediumThumbnailWidth,$mediumThumbnailHeight,$largeThumbnailWidth,$largeThumbnailHeight,$articleThumbnailWidth,$articleThumbnailHeight,$articleShortDescription,$articleLongDescription,$perPageProduct,$shortDesc,$longDesc,$relatedProducts,$relatedProductsLimit,$articlesLimit)
	{
		$update_query = "UPDATE `mediaSettings` SET `featuredProductsLimit`='$featuredProductsLimit',`featuredImageWidth`='$featuredImageWidth',`featuredImageHeight`='$featuredImageHeight',`productImageWidth`='$productImageWidth',`productImageHeight`='$productImageHeight',`smallThumbnailWidth`='$smallThumbnailWidth',`smallThumbnailHeight`='$smallThumbnailHeight',`mediumThumbnailWidth`='$mediumThumbnailWidth',`mediumThumbnailHeight`='$mediumThumbnailHeight',`largeThumbnailWidth`='$largeThumbnailWidth',`largeThumbnailHeight`='$largeThumbnailHeight',`articleThumbnailWidth`='$articleThumbnailWidth',`articleThumbnailHeight`='$articleThumbnailHeight',`articleShortDescription`='$articleShortDescription',`articleLongDescription`='$articleLongDescription',`perPageProduct`='$perPageProduct',`shortDescription`='$shortDesc',`longDescription`='$longDesc',`relatedProducts`='$relatedProducts',`relatedProductsLimit`='$relatedProductsLimit',`articlesLimit`='$articlesLimit'";
		mysql_query($update_query)or die(mysql_error());
	}
	if(isset($_POST) && $_SERVER['REQUEST_METHOD'] == "POST")
	{
	$relatedProductsLimit=xssClean(mres(trim($_POST['relatedProductsLimit'])));
	$featuredProductsLimit=xssClean(mres(trim($_POST['featuredProductsLimit'])));
	$FeaturedImageSize = xssClean(mres(trim($_POST["featuredImageSize"])));
	$productImage= xssClean(mres(trim($_POST["productImage"])));
	$smallThumbnail=xssClean(mres(trim($_POST['smallThumbnail'])));
	$mediumThumbnail= xssClean(mres(trim($_POST["mediumThumbnail"])));
	$largeThumbnail=xssClean(mres(trim($_POST['largeThumbnail'])));
	$articleThumbnail= xssClean(mres(trim($_POST["articleThumbnail"])));
	$articleShortDescription=xssClean(mres(trim($_POST['articleShortDescription'])));
	$articleLongDescription=xssClean(mres(trim($_POST['articleLongDescription'])));
	
	if($relatedProductsLimit=="" || !is_numeric($relatedProductsLimit))
		$error1="Invalid or Empty Feild";
	
	if($featuredProductsLimit=="" || !is_numeric($featuredProductsLimit))
		$error10="Invalid or Empty Feild";
	
	$split= split('[\xX]', $FeaturedImageSize);	
	if(countCharacters($FeaturedImageSize)==1 && (substr_count($FeaturedImageSize, 'x') || substr_count($FeaturedImageSize, 'X')) && is_numeric(trim($split[0])) && is_numeric(trim($split[1]))){
		$FeaturedImageWidth=trim($split[0]);
		$FeaturedImageHeight=trim($split[1]);
	} else {
		$error2='Invalid Format';
	}
	
	
	$split= split('[\xX]', $productImage);	
	if(countCharacters($productImage)==1 && (substr_count($productImage, 'x') || substr_count($productImage, 'X')) && is_numeric(trim($split[0])) && is_numeric(trim($split[1]))){
		$productImageWidth=trim($split[0]);
		$productImageHeight=trim($split[1]);
	} else {
		$error3='Invalid Format';
	}
	
	$split= split('[\xX]', $smallThumbnail);	
	if(countCharacters($smallThumbnail)==1 && (substr_count($smallThumbnail, 'x') || substr_count($smallThumbnail, 'X')) && is_numeric(trim($split[0])) && is_numeric(trim($split[1]))){
		$smallThumbnailWidth=trim($split[0]);
		$smallThumbnailHeight=trim($split[1]);
	} else {
		$error4='Invalid Format';
	}
	
	$split= split('[\xX]', $mediumThumbnail);	
	if(countCharacters($mediumThumbnail)==1 && (substr_count($mediumThumbnail, 'x') || substr_count($mediumThumbnail, 'X')) && is_numeric(trim($split[0])) && is_numeric(trim($split[1]))){
		$mediumThumbnailWidth=trim($split[0]);
		$mediumThumbnailHeight=trim($split[1]);
	} else {
		$error5='Invalid Format';
	}
	
	$split= split('[\xX]', $largeThumbnail);	
	if(countCharacters($largeThumbnail)==1 && (substr_count($largeThumbnail, 'x') || substr_count($largeThumbnail, 'X')) && is_numeric(trim($split[0])) && is_numeric(trim($split[1]))){
		$largeThumbnailWidth=trim($split[0]);
		$largeThumbnailHeight=trim($split[1]);
	} else {
		$error6='Invalid Format';
	}
	
	$split= split('[\xX]', $articleThumbnail);	
	if(countCharacters($articleThumbnail)==1 && (substr_count($articleThumbnail, 'x') || substr_count($articleThumbnail, 'X')) && is_numeric(trim($split[0])) && is_numeric(trim($split[1]))){
		$articleThumbnailWidth=trim($split[0]);
		$articleThumbnailHeight=trim($split[1]);
	} else {
		$error11='Invalid Format';
	}
	
	
	$perPageProduct = trim($_POST["perPageProduct"]);
	$articlesLimit=trim($_POST['articlesLimit']);
	$shortDesc = trim($_POST["shortDesc"]);
	$longDesc = trim($_POST["longDesc"]);
	$relatedProducts=$_POST['relatedProducts'];
	if($relatedProducts=='on')
		$relatedProducts=1;
	else
		$relatedProducts=0;
	if($perPageProduct=="")
		$error7="Feild must not be empty";
	else if(!is_numeric($perPageProduct))
		$error7="Enter only integers";
	if($shortDesc=="" || !is_numeric($shortDesc))
		$error8="Invalid or Empty Feild";
	if($longDesc=="" || !is_numeric($longDesc))
		$error9="Invalid or Empty Feild";
	if($articleShortDescription=="" || !is_numeric($articleShortDescription))
		$error12="Invalid or Empty Feild";
	if($articlesLimit=="" || !is_numeric($articlesLimit))
		$error13="Invalid or Empty Feild";
	if($articleLongDescription=="" || !is_numeric($articleLongDescription))
		$error14="Invalid or Empty Feild";

	if($error1=="" && $error2=="" && $error3=="" && $error4=="" && $error5=="" && $error6=="" && $error7=="" && $error8=="" && $error9=="" && $error10=="" && $error11=="" && $error12=="" && $error13=="" && $error14=="")
	{
		updateProductSettings($featuredProductsLimit,$FeaturedImageWidth,$FeaturedImageHeight,$productImageWidth,$productImageHeight,$smallThumbnailWidth,$smallThumbnailHeight,$mediumThumbnailWidth,$mediumThumbnailHeight,$largeThumbnailWidth,$largeThumbnailHeight,$articleThumbnailWidth,$articleThumbnailHeight,$articleShortDescription,$articleLongDescription,$perPageProduct,$shortDesc,$longDesc,$relatedProducts,$relatedProductsLimit,$articlesLimit);
	}	
}
	?>
	<script>
	var namesFeatured=[];
	var namesProduct=[];
	var namesSmall=[];
	var namesMedium=[];
	var namesLarge=[];
	var namesArticle=[];
	var articlesIdsArr = [];
	var namesArr = [];
	var featuredIdsArr=[];
	var productIdsArr=[];
	var smallIdsArr=[];
	var mediumIdsArr=[];
	var largeIdsArr=[];
	var idsArr = [];
	</script>
	<?php
	//Product and Featured Product Regeneration
	$names = mysql_query("SELECT * FROM `products`");
	while($fetch = mysql_fetch_array($names))
	{
		if($fetch['image']!="" && file_exists('../images/productImages/featuredImagesBackUp/_'.$fetch['image']))
		{
			list($width, $height) = getimagesize('../images/productImages/featuredImagesBackUp/_'.$fetch['image']);
			if($width >=featuredImageWidth() && $height >=featuredImageHeight())
			{
				list($featuredWidth, $featuredHeight) = getimagesize('../images/productImages/_'.$fetch['image']);
				if($featuredWidth!=featuredImageWidth() || $featuredHeight!=featuredImageHeight())
				{
					?>
					<script>
					namesFeatured.push('<?php echo $fetch['image'] ?>');
					featuredIdsArr.push('<?php echo $fetch['id'] ?>');
					</script>
					<?php
				}
			}
			if($width >=productImageWidth() && $height >=productImageHeight())
			{
				list($productWidth, $productHeight) = getimagesize('../images/productImages/'.$fetch['image']);
				if($productWidth!=productImageWidth() || $productHeight!=productImageHeight())
				{
					?>
					<script>
					namesProduct.push('<?php echo $fetch['image'] ?>');
					productIdsArr.push('<?php echo $fetch['id'] ?>');
					</script>
					<?php
				}
			}
			
		}
		//Small Thumbnail Regeneration
		for($i=1; $i<=5; $i++)
		{
			if($fetch['image'.$i]!="" && file_exists('../images/productImages/thumbnailsBackUp/'.$fetch['image'.$i]))
			{
				list($width, $height) = getimagesize('../images/productImages/thumbnailsBackUp/'.$fetch['image'.$i]);
				if($width >=smallThumbnailWidth() && $height >=smallThumbnailHeight())
				{
					list($smallThumbnailWidth, $smallThumbnailHeight) = getimagesize('../images/productImages/thumb1/'.$fetch['image'.$i]);
					if($smallThumbnailWidth!=smallThumbnailWidth() || $smallThumbnailHeight!=smallThumbnailHeight())
					{
						?>
						<script>
						namesSmall.push('<?php echo $fetch['image'.$i] ?>');
						smallIdsArr.push('<?php echo $fetch['id'] ?>');
						</script>
						<?php
					}
				}
			}
		}
		//Medium Thumbnail Regeneration
		for($i=1; $i<=5; $i++)
		{
			if($fetch['image'.$i]!="" && file_exists('../images/productImages/thumbnailsBackUp/'.$fetch['image'.$i]))
			{
				list($width, $height) = getimagesize('../images/productImages/thumbnailsBackUp/'.$fetch['image'.$i]);
				if($width >=mediumThumbnailWidth() && $height >=mediumThumbnailHeight())
				{
					list($mediumThumbnailWidth, $mediumThumbnailHeight) = getimagesize('../images/productImages/thumb2/'.$fetch['image'.$i]);
					if($mediumThumbnailWidth!=mediumThumbnailWidth() || $mediumThumbnailHeight!=mediumThumbnailHeight())
					{
						?>
						<script>
						namesMedium.push('<?php echo $fetch['image'.$i] ?>');
						mediumIdsArr.push('<?php echo $fetch['id'] ?>');
						</script>
						<?php
					}
				}
			}
		}
		//Large Thumbnail Regeneration
		for($i=1; $i<=5; $i++)
		{
			if($fetch['image'.$i]!="" && file_exists('../images/productImages/thumbnailsBackUp/'.$fetch['image'.$i]))
			{
				list($width, $height) = getimagesize('../images/productImages/thumbnailsBackUp/'.$fetch['image'.$i]);
				if($width >=largeThumbnailWidth() && $height >=largeThumbnailHeight())
				{
					list($largeThumbnailWidth, $largeThumbnailHeight) = getimagesize('../images/productImages/thumb3/'.$fetch['image'.$i]);
					if($largeThumbnailWidth!=largeThumbnailWidth() || $largeThumbnailHeight!=largeThumbnailHeight())
					{
						?>
						<script>
						namesLarge.push('<?php echo $fetch['image'.$i] ?>');
						largeIdsArr.push('<?php echo $fetch['id'] ?>');
						</script>
						<?php
					}
				}
			}
		}
	}
	
	//Regenerate Articles Images
	$articles = mysql_query("SELECT * FROM `articles`");
	while($fetch = mysql_fetch_array($articles))
	{
		$filename=str_replace('-img','',$fetch['image']);
		if(file_exists('../images/articleImages/articleImagesBackUp/'.$filename))
		{
			list($width, $height) = getimagesize('../images/articleImages/articleImagesBackUp/'.$filename);
			if($width >=articleImageWidth() && $height >=articleImageHeight())
			{
				list($articleImageWidth, $articleImageHeight) = getimagesize('../images/articleImages/'.$fetch['image']);
				if($articleImageWidth!=articleImageWidth() || $articleImageHeight!=articleImageHeight())
				{
				?>
				<script>
				namesArticle.push('<?php echo $fetch['image'] ?>');
				articlesIdsArr.push('<?php echo $fetch['id'] ?>');
				</script>
				<?php
				}
			}
			
		}
	}

$qry = mysql_query("SELECT * FROM `mediaSettings`");
while($fetch=mysql_fetch_array($qry))
{  
	$perPageProduct = $fetch["perPageProduct"];
	$articlesLimit=$fetch['articlesLimit'];
	$shortDesc = $fetch["shortDescription"];
	$longDesc = $fetch["longDescription"];
	$relatedProducts=$fetch['relatedProducts'];
	$relatedProductsLimit=$fetch['relatedProductsLimit'];
	$featuredProductsLimit=$fetch['featuredProductsLimit'];
	$FeaturedImageWidth = $fetch["featuredImageWidth"];
	$FeaturedImageHeight = $fetch["featuredImageHeight"];
	$productImageWidth = $fetch["productImageWidth"];
	$productImageHeight = $fetch["productImageHeight"];
	$smallThumbnailWidth = $fetch["smallThumbnailWidth"];
	$smallThumbnailHeight = $fetch["smallThumbnailHeight"];
	$mediumThumbnailWidth = $fetch["mediumThumbnailWidth"];
	$mediumThumbnailHeight = $fetch["mediumThumbnailHeight"];
	$largeThumbnailWidth = $fetch["largeThumbnailWidth"];
	$largeThumbnailHeight = $fetch["largeThumbnailHeight"];
	$articleThumbnailWidth = $fetch["articleThumbnailWidth"];
	$articleThumbnailHeight = $fetch["articleThumbnailHeight"];
	$articleShortDescription = $fetch["articleShortDescription"];
	$articleLongDescription = $fetch["articleLongDescription"];
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
<title>Media Settings : <?php echo(getTitle()) ?></title>
<script type="text/javascript">
$( document ).ready(function() {
	$( "#settings" ).hide();
});
</script>
</head>
<body>
<?php include 'common/navbar_admin.php'; ?>
<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h2><i class="fa fa-bullhorn color"></i> Media Settings</h2> 
				<hr />
				<?php if(isset($_POST['submit']) && $error1=="" && $error2=="" && $error3=="" && $error4=="" && $error5=="" && $error6=="" && $error7=="" && $error8=="" && $error9=="" && $error10=="" && $error11=="" && $error12=="" && $error13=="" && $error14==""){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Media Settings Updated Successfully!
				</div>  
				<?php } ?>
				<div class="alert alert-info alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong><i class="fa fa-hand-o-right"></i></strong> To Regenerate Thumbnails Please Update Settings First Then Regenerate If Thumbnails Already Have Same Size Then It Will Not Regenerate Thumbnails.
				</div>  
			</div>
			<div class="row">
				<div class="col-xs-12" style="padding: 15px;">
					<div class="awidget">
					<div class="row">
						<form class="form-group media-s" id="imageform" role="form" action="media_settings.php" method="post" enctype="multipart/form-data">
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label"> Related Products</label>
								<div style="margin-right: 8px" class="make-switch switches" data-on="primary" data-off="info" >
										<?php
										if ($relatedProducts) 
										{
											?> 
											<input type="checkbox" name="relatedProducts" checked>
											<?php
										} 
										else 
										{
											?>
											<input type="checkbox" name="relatedProducts">
											<?php
										}
										?>
									</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label"> Related Products Limit </label>
								<div class="col-lg-4">
									<input type="text" class="form-control" name="relatedProductsLimit" value="<?php echo $relatedProductsLimit;?>" />
									<?php
									if(isset($_POST) && $error1!="")
										echo('<span class="label label-danger">' . $error1 . '</span>');
									?>
								</div> 
							</div>
							</div>
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label"> Featured Products Limit </label>
								<div class="col-lg-4">
									<input type="text" class="form-control" name="featuredProductsLimit" value="<?php echo $featuredProductsLimit;?>" />
									<?php
									if(isset($_POST) && $error10!="")
										echo('<span class="label label-danger">' . $error10 . '</span>');
									?>
								</div> 
							</div>
							</div>
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label">Featured Image</label>
								<div class="col-lg-4">
									<input type="text" placeholder="widthxheight" class="form-control" name="featuredImageSize" value="<?php echo $FeaturedImageWidth.'x'.$FeaturedImageHeight;?>" />
									<?php
									if(isset($_POST) && $error2!="")
										echo('<span class="label label-danger">' . $error2 . '</span>');
									?>
								</div>
								<div class="col-lg-4">
									<button type="button" id="featuredImage" class="btn btn-success regenerate"><i class="fa fa-refresh"></i> Regenerate Featured Images</button>
								</div>
							</div>
							</div>
							<div id="featuredImageProgress" class="row hide">
								<div class="form-group">
									<label class="col-lg-3 control-label"></label>
									<div class="col-lg-4">
										<div class="progress">
											<div class="featuredImageProgress progress-bar progress-bar-striped active" role="progressbar"aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
												0%
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label">Product Image</label>
								<div class="col-lg-4">
									<input type="text" placeholder="widthxheight" class="form-control" name="productImage" value="<?php echo $productImageWidth.'x'.$productImageHeight;?>" />
									<?php
									if(isset($_POST) && $error3!="")
										echo('<span class="label label-danger">' . $error3 . '</span>');
									?>
								</div>
								<div class="col-lg-4">
									<button id="productImages" type="button" class="btn btn-success regenerate"><i class="fa fa-refresh"></i> Regenerate Product Images</button>
								</div>
							</div>
							</div>
							<div id="productImagesProgress" class="row hide">
								<div class="form-group">
									<label class="col-lg-3 control-label"></label>
									<div class="col-lg-4">
										<div class="progress">
											<div class="productImagesProgress progress-bar progress-bar-striped active" role="progressbar"aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
												0%
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label class="col-lg-3 control-label">Small Thumbnail</label>
									<div class="col-lg-4">
										<input type="text" placeholder="widthxheight" class="form-control" name="smallThumbnail" value="<?php echo $smallThumbnailWidth.'x'.$smallThumbnailHeight;?>" />
										<?php
										if(isset($_POST) && $error4!="")
											echo('<span class="label label-danger">' . $error4 . '</span>');
										?>
									</div>
									<div class="col-lg-4">
										<button id="smallThumbnails" type="button" class="btn btn-success regenerate"><i class="fa fa-refresh"></i> Regenerate Small Thumbnails</button>
									</div>
								</div>
							</div>
							<div id="smallThumbnailsProgress" class="row hide">
								<div class="form-group">
									<label class="col-lg-3 control-label"></label>
									<div class="col-lg-4">
										<div class="progress">
											<div class="smallThumbnailsProgress progress-bar progress-bar-striped active" role="progressbar"aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
												0%
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label">Medium Thumbnail</label>
								<div class="col-lg-4">
									<input type="text" placeholder="widthxheight" class="form-control" name="mediumThumbnail" value="<?php echo $mediumThumbnailWidth.'x'.$mediumThumbnailHeight;?>" />
									<?php
									if(isset($_POST) && $error5!="")
										echo('<span class="label label-danger">' . $error5 . '</span>');
									?>
								</div>
								<div class="col-lg-4">
									<button id="mediumThumbnails" type="button" class="btn btn-success regenerate"><i class="fa fa-refresh"></i> Regenerate Medium Thumbnails</button>
								</div>
							</div>
							</div>
							<div id="mediumThumbnailsProgress" class="row hide">
								<div class="form-group">
									<label class="col-lg-3 control-label"></label>
									<div class="col-lg-4">
										<div class="progress">
											<div class="mediumThumbnailsProgress progress-bar progress-bar-striped active" role="progressbar"aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
												0%
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label">Large Thumbnail</label>
								<div class="col-lg-4">
									<input type="text" placeholder="widthxheight" class="form-control" name="largeThumbnail" value="<?php echo $largeThumbnailWidth.'x'.$largeThumbnailHeight;?>" />
									<?php
									if(isset($_POST) && $error6!="")
										echo('<span class="label label-danger">' . $error6 . '</span>');
									?>
								</div>
								<div class="col-lg-4">
									<button id="largeThumbnails" type="button" class="btn btn-success regenerate"><i class="fa fa-refresh"></i> Regenerate Large Thumbnails</button>
								</div>
							</div>
							</div>
							<div id="largeThumbnailsProgress" class="row hide">
								<div class="form-group">
									<label class="col-lg-3 control-label"></label>
									<div class="col-lg-4">
										<div class="progress">
											<div class="largeThumbnailsProgress progress-bar progress-bar-striped active" role="progressbar"aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
												0%
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label"> Products Per Page</label>
								<div class="col-lg-4">
									<input type="text" class="form-control" name="perPageProduct" value="<?php echo $perPageProduct;?>" />
									<?php
									if(isset($_POST) && $error7!="")
										echo('<span class="label label-danger">' . $error7 . '</span>');
									?>
								</div> 
							</div>
							</div>
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label"> Short Description Length </label>
								<div class="col-lg-4">
									<input type="text" class="form-control" name="shortDesc" value="<?php echo $shortDesc;?>" />
									<?php
									if(isset($_POST) && $error8!="")
										echo('<span class="label label-danger">' . $error8 . '</span>');
									?>
								</div> 
							</div>	
							</div>
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label"> Short Description Length On Product Page</label>
								<div class="col-lg-4">
									<input type="text" class="form-control" name="longDesc" value="<?php echo $longDesc;?>" />
									<?php
									if(isset($_POST) && $error9!="")
										echo('<span class="label label-danger">' . $error9 . '</span>');
									?>
								</div> 
							</div>
							</div>
							<div class="row">
								<div class="form-group">
									<label class="col-lg-3 control-label">Article Thumbnail</label>
									<div class="col-lg-4">
										<input type="text" placeholder="widthxheight" class="form-control" name="articleThumbnail" value="<?php echo $articleThumbnailWidth.'x'.$articleThumbnailHeight;?>" />
										<?php
										if(isset($_POST) && $error11!="")
											echo('<span class="label label-danger">' . $error11 . '</span>');
										?>
									</div>
									<div class="col-lg-4">
									<button id="articleThumbnails" type="button" class="btn btn-success regenerate"><i class="fa fa-refresh"></i> Regenerate Article Thumbnails</button>
								</div>
								</div>
							</div>
							<div id="articleThumbnailsProgress" class="row hide">
								<div class="form-group">
									<label class="col-lg-3 control-label"></label>
									<div class="col-lg-4">
										<div class="progress">
											<div class="articleThumbnailsProgress progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
												0%
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label">Article Short Description Length</label>
								<div class="col-lg-4">
									<input type="text" placeholder="Article Short Description Length" class="form-control" name="articleShortDescription" value="<?php echo $articleShortDescription;?>" />
									<?php
									if(isset($_POST) && $error12!="")
										echo('<span class="label label-danger">' . $error12 . '</span>');
									?>
								</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label">Article Long Description Length</label>
								<div class="col-lg-4">
									<input type="text" placeholder="Article Long Description Length" class="form-control" name="articleLongDescription" value="<?php echo $articleLongDescription;?>" />
									<?php
									if(isset($_POST) && $error14!="")
										echo('<span class="label label-danger">' . $error14 . '</span>');
									?>
								</div>
							</div>
							</div>
							<div class="row">
							<div class="form-group">
								<label class="col-lg-3 control-label"> Article's Per Page</label>
								<div class="col-lg-4">
									<input type="text" class="form-control" name="articlesLimit" value="<?php echo $articlesLimit;?>" />
									<?php
									if(isset($_POST) && $error13!="")
										echo('<span class="label label-danger">' . $error13 . '</span>');
									?>
								</div>  
							</div>
							</div>
							<hr/>
							<div class="row">
							<div class="form-group">
								<div class="col-lg-offset-3 col-lg-9">
									<button type="submit" class="btn btn-success" name="submit"><i class="fa fa-pencil-square-o"></i> Update</button>
								</div>
							</div>
							</div>
						</form>
						</div>
					</div><!-- Awidget -->
				</div><!-- col-md-12 -->
			</div><!-- row -->
		</div><!-- mainy -->
		<div class="clearfix"></div> 
	</div><!-- container -->
</div>
<script src="style/switch/build/js/bootstrap-switch.min.js"></script> 
<script src="style/switch/docs/index.js"></script>
<?php include 'common/footer.php';
}
?>
<script>
$('.regenerate').on("click",function(e) 
{
	regenerateType=this.id;
	var i = 0;
	if(regenerateType=='featuredImage')
	{
		namesArr=namesFeatured;
		idsArr=featuredIdsArr;
	}
	else if(regenerateType=='productImages')
	{
		namesArr=namesProduct;
		idsArr=productIdsArr;
	}
	else if(regenerateType=='smallThumbnails')
	{
		namesArr=namesSmall;
		idsArr=smallIdsArr;
	}
	else if(regenerateType=='mediumThumbnails')
	{
		namesArr=namesMedium;
		idsArr=mediumIdsArr;
	}
	else if(regenerateType=='largeThumbnails')
	{
		namesArr=namesLarge;
		idsArr=largeIdsArr;
	}
	else if(regenerateType=='articleThumbnails')
	{
		namesArr=namesArticle;
		idsArr=articlesIdsArr;
	}
	var total = namesArr.length;
	var width = 0;
	var widthToExtend = 100/total;
	if(total)
	{
	$('button[type=button]').attr('disabled',true);
	regenerate(i,total,namesArr[i],idsArr[i],regenerateType,widthToExtend,width);
	}
});

function regenerate(i, total,name,id,type,widthToExtend,width)
{
	var typeId="#"+type+"Progress";
	var typeClass="."+type+"Progress";
	$.ajax
	({
		type:"POST",
		url: "<?php echo rootpath()?>/admin/regenerate.php",
		data: {'id':id ,'name': name , 'regenerateType':type},
		success: function(response) 
		{
			$(typeId).removeClass("hide");
			width = width + widthToExtend;
			$(typeClass).css("width",width+'%');
			var newWidth=Math.ceil(width);
			$(typeClass).text(newWidth+'%');
			i++;
			if(i<total)
			{
				regenerate(i,total,namesArr[i],idsArr[i],regenerateType,widthToExtend,width);
			}
			else
			{
				$(typeId).addClass("hide");
				$('button[type=button]').attr('disabled',false);
				location.reload();
			}
		}
	});
}

</script>