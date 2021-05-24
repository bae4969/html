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

function getReadClassList($level){
    include "/var/www/phpExe/sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = 'select class_index, name from class_list where read_level>='.$level.' order by order_num asc';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $classList = array();
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
