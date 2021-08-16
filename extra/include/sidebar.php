<script src="<?php echo rootpath()?>/style/js/typeahead.js"></script>
<script>
$(document).ready(function() {
$('input.typeahead').bind("typeahead:selected", function () {
var search=$('#search_box').val().trim();
if(search !="") 
	{
		search=search.split(/\s+/).slice(0,9).join(" ");
		search=search.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, ' ');
				search=search.trim();
		search=search.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '-');
		search=search.toLowerCase();
		var intIndexOfMatch = search.indexOf("--");	
		while (intIndexOfMatch != -1)
		{
			search = search.replace( "--", "-" )		
			intIndexOfMatch = search.indexOf( "--" );	
		}
		window.location="<?php echo rootpath()?>/search/"+search;
	}
});
$('#search_box').typeahead([ {
name: 'search',
remote: '<?php echo rootpath()?>/autocomplete.php?query=%QUERY',
}]);
$('#search_box').keypress(function (e) 
	{
		if (e.which == 13) 
		{  
			var search=$('#search_box').val().trim();
			if(search !="") 
			{
				search=search.split(/\s+/).slice(0,9).join(" ");
				search=search.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, ' ');
				search=search.trim();
				search=search.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '-');
				search=search.toLowerCase();
				var intIndexOfMatch = search.indexOf("--");	
				while (intIndexOfMatch != -1)
				{	
					search = search.replace( "--", "-" )		
					intIndexOfMatch = search.indexOf( "--" );	
				}
				window.location="<?php echo rootpath()?>/search/"+search;
			}
			return false;
		}
	});
	$(".clickMe").click(function()
		{
			var search=$('#search_box').val().trim();
			if(search !="") 
			{
				search=search.split(/\s+/).slice(0,9).join(" ");
				search=search.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, ' ');
				search=search.trim();
				search=search.replace(/[`~!@#$%^&*()_|+\-=?;:'",.<>\{\}\[\]\\\/]/gi, '-');
				search=search.toLowerCase();
				var intIndexOfMatch = search.indexOf("--");	
				while (intIndexOfMatch != -1)
				{	
					search = search.replace( "--", "-" )		
					intIndexOfMatch = search.indexOf( "--" );	
				}
				window.location="<?php echo rootpath()?>/search/"+search;
			}
		});
});
</script>
<div class="wrapper side row-offcanvas row-offcanvas-left"><!--Wrapper-->
            <aside class="left-side sidebar-offcanvas">
                <section class="sidebar"><!--Left SideBar-->
					
					<div id="product_form" role="search" class="sidebar-form"><!-- Search Form -->
                        <?php
						if(isset($Search))
						{ 	?>
							<div class="input-group">
								<input type="text" name="search" class="form-control typeahead" value="<?php echo(strip_tags(trim(str_replace('-',' ',$Search)))) ?>" id="search_box" placeholder="<?php echo $lang_array['search']?>" required />
								<span class="input-group-btn">
									<button id='search-btn' class="btn btn-flat clickMe"><i class="fa fa-search"></i></button>
								</span>
							</div>
							<?php
						}
						if(!isset($Search)) 
						{ ?>
							<div class="input-group ">
								<input type="text" name="search" class="form-control typeahead" id="search_box"  placeholder="<?php echo $lang_array['search']?>" required />
								<span class="input-group-btn">
									<button id='search-btn' class="btn btn-flat clickMe"><i class="fa fa-search"></i></button>
								</span>
								
							</div>
							<?php
						}
						?>
                    </div>	           
                    
                    <ul class="sidebar-menu"><!--Left Sidebar SubMenu-->
                        <li class="<?php echo (($curPageName=='home' || $curPageName=='index' || $curPageName=='index.php' || $curPageName=='') && $PageName=="") ? 'active' : ''?>">
                            <a href="<?php echo rootpath()?>/">
                                <span><?php echo $lang_array['home']?></span>
                            </a>
						</li>
						 <?php
						$qry = mysql_query("SELECT * FROM `categories` WHERE `parentId`=0 ORDER BY displayOrder ASC");
						$numRows = mysql_num_rows($qry); 
						if ($numRows > 0)
						{

							while($row = mysql_fetch_array($qry)) 
							{
								$qry_sub = mysql_query("SELECT * FROM `categories` WHERE `parentId`='" . $row["id"]."' ORDER BY `displayOrder` ASC");
								$numRowsSub = mysql_num_rows($qry_sub);
							?>
								 		<li class="<?php echo ($numRowsSub > 0) ? 'treeview ' : ''?><?php echo ($getCategory==catIdToPermalink($row['id']) || $row[$_SESSION['lanGuaGe']]==parentCategoryName($getCategory)) ? 'active' : ''?>">
											<a href="<?php echo rootpath() . '/category/' . catIdToPermalink($row['id'])?>"><?php echo $row[$_SESSION['lanGuaGe']].' ('.productsInParentCategory($row['id']).')'?><i class="<?php echo ($numRowsSub > 0) ? 'fa fa-angle-left pull-right' : ''?>"></i>
											</a>
										<ul class="<?php echo ($numRowsSub > 0) ? 'treeview-menu' : ''?>">
										<?php
										while($row_sub = mysql_fetch_array($qry_sub)) 
										{ ?>
											<li class="<?php echo ($getCategory==catIdToPermalink($row_sub['id'])) ? 'active' : ''?>">
												<a href="<?php echo rootpath() . '/category/'.catIdToPermalink($row['id']).'/' . catIdToPermalink($row_sub['id'])?>"> <i class="fa fa-angle-double-right"></i><?php echo $row_sub[$_SESSION['lanGuaGe']].' ('.productsInChildCatecory($row_sub['id']).')' ?></a>
											</li>	
										<?php
										} 
										if ($numRowsSub > 0)
										{ ?>
										<li>
											<a href="<?php echo rootpath() . '/category/' . catIdToPermalink($row["id"]) ?>"><i class="fa fa-angle-double-right"></i><?php echo $lang_array['all_products']?>
											</a>
										</li>
										<?php } ?>
										</ul></li>
								<?php

							}
						} ?> 
                    </ul><!--/Left Sidebar SubMenu-->
                    
                </section><!-- /Left Sidebar -->
            </aside>
            
            <aside class="right-side"><!--Right Side-->