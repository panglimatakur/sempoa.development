<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$str_product 	= "SELECT * FROM ".$tpref."products a, ".$tpref."products_photos b WHERE a.ID_PRODUCT='".$id_product."' AND a.ID_PRODUCT = b.ID_PRODUCT AND a.ID_STATUS != '1' AND a.ID_CLIENT='".$id_coin."'";
	//echo $str_product;
	$q_produk 		= $db->query($str_product);
	$num_produk		= $db->numRows($q_produk);
	$dt_produk		= $db->fetchNextObject($q_produk);
?>