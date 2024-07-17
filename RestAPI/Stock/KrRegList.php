<?php

include '/var/www/php/sql_connection_info.php';

$conn = mysqli_connect($sqlAddr, $sqlId, $sqlPw);
$conn->set_charset("utf8mb4");
$sql_query = 'SELECT I.stock_code, I.stock_name_kr ';
$sql_query .= 'FROM KoreaInvest.stock_last_ws_query AS Q ';
$sql_query .= 'JOIN KoreaInvest.stock_info AS I ';
$sql_query .= 'ON Q.stock_code = I.stock_code ';
$sql_query .= 'WHERE I.stock_market="KOSPI" OR I.stock_market="KOSDAQ"';
$result = mysqli_query($conn, $sql_query);
mysqli_close($conn);

$stock_list = array();
while ($row = mysqli_fetch_assoc($result))
	$stock_list[] = $row;

echo json_encode($stock_list, JSON_UNESCAPED_UNICODE);
