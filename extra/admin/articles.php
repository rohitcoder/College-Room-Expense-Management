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
$search_string=xssClean(mres(trim($_GET['search'])));
$sort_string=xssClean(mres(trim($_GET['sort'])));
if(isset($_GET['sort_by']))
{
	if(trim($_GET['sort_by'])=="date" || trim($_GET['sort_by'])=="views" || trim($_GET['sort_by'])=="rating" || trim($_GET['sort_by'])=="title")
	{
		if(trim($_GET['sort_by'])=="date")
				$_SESSION['soRTBy']="date";
		else if(trim($_GET['sort_by'])=="views")
			$_SESSION['soRTBy']="views";
		else if(trim($_GET['sort_by'])=="rating")
			$_SESSION['soRTBy']="rating";
		else if(trim($_GET['sort_by'])=="title")
			$_SESSION['soRTBy']="title";
		else
			$_SESSION['soRTBy']="date";
	}
}
if(!isset($_SESSION['soRTBy']))
	$_SESSION['soRTBy'] = "date";
if(!isset($_SESSION['sort_order']))
	$_SESSION['sort_order'] = "DESC";
$is_deleted = false;
if(isset($_GET['delete']) && is_numeric($_GET['delete']))
{
	$id=xssClean(mres(trim($_GET['delete'])));
	if(($_SESSION['type']=='3d3c3f3cc8a1f15cf46db659eaa2b63d' && articleDelete()) || $_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87' || $_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf'){
	unlink('../images/articleImages/'.getArticleImage($id));
	$filename=str_replace('-img','',getArticleImage($id));
	unlink('../images/articleImages/'.$filename);
	unlink('../images/articleImages/articleImagesBackUp/'.$filename);
	mysql_query("DELETE FROM `articles` WHERE id='$id'");
	mysql_query("DELETE FROM `articlesLanguage` WHERE id='$id'");
	mysql_query("DELETE FROM `articleRatings` WHERE id='$id'");
	clearArticlesCache();
	clearShowArticlesCache($id);
	clearArticleCategoriesCache(getArticleCategoryId($id));
	}
}
if($_SESSION['type']!='3d3c3f3cc8a1f15cf46db659eaa2b63d' || articleApproved()){
if(isset($_GET['publish']) && is_numeric($_GET['publish']))
{
	$id=xssClean(mres(trim($_GET['publish'])));
	if(($_SESSION['type']=='3d3c3f3cc8a1f15cf46db659eaa2b63d' && articleApproved()) || $_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87' || $_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf'){
	mysql_query("UPDATE `articles` SET `status`='0' WHERE `id`='$id'");
	clearArticlesCache();
	clearShowArticlesCache($id);
	clearArticleCategoriesCache(getArticleCategoryId($id));
	}
}
if(isset($_GET['unpublish']) && is_numeric($_GET['unpublish']))
{
	$id=xssClean(mres(trim($_GET['unpublish'])));
	mysql_query("UPDATE `articles` SET `status`='1' WHERE `id`='$id'");
	clearArticlesCache();
	clearArticleCategoriesCache(getArticleCategoryId($id));
	clearShowArticlesCache($id);
}
}					
$error = "";				
$page=1;//Default page 
$limit=10; //Records per page
$next=2; 
$prev=1;
$cid=trim($_GET['cid']);
//starts displaying records from 0
if($_SESSION['type']=='a3852d52cc0ac36ea335d8cdd952d4cf' || $_SESSION['type']=='f2215e916e70ca1152df06d5ce529a87') {
if(isset($_GET['search'])) {
	$search=xssClean(mres(trim($_GET['search'])));
	$data = mysql_query("SELECT * FROM `articles` WHERE `english` LIKE '%".$_GET['search']."%'");
} else if($_GET['cat']) {
$cid=xssClean(mres(trim($_GET['cat'])));
$data=mysql_query("SELECT * FROM `articles` WHERE `cid`='$cid'");
} else {
$data=mysql_query("SELECT * FROM `articles`");
}
} else {
if(isset($_GET['search'])) {
	$search=xssClean(mres(trim($_GET['search'])));
	$data = mysql_query("SELECT * FROM `articles` WHERE `english` LIKE '%".$search."%' AND `uid`=".$_SESSION['id']);
} else if($_GET['cat']) {
$cid=xssClean(mres(trim($_GET['cat'])));
$data=mysql_query("SELECT * FROM `articles` WHERE `cid`='$cid' AND `uid`=".$_SESSION['id']);
} else {
$data=mysql_query("SELECT * FROM `articles` WHERE  uid=".$_SESSION['id']);
}
}
$rows = mysql_num_rows($data);
$last = ceil($rows/$limit);
if(isset($_GET['page']) && $_GET['page']!='' && ($_GET['page']>=1 && $_GET['page']<=$last))
{
	$page=$_GET['page'];
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
<title>All Articles : <?php echo(getTitle()) ?></title>
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
					data: {'id':+the_id,'articlepublish':publish},
					success: function(n)
					{
						count=count+1;
						if(count==len){
						window.location.href="<?php echo rootpath()?>/admin/articles.php?publish_selected=1";
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
					data: {'id':+the_id,'articleunpublish':unpublish},
					success: function(n)
					{
						count=count+1;
						if(count==len){
						window.location.href="<?php echo rootpath()?>/admin/articles.php?unpublish_selected=0";
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
					data: {'id':+the_id,'articledelete':dselected},
					success: function(n)
					{
						count=count+1;
						if(count==len){
						window.location.href="<?php echo rootpath()?>/admin/articles.php?delete_selected=0";
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
							if($_SESSION['type'] !='3d3c3f3cc8a1f15cf46db659eaa2b63d' || articleApproved()){
							?>
							<div id="publish_products" class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<strong>Success!</strong> Articles's Published Successfully.
							</div>
							<?php 
							}
						} 
						else if(isset($_GET['unpublish_selected']) || isset($_GET['publish']))
						{ 
							if($_SESSION['type'] !='3d3c3f3cc8a1f15cf46db659eaa2b63d' || articleApproved()){
							?>
							<div id="unpublish_products" class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<strong>Success!</strong> Articles's Unpublished Successfully.
							</div>
							<?php 
							}
						}
						if(isset($_GET['delete']) || isset($_GET['delete_selected']))
						{ 
							if($_SESSION['type'] !='3d3c3f3cc8a1f15cf46db659eaa2b63d' || articleDelete()){
							?>
							<div class="alert alert-success alert-dismissible" role="alert">
								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
								<strong>Success!</strong> Article's Delete Successfully.
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
								$cid=xssClean(mres(trim($_GET['cat'])));
								
								$sql="SELECT * FROM `articles` WHERE `cid`='$cid' ORDER BY ".$_SESSION['soRTBy']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit ;
							}
							else if(isset($_GET['search']))
							{
								$search=xssClean(mres(trim($_GET['search'])));
								$sql = "SELECT * FROM `articles` WHERE english LIKE '%".$search."%' ORDER BY ".$_SESSION['soRTBy']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit;
							} else 
							{
								$sql = "SELECT * FROM `articles`ORDER BY ".$_SESSION['soRTBy']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit;
							}
						} else 
						{
							if(isset($_GET['cat']))
							{
								$cid=xssClean(mres(trim($_GET['cat'])));
								
								$sql="SELECT * FROM `articles` WHERE `cid`='$cid' AND uid=".$_SESSION['id']." ORDER BY ".$_SESSION['soRTBy']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit ;
							}
							else if(isset($_GET['search']))
							{
								$search=xssClean(mres(trim($_GET['search'])));
								$sql = "SELECT * FROM `articles` WHERE english LIKE '%".$search."%' AND uid='".$_SESSION['id']."'  ORDER BY ".$_SESSION['soRTBy']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit;
							} else {
								$sql = "SELECT * FROM `articles` WHERE uid='".$_SESSION['id']."' ORDER BY ".$_SESSION['soRTBy']." ".$_SESSION['sort_order']." LIMIT ".$start_result.",".$limit;
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
									<select onchange="self.location='<?php echo(rootpath()) ?>/admin/articles.php?cat=<?php echo trim($_GET['cat'])?>&sort_by='+this.options[this.selectedIndex].value+'<?php echo($qs) ?>'">
									<?php }
									else { ?>
									<select onchange="self.location='<?php echo(rootpath()) ?>/admin/articles.php?sort_by='+this.options[this.selectedIndex].value+'<?php echo($qs) ?>'">
									<?php } ?>
									<option value="title" <?php echo ($_SESSION['soRTBy']=="title" ? 'selected' : "") ?>>A-Z</option>
									<option value="views" <?php echo ($_SESSION['soRTBy']=="views" ? 'selected' : "") ?>>Views</option>
									<option value="date" <?php echo ($_SESSION['soRTBy']=="date" ? 'selected' : "") ?>>Date</option>
									<option value="rating" <?php echo ($_SESSION['soRTBy']=="rating" ? 'selected' : "") ?>>Rating</option>
									</select>
									<?php
									if(isset($_GET['cat']))
									{ ?>											
										<a title="<?php echo ($_SESSION['sort_order']=="ASC" ? 'Ascending' :'Descending')?>" href="<?php echo rootpath()?>/admin/articles.php?cat=<?php echo trim($_GET['cat'])?>&sort_by=<?php echo $_SESSION['soRTBy']?>&sort_order=<?php echo ($_SESSION['sort_order']=="ASC" ? 'DESC' :'ASC').$qs?>" style="margin-top: -4px;" class="btn btn-info btn-xs <?php echo ($_SESSION['sort_order']=="ASC" ? 'active' : 'active')?>"  type="button"><i class="fa fa-sort-amount-<?php echo ($_SESSION['sort_order']=="ASC" ? 'asc' : 'desc')?>"></i></a>
											<?php 
									} else { ?>
										<a title="<?php echo ($_SESSION['sort_order']=="ASC" ? 'Ascending' :'Descending')?>" href="<?php echo rootpath()?>/admin/articles.php?sort_by=<?php echo $_SESSION['soRTBy']?>&sort_order=<?php echo ($_SESSION['sort_order']=="ASC" ? 'DESC' :'ASC').$qs?>" style="margin-top: -4px;" class="btn btn-info btn-xs <?php echo ($_SESSION['sort_order']=="ASC" ? 'active' : 'active')?>"  type="button"><i class="fa fa-sort-amount-<?php echo ($_SESSION['sort_order']=="ASC" ? 'asc' : 'desc')?>"></i></a> 
									<?php }
									?> 												
								</label>
								<a href="add_article.php" class="btn btn-success" style="margin-left:553px;"><i class="fa fa-plus-square-o"></i> Add Article</a>
							</div>							
							<div class="awidget-body">
								<form action="articles.php" method="post">
									<div id="txtResult"></div>
									<div id="firstresult">
										<table class="table table-hover"> 
											<thead> 
												<tr>
													<th class="click"><div><input value="unknown" type="checkbox" id="selectall"></input></div></th>
													<th  class="title" id="selectall">Select All</th>
													<th  class="catagories">Categories</th>
													<th class="click clicks">Views</th>
													<?php if($_SESSION['type']!='3d3c3f3cc8a1f15cf46db659eaa2b63d' || articleEdit() || articleDelete() || articleApproved()) {?>
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
												$edit = '<a  class="btn btn-xs btn-info" href="edit_article.php?id=' . $row['id'] . '" title="Edit ' . $row['english'] . '"><i class="fa fa-pencil"></i></a>';
												$Publish = '<a  class="btn btn-xs btn-success" href="articles.php?publish=' . $row['id'] . '" title="Publish ' . $row['english'] . '"><i class="fa fa-check"></i></a>';
												$UnPublish = '<a  class="btn btn-xs btn-warning" href="articles.php?unpublish=' . $row['id'] . '" title="Unpublish ' . $row['english'] . '"><i class="fa fa-ban"></i></i></a>';
													$id=$row['id'];
													$delete = "<a id='meddelanden' data-toggle='clickover' data-placement='left' data-title='Are you sure?' data-content='<a href=articles.php?delete=$id class=&quot;btn btn-primary btn-xs yes&quot;><i class=fa fa-check></i> Yes</a>     <a id=close-popover data-toggle=clickover class=&quot;btn btn-default btn-xs&quot; onclick=$(&quot;.meddelanden&quot;).popover(&quot;hide&quot;);$(&quot;.popover&quot;).hide();><i class=fa fa-times></i> No</a>' class='btn btn-xs btn-danger meddelanden'><i class='fa fa-times'></i></a>";
												echo('<tr style="border-bottom: 1px dashed #eee;">');
												echo('<td><input class="selectedId" type="checkbox" name="checkboxvar[]" value="' . $row['id'] .'" >
												</td>');
												$productTitle=(strlen($row['english']) > 40) ? substr($row['english'],0,40).'...' : $row['english'];
												echo('<td><a title="' . $row['english'] . '" href="'.rootpath().'/show'.articleName().'/'.articleCategoryPermalinkById($row['cid']).'/'.$row['permalink'].'">'.$productTitle.'</a></td>');	
												echo ('</td>');	
												echo ('<td><a href="articles.php?cat=' . $row['cid'] . (($_GET['page'])?'&page='.$page : '').'">' . adminArticleCategoryNameById($row['cid']) . '</a></td> ');
												echo('<td>' . number_format ($row['views']) . '</td> '); 
												if($_SESSION['type'] !='3d3c3f3cc8a1f15cf46db659eaa2b63d'){
												echo("<td><center>" . $edit . ' ' . $delete .' '. ($row['status']==1 ? $Publish : $UnPublish)."</center></td>");
												} else {
												if(articleEdit() && articleDelete() && articleApproved()) {
													echo("<td><center>" . $edit . ' ' . $delete . ' '.($row['status']==1 ? $Publish : $UnPublish)."</center></td>");
												} else {
												if(articleEdit()){
													echo("<td><center>" . $edit . "</center></td>");
												}
												if(articleDelete()){
													echo("<td><center>". $delete . "</center></td>");
												}
												if(articleApproved()){
													echo("<td><center>". ($row['status']==1 ? $Publish : $UnPublish) . "</center></td>");
												}
												}
												}
												}
												echo("</tr>");
											}
											else 
											{ ?>
												<div align="left" style="padding-bottom:0px; margin-left:11px;">
													<a href="add_article.php" class="btn btn-success pull-right"><i class="fa fa-plus-square-o"></i> Add Article</a>
												</div>
												<p class="not-found"><i class="fa fa-folder-open"></i> No Article Found </p>
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
									if(articleApproved()){
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
									if(articleDelete()){
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
											<li><a href="articles.php<?php echo (($_GET['cat'])? '?cat='.trim($_GET['cat']) : (($_GET['search'])? '?search='.trim($_GET['search']):'')) ?>">First</a></li>
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