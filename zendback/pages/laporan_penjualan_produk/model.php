<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if($_SESSION['uclevelkey'] == 2){
		$notification_type_ids = array(4,5);
		update_notification($_SESSION['cidkey'],$notification_type_ids);
	}

if(empty($direction) || (!empty($direction) && $direction == "show") || (!empty($direction) && $direction !="export")){
	
	$condition  = ""; 
	if(!empty($direction) && $direction == "show"){
		if( !empty($tgl_1) && 
			!empty($tgl_2))			{ 
				$tgl_1_new		= $dtime->date2sysdate($tgl_1);
				$tgl_2_new		= $dtime->date2sysdate($tgl_2);
				$condition 	.= " AND a.TRANSACTION_DATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 		}
		if(!empty($faktur))			{ $condition 	.= " AND a.FACTURE_NUMBER	LIKE '%".$faktur."%'";			}
		if(!empty($keterangan))		{ $condition 	.= " AND a.NOTE 	LIKE '%".$keterangan."%'";				}
		if(!empty($lunas))			{ $condition 	.= " AND a.PAID_STATUS 			= '".$lunas."' "; 			}
		if(!empty($marketing))		{ $condition 	.= " AND a.ID_SALES				= '".$marketing."'";		}
		if(!empty($customer))		{ $condition 	.= " AND a.ID_CUSTOMER			= '".$customer."'";			}

		if(!empty($harga))			{ $condition 	.= " AND b.PRICE				= '".$harga."'";			}
		if(!empty($jual))			{ $condition 	.= " AND b.QUANTITY  			= '".$jual."'";				}
		if(!empty($diskon))			{ $condition 	.= " AND b.DISCOUNT 			= '".$diskon."'";			}
		if(!empty($total_jual))		{ $condition 	.= " AND b.TOTAL 				= '".$total_jual."'";		}
		
		if(!empty($id_type_report))	{ $condition 	.= " AND c.ID_PRODUCT_TYPE 		= '".$id_type_report."' "; 	}
		if(!empty($id_kategori))	{ $condition 	.= " AND c.ID_PRODUCT_CATEGORY 	= '".$id_kategori."' "; 	}
		if(!empty($code))			{ $condition 	.= " AND c.CODE 				= '".$code."' "; 			}
		if(!empty($nama))			{ $condition 	.= " AND c.NAME				LIKE '%".$nama."%' "; 			}
		if(!empty($deskripsi))		{ $condition 	.= " AND c.DESCRIPTION 		LIKE '%".$deskripsi."%' "; 		}
	}
}
	$query_str	= "
				SELECT 	
					*,SUM(b.TOTAL) AS SUMMARY,SUM(b.QUANTITY) AS JML,a.REMAIN AS PIUTANG  
				FROM 
					".$tpref."factures a ,".$tpref."products_sales b,".$tpref."products c
				WHERE 
					a.ID_FACTURE = b.ID_FACTURE AND 
					b.ID_PRODUCT = c.ID_PRODUCT AND 
					a.MODULE = 'SALE' AND
					(a.ID_CLIENT='".$_SESSION['cidkey']."' ".parent_condition($_SESSION['cidkey'],"a.").") 
					".$condition." 
					GROUP BY b.ID_FACTURE
					ORDER BY a.ID_CASH_FLOW DESC,a.FACTURE_NUMBER";
	//echo $query_str;
	$q_sale		= $db->query($query_str." ".$limit);
	$num_sale	= $db->recount($query_str);
?>
