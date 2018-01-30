<?php 
header('Access-Control-Allow-Origin:*');

#Acceptance variable
$data      =@  $_POST['data'];
$random    =@  $data['random'];

if ($random == ""){
	$response = array(
	'code' => 400,
	'message' => 'Parameter deletion',
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
	$response = array(
	'code' => 230,
	'message' => 'Link effectiveness',
	);
}

echo json_encode($response);
?>