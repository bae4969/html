<?php

include '/var/www/phpExe/key.php';
include '/var/www/phpExe/sqlcon.php';

$headers = array();
$headers[] = 'X-Naver-Client-Id: '.$naverId;
$headers[] = 'X-Naver-Client-Secret: '.$naverKey;

$space_filter = array('…', ',', '·', 'ㆍ', '.', '!', '?', '(', '{', '[');
$blank_filter = array(';', '\'', '‘', '’', '&quot', '"', '“', '”', ')', '}', ']', '<b>', '</b>');

$josa_filter_one = array('은', '는', '이', '가', '을', '를', '로', '과', '와', '에', '또', '것', '등', '왜', '중');
$josa_filter_two = array('으로', '대로');
$josa_expect = array('효과', '급등', '네로', '까이', '월가', '나와', '이은', '없는');

$update_list = array(
    array('name'=>'비트코인', 'table'=>'bitcoin_news'),
);

function cmp($a, $b) {
    if($a[1] == $b[1])
        return 0;
    return ($a[1] < $b[1]) ? 1 : -1;
}

function josa_replace($a) {
    $last_one = mb_substr($a, -1, 1);
    $last_two = mb_substr($a, -2, 2);

    if(in_array($last_two, $GLOBALS['josa_filter_two']))
        return mb_substr($a, 0, -2);

    if(in_array($last_one, $GLOBALS['josa_filter_one']) && !in_array($last_two, $GLOBALS['josa_expect']))
        return mb_substr($a, 0, -1);

	return $a;
}

function search_result($input) {
    
    $word_result = array();

    for($start = 1; $start < 500; $start += 100){
        $url = 'https://openapi.naver.com/v1/search/news.json';
        $url .= '?query='.urlencode(implode(' ', $input));
        $url .= '&display='.urlencode(100);    //1 ~ 100;
        $url .= '&start='.urlencode($start);    //1 ~ 1000
        $url .= '&sort='.urlencode('sim');    //'sim' or 'date'
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $GLOBALS['headers']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
    
        if($status_code != 200) {
            $GLOBALS['api_error_count']++;
            continue;
        }
    
        $result = json_decode($response, true);
    
        for($i = 0; $i < count($result['items']); $i++){
            $str_t = $result['items'][$i]['title'];
            $str_t = str_replace($GLOBALS['space_filter'], ' ', $str_t);
            $str_t = str_replace($GLOBALS['blank_filter'], '', $str_t);
            $word_t = explode(' ', $str_t);
    
            for($j = 0; $j < count($word_t); $j++){
                $temp = josa_replace($word_t[$j]);
                if($temp == '' || in_array($temp, $input))
                    continue;
    
                if(array_key_exists($temp, $word_result))
                    $word_result[$temp][1]++;
                else
                    $word_result[$temp] = array($temp, 1);
            }
        }
    }
    
    usort($word_result, 'cmp');

    return array_splice($word_result, 0, 20);
}

function get_time() { $t=explode(' ',microtime()); return (float)$t[0]+(float)$t[1]; }
$start = get_time();
$api_error_count = 0;
$sql_error_count = 0;

for($k = 0; $k < count($update_list); $k++){
    $base_key = array($update_list[$k]['name']);
    $base_result = search_result($base_key);
    $conn = mysqli_connect($sqlAddr, $sqlId, $sqlPw, $sqlNaverDb);

    for($i = 0; $i < 20; $i++){
        if($i < count($base_result)){
            $sql_query = 'update '.$update_list[$k]['table'].' set name="'.$base_result[$i][0].'", count='.$base_result[$i][1].' where pid=0 and id='.($i + 1);
            if(!mysqli_query($conn, $sql_query)){
                $sql_error_count++;
                continue;
            }

            $leaf_key = $base_key;
            $leaf_key[] = $base_result[$i][0];
            $leaf_result = search_result($leaf_key);

            for($j = 0; $j < 20; $j++){
                if($j < count($leaf_result)){
                    $sql_query = 'update '.$update_list[$k]['table'].' set name="'.$leaf_result[$j][0].'", count='.$leaf_result[$j][1].' where pid='.($i + 1).' and id='.($j + 1);
                    if(!mysqli_query($conn, $sql_query))
                        $sql_error_count++;
                }
                else{
                    $sql_query = 'update '.$update_list[$k]['table'].' set name="", count=0 where pid='.($i + 1).' and id='.($j + 1);
                    if(!mysqli_query($conn, $sql_query))
                        $sql_error_count++;
                }
            }
        }
        else{
            $sql_query = 'update '.$update_list[$k]['table'].' set name="", count=0 where pid=0 and id='.$i;
            if(!mysqli_query($conn, $sql_query))
                $sql_error_count++;
        }
    }
}

$end = get_time();
$time = $end - $start;
print('Execute : update naver news (Exe_time : '.number_format($time,6).', API_errors : '.$api_error_count.', SQL_errors : '.$sql_error_count).')';

?>
