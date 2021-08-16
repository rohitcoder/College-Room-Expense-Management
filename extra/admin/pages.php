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
		$array=$_POST['arrayorder'];
		if ($_POST['update'] == "update")
		{
			$count = 1;
			foreach ($array as $idval) 
			{
				mysql_query("UPDATE pages SET displayOrder = " . $count . " WHERE id = " . $idval) or die('Error, insert query failed');
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
					$.post("pages.php", order); 															 
				}								  
			});
		});
	</script>
	<?php
	$error = "";
	$isDeleted = false;
	function deletePage($id)
	{
		mysql_query("DELETE FROM `pages` WHERE `id`='$id'");
		mysql_query("DELETE FROM `pagesLanguage` WHERE `id`='$id'");
	}
	if(isset($_GET['delete']) && is_numeric($_GET['delete']))
	{
		deletePage(xssClean(mres(trim($_GET['delete']))));
		$isDeleted = true;
	}
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>All Pages : <?php echo(getTitle()) ?></title>
	</head>
	<body>
	<div class="page-content blocky">
	<div class="container" style="margin-top:20px;">
		<?php include 'common/sidebar.php'; ?>
		<div class="mainy">
			<div class="page-title">
				<h3>
					<i class="fa fa-file color"></i> Pages (Sortable : Drag Pages to set their Display Order) 
				</h3>
				<hr />
				<?php if(isset($_GET['delete'])){ ?>
				<div class="alert alert-success alert-dismissible" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
					<strong>Success !</strong> Page Deleted Successfully!
				</div>
				<?php } ?>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="awidget">
						<div class="awidget-head">
							<div align="right" style="padding-bottom:10px;">
								<a href="add_page.php" class="btn btn-success"><i class="fa fa-plus-square-o"></i> Add New Page</a>
							</div>
						</div>
						<?php
						$result = mysql_query("SELECT * FROM `pages` ORDER BY `displayOrder` ASC");
						$count=mysql_num_rows($result);
						if($count > 0){
						?>
						<div class="awidget-body"> 
							<div class="table_header" style="width:100%;line-height: 1px">
								<div style="width:50%;float:left;">Page Name</div> 
								<div style="width:20%;float:left;text-align: center;">Action</div>
								<div style="width:15%;float:left;text-align: center;">Status</div>
								<div style="width:15%;float:left;text-align: center;">Display</div>
							</div>
							<div id="list">
								<ul>
									<?php
									if($count > 0){
									while($row = mysql_fetch_array($result, MYSQL_ASSOC))
									{				
										$id = stripslashes($row['id']);
										$text = stripslashes($row['english']);
										$showIn=$row['showIn'];
										
										$edit = '<a class="btn btn-info btn-xs" href="edit_page.php?id=' . $row['id'] . '" title="Edit ' . $row['english'] . '"><i class="fa fa-pencil"></i></a>';
										
										$delete = '<a class="btn btn-success" id="deleteMe" href="#" title="Delete ' . $row['english'] . '">Delete</a>';
										
										$del = '<a class="btn btn-xs btn-danger deletePage" id="'.$row['id'].'" data-toggle="modal" href="#deletePage" title="Delete ' . $row['english'] . '"><i class="fa fa-trash"></i></a>';
										
										$status=stripslashes($row['status']);
										?>
										<li id="arrayorder_<?php echo $id ?>">
											<div style="width:100%;float:left;line-height: 1px">
												<div style="width:50%;float:left;line-height: 1px"><?php echo $text; ?></div> 
												<div style="width:20%;float:left;line-height: 1px;text-align: center; margin-top: -11px;"><?php echo($edit . " - " . $del) ?></div>
												<div style="width:15%;float:left;line-height: 1px;text-align: center;   margin-top: -9px; font-size: 20px; color: #323A45;"><?php 
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
												<div style="width:15%;float:left;line-height: 1px;text-align: center;   margin-top: -2px; color: #323A45;"><?php 
													if($showIn==0)
													{
														?>
														<span>Footer</span>
														<?php
													}
													else if($showIn==1)
													{
														?>
														<span>Header</span>
														<?php
													}
													else if($showIn==2)
													{
														?>
														<span>Both</span>
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
							echo '<p class="not-found"><i class="fa fa-folder-open"></i> No Page Found </p>';
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
				<h4 class="modal-title"><i class="fa fa-trash"></i> Delete a Page</h4> 
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
	
	$('.alertMsg').html('Do you Want to delete "'+title.substr(7)+'" page?')
	$('#deleteMe').prop("href", "<?php echo rootpath()?>/admin/pages.php?delete="+id);
});
</script>
<?php include 'common/footer.php'; 
}
?>				 