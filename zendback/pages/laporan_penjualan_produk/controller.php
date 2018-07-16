<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($_REQUEST['tgl_1']))			{ $tgl_1 	= $_REQUEST['tgl_1']; 								}
	if(!empty($_REQUEST['tgl_2']))			{ $tgl_2 	= $_REQUEST['tgl_2']; 								}
	if(!empty($_REQUEST['customer']))		{ $customer = $sanitize->number($_REQUEST['customer']); 		}
	$marketing 	= isset($_REQUEST['marketing'])	? $sanitize->number($_REQUEST['marketing']) :""; 
	$faktur 	= isset($_POST['faktur']) 		? $sanitize->str($_POST['faktur']) 			: "";
	$harga 		= isset($_POST['harga']) 		? $sanitize->str($_POST['harga']) 			: "";
	$jual 		= isset($_POST['jual']) 		? $sanitize->str($_POST['jual']) 			: "";
	$diskon 	= isset($_POST['diskon']) 		? $sanitize->str($_POST['diskon']) 			: "";
	$total_jual = isset($_POST['total_jual']) 	? $sanitize->str($_POST['total_jual']) 		: "";
	$keterangan = isset($_REQUEST['keterangan'])? $_REQUEST['keterangan']					:""; 
	$lunas 		= isset($_REQUEST['lunas'])		? $_REQUEST['lunas']						:""; 
	
	if(!empty($_REQUEST['id_type_report']))	{ $id_type_report 	= $sanitize->number($_REQUEST['id_type_report']); 	}
	if(!empty($_REQUEST['id_kategori']))	{ $id_kategori 		= $sanitize->number($_REQUEST['id_kategori']); 	}
	if(!empty($_REQUEST['code']))			{ $code 			= $sanitize->str($_REQUEST['code']); 			}
	if(!empty($_REQUEST['nama']))			{ $nama 			= $sanitize->str($_REQUEST['nama']); 			}
	if(!empty($_REQUEST['satuan']))			{ $satuan 			= $sanitize->str($_REQUEST['satuan']); 			}
	if(!empty($_REQUEST['deskripsi']))		{ $deskripsi 		= $sanitize->str($_REQUEST['deskripsi']); 		}
	
	if(empty($tgl_1)){ $tgl_1_new = date("d/m/Y"); } 
	if(empty($tgl_2)){ 
		$dateformat 	= mktime(0,0,0,date("m"),date("d")+3,date("Y"));
		$tgl_1_new 		=  date("d/m/Y", $dateformat);
	} 
?>