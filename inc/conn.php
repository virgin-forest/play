<?php
date_default_timezone_set("UTC");
$conn=mysql_connect("localhost","root","root");
mysql_select_db("play",$conn);
mysql_query("set names utf8");
?>