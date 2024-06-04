<?php

/* id, pw를 받아 유저 정보를 연관배열 형식으로 받는 함수 */
function GetUserInfo($user_id, $user_pw){
	include "sql_connection_info.php";


    if($user_id == '' || $user_pw == '')
        return array("state"=>100);	/* 입력 오류 */
    if(strpos($user_id, '#') !== false || strpos($user_id, '/*') !== false)
        return array("state"=>444);	/* injection 방지 */

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $conn->set_charset("utf8mb4");
    $sql_query = 'select * from user_list where user_id="'.$user_id.'" and user_pw="'.$user_pw.'"';
    $result = mysqli_query($conn, $sql_query);

    if($row = mysqli_fetch_assoc($result)){
		$row['state'] = 0;
        return $row;				/* 성공적인 결과 출력 */
	}
	else
		return array('state'=>100);	/* 일치하는 것 없음 */
}

/* user_index를 받아 해당 index의 마지막 활동일을 최신화 */
function UpdateUserLastActionDatetime($user_info){
	include "sql_connection_info.php";


    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $conn->set_charset("utf8mb4");
    $sql_query = "update user_list set user_last_action_datetime=NOW() where user_index=".$user_info['user_index'];
    $result = mysqli_query($conn, $sql_query);
	
    if(mysqli_query($conn, $sql_query))
        return array('state'=>0);
	else
		return array('state'=>100);
}

/* user 글쓰기 카운트 추가 */
function AddUserWriteCount($user_info){
	include "sql_connection_info.php";


    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $conn->set_charset("utf8mb4");
    $sql_query = "update user_list set user_posting_count=user_posting_count+1 where user_index=".$user_info['user_index'];
    $result = mysqli_query($conn, $sql_query);
	
    if(mysqli_query($conn, $sql_query))
        return array('state'=>0);
	else
		return array('state'=>100);
}

/************************************************************************************************************/

/* user_level에 따른 카테고리 리스트 반환 */
function GetCategoryListByReadLevel($user_level){
	include "sql_connection_info.php";


    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $conn->set_charset("utf8mb4");
    $sql_query = 'select * from category_list where category_read_level>='.$user_level.' order by category_order asc';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $category_list = array();
    $category_list[] = array('category_index'=>-1, 'category_name'=>'전체 보기');
    while($row = mysqli_fetch_assoc($result))
        $category_list[] = $row;
        
    return $category_list;
}

/* user_level에 따른 카테고리 리스트 반환 */
function GetCategoryListByWriteLevel($user_level){
	include "sql_connection_info.php";


    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $conn->set_charset("utf8mb4");
    $sql_query = 'select * from category_list where category_write_level>='.$user_level.' order by category_order asc';
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $category_list = array();
    $category_list[] = array('category_index'=>-1, 'category_name'=>'전체 보기');
    while($row = mysqli_fetch_assoc($result))
        $category_list[] = $row;
        
    return $category_list;
}

/* user_level에 따른 카테고리 리스트 반환 */
function GetCategoryInfo($category_index){
	include "sql_connection_info.php";


    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $conn->set_charset("utf8mb4");
    $sql_query = 'select * from category_list where category_index='.$category_index;
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);


    if($row = mysqli_fetch_assoc($result)){
		$row['state'] = 0;
        return $row;				/* 성공적인 결과 출력 */
	}
	else
		return array('state'=>100);	/* 일치하는 것 없음 */
}

/************************************************************************************************************/

