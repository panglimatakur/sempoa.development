<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$q_member			= $db->query("SELECT * FROM ".$tpref."customers WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND CUSTOMER_STATUS != ''");

	$str_query 			= "SELECT * FROM ".$tpref."clients_testimonials WHERE ID_CLIENT='".$_SESSION['cidkey']."' ";
	$link_str			= $lparam;
	$q_partner 			= $db->query($str_query ." ".$limit);
	$num_partner		= $db->numRows($q_partner);
	
	
if(!empty($direction) && $direction == "edit"){
	if(!empty($no)){
		$q_produk_edit 	= $db->query("SELECT * FROM ".$tpref."clients_testimonials WHERE ID_CLIENT_TESTIMONIAL = '".$no."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
		$dt_produk_edit	= $db->fetchNextObject($q_produk_edit);
		@$photo_edit 	= $dt_produk_edit->PHOTO;
		@$propinsi 		= $dt_produk_edit->ID_PROVINCE;
		@$nama			= $dt_produk_edit->NAME;
		@$testimonial	= $dt_produk_edit->TESTIMONIAL;
	}
}
?>