<?php
function getAllTransactions(){
	global $dbConnect, $month;	
	$mode_value= $month;
	if(empty($mode_value) && !isset($mode_value)){
	$sql = "SELECT * FROM transactions ORDER BY id DESC";
    }else{
		$from = strtotime('first day of '. $mode_value);
    	$to = strtotime('+1 day', strtotime("last day of ".$mode_value));
    	$sql = "SELECT * FROM transactions WHERE timestamp BETWEEN $from AND $to ORDER BY id DESC";
    } 
	$query = mysqli_query($dbConnect,$sql);
	while($data = mysqli_fetch_assoc($query)){
		$fetched_data[] = $data;
	}
	return $fetched_data;
}
function ShowDebitBalance($user_id,$session_user_id){
	global $dbConnect, $month;
	$mode_value=$month;
	if(empty($mode_value) && !isset($mode_value)){
	$sql = "SELECT SUM(per_person_price) as total_price FROM transaction_users WHERE cleared_balance='0' AND lend_by=$session_user_id AND data_for=$user_id";
	}else{	
		$from = strtotime('first day of '. $mode_value);
    	$to = strtotime('+1 day', strtotime("last day of ".$mode_value));
		$sql = "SELECT SUM(per_person_price) as total_price FROM transaction_users WHERE cleared_balance='0' AND lend_by=$session_user_id AND data_for=$user_id AND timestamp BETWEEN $from AND $to";
	}
	$query = mysqli_query($dbConnect, $sql);
	if(mysqli_num_rows($query)){
		$data = mysqli_fetch_assoc($query);
	}
	if(empty($data['total_price'])){
		$data['total_price'] = '0';
	}
	return $data['total_price'];
}
function safeinput($string) {
    global $dbConnect;
    $string = trim($string);
	$string = mysqli_real_escape_string($dbConnect, $string);
    $string = htmlspecialchars($string, ENT_QUOTES);
    $string = str_replace('\\r\\n', '<br>',$string);
    $string = str_replace('\\r', '<br>',$string);
    $string = str_replace('\\n\\n', '<br>',$string);
    $string = str_replace('\\n', '<br>',$string);
    $string = str_replace('\\n', '<br>',$string);
    $string = stripslashes($string);
    $string = str_replace('&amp;#', '&#',$string);
    return $string;
} 
function UsersList(){
global $dbConnect;
$sql = "SELECT * FROM users";
$query = mysqli_query($dbConnect,$sql);
$data = array();
$fetched_data = array();
while($data = mysqli_fetch_assoc($query)){
	$fetched_data[] = $data;
}  
return $fetched_data;
}
function CaluclateBalance($user_id,$mode){
	global $dbConnect,$month;
	$mode_value=$month;
	if(empty($mode_value) && !isset($mode_value)){
	$sql = "SELECT SUM(per_person_price) as total_price FROM transaction_users WHERE cleared_balance='0' AND data_for=$user_id";
	}else{
    $from = strtotime('first day of '. $mode_value);
    $to = strtotime('+1 day', strtotime("last day of ".$mode_value));
	$sql = "SELECT SUM(per_person_price) as total_price FROM transaction_users WHERE cleared_balance='0' AND data_for=$user_id AND timestamp BETWEEN $from AND $to";
	}
	$query = mysqli_query($dbConnect, $sql);
	if(mysqli_num_rows($query)){
		$data = mysqli_fetch_assoc($query);
	}
	if(empty($data['total_price'])){
		$data['total_price'] = '0';
	}
	return $data['total_price'];
}



function ShowCreditBalance($user_id,$session_user_id){
	global $dbConnect,$month;
	$mode_value=$month;
	if(empty($mode_value) && !isset($mode_value)){
	$sql = "SELECT SUM(per_person_price) as total_price FROM transaction_users WHERE cleared_balance='0' AND lend_by=$user_id AND data_for=$session_user_id";
	}else{
	$from = strtotime('first day of '. $mode_value);
    $to = strtotime('+1 day', strtotime("last day of ".$mode_value));
	$sql = "SELECT SUM(per_person_price) as total_price FROM transaction_users WHERE cleared_balance='0' AND lend_by=$user_id AND data_for=$session_user_id AND timestamp BETWEEN $from AND $to ";
	}
	$query = mysqli_query($dbConnect, $sql);
	if(mysqli_num_rows($query)){
		$data = mysqli_fetch_assoc($query);
	}
	if(empty($data['total_price'])){
		$data['total_price'] = '0';
	}
	return $data['total_price'];
}
function TotalPaidByUser($user_id,$mode){ 
	global $dbConnect;
	$from = strtotime('first day of '. date('F Y'));
    $to = strtotime('+1 day', strtotime("last day of ".$mode_value));
	if($mode=='monthly'){
	$sql = "SELECT SUM(total_price) as price FROM transactions WHERE timestamp BETWEEN $from AND $to AND paid_by=$user_id";
	}elseif($mode=='all'){
	$sql = "SELECT SUM(total_price) as price FROM transactions WHERE paid_by=$user_id"; 
	}
	$data = array();
	$query = mysqli_query($dbConnect,$sql);
	if(mysqli_num_rows($query)){
		$data = mysqli_fetch_assoc($query);
	}
	if(empty($data['price'])){
		$data['price'] = '0';
	}
	return $data['price'];
}
function getuser($user_id){
  global $dbConnect;
  $sql = "SELECT * FROM users WHERE id=$user_id";
  $query = mysqli_query($dbConnect, $sql);
  if(mysqli_num_rows($query) > 0){
    $data = mysqli_fetch_assoc($query);
  }
  return $data;
}