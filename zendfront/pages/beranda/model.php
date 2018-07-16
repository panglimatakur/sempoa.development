<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$q_client 		= $db->query("SELECT * FROM ".$tpref."clients WHERE CLIENT_ID_PARENT != '1' AND ACTIVATE_STATUS = '3' AND CLIENT_LOGO != '' ORDER BY ID_CLIENT DESC LIMIT 0,8");
	$q_communities	= $db->query("SELECT a.NAME,a.ID_COMMUNITY FROM ".$tpref."communities a,".$tpref."communities_merchants b WHERE a.ID_COMMUNITY = b.ID_COMMUNITY GROUP BY b.ID_COMMUNITY");

    $query_str	= " SELECT 
					a.*,b.PHOTOS 
				FROM 
					".$tpref."products a,".$tpref."products_photos b 
				WHERE 
					a.ID_PRODUCT = b.ID_PRODUCT
				GROUP BY a.ID_CLIENT
				ORDER BY RAND()";
				//echo  $query_str;
	$q_produk 	= $db->query($query_str."  LIMIT 0,10");
?>