<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$condition = "";
if($id_client == "1"){
	if($_SESSION['uclevelkey'] == "3"){
		$deal_string	= " SELECT 
								a.BY_ID_PURPLE, 
								b.ID_COMMUNITY,
								c.*
							FROM 
								".$tpref."communities a, 
								".$tpref."communities_merchants b,
								".$tpref."client_discounts c
							WHERE 
								c.ID_DISCOUNT IS NOT NULL AND
								a.BY_ID_PURPLE = '".$_SESSION['cidkey']."' AND 
								a.ID_COMMUNITY = b.ID_COMMUNITY AND
								b.ID_CLIENT = c.ID_CLIENT AND
								c.REQUEST_BY_ID_CUSTOMER != '0'";
	}else{
		$deal_string	= " SELECT * FROM ".$tpref."client_discounts WHERE REQUEST_BY_ID_CUSTOMER != '0' ORDER BY ID_DISCOUNT";
	}
}else{
	$deal_string	= " SELECT * FROM ".$tpref."client_discounts WHERE ID_CLIENT='".$id_client."' AND REQUEST_BY_ID_CUSTOMER != '0'";
}
$q_deal 	   = $db->query($deal_string);

?>