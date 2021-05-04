<!-- control.php -->
<!-- sql insert and update table function -->
<?php

function checkContentInput($title, $thumbnail, $summary, $content){
    if(mb_strlen($title) > 30) return -2;
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

function insertContent($user_index, $level, $class_index, $read_level, $write_level, $title, $thumbnail, $summary, $content){
    include "sqlcon.php";

    if($user_index < 1) return -1;
    if($class_index < 1) return -1;
    if($level > $write_level) return -1;
    if(($ret = checkContentInput($title, $thumbnail, $summary, $content)) < 0) return $ret;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query
        = "insert into contents(user_index, class_index, read_level, write_level, title, thumbnail, summary, content) value(".
        $user_index.",".$class_index.",".$read_level.",".$write_level.",'".$title."','".$thumbnail."','".$summary."','".$content."')";
    if(mysqli_query($conn, $sql_query)){
        $sql_query = 'SELECT LAST_INSERT_ID()';
        $result = mysqli_query($conn, $sql_query);
        mysqli_close($conn);
        if($row = mysqli_fetch_array($result))
            return $row['LAST_INSERT_ID()'];
    }

    return 0;
}

function editContent($user_index, $content_index, $title, $thumbnail, $summary, $content){
    include "sqlcon.php";

    if($user_index < 1) return -1;
    if(($ret = checkContentInput($title, $thumbnail, $summary, $content)) < 0) return $ret;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = "update contents set title='".$title."', thumbnail='".$thumbnail."', summary='".$summary."', content='".$content.
        "' where content_index=".$content_index." and user_index=".$user_index;
    if(mysqli_query($conn, $sql_query)){
        return 1;
    }
    
    return mysqli_error($conn);
}

function disableContent($user_index, $level, $content_user_index, $content_index){
    include "sqlcon.php";

    if($level > 1 && $user_index != $content_user_index) return -1;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'update contents set state = -1 where content_index='.$content_index;
    if(mysqli_query($conn, $sql_query))
        return 1;
    
    return 0;
}

function restoreContent($level, $content_index){
    include "sqlcon.php";

    if($level > 1) return -1;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'update contents set state = 0 where content_index='.$content_index;
    if(mysqli_query($conn, $sql_query))
        return 1;
    
    return 0;
}

?>