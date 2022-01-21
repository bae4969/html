<?php

include '../php/user.php';

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
