<?php

include '/var/www/html/php/user.php';

function getWriteClassList($level){
    include "/var/www/phpExe/sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = 'select class_index, name from class_list where write_level>='.$level.' order by class_index asc';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $classList = array();
    while($row = mysqli_fetch_assoc($result))
        $classList[] = $row;
        
    return $classList;
}

$user = checkUser($_GET['id'], $_GET['pw']);
$classList = getWriteClassList($user['level']);
echo json_encode(array('state'=>000, 'data'=>$classList));

?>
