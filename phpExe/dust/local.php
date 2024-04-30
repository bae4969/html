<?php

include '/var/www/phpExe/key.php';
include '/var/www/phpExe/sqlcon.php';

$name = array(
    'seoul', 'busan', 'daegu', 'incheon', 'gwangju', 'daejeon', 'ulsan', 'gyeonggi', 'gangwon',
    'chungbuk', 'chungnam', 'jeonbuk', 'jeonnam', 'gyeongbuk', 'gyeongnam', 'jeju', 'sejong'
);
$type = array('SO2', 'CO', 'O3', 'NO2', 'PM10', 'PM25');

$data = array();
for($i = 0; $i < count($name); $i++){
    $temp = array();
    for($j = 0; $j < count($type); $j++)
        $temp[$type[$j]] = -1.0;
    $data[$name[$i]] = $temp;
}

function get_time() { $t=explode(' ',microtime()); return (float)$t[0]+(float)$t[1]; }
$start = get_time();
$api_error_count = 0;
$sql_error_count = 0;
$count = 0;

for($i = 0; $i < count($type); $i++){
    $ch = curl_init();
    $url = 'http://apis.data.go.kr/B552584/ArpltnStatsSvc/getCtprvnMesureLIst';
    $queryParams = '?' . urlencode('serviceKey') . '='.$gKey;
    $queryParams .= '&' . urlencode('returnType') . '=' . urlencode('JSON');
    $queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('1');
    $queryParams .= '&' . urlencode('itemCode') . '=' . urlencode($type[$i]);
    $queryParams .= '&' . urlencode('dataGubun') . '=' . urlencode('HOUR');
    
    curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    if($result['response']['header']['resultCode'] != 0
        || !is_array($result['response']['body']['items'][0]))
    {
        usleep(100000);
        if($count < 5){
            $api_error_count++;
            $count++;
            $i--;
        }
        continue;
    }
    $count = 0;

    for($j = 0; $j < count($name); $j++)
        $data[$name[$j]][$type[$i]] = $result['response']['body']['items'][0][$name[$j]];

    usleep(100000);
}

$conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlWeatherDb );
for($i = 0; $i < count($name); $i++){
    $sql_query = "update weather.dust set";

    $setCount = 0;
    for($j = 0; $j < count($type); $j++){
        if($data[$name[$i]][$type[$j]] >= 0){
            if($setCount != 0) $sql_query .= ",";
            $sql_query .= " ".$type[$j]."=".$data[$name[$i]][$type[$j]];
            $setCount++;
        }
    }
    $sql_query .= " where name='".$name[$i]."'";

    if($setCount > 0)
        if(!mysqli_query($conn, $sql_query))
            $sql_error_count++;
}

$end = get_time();
$time = $end - $start;
print('Execute : update dust (Exe_time : '.number_format($time,6).', API_errors : '.$api_error_count.', SQL_errors : '.$sql_error_count).')';

?>
