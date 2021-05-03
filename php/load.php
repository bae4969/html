<!-- load.php -->
<!-- load data from sql and make data to html form -->
<?php

function getReadClassList($level = 4){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select class_index, name from class_list where read_level>='.$level.' order by class_index asc';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $classList = array();
    while($row = mysqli_fetch_array($result))
        $classList[] = $row;

    if($level <= 1)
        $classList[] = array('class_index'=>-1, 'name'=>'Class Lost');
        
    return $classList;
}

function getWriteClassList($level){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select class_index, name from class_list where write_level>='.$level.' order by class_index asc';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $classList = array();
    while($row = mysqli_fetch_array($result))
        $classList[] = $row;
        
    return $classList;
}

function getQuerySelectContentList($level, $class_index = null){
    $sql_query = ' from contents where read_level>='.$level;
    if($level > 1) $sql_query .= ' and state>=0';
    if($class_index) $sql_query .= ' and class_index = '.$class_index;
    if($class_index < 0 && $level < 2)
        $sql_query = ' from contents where class_index is NULL';

    return $sql_query;
}

function getContentListCount($level, $class_index = null){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select count(*)'.getQuerySelectContentList($level, $class_index);
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    if($row = mysqli_fetch_array($result))
        return $row[0];

    return 0;
}

function getContentList($level, $pageNum = 1, $class_index = null){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select user_index, content_index, state, date, title, thumbnail, summary '.getQuerySelectContentList($level, $class_index);
    $sql_query .= ' order by content_index desc limit '.(($pageNum - 1) * 10).', 10';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $MainContentList = array();
    while($row = mysqli_fetch_array($result))
        $MainContentList[] = $row;
        
    return $MainContentList;
}

function getDetailContent($level, $content_index){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select user_index, state, date, title, content from contents where content_index='.$content_index;
    if($level > 1) $sql_query .= ' and read_level>='.$level.' and state>=0';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    if($row = mysqli_fetch_array($result))
        return $row;
        
    return null;
}

function getEditContent($user_index, $content_index){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select title, content from contents where content_index='.
        $content_index.' and user_index='.$user_index.' and state>=0';
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
    $classList = getReadClassList($level);

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

function echoContentList($level = 4, $page, $class_index = null){
    $contentList = getContentList($level, $page, $class_index);

    $ret = '<div id=contents><div id=temp>';
    for($i = 0; $i < count($contentList); $i++){
        if($contentList[$i]['state'] < 0)
            $ret .= '<div id=content'.$i.' class="content_ban" onclick="contentClick('.$contentList[$i]['content_index'].')">';
        else
            $ret .=  '<div id=content'.$i.' class="content" onclick="contentClick('.$contentList[$i]['content_index'].')">';
            $ret .= 
            '<div class=content_title>'.
                $contentList[$i]["title"].
            '</div>'.
            '<div class=content_date>'.
                $contentList[$i]["date"].
            '</div>'.
            '<div class=content_date>'.
                'UID : '.$contentList[$i]["user_index"].
            '</div><hr>';
            if($contentList[$i]["thumbnail"] != ''){
                $ret .= '<div class=content_bot><img '.$contentList[$i]["thumbnail"].' class=content_thumbnail></div>';
            }
            $ret .=
            '<div class=content_summary>'.
                $contentList[$i]["summary"].
            '</div>
        </div>';
    }
    $ret .=  '</div><div id=left></div><div id=right></div></div>';

    return array(count($contentList), $ret);
}

function echoDetailContent($user_index, $level, $content_index){
    $content = getDetailContent($level, $content_index);

    if($content['state'] < 0)
        echo '<div id="content_ban">';
    else
        echo '<div id="content">';
    echo
        '<div id=content_title>'
            .$content["title"].
        '</div>'.
        '<div id=content_date>'
            .$content["date"].
        '</div>'.
        '<div id=content_date>'.
            'UID : '.$content["user_index"].
        '</div><hr>'.
        '<div id=content_content>'
            .$content["content"].
        '</div>';
    if($user_index == $content['user_index'] || $level < 2){
        if($content['state'] < 0 && $level < 2)
            echo '<button class=content_control onclick=restoreClick()>복구</button>';
        else
            echo '<button class=content_control onclick=deleteClick()>삭제</button>';
    }
    if($user_index == $content['user_index'])
        echo '<button class=content_control onclick=editClick()>수정</button>';
    
    echo
    '</div>';
}

function echoFooter(){
    echo'
    <p>Contact : bae4969@naver.com</br>
    Github : https://github.com/bae4969</p>';
}

function echoSelectClassList($level = 4){
    $classList = getWriteClassList($level);

    for($i = 0; $i < count($classList); $i++)
        echo '<option value="'.$classList[$i]['class_index'].'">'.$classList[$i]['name'].'</option>';
}

?>
