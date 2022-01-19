<?php

function checkUserCanWrite($user){
    include "/var/www/phpExe/const.php";

    if($user['user_index'] < 1) return false;
    else if($user['user_index'] == 1) return true;
    else if($user['write_limit'] < $write_limit) return true;

    return false;
}

function checkContentInput($title, $thumbnail, $summary, $content){
    if(mb_strlen($title) > 50) return -2;
    if(mb_strlen($thumbnail) > 200) return -3;
    if(mb_strlen($summary) > 203) return -4;
    if(strlen($content > 8388608)) return -5;

    $filter = array('<script');
    for($i = 0; $i < count($filter); $i++) if(mb_stripos($title, $filter[$i]) !== false) return -6;
    for($i = 0; $i < count($filter); $i++) if(mb_stripos($thumbnail, $filter[$i]) !== false) return -6;
    for($i = 0; $i < count($filter); $i++) if(mb_stripos($summary, $filter[$i]) !== false) return -6;
    for($i = 0; $i < count($filter); $i++) if(mb_stripos($content, $filter[$i]) !== false) return -6;

    return 0;
}

function updateWriteLimit($user_index){
    include "/var/www/phpExe/sqlcon.php";
    
    if($user_index < 1) return -1;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = "update user_list set write_limit=write_limit+1 where user_index=".$user_index;

    if(mysqli_query($conn, $sql_query)){
        return 1;
    }
    
    return 0;
}

?>
