<?php
ob_start();
if(!isset($_SESSION))
	session_start();
include 'config/config.php';
include 'common/functions.php';
unset($_SESSION['store_uid']);
unset($_SESSION['store_username']);
unset($_SESSION['store_email']);
unset($_SESSION['21d72e65f75d499adb5d2b9f17fcf352']);
//session_destroy();
header('Location: ' .rootpath());
?>