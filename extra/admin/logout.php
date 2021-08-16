<?php 
error_reporting(0);
session_start();
if(isset($_SESSION['admin_eap']) && isset($_SESSION['id']) && isset($_SESSION['type']) && isset($_SESSION['username']))
{
	session_unset($_SESSION['id']);
}
session_destroy();
header("location:index.php");
?>