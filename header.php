<?php
session_start();
include('config/config.php');
include('config/functions.php');
if($_SESSION['logged_in'] != true){
header("Location: login.php");
}else{
$user_details = getuser($_SESSION['user_id']);
$user_list = UsersList();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8"><link rel="manifest" href="manifest.json">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="mobile-web-app-capable" content="yes">
	<link rel="icon" sizes="192x192" href="https://i.imgur.com/dw38Xz8.png">
<link rel="icon" sizes="128x128" href="https://i.imgur.com/dw38Xz8.png">
<link rel="apple-touch-icon" sizes="128x128" href="https://i.imgur.com/dw38Xz8.png">
<link rel="apple-touch-icon-precomposed" sizes="128x128" href="https://i.imgur.com/dw38Xz8.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>Management - Admin Panel</title>
  <!-- Bootstrap core CSS-->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template-->
  <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <!-- Page level plugin CSS-->
  <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin.css" rel="stylesheet">
    <!-- Bootstrap core JavaScript-->
    <script src="https://cdn.jsdelivr.net/jquery/3.2.1/jquery.min.js"></script>
</head>

<body class="fixed-nav sticky-footer bg-dark" id="page-top">
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" id="mainNav">
    <a class="navbar-brand" href="index.html">Welcome <?php echo $user_details['name'];?></a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link" href="request.php?type=logout">
            <i class="fa fa-fw fa-dashboard"></i>
            <span class="nav-link-text">Log out</span>
          </a>
        </li>
		<li class="nav-item" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link" href="index.php">
            <i class="fa fa-fw fa-dashboard"></i>
            <span class="nav-link-text">Dashboard</span>
          </a>
        </li> 
        <li class="nav-item" data-toggle="tooltip" data-placement="right" title="Search">
          <a class="nav-link" href="search.php">
            <i class="fa fa-fw fa-search"></i>
            <span class="nav-link-text">Search</span>
          </a>
        </li> 
      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul> </div>
  </nav>
  