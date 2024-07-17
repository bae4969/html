<?php

include '/var/www/php/sql_connection_info.php';

$conn = mysqli_connect($sqlAddr, $sqlId, $sqlPw);
$conn->set_charset("utf8mb4");
$sql_query = 'SELECT I.coin_code, I.coin_name_kr ';
$sql_query .= 'FROM Bithumb.coin_last_ws_query AS Q ';
$sql_query .= 'JOIN Bithumb.coin_info AS I ';
$sql_query .= 'ON Q.coin_code = I.coin_code';
$result = mysqli_query($conn, $sql_query);
mysqli_close($conn);

$coin_list = array();
while ($row = mysqli_fetch_assoc($result))
	$coin_list[] = $row;

echo json_encode($coin_list, JSON_UNESCAPED_UNICODE);
