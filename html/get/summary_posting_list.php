<?php

include '/var/www/php/sql_functions.php';


$user_info = GetUserInfo($_GET["user_id"], $_GET["user_pw"]);
if($user_info['state'] != 0 or $user_info['user_state'] != 0){
	$user_info['user_index'] = -1;
	$user_info['user_level'] = 4;
}

$posting_list = GetSummaryPostingList($_GET['page_index'], $_GET['page_size'], $_GET['category_index'], $user_info);
$posting_total_count = GetTotalPostingTotalCount($_GET['category_index'], $user_info);
if(count($posting_list) == 0) {
	echo json_encode(array('state'=>000, 'total_count'=>$posting_total_count, 'data'=>array()));
	return;
}

echo json_encode(array('state'=>000, 'total_count'=>$posting_total_count, 'data'=>$posting_list));

?>
