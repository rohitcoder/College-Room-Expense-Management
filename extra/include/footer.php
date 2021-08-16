		</aside><!-- /Right Side -->
		</div>
		<aside class="right-side"><!-- Right Side -->
		<div class="footer"><!--Footer Starts-->
		<select id="language" class="form-control">
		<?php
		if(isset($_SESSION['lanGuaGe'])){
			$query=mysql_query("SELECT * FROM `languages` WHERE `languageName`!='".$_SESSION['lanGuaGe']."' AND `status`='1'");
			?><option value="<?php echo $_SESSION['lanGuaGe']?>"><?php echo ucfirst($_SESSION['lanGuaGe'])?></option><?php
			while($fetch=mysql_fetch_array($query)){
				?><option value="<?php echo $fetch['languageName']?>"><?php echo ucfirst($fetch['languageName'])?></option><?php
			}
		} ?>
		</select>
			<div class="links">
				<a class="selectedPage" href="<?php echo rootpath()?>/contact">
					<i class="fa fa-envelope"></i> <?php echo $lang_array['contact_us']?>
				</a>
				<?php
				$qry=mysql_query("SELECT * FROM `pages` WHERE `status`=1 AND `showIn`='0' OR `showIn`='2' ORDER BY `displayOrder`");
				while($row=mysql_fetch_array($qry))
				{ ?>
				<a href="<?php echo rootpath().'/page/'.$row['permalink']?>"><?php echo $row[$_SESSION['lanGuaGe']]?></a>
				<?php
				}
				$qry=mysql_query("SELECT * FROM `links` WHERE `status`=1 AND `showIn`='0' OR `showIn`='2' ORDER BY `displayOrder`");
				while($row=mysql_fetch_array($qry))
				{ ?>
				<a href="<?php echo $row['url']?>" <?php echo ($row['newTab'] ? 'target="_blank"' : '')?>><?php echo $row[$_SESSION['lanGuaGe']]?></a>
				<?php
				}
				?>
				</div>
				<p class="copyrights"><?php echo $lang_array['copyright']?> <a href="<?php echo rootpath()?>"><?php echo $webdata['websiteName']?></a> <?php echo $lang_array['powered_by']?> <a href="http://wwww.nexthon.com"><?php echo $lang_array['developer']?></a></p>
			</div><!-- /Footer Ends -->
			
		</aside><!-- /Right Side -->
        <!-- Bootstrap -->
        <script src="<?php echo rootpath()?>/style/js/bootstrap.min.js" type="text/javascript"></script>
		<script src="<?php echo rootpath()?>/style/js/jquery.jscrollpane.min.js" type="text/javascript"></script>
		<script>
		$(document).ready(function(){
		$('.loading').imgPreload();
		$('.sp-large img').imgPreload();
		$('.lgi-scroll img').imgPreload();
		});
		</script>
		<script>
		$(document).ready(function(){
			if (!$.browser.webkit) {
				  $('.lgi-scroll').jScrollPane();
			  }
			$(".item").first().addClass('active');
		 });
		</script>
        <!-- Custom js -->
        <script src="<?php echo rootpath()?>/style/js/custom.js" type="text/javascript"></script>  
		<script type="text/javascript">
            var _gaq=[['_setAccount','UA-404294-1'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
		<script>
		$('.isActive').on('click', function(){
			$('.isActive').removeClass('active');
			$(this).addClass('active');
			$('.isActive').children('.treeview-menu').css('display','');
			$(this).children('.treeview-menu').css('display','block');
		});
		$('.selectedPage').on('click', function(){
			$('.selectedPage').removeClass('active');
			$(this).addClass('active');
		});
		$.ajax
			({
				type:"POST",
				url: "<?php echo rootpath()?>/go",
				data: {'stats':'stats'}
			});
		$(".favPrd").click(function(){
			pid=this.id;
			uid='<?php echo $_SESSION['store_uid']?>';
			if(uid!="")
			{
			if ($(this).hasClass("active")) {
				$(this).removeClass('active');
				if ($(this).parent().parent().hasClass('markASdel'))
				{
				$(this).parent().parent().remove();
				}
				var removeFav=1;
				$.ajax({
				type:"POST",
				url: "<?php echo rootpath()?>/go",
				data: {'removeFav':removeFav,'uid':uid,'pid':pid},
				success: function(info) {
				}
				});
			}
			else
			{
				$(this).addClass('active');
				var addFav=1;
				$.ajax({
				type:"POST",
				url: "<?php echo rootpath()?>/go",
				data: {'addFav':addFav,'uid':uid,'pid':pid},
				success: function(info) {
				}
				});
			}
			}
		});
		$(document).ready(function(){
    
			var clickEvent = false;
			$('#myCarousel').carousel({
				interval:   4000	
			}).on('click', '.list-group li', function() {
					clickEvent = true;
					$('.list-group li').removeClass('active');
					$(this).addClass('active');		
			}).on('slid.bs.carousel', function(e) {
				if(!clickEvent) {
					var count = $('.list-group').children().length -1;
					var current = $('.list-group li.active');
					current.removeClass('active').next().addClass('active');
					var id = parseInt(current.data('slide-to'));
					if(count == id) {
						$('.list-group li').first().addClass('active');	
					}
				}
				clickEvent = false;
			});
		})

		$(window).load(function() {
			var boxheight = $('#myCarousel .carousel-inner').innerHeight();
			var itemlength = $('#myCarousel .item').length;
			var triggerheight = Math.round(boxheight/itemlength+1);
			$('#myCarousel .list-group-item').outerHeight(triggerheight);
		});

		</script>
		<script type="text/javascript">
		$("#language").change(function(){
			var lanGuaGe=$(this).val();
			document.cookie = "lanGuaGe="+lanGuaGe;
			location.reload();
		});
		</script>
    </body><!--/Body Ends-->
</html>