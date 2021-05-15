<?php

include '../php/key.php';
include '../php/sqlcon.php';

$conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlWeatherDb );
$sql_query = 'select * from xyList';
$result = mysqli_query($conn, $sql_query);
mysqli_close($conn);

$xyList = array();
while($row = mysqli_fetch_array($result))
    $xyList[] = $row;

$yyyy = date("Y");
$MM = date("m");
$dd = date("d");
$hh = date("H");
$mm = '00';

for($i = 0; $i < count($xyList); $i++){
    $x = $xyList[$i]['x'];
    $y = $xyList[$i]['y'];

    $ch = curl_init();
    $url = 'http://apis.data.go.kr/1360000/VilageFcstInfoService/getVilageFcst';
    $queryParams = '?' . urlencode('ServiceKey') . '='.$gKey;
    $queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1');
    $queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('500');
    $queryParams .= '&' . urlencode('dataType') . '=' . urlencode('JSON');
    $queryParams .= '&' . urlencode('base_date') . '=' . urlencode($yyyy.$MM.$dd);
    $queryParams .= '&' . urlencode('base_time') . '=' . urlencode($hh.$mm);
    $queryParams .= '&' . urlencode('nx') . '=' . urlencode($x);
    $queryParams .= '&' . urlencode('ny') . '=' . urlencode($y);

    curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
    $response = curl_exec($ch);
    curl_close($ch);

    print_r($response);

    $result = json_decode($response, true);
    if(!is_array($result['response']['body']['items']['item'])) return;

    $insert_array = array('x'=>$x, 'y'=>$y, 'T1H'=>0, 'REH'=>0, 'WSD'=>0.0, 'PTY'=>0, 'RN1'=>0.0);

    $num_data = count($result['response']['body']['items']['item']);
    for($i = 0; $i < $num_data; $i++){
        switch($result['response']['body']['items']['item'][$i]['category']){
            case 'T1H': $insert_array['T1H'] = $result['response']['body']['items']['item'][$i]['obsrValue']; break;
            case 'REH': $insert_array['REH'] = $result['response']['body']['items']['item'][$i]['obsrValue']; break;
            case 'WSD': $insert_array['WSD'] = $result['response']['body']['items']['item'][$i]['obsrValue']; break;
            case 'PTY': $insert_array['PTY'] = $result['response']['body']['items']['item'][$i]['obsrValue']; break;
            case 'RN1': $insert_array['RN1'] = $result['response']['body']['items']['item'][$i]['obsrValue']; break;
        }
    }

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlWeatherDb );
    // $sql_query =
    //     'update now set'
    //     .' T1H='.$insert_array['T1H']
    //     .', REH='.$insert_array['REH']
    //     .', WSD='.$insert_array['WSD']
    //     .', PTY='.$insert_array['PTY']
    //     .', RN1='.$insert_array['RN1']
    //     .' where x='.$x.' and y='.$y;

    // mysqli_query($conn, $sql_query);
    usleep(40000);
}

?>
