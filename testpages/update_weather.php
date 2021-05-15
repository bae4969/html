<?php

include '../php/key.php';
include '../php/sqlcon.php';

$x = 98;
$y = 86;
$yyyy = '2021';
$MM = '05';
$dd = '15';
$hh = '17';
$mm = '00';

$ch = curl_init();
$url = 'http://apis.data.go.kr/1360000/VilageFcstInfoService/getUltraSrtNcst'; /*URL*/
//$url = 'http://apis.data.go.kr/1360000/VilageFcstInfoService/getVilageFcst'; /*URL*/
$queryParams = '?' . urlencode('ServiceKey') . '='.$gKey; /*Service Key*/
$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1'); /**/
$queryParams .= '&' . urlencode('numOfRows') . '=' . urlencode('500'); /**/
$queryParams .= '&' . urlencode('dataType') . '=' . urlencode('JSON'); /**/
$queryParams .= '&' . urlencode('base_date') . '=' . urlencode($yyyy.$MM.$dd); /**/
$queryParams .= '&' . urlencode('base_time') . '=' . urlencode($hh.$mm); /**/
$queryParams .= '&' . urlencode('nx') . '=' . urlencode($x); /**/
$queryParams .= '&' . urlencode('ny') . '=' . urlencode($y); /**/

curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
curl_close($ch);

// print($response);

$result = json_decode($response, true);

$insert_array = array('x'=>$x, 'y'=>$y, 'T1H'=>0, 'REH'=>0, 'WSD'=>0, 'PTY'=>0, 'RN1'=>0);
$num_data = count($result['response']['body']['items']['item']);
for($i = 0; $i < $num_data; $i++){
    switch($result['response']['body']['items']['item'][$i]['category']){
        case 'T1H':
            $insert_array['T1H'] = $result['response']['body']['items']['item'][$i]['obsrValue'];
            break;
        case 'REH':
            $insert_array['REH'] = $result['response']['body']['items']['item'][$i]['obsrValue'];
            break;
        case 'WSD':
            $insert_array['WSD'] = $result['response']['body']['items']['item'][$i]['obsrValue'];
            break;
        case 'PTY':
            $insert_array['PTY'] = $result['response']['body']['items']['item'][$i]['obsrValue'];
            break;
        case 'RN1':
            $insert_array['RN1'] = $result['response']['body']['items']['item'][$i]['obsrValue'];
            break;
    }
}

print_r($insert_array);

?>