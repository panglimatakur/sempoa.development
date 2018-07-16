<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$query_branch 		= $db->query("SELECT * FROM ".$tpref."clients 
									  WHERE ID_CLIENT IS NOT NULL ORDER BY CLIENT_NAME");
	
	//FILTER CONDITION
	$condition			= "";
	if($_SESSION['admin_only'] == "false"){
		$condition .= " AND ACTIVATE_BY_ID_CLIENT  = '".$_SESSION['cidkey']."'";	  
	}else{
		if(!empty($id_client_form))	{ 
			$condition .= " AND ACTIVATE_BY_ID_CLIENT = '".$id_client_form."'"; 	
		}else{
			$condition .= " AND ACTIVATE_BY_ID_CLIENT IS NULL"; 	
		}
	}
	if(!empty($status))	{ $condition .= " AND ACTIVATE_STATUS  = '".$status."'"; 					}
	else				{ $condition .= " AND (ACTIVATE_STATUS  = '0' OR ACTIVATE_STATUS IS NULL)";	}
	
	//END OF FILTER CONDITION
	
	$str_coin 	 	  = "SELECT * FROM ".$tpref."discoin_activation_codes 
					  	 WHERE ID_DISCOIN_ACTIVATION_CODE IS NOT NULL ".$condition." 
					     ORDER BY ID_DISCOIN_ACTIVATION_CODE DESC";
	$q_coin 		  = $db->query($str_coin." LIMIT 0,100");
	$num_coin		  = $db->recount($str_coin);
?>
