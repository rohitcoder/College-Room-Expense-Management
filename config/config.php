<?php
$month = "February 2020";
ini_set('error_reporting', E_ALL & ~E_NOTICE  & ~E_WARNING & ~E_STRICT & ~E_DEPRECATED);
$sql_host = "localhost";
$sql_name = "room_expense";
$sql_user = "rohitcoder";
$sql_pass = "9534607283@Rohit"; 
// Connect to SQL Server
$dbConnect = mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_name);
session_start();
// Check connection
if (mysqli_connect_errno($dbConnect)) {
    exit(mysqli_connect_error());
}