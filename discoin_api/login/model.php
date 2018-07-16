<?php
ini_set('session.gc_maxlifetime', 30*60);
session_start();
if((!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') || (!empty($_REQUEST['sempoakey']) && $_REQUEST['sempoakey'] == "99765")) {
	
	define('mainload','SEMPOA',true); 
	include("../../includes/config.php");
	include("../../includes/classes.php");
	include("../../includes/functions.php");
	include("../../includes/declarations.php");

	function config($id){
		global $db;
		global $tpref;
		$result = $db->fob("CONFIG_VALUE",$tpref."config"," WHERE ID_CONFIG = '".$id."'");
		return $result;
	}
	$id_coin		= isset($_REQUEST['id_coin']) 	? $sanitize->number($_REQUEST['id_coin']) 		: "1";
	$direction		= isset($_REQUEST['direction']) ? $_REQUEST['direction'] 						: "";
	$titanium 		= isset($_REQUEST['titanium']) 	? $_REQUEST['titanium'] 						: "false";
	
	$id_customer	= isset($_REQUEST['id_customer'])? $sanitize->number($_REQUEST['id_customer'])	: "";
	$token			= isset($_REQUEST['token']) 	? $sanitize->str($_REQUEST['token']) 			: "";
	$susername		= isset($_REQUEST['username']) 	? $sanitize->str($_REQUEST['username']) 		: "";
	$password		= isset($_REQUEST['password']) 	? $_REQUEST['password'] 						: "";
	$activated		= isset($_REQUEST['activated']) ? $_REQUEST['activated'] 						: "";
	
	
	$coin_number	= isset($_REQUEST['coin_number']) 	? $sanitize->str($_REQUEST['coin_number']) 	: "";
	$nama			= isset($_REQUEST['nama']) 			? $sanitize->str($_REQUEST['nama']) 		: "";
	$email			= isset($_REQUEST['email']) 		? $sanitize->email($_REQUEST['email']) 		: "";
	$user_pass		= isset($_REQUEST['user_pass']) 	? $_REQUEST['user_pass'] 					: "";
	$tlp			= isset($_REQUEST['tlp']) 			? $sanitize->number($_REQUEST['tlp']) 		: "";
	$sex			= isset($_REQUEST['sex']) 			? $sanitize->str($_REQUEST['sex']) 			: "";
	$referal		= isset($_REQUEST['referal']) 		? $sanitize->email($_REQUEST['referal']) 	: "";
	
	
    $callback 		= 'mycallback';
	$callback 		= isset($_REQUEST['mycallback']) ? $_REQUEST['mycallback'] : "";

	if(!empty($id_coin)){
		//CHECK MERCHANT INFO
		$q_merchant  		= $db->query("SELECT * FROM ".$tpref."clients WHERE ID_CLIENT='".$id_coin."'"); 
		$dt_merchant 		= $db->fetchNextObject($q_merchant);
		$color				= explode(";",$dt_merchant->COLOUR);
		$color_1 			= $color[0]; 
		$color_2 			= $color[1];
		if(empty($color_1))	{ $color_1 = "#993366"; }
		if(empty($color_2))	{ $color_2 = "#732b4f"; }
		$m_name				= $dt_merchant->CLIENT_NAME;
		$logo 		 		= $dirhost."/files/images/logos/".$dt_merchant->CLIENT_LOGO; 
		//END OF CHECK MERCHANT INFO
	}
	if(!empty($direction) && $direction == "load"){
		
		if(empty($logo)){
			$logo = "no_image.jpg";
		}
		$result['m_name']  	= $dt_merchant->CLIENT_NAME;
		$result['aff_flag'] = $dt_merchant->AFFILIATE_FLAG;
		$result['logo']  	= $logo;
		$result['color_1']  = $color_1;
		$result['color_2']  = $color_2;
		$result['addons']	= ch_addon($id_coin);
		echo $callback.'('.json_encode($result).')';
	}
	if(!empty($direction) && $direction == "save_token"){
		$db->query("UPDATE ".$tpref."customers SET CUSTOMER_REG_ID = '".$token."' WHERE ID_CUSTOMER = '".$id_customer."'");
		$result['msg'] = "Token Berhasil disimpan";
		echo $callback.'('.json_encode($result).')';
	}
	if(!empty($direction) && $direction == "activate"){
		
		$q_customer 	= $db->query("SELECT ID_CUSTOMER,CUSTOMER_EMAIL FROM ".$tpref."customers
									  WHERE CUSTOMER_EMAIL = '".$susername."' AND ID_CLIENT = '".$id_coin."'");
		$num_customer 	= $db->numRows($q_customer);
		
		
		$num_coin 		= $db->recount("SELECT ACTIVATION_CODE 
										FROM ".$tpref."discoin_activation_codes 
										WHERE 
											ACTIVATION_CODE = '".$coin_number."' AND 
											ACTIVATE_STATUS = '0'"); 	


		if($num_coin > 0){
			if($num_customer > 0){ 
				$dt_customer 	= $db->fetchNextObject($q_customer);
				$id_customer 	= $dt_customer->ID_CUSTOMER;
				$next_year		= date('Y')+1;
				$expired_date 	= $next_year."-".date('m')."-".date('d');
				
				$reg_content	= array(1=>
										array("CUSTOMER_STATUS","3"),
										array("EXPIRATION_DATE",$expired_date));
				$db->update($tpref."customers",$reg_content," WHERE ID_CUSTOMER = '".$id_customer."'");
		
				
				$new_activation = array(1=>
									array("ACTIVATE_BY_ID_CLIENT",@$id_coin),
									array("ACTIVATE_BY_ID_CUSTOMER",@$id_customer),
									array("ACTIVATE_STATUS","3"),
									array("ACTIVATE_DATETIME",$tglupdate." ".$wktupdate));
				$db->update($tpref."discoin_activation_codes",$new_activation," 
							WHERE ACTIVATION_CODE = '".$coin_number."'");
		
				//rank_formula($id_coin);
				
				$result['msg'] = "<div class='alert alert-success' style='margin:5px 0 5px 0'>Terimakasih, COIN anda berhasil di aktifkan dan di perpanjang hingga <b>".$dtime->now2indodate2($expired_date)."</b>, Silahkan login kembali menggunakan email dan password yang sama, terimakasih</div>";
				$result['io'] 	= 3;
				
			}
		}
		
		
		if($num_coin == 0){
				$result['msg'] = 
					"<div class='alert alert-danger' style='margin:5px 0 5px 0;'>Kode Aktifasi ini tidak di temukan , silahkan gunakan kode aktifasi lainnya yang telah di berikan oleh <b>".$m_name."</b>
					</div>";
				$result['io'] 	= 2;
		}
		if($num_customer == 0){
				$result['msg'] = 
					"<div class='alert alert-danger' style='margin:5px 0 5px 0'>Akun dengan email ini tidak di temukan , silahkan gunakan yang lain., atau lakukan pendaftaran ulang.
					</div>";
				$result['io'] 	= 2;
		}
		echo $callback.'('.json_encode($result).')';
	}	
	
	if(!empty($direction) && $direction == "login"){
		$log_condition	= "";
		if(!empty($susername) && !empty($password)){
			if($titanium == "false")					{ $log_condition = "AND ID_CLIENT='".$id_coin."'"; 				}
			if($titanium == "true")					 	{ $log_condition = "AND ID_CLIENT='1' AND INTERNAL_FLAG = '2'"; }
			if($titanium == "false" && $id_coin == "1") { $log_condition = "AND ID_CLIENT='1' AND INTERNAL_FLAG = '1'"; }
			
			
			$field 			= "CUSTOMER_EMAIL";
			$ch_email 		= $validate->email($susername);
			if($ch_email == 1)	{ $field = "CUSTOMER_EMAIL";}
			else				{ $field = "COIN_NUMBER";	}
			
			//CHECK CUSTOMER INFO
			$str_login 			= "SELECT * FROM ".$tpref."customers WHERE ".$field." = '".$susername."' AND CUSTOMER_PASS ='".$password."' ".$log_condition." ";
			$query_login 		= $db->query($str_login);
			$num_login			= $db->numRows($query_login);
			//END OF CHECK CUSTOMER INFO
			
			if($num_login > 0){	
				$data_logins 		= $db->fetchNextObject($query_login);
				$customer_status 	= $data_logins->CUSTOMER_STATUS;
				if($data_logins->EXPIRATION_DATE < $tglupdate){					
					if($customer_status == "3"){
						$db->query("UPDATE 
										".$tpref."customers 
									SET 
										CUSTOMER_STATUS = '1' 
									WHERE 
										ID_CUSTOMER = '".$data_logins->ID_CUSTOMER."'");
					}
					$result['title'] = "Aktifasi Discoin"; 
					$result['msg'] = 
						"<div style='font-size:12px; text-align:justify'>Maaf, keanggotaan Discoin ".$m_name." telah berakhir, silahkan menghubungi ".$m_name." langgananmu, atau email ".$dt_merchant->CLIENT_EMAIL.", untuk mendapatkan Kode Aktifasi perpanjangan keanggotaan</span><br>
						<div>
						<div id='activate_loader'></div>
						<div class='input-group activate-input' style='margin-top:5px;margin-bottom:5px;'>
							<input type='text' id='activated' placeholder='Kode Aktifasi' class='form-control' style='height: 33px;text-transform:uppercase'>
							<div class='input-group-btn'>
								<button type='button' class='btn btn-primary' onclick='activate()'>
									Aktifkan
								</button>
							</div>
						</div>";
					$result['io'] 	= 2;
					
				}else{
					if($data_logins->CUSTOMER_STATUS == 3){
						$m = 0;
						$q_bank 		= $db->query("SELECT * FROM system_master_bank_info WHERE DIVISION = 'finance'");
						while($dt_bank  = $db->fetchNextObject($q_bank)){
							$m++;
							$bank['acc_name'][$m] 	= $dt_bank->BANK_NAME;
							$bank['bank_name'][$m] 	= $dt_bank->BANK_ACCOUNT_NAME;
							$bank['acc_num'][$m] 	= $dt_bank->BANK_ACCOUNT_NUMBER;
						}
						$result['bank']			= $bank;
						
						$saldo  = $db->last("SALDO",$tpref."savings"," WHERE ID_CUSTOMER = '".$data_logins->ID_CUSTOMER."' AND ID_CONFIG = '3' ORDER BY ID_SAVING DESC");
						if(empty($saldo) || $saldo == 0){ $saldo = 0; } 

						//REGISTER SESSIONS
						if($titanium == "true") { $_SESSION['titanium']	= "true";	}
						else					{ $_SESSION['titanium']	= "false";	}
						
						$merchant_join 			= $db->query("SELECT ID_COMMUNITY FROM ".$tpref."communities_merchants WHERE ID_CLIENT='".$data_logins->ID_CLIENT."' ");
						$num_merchant_join		= $db->numRows($merchant_join);
						$com_join				= "";
						while($dt_join 			= $db->fetchNextObject($merchant_join)){ 
							$nm_com 	= $db->fob("NAME",$tpref."communities"," WHERE ID_COMMUNITY='".$dt_join->ID_COMMUNITY."'");
							$com_join.= "<span style='color:#990000'>".$nm_com."</span> | "; 
						}
						
						$_SESSION['scomidkey'] 	= $com_join;	
						$_SESSION['sidkey']	    = $data_logins->ID_CUSTOMER;
						$_SESSION['sidtoken']	= $data_logins->CUSTOMER_REG_ID;
						$_SESSION['susername']	= $data_logins->CUSTOMER_USERNAME;
						$_SESSION['spassword']	= $data_logins->CUSTOMER_PASS;
						$_SESSION['cust_name']	= $data_logins->CUSTOMER_NAME;
						if(empty($data_logins->CUSTOMER_NAME)){
							$_SESSION['cust_name']	= "Belum Kenalan";
						}
						$_SESSION['csidkey']	= $data_logins->ID_CLIENT;
						$_SESSION['color_1']	= $color_1;
						$_SESSION['color_2']	= $color_2; 
						
						//END OF REGISTER SESSIONS
						cuser_log("customer",$data_logins->ID_CUSTOMER,"Login",$data_logins->ID_CLIENT);						
						
						//END OF CHECK CHILDREN//
						$cust_photo 			= $data_logins->CUSTOMER_PHOTO;
						if(is_file($basepath."/files/images/members/".$cust_photo)){
							$path_photo 		= $dirhost."/files/images/members/".$cust_photo;
						}else{
							$path_photo 		= $dirhost."/files/images/noimage-m.jpg";
						}
						$forever 	= 2000000000;
						setcookie("sidkey",$_SESSION['csidkey'], $forever,"/",$dirhost,true);
						$result['scomidkey']	= substr(@$com_join, 0, -2);;
						$result['sidkey']		= @$_SESSION['sidkey'];
						$result['sidtoken']		= $data_logins->CUSTOMER_REG_ID;
						$result['susername']	= @$_SESSION['susername'];
						$result['spassword']	= @$_SESSION['spassword'];
						$result['cust_name']	= @$_SESSION['cust_name'];
						$result['cust_email']	= @$data_logins->CUSTOMER_EMAIL;
						$result['cust_sex']		= @$data_logins->CUSTOMER_SEX;
						$result['cust_phone']	= @$data_logins->CUSTOMER_PHONE;
						$result['path_photo']	= @$path_photo;
						$result['number']		= @$data_logins->COIN_NUMBER;
						$result['cust_add'] 	= $data_logins->CUSTOMER_ADDRESS." ".$data_logins->CUSTOMER_CITY." ".$data_logins->CUSTOMER_PROVINCE;
						$result['join_date']	= $dtime->now2indodate2(@$data_logins->TGLUPDATE);
						$result['exp_date']		= $dtime->now2indodate2(@$data_logins->EXPIRATION_DATE);

						$merchant_logo 			= $dt_merchant->CLIENT_LOGO;
						if(is_file($basepath."/files/images/logos/".$merchant_logo)){
							$path_logo 			= $dirhost."/files/images/logos/".$merchant_logo;
						}else{
							$path_logo 			= $dirhost."/files/images/no_image.jpg";
						}
						$result['mlogo']		= @$path_logo;
						$result['csidkey']		= @$_SESSION['csidkey'];
						$result['nama_merchant']= @$m_name;
						$result['color_1']		= @$_SESSION['color_1'];
						$result['color_2']		= @$_SESSION['color_2'];
						$result['saldo'] 		= money("Rp.",@$saldo);
						$result['msg']   		= "Anda Berhasil Masuk";
						$result['io'] 			= 1;
					}
				}
			}else{
				$result['msg'] 		   = "Info Username dan Password Tidak Cocok";
				$result['io'] 			= 0;
			}
		
		}else{
			$result['msg'] 				= "Pengisian Form Login Belum Lengkap";
			$result['io'] 				= 0;
		}
		$result['susername'] 				= $susername;
		echo $callback.'('.json_encode($result).')';
	}
	
	if(!empty($direction) && $direction == "forget"){
		$forgot_email		= isset($_REQUEST['forgot_email']) ? $_REQUEST['forgot_email'] : "";
		if(!empty($forgot_email)){
			if($titanium == "false"){ 
				$log_condition = "AND ID_CLIENT='".$id_coin."'"; 
			}
			if($titanium == "true"){ 
				$log_condition = "AND ID_CLIENT='1'"; 
			}
			$query_email = $db->query("SELECT CUSTOMER_NAME,CUSTOMER_USERNAME,CUSTOMER_PASS FROM ".$tpref."customers WHERE CUSTOMER_EMAIL = '".$forgot_email."' ".$log_condition." ");
			$num_email	= $db->numRows($query_email);
			if($num_email > 0){
				$dt_cust		= $db->fetchNextObject($query_email);
				@$nama_coin		= $dt_cust->CUSTOMER_NAME;
				@$uname_coin	= $dt_cust->CUSTOMER_USERNAME;
				@$pass_coin		= $dt_cust->CUSTOMER_PASS;
				$subject 		= "Lupa Password DisCOIN ".$merchant_coin;
				$from			= "Info Sempoa Discoin <support@sempoa.biz>";
				$type			= "html";
				$msg 			= "
				<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
					Dear ".@$nama_coin.",<br><br>
					Terimakasih sudah bergabung di Komunitas ".$m_name.", yang di support oleh Sempoa Discoin Community.
					<br><br>
					Akun dibawah ini adalah akun Login COIN anda<br><br>
					Username : ".@$uname_coin."<br>
					Password : ".@$pass_coin."<br>
					<br>
					<br>
					Mohon di jaga dengan baik, atau di simpan dengan baik dimana pun anda bisa meyimpan nomor COIN anda.
					<br>
					<br>
					Terimakasih<br><br>
					- support@sempoa.community - <br><br>
					<img src='".$dirhost."/templates/sempoa/img/beoro_logo.png'><br>
					<span style='color:#800040'><b>The Art Of Abacus Technology</b></span>
				</div>	";		
				sendmail(trim($forgot_email),$subject,$msg,$from,$type);
				
				echo "Password telah dikirim ke alamat email tersebut";	
			}else{
				echo "Maaf, Alamat email ini tidak ditemukan, periksa kembali";
			}
		}else{
			echo "Mohon isi alamat email";
		}
		
	}
	
	if(!empty($direction) && $direction == "logout"){
		session_destroy();
		echo $callback.'('.json_encode($result).')';
	}
	if(!empty($direction) && $direction == "insert"){
		if($id_coin == "191" || $id_coin == "180"){
			$coin_number 		= $db->fob("ACTIVATION_CODE",$tpref."discoin_activation_codes","WHERE 
											ACTIVATE_BY_ID_CLIENT = '".$id_coin."' AND 
											ACTIVATE_STATUS = '0'"); 		
		}
		if(!empty($coin_number)  && !empty($nama)  && !empty($user_pass) && !empty($tlp) && !empty($email)){
			//CHECK AKUN DISCOIN DARI EMAIL MEBER BARU//
			$num_user = $db->recount("SELECT CUSTOMER_EMAIL FROM ".$tpref."customers WHERE CUSTOMER_EMAIL = '".$email."' AND ID_CLIENT = '".$id_coin."'"); 	
			//END OF CHECK AKUN DISCOIN DARI EMAIL MEBER BARU//
			
			if($num_user == 0){
				
				//CHECK NOMOR COIN
				$num_coin 		= $db->recount("SELECT ACTIVATION_CODE 
												FROM ".$tpref."discoin_activation_codes 
												WHERE 
													ACTIVATION_CODE = '".$coin_number."' AND 
													ACTIVATE_STATUS = '0'"); 	
				//END OF CHECK NOMOR COIN
				if($num_coin > 0){
					
					//REFERRAL PROSES//
					$id_referal		= 0;
					$req_saldo 		= 2;
					if(!empty($referal)){
						$q_referal 	= $db->query("SELECT CUSTOMER_NAME,ID_CUSTOMER 
												  FROM ".$tpref."customers 
												  WHERE CUSTOMER_EMAIL = '".$referal."' AND 
												  ID_CLIENT = '".$id_coin."'");
						$dt_referal	= $db->fetchNextObject($q_referal);
						$nm_referal	= $dt_referal->CUSTOMER_NAME;
						$id_referal = $dt_referal->ID_CUSTOMER;
						if(!empty($id_referal)){
							$saldo	   = $db->fob("SALDO",$tpref."savings"," WHERE ID_CUSTOMER='".$id_referal."' ORDER BY ID_SAVING DESC");
							$coin_price = config(13);
							$coin_fee	= config(3);
							$debt 	   = ($coin_price/100)*config(3);
							$new_saldo = $saldo+$debt;
							$saving	   = array(1=>
												array("ID_CUSTOMER",$id_referal),
												array("ID_CONFIG",3),
												array("DEBT",$debt),
												array("SALDO",$new_saldo),
												array("TGLUPDATE",$tglupdate));
							$db->insert($tpref."savings",$saving);
							$req_saldo = 2;
								
						}else{
							$req_saldo 		= 1;	
							$result['msg']	= "Maaf, COIN Referal ini tidak ditemukan sebagai member merchant ".$m_name;
						}
					}
					//END OF REFERRAL PROSES//
					
					if($req_saldo == 2){	
						$next_year		= date('Y')+1;
						$expired_date 	= $next_year."-".date('m')."-".date('d');
						$number 		= strtoupper(substr(md5($nama.$email.rand(0,1000000000)),0,10));
						$new_member 	= array(1=>
											array("ID_CLIENT",@$id_coin),
											array("COIN_NUMBER",@$number),
											array("CUSTOMER_NAME",@$nama),
											array("CUSTOMER_PASS",@$user_pass),
											array("CUSTOMER_EMAIL",@$email),
											array("CUSTOMER_PHONE",@$tlp),
											array("CUSTOMER_SEX",@$sex),
											array("CUSTOMER_STATUS","3"),
											array("ID_CUSTOMER_REF",@$id_referal),
											array("EXPIRATION_DATE",$expired_date),
											array("TGLUPDATE",$tglupdate));
						$db->insert($tpref."customers",$new_member);
						$id_customer 	= mysql_insert_id();
						$new_activation = array(1=>
											array("ACTIVATE_BY_ID_CLIENT",@$id_coin),
											array("ACTIVATE_BY_ID_CUSTOMER",@$id_customer),
											array("ACTIVATE_STATUS","3"),
											array("ACTIVATE_DATETIME",$tglupdate." ".$wktupdate));
						$db->update($tpref."discoin_activation_codes",$new_activation," 
									WHERE ACTIVATION_CODE = '".$coin_number."'");

						rank_formula($id_coin);
						
						if($id_coin != 1){
							$merchant_coin	= $dt_merchant->CLIENT_NAME;
							$merchant_email	= $dt_merchant->CLIENT_EMAIL;
							$merchant_app	= $dt_merchant->CLIENT_APP;
							$activation_code= md5($number);
							$from			= "Info Discoin Community <info@sempoa.biz>";
							$subject 		= "Aktifasi COIN ".$merchant_coin;
							$to 			 = $email;
							$type			= "html";
							$msg 			= "
							<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
								Dear ".$nama.",<br><br>
								Terimakasih sudah bergabung di Member Discoin ".$merchant_coin."
								<br><br>
								COIN anda telah berhasil di aktifkan, dengan informasi akun dibawah ini<br><br>
								COIN 	  : ".$number."<br>
								Email     : ".$email."<br>
								Password  : ".$user_pass."<br>
								<br>
								<br>
								Fiuh..., tinggal selangkah lagi, Silahkan Klik Link dibawah ini atau <i>Paste</i>kan Dibrowser anda, Untuk mengaktifkan akun Discoin ".$merchant_coin." anda<br>
								<a href='".$dirhost."/".$merchant_app.".coin/activation/".$id_coin."/".$activation_code."' target='_blank'>
									".$dirhost."/".$merchant_app.".coin/activation/".$id_coin."/".$activation_code."
								</a>
								<br>
								<br>
								Terimakasih<br><br>
								<img src='".$dirhost."/templates/sempoa/img/beoro_logo.png'><br>
								<span style='color:#800040'><b>The Art Of Abacus Technology</b></span>
							</div>	";		
							sendmail($to,$subject,$msg,$from,$type);
							
							$subject_coin	= $nama." baru saja menjadi member Discoin ".$merchant_coin." ";
							$msg_coin		= "
							<div style='font-family:Verdana, Geneva, sans-serif; font-size:12px;'>
								".$nama." baru saja bergabung menjadi pelanggan ".$merchant_coin.", yang di support oleh Sempoa Discoin Community.
								<br>
								<br>
								Terimakasih<br><br>
								- info@sempoa.biz - <br><br>
								<img src='".$dirhost."/templates/sempoa/img/beoro_logo.png'><br>
								<span style='color:#800040'><b>The Art Of Abacus Technology</b></span>
							</div>		
							";
							$recipients 	= "thetakur@gmail.com"; //,indwic@gmail.com,junjungan70@gmail.com";
							sendmail($recipients,$subject_coin,$msg_coin,$from,$type);
		
							/*$recipient 	= "";
							$q_communities 	= $db->query("SELECT ID_COMMUNITY FROM ".$tpref."communities_merchants WHERE ID_CLIENT = '".$id_coin."'");
							while($dt_communities = $db->fetchNextObject($q_communities)){
								$q_communities_2 	= $db->query("
																SELECT 
																	a.ID_CLIENT,b.CLIENT_EMAIL 
																FROM 
																	".$tpref."communities_merchants a, 
																	".$tpref."clients b
																WHERE 
																	a.ID_CLIENT = b.ID_CLIENT AND
																	a.ID_COMMUNITY = '".$dt_communities->ID_COMMUNITY."'");
								while($dt_communities_2 = $db->fetchNextObject($q_communities_2)){
									if(!empty($dt_communities_2->CLIENT_EMAIL)){
										$recipient = $dt_communities_2->CLIENT_EMAIL;
										sendmail($recipient,$subject_coin,$msg_coin,$from,$type);
									}
								}
								
							}*/
						}
						
						
						$result['msg'] = "Terimakasih, Pendaftaran anda berhasil di simpan, periksa Email anda, untuk mengaktifkan Akun Member Discoin ".$dt_merchant->CLIENT_NAME." Ini";
											
					} 
				
				}else{
					$result['msg'] = "Maaf, Kode Aktifasi ini tidak ditemukan, silahkan hubungi ".$m_name." untuk nomor COIN yang valid";
				}
				
			}else{
				$result['msg'] = "Maaf,Email Ini Sudah Terdaftar";
			}
		}else{
				$result['msg'] = "Pengisian Form Belum Lengkap";
		}
		echo $callback.'('.json_encode($result).')';
	}
	
	if(!empty($direction) && $direction == "pengen_daftar"){
		$ip_address = $_SERVER['REMOTE_ADDR'];
		cuser_log("customer","0","Pengen daftar tapi Entah kenapa gak jadi",@$id_coin);
	}
	
}
?>

