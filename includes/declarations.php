<?php defined('mainload') or die('Restricted Access'); ?>
<?php
if(isset($_REQUEST['direction'])) 	{	$direction 	= $sanitize->str($_REQUEST['direction']); 	}
if(isset($_REQUEST['pagesize'])) 	{	$pagesize 	= $sanitize->number($_REQUEST['pagesize']); }

if(isset($_REQUEST['page'])) 		{	$page 		= $sanitize->str($_REQUEST['page']); 		}
if(isset($_REQUEST['module'])) 	  	{	$module 	= $sanitize->str($_REQUEST['module']); 		}
if(isset($_REQUEST['spage'])) 	   	{	$spage 	  	= str_replace(".spa","",$sanitize->str($_REQUEST['spage'])); 		}
if(isset($_REQUEST['parameters']))  {	$parameters = $sanitize->str($_REQUEST['parameters']); 		}

if(isset($_REQUEST['idpage'])) 		{	$idpage 	= $sanitize->str($_REQUEST['idpage']); 		}
if(isset($_REQUEST['no'])) 			{	$no 		= $sanitize->str($_REQUEST['no']); 			}
if(isset($_REQUEST['msg'])) 		{	$msg 		= $sanitize->str($_REQUEST['msg']); 		}

if(!empty($_SESSION['uclevelkey'])){
	if($_SESSION['uclevelkey'] == 2 || $_SESSION['uclevelkey'] == 1){ $id_client = $_SESSION['cidkey']; 	}
	else							{ $id_client = $_SESSION['cparentkey']; }
}
//TEMPLATES RESOURCE
$img_dir_tpl	= "templates/images";
$tpl_css		= "templates/css";
$tpl_js			= "templates/js";

$backend_dir	= $dirhost."/zendback/";
$frontend_dir	= $dirhost."/zendfront/";

$templates 		= $dirhost."/templates/";

$dicoin_tpl_dir	= $frontend_dir."templates/discoin/";

$web_ftpl_dir	= $frontend_dir."templates/".$web_template."/";
$web_btpl_dir	= $backend_dir."templates/admin-v2.1/";

//FILE DIRECTORY
$user_file		= "files";
$img_dir		= "files/images";
$discoin_folder	= "files/images/icons/discoin/";
$user_foto_dir	= "files/images/users";
$prod_dir		= "files/images/products/".@$id_client;
$file_dir		= "files/documents";
$plugins_dir	= $dirhost."/libraries";

if(!empty($_SESSION['uidkey'])){
	if(empty($page)){ $page = "profil_pengguna"; }
	$q_pages		= $db->query("SELECT ID_PAGE_CLIENT,TITLE FROM system_pages_client WHERE PAGE='".@$page."'");
	$dt_pages		= $db->fetchNextObject($q_pages);
	@$id_page 		= $dt_pages->ID_PAGE_CLIENT;
	@$page_title 	= $dt_pages->TITLE;
	@$page_dir 		= "zendback/pages/".$page;
	
	if(!empty($_SESSION['cidkey'])){
		@$cash 			= $db->fob("CASH_RESIDUAL_VALUE",$tpref."cash_flow_history"," WHERE ID_CLIENT='".$_SESSION['cidkey']."' ORDER BY ID_CASH_FLOW_HISTORY DESC");
	}
}else{
	
	if(empty($page)){ $page = "beranda"; }
	
	if(!empty($page) && $page == "statis"){
		$q_pages = $db->query("SELECT ID_PAGE_DISCOIN,TITLE,TYPE,KEYWORDS,DESCRIPTIONS FROM system_pages_discoin WHERE PAGE='".@$parameters."'");
	}else{
		if(!empty($page) && $page == "artikel" && !empty($parameters)){
			$q_pages = $db->query("SELECT TITLE,KEYWORDS,DESCRIPTIONS FROM ".$tpref."posts WHERE ID_POST='".@$parameters."'");
		}else{
			$q_pages = $db->query("SELECT ID_PAGE_DISCOIN,TITLE,TYPE,KEYWORDS,DESCRIPTIONS FROM system_pages_discoin WHERE PAGE='".@$page."'");
		}
	}
	$dt_pages	   = $db->fetchNextObject($q_pages);
	if((!empty($page) && $page == "artikel" && empty($parameters)) || !empty($page)){
		@$id_page 	   = $dt_pages->ID_PAGE_DISCOIN;
		@$page_type 	= $dt_pages->TYPE;
	}
	@$page_dir 		= "zendfront/pages/".@$page;
	@$page_title   = $dt_pages->TITLE;
	@$keywords 	   = $dt_pages->KEYWORDS;
	@$description  = $dt_pages->DESCRIPTIONS;
	
}
//ON SYSTEM




//MODULES
@$ajax_dir	   	= $page_dir."/ajax";
@$js_dir		 = $page_dir."/js/js.js";
@$css_dir		= $page_dir."/css/style.css";
@$inc_dir		= $page_dir."/includes";
$lparam		  	= $dirhost."?page=".@$page;
$blnarray		= array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
$thisyear		= date("Y");

$user_agent     	= $_SERVER['HTTP_USER_AGENT'];
$user_os     		= getOS($user_agent);
$ip_address 		= $_SERVER['REMOTE_ADDR'];

