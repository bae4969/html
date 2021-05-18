<?php

function getDustData(){
    include '/var/www/phpExe/sqlcon.php';

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlWeatherDb );
    $sql_query = 'select * from dust';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $data = array();
    while($row = mysqli_fetch_array($result))
        $data[] = $row;

    return $data;
}

?>
