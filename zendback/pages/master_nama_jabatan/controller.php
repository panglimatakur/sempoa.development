<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($_REQUEST['nama']))		{ $nama 	= $sanitize->str($_REQUEST['nama']); 		}
	if(!empty($_REQUEST['ori_name']))	{ $ori_name = $sanitize->str($_REQUEST['ori_name']); 		}
	
	if(!empty($direction) && ($direction == "insert" || $direction == "save")){
		if(!empty($nama)){
			
			if(!empty($direction) && $direction == "insert"){ 
				$num_level = $db->recount("SELECT ID_CLIENT_USER_LEVEL FROM system_master_client_users_level WHERE NAME='".$nama."'"); 
				if($num_level == 0){
					$container = array(1=>
						//array("ID_CLIENT",@$id_client),
						array("NAME",$nama));
					$db->insert("system_master_client_users_level",$container);
					redirect_page($lparam."&msg=1");
				}else{
					$msg = 3;
				}
			}
			if(!empty($direction) && $direction == "save"){ 
				$num_level = $db->recount("SELECT ID_CLIENT_USER_LEVEL FROM system_master_client_users_level WHERE NAME='".$nama."'"); 
				if($num_level > 0 && ($ori_name == $nama) || $num_level == 0){
					$container = array(1=>
						//array("ID_CLIENT",@$id_client),
						array("NAME",$nama));
					$db->update("system_master_client_users_level",$container," WHERE ID_CLIENT_USER_LEVEL='".$no."'");
					redirect_page($lparam."&msg=1");
				}else{
					$msg = 3;
				}
			}
			
		}else{
			$msg = 2;
		}
	}
?>