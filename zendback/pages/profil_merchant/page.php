<?php defined('mainload') or die('Restricted Access'); ?>
<?php include $call->clas('class.fileanddirectory'); ?>
<?php include $call->lib("qrgenerator"); ?>
<?php
	include $call->inc($page_dir,"controller.php"); 
	include $call->inc($page_dir,"model.php"); 
	include $call->inc($page_dir,"view.php"); 
?>
<?php include $call->lib("validation"); ?>