<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$id_merchant= isset($_REQUEST['id']) 		? $_REQUEST['id'] : "";
	
	if(empty($parameters) || (!empty($parameters) && substr_count($parameters,"cancel_subscribe") == 0)){
		$str_coin	= "SELECT COIN_NUMBER FROM ".$tpref."customers WHERE ID_CLIENT='".$id_merchant."' AND CUSTOMER_STATUS = '0' ORDER BY ID_CUSTOMER DESC";
		$done 		= 0;
		$q_coin		= $db->query($str_coin);
		while($dt_coin = $db->fetchNextObject($q_coin)){
			//echo "<br>".$parameters."== (".$dt_coin->COIN_NUMBER.") - ".md5($dt_coin->COIN_NUMBER);
			if($parameters == md5($dt_coin->COIN_NUMBER)){
				$done++;
				$q_customer 	= $db->query("SELECT CUSTOMER_NAME FROM ".$tpref."customers WHERE COIN_NUMBER = '".$dt_coin->COIN_NUMBER."' AND ID_CLIENT = '".$id_merchant."' AND CUSTOMER_STATUS = '0'  ");
				$dt_customer	= $db->fetchNextObject($q_customer);
				
				$expired_date   = $dtime->tomorrow(365,date('d'),date('m'),date('Y'));
				$new_activation 	= array(1=>
										array("CUSTOMER_STATUS","3"),
										array("EXPIRATION_DATE",$expired_date),
										array("TGLUPDATE",$tglupdate));
				$db->update($tpref."customers",$new_activation," WHERE COIN_NUMBER = '".$dt_coin->COIN_NUMBER."' AND ID_CLIENT = '".$id_merchant."' AND CUSTOMER_STATUS = '0' ");
				break;
			}
		}
	}else{
		$parameter 		= explode("=",$parameters);
		$cancel_email 	= $parameter[1];
		$new_activation 	= array(1=>array("SUBSCRIBE_STATUS","1"));
		$db->update($tpref."newsletters",$new_activation," WHERE ID_NEWSLETTER = '".$id_merchant."' AND ENCRYPTED_EMAIL = '".$cancel_email."' ");
		$done = 2;
	}
?>