<?php

include '../php/user.php';
include '../php/writeEdit.php';

function editContent($user_index, $content_index, $title, $thumbnail, $summary, $content){
    include "/var/www/phpExe/sqlcon.php";

    if($user_index < 1) return -1;
    if(($ret = checkContentInput($title, $thumbnail, $summary, $content)) < 0) return $ret;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = "update contents set title='".addslashes($title)."', thumbnail='".addslashes($thumbnail)."', summary='".addslashes($summary)."', content='".addslashes($content).
        "' where content_index=".$content_index." and user_index=".$user_index;
    if(mysqli_query($conn, $sql_query)){
        updateWriteLimit($user_index);
        return 1;
    }
    
    return 0;
}

$user = checkUser($_POST['id'], $_POST['pw']);
if(!checkUserCanWrite($user))
    echo json_encode(array('state'=>100, 'data'=>'하루 글쓰기 수가 초과 되었습니다'));
else{
    $result = editContent($user['user_index'], $_POST['content_index'], $_POST['title'], $_POST['thumbnail'], $_POST['summary'], $_POST['content']);
    if($result == 0)
        echo json_encode(array('state'=>100, 'data'=>'일시적 오류'));
    else if($result == -1)
        echo json_encode(array('state'=>200, 'data'=>'잘못된 접근'));
    else if($result == -2)
        echo json_encode(array('state'=>100, 'data'=>'제목이 최대 문자열 길이를 초과했습니다.'));
    else if($result == -3)
        echo json_encode(array('state'=>100, 'data'=>'썸네일이 최대 문자열 길이를 초과했습니다.'));
    else if($result == -4)
        echo json_encode(array('state'=>100, 'data'=>'요약이 최대 문자열 길이를 초과했습니다.'));
    else if($result == -5)
        echo json_encode(array('state'=>100, 'data'=>'내용이 최대 문자열 길이를 초과했습니다.'));
    else if($result == -6)
        echo json_encode(array('state'=>200, 'data'=>'입력 불가능한 문자열이 포함되어 있습니다.'));
    else
        echo json_encode(array('state'=>000));
}

?>
