<?php

include '/var/www/phpExe/key.php';
include '/var/www/phpExe/sqlcon.php';

$conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlWeatherDb );
$sql_query = 'select * from xyList';
$result = mysqli_query($conn, $sql_query);
mysqli_close($conn);

$xyList = array();
while($row = mysqli_fetch_array($result))
    $xyList[] = $row;

$dateTime_now = new DateTime(date("Y-m-d H").':00:00');
$hh = $dateTime_now->format('H');

if($hh < 2)        {$dateTime_now->modify('-1 day');
                    $dateTime_now->setTime(23, 0);}
else if($hh < 5)    $dateTime_now->setTime(2, 0);
else if($hh < 8)    $dateTime_now->setTime(5, 0);
else if($hh < 11)   $dateTime_now->setTime(8, 0);
else if($hh < 14)   $dateTime_now->setTime(11, 0);
else if($hh < 17)   $dateTime_now->setTime(14, 0);
else if($hh < 20)   $dateTime_now->setTime(17, 0);
else if($hh < 23)   $dateTime_now->setTime(20, 0);
else                $dateTime_now->setTime(23, 0);

$dateTime_tom = new DateTime($dateTime_now->format("Y-m-d H").':00:00');
$dateTime_tom->modify('+1 day');
$yyyy = $dateTime_now->format('Y');
$MM = $dateTime_now->format('m');
$dd = $dateTime_now->format('d');
$hh = $dateTime_now->format('H');
$mm = '00';

