<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$query_branch 	= $db->query("SELECT ID_CLIENT,CLIENT_NAME FROM ".$tpref."clients WHERE ID_CLIENT != '1' AND CLIENT_ID_PARENT != '1' ORDER BY CLIENT_NAME");

if(empty($id_merchant) && $_SESSION['uclevelkey'] != 1){
	$id_merchant = $_SESSION['cidkey'];
}
$condition	= "";
if(!empty($statlun)){
	$condition .= " AND a.STATUS 	= '".$statlun."'";
}else{
	$condition .= " AND a.STATUS = '3'";	
}

if(!empty($id_merchant)){
	$condition .= " AND a.ID_CLIENT = '".$id_merchant."'";	
}

	$cart_cust   	= "SELECT 
							b.*,a.ID_CLIENT,a.STATUS
					   FROM 
					   		".$tpref."customers_carts a,".$tpref."customers b
					   WHERE 
					   		a.ID_CUSTOMER = b.ID_CUSTOMER 
							".$condition." 
					   GROUP BY 
					   		a.ID_CUSTOMER 
					   ORDER BY 
					   		a.ID_CART ASC";
	//echo $cart_cust;
	$q_cart_cust 	= $db->query($cart_cust);
	$num_cart_cust  = $db->numRows($q_cart_cust);

?>