<?php

function checkUser($id, $pw){
    include "/var/www/phpExe/sqlcon.php";
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

$user = checkUser($_GET["id"], $_GET["pw"]);

if($user["user_index"] > 0){
    $user['etc'] = 'none';
    echo json_encode(array('state'=>'000', 'data'=>$user));
}
else if($user["user_index"] < 0){
    $user['etc'] = '접근 불가 유저입니다.';
    echo json_encode(array('state'=>'200', 'data'=>$user));
}
else{
    $user['etc'] = 'ID 또는 PW가 일치하지 않습니다.';
    echo json_encode(array('state'=>'100', 'data'=>$user));
}

?>
