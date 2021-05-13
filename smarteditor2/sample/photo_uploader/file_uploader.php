<?php
include '../../../php/blog.php';
include '../../../php/const.php';

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