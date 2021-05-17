<?php

function getGeoData(){
    include 'sqlcon.php';

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select * from geo';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $data = array();
    while($row = mysqli_fetch_array($result))
        $data[] = $row;

    return $data;
}

function getWeatherNowData(){
    include 'sqlcon.php';

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select * from now';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $data = array();
    while($row = mysqli_fetch_array($result))
        $data[] = $row;

    return $data;
}

function getWeatherAfterData(){
    include 'sqlcon.php';

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select * from after';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $data = array();
    while($row = mysqli_fetch_array($result))
        $data[] = $row;

    return $data;
}

function getDustData(){
    include 'sqlcon.php';

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select * from dust';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $data = array();
    while($row = mysqli_fetch_array($result))
        $data[] = $row;

    return $data;
}


?>