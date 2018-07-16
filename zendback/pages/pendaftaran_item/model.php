<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(empty($direction) || (!empty($direction) && $direction != "show")){
		$class_proses = "active";
	}
	
	if(!empty($direction) && $direction == "show"){
		$class_report = "active";
	}
	$condition = "";
	if(!empty($id_type_report))		{ $condition 	.= "AND ID_PRODUCT_TYPE 	= '".$id_type_report."' "; 		}
	if(!empty($id_kategori_report))	{ $condition 	.= "AND ID_PRODUCT_CATEGORY = '".$id_kategori_report."' "; 		}
	if(!empty($code_report))		{ $condition 	.= "AND CODE 				= '".$code_report."' "; 			}
	if(!empty($nama_report))		{ $condition 	.= "AND NAME				LIKE '%".$nama_report."%' "; 		}
	if(!empty($deskripsi_report))	{ $condition 	.= "AND DESCRIPTION 		LIKE '%".$deskripsi_report."%' "; 	}

	$query_str	= "SELECT * FROM ".$tpref."products WHERE ID_CLIENT='".$_SESSION['cidkey']."' ".@$condition." ORDER BY ID_PRODUCT DESC ";
	$q_produk 	= $db->query($query_str." ".$limit);
	$num_produk	= $db->recount($query_str);


if(!empty($direction) && $direction == "edit"){
	if(!empty($no)){
		$q_produk_edit 	= $db->query("SELECT * FROM ".$tpref."products WHERE ID_PRODUCT = '".$no."' AND ID_CLIENT='".$_SESSION['cidkey']."' ORDER BY ID_PRODUCT DESC");
		$dt_produk_edit	= $db->fetchNextObject($q_produk_edit);
		@$id_type 		= $dt_produk_edit->ID_PRODUCT_TYPE;
		@$id_kategori 	= $dt_produk_edit->ID_PRODUCT_CATEGORY;
		@$code 			= $dt_produk_edit->CODE;
		@$nama			= $dt_produk_edit->NAME;
		@$harga			= $dt_produk_edit->SALE_PRICE;
		@$diskon		= $dt_produk_edit->DISCOUNT;
		@$deskripsi		= $dt_produk_edit->DESCRIPTION;
		@$satuan		= $dt_produk_edit->ID_PRODUCT_UNIT;
	}
}

@$code_random 	= generateString(5);
?>