<script>
$(".remove-image").click(function () 
{
	$(this).closest('li').remove();
	var fileVal=document.getElementById("myfile");
	var sr=$(this).children('img').attr('src');
	if(typeof(sr) == "undefined"){
		sr=this.id;
	}
	$.post("processupload.php",{ sr:sr},function(ajaxresult){
	});
});
function total_img() 
{
	var imgFile=$('#imgFile').val();
	imgFile=Number(imgFile)-1;
	if(imgFile==0) 
	{
		$('#myfile').val('');
		$('#imgFile').val('0');
	} 
	else 
	{
		$('#imgFile').val(imgFile);
	}
}
</script>
<?php
error_reporting(0);
include '../config/config.php';
include '../common/functions.php';
if (!isset($_SESSION))
session_start();
if(!isset($_SESSION['addfilenames']))
{ 
	$_SESSION['addfilenames']=array();
}
if(!isset($_SESSION['newfilenames']))
{ 
	$_SESSION['newfilenames']=array();
}
if(!isset($_SESSION['removeImgs']))
{ 
	$_SESSION['removeImgs']=array();
}

//Sort Add Page

if(isset($_POST['addPageSort'])){
unset($_SESSION['addfilenames']);
$_SESSION['addfilenames']=array();
$array	= $_POST['arrayorder'];
if ($_POST['addPageSort'] == "sortMe")
{
	foreach ($array as $idval) 
	{
		array_push($_SESSION['addfilenames'],$idval);	
	}
}
}

//Sort Edit Page
if(isset($_POST['editPageSort'])){
unset($_SESSION['filenames']);
$_SESSION['filenames']=array();
$array	= $_POST['arrayorder'];
if ($_POST['editPageSort'] == "sortMe")
{
	foreach ($array as $idval) 
	{
		array_push($_SESSION['filenames'],$idval);	
	}
}
}
if(isset($_POST['unlinkExtraInAddPage'])) 
{
	foreach($_SESSION['addfilenames'] as $delimages) 
	{
		if(($key = array_search($delimages, $_SESSION['addfilenames'])) !== false) 
		{
			unset($_SESSION['addfilenames'][$key]);
			unlink('../images/productImages/thumbnailsBackUp/'.$delimages);
			unlink('../images/productImages/thumb1/'.$delimages);
			unlink('../images/productImages/thumb2/'.$delimages);
			unlink('../images/productImages/thumb3/'.$delimages);
		}
	}
	if($_SESSION['image'] !="") 
	{
		unlink('../images/productImages/'.$_SESSION['image']);
		unlink('../images/productImages/_'.$_SESSION['image']);
		unlink('../images/productImages/featuredImagesBackUp/_'.$_SESSION['image']);
		unlink('../images/productImages/'.str_replace('-img','',$_SESSION['image']));
		unset($_SESSION['image']);
	}
}
if(isset($_POST['sr'])) 
{
	$link=$_POST['sr'];
	$data=strrev($link);
	$data=explode('/',$data);
	$data=$data[0];
	$imgname=strrev($data);
	if(($key = array_search($imgname, $_SESSION['addfilenames'])) !== false) 
	{
		$_SESSION['addfilenames'][$key];
		unset($_SESSION['addfilenames'][$key]);
	}
	unlink('../images/productImages/thumbnailsBackUp/'.$imgname);
	unlink('../images/productImages/thumb1/'.$imgname);
	unlink('../images/productImages/thumb2/'.$imgname);
	unlink('../images/productImages/thumb3/'.$imgname);
	if(!in_array($imgname,$_SESSION['removeImgs'])) 
	{
		array_push($_SESSION['removeImgs'],$imgname);
	}
	$_SESSION['addfilenames']=array_values($_SESSION['addfilenames']);
}
if(isset($_POST['editsr'])) 
{
	$link=$_POST['editsr'];
	$data=strrev($link);
	$data=explode('/',$data);
	$data=$data[0];
	$imgname=strrev($data);
	if(!in_array($imgname,$_SESSION['removeImgs'])) 
	{
		array_push($_SESSION['removeImgs'],$imgname);
	}
}
if(isset($_POST['featuredImage'])) {
$featuredImage=$_POST['featuredImage'];
unlink('../images/productImages/'.$featuredImage);
unlink('../images/productImages/_'.$featuredImage);
unlink('../images/productImages/'.str_replace('-img','',$featuredImage));
unlink('../images/productImages/featuredImagesBackUp/_'.$featuredImage);
foreach($_SESSION['newfilenames'] as $newfilenames){
unlink('../images/productImages/thumbnailsBackUp/'.$newfilenames);
unlink('../images/productImages/thumb1/'.$newfilenames);	
unlink('../images/productImages/thumb2/'.$newfilenames);
unlink('../images/productImages/thumb3/'.$newfilenames);
}
unset($_SESSION['newfilenames']);
}
error_reporting(0);

