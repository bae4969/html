<?php

function submitContent($user_index, $level, $class_index, $read_level, $write_level, $title, $thumbnail, $content){
    include "sqlcon.php";

    if($user_index < 1) return -1;
    if($class_index < 1) return -1;
    if($level > $write_level) return -1;

    $filter = array('<script', '</');
    for($i = 0; $i < count($filter); $i++) if(stripos($title, $filter) != 0) return -2;
    for($i = 0; $i < count($filter); $i++) if(stripos($thumbnail, $filter) != 0) return -2;
    for($i = 0; $i < count($filter); $i++) if(stripos($content, $filter) != 0) return -2;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query
        = 'insert into contents(user_index, class_index, read_level, write_level, title, thumbnail, content) value('.
        $user_index.','.$class_index.','.$read_level.','.$write_level.',"'.$title.'","'.$thumbnail.'","'.$content.'")';

    if(mysqli_query($conn, $sql_query)){
        $sql_query = 'SELECT LAST_INSERT_ID()';
        $result = mysqli_query($conn, $sql_query);
        mysqli_close($conn);
        if($row = mysqli_fetch_array($result))
            return $row['LAST_INSERT_ID()'];
    }

    return 0;
}

?>
