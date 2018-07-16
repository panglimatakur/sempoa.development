<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	include $call->clas("class.html2text");
	include $call->inc("zendfront/pages/".$page,"model.php"); 
	include $call->inc("zendfront/pages/".$page,"view.php"); 
?>
