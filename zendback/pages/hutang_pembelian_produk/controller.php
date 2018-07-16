<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($_REQUEST['id_buy']))			{ $id_buy 			= $sanitize->number($_REQUEST['id_buy']); 			}
	if(!empty($_REQUEST['id_type_report']))	{ $id_type_report 	= $sanitize->number($_REQUEST['id_type_report']); 	}
	if(!empty($_REQUEST['id_kategori']))	{ $id_kategori 		= $sanitize->number($_REQUEST['id_kategori']); 		}
	if(!empty($_REQUEST['code']))			{ $code 			= $sanitize->str($_REQUEST['code']); 				}
	if(!empty($_REQUEST['nama']))			{ $nama 			= $sanitize->str($_REQUEST['nama']); 				}
	if(!empty($_REQUEST['deskripsi']))		{ $deskripsi 		= $sanitize->str($_REQUEST['deskripsi']); 			}

	if(!empty($_REQUEST['tgl_1']))			{ $tgl_1 			= $_REQUEST['tgl_1']; 								}
	if(!empty($_REQUEST['tgl_2']))			{ $tgl_2 			= $_REQUEST['tgl_2']; 								}
	if(!empty($_REQUEST['id_product']))		{ $id_product 		= $sanitize->number($_REQUEST['id_product']); 		}
	if(!empty($_REQUEST['faktur']))			{ $faktur 			= $sanitize->str($_REQUEST['faktur']); 				}
	if(!empty($_REQUEST['harga_pokok']))	{ $harga_pokok 		= $sanitize->number($_REQUEST['harga_pokok']); 		}
	
	if(empty($tgl_1)){ $tgl_1_new = date("d/m/Y"); } 
	if(empty($tgl_2)){ 
		$dateformat 	= mktime(0,0,0,date("m"),date("d")+3,date("Y"));
		$tgl_1_new 		=  date("d/m/Y", $dateformat);
	} 
		
?>