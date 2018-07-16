<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$notification_type_ids = array(7,8,9,10,11,12);
	update_notification($_SESSION['cidkey'],$notification_type_ids);
	
	$condition  = ""; 
	if(substr_count($page,"laporan") == 1){
		$display 	= ''; 
	}else{
		$display 	= 'display:none;'; 
	}
	if($_SESSION['uclevelkey'] != 2){
		$condition 	.= " AND ID_BRANCH			= '".$_SESSION['cidkey']."'";
	}else{
		$condition 	.= " AND ID_DISTRIBUTION_STATUS != '5'";
	}
	if(!empty($direction) && $direction == "show"){
		if(!empty($id_branch))		{ $condition 	.= " AND ID_BRANCH			= '".$id_branch."'";				}
		if(!empty($keterangan))		{ $condition 	.= " AND DESCRIPTION 		LIKE '%".$keterangan."%'";			}
		if(!empty($shipp_direction)){ $condition 	.= " AND ID_DISTRIBUTION_STATUS = '".$shipp_direction."'";		}
	}
	$str_shipping_branch	= 
	"SELECT *
	FROM 
		".$tpref."products_distributions
	WHERE 
		(ID_BRANCH IS NOT NULL OR ID_BRANCH != '0')
		".$condition."
	ORDER BY 
		ID_PRODUCT_DISTRIBUTION ASC";
		//echo $str_shipping_branch;
	$q_shipping_branch 		= $db->query($str_shipping_branch." ".$limit);
	$num_shipping_branch	= $db->recount($str_shipping_branch);
?>