/* user_level, category_index에 따른 posting 갯수 반환 */
function GetTotalPostingTotalCount($category_index, $user_info){
	include "sql_connection_info.php";


    $conn = mysqli_connect($sqlAddr, $sqlId, $sqlPw, $sqlBlogDb);
    $conn->set_charset("utf8mb4");
    $sql_query = 'SELECT COUNT(*) ';
	$sql_query .= 'FROM posting_list AS P ';
	$sql_query .= 'JOIN category_list AS C ';
    $sql_query .= 'ON P.category_index = C.category_index ';
    $sql_query .= 'WHERE C.category_read_level>='.$user_info['user_level'].' ';
	if($category_index >= 0)
		$sql_query .= 'AND P.category_index='.$category_index.' ';
    if($search_string != '' and strlen($search_string) > 1)
        $sql_query .= 'AND P.posting_title LIKE "%'.$search_string.'%" ';
    if($user_info['user_level'] > 1) {
        if($user_info['user_index'] < 0)
            $sql_query .= 'AND P.posting_state=0 ';
        else
            $sql_query .= 'AND (P.posting_state=0 or P.user_index='.$user_info['user_index'].') ';
    }
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    if($row = mysqli_fetch_assoc($result))
        return $row['COUNT(*)'];
}

/* user_level, category_index에 따른 summary posting 리스트 반환 */
function GetSummaryPostingList($user_info, $category_index, $search_string, $page_index, $page_size){
	include "sql_connection_info.php";


    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $conn->set_charset("utf8mb4");
    $sql_query = 'SELECT ';
	$sql_query .= 'P.posting_index, P.user_index, P.category_index, C.category_read_level, P.posting_state, ';
	$sql_query .= 'P.posting_first_post_datetime, P.posting_last_edit_datetime, ';
	$sql_query .= 'P.posting_title, posting_thumbnail, P.posting_summary ';
	$sql_query .= 'FROM posting_list AS P ';
	$sql_query .= 'JOIN category_list AS C ';
    $sql_query .= 'ON P.category_index = C.category_index ';
    $sql_query .= 'WHERE C.category_read_level>='.$user_info['user_level'].' ';
	if($category_index >= 0)
		$sql_query .= 'AND P.category_index='.$category_index.' ';
    if($search_string != '' and strlen($search_string) > 1)
        $sql_query .= 'AND P.posting_title LIKE "%'.$search_string.'%" ';
    if($user_info['user_level'] > 1) {
        if($user_info['user_index'] < 0)
            $sql_query .= 'AND P.posting_state=0 ';
        else
            $sql_query .= 'AND (P.posting_state=0 or P.user_index='.$user_info['user_index'].') ';
    }   
    $sql_query .= 'order by posting_index desc limit '.($page_index * $page_size).', '.$page_size;
    $result = mysqli_query($conn, $sql_query);
    mysqli_close($conn);

    $summary_posting_list = array();
    while($row = mysqli_fetch_assoc($result))
        $summary_posting_list[] = $row;
        
    return $summary_posting_list;
}

/* posting index를 받아 posting 풀 정보를 연관배열 형식으로 받는 함수 */
function GetPostingInfo($posting_index){
	include "sql_connection_info.php";


    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $conn->set_charset("utf8mb4");
    $sql_query = 'select ';
	$sql_query .= 'P.posting_index, P.user_index, P.category_index, C.category_read_level, P.posting_state, ';
	$sql_query .= 'P.posting_first_post_datetime, P.posting_last_edit_datetime ';
	$sql_query .= 'FROM posting_list as P ';
	$sql_query .= 'JOIN category_list as C ';
    $sql_query .= 'ON P.category_index = C.category_index ';
    $sql_query .= 'where P.posting_index="'.$posting_index.'"';
    $result = mysqli_query($conn, $sql_query);

    if($row = mysqli_fetch_assoc($result)){
		$row['state'] = 0;
        return $row;				/* 성공적인 결과 출력 */
	}
	else
		return array('state'=>100);	/* 일치하는 것 없음 */
}

