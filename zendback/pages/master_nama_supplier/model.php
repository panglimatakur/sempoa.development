<?php defined('mainload') or die('Restricted Access'); ?>
<?php
	$condition = "";
	if(!empty($direction) && $direction == "show"){
		if(!empty($_REQUEST['nama_report']))		{ $condition 	.= " AND PARTNER_NAME 			LIKE '%".$nama_report."%'"; 	}
		if(!empty($_REQUEST['propinsi_report']))	{ $condition 	.= " AND PARTNER_PROVINCE 		= '".$propinsi_report."'"; 	}
		if(!empty($_REQUEST['kota_report']))		{ $condition 	.= " AND PARTNER_CITY 			= '".$kota_report."'"; 		}
		if(!empty($_REQUEST['alamat_report']))		{ $condition 	.= " AND PARTNER_ADDRESS 		LIKE '%".$alamat_report."%'"; 	}
		if(!empty($_REQUEST['tlp_report']))			{ $condition 	.= " AND PARTNER_PHONE 			LIKE '%".$tlp_report."%'"; 	}
		if(!empty($_REQUEST['kontak_report']))		{ $condition 	.= " AND PARTNER_PERSON_CONTACT LIKE '%".$kontak_report."%'"; 	}
		if(!empty($_REQUEST['email_report']))		{ $condition 	.= " AND PARTNER_EMAIL 			LIKE '%".$email_report."%'"; 	}
		if(!empty($_REQUEST['website_report']))		{ $condition 	.= " AND PARTNER_URL 			LIKE '%".$website_report."%'"; }
		$class_report = "active";
	}else{
		$class_proses = "active";
	}
	$str_query 			= "SELECT * FROM ".$tpref."partners WHERE ID_CLIENT='".$_SESSION['cidkey']."' ".$condition."";
	$link_str			= $lparam;
	$q_partner 			= $db->query($str_query ." ".$limit);
	$num_partner		= $db->numRows($q_partner);
	
	
	
	if(!empty($direction) && $direction == "edit"){
		$q_partner_edit = $db->query("SELECT * FROM ".$tpref."partners WHERE ID_PARTNER='".$no."' AND ID_CLIENT='".$_SESSION['cidkey']."'");
		$dt_partner_edit= $db->fetchNextObject($q_partner_edit);
		
		$nama 			= $dt_partner_edit->PARTNER_NAME; 		
		$propinsi 		= $dt_partner_edit->PARTNER_PROVINCE; 	
		$kota 			= $dt_partner_edit->PARTNER_CITY; 		
		$alamat 		= $dt_partner_edit->PARTNER_ADDRESS; 		
		$tlp 			= $dt_partner_edit->PARTNER_PHONE; 		
		$kontak 		= $dt_partner_edit->PARTNER_PERSON_CONTACT; 		
		$email 			= $dt_partner_edit->PARTNER_EMAIL; 		
		$website 		= $dt_partner_edit->PARTNER_URL; 		
	}
?>