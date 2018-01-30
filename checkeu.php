<?php 
header('Access-Control-Allow-Origin:*');

#Acceptance variable
$data      =@  $_POST['data'];
$parameter  =@  $data['parameter'];

if ($parameter == ""){
	$response = array(
	'code' => 400,
	'message' => 'Parameter deletion',
	);
	echo json_encode($response);
	exit();
}

if (!preg_match("/^((?:(?:[a-zA-Z0-9]+\.?[A-Za-z]*)*[a-zA-Z]*)+[A-Za-z0-9]+)@((?:[a-zA-Z0-9])+\.{1}(?:[A-Za-z]{1,5})*[a-zA-Z]+)$/", $parameter)){
	if (!preg_match("/^[\w\-]{4,20}$/", $parameter)){
	$response = array( 
	'code' => 301, 
	'message' => 'parameter error', 
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

$sql = "select $field from user where $field = '$parameter'";
$query = mysql_query($sql,$conn);
$row=mysql_fetch_assoc($query);

if(!$row){
	$response = array(
	'code' => 210,
	'message' => 'Parameter availability',
	);
}
else{
	if($field == "username"){
		$response = array(
		'code' => 304,
		'message' => 'username duplication',
		);
		echo json_encode($response);
		exit();
	}
	if($field == "email"){
		$response = array(
		'code' => 305,
		'message' => 'email duplication',
		);
		echo json_encode($response);
		exit();
	}
}

echo json_encode($response);
?>