$change="";
$abc="";


 define ("MAX_SIZE","2");
 function getExtension($str) {
 $i = strrpos($str,".");
 if (!$i) { return ""; }
 $l = strlen($str) - $i;
 $ext = substr($str,$i+1,$l);
 return $ext;
 }
 $errors=0;
if(isset($_FILES['myfile']))
{
$image =$_FILES["myfile"]["name"];
$uploadedfile = $_FILES['myfile']['tmp_name'];
     
 
if ($image) 
{

	$filename = stripslashes($_FILES['myfile']['name']);

	$extension = getExtension($filename);
	$extension = strtolower($extension);
	$size=filesize($_FILES['myfile']['tmp_name']);
		
if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) 
{

	echo '<div class="thumbnail thumb"><a class="remove-image" onclick="total_img()"><i class="fa fa-remove"></i></a>Invalid Extension</div>';
	$errors=1;
}
else
{

if($extension=="jpg" || $extension=="jpeg" )
{
$uploadedfile = $_FILES['myfile']['tmp_name'];
$src = imagecreatefromjpeg($uploadedfile);

}
else if($extension=="png")
{
$uploadedfile = $_FILES['myfile']['tmp_name'];
$src = imagecreatefrompng($uploadedfile);

}
else 
{
$src = imagecreatefromgif($uploadedfile);
}

echo $scr;

list($width,$height)=getimagesize($uploadedfile);

if($height < largeThumbnailHeight() || $width < largeThumbnailWidth())
{
	echo '<li id="arrayorder" class="col-sm-2 col-xs-12"><div class="thumbnail thumb"><a class="remove-image" onclick="total_img()"><i class="fa fa-remove"></i></a></br> Image Size Less Then Minimum Size Remove It And Try Another</div></li>';
} 
else
{
	$newwidth=smallThumbnailWidth();
	$newheight=($height/$width)*$newwidth;
	$tmp1=imagecreatetruecolor($newwidth,$newheight);


	$newwidth1=$width;
	$newheight1=mediumThumbnailHeight();
	$tmp2=imagecreatetruecolor($newwidth1,$newheight1);
	$orgwidth=$width;
	$orgheight=$height;
	$tmp3=imagecreatetruecolor($orgwidth,$orgheight);
	
	$tmp4=imagecreatetruecolor($orgwidth,$orgheight);

	imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth,$newheight,$width,$height);

	imagecopyresampled($tmp2,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);

	imagecopyresampled($tmp3,$src,0,0,0,0,$orgwidth,$orgheight,$width,$height);
	
	imagecopyresampled($tmp4,$src,0,0,0,0,$orgwidth,$orgheight,$width,$height);

	$time=time().uniqid();
	$filename1 = "../images/productImages/thumb1/". $time.'.'.$extension;

	$filename2 = "../images/productImages/thumb2/". $time.'.'.$extension;
	
	$filename3 = "../images/productImages/thumb3/". $time.'.'.$extension;
	
	$filename4 = "../images/productImages/thumbnailsBackUp/". $time.'.'.$extension;



	imagejpeg($tmp1,$filename1,100);

	imagejpeg($tmp2,$filename2,100);

	imagejpeg($tmp3,$filename3,100);
	
	imagejpeg($tmp4,$filename4,100);

	$NewImageName=$time.'.'.$extension;
	array_push($_SESSION['filenames'],$NewImageName);
	array_push($_SESSION['newfilenames'],$NewImageName);
	array_push($_SESSION['addfilenames'],$NewImageName);

	echo '<li id="arrayorder_'.$time.'.'.$extension.'" class="col-sm-2 col-xs-12"><div class="thumbnail thumb"><a id="'.$time.'.'.$extension.'" class="remove-image" onclick="total_img()"><i class="fa fa-remove"></i></a><img src="../images/productImages/thumb1/'.$time.'.'.$extension.'" alt="Thumbnail" class="img-responsive"></div></li>';

	imagedestroy($src);
	imagedestroy($tmp1);
	imagedestroy($tmp2);
	imagedestroy($tmp3);
	imagedestroy($tmp4);
}
}}
}
?>