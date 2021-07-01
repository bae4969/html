<?php

include '../php/user.php';

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
