<?php
error_reporting(0);
if(!isset($_SESSION)) session_start();
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
	
	$parent = $_POST['arrayorder'];
	for ($i = 0; $i < count($parent); $i++) 
	{
		mysql_query("UPDATE `categories` SET `displayOrder`=" . $i . " WHERE `id`='" . $parent[$i] . "'")or die(mysql_error());
	}
	if(isset($_POST['subarrayorder']))
		$sub = $_POST['subarrayorder'];
	for ($i = 0; $i < count($sub); $i++) 
	{
		mysql_query("UPDATE `categories` SET `displayOrder`=" . $i . " WHERE `id`='" . $sub[$i] . "'") or die(mysql_error());
	}
	?>
	<script src="style/ui/jquery-2.0.2.js"></script>
	<script src="style/ui/jquery.ui.core.js"></script>
	<script src="style/ui/jquery.ui.widget.js"></script>
	<script src="style/ui/jquery.ui.mouse.js"></script>
	<script src="style/ui/jquery.ui.sortable.js"></script>
	<script src="style/ui/jquery.ui.accordion.js"></script>
	<script src="style/ui/jquery.ui.js"></script>
	<script>
	$(function() {
		$( ".connectedSortable" )
		.sortable({
			connectWith:['.connectedSortable'],
			placeholder: "ui-sortable-placeholder", 
			forcePlaceholderSize: true,
			update : function () {
				serial = $(this).sortable('serialize');
				$.ajax({
					url: "categories.php",
					type: "post",
					data: serial,
					error: function(){
						alert("theres an error with AJAX");
					}
				});
			}
		})
		.droppable({
			accept: '.connectedSortable > div',
			drop: function(event,ui) {
				var cat_id=ui.draggable.attr('name');
				var parent=$(this).attr('id');
				set(parent,cat_id);
			}
		})
		.disableSelection();
		$(".edit").click(function(){
			 var url = $(this).attr("href");
			$(location).attr('href',url);
			return false;
		});
		$(".delete").click(function(){
			 var url = $(this).attr("href");
			$(location).attr('href',url);
			return false;
		});	 		
		var $tabs = $( "#tabs" ).accordion ({
			heightStyle: "content",
			collapsible: true,
			header: ".h3",
			active:false,
			beforeActivate: function(event, ui) {
				// The accordion believes a panel is being opened
				if (ui.newHeader[0]) {
					var currHeader  = ui.newHeader;
					var currContent = currHeader.next('.ui-accordion-content');
				// The accordion believes a panel is being closed
				}
				else {
					var currHeader  = ui.oldHeader;
					var currContent = currHeader.next('.ui-accordion-content');
				}
				// Since we've changed the default behavior, this detects the actual status
				var isPanelSelected = currHeader.attr('aria-selected') == 'true';

				// Toggle the panel's header
				currHeader.toggleClass('ui-corner-all',isPanelSelected).toggleClass('accordion-header-active ui-state-active ui-corner-top',!isPanelSelected).attr('aria-selected',((!isPanelSelected).toString()));

				// Toggle the panel's icon
				currHeader.children('.ui-icon').toggleClass('ui-icon-triangle-1-e',isPanelSelected).toggleClass('ui-icon-triangle-1-s',!isPanelSelected);

				// Toggle the panel's content
				currContent.toggleClass('accordion-content-active',!isPanelSelected)    
				if (isPanelSelected) { currContent.slideUp(); }  else { currContent.slideDown(); }

				return false; // Cancel the default action
			}
		})
	});
	function set(parent,cat_id){
		$.ajax({
			url: "sub_cat_sort.php",
			type: "post",
			data:  "parent="+parent+"&name="+cat_id,
		});
	}
	$(function() { 
		$( "#tabs" )
			.sortable({
				axis: "y",
				placeholder: "ui-sortable-placeholder", 
				forcePlaceholderSize: true,
				handle: ".h3",
				stop: function( event, ui ) {
					ui.item.children( ".h3" ).triggerHandler( "focusout" );
				},
				update : function () {
					serial = $('#tabs').sortable('serialize');
					$.ajax({
						url: "categories.php",
						type: "post",
						data: serial,
						error: function(){
							alert("theres an error with AJAX");
						}
					});
				}
			});
	});
	</script>
	<?php
	$error = "";	
	$is_deleted = false;
	function category_delete($id) {
		mysql_query("DELETE FROM `categories` WHERE `id`=" . $id);
	}
	if(isset($_GET['delete']) && is_numeric($_GET['delete'])) {
		if($_GET['delete']==1){
			$error = "Sorry ! Can't Delete Root Category";
		}
		else{
			$delete=xssClean(mres(trim($_GET['delete'])));
			category_delete($delete);
			$is_deleted = true;
		}
	}
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<!-- TemplateBeginEditable name="doctitle" -->			
	<title>All Categories : <?php echo(getTitle()) ?></title>
	<script type="text/javascript">
		$( document ).ready(function() {
			$( "#deletecategory" ).hide();
		});
	</script>
	</head>
	<body>
	<div class="page-content blocky">
		<div class="container" style="margin-top:20px;">
			<?php include 'common/sidebar.php'; ?>
			<div class="mainy">
				<!-- Page title -->
				<div class="page-title">
					<h3><i class="fa fa-folder-open color"></i> Categories (Sortable:Drag category to set their Display Order) </h3> 
				</div>
				<!-- Page title -->    
				<div class="row">
					<div class="col-md-12">
						<div class="awidget">
							<div class="awidget-head">
								<div align="right" style="padding-bottom:10px;">
									<a href="add_category.php" class="btn btn-success"><i class="fa fa-plus-square-o"></i> Add Category</a>
								</div>
								<?php
								$count=mysql_num_rows(mysql_query("SELECT `id`,`english` FROM `categories` WHERE `parentId`=0 ORDER BY displayOrder ASC"));
								if($count > 0){ ?>
								<div class="awidget-body">   
									<div class="row alert" style="border-bottom: 1px solid #EEE; font-weight: bold; font-size: 16px;">
										<div class="col-lg-4">Category Name</div> 
										<div class="col-lg-4">Total Products</div>
										<div class="col-lg-4"><center>Action</center></div>
									</div>
									<div id="tabs">
										<?php
										$qry = mysql_query("SELECT `id`,`english` FROM `categories` WHERE `parentId`=0 ORDER BY displayOrder ASC");
										while($row = mysql_fetch_array($qry))
										{
											echo '<div style="margin-bottom:10px" class="tabs-1" id="arrayorder_' . $row['id'] . '">' ;?>
											<?php 
											$categoryid=$row['id'];
											$procatquery=mysql_query("SELECT * FROM `procat` WHERE cid IN(SELECT id FROM categories WHERE parentId='$categoryid') OR cid='$categoryid'");	
											$numRowsProducts = mysql_num_rows($procatquery); 
											$edit = '<a  style="color:white" class="btn btn-xs btn-info edit " href="edit_category.php?id=' . $row['id'] . '" title="Edit ' . $row['english'] . '"><i class="fa fa-pencil"></i></a>';
											$id=$row['id'];
											$delete = "<a style='color:white' class='btn btn-xs btn-danger delete' href='category_delete.php?delete=$id' ><i class='fa fa-times'></i></a>";
											?>
											<div class="row h3 cat">
												<div id="name" class="col-lg-4"><?php echo $row['english'];?></div> 
												<div class="col-lg-4 no-open"><?php echo $numRowsProducts; ?></div>
												<div class="col-lg-4 action" style="text-align: right; position: relative; left: 14px;"><?php echo  $edit . ' - ' . $delete ?></div>
											</div>
											<div id="<?php echo $row['id'];?>" class="connectedSortable ui-helper-reset">
												<?php
												$qry_sub=mysql_query("SELECT `id`,`english` FROM `categories` WHERE `parentId`='".$row['id']."' ORDER BY displayOrder ASC"); 
												$total_sub=mysql_num_rows($qry_sub);
												while($rowSub = mysql_fetch_array($qry_sub)){
												$subCatId=$rowSub['id'];
												$qryProductsSub = mysql_query("SELECT `pid`,`cid` FROM `procat` WHERE `cid`='".$subCatId."'");
												$numRowsProductsSub = mysql_num_rows($qryProductsSub); 
												$edit = '<a  style="color:white" class="btn btn-xs btn-info edit " href="edit_category.php?id=' . $rowSub['id'] . '" title="Edit ' . $rowSub['english'] . '"><i class="fa fa-pencil"></i></a>';
												$id=$rowSub['id'];
												if($numRowsProductsSub==0)
												{
													$delete = "<a style='color:white' id='meddelanden' data-toggle='clickover' data-placement='left'  data-content='<a style=color:white href=categories.php?delete=$id class=&quot;btn btn-primary btn-xs&quot;><i style=color:white class=icon-ok></i> Yes</a><a id=close-popover data-toggle=clickover class=&quot;btn btn-default btn-xs&quot; onclick=$(&quot;.meddelanden&quot;).popover(&quot;hide&quot;);$(&quot;.popover&quot;).hide();><i class=fa fa-times></i> No</a>' class='btn btn-xs btn-danger meddelanden'><i class='fa fa-times'></i></a>";
												}
												else
												{
													$delete = "<a style='color:white' class='btn btn-xs btn-danger delete' href='category_delete.php?delete=$id' ><i class='fa fa-times'></i></a>";
												}
												echo '<div style="margin-bottom:5px;padding:8px 0;background:#ECF0F1;color:#4F5E66"  class="ui-state-default row" id="subarrayorder_' . $rowSub['id'] . '" name="' . $rowSub['english'] . '">
												<div class="col-lg-4">'.$rowSub['english'].'</div>
												<div class="col-lg-4">'.$numRowsProductsSub.'</div>
												<div class="col-lg-4" style="text-align: center; padding-left: 35px;">' . $edit . ' - ' . $delete .'</div>
												</div>' ;
												}
												?>
											</div>
											</div>
											<?php
										}
										?>
									</div>
								</div>   
							<?php 
							}
							else
							{
								echo '<p class="not-found"><i class="fa fa-folder-open"></i> No Category Found </p>';
							} ?>
							</div> 
						</div> 

						<?php
						if($error!="")
						{ 
							?>
							<span class="label label-danger"><?php echo($error); ?></span>
							<?php 
						}             
						if(isset($_GET['delete']) && $error=="")
						{
							echo('<script type="text/javascript">
							$( document ).ready(function() {
							$( "#deletecategory" ).click();
							});
							</script>');
						} 
						?>                                                 
						<button class="notify-without-image" id="deletecategory"></button>               
						<div class="clearfix"></div>
					</div>
					</div>
			</div>
		</div>
	</div>                   
	<div class="clearfix"></div>        
	<?php include 'common/footer.php';
} 
?>
<script type="text/javascript"> 
	$('.meddelanden').popover({animation:true,  html:true});
</script>