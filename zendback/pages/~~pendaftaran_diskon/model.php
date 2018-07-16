<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$nm_merchant	 	= $db->fob("CLIENT_NAME",$tpref."clients"," WHERE ID_CLIENT = '".$_SESSION['cidkey']."'");

$q_discount_pattern = $db->query("SELECT ID_DISCOUNT_PATTERN,DESCRIPTION FROM ".$tpref."discount_patterns ORDER BY ID_DISCOUNT_PATTERN ASC");

$q_diskon_list 	= $db->query("SELECT * FROM ".$tpref."clients_discounts WHERE ID_CLIENT = '".$_SESSION['cidkey']."' ");

if(!empty($direction) && $direction == "edit"){
	$q_diskon 	= $db->query("SELECT * FROM ".$tpref."clients_discounts WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND ID_DISCOUNT = '".$id_diskon."'");
	$dt_diskon 	= $db->fetchNextObject($q_diskon);
	$formember  = $dt_diskon->DISCOUNT_SEGMENT;
	$besar		= $dt_diskon->DISCOUNT; 
	if($dt_diskon->EXPIRATION_DATE != "0000-00-00"){
		$expiration	= $dtime->date2indodate($dt_diskon->EXPIRATION_DATE);
	}
	$keterangan	= $dt_diskon->DISCOUNT_STATEMENT;
	$pattern	= $dt_diskon->ID_DISCOUNT_PATTERN;
	$nilai		= $dt_diskon->DISCOUNT_VALUE;
	$sifat_jual	= $dt_diskon->SELLING_METHOD; 
	@$num_kupon	= $dt_diskon->SELLING_METHOD_PO_COUPON_QTY; 
	@$harga_kupon= $dt_diskon->SELLING_METHOD_PO_COUPON_PRICE;
	
}
?>