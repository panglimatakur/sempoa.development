<?php
//if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {

	$callback 		= 'mycallback';
	$callback 		= isset($_REQUEST['mycallback']) 		? $_REQUEST['mycallback'] 		: "";
	$result['content'] = "Berhasil";
	$result['content'] .= "Sangat Lah";
	echo json_encode($result);

//}
?>