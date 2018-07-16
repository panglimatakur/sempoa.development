<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(!empty($_REQUEST['search'])) 		{ $search 		= $sanitize->str($_REQUEST['search']); 		}
if(!empty($_REQUEST['propinsi'])) 		{ $propinsi 	= $sanitize->number($_REQUEST['propinsi']); }
if(!empty($_REQUEST['kota'])) 			{ $kota 		= $sanitize->number($_REQUEST['kota']); 	}

if(!empty($direction) && $direction == "search"){

} 
?>
