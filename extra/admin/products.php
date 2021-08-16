<?php
error_reporting(0);
if(!isset($_SESSION)) 
session_start();
if(!isset($_SESSION['type']) || !isset($_SESSION['admin_eap']) || !isset($_SESSION['id']) || !isset($_SESSION['username']))
{
header("location: index.php");
}
if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf')
$userType=1;
else if($_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87')
$userType=2;
else if($_SESSION['type']=='3d3c3f3cc8a1f15cf46db659eaa2b63d')
$userType=3;
$user_id=$_SESSION['id'];
include 'common/header.php';
include 'common/navbar_admin.php';
$search = "";
$page_no = "";
$qs = "";
foreach($_GET as $variable => $value)
{ 
	if($variable=="search")
		$search=$value;
	else if($variable=="page")
		$page_no=$value;
	if($search!="" && $page_no=="")
		$qs = "&search=$search";
	if($search=="" && $page_no!="")
		$qs = "&page=$page_no";
	if($search!="" && $page_no!="")
		$qs = "&search=$search&page=$page_no";
}
if(isset($_GET['sort_order']))
{
	if(trim($_GET['sort_order'])=="ASC" || trim($_GET['sort_order'])=="DESC")
		$_SESSION['sort_order'] = trim($_GET['sort_order']);
}
$search_string=mres(trim($_GET['search']));
$sort_string=mres(trim($_GET['sort']));
if(isset($_GET['sort_by']))
{
	if(trim($_GET['sort_by'])=="publishedDate" || trim($_GET['sort_by'])=="views" || trim($_GET['sort_by'])=="clicks" || trim($_GET['sort_by'])=="title")
	{
		if(trim($_GET['sort_by'])=="publishedDate")
				$_SESSION['sort_by']="id";
		else if(trim($_GET['sort_by'])=="views")
			$_SESSION['sort_by']="views";
		else if(trim($_GET['sort_by'])=="clicks")
			$_SESSION['sort_by']="clicks";
		else if(trim($_GET['sort_by'])=="title")
			$_SESSION['sort_by']="title";
		else
			$_SESSION['sort_by']="id";
	}
}
if(!isset($_SESSION['sort_by']))
	$_SESSION['sort_by'] = "id";
if(!isset($_SESSION['sort_order']))
	$_SESSION['sort_order'] = "DESC";
$is_deleted = false;
if(isset($_GET['delete']) && is_numeric($_GET['delete']))
{
	$cache = phpFastCache();
	$id=mres(trim($_GET['delete']));
	$query=mysql_query("SELECT `cid` FROM `procat` WHERE `pid`='$id'");
	while($fetch=mysql_fetch_array($query)){
		clearCategorycache($fetch['cid']);
		clearRecentCache($fetch['cid']);
	}
	foreach( getAllLanguages() as $fetch){
	$language=$fetch['languageName'];
	$cache->delete($id.'_product'.$language);
	$cache->delete($id.'_relatedProduct'.$language);
	}
	deleteProcat($id);
	deleteProduct($id);
}
if($_SESSION['type']!='3d3c3f3cc8a1f15cf46db659eaa2b63d' || productApproved()){
if(isset($_GET['publish']) && is_numeric($_GET['publish']))
{
	$cache = phpFastCache();
	$id=mres(trim($_GET['publish']));
	mysql_query("UPDATE `products` SET `status`='0' WHERE `id`='$id'");
	$query=mysql_query("SELECT `cid` FROM `procat` WHERE `pid`='$id'");
	while($fetch=mysql_fetch_array($query)){
		clearCategorycache($fetch['cid']);
		clearRecentCache($fetch['cid']);
	}
	foreach( getAllLanguages() as $fetch){
	$language=$fetch['languageName'];
	$cache->delete($id.'_product'.$language);
	$cache->delete($id.'_relatedProduct'.$language);
	}
}
if(isset($_GET['unpublish']) && is_numeric($_GET['unpublish']))
{
	$cache = phpFastCache();
	$id=mres(trim($_GET['unpublish']));
	mysql_query("UPDATE `products` SET `status`='1' WHERE `id`='$id'");
	$query=mysql_query("SELECT `cid` FROM `procat` WHERE `pid`='$id'");
	while($fetch=mysql_fetch_array($query)){
		clearCategorycache($fetch['cid']);
		clearRecentCache($fetch['cid']);
	}
	foreach( getAllLanguages() as $fetch){
	$language=$fetch['languageName'];
	$cache->delete($id.'_product'.$language);
	$cache->delete($id.'_relatedProduct'.$language);
	}
}
}			
if(isset($_GET['cat']))
{
	$catId=mres(trim($_GET['cat']));
	$proquery = mysql_query("SELECT `pid` FROM `procat` WHERE `cid`=".$catId);
	$sub=array();
	while($row=mysql_fetch_array($proquery))
	{
		$productquery=mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`='".$row['pid']."' AND pl.`id`='".$row['pid']."' AND pl.`language`='english'");
		while($subrow=mysql_fetch_array($productquery))
		{
			array_push($sub, $subrow['id']);
		}
	}
	$catWiseProducts = "'". implode("', '", $sub) ."'";
}		
$error = "";				
$page=1;//Default page 
$limit=10; //Records per page
$next=2; 
$prev=1;
//starts displaying records from 0
if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf' || $_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87') {
if(isset($_GET['search'])) {
	$search=mres(trim($_GET['search']));
	$data = mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND  pl.`title` LIKE '%$search%' AND pl.`language`='english'");
} else if($_GET['cat']) {
	$catId=mres(trim($_GET['cat']));
	$data=mysql_query("SELECT  p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND p.`id` IN($catWiseProducts) AND pl.`id` IN($catWiseProducts) AND pl.`language`='english'");
} else {
	$data=mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND pl.`language`='english'");
}
} else {
if(isset($_GET['search'])) {
	$search=mres(trim($_GET['search']));
	$data = mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE pl.`title` LIKE '%".$_GET['search']."%' AND p.`id`=pl.`id` AND pl.`language`='english' AND p.`userId`=".$_SESSION['id']);
} else if($_GET['cat']) {
	$catId=mres(trim($_GET['cat']));
	$data=mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id` IN($catWiseProducts) AND pl.`id` IN($catWiseProducts) AND p.`id`=pl.`id` AND pl.`language`='english' AND p.`userType`='3' AND p.`userId`=".$_SESSION['id']);
} else {
	$data=mysql_query("SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`userType`='3' AND pl.`language`='english' AND p.`id`=pl.`id` AND p.`userId`=".$_SESSION['id']);
}
}
$rows = mysql_num_rows($data);
$last = ceil($rows/$limit);
if(isset($_GET['page']) && $_GET['page']!='' && ($_GET['page']>=1 && $_GET['page']<=$last))
{
	$page=mres(trim($_GET['page']));
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
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>All Products : <?php echo(getTitle()) ?></title>
<script type="text/javascript">
$( document ).ready(function() 
{
	$( "#deleteproduct" ).hide();
}); 
</script>
<script type='text/javascript'>
jQuery(function($) 
{
	$("#selectall").click(function() { // triggred check
		var inputs = $("td input[type='checkbox']"); // get the checkbox
		for(var i = 0; i < inputs.length; i++) 
		{ // count input tag in the form
			var type = inputs[i].getAttribute("type"); //  get the type attribute
			if(type == "checkbox") 
			{
				if(this.checked) 
				{
					inputs[i].checked = true; // checked
				} 
				else 
				{
					inputs[i].checked = false; // unchecked
				}
			}
		}
	});
	//publish selected
	$('#publish').click(function()
	{
		var count=1;
		var len=$('input[type=checkbox]:checked').length;
		$('input[type=checkbox]:checked').each(function()
		{
			var the_id = $(this).val();
			var publish='publish_all'; 
			if(the_id!='unknown')
			{
				$.ajax
				({
					type: "POST",
					url: "publish_selected.php",
					data: {'id':+the_id,'publish':publish},
					success: function(n)
					{
						count=count+1;
						if(count==len){
						window.location.href="<?php echo rootpath()?>/admin/products.php?publish_selected=1";
						}
					}
				});
			}
		});
	});   
	//unpublish selected
	$('#unpublish').click(function()
	{
		var count=1;
		var len=$('input[type=checkbox]:checked').length;
		$('input[type=checkbox]:checked').each(function()
		{
			var the_id = $(this).val();
			var unpublish='publish_selected'; 
			if(the_id!='unknown')
			{
				$.ajax
				({
					type: "POST",
					url: "publish_selected.php",
					data: {'id':+the_id,'unpublish':unpublish},
					success: function(n)
					{
						count=count+1;
						if(count==len){
						window.location.href="<?php echo rootpath()?>/admin/products.php?unpublish_selected=0";
						}
					}
				});
			}
		});
	});  
	//Delete selected
	$('#del_link').click(function()
	{
		var count=1;
		var len=$('input[type=checkbox]:checked').length;
		$('input[type=checkbox]:checked').each(function()
		{
			var the_id = $(this).val();
			var val=$('#del_link').val();
			var dselected='delete_selected';
			if(the_id!='unknown' && val=="Yes")
			{
				$.ajax
				({
					type: "POST",
					url: "publish_selected.php",
					data: {'id':+the_id,'delete':dselected},
					success: function(n)
					{
						count=count+1;
						if(count==len){
						window.location.href="<?php echo rootpath()?>/admin/products.php?delete_selected=0";
						}
					}
				});
			}
		});
	}); 
});
</script>						
</head>
<body>
<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>  
		<div class="mainy">
			<div class="page-title">
				<?php include 'admin_product_search.php'; ?>  
			</div>
			<hr />            
			<div class="row">
				<div class="col-md-12">
						<?php 
						if(isset($_GET['publish_selected']) || isset($_GET['unpublish']))
						{
							if($_SESSION['type'] !='3d3c3f3cc8a1f15cf46db659eaa2b63d' || productApproved()){
							?>
							<div id="publish_products" class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<strong>Success!</strong> Product's Published Successfully.
							</div>
							<?php 
							}
						} 
						else if(isset($_GET['unpublish_selected']) || isset($_GET['publish']))
						{ 
							if($_SESSION['type'] !='3d3c3f3cc8a1f15cf46db659eaa2b63d' || productApproved()){
							?>
							<div id="unpublish_products" class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<strong>Success!</strong> Product's Unpublished Successfully.
							</div>
							<?php 
							}
						}
						if(isset($_GET['delete']) || isset($_GET['delete_selected']))
						{ 
							if($_SESSION['type'] !='3d3c3f3cc8a1f15cf46db659eaa2b63d' || productDelete()){
							?>
							<div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<strong>Success!</strong> Product's Delete Successfully.
							</div>
							<?php 
							}
						}
						?>
					<div class="awidget">
						<div class="awidget-head">                       
											<?php
											$start_result = ($page-1)*$limit;
											if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf' || $_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87')
											{
												if(isset($_GET['cat']))
												{
													if($_SESSION['sort_by']=='title')
													{
														$sql="SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.id=pl.id AND pl.`language`='english' AND p.`id` IN ($catWiseProducts) ORDER BY pl.".$_SESSION['sort_by']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit ;
													} else {
														$sql="SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.id=pl.id AND pl.`language`='english' AND p.`id` IN ($catWiseProducts) ORDER BY p.".$_SESSION['sort_by']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit ;
													}
												}
												else if(isset($_GET['search']))
												{
													$search=mres(trim($_GET['search']));
													if($_SESSION['sort_by']=='title')
													{
														$sql = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.id=pl.id AND pl.`language`='english' AND pl.`title` LIKE '%$search%' ORDER BY pl.".$_SESSION['sort_by']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit;
													} else {
														$sql = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.id=pl.id AND pl.`language`='english' AND pl.`title` LIKE '%$search%' ORDER BY p.".$_SESSION['sort_by']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit;
													}
												} else 
												{
													if($_SESSION['sort_by']=='title'){
														$sql = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.id=pl.id AND pl.`language`='english' ORDER BY pl.".$_SESSION['sort_by']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit;
													} else {
														$sql = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.id=pl.id AND pl.`language`='english' ORDER BY p.".$_SESSION['sort_by']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit;
													}
												}
											} else 
											{
												if(isset($_GET['cat']))
												{
													if($_SESSION['sort_by']=='title')
													{
														$sql="SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.id=pl.id AND pl.`language`='english' AND p.`id` IN ($catWiseProducts) AND p.`userId`=".$_SESSION['id']." AND p.`userType`='3'  ORDER BY pl.".$_SESSION['sort_by']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit ;
													} else {
														$sql="SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.id=pl.id AND pl.`language`='english' AND p.`id` IN ($catWiseProducts) AND p.`userId`=".$_SESSION['id']." AND p.`userType`='3'  ORDER BY p.".$_SESSION['sort_by']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit ;
													}
												}
												else if(isset($_GET['search']))
												{
													$search=mres(trim($_GET['search']));
													if($_SESSION['sort_by']=='title'){
														$sql = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND pl.`language`='english' AND pl.`title` LIKE '%$search%' AND p.`userId`='".$_SESSION['id']."' AND p.`userType`='3'  ORDER BY pl.".$_SESSION['sort_by']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit;
													} else {
														$sql = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND pl.`language`='english' AND pl.`title` LIKE '%$search%' AND p.`userId`='".$_SESSION['id']."' AND p.`userType`='3'  ORDER BY p.".$_SESSION['sort_by']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit;
													}
												} else {
													if($_SESSION['sort_by']=='title'){
														$sql = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.id=pl.id AND pl.`language`='english' AND p.`userId`='".$_SESSION['id']."' AND p.`userType`='3' ORDER BY pl.".$_SESSION['sort_by']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit;
													} else {
														$sql = "SELECT p.*,pl.* FROM `products` p,`productsLanguage` pl WHERE p.`id`=pl.`id` AND pl.`language`='english' AND p.`userId`='".$_SESSION['id']."' AND p.`userType`='3' ORDER BY p.".$_SESSION['sort_by']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit;
													}
													}
											}
												$qry = mysql_query($sql);
												$num_rows = mysql_num_rows($qry); 
												if ($num_rows > 0) 
												{
													?>
													<div align="left" style="padding-bottom:0px; margin-left:11px;">
								<b>Sort By: </b>
								<label>		
								<?php	
									if(isset($_GET['cat'])) {?>
									<select onchange="self.location='<?php echo(rootpath()) ?>/admin/products.php?cat=<?php echo trim($_GET['cat'])?>&sort_by='+this.options[this.selectedIndex].value+'<?php echo($qs) ?>'">
									<?php }
									else { ?>
									<select onchange="self.location='<?php echo(rootpath()) ?>/admin/products.php?sort_by='+this.options[this.selectedIndex].value+'<?php echo($qs) ?>'">
									<?php } ?>
									<option value="publishedDate" <?php echo ($_SESSION['sort_by']=="id" ? 'selected' : "") ?>>Recent</option>
									<option value="views" <?php echo ($_SESSION['sort_by']=="views" ? 'selected' : "") ?>>Views</option>
									<option value="clicks" <?php echo ($_SESSION['sort_by']=="clicks" ? 'selected' : "") ?>>Clicks</option>
									<option value="title" <?php echo ($_SESSION['sort_by']=="title" ? 'selected' : "") ?>>A-Z</option>
									</select>
									<?php
									if(isset($_GET['cat']))
									{ ?>											
										<a title="<?php echo ($_SESSION['sort_order']=="ASC" ? 'Ascending' :'Descending')?>" href="<?php echo rootpath()?>/admin/products.php?cat=<?php echo trim($_GET['cat'])?>&sort_by=<?php echo $_SESSION['sort_by']?>&sort_order=<?php echo ($_SESSION['sort_order']=="ASC" ? 'DESC' :'ASC').$qs?>" style="margin-top: -4px;" class="btn btn-info btn-xs <?php echo ($_SESSION['sort_order']=="ASC" ? 'active' : 'active')?>"  type="button"><i class="fa fa-sort-amount-<?php echo ($_SESSION['sort_order']=="ASC" ? 'asc' : 'desc')?>"></i></a>
											<?php 
									} else { ?>
										<a title="<?php echo ($_SESSION['sort_order']=="ASC" ? 'Ascending' :'Descending')?>" href="<?php echo rootpath()?>/admin/products.php?sort_by=<?php echo $_SESSION['sort_by']?>&sort_order=<?php echo ($_SESSION['sort_order']=="ASC" ? 'DESC' :'ASC').$qs?>" style="margin-top: -4px;" class="btn btn-info btn-xs <?php echo ($_SESSION['sort_order']=="ASC" ? 'active' : 'active')?>"  type="button"><i class="fa fa-sort-amount-<?php echo ($_SESSION['sort_order']=="ASC" ? 'asc' : 'desc')?>"></i></a> 
									<?php }
									?> 												
								</label>
								<a href="add_product.php" class="btn btn-success" style="margin-left:553px;"><i class="fa fa-plus-square-o"></i> Add Product</a>
							</div>							
							<div class="awidget-body">
								<form action="products.php" method="post">
									<div id="txtResult"></div>
									<div id="firstresult">
										<table class="table table-hover"> 
													<thead> 
														<tr>
															<th class="click"><div><input value="unknown" type="checkbox" id="selectall"></input></div></th>
															<th  class="title" id="selectall">Select All</th>
															<th  class="catagories">Categories</th>
															<th class="click clicks">Clicks</th>
															<th class="click clicks">Views</th>
															<?php if($_SESSION['type']!='3d3c3f3cc8a1f15cf46db659eaa2b63d' || productEdit() || productDelete() || productApproved()) {?>
															<th class="action_P action">
															<center>Action</center></th>
															<?php }
															?>
														</tr>
													</thead>
													<tbody id="results"> 
													<?php
													while($row = mysql_fetch_array($qry))
													{
														$edit = '<a  class="btn btn-xs btn-info" href="edit_product.php?id=' . $row['id'] . '" title="Edit ' . $row['title'] . '"><i class="fa fa-pencil"></i></a>';
														$Publish = '<a  class="btn btn-xs btn-success" href="products.php?publish=' . $row['id'] . '" title="Publish ' . $row['title'] . '"><i class="fa fa-check"></i></a>';
														$UnPublish = '<a  class="btn btn-xs btn-warning" href="products.php?unpublish=' . $row['id'] . '" title="Unpublish ' . $row['title'] . '"><i class="fa fa-ban"></i></i></a>';
															$id=$row['id'];
															$delete = "<a id='meddelanden' data-toggle='clickover' data-placement='left' data-title='Are you sure?' data-content='<a href=products.php?delete=$id class=&quot;btn btn-primary btn-xs yes&quot;><i class=fa fa-check></i> Yes</a>     <a id=close-popover data-toggle=clickover class=&quot;btn btn-default btn-xs&quot; onclick=$(&quot;.meddelanden&quot;).popover(&quot;hide&quot;);$(&quot;.popover&quot;).hide();><i class=fa fa-times></i> No</a>' class='btn btn-xs btn-danger meddelanden'><i class='fa fa-times'></i></a>";
														echo('<tr style="border-bottom: 1px dashed #eee;">');
														echo('<td><input class="selectedId" type="checkbox" name="checkboxvar[]" value="' . $row['id'] .'" >
														</td>');
														$productTitle=(strlen($row['title']) > 40) ? substr($row['title'],0,40).'...' : $row['title'];
														if($row['status']==1)
														{
															echo('<td><a title="' . $row['title'] . '" href="' . rootpath() . '/'.productCategoryAndSubcategory($row['permalink']).'/' . $row['permalink']. '.html">'.$productTitle.'</a></td>');
														} 
														else
														{
															echo('<td><a title="' . $row['title'] . '" href="' . rootpath() . '/'.productCategoryAndSubcategory($row['permalink']).'/' . $row['permalink']. '.html">'.$productTitle.'</a></td>');
														}
														$getcatproid = mysql_query("SELECT * FROM `procat` WHERE `pid`=" . $row['id']);
														echo ('<td>');
														$cat = "";
														while($fetch=mysql_fetch_array($getcatproid))
														{
															$proid=$fetch['pid'];
															$catid=$fetch['cid'];
															//echo $proid;					
															$qry_category = mysql_query("SELECT `english` FROM `categories` WHERE `id`=" .$catid);	
															while($row_category = mysql_fetch_array($qry_category))
															{
																$cat .= '<a href="products.php?cat=' . $catid . (($_GET['page'])?'&page='.$page : '').'" title="View Products In ' . $row_category['english'] . '">'. $row_category['english'] .'</a> ,  '  ;
															}	
														}
														echo rtrim(trim($cat), ',') ;	
														echo ('</td>');	
														echo('<td>' . number_format ($row['clicks']) . '</td> ');
														echo('<td>' . number_format ($row['views']) . '</td> ');
														if($_SESSION['type'] !='3d3c3f3cc8a1f15cf46db659eaa2b63d'){
														echo("<td><center>" . $edit . ' ' . $delete .' '. ($row['status']==1 ? $Publish : $UnPublish)."</center></td>");
														} else {
														if(productEdit() && productDelete() && productApproved()) {
															echo("<td><center>" . $edit . ' ' . $delete . ' '.($row['status']==1 ? $Publish : $UnPublish)."</center></td>");
														} else {
														if(productEdit()){
															echo("<td><center>" . $edit . "</center></td>");
														}
														if(productDelete()){
															echo("<td><center>". $delete . "</center></td>");
														}
														if(productApproved()){
															echo("<td><center>". ($row['status']==1 ? $Publish : $UnPublish) . "</center></td>");
														}
														}
														}
														}
														echo("</tr>");
													}
												else 
												{ ?>
													<p class="not-found"><i class="fa fa-folder-open"></i> No Product Found </p>
												</div>
												<?php }
											?>  	
											</tbody>	 
										</table> 
									</div>		
									<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header btn-green text-center">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
												<h3 class="modal-title">Confirm Delete</h3>
												</div>
												<div class="modal-body">
													<input type="hidden" name="delete" id="vId" value=""/>
													<center><h5>Are You Sure to Delete All Selected? </h5></center>
												</div>
												<div class="modal-footer">
													<input type="button" name="delete" id="del_link" class="btn btn-danger" value="Yes" />
													<button type="button" class="btn btn-info" id="del_link" data-dismiss="modal" value="NO">No</button>
												</div>	
											</div>
										</div>
									</div>
								</form>					
								<div class="pull-right" id="multipleaction">
									<?php if($_SESSION['type']!='3d3c3f3cc8a1f15cf46db659eaa2b63d') {?>
									<button class="btn btn-success" id="publish"><i class="fa fa-check-circle"></i> Publish</button>
									<button class="btn btn-info" id="unpublish"><i class="icon-ban-circle"></i> UnPublish</button>
									<?php } else { 
									if(productApproved()){
									?>
									<button class="btn btn-success" id="publish"><i class="fa fa-check-circle"></i> Publish</button>
									<button class="btn btn-info" id="unpublish"><i class="icon-ban-circle"></i> UnPublish</button>
									<?php } }
									?>
									<?php if($_SESSION['type']!='3d3c3f3cc8a1f15cf46db659eaa2b63d') {?>
									<a data-toggle="modal" href="#myModal2">
									<button type="button" name="delete_selected" id="delete_selected" class="btn btn-danger deleteall"><i class="fa fa-times-circle"></i> Delete Selected</button>
									</a>
									<?php } else { 
									if(productDelete()){
									?>
									<a data-toggle="modal" href="#myModal2">
									<button type="button" name="delete_selected" id="delete_selected" class="btn btn-danger deleteall"><i class="fa fa-times-circle"></i> Delete Selected</button>
									</a>
									<?php } }
									?>
								</div>  
									<?php 
									if($rows > $limit) 
									{ 
									?>
										<ul class="pagination">
											<li><a href="products.php<?php echo (($_GET['cat'])? '?cat='.trim($_GET['cat']) : (($_GET['search'])? '?search='.trim($_GET['search']):'')) ?>">First</a></li>
											<?php
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
													echo('<li class="active"><a href="?'.(($_GET['cat'])? 'cat='.trim($_GET['cat']).'&page='.$i : (($_GET['search'])? 'search='.trim($_GET['search']).'&page='.$i : 'page='.$i)).'">' . $i . '</a></li>');
												else
													echo('<li><a href="?'.(($_GET['cat'])? 'cat='.trim($_GET['cat']).'&page='.$i : (($_GET['search'])? 'search='.trim($_GET['search']).'&page='.$i : 'page='.$i)).'">' . $i . '</a></li>');
											}
											?>
											<li><a href="?cat=<?php echo trim($_GET['cat'])?>&page=<?php echo($next) ?>">&raquo;</a></li>
											<li><a href="?<?php echo (($_GET['cat'])? 'cat='.trim($_GET['cat']).'&page='.$last : (($_GET['search'])? 'search='.trim($_GET['search']).'&page='.$last : 'page='.$last)) ?>">Last</a></li>
										</ul>
										<?php 
										}
										?>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
				</div> 
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<?php include 'common/footer.php'; ?> 	
<script type="text/javascript">
$('.meddelanden').popover({animation:true,  html:true});
$('#selectall').click(function () 
{
	$('.selectedId').prop('checked', this.checked);
	selectUnselect();
});
$('.selectedId').change(function () 
{
	selectUnselect();
	var check = ($('.selectedId').filter(":checked").length == $('.selectedId').length);
	$('#selectall').prop("checked", check);
});
function selectUnselect()
{ 
	var count_checked1 = $("[name='checkboxvar[]']:checked").length;
	if(count_checked1 <= 0) {
		$('#multipleaction').fadeOut('fast');
	} 
	else {		
		$('#multipleaction').fadeIn('fast');
	}
}
</script>