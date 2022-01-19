<?php

include '/var/www/html/php/user.php';

function getReadClassList($level){
    include "/var/www/phpExe/sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = 'select class_index, name from class_list where read_level>='.$level.' order by order_num asc';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $classList = array();
    $classList[] = array('class_index'=>0, 'name'=>'전체 보기');
    while($row = mysqli_fetch_assoc($result))
        $classList[] = $row;

    if($level <= 1)
        $classList[] = array('class_index'=>-1, 'name'=>'Class Lost');
        
    return $classList;
}

$user = checkUser($_GET["id"], $_GET["pw"]);
$classList = getReadClassList($user['level']);
echo json_encode(array('state'=>000, 'data'=>$classList));

?>
