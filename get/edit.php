<?php

include '/var/www/html/php/user.php';

function checkUserCanWrite($user){
    include "/var/www/phpExe/const.php";

    if($user['user_index'] < 1) return false;
    else if($user['user_index'] == 1) return true;
    else if($user['write_limit'] < $write_limit) return true;

    return false;
}

function getEditContent($user_index, $content_index){
    include "/var/www/phpExe/sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = 'select title, content from contents where content_index='.
        $content_index.' and user_index='.$user_index.' and state>=0';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    if($row = mysqli_fetch_assoc($result))
        return $row;
        
    return null;
}

$user = checkUser($_GET['id'], $_GET['pw']);
$canWrite = checkUserCanWrite($user);
if($canWrite){
    $editContent = getEditContent($user['user_index'], $_GET['content_index']);
    if(!$editContent)
        echo json_encode(array('state'=>200, 'data'=>'잘못된 접근'));
    else
        echo json_encode(array('state'=>000, 'data'=>$editContent));
}
else{
    echo json_encode(array('state'=>100, 'data'=>'하루 글쓰기 수가 초과 되었습니다.'));
}

?>
