<?php
include('config/config.php');
include('config/functions.php');
$type = $_GET['type'];
header("Content-Type: application/json");
$data = array('status' => 417);
if($type=='login'){
// define login email and pass
$email = safeinput($_POST['email']);
$pass = safeinput($_POST['password']);  
$sql = "SELECT * FROM users WHERE username='$email' AND password='$pass'";
$query = mysqli_query($dbConnect, $sql); 
if(mysqli_num_rows($query)>0){
 $fetch = mysqli_fetch_assoc($query);
 $_SESSION['logged_in'] = true; 
 $_SESSION['user_id'] = $fetch['id']; 
 header("Location: index.php");
}else{  
 header("Location: login.php");
}
}elseif($type=='logout'){
session_destroy();
header("Location: index.php");
}elseif($type=='add_transaction'){
$desc = safeinput($_POST['description']);
$total_price = safeinput($_POST['total_price']);
$date = safeinput($_POST['date']);
$paid_by = safeinput($_POST['paid_by']);
$applied_for_arr = $_POST['applied_for']; 
$applied_for = implode(',',$applied_for_arr);
$count_applied_for = count($_POST['applied_for']);
$per_person_price = $total_price/$count_applied_for; 
$timestamp = time();
$sql = "INSERT INTO transactions (description,total_price,date,timestamp,paid_by,per_person_price,applied_for) VALUES ('$desc','$total_price','$date','$timestamp','$paid_by','$per_person_price','$applied_for')";
$query = mysqli_query($dbConnect,$sql);
$transaction_id = mysqli_insert_id($dbConnect);
foreach($applied_for_arr as $data_for){
$sql = "INSERT INTO transaction_users (description,lend_by,total_price,date,timestamp,paid_by,per_person_price,applied_for,data_for,transaction_id) VALUES ('$desc',$paid_by,'$total_price','$date','$timestamp','$paid_by','$per_person_price','$applied_for',$data_for,$transaction_id)";
$query = mysqli_query($dbConnect,$sql);
}
if($query){
	header("Location: index.php?msg=Added Sucessfully");
}else{
	header("Location: index.php?msg=Some error occcurred");
}
}