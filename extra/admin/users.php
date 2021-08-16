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
	$sType =1;								
	include 'common/header.php';
	include 'common/navbar_admin.php';
	$error = "";
	$is_deleted = false;
	function myProduts($userId){
		$count=mysql_num_rows(mysql_query("SELECT * FROM `products` WHERE userId='$userId'"));
		return $count;
	}
	function deleteUser($id)
	{
		mysql_query("DELETE FROM `user` WHERE `id`='$id' AND `type`!=1");
	}
	if(isset($_GET['delete']) && is_numeric($_GET['delete']))
	{
		$id=xssClean(mres(trim($_GET['delete'])));
		if($id==1)
		{
			$error = "Sorry ! Can't Delete Root USER";
		}
		else
		{
			deleteUser($id);
			$is_deleted = true;
		}
	}
	$rowsperpage = 10;
	$data = mysql_query("SELECT `id` FROM `user` WHERE `type`!='$sType'");
	$totalrows=mysql_num_rows($data);
	$totalpages = ceil($totalrows / $rowsperpage);
	$last=$totalpages;
	if (isset($_GET['page']) && is_numeric($_GET['page']))
	{	
		$page = xssClean(mres(trim($_GET['page'])));
	} 	
	else 
	{
		$page = 1;
	} 
	if($page > $totalpages) 
	{
		$page = $totalpages;
	} 
	if($page < 1) 
	{
		$page = 1;
	} 
	$offset = ($page - 1) * $rowsperpage;
	?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<?php
	if(isset($_GET['type']))
	{ 
	
		if(trim($_GET['type'])=="moderator")
		{
			?>
			<title>Moderator Users</title>
			<?php 
		} 
		if(trim($_GET['type'])=="publisher")
		{
			?>
			<title>publisher Users</title>
			<?php 
		}
	}
	else
	{ 
		?>
		<title>All Users : <?php echo(getTitle()) ?></title>
		<?php 
	}
	?>
	<script type='text/javascript'>
		jQuery(function($) 
		{
			$("button[name='notallow']").click(function() 
			{ 
				alert("You do not have permision to Delete User");
				return false;
			});
			$("button[name='editnotallow']").click(function() 
			{ 
				alert("You do not have permision to Edit User");
				return false;
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
					<?php
					if(isset($_GET['type']))
					{ 
						if(trim($_GET['type'])=="moderator")
						{
							?>
							<h2><i class="fa fa-group color"></i> Moderator Users (<?php echo(moderatorUsers()) ?>)</h2>
							<?php 
						} 
						if(trim($_GET['type'])=="publisher")
						{
							?>
							<h2><i class="fa fa-group color"></i> Publisher Users (<?php echo(publisherUsers()) ?>)</h2>
							<?php 
						}
					}
					else
					{ 
						?>
						<h2><i class="fa fa-group color"></i> All Users (<?php echo(countUsers()) ?>)</h2>
						<?php 
					} 
					?>
					<hr />
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="awidget">
							<div class="awidget-head">
								<div align="right" style="padding-bottom:10px;margin-right:10px"> 
									<a href="add_user.php" class="btn btn-success"><i class="fa fa-plus-square-o"></i> Add User</a>
								</div>
								<div class="awidget-body">
									<?php
									if(isset($_GET['type']))
									{ 
										$type = $_GET['type'];
										if(trim($_GET['type'])=="moderator")
										{
											$match = "SELECT * FROM `user` WHERE `type`=2 ORDER BY `id` DESC LIMIT ".$offset.",".$rowsperpage;
										} 
										if(trim($_GET['type'])=="publisher")
										{	
											$match = "SELECT * FROM `user` WHERE `type`=3 ORDER BY `id` DESC LIMIT ".$offset.",".$rowsperpage;
										} 
									}
									else	
									{
										$match = "SELECT * FROM `user` WHERE `type` !='$sType'  ORDER BY `id` DESC LIMIT ".$offset.",".$rowsperpage;
									}
									$qry = mysql_query($match);
									$num_rows = mysql_num_rows($qry); 
									if ($num_rows > 0) 
									{
										?>
										<div class="table-responsive">
										<table class="table table-hover table-bordered ">
											<thead>
												<tr>
													<th>User Name</th>
													<th>Email</th>
													<th>Type</th>
													<th>Products</th>
													<th><center>Action</center></th>
												</tr>
											</thead>
											<tbody>			   
												<?php
												while($row = mysql_fetch_array($qry))
												{
													$id=$row['id'];
													if($sType==1)
													{
														$edit = '<a class="btn btn-xs btn-info" href="edit_user.php?id='.$row['id'].'" title="Edit ' . $row['title'] . '"><i class="fa fa-edit"></i></a>';
														if(isset($_GET['type']))
														{
															$delete = '<a class="btn btn-danger" href="users.php?delete='.$row['id'].'&type='.$type.'&page='.$page.'" title="Delete ' . $row['title'] . '">Delete</i></a>';
														}
														else
														{
															$delete = '<a class="btn btn-danger" href="users.php?delete='.$row['id'].'&page='.$page.'" title="Delete ' . $row['title'] . '">Delete</i></a>';
														}
														$del = '<a class="btn btn-xs btn-danger"  href="user_delete.php?uid='.$row['id'].'" title="Delete ' . $row['title'] . '"><i class="fa fa-trash"></i></a>';
													}
													echo('<tr>');
														echo('<td>'.$row['username'].'</td>');
														echo('<td>' . $row['email']. '</td>');
													if($row['type']==2)
													{
														echo('<td><a title="Show All Moderator" href="users.php?type=moderator">'.Moderator.'</a></td>');
													}
													if($row['type']==3)
													{
														echo('<td><a title="Show All Publisher" href="users.php?type=publisher">'.Publisher.'</a></td>');
													}
													echo('<td>' . myProduts($row['id']). '</td>');
													echo("<td><center>" . $edit . ' ' . $del . "</center></td>");
													echo("</tr>");
													?>
													<div id="delete-<?php echo $row['id'] ?>" class="modal fade"  tabindex="-1" role="dialog">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
																	<h4 class="modal-title"><i class="fa fa-trash"></i> Delete a User</h4> 
																</div>
																<div class="modal-body">
																	<p style="text-align:center;font-size:15px">Do you Want to Delete the User ?? </p>
																</div>
																<div class="modal-footer">
																	<?php echo $delete ?>
																	<button type="button" class="btn btn-info" data-dismiss="modal" aria-hidden="true">No</button>
																</div>
															</div>
														</div>
													</div>
													<?php
												}
												?> 	
											</tbody>
										</table>
										</div>
										<?php
									} 
									else 
									{
										?>
										<p class="not-found"><i class="fa fa-folder-open"></i> No Records Found</p>
										<?php
									}
									if($error!="")
									{ 
										?>
										<span class="label label-danger"><?php echo($error); ?></span>
										<?php 
									} 
									?>
									<ul class="pagination" style="margin-left:8px">
									<?php
									if($totalrows>$rowsperpage)	
									{
										if($page<3)
										{
											$range=7;
										}
										else
										{
											$range = $page+4;
										}
										$start=1;
										if ($page > 2) 
										{
											$start=$page-2; 
										}
										if ($page > 1) 
										{  
											echo "<li><a href='users.php?page=$start&type=$type'>First</a><li> ";
											$prevpage = $page - 1;
											echo "<li><a href='users.php?page=$prevpage&type=$type'><</a><li> ";
										} 
										for ($x = $page-2; $x <= $range-2; $x++)
										{
											if (($x > 0) && ($x <= $totalpages)) 
											{
												if ($x == $page) 
												{
													echo "<li class='active'><a>$x</a></li>";
												} 
												else 
												{
													echo "<li><a href='users.php?page=$x&type=$type'>$x</a></li> ";
												}
											} 
										}
										$next=$x-1;
										if ($page != $totalpages) 
										{
											$nextpage = $page + 1;
											echo " <li><a href='users.php?page=$nextpage&type=$type'>></a></li> ";
											echo " <li><a href='users.php?page=$last&type=$type'>Last</a></li> ";
										} 
									}
									?>
									</ul>
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
	<?php include 'common/footer.php'; 
}
?>	
			 