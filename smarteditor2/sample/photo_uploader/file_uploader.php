<?php

include '/var/www/phpExe/const.php';

function checkUser($id, $pw){
    include '/var/www/phpExe/sqlcon.php';
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

function checkUserCanUploadImg($user){
    include "/var/www/phpExe/const.php";

    if($user['user_index'] < 1) return false;
    else if($user['user_index'] == 1) return true;
    else if($user['img_upload_limit'] < $img_total_limit) return true;

    return false;
}

function updateLoadFileLimit($user_index, $volume){
    include "/var/www/phpExe/sqlcon.php";
    
    if($user_index < 1) return -1;

    $conn = mysqli_connect( $sqlAddr, $sqlId, $sqlPw, $sqlBlogDb );
    $sql_query = "update user_list set img_upload_limit=img_upload_limit+".$volume.
        " where user_index=".$user_index;

    if(mysqli_query($conn, $sql_query)){
        return 1;
    }
    
    return 0;
}


$user = checkUser($_REQUEST['id'], $_REQUEST['pw']);
if(!checkUserCanUploadImg($user)){
	$url .= '&errstr=user_check_error';
	header('Location: '. $url);
}

$url = 'callback.html?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);

if(!$bSuccessUpload){
	$url .= '&errstr=upload_error';
	header('Location: '. $url);
}

$tmp_name = $_FILES['Filedata']['tmp_name'];
$name = $_FILES['Filedata']['name'];
$filename_ext = strtolower(array_pop(explode('.',$name)));
$name = date("dHis", time()).'_'.$user['user_index'].'.'.$filename_ext;
$allow_file = array("jpg", "png", "bmp", "gif");

if(!in_array($filename_ext, $allow_file))
	$url .= '&errstr='.$name;
else if($_FILES['Filedata']['size'] > $img_single_limit)
	$url .= '&errstr=file_size_error';
else {
	$date = date("Ym", time()).'/';
	$uploadDir = '/var/www/html/res/upload/'.$date;
	if(!is_dir($uploadDir)) mkdir($uploadDir);
	
	$newPath = $uploadDir.urlencode($name);
	
	@move_uploaded_file($tmp_name, $newPath);
	
	$url .= "&bNewLine=true";
	$url .= "&sFileName=".urlencode(urlencode($name));
	$url .= "&sFileURL=/res/upload/".$date.urlencode(urlencode($name));
	updateLoadFileLimit($user['user_index'], $_FILES['Filedata']['size']);
}
	
header('Location: '. $url);
?>