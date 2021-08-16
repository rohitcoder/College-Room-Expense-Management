<?php 
header('Content-Type: text/html; charset=utf-8');
include 'config/config.php';
mysql_query("SET NAMES 'utf8'");
class DB
{
	public function parentCategories($permalink)
	{
	$count=mysql_num_rows(mysql_query("SELECT * FROM `categories` WHERE `parentId`=0 AND permalink='$permalink'"));
	if($count>0)
		return true;
	else
		return false;
	}
	public function isValidCategory($permalink)
	{ 
	$qry = mysql_query("SELECT * FROM `categories` WHERE `permalink`='$permalink'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	return true;
	else
	return false;
	}
	public function userNameExists($val)
	{
	$qry = mysql_query("SELECT * FROM `user` WHERE `username` ='$val'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	return true;
	else
	return false;
	}
	public function childCategories()
	{
	$subCategories=array();
	$query=mysql_query("SELECT * FROM `categories` WHERE `parentId`!=0");
	while($row=mysql_fetch_array($query))
	{
		array_push($subCategories,$row['permalink']);
	}
	return $subCategories;
	}
	public function isValidArticleCategory($permalink)
	{ 
	$qry = mysql_query("SELECT * FROM `articleCategories` WHERE `permalink`='$permalink'");
	$num_rows = mysql_num_rows($qry); 
	if ($num_rows > 0)
	return true;
	else
	return false;
	}
	public function articleName(){
	$fetch=mysql_fetch_array(mysql_query("SELECT `name` FROM `articleSettings`"));
	return $fetch['name'];
	}
	public function isValidLanguage($language){
	$count=mysql_num_rows(mysql_query("SELECT * FROM `languages` WHERE `languageName`='$language'"));
	if($count>0)
	return true;
	else
	return false;
	}
}
class App {
	protected $config=array(), $action="", $do="", $id="", $sandbox = FALSE;
	protected $actions = array("search","tags","page","contact","go","favourite");
	public function run()
	{
	
		if(isset($_GET["a"]) && !empty($_GET["a"]))
		{
			$var=explode("/", $_GET["a"]);
			if(count($var) > 5 && $var[0]!='rss')
			return $this->_404();
			$this->action = $var[0];
			if(isset($var[1]) && !empty($var[1]))
			if(isset($var[2]) && !empty($var[2]))
			$this->id = $var[2];
			if(($var[0]=='tags' && $var[1]=="") || (DB::parentCategories($var[0]) && $var[1]=="") || ($var[0]=='category' && $var[1]=="" && $var[2]=="") || ($var[0]=='search' && ($var[1]=="")) || ($var[0]=='rss' && (($var[1]=="category" && $var[2]=="") || ($var[1]=="tags" && $var[2]==""))) || ($var[0]==DB::articleName()."Category" && $var[1]=="")) {
			$this->_404();
			}
			else if($var[0]=='home' || $var[0]=='search' || $var[0]=='tags' || $var[0]=='contact' || $var[0]=='page' || $var[0]=='go' || $var[0]=='favourite')
			{
			if(in_array($var[0],$this->actions))
			{
				return $this->{$var[0]}($var[1],$var[2],$var[3]);
			}
			else if(!in_array($var[0],$this->actions))
			{
				return $this->home($var[1]);
			}
			}
			else if($var[0]=='category' && DB::isValidCategory($var[2]))
			{
				return $this->subCategory($var[1],$var[2],$var[3],$var[4]);
			}
			else if($var[0]=='category' && DB::isValidCategory($var[1]))
			{
				return $this->mainCategory($var[1],$var[2],$var[3]);
			}
			else if($var[0]=='rss' && count($var)==5)
			{
				return $this->rssSub($var[1],$var[2],$var[3],$var[4]);
			}
			else if($var[0]=='rss' && count($var)<=4)
			{
				return $this->rssMain($var[1],$var[2],$var[3]);
			}
			else if($var[0]=='show'.DB::articleName() && DB::isValidArticleCategory($var[1]) && $var[2]!="")
			{
				return $this->showArticle($var[1],$var[2]);
			}
			else if(DB::parentCategories($var[0]) && $var[1]!="")
			{
				return $this->product($var[1],$var[2]);
			}
			else if($var[0]==DB::articleName().'category')
			{
				return $this->articleCategory($var[1],$var[2],$var[3]);
			}
			else if($var[0]==DB::articleName())
			{
				return $this->article($var[1],$var[2],$var[3]);
			}
			 else if($var[0]=='index.php' || $var[0]=='index')
			{
				return $this->home($var[1],$var[2],$var[3]);
			}
		     else {
			$this->_404();
			}
			return $this->_404();
		   
		}
		else 
		{
			return $this->home($var[1]);
		}
	}	
	protected function home($sortBy)
	{   
	    if($sortBy !="" && ($sortBy=='newest' || $sortBy=='hotest' || $sortBy=='lprice' || $sortBy=='hprice')) {
		$this->sortBy=$sortBy;
		}
		include(ROOT."/home.php");	
	}
	protected function go($id,$noNeed,$noNeed)
	{
		$this->id=$id;
		include(ROOT."/go.php");	
	}
	protected function subCategory($parentCategory,$childCategory,$data,$page)
	{
	    if($data !="" && ($data=='newest' || $data=='hotest' || $data=='hprice' || $data=='lprice') && $page=="") {
		$this->SORTby=$data;
		} else if(is_numeric($data) && $page==""){
		$this->Page=$data;
		} else if($data !="" && ($data=='newest' || $data=='hotest' || $data=='hprice' || $data=='lprice') && $page!="") {
		$this->SORTby=$data;
		$this->Page=$page;
		}
		$this->categoryName=$childCategory;
		include(ROOT."/category.php");
	}
	protected function mainCategory($categoryname,$data,$page)
	{
	    if($data !="" && ($data=='newest' || $data=='hotest' || $data=='hprice' || $data=='lprice') && $page=="") {
		$this->SORTby=$data;
		} else if(is_numeric($data) && $page==""){
		$this->Page=$data;
		} else if($data !="" && ($data=='newest' || $data=='hotest' || $data=='hprice' || $data=='lprice') && $page!="") {
		$this->SORTby=$data;
		$this->Page=$page;
		}
		$this->categoryName=$categoryname;
		include(ROOT."/category.php");
	}
	protected function favourite($page)
	{
		$this->Page=$page;
		include(ROOT."/favourite.php");
	}
	protected function articleCategory($categoryname,$data,$page)
	{   
	    if(($data=='title' || $data=='date' || $data=='rating' || $data=='views') && $page=="") {
		$this->SORTby=$data;
		} else if(is_numeric($data) && $page==""){
		$this->Page=$data;
		} else if(($data=='asc' || $data=='desc') && $page==""){
		$this->sorTORDER=$data;
		}
		else if(($data=='title' || $data=='date' || $data=='rating' || $data=='views') && $page!="") {
		$this->SORTby=$data;
		$this->Page=$page;
		}
		 else if(($data=='asc' || $data=='desc') && $page!=""){
		$this->sorTORDER=$data;
		$this->Page=$page;
		}
		if(DB::isValidArticleCategory($categoryname))
		$this->categoryName=$categoryname;
		include(ROOT."/reviews_category.php");
	}
	protected function article($data1,$data2,$data3)
	{
		if($data1!=""){
			if(is_numeric($data1))
				$this->Page=$data1;
			else if(DB::userNameExists($data1) && is_numeric($data2)){
				$this->user=$data1;
				$this->Page=$data2;
			}
			else if(DB::userNameExists($data1) && ($data2=='title' || $data2=='date' || $data2=='rating' || $data2=='views')){
				$this->user=$data1;
				$this->sortBy=$data2;
			}
			else if(($data1=='title' || $data1=='date' || $data1=='rating' || $data1=='views') && (is_numeric($data2))){
				$this->sortBy=$data1;
				$this->Page=$data2;
			}
			else if(DB::userNameExists($data1) && $data2==''){
				$this->user=$data1;
			}
			else if(($data1=='asc' || $data1=='desc') && is_numeric($data2)){
				$this->sortOrder=$data1;
				$this->Page=$data2;
			}
			else if(($data1=='asc' || $data1=='desc') && $data2==''){
				$this->sortOrder=$data1;
			}
			else if($data1=='title' || $data1=='date' || $data1=='rating' || $data1=='views'){
				$this->sortBy=$data1;
			}
			
		}
		include(ROOT."/reviews.php");
	}
	protected function product($mainCategory,$product)
	{  
		if($mainCategory!="" && $product=="")
			$this->Product=$mainCategory;
		else
			$this->Product=$product;
		include(ROOT."/product.php");
	}
	protected function showArticle($categoryPermalink,$articleTitle)
	{  
			$this->Category=$categoryPermalink;
			$this->Article=$articleTitle;
		include(ROOT."/reviews-show.php");
	}
	protected function search($search,$data,$page)
	{   
	    if($data !="" && ($data=='relevence' || $data=='newest' || $data=='hotest' || $data=='hprice' || $data=='lprice') && $page=="") {
		$this->SoRTby=$data;
		} else if(is_numeric($data) && $page==""){
		$this->Page=$data;
		} else if($data !="" && ($data=='relevence' || $data=='newest' || $data=='hotest' || $data=='hprice' || $data=='lprice') && $page!="") {
		$this->SoRTby=$data;
		$this->Page=$page;
		}
		$this->Search=$search;
		include(ROOT."/search.php");
	}
	protected function tags($tagname,$data,$page)
	{   
	    if($data !="" && ($data=='newest' || $data=='hotest' || $data=='hprice' || $data=='lprice') && $page=="") {
		$this->SortbY=$data;
		} else if(is_numeric($data) && $page==""){
		$this->Page=$data;
		} else if($data !="" && ($data=='newest' || $data=='hotest' || $data=='hprice' || $data=='lprice') && $page!="") {
		$this->SortbY=$data;
		$this->Page=$page;
		}
		$this->tagName=$tagname;
		include(ROOT."/tags.php");
	}
	protected function rssSub($type,$data1,$data2,$data3)
	{ 
		if($type=='recent' || $type=='tags' || $type=='category' || $type=='top')
		$this->Type=$type;
		if($data2!="" && DB::isValidLanguage($data3)){
		$this->data=$data2;
		$this->language=$data3;
		} else if(DB::isValidLanguage($data2) && $data3=="")
		$this->language=$data2;
		include(ROOT."/rss.php");
	}
	protected function rssMain($type,$data1,$data2)
	{ 
		if($type=='recent' || $type=='tags' || $type=='category' || $type=='top')
		$this->Type=$type;
		if($data1!="" && DB::isValidLanguage($data2)){
		$this->data=$data1;
		$this->language=$data2;
		} else if(DB::isValidLanguage($data1) && $data2=="")
		$this->language=$data1;
		include(ROOT."/rss.php");
	}
	protected function page($permalink,$noneed,$noneed)
	{  
		$this->Permalink=$permalink;
		include(ROOT."/page.php");
	}
	protected function contact($noneed,$noneed,$noneed)
	{  
		include(ROOT."/contact.php");
	}
	protected function _404()
	{
		include(ROOT."/404.php");		
	}
}