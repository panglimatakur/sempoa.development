<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($direction) && $direction == "edit"){
		$qcont				=	$db->query("SELECT * FROM ".$tpref."cash_flow WHERE ID_CASH_FLOW='".$no."' ");
		$dtedit				=	$db->fetchNextObject($qcont);
		$nilai				=	$dtedit->CASH_VALUE;
		@$nama_parent		=	$db->fob("NAME",$tpref."cash_type","WHERE ID_CASH_TYPE='".$dtedit->ID_CASH_TYPE."'");
	}
?>
