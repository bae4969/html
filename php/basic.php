<!-- basic.php -->
<!-- select table function -->
<?php

function checkUser($id, $pw){
    include "sqlcon.php";

    if($id == '' || $pw == '')
        return array("user_index"=>0, "level"=>4, "user_index"=>0);

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select user_index, level, state from user_list where id="'.$id.'" and pw="'.$pw.'"';
    $result = mysqli_query($conn, $sql_query);

    if($row = mysqli_fetch_array($result)){
        if($row["state"] == 0)
            return $row;
        else
            return array("user_index"=>-1, "level"=>4);
    }

    return array("user_index"=>0, "level"=>4);
}

function getClassLevel($class_index){
    include "sqlcon.php";

    if($class_index == '')
        return array("class_index"=>0, "read_level"=>4, "write_level"=>4);

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select class_index, read_level, write_level from class_list where class_index='.$class_index;
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    if($row = mysqli_fetch_array($result))
        return $row;

    return array("class_index"=>0, "read_level"=>4, "write_level"=>4);
}

function getContentInfo($content_index){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select content_index, user_index, class_index, read_level, write_level, state, date'.
        ' from contents where content_index='.$content_index;
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    if($row = mysqli_fetch_array($result))
        return $row;
        
    return null;
}
    
?>
