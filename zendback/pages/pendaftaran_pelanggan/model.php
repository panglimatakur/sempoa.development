<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(empty($id_client_form)){
	$id_client_form = $_SESSION['cidkey']; 
}

function merchant_list($parent,$deep){
	global $tpref;
	global $db;
	$deep = $deep*2; 
	$q_merchant 	= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL AND CLIENT_ID_PARENT='".$parent."' ORDER BY CLIENT_NAME ASC");
	$num_merchant 	= $db->numRows($q_merchant);
	if($num_merchant >0){
		while($dt_merchant = $db->fetchNextObject($q_merchant)){
?>
		<option class="item" value="<?php echo $dt_merchant->ID_CLIENT; ?>" 
        		data-parent="<?php echo $parent; ?>" style="margin-right:10px;"><?php echo str_repeat("&nbsp;",$deep).$dt_merchant->CLIENT_NAME; ?></option>
<?php
		echo merchant_list($dt_merchant->ID_CLIENT,$deep); 
		}
	}
}

$q_merchant 	= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT IS NOT NULL AND CLIENT_ID_PARENT='0' ORDER BY CLIENT_NAME ASC");



if($_SESSION['uclevelkey'] == 1 || $_SESSION['uclevelkey'] == 2){ 
	
	if($_SESSION['uclevelkey'] == 2){ 
		$branch_cond = " WHERE 	   ID_CLIENT='".$_SESSION['cidkey']."' 
								OR ".networks_condition($_SESSION['cidkey'],""); 
	}
	$str_branch		= "SELECT * FROM ".$tpref."clients  ".@$branch_cond." ORDER BY CLIENT_NAME";
	$query_branch 	= $db->query($str_branch);

}



$condition						 = "";
if($_SESSION['cidkey'] == 1 && $_SESSION['ulevelkey'] == 1){
	if(!empty($id_client_report)){
		$condition			.= " AND ID_CLIENT='".$id_client_report."'	";
		$user_condition 	 = " WHERE ID_CLIENT = '".$_SESSION['cidkey']."' ";
	}
}else{
	$condition				.= " AND ID_CLIENT='".$_SESSION['cidkey']."'	";
	$user_condition 		 = " WHERE ID_CLIENT = '".$_SESSION['cidkey']."' "; 	
}
//Input "Didaftarkan Oleh"
$query_user			= $db->query("SELECT * FROM system_users_client ".@$user_condition." ORDER BY USER_NAME ASC"); 
//ED OF Input "Didaftarkan Oleh"

if(!empty($direction) && $direction == "show"){
	if(!empty($_REQUEST['nama_report']))	 { $condition 	.= " AND CUSTOMER_NAME 		LIKE '%".$nama_report."%'"; 	}
	if(!empty($_REQUEST['reg_by']))			 { $condition 	.= " AND REQUEST_BY_ID_USER = '".$reg_by."'"; 				}
	if(!empty($_REQUEST['sex_report']))		 { $condition 	.= " AND CUSTOMER_SEX 		= '".$sex_report."'"; 			}
	if(!empty($_REQUEST['id_member_report'])){ $condition 	.= " AND CUSTOMER_ID_NUMBER LIKE '%".$id_member_report."%'";}
	if(!empty($_REQUEST['propinsi_report'])) { $condition 	.= " AND CUSTOMER_PROVINCE 	= '".$propinsi_report."'"; 		}
	if(!empty($_REQUEST['kota_report']))	 { $condition 	.= " AND CUSTOMER_CITY 		= '".$kota_report."'"; 			}
	if(!empty($_REQUEST['alamat_report']))	 { $condition 	.= " AND CUSTOMER_ADDRESS 	LIKE '%".$alamat_report."%'"; 	}
	if(!empty($_REQUEST['nocoin']))			 { $condition 	.= " AND COIN_NUMBER 		LIKE '%".$nocoin."%'"; 			}
	if(!empty($_REQUEST['kontak_report']))	 { $condition 	.= " AND CUSTOMER_PERSON_CONTACT LIKE '%".$kontak_report."%'"; 	}
	if(!empty($_REQUEST['email_report']))	 { $condition 	.= " AND CUSTOMER_EMAIL 	LIKE '%".$email_report."%'"; 	}
	if(!empty($_REQUEST['coin_stat']))		 { $condition 	.= " AND CUSTOMER_STATUS 	= '".$coin_stat."'"; 			}
	if(!empty($_REQUEST['keterangan']))		 { $condition 	.= " AND ADDITIONAL_INFO 	LIKE '%".$keterangan."%'"; 		}
	if($_SESSION['uclevelkey'] == 1){
		if(!empty($id_client_report))		 { $condition	.= " AND ID_CLIENT='".$id_client_report."'	"; 				}
	}
	$class_report = "active";
}



//DAFTAR CUSTOMER
$str_query			= "SELECT * FROM ".$tpref."customers 
					   WHERE ID_CLIENT IS NOT NULL ".$condition." ORDER BY ID_CUSTOMER DESC"; 
$q_customer 		= $db->query($str_query." LIMIT 0,50");
$link_str			= $lparam;
$num_customer		= $db->recount($str_query);
//END OF DAFTAR CUSTOMER

if(!empty($direction) && $direction == "edit"){
	$condition_level = "";
	if($_SESSION['uclevelkey'] != 1){
		$condition_level	= "AND ID_CLIENT='".$_SESSION['cidkey']."'	";
	}
	$q_customer_edit = $db->query("SELECT * FROM ".$tpref."customers WHERE ID_CUSTOMER='".$no."' ".@$condition_level."");
	$dt_customer_edit= $db->fetchNextObject($q_customer_edit);
	
	$id_client_form= $dt_customer_edit->ID_CLIENT; 
	@$number 		= $dt_customer_edit->COIN_NUMBER; 	
	@$id_member 	= $dt_customer_edit->CUSTOMER_ID_NUMBER; 
	@$nama 			= $dt_customer_edit->CUSTOMER_NAME; 		
	@$alamat 		= $dt_customer_edit->CUSTOMER_ADDRESS; 		
	@$kontak 		= $dt_customer_edit->CUSTOMER_PERSON_CONTACT; 		
	@$email 		= $dt_customer_edit->CUSTOMER_EMAIL; 		
	@$website 		= $dt_customer_edit->CUSTOMER_URL; 	
	@$photo 		= $dt_customer_edit->CUSTOMER_PHOTO;
	@$sex 			= $dt_customer_edit->CUSTOMER_SEX; 		
	@$propinsi 		= $dt_customer_edit->CUSTOMER_PROVINCE; 	
	@$kota 			= $dt_customer_edit->CUSTOMER_CITY; 		
	$class_proses = "active";
	$class_report = "";
}
?>