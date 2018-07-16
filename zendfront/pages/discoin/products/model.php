<?php defined('mainload') or die('Restricted Access'); ?>
<?php
    if(!empty($id_category)){
        $category_condition = " a.ID_PRODUCT_CATEGORY = '".$id_category."' AND "; 
    }
    $query_str	= " SELECT 
                        a.*,b.PHOTOS 
                    FROM 
                        ".$tpref."products a,".$tpref."products_photos b 
                    WHERE 
                        a.ID_CLIENT = '".$id_coin."' AND 
						".@$category_condition."
                        a.ID_PRODUCT = b.ID_PRODUCT AND
						a.ID_STATUS != '1'
                    ORDER BY a.ID_PRODUCT DESC";
                    //echo $query_str;
    $num_produk	= $db->recount($query_str);
	
?>