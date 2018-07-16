<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($_REQUEST['id_sale']))		{ $id_sale 	= $sanitize->number($_REQUEST['id_sale']); 					}
	if(!empty($_REQUEST['tgl_1']))			{ $tgl_1 	= $_REQUEST['tgl_1']; 										}
	if(!empty($_REQUEST['tgl_2']))			{ $tgl_2 	= $_REQUEST['tgl_2']; 										}
	
	$marketing 	= isset($_REQUEST['marketing'])	? $sanitize->number($_REQUEST['marketing']) :""; 
	$faktur 	= isset($_REQUEST['faktur']) 	? $sanitize->str($_REQUEST['faktur']) 		: "";
	$harga 		= isset($_REQUEST['harga']) 	? $sanitize->str($_REQUEST['harga']) 		: "";
	$keterangan = isset($_REQUEST['keterangan'])? $_REQUEST['keterangan']					:""; 
	
	if(!empty($_REQUEST['id_type_report']))	{ $id_type_report 	= $sanitize->number($_REQUEST['id_type_report']); 	}
	if(!empty($_REQUEST['id_kategori']))	{ $id_kategori 		= $sanitize->number($_REQUEST['id_kategori']); 		}
	if(!empty($_REQUEST['code']))			{ $code 			= $sanitize->str($_REQUEST['code']); 				}
	if(!empty($_REQUEST['nama']))			{ $nama 			= $sanitize->str($_REQUEST['nama']); 				}
	if(!empty($_REQUEST['satuan']))			{ $satuan 			= $sanitize->str($_REQUEST['satuan']); 				}
	if(!empty($_REQUEST['deskripsi']))		{ $deskripsi 		= $sanitize->str($_REQUEST['deskripsi']); 			}
	
	if(empty($tgl_1)){ $tgl_1_new = date("d/m/Y"); } 
	if(empty($tgl_2)){ 
		$dateformat 	= mktime(0,0,0,date("m"),date("d")+3,date("Y"));
		$tgl_1_new 		=  date("d/m/Y", $dateformat);
	} 
?>