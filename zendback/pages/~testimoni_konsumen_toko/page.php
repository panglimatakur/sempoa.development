<?php defined('mainload') or die('Restricted Access'); 	?>
<?php include $call->clas("class.fileanddirectory"); 	?>
<?php include $call->func("function.paging"); ?>

<?php include $call->inc($page_dir,"controller.php"); 	?>
<?php include $call->inc($page_dir,"model.php"); 		?>
<?php include $call->inc($page_dir,"view.php");	 		?>
<?php include $call->lib("validation"); 				?>
