<?php 
header('Access-Control-Allow-Origin:*');

#Acceptance variable
$data      =@  $_POST['data'];
$username  =@  $data['username'];
$password  =@  $data['password'];
$email     =@  $data['email'];
$salt      =@  md5(uniqid(rand(), true));
$token     =@  md5($username+$salt);

if ($email == ""||$username == ""||$password == ""){
	$response = array(
	'code' => 400,
	'message' => 'Parameter deletion',
	);
	echo json_encode($response);
	exit();
}

if (!preg_match("/^[\w\-]{4,20}$/", $username)){
	$response = array( 
	'code' => 301, 
	'message' => 'Username parameter error', 
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

if (!preg_match("/^((?:(?:[a-zA-Z0-9]+\.?[A-Za-z]*)*[a-zA-Z]*)+[A-Za-z0-9]+)@((?:[a-zA-Z0-9])+\.{1}(?:[A-Za-z]{1,5})*[a-zA-Z]+)$/", $email)){
	$response = array( 
	'code' => 303, 
	'message' => 'Email parameter error', 
	);
	echo json_encode($response);
	exit();
}

$password = hash("sha512",$password.$salt);

#connect databases
require_once("./inc/conn.php");

if (!$conn)
{
    die('Could not connect: ' . mysql_error());
}

$sql = "select username from user where username = '$username'";
$query = mysql_query($sql,$conn);
$row=mysql_fetch_assoc($query);
if($row){
	$response = array(
	'code' => 304,
	'message' => 'Username duplication',
	);
	echo json_encode($response);
	exit();
}

$sql = "select username from user where email = '$email'";
$query = mysql_query($sql,$conn);
$row=mysql_fetch_assoc($query);
if($row){
	$response = array(
	'code' => 305,
	'message' => 'email duplication',
	);
	echo json_encode($response);
	exit();
}

$sql = "insert into user (username,password,email,salt,token) values ('$username','$password','$email','$salt','$token')";
if(mysql_query($sql,$conn)){
	$response = array( 
	'code' => 201, 
	'message' => 'Sign up success', 
	);
}
else{
	$response = array( 
	'code' => 401, 
	'message' => 'Sign up fail', 
	);
}

echo json_encode($response);
?>