function relogin($id_merchant,$id_customer){
	global $db;
	global $tpref;
	global $dirhost;
	global $validate;
	global $activated;
	global $tglupdate;
	
	//CHECK MERCHANT INFO
	$q_merchant  		= $db->query("SELECT CLIENT_NAME,CLIENT_LOGO,CLIENT_EMAIL,AFFILIATE_FLAG,COLOUR FROM ".$tpref."clients WHERE ID_CLIENT='".$id_merchant."'"); 
	$dt_merchant 		= $db->fetchNextObject($q_merchant);
	$color				= explode(";",$dt_merchant->COLOUR);
	$color_1 			= $color[0]; 
	$color_2 			= $color[1];
	if(empty($color_1))	{ $color_1 = "#993366"; }
	if(empty($color_2))	{ $color_2 = "#732b4f"; }
	$m_name				= $dt_merchant->CLIENT_NAME;
	//END OF CHECK MERCHANT INFO
		
	//CHECK CUSTOMER INFO
	$str_login 			= "SELECT * FROM ".$tpref."customers WHERE ID_CUSTOMER = '".$id_customer."' ".$log_condition." ";
	$query_login 		= $db->query($str_login);
	$num_login			= $db->numRows($query_login);
	//END OF CHECK CUSTOMER INFO

	$data_logins 		= $db->fetchNextObject($query_login);
	$customer_status 	= $data_logins->CUSTOMER_STATUS;
	$today 				= $tglupdate;
	if($data_logins->EXPIRATION_DATE == $tglupdate || $data_logins->EXPIRATION_DATE < $tglupdate){
		
		$expired_msg = "<span style='font-size:12px; text-align:justify'>Maaf, keanggotaan Discoin ".$m_name." telah berakhir, silahkan menghubungi ".$m_name." langgananmu, atau email ".$dt_merchant->CLIENT_EMAIL.", untuk mendapatkan Kode Aktifasi perpanjangan keanggotaan</span><br>
	<div> 
		<input type='text' id='activated' value='' placeholder='NEW ACTIVATION COIN' style='font-size:16px; text-align:center:text-transform:uppercase'>
	</div>
	<div>
		<img src='".$dirhost."/templates/sempoa/img/beoro_logo.png' style='width:40%'>
	</div>";
		
		switch($customer_status){
			case "0":
			$db->query("UPDATE ".$tpref."customers SET EXPIRATION_DATE='".$expired_date."',CUSTOMER_STATUS = '3' WHERE ID_CUSTOMER = '".$data_logins->ID_CUSTOMER."'");
			
			$result['msg_log'] = "Terimakasih, kamu telah resmi menjadi member ".$m_name.", untuk pertanyaan tentang penggunaan aplikasi Discoin ini, silahkan kirim email ke support@".$website_name.", silahkan login kembali.			
		<img src='".$dirhost."/templates/sempoa/img/beoro_logo.png' style='width:40%;'>";
			$result['io_log'] 	  = 0;
			break;
			case "1";
				$result['msg_log'] = $expired_msg;
				$result['io_log']  = 2;
			break;
			case "3":
				$db->query("UPDATE ".$tpref."customers SET CUSTOMER_STATUS = '1' WHERE ID_CUSTOMER = '".$data_logins->ID_CUSTOMER."'");
				$result['msg_log'] = $expired_msg;
				$result['io_log']  = 2;
			break;
			
		}
		
	}else{
		
		if($data_logins->CUSTOMER_STATUS == 3){
			$m = 0;
			//REGISTER SESSIONS
			$titanium	= $data_logins->ID_CLIENT_TITANIUM;
			if($titanium == "0")		{ $log_condition = "AND ID_CLIENT='".$id_merchant."'"; 			}
			if($titanium > "0")			{ $log_condition = "AND ID_CLIENT='1' AND INTERNAL_FLAG = '2'"; }
			if($titanium == "0" && 
			   $id_merchant == "1") { $log_condition = "AND ID_CLIENT='1' AND INTERNAL_FLAG = '1'"; }
			
			if($titanium > "0") { $_SESSION['titanium']	= "true";	}
			else				{ $_SESSION['titanium']	= "false";	}
			
			$merchant_join 			= $db->query("SELECT ID_COMMUNITY FROM ".$tpref."communities_merchants WHERE ID_CLIENT='".$data_logins->ID_CLIENT."' ");
			$num_merchant_join		= $db->numRows($merchant_join);
			$com_join				= "";
			while($dt_join 			= $db->fetchNextObject($merchant_join)){ 
				$nm_com 	= $db->fob("NAME",$tpref."communities"," WHERE ID_COMMUNITY='".$dt_join->ID_COMMUNITY."'");
				$com_join.= "<span style='color:#990000'>".$nm_com."</span> | "; 
			}
			
			$_SESSION['scomidkey'] 	= $com_join;	
			$_SESSION['sidkey']	    = $data_logins->ID_CUSTOMER;
			$_SESSION['susername']	= $data_logins->CUSTOMER_USERNAME;
			$_SESSION['spassword']	= $data_logins->CUSTOMER_PASS;
			$_SESSION['cust_name']	= $data_logins->CUSTOMER_NAME;
			if(empty($data_logins->CUSTOMER_NAME)){
				$_SESSION['cust_name']	= "Belum Kenalan";
			}
			$_SESSION['csidkey']	= $data_logins->ID_CLIENT;
			$_SESSION['color_1']	= $color_1;
			$_SESSION['color_2']	= $color_2; 
			$result['msg_log'] 		= "";
			$result['io_log'] 		= "";
			//END OF REGISTER SESSIONS
			//cuser_log("customer",$data_logins->ID_CUSTOMER,"Login",$data_logins->ID_CLIENT);						
		}else{
			$result['msg_log'] 		= "Maaf akun Discoin ".@$m_name." anda belum diaktifkan, silahkan medaftar terlebih dahulu";
			$result['io_log'] 		= 0;
		}
	}

	return $result;
}

?>
