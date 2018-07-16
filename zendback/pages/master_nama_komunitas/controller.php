<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$nama 		 	= isset($_REQUEST['nama']) ? $sanitize->str($_REQUEST['nama']):""; 				
	$ori_name 	 	= isset($_REQUEST['ori_name']) ? $sanitize->str($_REQUEST['ori_name']):""; 			
	@$status_aktif 	= isset($_REQUEST['status_aktif']) ? $sanitize->number($_REQUEST['status_aktif']):"0"; 	
	
	if(!empty($direction) && ($direction == "insert" || $direction == "save")){
		if(!empty($nama)){
			
			if(!empty($direction) && $direction == "insert"){ 
				$num_com = $db->recount("SELECT ID_COMMUNITY FROM ".$tpref."communities WHERE NAME='".$nama."'"); 
				if($num_com == 0){
					$container = array(1=>
						array("NAME",$nama),
						array("BY_ID_USER",@$_SESSION['uidkey']),
						array("BY_ID_PURPLE",@$_SESSION['cidkey']),
						array("STATUS_ACTIVE",@$status_aktif));
					$db->insert($tpref."communities",$container);
					redirect_page($lparam."&msg=1");
				}else{
					$msg = 3;
				}
			}
			if(!empty($direction) && $direction == "save"){ 
				$num_com = $db->recount("SELECT ID_COMMUNITY FROM ".$tpref."communities WHERE NAME='".$nama."'"); 
				if($num_com > 0 && ($ori_name == $nama) || $num_com == 0){
					$container = array(1=>
						array("NAME",$nama),
						array("BY_ID_USER",@$_SESSION['uidkey']),
						array("BY_ID_PURPLE",@$_SESSION['cidkey']),
						array("STATUS_ACTIVE",@$status_aktif));
					$db->update($tpref."communities",$container," WHERE ID_COMMUNITY='".$no."'");
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