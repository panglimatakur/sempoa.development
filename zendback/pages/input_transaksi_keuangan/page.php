<?php defined('mainload') or die('Restricted Access'); ?>
<?php include $call->lib('treeview'); ?>
<?php include $call->lib('validation'); ?>
<?php
	include $call->inc($page_dir,"controller.php"); 
	include $call->inc($page_dir,"model.php"); 
	include $call->inc($page_dir,"view.php"); 
?>
<?php include $call->lib("fancybox"); 					?>
