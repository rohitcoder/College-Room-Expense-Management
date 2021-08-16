<?php
error_reporting(0);
if(!isset($_SESSION)) 
session_start();
if(!isset($_SESSION['admin_eap']) && !isset($_SESSION['id']) && !isset($_SESSION['type']) && !isset($_SESSION['username']))
{
	header("location: index.php");
}
$session_id=$_SESSION['id'];
include 'common/header.php';
?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style/switch/build/css/bootstrap3/bootstrap-switch.min.css" />
<title>Xml / Bulk Add : <?php echo(getTitle()) ?></title>
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
				<h2><i class="fa fa-plus-square-o color"></i> Xml/Bulk Upload</h2> 
				<hr />
			</div>
			<div class="row">
				<div class="col-xs-12">
					<div class="awidget" style="padding-top:40px;">
					<form class="form-horizontal" id="imageform" role="form" method="post" enctype="multipart/form-data">
						<div class="form-group">
							<label class="col-lg-2 control-label">Action</label>
							<div class="col-lg-10">
									<div class="radio">
										<label>
											<input type="radio" class="csv" name="csv" value="0" checked>
											Upload Xml File
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" class="csv" name="csv" value="1">
											Paste Xml code Here
										</label>
									</div>
							</div>
						</div>
						<div class="form-group" id="csv">
							<label class="col-lg-2 control-label">Upload Xml</label>
							<div class="col-lg-5">
								<input type="file" id="csvFile" class="form-control" name="csv" style="height:auto" />
							</div>
						</div>
						<div class="form-group" id="bulkUpload" style="display:none">
							<label class="col-lg-2 control-label">Paste Xml code Here</label>
							<div class="col-lg-10">
								<textarea class="form-control" rows="10" cols="10" id="bulkuploadproducts" name="abulkupload" ></textarea>     
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label">Status</label>
							<div class="col-lg-10">
							<?php if($_SESSION['type']!='3d3c3f3cc8a1f15cf46db659eaa2b63d' || productApproved()){ ?>
									<div class="radio">
										<label>
											<input type="radio" class="status" name="status" value="1" checked>
											Published
										</label>
									</div>
									<div class="radio">
										<label>
											<input type="radio" class="status" name="status" value="0">
											Pending
										</label>
									</div>
							<?php } else { 
							?>
							<div class="radio">
								<label>
									<input type="radio" class="status" name="status" value="0" checked>
									Pending
								</label>
							</div>
							<?php
							}?>
							</div>
						</div>
						<div class="form-group">
						<label class="col-lg-2 control-label"></label>
							<div class="col-lg-10">
						To check Xml/bulk upload format <a data-toggle="modal" href="#format">click here</a>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label"></label>
							<div class="col-lg-10">
							<button type="button" id="upload" class="btn btn-success"><i class="fa fa-pencil-square-o"></i> Upload</button>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label"></label>
							<div class="col-lg-10">
								<div id="loader"></div> 
								<div id="response"></div>     
							</div>
						</div>
						<br><br>
					</form>
					</div><!-- Awidget -->
				</div><!-- col-md-12 -->
			</div><!-- row -->
		</div><!-- mainy -->
		<div class="clearfix"></div>
	</div><!-- container -->
</div>
<div class="modal fade" id="format" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title"><i class="fa fa-plus"></i> Xml/Bulk Upload Format</h4> 
			</div>
			<div class="modal-body">
				<textarea class="form-control" rows="15">
For Bulk Upload:
--------------------------------------------------------------------------------------------------------------
<data>
	<title>Product Title(required)</title>
	
	<description>Description(required)</description>
	
	<summary>Summary(required)</summary>
	
	<productImageUrl>Featured Product Image Url(required)</productImageUrl>
	
	<affiliateUrl>Affiliate Url(required)</affiliateUrl>
	
	<originalPrice>Original Price Allow(0-9.)(required)</originalPrice>
	
	<salePrice>Sale Price Allow(0-9.)</salePrice>
	
	<tags>tags(comma separated e.g:a,b,c)</tags>
	
	<expiryDate>Product Expiry Date(2015-06-30)</expiryDate>
	
	<image1Url>Image1 Url</image1Url>
	
	<image2Url>Image2 Url</image2Url>
	
	<image3Url>Image3 Url</image3Url>
	
	<image4Url>Image4 Url</image4Url>
	
	<image5Url>Image5 Url</image5Url>
	
	<categoryPermalink>Category Permalink</categoryPermalink>
