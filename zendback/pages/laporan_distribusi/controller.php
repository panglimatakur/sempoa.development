<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($_REQUEST['id_branch']))		{ $id_branch 		= $sanitize->number($_REQUEST['id_branch']); 	}
	if(!empty($_REQUEST['keterangan']))		{ $keterangan 		= $sanitize->str($_REQUEST['keterangan']); 		}
	if(!empty($_REQUEST['shipp_direction'])){ $shipp_direction 	= $sanitize->str($_REQUEST['shipp_direction']); }
	
?>