<?php

include '../php/user.php';

function enableContent($level, $content_index){
    include "/var/www/phpExe/sqlcon.php";

    if($level > 1) return -1;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = 'update contents set state = 0 where content_index='.$content_index;
    if(mysqli_query($conn, $sql_query))
        return 1;
    
    return 0;
}

$user = checkUser($_POST["id"], $_POST["pw"]);
$result = enableContent($user['level'], $_POST['content_index']);
if($result > 0)
    echo json_encode(array('state'=>000));
else
    echo json_encode(array('state'=>300));

?>
