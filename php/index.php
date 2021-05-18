<?php

function checkUser($id, $pw){
    include "/var/www/phpExe/sqlcon.php";
    include "/var/www/phpExe/const.php";

    if($id == '' || $pw == '')
        return array("user_index"=>0, "level"=>4, "write_limit"=>$write_limit, "img_upload_limit"=>$img_total_limit);

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = 'select user_index, level, state, write_limit, img_upload_limit from user_list where id="'.$id.'" and pw="'.$pw.'"';
    $result = mysqli_query($conn, $sql_query);

    if($row = mysqli_fetch_array($result)){
        if($row["state"] == 0)
            return $row;
        else
            return array("user_index"=>-1, "level"=>4, "write_limit"=>$write_limit, "img_upload_limit"=>$img_total_limit);
    }

    return array("user_index"=>0, "level"=>4, "write_limit"=>$write_limit, "img_upload_limit"=>$img_total_limit);
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function echoMainOnload($user_index = 0){
    if($user_index > 0){
        echo'
        document.getElementById("topRight").innerHTML = "로그아웃";';
    }
    else{
        echo'
        document.getElementById("topRight").innerHTML = "로그인";';
    }
}

function echoHeader($user_index = 0){
    echo
    '<div id=topLeft onclick=indexClick()>Home</div>
    <div id=topRight onclick=loginoutClick('.$user_index.')></div>
    <div id=title>
        <img id=mainTitle onclick=indexClick() src="/res/index/index_title.png" alt="Index Page" />
    </div>';
}

?>