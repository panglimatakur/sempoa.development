<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(empty($direction) || (!empty($direction) && $direction == "show") || (!empty($direction) && $direction !="export")){
	$condition  = ""; 
	if(!empty($direction) && $direction == "show"){
		$condition = "";
		if( !empty($tgl_1) && 
			!empty($tgl_2))					{ 
			$tgl_1_new		= $dtime->date2sysdate($tgl_1);
			$tgl_2_new		= $dtime->date2sysdate($tgl_2);
			$condition 		.= " AND a.TRANSACTION_DATE BETWEEN '".$tgl_1_new."' AND '".$tgl_2_new."'"; 							}
		if(!empty($faktur))					{ $condition 	.= " AND a.FACTURE_NUMBER		LIKE '%".$faktur."%'";			}
		if(!empty($lunas))					{ $condition 	.= " AND a.PAID_STATUS 			= '".$lunas."' "; 				}

		if(!empty($harga_pokok))			{ $condition 	.= " AND b.BUY_PRICE 			= '".$harga_pokok."' "; 		}
		if(!empty($harga))					{ $condition 	.= " AND b.SALE_PRICE 			= '".$harga."' "; 				}
		if(!empty($stock))					{ $condition 	.= " AND b.QUANTITY 			= '".$stock."' "; 			}
		if(!empty($total))					{ $condition 	.= " AND b.TOTAL 				= '".$total."' "; 				}
		
		if(!empty($id_kategori))			{ $condition 	.= " AND c.ID_PRODUCT_CATEGORY 	= '".$id_kategori."' "; 		}
		if(!empty($code))					{ $condition 	.= " AND c.CODE 				= '".$code."' "; 				}
		if(!empty($nama))					{ $condition 	.= " AND c.NAME					LIKE '%".$nama."%' "; 			}
		if(!empty($deskripsi))				{ $condition 	.= " AND c.DESCRIPTION 			LIKE '%".$deskripsi."%' "; 		}
	}
}
?>
<?php
	$query_str	= "
				SELECT 
					*,SUM(b.TOTAL) AS SUMMARY,SUM(b.QUANTITY) AS JML,a.REMAIN AS HUTANG 
				FROM 
					".$tpref."factures a,".$tpref."products_buys b,".$tpref."products c
				WHERE 
					a.ID_FACTURE = b.ID_FACTURE AND 
					b.ID_PRODUCT = c.ID_PRODUCT AND 
					a.MODULE 	 = 'BUY' AND
					a.ID_CLIENT='".$_SESSION['cidkey']."' 
					".$condition." 
				GROUP BY a.ID_FACTURE
				ORDER BY a.ID_CASH_FLOW DESC,a.FACTURE_NUMBER";
	$q_buy 		= $db->query($query_str." ".$limit);
	@$num_buy	= $db->recount($query_str);


?>
