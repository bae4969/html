<?php

function checkUser($id, $pw){
    include "sqlcon.php";
    include "const.php";

    if($id == '' || $pw == '')
        return array("user_index"=>0, "level"=>4, "write_limit"=>$write_limit, "img_upload_limit"=>$img_total_limit);

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
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

function checkUserCanWrite($user){
    include "const.php";

    if($user['user_index'] < 1) return false;
    else if($user['user_index'] == 1) return true;
    else if($user['write_limit'] < $write_limit) return true;

    return false;
}

function checkUserCanUploadImg($user){
    include "const.php";

    if($user['user_index'] < 1) return false;
    else if($user['user_index'] == 1) return true;
    else if($user['img_upload_limit'] < $img_total_limit) return true;

    return false;
}

function getClassLevel($class_index){
    include "sqlcon.php";

    if($class_index == '')
        return array("class_index"=>0, "read_level"=>4, "write_level"=>4);

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select class_index, read_level, write_level from class_list where class_index='.$class_index;
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    if($row = mysqli_fetch_array($result))
        return $row;

    return array("class_index"=>0, "read_level"=>4, "write_level"=>4);
}

function getContentInfo($content_index){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select content_index, user_index, class_index, read_level, write_level, state, date'.
        ' from contents where content_index='.$content_index;
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    if($row = mysqli_fetch_array($result))
        return $row;
        
    return null;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function checkContentInput($title, $thumbnail, $summary, $content){
    if(mb_strlen($title) > 50) return -2;
    if(mb_strlen($thumbnail) > 200) return -3;
    if(mb_strlen($summary) > 203) return -4;
    if(strlen($content > 8388608)) return -5;

    $filter = array('<script');
    for($i = 0; $i < count($filter); $i++) if(mb_stripos($title, $filter[$i]) !== false) return -6;
    for($i = 0; $i < count($filter); $i++) if(mb_stripos($thumbnail, $filter[$i]) !== false) return -6;
    for($i = 0; $i < count($filter); $i++) if(mb_stripos($summary, $filter[$i]) !== false) return -6;
    for($i = 0; $i < count($filter); $i++) if(mb_stripos($content, $filter[$i]) !== false) return -6;

    return 0;
}

function updateWriteLimit($user_index){
    include "sqlcon.php";
    
    if($user_index < 1) return -1;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = "update user_list set write_limit=write_limit+1 where user_index=".$user_index;

    if(mysqli_query($conn, $sql_query)){
        return 1;
    }
    
    return 0;
}

function updateLoadFileLimit($user_index, $volume){
    include "sqlcon.php";
    
    if($user_index < 1) return -1;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = "update user_list set img_upload_limit=img_upload_limit+".$volume.
        " where user_index=".$user_index;

    if(mysqli_query($conn, $sql_query)){
        return 1;
    }
    
    return 0;
}

function insertContent($user_index, $level, $class_index, $read_level, $write_level, $title, $thumbnail, $summary, $content){
    include "sqlcon.php";

    if($user_index < 1) return -1;
    if($class_index < 1) return -1;
    if($level > $write_level) return -1;
    if(($ret = checkContentInput($title, $thumbnail, $summary, $content)) < 0) return $ret;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query
        = "insert into contents(user_index, class_index, read_level, write_level, title, thumbnail, summary, content) value(".
        $user_index.",".$class_index.",".$read_level.",".$write_level.",'".$title."','".$thumbnail."','".$summary."','".$content."')";
    if(mysqli_query($conn, $sql_query)){
        $sql_query = 'SELECT LAST_INSERT_ID()';
        $result = mysqli_query($conn, $sql_query);
        mysqli_close($conn);
        if($row = mysqli_fetch_array($result)){
            $ret = $row['LAST_INSERT_ID()'];
            updateWriteLimit($user_index);
            return $ret;
        }
    }

    return 0;
}

function editContent($user_index, $content_index, $title, $thumbnail, $summary, $content){
    include "sqlcon.php";

    if($user_index < 1) return -1;
    if(($ret = checkContentInput($title, $thumbnail, $summary, $content)) < 0) return $ret;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = "update contents set title='".$title."', thumbnail='".$thumbnail."', summary='".$summary."', content='".$content.
        "' where content_index=".$content_index." and user_index=".$user_index;
    if(mysqli_query($conn, $sql_query)){
        updateWriteLimit($user_index);
        return 1;
    }
    
    return 0;
}

function disableContent($user_index, $level, $content_user_index, $content_index){
    include "sqlcon.php";

    if($level > 1 && $user_index != $content_user_index) return -1;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'update contents set state = -1 where content_index='.$content_index;
    if(mysqli_query($conn, $sql_query))
        return 1;
    
    return 0;
}

function restoreContent($level, $content_index){
    include "sqlcon.php";

    if($level > 1) return -1;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'update contents set state = 0 where content_index='.$content_index;
    if(mysqli_query($conn, $sql_query))
        return 1;
    
    return 0;
}

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getReadClassList($level = 4){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select class_index, name from class_list where read_level>='.$level.' order by order_num asc';
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

function getQuerySelectContentList($level, $class_index = 0){
    $sql_query = ' from contents where read_level>='.$level;
    if($level > 1) $sql_query .= ' and state>=0';
    if($class_index > 0) $sql_query .= ' and class_index = '.$class_index;
    if($class_index < 0 && $level < 2)
        $sql_query = ' from contents where class_index is NULL';

    return $sql_query;
}

function getContentListCount($level, $class_index = 0){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select count(*)'.getQuerySelectContentList($level, $class_index);
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    if($row = mysqli_fetch_array($result))
        return $row[0];

    return 0;
}

function getContentList($level, $pageNum = 0, $class_index = 0){
    include "sqlcon.php";

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlDb );
    $sql_query = 'select user_index, content_index, state, date, title, thumbnail, summary '.getQuerySelectContentList($level, $class_index);
    $sql_query .= ' order by content_index desc limit '.($pageNum * 10).', 10';
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
    '<div id=topLeft onclick=indexClick()>Home</div>
    <div id=topRight onclick=loginoutClick('.$user_index.')></div>';
    if($level < 4)
    echo
    '<div id=topWrite onclick=writeClick('.$user_index.')></div>';
    echo
    '<div id=title>
        <img id=mainTitle onclick=blogClick() src="/res/blog/blog_title.png" alt="Index Page" />
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

function echoContentList($level = 4, $page = 0, $class_index = 0){
    $pageCount = getContentListCount($level, $class_index);
    $contentList = getContentList($level, $page, $class_index);

    if($pageCount % 10 == 0)
        $pageCount = ($pageCount - ($pageCount % 10)) / 10;
    else
        $pageCount = ($pageCount - ($pageCount % 10)) / 10 + 1;

    $start = ($page - ($page % 10)) / 10;

    $longBefore = $page < 10 ? 0 : $page - 10;
    $before = $page < 1 ? 0 : $page - 1;
    $after = $page + 1 >= $pageCount ? $pageCount - 1 : $page + 1;
    $longAfter = $page + 10 > $pageCount ? $pageCount - 1 : $page + 10;

    $pages = '<button class=page onClick=pageClick('.$class_index.','.$longBefore.')><<</button>';
    $pages .= '<button class=page onClick=pageClick('.$class_index.','.$before.')><</button>';
    for($i = $start; $i < $pageCount && $i < 10; $i++){
        if($i == $page)
            $pages .= '<button id=selectedPage class=page onClick=pageClick('.$class_index.','.(string)$i.')>'.($i + 1).'</button>';
        else
            $pages .= '<button class=page onClick=pageClick('.$class_index.','.(string)$i.')>'.($i + 1).'</button>';
    }
    $pages .= '<button class=page onClick=pageClick('.$class_index.','.$after.')>></button>';
    $pages .= '<button class=page onClick=pageClick('.$class_index.','.$longAfter.')>>></button>';

    $content = '';
    for($i = 0; $i < count($contentList); $i++){
        if($contentList[$i]['state'] < 0)
            $content .= '<div id=content'.$i.' class="content_ban" onclick="contentClick('.$contentList[$i]['content_index'].')">';
        else
            $content .= '<div id=content'.$i.' class="content" onclick="contentClick('.$contentList[$i]['content_index'].')">';
        $content .= 
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
                $content .=
                '<div class=content_bot><img src="'.$contentList[$i]["thumbnail"].'" class=content_thumbnail></div>';
            }
            $content .=
            '<div class=content_summary>'.
                $contentList[$i]["summary"].
            '</div>
        </div>';
    }

    return array(count($contentList), $pages, $content);
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
        '</div><hr>';
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

function echoSelectClassList($level = 4){
    $classList = getWriteClassList($level);

    for($i = 0; $i < count($classList); $i++)
        echo '<option value="'.$classList[$i]['class_index'].'">'.$classList[$i]['name'].'</option>';
}

?>
