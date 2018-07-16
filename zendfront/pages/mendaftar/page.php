<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	include $call->inc("zendfront/pages/".$page,"controller.php"); 
	include $call->inc("zendfront/pages/".$page,"model.php"); 
	include $call->inc("zendfront/pages/".$page,"view.php"); 
?>
