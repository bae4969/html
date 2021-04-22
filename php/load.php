<?php

function loadReadClassList($level = 4){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select class_index, name from class_list where read_level >= '.$level.' order by class_index asc';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $classList = array();
    while($row = mysqli_fetch_array($result))
        $classList[] = $row;
        
    return $classList;
}

function loadWriteClassList($level){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select class_index, name from class_list where write_level >= '.$level.' order by class_index asc';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $classList = array();
    while($row = mysqli_fetch_array($result))
        $classList[] = $row;
        
    return $classList;
}

function loadContentList($level, $class_index = null){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select content_index, date, title, thumbnail from contents where read_level >= '.$level;
    if($class_index) $sql_query .= ' and class_index = '.$class_index;
    $sql_query .= ' order by content_index desc limit 10';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $MainContentList = array();
    while($row = mysqli_fetch_array($result))
        $MainContentList[] = $row;
        
    return $MainContentList;
}

function loadDetailContent($level, $content_index){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select date, title, content from contents where content_index = '
        .$content_index.' and read_level >= '.$level;
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    if($row = mysqli_fetch_array($result))
        return $row;
        
    return null;
}

/************************************echo************************************/

function echoMainOnload($user_index = 0){
    if($user_index > 0){
        echo'
        document.getElementById("topRight").innerHTML = "로그아웃";
        document.getElementById("topWrite").innerHTML = "글쓰기";';
    }
    else{
        echo'
        document.getElementById("topRight").innerHTML = "로그인";
        if(document.getElementById("topWrite") !== null)
            document.getElementById("topWrite").innerHTML = "";';
    }
}

function echoHeader($user_index = 0, $level = 4){
    echo
    '<div id=topLeft onclick=homeClick()>Home</div>
    <div id=topRight onclick=loginoutClick('.$user_index.')></div>';
    if($level < 4)
    echo
    '<div id=topWrite onclick=writeClick('.$user_index.')></div>';
    echo
    '<div id=title>
        <img id=mainTitle onclick=homeClick() src="../res/index/title.png" alt="Index Page" />
    </div>';
}

function echoAsideList($level = 4){
    $classList = loadReadClassList($level);

    for($i = 0; $i < count($classList); $i++) {
        echo
        '<li class=category>
            <div class=category onclick=classClick('.$classList[$i]['class_index'].')>';
        echo
                $classList[$i]['name'];
        echo
            '</div>
        </li>';
    }

    return;
}

function echoContentList($level = 4, $class_index = null){
    $contentList = loadContentList($level, $class_index);

    for($i = 0; $i < count($contentList); $i++){
        echo
        '<div class="content" onclick="contentClick('.$contentList[$i]['content_index'].')">
            <div class="content_title">';
        echo 
                $contentList[$i]["title"];
        echo
            '</div>
            <div class="content_date">';
        echo
                $contentList[$i]["date"];
        echo
            '</div><hr>
            <div class="content_thumbnail">';
        echo
                $contentList[$i]["thumbnail"];
        echo
            '</div>
        </div>';
    }
}

function echoDetailContent($level, $content_index){
    $content = loadDetailContent($level, $content_index);

    echo
    '<div id=content_title>'
        .$content["title"].
    '</div>';
    echo
    '<div id=content_date>'
        .$content["date"].
    '</div><hr>';
    echo
    '<div id=content_content>'
        .$content["content"].
    '</div>';
}

function echoFooter(){
    echo'
    <p>Contact : bae4969@naver.com</br>
    Github : https://github.com/bae4969</p>';
}

function echoSelectClassList($level = 4){
    $classList = loadWriteClassList($level);

    for($i = 0; $i < count($classList); $i++)
        echo '<option value="'.$classList[$i]['class_index'].'">'.$classList[$i]['name'].'</option>';
}

?>
