<?php 
date_default_timezone_set('Asia/Jakarta');
session_start();
//session_destroy();
ob_start();
if(!defined('mainload')) { define('mainload','SEMPOA',true); }
include_once('includes/config.php');
include_once('includes/classes.php');
include $call->inc("includes/classes","class.template.php");
include_once('includes/functions.php');
include_once('includes/declarations.php');


if(!empty($_SESSION['uidkey'])){
	include $call->inc("zendback","index.php");
}else{
	if(!empty($page) && $page != "login"){
		include $call->inc("zendfront","index.php");
	}else{
		include $call->inc("zendback","index.php");
	}
}
//$db->close();
?>
