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

function getQuerySelectContentList($level, $class_index = 0){
    if($class_index >= 0){
        $sql_query = ' where read_level>='.$level;
        if($level > 1)
            $sql_query .= ' and state>=0';
        if($class_index > 0)
            $sql_query .= ' and class_index='.$class_index;
    }
    else{
        if($level < 2){
            $sql_query = ' where class_index is NULL';
        }
        else{
            return null;
        }
    }

    return $sql_query;
}

function getContentListCount($condition){
    include "/var/www/phpExe/sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = 'select count(*) from contents'.$condition;
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    if($row = mysqli_fetch_assoc($result))
        return $row['count(*)'];

    return 0;
}

function getContentList($condition, $pageNum){
    include "/var/www/phpExe/sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = 'select user_index, content_index, state, date, title, thumbnail, summary from contents'.$condition;
    $sql_query .= ' order by content_index desc limit '.($pageNum * 10).', 10';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $MainContentList = array();
    while($row = mysqli_fetch_assoc($result))
        $MainContentList[] = $row;
        
    return $MainContentList;
}

$user = checkUser($_GET["id"], $_GET["pw"]);
$condition = getQuerySelectContentList($user['level'], $_GET['class_index']);
if(!$condition){
    echo json_encode(array('state'=>200, 'data'=>array('count'=>0, 'content'=>array())));
}
else{
    $totalContentCount = getContentListCount($condition);
    $contentList = getContentList($condition, $_GET['page']);
    echo json_encode(array('state'=>000, 'data'=>array('count'=>$totalContentCount, 'content'=>$contentList)));
}

?>
