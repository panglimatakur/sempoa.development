
<?php
if(!defined('mainload')) { define('mainload','Master Web Card',true); }
include_once('../includes/config.php');
include_once('../includes/classes.php');
include_once('../includes/functions.php');
include_once('../includes/declarations.php');
	$str_query = "SELECT 
		a.ID_PRODUCT,
		a.SALE_PRICE 
	FROM 
		".$tpref."products_buys a, 
		".$tpref."products b 
	WHERE 
		a.ID_PRODUCT = b.ID_PRODUCT 
	ORDER BY 
		a.ID_PRODUCT DESC";
	$q_buy 			= $db->query($str_query);
		
	while($dt_buy	= $db->fetchNextObject($q_buy)){
		echo $dt_buy->ID_PRODUCT." = ".$dt_buy->SALE_PRICE."<br>";
		$prod_content = array(1=>
						  array("SALE_PRICE",@$dt_buy->SALE_PRICE));
		$db->update($tpref."products",$prod_content," WHERE ID_PRODUCT='".$dt_buy->ID_PRODUCT."'");
	}
?>