function get_time() { $t=explode(' ',microtime()); return (float)$t[0]+(float)$t[1]; }
$start = get_time();
$api_error_count = 0;
$sql_error_count = 0;
$count = 0;

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

    $result = json_decode($response, true);
    if($result['response']['header']['resultCode'] != 0
        || !is_array($result['response']['body']['items']['item']))
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

    $num_data = count($result['response']['body']['items']['item']);
    $insert_array = array();
    for($j = 0; $j < 2; $j++)
        $insert_array[] = array(
            'x'=>$x, 'y'=>$y, 'num'=>$j,
            'TMN'=>null, 'TMX'=>null,
            'REH0'=>null, 'REH1'=>null,
            'WSD0'=>null, 'WSD1'=>null,
            'SKY0'=>null, 'SKY1'=>null,
            'POP0'=>null, 'POP1'=>null,
            'PTY0'=>null, 'PTY1'=>null,
            'R0'=>null, 'R1'=>null,
            'S0'=>null, 'S1'=>null
        );

    $today_date = $time_before = $result['response']['body']['items']['item'][0]['fcstDate'];
    $today_time = $time_before = $result['response']['body']['items']['item'][0]['fcstTime'];    
    $date_tomm = $dateTime_tom->format('Ymd');
    for($j = 0; $j < $num_data; $j++){
        if($today_date == $result['response']['body']['items']['item'][$j]['fcstDate'] &&
           $today_time == $result['response']['body']['items']['item'][$j]['fcstTime']){
            if($today_time < 15){
                switch($result['response']['body']['items']['item'][$j]['category']){
                    case 'TMN': $insert_array[0]['TMN'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'REH': $insert_array[0]['REH0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'WSD': $insert_array[0]['WSD0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'SKY': $insert_array[0]['SKY0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'POP': $insert_array[0]['POP0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'PTY': $insert_array[0]['PTY0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'R06': $insert_array[0]['R0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'S06': $insert_array[0]['S0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                }
            }
            else{
                switch($result['response']['body']['items']['item'][$j]['category']){
                    case 'TMX': $insert_array[0]['TMX'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'REH': $insert_array[0]['REH1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'WSD': $insert_array[0]['WSD1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'SKY': $insert_array[0]['SKY1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'POP': $insert_array[0]['POP1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'PTY': $insert_array[0]['PTY1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'R06': $insert_array[0]['R1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'S06': $insert_array[0]['S1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                }
            }
        }
        if($date_tomm == $result['response']['body']['items']['item'][$j]['fcstDate']){
            $time_this = $result['response']['body']['items']['item'][$j]['fcstTime'];
            if($time_this == '0600')
                switch($result['response']['body']['items']['item'][$j]['category']){
                    case 'TMN': $insert_array[1]['TMN'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'REH': $insert_array[1]['REH0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'WSD': $insert_array[1]['WSD0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'SKY': $insert_array[1]['SKY0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'POP': $insert_array[1]['POP0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'PTY': $insert_array[1]['PTY0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'R06': $insert_array[1]['R0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'S06': $insert_array[1]['S0'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                }
            else if($time_this == '1500'){
                switch($result['response']['body']['items']['item'][$j]['category']){
                    case 'TMX': $insert_array[1]['TMX'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'REH': $insert_array[1]['REH1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'WSD': $insert_array[1]['WSD1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'SKY': $insert_array[1]['SKY1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'POP': $insert_array[1]['POP1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'PTY': $insert_array[1]['PTY1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'R06': $insert_array[1]['R1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                    case 'S06': $insert_array[1]['S1'] = $result['response']['body']['items']['item'][$j]['fcstValue']; break;
                }
            }
        }
    }

    $update_str = array();
    for($j = 0; $j < 2; $j++){
        $temp = array();
        if($insert_array[$j]['TMN'] != null) $temp['TMN'] = $insert_array[$j]['TMN'];
        if($insert_array[$j]['TMX'] != null) $temp['TMX'] = $insert_array[$j]['TMX'];
        if($insert_array[$j]['REH0'] != null) $temp['REH0'] = $insert_array[$j]['REH0'];
        if($insert_array[$j]['REH1'] != null) $temp['REH1'] = $insert_array[$j]['REH1'];
        if($insert_array[$j]['WSD0'] != null) $temp['WSD0'] = $insert_array[$j]['WSD0'];
        if($insert_array[$j]['WSD1'] != null) $temp['WSD1'] = $insert_array[$j]['WSD1'];
        if($insert_array[$j]['SKY0'] != null) $temp['SKY0'] = $insert_array[$j]['SKY0'];
        if($insert_array[$j]['SKY1'] != null) $temp['SKY1'] = $insert_array[$j]['SKY1'];
        if($insert_array[$j]['POP0'] != null) $temp['POP0'] = $insert_array[$j]['POP0'];
        if($insert_array[$j]['POP1'] != null) $temp['POP1'] = $insert_array[$j]['POP1'];
        if($insert_array[$j]['PTY0'] != null) $temp['PTY0'] = $insert_array[$j]['PTY0'];
        if($insert_array[$j]['PTY1'] != null) $temp['PTY1'] = $insert_array[$j]['PTY1'];
        if($insert_array[$j]['R0'] != null) $temp['R0'] = $insert_array[$j]['R0'];
        if($insert_array[$j]['R1'] != null) $temp['R1'] = $insert_array[$j]['R1'];
        if($insert_array[$j]['S0'] != null) $temp['S0'] = $insert_array[$j]['S0'];
        if($insert_array[$j]['S1'] != null) $temp['S1'] = $insert_array[$j]['S1'];

        $update_str[] = implode(', ', array_map(
            function ($k, $v) {
                if(is_array($v))return $k.'[]='.implode('&'.$k.'[]=', $v);
                else            return $k.'='.$v;
            },
            array_keys($temp),
            $temp
        ));
    }

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlWeatherDb );
    for($j = 0; $j < 2; $j++){
        $sql_query =
            'update after set '.$update_str[$j].
            ' where x='.$insert_array[$j]['x'].
            ' and y='.$insert_array[$j]['y'].
            ' and num='.$insert_array[$j]['num'];
        if(!mysqli_query($conn, $sql_query)) $sql_error_count++;
    }
    usleep(100000);
}

$end = get_time();
$time = $end - $start;
print('Execute : update weather after (Exe_time : '.number_format($time,6).', API_errors : '.$api_error_count.', SQL_errors : '.$sql_error_count).')';

?>
