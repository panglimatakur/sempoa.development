<?php
	session_start();
	$callback 			= 'mycallback';
	$callback 			= isset($_REQUEST['mycallback']) 	? $_REQUEST['mycallback'] 	: "";
	$result['session']	= "alive";
	$result['msg']		= "alive";
	if(empty($_SESSION['sidkey'])){
		$result['session']	= "dead";
	}
	echo $callback.'('.json_encode($result).')';
?>
