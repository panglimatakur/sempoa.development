<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($_REQUEST['periode']))	{ $periode 	= $sanitize->str($_REQUEST['periode']); 		}
	if(!empty($_REQUEST['bln']))		{ $bln 		= $sanitize->str($_REQUEST['bln']); 			}
	if(!empty($_REQUEST['thn']))		{ $thn 		= $sanitize->str($_REQUEST['thn']); 			}
	if(!empty($_REQUEST['thn2']))		{ $thn2 	= $sanitize->str($_REQUEST['thn2']); 			}
?>