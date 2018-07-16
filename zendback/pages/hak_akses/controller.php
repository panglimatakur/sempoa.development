<?php defined('mainload') or die('Restricted Access'); ?>
<?php

$single			   	= isset($_REQUEST['single'])    ? $sanitize->str($_REQUEST['single']) 						:"0";
$client_id 			= isset($_REQUEST['client_id']) ? $sanitize->number($_REQUEST['client_id'])			  		:"0";
$id_client_level 	= isset($_REQUEST['id_client_level']) ? $sanitize->number($_REQUEST['id_client_level'])		:"0";
$id_client_user_level= isset($_REQUEST['id_client_user_level']) ? $sanitize->number($_REQUEST['id_client_user_level']):"0";
$jmlpage 			= isset($_REQUEST['jmlpage']) ? $sanitize->number($_REQUEST['jmlpage'])			  			:"0";

//-----> PROSES INSERT

if(!empty($direction) && $direction == "insert"){
	if(!empty($id_client_level) && !empty($id_client_user_level)){
		foreach($_REQUEST['c_id'] as &$cid){

			$i = 0;
			$db->delete("system_pages_client_rightaccess","WHERE ID_CLIENT='".$cid."' AND ID_CLIENT_LEVEL='".$id_client_level."' AND ID_CLIENT_USER_LEVEL='".$id_client_user_level."'");
			
			//echo "HAPUS DULU SEMUA <b>DELETE system_pages_client_rightaccess WHERE ID_CLIENT='".$cid."' AND ID_CLIENT_LEVEL='".$id_client_level."' AND ID_CLIENT_USER_LEVEL='".$id_client_user_level."'</b><br><br>";
			
			$ori_page_id = $_REQUEST['ori_page_id'];
			foreach($ori_page_id as &$page){
				$i++;
				$str_ch_user = "SELECT * FROM system_pages_client_rightaccess 
										WHERE 
											ID_CLIENT='".$cid."' AND 
											ID_PAGE_CLIENT='".$page."' AND 
											ID_CLIENT_LEVEL='".$id_client_level."' AND 
											ID_CLIENT_USER_LEVEL='".$id_client_user_level."'";				
				$chuser 	 = $db->recount($str_ch_user);
				
				if($chuser == 0){
					if(isset($_REQUEST['id_halaman'][$i])){
						//echo "Asupkan ".$_REQUEST['id_halaman'][$i]."<br>";
						
						$container = array(1=>
							array("ID_CLIENT",$cid),
							array("ID_PAGE_CLIENT",$_REQUEST['id_halaman'][$i]),
							array("ID_CLIENT_LEVEL",$id_client_level),
							array("ID_CLIENT_USER_LEVEL",$id_client_user_level));
						 $db->insert("system_pages_client_rightaccess",$container);
					}
				}
			}
				
		}
		
		$msg = 1;
	}
	else{
		$msg = 2;
	}
}
?>