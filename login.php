<?php 
header('Access-Control-Allow-Origin:*');

#Acceptance variable
$data      =@  $_POST['data'];
$username  =@  $data['username'];
$password  =@  $data['password'];

if ($username == ""||$password == ""){
	$response = array(
	'code' => 400,
	'message' => 'Parameter deletion',
	);
	echo json_encode($response);
	exit();
}

$response = array( 
'code' => 306, 
'message' => 'Invaild username or password', 
);

if (!preg_match("/^((?:(?:[a-zA-Z0-9]+\.?[A-Za-z]*)*[a-zA-Z]*)+[A-Za-z0-9]+)@((?:[a-zA-Z0-9])+\.{1}(?:[A-Za-z]{1,5})*[a-zA-Z]+)$/", $username)){
	if (!preg_match("/^[\w\-]{4,20}$/", $username)){
	$response = array( 
	'code' => 301, 
	'message' => 'Username parameter error', 
	);
	echo json_encode($response);
	exit();
	}
	$field = 'username';
}
else{
	$field = 'email';
}

#connect databases
require_once("./inc/conn.php");

if (!$conn)
{
    die('Could not connect: ' . mysql_error());
}

$sql = "select * from user where $field = '$username'";
$query = mysql_query($sql,$conn);
$row=mysql_fetch_assoc($query);
if($row){
	$pass = $row['password'];
	$name = $row['username'];
	$salt = $row['salt'];
	$token = $row['token'];

	$password = hash("sha512",$password.$salt);
		
	if ($pass === $password){
		$response = array( 
		'code' => 202, 
		'message' => 'Login success', 
		'username' => $name,
		'token' => $token,
		);
	}
}

echo json_encode($response);
?>