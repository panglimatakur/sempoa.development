<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$condition_comm		= "";	
	if(!empty($_REQUEST['titanium']) && $_REQUEST['titanium'] == "true"){
				$condition_comm = "AND ID_CLIENT = '1'";
	}else{		$condition_comm = "AND ID_CLIENT = '".$id_coin."'";	}
	$str_list_comm	= "SELECT DISTINCT(ID_COMMUNITY) FROM ".$tpref."communities_merchants WHERE ID_COMMUNITY IS NOT NULL ".$condition_comm." ORDER BY ID_COMMUNITY ASC";
	//echo $str_list_comm;
	$q_list_comm	= $db->query($str_list_comm);
    $j = 0;
    $q_list_merch	= $db->query($str_list_comm);
?>