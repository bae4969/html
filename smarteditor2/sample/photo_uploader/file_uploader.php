<?php

include '/var/www/phpExe/sql_functions.php';


$user_info_row = GetUserInfo($_REQUEST['id'], $_REQUEST['pw']);
if($user_info_row['state'] != 0 or $user_info_row['user_state'] != 0) {
	return;
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
else if($_FILES['Filedata']['size'] > 5242880) /* 5MB 제한 */
	$url .= '&errstr=Ecceed_file_size';
else if($_FILES['Filedata']['size'] + $user_info_row['user_upload_byte'] > $user_info_row['user_upload_limit'])
    $url .= '&errstr=Ecceed_upload_limit';
else{
    $date = date("Ym", time()).'/';
    $uploadDir = '/var/www/html/res/upload/'.$date;
    if(!is_dir($uploadDir)) mkdir($uploadDir);

    $newPath = $uploadDir.urlencode($name);

    @move_uploaded_file($tmp_name, $newPath);

    $url .= "&bNewLine=true";
    $url .= "&sFileName=".urlencode(urlencode($name));
    $url .= "&sFileURL=/res/upload/".$date.urlencode(urlencode($name));

    /* 파일 올리고 유저의 업로드 Byte 올리기 */
    AddUserUploadByte($user_info_row, $_FILES['Filedata']['size']);
}
	
header('Location: '. $url);

?>
