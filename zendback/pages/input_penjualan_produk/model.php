<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if((empty($direction) && empty($msg)) || (!empty($direction) && $direction == "show") || (!empty($direction) && $direction !="export")){
	$class_multi_proses = "active";
}
$status_lunas_label = 2;
if(empty($faktur_multi)){
	$faktur_multi = $db->last("ID_PRODUCT_SALE",$tpref."products_sales"," WHERE ID_CLIENT='".$_SESSION['cidkey']."'")+1;
	$faktur_multi = "FAK".transletNum($faktur_multi);
}
if(empty($nopo_multi)){
	$nopo_multi = $db->last("ID_PRODUCT_SALE",$tpref."products_sales"," WHERE ID_CLIENT='".$_SESSION['cidkey']."'")+1;
	$nopo_multi = "PO".transletNum($nopo_multi);
}
?>
