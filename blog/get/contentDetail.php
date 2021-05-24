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

function getDetailContent($level, $content_index){
    include "/var/www/phpExe/sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = 'select user_index, state, date, title, content from contents where content_index='.$content_index;
    if($level > 1) $sql_query .= ' and read_level>='.$level.' and state>=0';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    if($row = mysqli_fetch_assoc($result))
        return $row;
        
    return null;
}

$user = checkUser($_GET["id"], $_GET["pw"]);
$content = getDetailContent($user['level'], $_GET["content_index"]);
if(!$content)
    echo json_encode(array('state'=>200, 'data'=>'잘못된 접근'));
else{
    $canEdit = $content['state'] >= 0 && $user['user_index'] == $content['user_index'] ? 1 : 0;
    $canDisable = $content['state'] >= 0 && ($user['user_index'] == $content['user_index'] || $user['level'] < 2) ? 1 : 0;
    $canEnable = $content['state'] < 0 && $user['level'] < 2 ? 1 : 0;
    echo json_encode(array('state'=>000, 'data'=>array('canEdit'=>$canEdit, 'canDisable'=>$canDisable, 'canEnable'=>$canEnable, 'content'=>$content)));
}

?>