</data>
<data>
	<title>Product Title(required)</title>
	
	<description>Description(required)</description>
	
	<summary>Summary(required)</summary>
	
	<productImageUrl>Featured Product Image Url(required)</productImageUrl>
	
	<affiliateUrl>Affiliate Url(required)</affiliateUrl>
	
	<originalPrice>Original Price Allow(0-9.)(required)</originalPrice>
	
	<salePrice>Sale Price Allow(0-9.)</salePrice>
	
	<tags>tags(comma separated e.g:a,b,c)</tags>
	
	<expiryDate>Product Expiry Date(2015-06-30)</expiryDate>
	
	<image1Url>Image1 Url</image1Url>
	
	<image2Url>Image2 Url</image2Url>
	
	<image3Url>Image3 Url</image3Url>
	
	<image4Url>Image4 Url</image4Url>
	
	<image5Url>Image5 Url</image5Url>
	
	<categoryPermalink>Category Permalink</categoryPermalink>
</data>
--------------------------------------------------------------------------------------------------------------
For Xml File:
--------------------------------------------------------------------------------------------------------------
<product>
	<data>
		<title>Product Title(required)</title>
		
		<description>Description(required)</description>
		
		<summary>Summary(required)</summary>
		
		<productImageUrl>Featured Product Image Url(required)</productImageUrl>
		
		<affiliateUrl>Affiliate Url(required)</affiliateUrl>
		
		<originalPrice>Original Price Allow(0-9.)(required)</originalPrice>
		
		<salePrice>Sale Price Allow(0-9.)</salePrice>
		
		<tags>tags(comma separated e.g:a,b,c)</tags>
		
		<expiryDate>Product Expiry Date(2015-06-30)</expiryDate>
		
		<image1Url>Image1 Url</image1Url>
		
		<image2Url>Image2 Url</image2Url>
		
		<image3Url>Image3 Url</image3Url>
		
		<image4Url>Image4 Url</image4Url>
		
		<image5Url>Image5 Url</image5Url>
		<categoryPermalink>Category Permalink</categoryPermalink>
	</data>
	<data>
		<title>Product Title(required)</title>
		
		<description>Description(required)</description>
		
		<summary>Summary(required)</summary>
		
		<productImageUrl>Featured Product Image Url(required)</productImageUrl>
		
		<affiliateUrl>Affiliate Url(required)</affiliateUrl>
		
		<originalPrice>Original Price Allow(0-9.)(required)</originalPrice>
		
		<salePrice>Sale Price Allow(0-9.)</salePrice>
		
		<tags>tags(comma separated e.g:a,b,c)</tags>
		
		<expiryDate>Product Expiry Date(2015-06-30)</expiryDate>
		
		<image1Url>Image1 Url</image1Url>
		
		<image2Url>Image2 Url</image2Url>
		
		<image3Url>Image3 Url</image3Url>
		
		<image4Url>Image4 Url</image4Url>
		
		<image5Url>Image5 Url</image5Url>
		
		<categoryPermalink>Category Permalink</categoryPermalink>
	</data>
</product>
--------------------------------------------------------------------------------------------------------------
				</textarea>
			</div>
		</div>
	</div>
</div>
<script src="style/switch/build/js/bootstrap-switch.min.js"></script> 
<script src="style/switch/docs/index.js"></script>
<script>
$(".csv").on('change', function(){
    var value=$(this).val();
	$('#response').html('');
	if(value==0){
		$('#csv').show();
		$('#bulkUpload').hide();
	} else {
		$('#csv').hide();
		$('#bulkUpload').show();
	}
});
$( document ).ajaxStop(function() 
{
	$('#loader').hide();
});

$('#upload').on('click', function() {
	var value=$('input:radio[name=csv]:checked').val();
	var status=$('input:radio[name=status]:checked').val();
	if(value==0){
    var file_data = $('#csvFile').prop('files')[0];
	if(file_data)
		{
			var form_data = new FormData();	
			form_data.append('csv', file_data);   
			form_data.append('csvFile', 'csvFile');
			form_data.append('status', status);
			$('#loader').show();
			$('#response').html('');
			$('#loader').html('<div class="wobblebar">Loading...</div>');
			$.ajax({
						url: '<?php echo rootpath()?>/admin/uploadcsv.php', // point to server-side PHP script 
						contentType: false,
						processData: false,
						data: form_data,                         
						type: 'post',
						success: function(php_script_response){
							$('#response').html(php_script_response); // display response from the PHP script, if any
						}
			 });
		} else
		{
			alert('no file selected');
		}
	} else {
		$('#response').html('');
		var data = $('#bulkuploadproducts').val().split('</data>');
		if(data!=""){
		$('#loader').show();
		$('#loader').html('<div class="wobblebar">Loading...</div>');
		$.each(data, function(index,separate)   
		{
			$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath()?>/admin/uploadcsv.php",
				data: {'bulkUpload':separate,'status':status},
				success: function(php_script_response){
					$('#response').append(php_script_response); // display response from the PHP script, if any
				}
			});
		});
		} else {
			$('#loader').hide();
		}
	}
});
</script>
<?php include 'common/footer.php';
?>