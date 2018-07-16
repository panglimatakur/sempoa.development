<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	if(!empty($_REQUEST['by_merc_status']))	{ $by_merc_status 	= $sanitize->number($_REQUEST['by_merc_status']); 	}
	
	if(!empty($_REQUEST['ch_ref']))			{ $ch_ref			= $sanitize->str($_REQUEST['ch_ref']); 				}
	if(!empty($_REQUEST['id_client_form']))	{ $id_client_form 	= $sanitize->str($_REQUEST['id_client_form']); 		}
	if(!empty($_REQUEST['jml_cetak']))		{ $jml_cetak 		= $sanitize->number($_REQUEST['jml_cetak']); 		}
	if(!empty($_REQUEST['status']))			{ $status 			= $sanitize->number($_REQUEST['status']); 			}
	if(!empty($_REQUEST['show']))			{ $show 			= $sanitize->str($_REQUEST['show']); 				}
	if(!empty($_REQUEST['id_merchant']))	{ $id_merchant 		= $sanitize->number($_REQUEST['id_merchant']); 		}
	if(!empty($_REQUEST['row_sel']))		{ $row_sel 			= $_REQUEST['row_sel']; 		}
	if(!empty($direction) && $direction == "generate"){
		if(!empty($jml_cetak)){
			function checkCoin($number){
				global $db;
				global $tpref;
				global $tglupdate;
				global $id_client_form;
				$ch		= $db->recount("SELECT ACTIVATION_CODE FROM ".$tpref."discoin_activation_codes WHERE ACTIVATION_CODE ='".$number."'");
				if($ch == 0){
					$arr_coin	 = array(1=>array("ACTIVATION_CODE",$number),
											array("ACTIVATE_STATUS","0"),
											array("UPDATEDATE",$tglupdate));
					$db->insert($tpref."discoin_activation_codes",$arr_coin);
					return $number;
				}else{
					$number = strtoupper(substr(md5($ip_address.$file_id.rand(0,1000000000)),0,10));
					checkCoin($number);
				}
			}
			$r = 0;
			while($r < $jml_cetak){
				$r++;
				$number = strtoupper(substr(md5($ip_address.$file_id.rand(0,1000000000)),0,10));
				checkCoin($number);	
			}
			redirect_page($lparam."&msg=1");	
		}else{
			$msg = 2;	
		}
	}
	
?>