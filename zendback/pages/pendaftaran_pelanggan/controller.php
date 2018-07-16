<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$class_report = "active";
	if(!empty($_REQUEST['sub_msg']))		{ $sub_msg = $sanitize->str($_REQUEST['sub_msg']);					}
	if(!empty($_REQUEST['id_client_form']))	{ $id_client_form = $sanitize->number($_REQUEST['id_client_form']);	}
	if(!empty($_REQUEST['reg_by']))		{ $reg_by 	= $sanitize->number($_REQUEST['reg_by']);					}
	if(!empty($_REQUEST['number']))		{ $number 	= $sanitize->str(strtoupper($_REQUEST['number'])); 			}
	if(!empty($_REQUEST['type']))		{ $type 	= $sanitize->str($_REQUEST['type']); 						}
	if(!empty($_REQUEST['nama']))		{ $nama 	= $sanitize->str(ucwords($_REQUEST['nama'])); 				}
	if(!empty($_REQUEST['id_member']))	{ $id_member= $sanitize->str(strtoupper($_REQUEST['id_member'])); 		}
	if(!empty($_REQUEST['sex']))		{ $sex 		= $sanitize->str($_REQUEST['sex']); 						}
	if(!empty($_FILES['photo']['name'])){ $photo 	= $_FILES['photo']['name']; 								}

	if(!empty($_REQUEST['propinsi']))	{ $propinsi = $sanitize->str($_REQUEST['propinsi']); 					}
	if(!empty($_REQUEST['kota']))		{ $kota 	= $sanitize->str($_REQUEST['kota']); 						}
	if(!empty($_REQUEST['alamat']))		{ $alamat 	= $sanitize->str(ucwords($_REQUEST['alamat'])); 			}
	if(!empty($_REQUEST['kontak']))		{ $kontak 	= $sanitize->str($_REQUEST['kontak']); 						}
	if(!empty($_REQUEST['email']))		{ $email 	= $sanitize->email(strtolower($_REQUEST['email'])); 			}
	
	$id_client_report = isset($_REQUEST['id_client_report'])? $sanitize->number($_REQUEST['id_client_report'])	:"";
	$reg_by 		= isset($_REQUEST['reg_by']) 			? $sanitize->str($_REQUEST['reg_by'])				:"";
	$nama_report 	= isset($_REQUEST['nama_report']) 		? $sanitize->str($_REQUEST['nama_report'])			:"";
	$email_report 	= isset($_REQUEST['email_report']) 		? $sanitize->str($_REQUEST['email_report'])			:"";
	$kontak_report 	= isset($_REQUEST['kontak_report']) 	? $sanitize->str($_REQUEST['kontak_report'])		:"";
	$sex_report 	= isset($_REQUEST['sex_report']) 		? $sanitize->str($_REQUEST['sex_report'])			:"";
	$nocoin 		= isset($_REQUEST['nocoin']) 			? $sanitize->str($_REQUEST['nocoin'])				:"";
	$coin_stat 		= isset($_REQUEST['coin_stat']) 		? $sanitize->number($_REQUEST['coin_stat'])			:"";
	$id_member_report = isset($_REQUEST['id_member_report'])? $sanitize->str($_REQUEST['id_member_report'])		:"";
	$alamat_report 	= isset($_REQUEST['alamat_report']) 	? $sanitize->str($_REQUEST['alamat_report'])		:"";
	$propinsi_report = isset($_REQUEST['propinsi_report']) 	? $sanitize->number($_REQUEST['propinsi_report'])	:"";
	$kota_report 	= isset($_REQUEST['kota_report']) 		? $sanitize->number($_REQUEST['kota_report'])		:"";
		
	
	if(!empty($_REQUEST['number_msg']))	{ $number_msg 	= $sanitize->str($_REQUEST['number_msg']);	}
	if(!empty($_REQUEST['nama_msg']))	{ $nama_msg 	= $sanitize->str($_REQUEST['nama_msg']);	}
	
	//echo  $coin_stat;
	//mysql_query("UPDATE cat_customers SET CUSTOMER_STATUS = '3' WHERE CUSTOMER_STATUS='2'");
	//echo @$direction;
	
	
	if(!empty($direction) && ($direction == "insert" || $direction == "save")){
		if(!empty($nama) && !empty($sex) && !empty($email) && !empty($kontak)){
			$ch_number = 0;
			$msg_param = "&msg=1";
			if(!empty($number)){
				$num_coin 		= $db->recount("SELECT ACTIVATION_CODE 
												FROM ".$tpref."discoin_activation_codes 
												WHERE 
													ACTIVATION_CODE = '".$number."' AND 
													ACTIVATE_STATUS = '0'");
				if($num_coin == 0){
					$msg_param      = "&msg=3&number_msg=".$number."&nama_msg=".$nama;
					$num_coin 	= "0";
					$number   	= "";
				}
			}else{
				$num_coin 	= "0";
				$number   	= "";
			}
			
			
			if(!empty($direction) && $direction == "insert"){ 
				if(!empty($photo)){
					$dest			= $basepath."/files/images/members/";
					$extensions		= array("jpg","JPG","jpeg","JPEG","png","PNG","GIF","gif");
					$filename		= $file_id."-".$_FILES['photo']['name'];
					$src			= array($_FILES['photo']['tmp_name'],$filename);
					$dest_thumb		= $basepath."/files/images/members/big";
					$new_width		= '300';
					
					$cupload->upload($src,$dest,$extensions);
					$cupload->resizeupload($dest."/".$filename,$dest_thumb,$new_width);
				}else{
					$filename = "";
				}
				$user_pass	= substr(md5($email),0,10);
				
					   
				$container = array(1=>
					array("ID_CLIENT",$id_client_form),
					array("COIN_NUMBER",@$number),	
					array("CUSTOMER_PHOTO",@$filename),	
					array("CUSTOMER_NAME",@$nama),
					array("CUSTOMER_PASS",$user_pass),
					array("CUSTOMER_SEX",@$sex),
					array("CUSTOMER_ID_NUMBER",@$id_member),
					array("CUSTOMER_EMAIL",@$email),
					array("CUSTOMER_PERSON_CONTACT",@$kontak),
					array("CUSTOMER_ADDRESS",@$alamat),
					array("CUSTOMER_PROVINCE",@$propinsi),
					array("CUSTOMER_CITY",@$kota),
					array("TGLUPDATE",@$tglupdate));
				$db->insert($tpref."customers",$container);
				$id_customer = mysql_insert_id();   
				if($num_coin > 0){
					$new_activation = array(1=>
										array("ACTIVATE_BY_ID_CLIENT",@$id_client_form),
										array("ACTIVATE_BY_ID_CUSTOMER",@$id_customer),
										array("ACTIVATE_STATUS","3"),
										array("ACTIVATE_DATETIME",$tglupdate." ".$wktupdate));
					$db->update($tpref."discoin_activation_codes",$new_activation," 
								WHERE ACTIVATION_CODE = '".$number."'");
				}
				redirect_page($lparam.$msg_param);
			}
			if(!empty($direction) && $direction == "save"){ 
				@$q_customer 		= $db->query("SELECT 
													CUSTOMER_PHOTO,COIN_NUMBER
												  FROM ".$tpref."customers 
												  WHERE ID_CUSTOMER='".$no."'");
				@$dt_customer 		= $db->fetchNextObject($q_customer);
				@$photori			= $dt_customer->CUSTOMER_PHOTO;
				@$coin_ori			= $dt_customer->COIN_NUMBER;

				if(!empty($photo)){
					if(!empty($photori)){ unlink($basepath."/files/images/members/".$photori); }
					$dest			= $basepath."/files/images/members/";
					$extensions		= array("jpg","JPG","jpeg","JPEG","png","PNG","GIF","gif");
					$filename		= $file_id."-".$_FILES['photo']['name'];
					$src			= array($_FILES['photo']['tmp_name'],$filename);
					$dest_thumb		= $basepath."/files/images/members/big";
					$new_width		= '300';
					
					$cupload->upload($src,$dest,$extensions);
					$cupload->resizeupload($dest."/".$filename,$dest_thumb,$new_width);
				}
				else{ $filename = $photori; }
				
				if($num_coin == 0 && !empty($coin_ori)){
					$number 		= $coin_ori;
					$msg_param      = "&msg=3&number_msg=".$_REQUEST['number']."&nama_msg=".$nama;
				}
				
				$container = array(1=>
					array("ID_CLIENT",$id_client_form),
					array("COIN_NUMBER",@$number),	
					array("CUSTOMER_PHOTO",@$filename),	
					array("CUSTOMER_NAME",@$nama),
					array("CUSTOMER_SEX",@$sex),
					array("CUSTOMER_ID_NUMBER",@$id_member),
					array("CUSTOMER_EMAIL",@$email),
					array("CUSTOMER_PERSON_CONTACT",@$kontak),
					array("CUSTOMER_ADDRESS",@$alamat),
					array("CUSTOMER_PROVINCE",@$propinsi),
					array("CUSTOMER_CITY",@$kota),
					array("TGLUPDATE",@$tglupdate));
				$db->update($tpref."customers",$container," WHERE ID_CUSTOMER='".$no."'");
				if($num_coin > 0){
					$new_activation = array(1=>
										array("ACTIVATE_BY_ID_CLIENT",@$id_client_form),
										array("ACTIVATE_BY_ID_CUSTOMER",@$no),
										array("ACTIVATE_STATUS","3"),
										array("ACTIVATE_DATETIME",$tglupdate." ".$wktupdate));
					$db->update($tpref."discoin_activation_codes",$new_activation," 
								WHERE ACTIVATION_CODE = '".$number."'");
				}
				
				redirect_page($lparam.$msg_param);
			}
		}else{
			$msg = 2;
		}
	}
?>