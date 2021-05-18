<?php

function getGeoData(){
    include '/var/www/phpExe/sqlcon.php';

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlWeatherDb );
    $sql_query = 'select * from geo';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $data = array();
    while($row = mysqli_fetch_array($result))
        $data[] = $row;

    return $data;
}

function getWeatherNowData(){
    include '/var/www/phpExe/sqlcon.php';

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlWeatherDb );
    $sql_query = 'select * from now';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $data = array();
    while($row = mysqli_fetch_array($result))
        $data[] = $row;

    return $data;
}

function getWeatherAfterData(){
    include '/var/www/phpExe/sqlcon.php';

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlWeatherDb );
    $sql_query = 'select * from after';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $data = array();
    while($row = mysqli_fetch_array($result))
        $data[] = $row;

    return $data;
}


?>