<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($parameters)){ $condition = " AND ID_POST='".$parameters."' "; }
	
	$q_statis		= $db->query("SELECT ID_POST,POST_TITLE,POST_CONTENT,ID_USER,POST_COVER,TGLUPDATE FROM ".$tpref."posts WHERE ID_USER = '1' AND ID_CLIENT ='1' AND AS_ARTICLE = '1' ".@$condition." ORDER BY ID_POST DESC LIMIT 0,10");
	//	
	$q_title		= $db->query("SELECT ID_POST,POST_TITLE,TGLUPDATE,ID_USER FROM ".$tpref."posts WHERE ID_USER = '1' AND ID_CLIENT ='1' AND AS_ARTICLE = '1' ORDER BY ID_POST DESC");	
?>