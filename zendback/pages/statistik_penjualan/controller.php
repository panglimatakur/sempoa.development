<?php defined('mainload') or die('Restricted Access'); ?>
<?php	
	if(!empty($_REQUEST['periode']))		{ $periode 		= $sanitize->str($_REQUEST['periode']); 		}
	if(!empty($_REQUEST['bln']))			{ $bln 			= $sanitize->str($_REQUEST['bln']); 			}
	if(!empty($_REQUEST['thn']))			{ $thn 			= $sanitize->str($_REQUEST['thn']); 			}
	if(!empty($_REQUEST['thn2']))			{ $thn2 		= $sanitize->str($_REQUEST['thn2']); 			}
	
	if(!empty($_REQUEST['harga']))			{ $harga 		= $sanitize->str($_POST['harga']); 				}
	if(!empty($_REQUEST['diskon']))			{ $diskon 		= $sanitize->str($_POST['diskon']); 			}
	if(!empty($_REQUEST['lunas']))			{ $lunas 		= $_REQUEST['lunas'];							} 
	
	$propinsi 	= isset($_REQUEST['propinsi']) 		? $sanitize->number($_REQUEST['propinsi'])		:""; 
	$kota 		= isset($_REQUEST['kota']) 			? $sanitize->number($_REQUEST['kota'])			:""; 
	$kecamatan 	= isset($_REQUEST['kecamatan']) 	? $sanitize->number($_REQUEST['kecamatan'])		:""; 
	$kelurahan 	= isset($_REQUEST['kelurahan']) 	? $sanitize->number($_REQUEST['kelurahan'])		:""; 

	if(!empty($_REQUEST['id_type_report']))	{ $id_type_report 	= $sanitize->number($_REQUEST['id_type_report']); 		}
	if(!empty($_REQUEST['id_kategori']))	{ $id_kategori 		= $sanitize->number($_REQUEST['id_kategori']); 			}
	if(!empty($_REQUEST['code']))			{ $code 			= $sanitize->str($_REQUEST['code']); 					}
?>