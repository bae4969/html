<?php
$url = 'callback.html?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);

if($bSuccessUpload) {
	$tmp_name = $_FILES['Filedata']['tmp_name'];
	$name = $_FILES['Filedata']['name'];
	$filename_ext = strtolower(array_pop(explode('.',$name)));
	$allow_file = array("jpg", "png", "bmp", "gif");
	
	if(!in_array($filename_ext, $allow_file))
		$url .= '&errstr='.$name;
	else if($_FILES['Filedata']['size'] > 5242880)
		$url .= '&errstr=file_size_error';
	else {
		$uploadDir = '/var/www/html/res/upload/';
		if(!is_dir($uploadDir)) mkdir($uploadDir);
		
		$newPath = $uploadDir.urlencode($name);
		
		@move_uploaded_file($tmp_name, $newPath);
		
		$url .= "&bNewLine=true";
		$url .= "&sFileName=".urlencode(urlencode($name));
		$url .= "&sFileURL=/res/upload/".urlencode(urlencode($name));
	}
}
else {
	$url .= '&errstr=error';
}
	
header('Location: '. $url);
?>