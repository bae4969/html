<?php

include '/var/www/phpExe/sqlcon.php';

$conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlCovidDb );
$sql_query = 'select * from total';
$result = mysqli_query($conn, $sql_query);
mysqli_close($conn);

$data = array();
while($row = mysqli_fetch_assoc($result))
    $data[] = $row;

echo json_encode(array('state'=>000, 'data'=>$data));

?>
