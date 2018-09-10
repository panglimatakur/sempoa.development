<?php
//print_r($_SESSION);
if((!empty($_REQUEST['logout']) && $_REQUEST['logout']=="true")){
	session_destroy();
	redirect_page($dirhost);
}
//RECHANGE ACCOUNT//
if(!empty($_REQUEST['first_acc']) && $_REQUEST['first_acc'] == "true"){
	unset($_SESSION['childkey']);
	$_SESSION['cidkey']		= $_SESSION['ori_cidkey'];
	$_SESSION['uclevelkey'] = $_SESSION['ori_uclevelkey'];
	$_SESSION['cparentkey']	= $_SESSION['ori_cparentkey'];
	$new_account			= $_SESSION['ori_cidkey'];
}
if(!empty($_REQUEST['form_cabang'])){
	unset($_SESSION['childkey'],$_SESSION['all']);
	if($_REQUEST['form_cabang'] == "all")	{ 
		$new_account			= $_SESSION['cidkey']; 		
	}
	else{ 
		$_SESSION['cidkey'] 	= $_REQUEST['form_cabang'];
		$_SESSION['uclevelkey'] = 3;
		if($_SESSION['ori_uclevelkey'] == 2){
			$_SESSION['cparentkey']	= $_SESSION['ori_cidkey'];
		}
		$new_account 			= $_REQUEST['form_cabang']; 
	}
}

if(!empty($_REQUEST['form_cabang']) || (!empty($_REQUEST['first_acc']) && $_REQUEST['first_acc'] == "true")){
	$q_list 			= $db->query("SELECT DISTINCT(ID_CLIENT) FROM ".$tpref."clients WHERE CLIENT_ID_PARENT_LIST LIKE '%,".$new_account."%'");
	$num_list			= $db->numRows($q_list);
	if($num_list > 1){
		$_SESSION['all']= "true";
		if($_SESSION['all'] == "true" && (!empty($_REQUEST['form_cabang']) && $_REQUEST['form_cabang'] == "all")){
			$id_list	= "";
			while($dt_list = $db->fetchNextObject($q_list)){
				$id_list .= ",".$dt_list->ID_CLIENT;
			}
			$_SESSION['childkey']= $id_list;
		}
	}
	redirect_page($dirhost."/?page=beranda");
}

//END OF RECHANGE ACCOUNT//
//echo @$_COOKIE['username']." cookie";
if(!empty($_COOKIE['username']) && !empty($_COOKIE['password'])){
	$direction 		= "login";
	$username 		= $_COOKIE['username']; 
	$password 		= $_COOKIE['password']; 
}

