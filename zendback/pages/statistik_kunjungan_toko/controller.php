<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($_REQUEST['cur_bulan']))		{ $cur_bulan 	= $sanitize->number($_REQUEST['cur_bulan']); 		}
	if(!empty($_REQUEST['cur_tahun']))		{ $cur_tahun 	= $sanitize->number($_REQUEST['cur_tahun']); 		}
?>