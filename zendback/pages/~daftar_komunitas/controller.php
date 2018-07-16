<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(isset($_REQUEST['show'])) 		{	$show 		= $sanitize->str($_REQUEST['show']); 			}
if(isset($_REQUEST['id_com'])) 		{	$id_com 	= $sanitize->number($_REQUEST['id_com']); 		}

?>