/* posting index를 받아 posting 풀 정보를 연관배열 형식으로 받는 함수 */
function GetFullPosting($posting_index, $user_info){
	include "sql_connection_info.php";


    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $conn->set_charset("utf8mb4");
    $sql_query = 'select ';
	$sql_query .= 'P.posting_index, P.user_index, P.category_index, P.posting_state, ';
	$sql_query .= 'P.posting_first_post_datetime, P.posting_last_edit_datetime, ';
	$sql_query .= 'P.posting_title, P.posting_content ';
	$sql_query .= 'FROM posting_list as P ';
	$sql_query .= 'JOIN category_list as C ';
    $sql_query .= 'ON P.category_index = C.category_index ';
    $sql_query .= 'where C.category_read_level>='.$user_info['user_level'].' and P.posting_index='.$posting_index.' ';
    if($user_info['user_level'] > 1) {
        if($user_info['user_index'] < 0)
            $sql_query .= 'and P.posting_state=0 ';
        else
            $sql_query .= 'and (P.posting_state=0 or P.user_index='.$user_info['user_index'].') ';
    }   
    $result = mysqli_query($conn, $sql_query);

    if($row = mysqli_fetch_assoc($result)){
		$row['state'] = 0;
        return $row;				/* 성공적인 결과 출력 */
	}
	else
		return array('state'=>100);	/* 일치하는 것 없음 */
}

/* posting index를 받아 posting를 활성화 상태로 변경하는 함수 */
function EnablePosting($posting_index){
	include "sql_connection_info.php";


    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $conn->set_charset("utf8mb4");
    $sql_query = 'update posting_list set posting_state=0 where posting_index='.$posting_index;
    if(mysqli_query($conn, $sql_query))
        return 1;
    
    return 0;
}

/* posting index를 받아 posting를 비활성화 상태로 변경하는 함수 */
function DisablePosting($posting_index){
	include "sql_connection_info.php";


    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $conn->set_charset("utf8mb4");
    $sql_query = 'update posting_list set posting_state=1 where posting_index='.$posting_index;
    if(mysqli_query($conn, $sql_query))
        return 1;
    
    return 0;
}

/* 글쓰기 데이터가 문제 없는지 검사 */
function VerifyPostingData($title, $thumbnail, $summary, $content){
    if(strlen($title) > 255) return -2;
    if(strlen($thumbnail) > 255) return -3;
    if(strlen($summary) > 65535) return -4;
    if(strlen($content) > 16777215) return -5;

    $filter = array('<script');
    for($i = 0; $i < count($filter); $i++) if(stripos($title, $filter[$i]) !== false) return -6;
    for($i = 0; $i < count($filter); $i++) if(stripos($thumbnail, $filter[$i]) !== false) return -6;
    for($i = 0; $i < count($filter); $i++) if(stripos($summary, $filter[$i]) !== false) return -6;
    for($i = 0; $i < count($filter); $i++) if(stripos($content, $filter[$i]) !== false) return -6;

    return 0;
}

/* 글 추가 */
function AddPosting($user_info, $category_info, $title, $thumbnail, $summary, $content) {
	include "sql_connection_info.php";


    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $conn->set_charset("utf8mb4");
    $sql_query
        = "insert into posting_list(user_index, category_index, posting_title, posting_thumbnail, posting_summary, posting_content) value(".
        $user_info['user_index'].",".$category_info['category_index'].",'".addslashes($title)."','".addslashes($thumbnail)."','".addslashes($summary)."','".addslashes($content)."')";
    if(mysqli_query($conn, $sql_query)){
        $sql_query = 'SELECT LAST_INSERT_ID()';
        $result = mysqli_query($conn, $sql_query);
        mysqli_close($conn);
        if($row = mysqli_fetch_assoc($result)){
            $ret = $row['LAST_INSERT_ID()'];
            return $ret;
        }
    }

    return -1;
}

/* 글 수정 */
function FixPosting($posting_index, $title, $thumbnail, $summary, $content) {
	include "sql_connection_info.php";


    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $conn->set_charset("utf8mb4");
    $sql_query = "update posting_list set ".
        "posting_title='".addslashes($title)."', ".
        "posting_thumbnail='".addslashes($thumbnail)."', ".
        "posting_summary='".addslashes($summary)."', ".
        "posting_content='".addslashes($content)."' ".
        "where posting_index=".$posting_index;
    if(mysqli_query($conn, $sql_query)){
        return 0;
    }
    
    return -1;
}

?>
