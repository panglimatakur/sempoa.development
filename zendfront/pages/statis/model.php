<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$q_statis		= $db->query("SELECT ID_PAGE_DISCOIN,TITLE,CONTENT FROM system_pages_discoin WHERE PAGE='".$parameters."'");
	$dt_statis		= $db->fetchNextObject($q_statis);
	
	$q_title		= $db->query("SELECT ID_POST,POST_TITLE,TGLUPDATE FROM ".$tpref."posts WHERE ID_USER = '1' AND ID_CLIENT ='1' ORDER BY ID_POST DESC");	
?>