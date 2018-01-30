<?php 
header('Access-Control-Allow-Origin:*');

#Acceptance variable
$data      =@  $_POST['data'];
$random    =@  $data['random'];
$password  =@  $data['password'];

if ($random == ""||$password == ""){
	$response = array(
	'code' => 400,
	'message' => 'Parameter deletion',
	);
	echo json_encode($response);
	exit();
}

if( !preg_match("/^([a-z0-9]){128}$/", $password)){
	$response = array(
		'code' => 302,
		'message' => 'Password parameter error',
	);
	echo json_encode($response);
	exit();
}

$response = array( 
'code' => 308, 
'message' => 'Parameter can\'t not be find', 
);

#connect databases
require_once("./inc/conn.php");

if (!$conn)
{
    die('Could not connect: ' . mysql_error());
}

$sql = "select * from email where random = '$random'";
$query = mysql_query($sql,$conn);
$row=mysql_fetch_assoc($query);

if($row){
	$name = $row['username'];
	$row_time = $row['time'];
	date_default_timezone_set('PRC');
	$time = time()-strtotime($row_time);
	$yourhour = (int)(($time%(3600*24))/(3600));

	if($yourhour>3){
		$response = array(
		'code' => 403,
		'message' => 'Link failure',
		);
		echo json_encode($response);
		exit();
	}

	$sql_in = "select * from user where username = '$name'";
	$query = mysql_query($sql_in,$conn);
	$row_in=mysql_fetch_assoc($query);
	if($row_in){
		$salt = $row_in['salt'];
		$password = hash("sha512",$password.$salt);
		$sql_update = "update user set password = '$password' where username ='$name'";
		if(mysql_query($sql_update,$conn)){
			$response = array(
			'code' => 221,
			'message' => 'The password has change',
			);
		}
		else{
			$response = array(
			'code' => 404,
			'message' => 'Change the password failure',
			);
		}
	}
}

echo json_encode($response);
?>

