<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(empty($direction) || (!empty($direction) && $direction == "show") || (!empty($direction) && $direction !="export")){
	
	$condition  = ""; 
	if(!empty($direction) && $direction == "show"){
		if(!empty($id_sale))		{ $condition 	.= " AND b.ID_PRODUCT_SALE		= '".$id_sale."'";			}
		if( !empty($tgl_1) && 
			!empty($tgl_2))			{ 
				$tgl_1_new		= $dtime->date2sysdate($tgl_1);
				$tgl_2_new		= $dtime->date2sysdate($tgl_2);
				$condition 	.= " AND a.TRANSACTION_DATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 		}
		if(!empty($faktur))			{ $condition 	.= " AND a.FACTURE_NUMBER	LIKE '%".$faktur."%'";			}

		if(!empty($marketing))		{ $condition 	.= " AND b.ID_SALES				= '".$marketing."'";		}
		if(!empty($harga))			{ $condition 	.= " AND b.PRICE				= '".$harga."'";			}
		if(!empty($keterangan))		{ $condition 	.= " AND b.NOTE 			LIKE '%".$keterangan."%'";		}
		
		if(!empty($id_kategori))	{ $condition 	.= " AND c.ID_PRODUCT_CATEGORY 	= '".$id_kategori."' "; 	}
		if(!empty($code))			{ $condition 	.= " AND c.CODE 				= '".$code."' "; 			}
		if(!empty($nama))			{ $condition 	.= " AND c.NAME				LIKE '%".$nama."%' "; 			}
		if(!empty($deskripsi))		{ $condition 	.= " AND c.DESCRIPTION 		LIKE '%".$deskripsi."%' "; 		}
	}
}
	$query_str	= "
				SELECT *,SUM(b.TOTAL) AS SUMMARY
				FROM 
					".$tpref."factures a,".$tpref."products_sales b
				WHERE 
					a.ID_FACTURE = b.ID_FACTURE AND
					a.PAID_STATUS = '3' AND
					a.ID_CLIENT		= '".$_SESSION['cidkey']."' 
					".$condition." 
				GROUP BY a.ID_FACTURE
				ORDER BY a.ID_CASH_FLOW DESC,a.FACTURE_NUMBER";
	//echo $query_str;
	$q_sale		= $db->query($query_str." ".$limit);
	$num_sale	= $db->recount($query_str);
?>
