<?php defined('mainload') or die('Restricted Access'); ?>
<?php
function discount($id_merchant){
	global $db;
	global $tpref;
	$discount = $db->fob("VALUE",$tpref."client_discounts"," WHERE ID_DISCOUNT_PATTERN = '1' AND COMMUNITY_FLAG = '1'");	
	if($_SESSION['cidkey'] == $id_merchant){
		$discount = $db->fob("VALUE",$tpref."client_discounts"," WHERE ID_DISCOUNT_PATTERN = '1' AND CUSTOMER_FLAG = '1'");	
	}
	return $discount;
}


$condition	= "";
if(!empty($statlun)){
	$condition = " AND  PAID_STATUS 	= '".$statlun."'";
}else{
	$condition = " AND PAID_STATUS = '0'";	
	$statlun	= '0';
}
	$pur_string   	= "SELECT * FROM ".$tpref."customers_purchases WHERE ID_PURCHASE IS NOT NULL ".$condition." ORDER BY ID_PURCHASE ASC";
	//echo $pur_string;
	$q_purchase 	= $db->query($pur_string);
	$num_pur	   = $db->numRows($q_purchase);

?>