if(!empty($direction) && $direction == "login"){
	if(!empty($_REQUEST['username']))		{ $username 		= $sanitize->str($_REQUEST['username']); 		}
	if(!empty($_REQUEST['password']))		{ $password 		= $sanitize->str($_REQUEST['password']); 		}
	if(!empty($_REQUEST['login_remember']))	{ $login_remember 	= $sanitize->str($_REQUEST['login_remember']); 	}
	
	if(!empty($username) && !empty($password)){
		$query_login 					= $db->query("SELECT * FROM system_users_client WHERE USER_EMAIL = '".$username."' AND USER_PASS ='".$password."'");
		$num_login						= $db->numRows($query_login);
		if($num_login > 0){
			
			if(!empty($login_remember)){
				$date_of_expiry 		= time() + 60 ;
				setcookie( "username", $username, $date_of_expiry);
				setcookie( "password", $password, $date_of_expiry);
			}else{
				$date_of_expiry 		= time() - 60 ;
				setcookie( "username",$username, $date_of_expiry, "/", $dirhost);	
				setcookie( "password",$password, $date_of_expiry, "/", $dirhost);	
			}
			
			$data_login 				= $db->fetchNextObject($query_login);
			$q_client					= $db->query("SELECT 
														ID_CLIENT,
														ID_CLIENT_LEVEL,
														CLIENT_COIN,
														CLIENT_ID_PARENT,
														CLIENT_NAME,
														CLIENT_APP,
														BY_ID_PURPLE 
													  FROM 
													  	".$tpref."clients 
													  WHERE ID_CLIENT='".$data_login->ID_CLIENT."'");
			$dt_client					= $db->fetchNextObject($q_client);
			
			$_SESSION['cidkey']			= $dt_client->ID_CLIENT;
			$_SESSION['ori_cidkey']		= $dt_client->ID_CLIENT;
			$_SESSION['uclevelkey']		= $dt_client->ID_CLIENT_LEVEL;
			$_SESSION['ori_uclevelkey']	= $dt_client->ID_CLIENT_LEVEL;
			@$_SESSION['cparentkey']	= $dt_client->CLIENT_ID_PARENT;
			@$_SESSION['ori_cparentkey']= $dt_client->CLIENT_ID_PARENT;
			$_SESSION['app'	]			= $dt_client->CLIENT_APP;
			$_SESSION['ccoin']			= $dt_client->CLIENT_COIN;
			@$_SESSION['cname']			= $dt_client->CLIENT_NAME;
			if(!empty($dt_client->BY_ID_PURPLE)){ $_SESSION['id_purple']		= $dt_client->BY_ID_PURPLE; }
			
			$_SESSION['ulevelkey']		= $data_login->ID_CLIENT_USER_LEVEL;
			$_SESSION['levelname']		= $db->fob("NAME","system_master_client_users_level"," 
													WHERE 
														ID_CLIENT_USER_LEVEL = '".$data_login->ID_CLIENT_USER_LEVEL."'");
			$_SESSION['uidkey']			= $data_login->ID_USER;
			$_SESSION['username']		= $username;
			$_SESSION['loginname']		= $data_login->USER_NAME;
			if($dt_client->ID_CLIENT == 1 && $data_login->ID_USER == 1){
				$_SESSION['admin_only']= "true";
			}else{
				$_SESSION['admin_only']= "false";
			}
			
			
			if($data_login->INSERT_DATA > 0){ $_SESSION['insert']		= $data_login->INSERT_DATA; }
			if($data_login->EDIT_DATA > 0)	{ $_SESSION['edit']			= $data_login->EDIT_DATA;	}
			if($data_login->DELETE_DATA > 0){ $_SESSION['delete']		= $data_login->DELETE_DATA;	}
			
			$str_community				= " SELECT 
												a.BY_ID_PURPLE, 
												b.ID_COMMUNITY 
											FROM 
												".$tpref."communities a, 
												".$tpref."communities_merchants b 
											WHERE 
												b.ID_CLIENT = '".$data_login->ID_CLIENT."' AND
												a.ID_COMMUNITY = b.ID_COMMUNITY
											ORDER BY a.BY_ID_PURPLE ASC";
			//echo $str_community;
			$q_community 				= $db->query($str_community);
			@$num_community				= $db->numRows($q_community);
			$join 						= array();
			$id_purples 				= array();
			if($num_community > 0){
					while($dt_join = $db->fetchNextObject($q_community)){
						$join[] 		= $dt_join->ID_COMMUNITY;	
					}
			}
			$_SESSION['comidkey']	= $join;
			//CHECK CHILDREN//
			@$num_list 					= $db->recount("SELECT DISTINCT(ID_CLIENT),CLIENT_ID_PARENT FROM ".$tpref."clients WHERE CLIENT_ID_PARENT_LIST LIKE '%,".$data_login->ID_CLIENT."%'");
			if($num_list > 1){
				$_SESSION['all'] = "true";
			}
			
			@$ranks 					= rank_formula($data_login->ID_CLIENT);
			$_SESSION['ranks']			= $ranks;
			//END OF CHECK CHILDREN//
			redirect_page($dirhost."/?module=cpanel&page=profil_pengguna");
		}else{
			$msg = 1;
		}
	}else{
		$msg = 2;
	}
}

//$table = $db->query("SHOW TABLES");
if(!empty($_SESSION['uidkey'])){
	
}else{
	include $call->inc("zendback/templates/admin-v2.1","login.php");
}
?>
