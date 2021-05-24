<?php

function checkUser($id, $pw){
    include '/var/www/phpExe/sqlcon.php';
    include "/var/www/phpExe/const.php";

    if($id == '' || $pw == '')
        return array("user_index"=>0, "level"=>4, "write_limit"=>$write_limit, "img_upload_limit"=>$img_total_limit);

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = 'select user_index, level, state, write_limit, img_upload_limit from user_list where id="'.$id.'" and pw="'.$pw.'"';
    $result = mysqli_query($conn, $sql_query);

    if($row = mysqli_fetch_assoc($result)){
        if($row["state"] == 0)
            return $row;
        else
            return array("user_index"=>-1, "level"=>4, "write_limit"=>$write_limit, "img_upload_limit"=>$img_total_limit);
    }

    return array("user_index"=>0, "level"=>4, "write_limit"=>$write_limit, "img_upload_limit"=>$img_total_limit);
}

function disableContent($user_index, $level, $content_index){
    include "/var/www/phpExe/sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = 'update contents set state = -1 where content_index='.$content_index;
    if($level > 1)
        $sql_query.=' and user_index='.$user_index;
    if(mysqli_query($conn, $sql_query))
        return 1;
    
    return 0;
}

$user = checkUser($_POST["id"], $_POST["pw"]);
$result = disableContent($user['user_index'], $user['level'], $_POST['content_index']);
if($result > 0)
    echo json_encode(array('state'=>000));
else
    echo json_encode(array('state'=>300));

?>
