<?php

include '/var/www/php/sql_connection_info.php';

$conn = mysqli_connect($sqlAddr, $sqlId, $sqlPw);
$conn->set_charset("utf8mb4");
$sql_query = 'SELECT coin_code, coin_name_kr FROM Bithumb.coin_info';
$result = mysqli_query($conn, $sql_query);
mysqli_close($conn);

$coin_list = array();
while ($row = mysqli_fetch_assoc($result))
	$coin_list[] = $row;

echo json_encode($coin_list, JSON_UNESCAPED_UNICODE);
