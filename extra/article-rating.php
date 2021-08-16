<?php
error_reporting(0);
if(!isset($_SESSION['lanGuaGe']))
	$_SESSION['lanGuaGe']=getDefaultLanguage();
if(isset($_COOKIE['lanGuaGe'])){
$_SESSION['lanGuaGe']=$_COOKIE['lanGuaGe'];
setcookie("lanGuaGe",$_SESSION['lanGuaGe'],time()-1,"");
}
$json = file_get_contents('language/'.$_SESSION['lanGuaGe'].'.php');
$lang_array=json_decode($json, true);
$q = mysql_query("SELECT * FROM `articleRatings` WHERE `id`='$id'");
$n = mysql_num_rows($q);
if($n==1)
	$v = $lang_array['vote'];
else
	$v = $lang_array['votes'];
while($r=mysql_fetch_array($q))
{
    $rr = $r["rating"]; //EACH RATING FOR THE CONTENT
    $x += $rr; //ADDS THEM ALL UP
}
//IF THERE ARE RATINGS...
if($n)
    $rating = $x/$n; //THE AVERAGE RATING (UNROUNDED)
else 
    $rating = 0; //SET TO PREVENT THE ERROR OF DIVISION BY 0, WHICH WOULD BE THE NUMBER OF RATINGS HERE

$decRating = round($rating, 1); //ROUNDED RATING TO THE NEAREST TENTH

//SHOWS THE FULL NUMBER OF STARS (Ex: 3.5 stars = 3 full stars)
mysql_query("UPDATE `articles` SET `rating`='$decRating' WHERE `id`='$id'");
for($i=1; $i<=floor($rating); $i++)
{
    $stars .= '<div class="star" id="'.$i.'"></div>';
}

//SHOWS THE USER'S RATING, IF RATING HAS BEEN SUBMITTED BEFORE
$q = mysql_query("SELECT * FROM articleRatings WHERE `id`='$id' AND `ip`='$ip'");
$r = mysql_fetch_assoc($q);
if($r["rating"])
{
    //$y = 'You rated this a <b>'.$r["rating"].'</b>';
}
?>
<div class="r">
<div class="rating">
	<?php
	for($i=1; $i<=floor($rating); $i++){
	?>
	<div class="star active" id="<?php echo $i?>"></div>
	<?php
	}
	for($i=floor($rating)+1; $i<=5; $i++){
	?>
	<div class="star" id="<?php echo $i?>"></div>
	<?php
	}?>
	<div class="votes">(<?php echo $decRating.'/5, '.$n.' '.$v.') '.$y?></div>
    <input type="hidden" id="currentRating" value="<?php echo $decRating?>">
	</div>
</div>
	<script>
		var currentRating=$('#currentRating').val();
		currentRating=Math.floor(currentRating);
		for(i=5; i>=0; i--)
		{
			$(".rating .star:eq("+i+")").removeClass("active");
		}
		for(i=(currentRating-1); i>=0; i--)
		{
			$(".rating .star:eq("+i+")").addClass("active");
		}
		$(".star").mouseover(function()
		{
			var d = $(this).attr("id");
			$(".rating .star").removeClass("active");
			for(i=(d-1); i>=0; i--)
			{
				$(".rating .star:eq("+i+")").addClass("active")
			}
		})
		$(".star").click(function()
		{
			var the_id = '<?php echo $id?>';
			var rating = $(this).attr("id");
			for(i=(rating-1); i>=0; i--)
			{
				$(".rating .star:eq("+i+")").addClass("active");
			}
			var data = 'rating='+rating+'&id=<?php echo $id; ?>&page=article';
			$.ajax
			({
				type: "POST", 
				data: data,
				url: "<?php echo rootpath()?>/rate.php",
				success: function(e)
				{
					$(".ajax").html(e);
				}
			});
		})
		$(".star").mouseout(function()
		{
			var currentRating=$('#currentRating').val();
			currentRating=Math.floor(currentRating);
			for(i=5; i>=0; i--)
			{
				$(".rating .star:eq("+i+")").removeClass("active");
			}
			for(i=(currentRating-1); i>=0; i--)
			{
				$(".rating .star:eq("+i+")").addClass("active");
			}
		});
	</script> 