<?php

include '/var/www/php/sql_connection_info.php';

$conn = mysqli_connect($sqlAddr, $sqlId, $sqlPw);
$conn->set_charset("utf8mb4");
$sql_query = 'SELECT stock_code, stock_name_kr FROM KoreaInvest.stock_info WHERE stock_market="NYSE" OR stock_market="NASDAQ" OR stock_market="AMEX"';
if($_GET["count"])
	$sql_query .= $_GET["count"];
$result = mysqli_query($conn, $sql_query);
mysqli_close($conn);

$stock_list = array();
while ($row = mysqli_fetch_assoc($result))
	$stock_list[] = $row;

echo json_encode($stock_list, JSON_UNESCAPED_UNICODE);
