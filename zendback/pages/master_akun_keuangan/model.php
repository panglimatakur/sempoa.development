<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($direction) && $direction == "edit"){
		$qcont				=	$db->query("SELECT * FROM ".$tpref."cash_type WHERE ID_CASH_TYPE='".$no."' ");
		$dtedit				=	$db->fetchNextObject($qcont);
		$parent_id			=	$dtedit->ID_PARENT;
		$is_folder			=  	$dtedit->IS_FOLDER;
		$nama				=	$dtedit->NAME;
		$contenttype		= 	$dtedit->IN_OUT;
		@$nama_parent		=	$db->fob("NAME",$tpref."cash_type","WHERE ID_CASH_TYPE='".$parent_id."'");
	}
?>
