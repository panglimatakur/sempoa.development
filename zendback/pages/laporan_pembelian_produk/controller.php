<?php defined('mainload') or die('Restricted Access'); ?>
<?php
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
	if(!empty($_REQUEST['harga']))			{ $harga 			= $sanitize->number($_REQUEST['harga']); 			}
	if(!empty($_REQUEST['stock']))			{ $stock 			= $sanitize->number($_REQUEST['stock']); 			}
	if(!empty($_REQUEST['total']))			{ $total 			= $sanitize->str($_REQUEST['total']); 				}
	if(!empty($_REQUEST['lunas']))			{ $lunas 			= $sanitize->str($_REQUEST['lunas']); 				}
	if(!empty($_REQUEST['downpay']))		{ $downpay 			= $sanitize->str($_REQUEST['downpay']); 			}
	if(!empty($_REQUEST['kredit']))			{ $kredit 			= $sanitize->str($_REQUEST['kredit']); 				}
	
	if(empty($tgl_1)){ $tgl_1_new = date("d/m/Y"); } 
	if(empty($tgl_2)){ 
		$dateformat 	= mktime(0,0,0,date("m"),date("d")+3,date("Y"));
		$tgl_1_new 		=  date("d/m/Y", $dateformat);
	} 
		
?>