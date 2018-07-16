<?php defined('mainload') or die('Restricted Access'); 	?>
<?php include $call->func("function.paging"); ?>
<?php include($page_dir."/controller.php"); ?>
<?php include($page_dir."/model.php"); ?>
<?php include($page_dir."/view.php"); ?>
<?php include $call->lib("table2excel");