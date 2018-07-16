<?php defined('mainload') or die('Restricted Access'); ?>
<?php
    $query_str	= " SELECT 
                        a.*,b.PHOTOS 
                    FROM 
                        ".$tpref."products a,".$tpref."products_photos b 
                    WHERE 
                        a.ID_CLIENT = '".$id_coin."' AND 
                        a.ID_PRODUCT = b.ID_PRODUCT AND
						a.ID_STATUS != '1'
                    ORDER BY a.ID_PRODUCT DESC";
                    //echo $query_str;
    $num_produk	= $db->recount($query_str);
    $discount 	= $db->fob("VALUE",$tpref."client_discounts","WHERE ID_CLIENT = '".$id_coin."' AND COMMUNITY_FLAG != '0' AND REQUEST_BY_ID_CUSTOMER = ''");
	
	$condition_comm		= "";	
	if(!empty($_REQUEST['titanium']) && $_REQUEST['titanium'] == "true"){
				$condition_comm = "AND ID_CLIENT = '1'";
	}else{		$condition_comm = "AND ID_CLIENT = '".$id_coin."'";	}
	$str_list_comm	= "SELECT DISTINCT(ID_COMMUNITY) FROM ".$tpref."communities_merchants WHERE ID_COMMUNITY IS NOT NULL ".$condition_comm." ORDER BY ID_COMMUNITY ASC";
	//echo $str_list_comm;
	$q_list_comm	= $db->query($str_list_comm);
    $j = 0;
    $q_list_merch	= $db->query($str_list_comm);
?>