<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($_REQUEST['type']))		{ $type 	= $sanitize->str($_REQUEST['type']); 		}
	if(!empty($_REQUEST['nama']))		{ $nama 	= $sanitize->str($_REQUEST['nama']); 		}
	
	
	if(!empty($direction) && ($direction == "insert" || $direction == "save")){
		if(!empty($nama)){
			if(!empty($direction) && $direction == "insert"){ 
				$container = array(1=>array("NAME",$nama));
				$db->insert($tpref."products_units",$container);
				redirect_page($lparam."&msg=1");
			}
			if(!empty($direction) && $direction == "save"){ 
				$container = array(1=>array("NAME",$nama));
				$db->update($tpref."products_units",$container," WHERE ID_PRODUCT_UNIT='".$no."'");
				redirect_page($lparam."&msg=1");
			}
		}else{
			$msg = 2;
		}
	}
?>