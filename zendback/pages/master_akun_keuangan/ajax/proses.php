<?php
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	session_start();
	define('mainload','SEMPOA',true);
	include_once("../../../../includes/config.php");
	include_once("../../../../includes/classes.php");
	include_once("../../../../includes/declarations.php");
	
	$direction 	= isset($_REQUEST['direction']) 	? $sanitize->str($_REQUEST['direction']) 			: "";
	$no 		= isset($_REQUEST['no']) 			? $sanitize->number($_REQUEST['no']) 				: "";
	$id_root 	= isset($_REQUEST['id_root']) 		? $sanitize->number($_REQUEST['id_root']) 			: "";
	$parent_id 	= isset($_REQUEST['parent_id']) 	? $sanitize->number($_REQUEST['parent_id']) 		: "";
	$nama		= isset($_REQUEST['nama']) 			? $sanitize->str(ucwords($_REQUEST['nama']))		: "";
	$is_folder	= isset($_REQUEST['is_folder'])		? $sanitize->number($_REQUEST['is_folder'])			: "";
	
	if(!empty($direction) && $direction == "delete"){ 
		$db->delete($tpref."cash_type","WHERE ID_CASH_TYPE='".$no."'");
		$db->delete($tpref."cash_type","WHERE ID_PARENT='".$no."'");
	}
	
	if(!empty($direction) && ($direction == "save" || $direction == "insert")){
		$tblnya = $tpref."cash_type";
		$result	= array();
		if(!empty($parent_id) && !empty($nama) && !empty($is_folder)){
			
			if($direction == "insert"){
				$content = array(1=>
							array("ID_CLIENT",$_SESSION['cidkey']),
							array("NAME",$nama),
							array("ID_PARENT",@$parent_id),
							array("IN_OUT",@$id_root),
							array("IS_FOLDER",@$is_folder),
							array("BY_ID_USER",$_SESSION['uidkey']),
							array("TGLUPDATE",$tglupdate),
							array("WKTUPDATE",$wktupdate)
							);
				$db->insert($tblnya,$content);
			}	
				
	
			if($direction == "save"){	
				$content = array(1=>
							array("ID_CLIENT",$_SESSION['cidkey']),
							array("NAME",$nama),
							array("ID_PARENT",@$parent_id),
							array("IN_OUT",@$id_root),
							array("IS_FOLDER",@$is_folder),
							array("BY_ID_USER",$_SESSION['uidkey']),
							array("TGLUPDATE",$tglupdate),
							array("WKTUPDATE",$wktupdate)
						   );
				$db->update($tblnya,$content,"WHERE ID_CASH_TYPE = '".$no."'");
			}
			$result['nama']			= $nama;
			if($is_folder == 1)	{ $class	= "file";	}
			else				{ $class	= "folder";	}
			$result['class'] 	= $class;
			$result['msg'] 			= 2;
		}
		else{
			$result['msg'] 	= 1;
		}
	}
	echo json_encode($result);; 
}
else{  defined('mainload') or die('Restricted Access'); }
?>
