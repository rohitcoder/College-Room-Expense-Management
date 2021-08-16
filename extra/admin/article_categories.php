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
	include 'common/navbar_admin.php';
	if(isset($_POST['arrayorder']))
	{
		$array	= $_POST['arrayorder'];
		if ($_POST['update'] == "update")
		{
			$count = 1;
			foreach ($array as $idval) 
			{
				mysql_query("UPDATE `articleCategories` SET displayOrder = " . $count . " WHERE `cid` = " . $idval) or die('Error, insert query failed');
				$count ++;	
			}
		}
	}
	?>
	<script src="style/ui/jquery.ui.sortable.js"></script>
	<script src="style/ui/jquery.ui.accordion.js"></script>
	<style>
	ul {
		padding:0px;
		margin: 0px;
	}
	#list li {
		margin: 0 0 3px;
		padding:18px;
		list-style: none;
		font-size: 14px;
		font-weight: bold;
		border-radius:1px;
		background-color: #ECF0F1;
		color: #323A45;
	}
	.table_header {
		margin: 0 0 3px;
		padding: 18px;
		background-color: #323A45;
		color: #fff;
		list-style: none;
		font-size: 14px;
		font-weight: bold;
		border-radius: 2px;
	}
	</style>
	<script type="text/javascript">
		$(function() 
		{
			$("#list ul").sortable
			({ 
				opacity: 0.8, 
				cursor: 'move',
				placeholder: "sortable-placeholder", 
				forcePlaceholderSize: true,
				update: function() 
				{
					var order = $(this).sortable("serialize") + '&update=update';
					$.post("article_categories.php", order); 															 
				}								  
			});
		});
	</script>
	<?php
	$error = "";
	$isDeleted = false;
	function deletearticleCategory($id)
	{
		$query=mysql_query("SELECT * FROM articles WHERE cid='$id'");
		while($row=mysql_fetch_array($query)){
			unlink('../images/articleImages/'.getArticleImage($row['id']));
			unlink('../images/articleImages/_'.getArticleImage($row['id']));
			mysql_query("DELETE FROM `articlesLanguage` WHERE `id`=".$row['id']);
		}
		mysql_query("DELETE FROM `articles` WHERE `cid`='$id'");
		mysql_query("DELETE FROM `articleCategories` WHERE `cid`='$id'");
		clearArticlesCache();
		clearArticleCategoriesCache($id);
	}
	if(isset($_GET['delete']) && is_numeric($_GET['delete']))
	{
		$id=xssClean(mres(trim($_GET['delete'])));
		deletearticleCategory($id);
		$isDeleted = true;
	}
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>All Article Categories : <?php echo(getTitle()) ?></title>
	</head>
	<body>
	<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h3>
					<i class="fa fa-folder-open color"></i> Article Categories (Sortable : Drag Pages to set their Display Order) 
				</h3>
				<hr />
				<?php if(isset($_GET['delete'])){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Article Category Deleted Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="awidget">
						<div class="awidget-head">
							<div align="right" style="padding-bottom:10px;">
								<a href="add_articleCategory.php" class="btn btn-success"><i class="fa fa-plus-square-o"></i> Add New Category</a>
							</div>
						</div>
						<?php
						$result = mysql_query("SELECT * FROM `articleCategories` ORDER BY `displayOrder`");
						$count=mysql_num_rows($result);
						if($count > 0){
						?>
						<div class="awidget-body"> 
							<div class="table_header" style="width:100%;line-height: 1px">
								<div style="width:50%;float:left;">Name</div> 
								<div style="width:25%;float:left;text-align: center;">Action</div>
								<div style="width:25%;float:left;text-align: center;">Status</div>
							</div>
							<div id="list">
								<ul>
									<?php
									if($count > 0){
									while($row = mysql_fetch_array($result, MYSQL_ASSOC))
									{				
										$cid = stripslashes($row['cid']);
										$status=$row['status'];
										
										$edit = '<a class="btn btn-info btn-xs" href="edit_articleCategory.php?cid=' . $row['cid'] . '" title="Edit ' . $row['english'] . '"><i class="fa fa-pencil"></i></a>';
										
										$delete = '<a class="btn btn-success" id="deleteMe" href="#" title="Delete ' . $row['english'] . '">Delete</a>';
										
										$del = '<a class="btn btn-xs btn-danger deletePage" id="'.$row['cid'].'" data-toggle="modal" href="#deletePage" title="Delete ' . $row['english'] . '"><i class="fa fa-trash"></i></a>';
										
										$status=stripslashes($row['status']);
										?>
										<li id="arrayorder_<?php echo $cid ?>">
											<div style="width:100%;float:left;line-height: 1px">
												<div style="width:50%;float:left;line-height: 1px"><?php echo $row['english']; ?></div> 
												<div style="width:25%;float:left;line-height: 1px;text-align: center; margin-top: -11px;"><?php echo($edit . " - " . $del) ?></div>
												<div style="width:25%;float:left;line-height: 1px;text-align: center;   margin-top: -9px; font-size: 20px; color: #323A45;"><?php 
													if($status==1)
													{
														?>
														<i class="fa fa-check-circle" style="color: green"></i>
														<?php
													}
													else
													{
														?>
														<i class="fa fa-times-circle" style="color: red"></i>
														<?php
													}
													?>
												</div>
											</div>
										</li>
										<?php 
									}
									}
									?>
								</ul>
							</div> 
							<div class="clearfix"></div>
						</div>
						<?php
						}
						else
						{
							echo '<p class="not-found"><i class="fa fa-folder-open"></i> No Article Found </p>';
						} ?>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
<div class="modal fade" id="deletePage" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title"><i class="fa fa-trash"></i> Delete a category</h4> 
			</div>
			<div class="modal-body">
				<p style="text-align:center;font-size:15px" class="alertMsg"> </p>
			</div>
			<div class="modal-footer">
				<?php echo $delete ?>
				<button type="button" class="btn btn-info" data-dismiss="modal" aria-hidden="true">No</button>
			</div>
		</div>
	</div>
</div>
<script>
$(".deletePage").click(function(){
    var id=this.id;
	var title=$(this).attr('title');
	
	$('.alertMsg').html('Do you Want to delete "'+title.substr(7)+'" category?')
	$('#deleteMe').prop("href", "<?php echo rootpath()?>/admin/article_categories.php?delete="+id);
});
</script>
<?php include 'common/footer.php'; 
}
?>				 