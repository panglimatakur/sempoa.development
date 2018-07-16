<?php defined('mainload') or die('Restricted Access'); ?>
<?php
$cond = "";
if($_SESSION['uclevelkey'] != 1 && $_SESSION['cidkey'] != 1){ $cond = "AND ID_CLIENT='".$_SESSION['cidkey']."'"; }


//LAST SUBJECT 
$q_last_subject 	= $db->query("SELECT * FROM ".$tpref."chat_attribute WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND CHAT_SRC = 'VISITOR' ORDER BY ORDER_DTIME DESC");
$dt_last_subject	= $db->fetchNextObject($q_last_subject);
@$last_visitor 		= $dt_last_subject->ID_PARENT;
@$last_subject  	= $dt_last_subject->ID_CHAT_ATTRIBUTE;

//INPUT TO CONTACT / VISITOR_CONTACT
//INPUT TO CHAT_ATTRIBUT "CHAT_SRC = 'VISITOR'"
//INPUT TO CHAT


//TIPE PEMBELI
/*
	- SURFER 	-  Orang yang cuma berkunjung tapi tidak meninggalkan kontak atau informasi apa-apa
	- VISITOR	-  Orang yang cuma berkunjung dan meninggalkan kontak atau informasi
	- MEMBER	-  Customer dari merchant lain tapi anggota komunitas yang sama
	- CUSTOMER	-  Customer dari merchant lain tapi beda komunitas
	- FRIEND 	-  Customer Sendiri
	- USER		-  Pengelola Sempoa dari Merchant-merchant lain atau 
*/
//TIPE KARYAWAN
/*
	- Karyawan merchant dari beda komunitas
	- Karyawan merchant dari sesama Komunitas
	- Karyawan merchant internal
*/

$str_vis_chat 		= "SELECT 
					   		a.ID_CLIENT,
							a.ID_VISITOR, 
							a.VISITOR_NAME
					   FROM 
					   		cat_clients_visitors a,
							cat_chat b 
					   WHERE 
					   		a.ID_CLIENT 			= b.ID_CLIENT AND 
							a.ID_CLIENT_VISITOR 	= b.ID_SENDER AND 
							a.ID_CLIENT 			= '".$_SESSION['cidkey']."' 
					   GROUP BY 
							a.ID_CLIENT_VISITOR 
					   ORDER BY 
					   	b.UPDATEDATETIME DESC";
$q_vis_chat 		= $db->query($str_vis_chat);	
@$num_vis_chat 		= $db->numRows($q_vis_chat);

$str_vis_list 		= "SELECT * FROM ".$tpref."clients_visitors WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND VISITOR_NAME IS NOT NULL AND ID_CLIENT_VISITOR NOT IN (SELECT ID_PARENT FROM cat_chat_attribute WHERE CHAT_SRC = 'VISITOR') ORDER BY VISITOR_NAME ASC LIMIT 0,100";
$q_vis_list		= $db->query($str_vis_list);
@$num_vis_list 	= $db->numRows($q_vis_list);




$str_chat 	= "SELECT * FROM ".$tpref."chat 
			   WHERE ID_CLIENT = '".$_SESSION['cidkey']."' AND
					 ID_CHAT_ATTRIBUTE = '".$last_subject."'
					 ".$cond." 
			   ORDER BY 
			   		 UPDATEDATETIME ASC LIMIT 0,100";
$q_chat		= $db->query($str_chat);

?>