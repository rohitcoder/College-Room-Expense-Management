<?php
error_reporting(0);
include '../config/config.php';
require "../include/cache/phpfastcache.php";
phpFastCache::setup("storage","auto");
include '../common/functions.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index, follow" />

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="<?php echo(rootpath()); ?>/images/favicon/<?php echo(favicon() . "?" . time()); ?>"/>
<link href='http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,600italic,600' rel='stylesheet' type='text/css'>
<!-- Bootstrap CSS -->
<link href="style/css/bootstrap.min.css" rel="stylesheet">
<!-- Animate css -->
<link href="style/css/animate.min.css" rel="stylesheet">
<!-- Bootstrap toggable -->
<link href="style/css/bootstrap-switch.css" rel="stylesheet">
<!-- jQuery UI -->
<link href="style/css/jquery-ui.css" rel="stylesheet">
<!-- Font awesome CSS -->
<link href="style/css/font-awesome/css/font-awesome.css" rel="stylesheet">
<!-- Accordion CSS -->
<link rel="stylesheet" href="style/ui/themes/base/jquery.ui.all.css">
<!-- Custom CSS -->
<link href="style/css/style.css" rel="stylesheet">
<!-- Javascript files -->
<!-- jQuery -->
<script type="text/javascript" src="style/js/jquery.1.7.1.min.js"></script>
<script src="style/js/jquery.js"></script>
<!-- jQuery UI -->
<script src="style/js/jquery-ui-1.10.2.custom.min.js"></script>
<!-- Stats -->
<script src="style/js/stats.min.js"></script>
<!-- Date and Time picker -->
<script src="style/js/bootstrap-datetimepicker.min.js"></script>  
<!-- HTML5 Support for IE -->
<script src="style/js/html5shiv.js"></script>
<!-- Custom JS -->
<script src="style/js/custom.js"></script>

